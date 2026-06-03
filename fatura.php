<?php
// fatura.php
require_once __DIR__ . '/src/auth.php';
require_once __DIR__ . '/src/layout.php';
requireAuth();

if (!isset($_GET['id'])) {
    header('Location: cartoes.php');
    exit;
}
$cartao_id = intval($_GET['id']);

renderHeader("Fatura do Cartão");
?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <a href="cartoes.php" class="text-slate-500 hover:text-slate-700 dark:hover:text-white mb-6 inline-flex items-center gap-2 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Voltar para Cartões
    </a>

    <!-- Header da Fatura -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 mb-8 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2" id="cartaoNome">
                    Carregando...
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1" id="cartaoInfo">Vencimento: --/--/---- • Fechamento: --/--/----</p>
            </div>
            
            <div class="text-left sm:text-right flex flex-col items-start sm:items-end">
                <div class="flex items-center gap-2 mb-1">
                    <p class="text-slate-500 dark:text-slate-400 text-sm uppercase tracking-wider font-bold">Total da Fatura</p>
                    <span id="faturaStatus" class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">ABERTA</span>
                </div>
                <div class="text-3xl font-black text-blue-600 dark:text-blue-500 mb-3" id="faturaTotal">R$ 0,00</div>
                
                <button id="btnPagarFatura" onclick="pagarFatura()" class="hidden bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-bold text-sm transition-colors shadow-lg shadow-green-500/30 flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Marcar como Paga
                </button>
            </div>
        </div>
        
        <!-- Background decoration -->
        <div class="absolute -right-10 -bottom-10 text-slate-100 dark:text-slate-700/30 rotate-12 pointer-events-none">
            <i class="fa-solid fa-file-invoice-dollar text-[12rem]"></i>
        </div>
    </div>

    <!-- Filtro de Mês -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Compras Lançadas</h2>
        <input type="month" id="faturaMes" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 font-medium shadow-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer">
    </div>

    <!-- Lista de Compras -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="divide-y divide-slate-100 dark:divide-slate-700/50" id="faturaList">
            <div class="p-8 text-center text-slate-500">
                <i class="fa-solid fa-spinner fa-spin text-2xl mb-3 text-blue-500"></i>
                <p>Carregando fatura...</p>
            </div>
        </div>
    </div>
</div>

<script>
    const cartaoId = <?php echo $cartao_id; ?>;
    const mesInput = document.getElementById('faturaMes');
    
    // Set initial month
    const today = new Date();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const yyyy = today.getFullYear();
    mesInput.value = `${yyyy}-${mm}`;

    mesInput.addEventListener('change', loadFatura);

    let currentFaturaData = null;

    async function loadFatura() {
        const list = document.getElementById('faturaList');
        list.innerHTML = '<div class="p-8 text-center text-slate-500"><i class="fa-solid fa-spinner fa-spin text-2xl mb-3 text-blue-500"></i><p>Carregando...</p></div>';
        
        try {
            const res = await fetch(`api/get_fatura_detalhes.php?id=${cartaoId}&mes=${mesInput.value}`);
            const data = await res.json();
            
            if (data.error) {
                list.innerHTML = `<div class="p-8 text-center text-red-500 font-bold">${data.error}</div>`;
                return;
            }
            
            currentFaturaData = data;

            // Atualizar cabeçalho
            document.getElementById('cartaoNome').innerHTML = `<i class="fa-regular fa-credit-card text-blue-500"></i> ${data.cartao.nome}`;
            
            const [vAno, vMes, vDia] = data.data_vencimento.split('-');
            const [fAno, fMes, fDia] = data.data_fechamento.split('-');
            
            document.getElementById('cartaoInfo').innerText = `Vencimento: ${vDia}/${vMes}/${vAno} • Fechamento: ${fDia}/${fMes}/${fAno}`;
            document.getElementById('faturaTotal').innerText = `R$ ${parseFloat(data.total).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;

            // Status e Botão Pagar
            const statusBadge = document.getElementById('faturaStatus');
            const btnPagar = document.getElementById('btnPagarFatura');
            
            if (data.compras.length === 0) {
                statusBadge.innerText = "VAZIA";
                statusBadge.className = "text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500";
                btnPagar.classList.add('hidden');
            } else if (data.is_paga) {
                statusBadge.innerText = "PAGA";
                statusBadge.className = "text-xs font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-600";
                btnPagar.classList.add('hidden');
            } else {
                statusBadge.innerText = "ABERTA";
                statusBadge.className = "text-xs font-bold px-2 py-0.5 rounded-full bg-orange-100 text-orange-600";
                btnPagar.classList.remove('hidden');
            }

            // Renderizar Lista
            if (data.compras.length === 0) {
                list.innerHTML = `
                    <div class="p-12 text-center text-slate-400">
                        <i class="fa-solid fa-bag-shopping text-4xl mb-3 opacity-20"></i>
                        <p>Nenhuma compra encontrada nesta fatura.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            data.compras.forEach(c => {
                const [cAno, cMes, cDia] = c.data.split('-');
                const valStr = parseFloat(c.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                
                html += `
                    <div class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 flex-shrink-0">
                                <i class="fa-solid fa-tag"></i>
                            </div>
                            <div>
                                <h4 class="text-slate-800 dark:text-white font-bold leading-tight">${c.descricao}</h4>
                                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-md font-medium">${c.categoria}</span>
                                    <span>${cDia}/${cMes}/${cAno}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-slate-800 dark:text-white font-bold whitespace-nowrap">R$ ${valStr}</span>
                            <button onclick="excluirCompraFatura(${c.id})" class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-red-500 transition-all p-2">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;

        } catch (e) {
            list.innerHTML = `<div class="p-8 text-center text-red-500 font-bold">Erro de conexão</div>`;
        }
    }

    async function pagarFatura() {
        if (!currentFaturaData) return;
        if (!confirm(`Deseja marcar a fatura de ${currentFaturaData.fatura_mes} no valor de R$ ${currentFaturaData.total.toLocaleString('pt-BR', {minimumFractionDigits: 2})} como PAGA?`)) return;

        const btn = document.getElementById('btnPagarFatura');
        const oldHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processando...';
        btn.disabled = true;

        try {
            const res = await fetch('api/pagar_fatura.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    cartao_id: cartaoId,
                    data_inicio: currentFaturaData.data_inicio,
                    data_fim: currentFaturaData.data_fechamento
                })
            });

            const data = await res.json();
            if (data.success) {
                if (typeof showToast !== 'undefined') showToast('Fatura marcada como Paga!');
                loadFatura();
            } else {
                alert(data.error);
                btn.innerHTML = oldHtml;
                btn.disabled = false;
            }
        } catch (e) {
            alert('Erro ao pagar fatura');
            btn.innerHTML = oldHtml;
            btn.disabled = false;
        }
    }

    async function excluirCompraFatura(id) {
        if (!confirm('Deseja excluir esta compra da fatura? Isso não pode ser desfeito.')) return;
        
        try {
            const res = await fetch('api/delete_despesa.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id: id })
            });
            const data = await res.json();
            if (data.success) {
                if (typeof showToast !== 'undefined') showToast('Compra excluída com sucesso!');
                loadFatura();
            } else {
                alert(data.error || 'Erro ao excluir');
            }
        } catch (e) {
            alert('Erro de conexão ao excluir');
        }
    }

    document.addEventListener('DOMContentLoaded', loadFatura);
</script>

<?php
renderFooter();
?>
