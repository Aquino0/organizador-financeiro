<?php
// cartoes.php
require_once __DIR__ . '/src/auth.php';
require_once __DIR__ . '/src/layout.php';
requireAuth();

renderHeader("Meus Cartões");
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Cartões de Crédito</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Gerencie seus cartões e faturas</p>
        </div>
        <button onclick="openCardModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-colors shadow-lg shadow-blue-500/30 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Cartão
        </button>
    </div>

    <!-- Lista de Cartões -->
    <div id="cardsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Renderizados via JS -->
    </div>
</div>

<!-- Modal Novo Cartão -->
<div id="cardModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Novo Cartão</h2>
            <button onclick="closeCardModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="cardForm" onsubmit="saveCard(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nome do Cartão (Ex: Nubank)</label>
                    <input type="text" name="nome" required class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Limite (R$)</label>
                    <input type="number" step="0.01" name="limite" required class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 outline-none focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dia Fechamento</label>
                        <input type="number" min="1" max="31" name="fechamento" required class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dia Vencimento</label>
                        <input type="number" min="1" max="31" name="vencimento" required class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full mt-8 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors">
                Salvar Cartão
            </button>
        </form>
    </div>
</div>

<script>
    function openCardModal() {
        document.getElementById('cardModal').classList.remove('hidden');
    }

    function closeCardModal() {
        document.getElementById('cardModal').classList.add('hidden');
        document.getElementById('cardForm').reset();
    }

    async function loadCards() {
        const res = await fetch('api/list_cartoes.php');
        const cards = await res.json();
        
        const container = document.getElementById('cardsList');
        container.innerHTML = '';
        
        if (cards.length === 0) {
            container.innerHTML = `<div class="col-span-full text-center py-12 text-slate-500">Você ainda não tem cartões cadastrados.</div>`;
            return;
        }

        cards.forEach(c => {
            container.innerHTML += `
                <a href="fatura.php?id=${c.id}" class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group block hover:scale-[1.02] transition-transform cursor-pointer">
                    <div class="flex justify-between items-start mb-8 relative z-10">
                        <div>
                            <h3 class="font-bold text-xl">${c.nome}</h3>
                            <p class="text-slate-400 text-sm">Vence dia ${c.vencimento} • Fecha dia ${c.fechamento}</p>
                        </div>
                        <button onclick="event.preventDefault(); deleteCard(${c.id})" class="text-slate-400 hover:text-red-400 transition-colors p-2 -m-2">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    <div class="relative z-10">
                        <p class="text-slate-400 text-sm mb-1">Limite Total</p>
                        <p class="font-bold text-2xl">R$ ${parseFloat(c.limite).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 text-white/5 group-hover:text-white/10 transition-colors pointer-events-none z-0">
                        <i class="fa-brands fa-cc-visa text-9xl"></i>
                    </div>
                </a>
            `;
        });
    }

    async function saveCard(e) {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        
        try {
            const res = await fetch('api/create_cartao.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.success) {
                closeCardModal();
                loadCards();
                if (typeof showToast !== 'undefined') showToast('Cartão adicionado!');
            } else {
                alert(result.error);
            }
        } catch (err) {
            alert("Erro ao salvar");
        }
    }

    async function deleteCard(id) {
        if(!confirm('Deletar este cartão? As faturas associadas poderão perder a referência.')) return;
        
        await fetch('api/delete_cartao.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id})
        });
        loadCards();
    }

    document.addEventListener('DOMContentLoaded', loadCards);
</script>

<?php
renderFooter();
?>
