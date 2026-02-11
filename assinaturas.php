<?php
// assinaturas.php
require_once 'src/auth.php';
requireAuth();
require_once 'src/layout.php';

renderHeader('Despesas Fixas');
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                Despesas Fixas 📅
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Gerencie suas contas fixas mensais (Aluguel, Luz,
                Internet, Assinaturas).</p>
        </div>
        <button onclick="openModalSub()"
            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all transform hover:scale-105 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
            Nova Despesa
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Comprometido (Mês)</p>
                <h3 class="text-3xl font-bold text-slate-800 dark:text-white mt-1 blur-sensitive" id="statMonthly">R$
                    0,00</h3>
            </div>
            <div
                class="h-12 w-12 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 flex items-center justify-center text-xl">
                📉
            </div>
        </div>
        <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Custo Anual Projetado</p>
                <h3 class="text-3xl font-bold text-slate-800 dark:text-white mt-1 blur-sensitive" id="statYearly">R$
                    0,00</h3>
            </div>
            <div
                class="h-12 w-12 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center text-xl">
                📅
            </div>
        </div>
    </div>

    <!-- List -->
    <div
        class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Minhas Despesas Fixas</h3>
        </div>

        <div id="subsList" class="divide-y divide-slate-100 dark:divide-slate-700">
            <!-- Items injected here -->
            <div class="p-10 text-center text-slate-400">
                <svg class="animate-spin h-8 w-8 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Carregando...
            </div>
        </div>
    </div>

</div>

<!-- Modal -->
<div id="modalSub"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-opacity opacity-0">
    <div
        class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-200">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modalSubTitle">Nova Despesa Fixa</h3>
            <button onclick="closeModalSub()"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-white">✕</button>
        </div>
        <div class="p-6">
            <form id="formSub" onsubmit="saveSub(event)">
                <input type="hidden" name="id" id="subId">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nome da Despesa</label>
                        <input type="text" name="service_name" id="subName" required placeholder="Ex: Aluguel"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Valor</label>
                            <input type="number" step="0.01" name="amount" id="subAmount" required placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ícone</label>
                            <select name="icon" id="subIcon"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xl text-center cursor-pointer">
                                <option value="📦">📦</option>
                                <option value="🏠">🏠</option>
                                <option value="💡">💡</option>
                                <option value="🌐">🌐</option>
                                <option value="🎓">🎓</option>
                                <option value="🏋️">🏋️</option>
                                <option value="🚗">🚗</option>
                                <option value="🍔">🍔</option>
                                <option value="🎬">🎬</option>
                                <option value="💳">💳</option>
                                <option value="🏥">🏥</option>
                                <option value="🐶">🐶</option>
                                <option value="👶">👶</option>
                                <option value="✈️">✈️</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Vencimento</label>
                        <input type="date" name="next_billing_date" id="subDate" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ciclo</label>
                            <select name="billing_cycle" id="subCycle"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="monthly">Mensal</option>
                                <option value="yearly">Anual</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Categoria
                                (Nome)</label>
                            <input type="text" list="categoryList" name="category" id="subCategory"
                                placeholder="Ex: Habitação"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <datalist id="categoryList">
                                <option value="Habitação">
                                <option value="Contas">
                                <option value="Internet/TV">
                                <option value="Educação">
                                <option value="Saúde">
                                <option value="Cartão">
                                <option value="Veículo">
                                <option value="Lazer">
                                <option value="Outro">
                            </datalist>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="deleteSubAction()" id="btnDeleteSub"
                        class="hidden px-4 py-3 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors">
                        🗑️
                    </button>
                    <button type="button" onclick="closeModalSub()"
                        class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 font-medium transition-colors dark:bg-slate-700 dark:text-slate-300">Cancelar</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-lg shadow-indigo-500/30 transition-colors">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', loadSubs);

    async function loadSubs() {
        const list = document.getElementById('subsList');
        try {
            const res = await fetch('api/subscriptions.php');
            const data = await res.json();

            if (data.stats) {
                document.getElementById('statMonthly').textContent = 'R$ ' + data.stats.monthly.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                document.getElementById('statYearly').textContent = 'R$ ' + data.stats.yearly.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
            }

            renderList(data.subscriptions);
        } catch (e) {
            console.error(e);
            list.innerHTML = '<div class="p-10 text-center text-red-500">Erro ao carregar dados.</div>';
        }
    }

    function renderList(subs) {
        const list = document.getElementById('subsList');
        list.innerHTML = '';

        if (!subs || subs.length === 0) {
            list.innerHTML = `
                <div class="p-10 text-center text-slate-400">
                    <p>Nenhuma despesa fixa cadastrada.</p>
                </div>
            `;
            return;
        }

        subs.forEach(sub => {
            const div = document.createElement('div');
            div.className = 'p-4 sm:p-6 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group';

            const icon = sub.icon || '📦';
            const categoryName = sub.category || 'Outro';

            // Date logic fix: avoid UTC conversion issues
            const parts = sub.next_billing_date.split('-');
            const date = new Date(parts[0], parts[1] - 1, parts[2]); // Year, Month (0-indexed), Day

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const diffTime = date - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            let dateBadgeClass = 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400';
            let dateText = `${parts[2]}/${parts[1]}/${parts[0]}`; // Direct string formatting to avoid ANY timezone offset

            if (diffDays < 0) {
                dateBadgeClass = 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 font-bold';
                dateText = `Venceu dia ${dateText}`;
            } else if (diffDays <= 3) {
                dateBadgeClass = 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 font-bold';
                dateText = diffDays === 0 ? 'Vence HOJE' : `Vence em ${diffDays} dias`;
            }

            div.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-indigo-50 dark:bg-slate-700 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                        ${icon}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-base">${sub.service_name}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-0.5 rounded ${dateBadgeClass}">
                                ${dateText}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">${categoryName}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="font-bold text-slate-800 dark:text-white blur-sensitive text-lg">
                            R$ ${parseFloat(sub.amount).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                        </div>
                         <div class="text-xs text-slate-400 capitalize">${sub.billing_cycle === 'monthly' ? 'Mensal' : 'Anual'}</div>
                    </div>
                    <div class="flex items-center gap-1">
                         <button onclick='renewSub(${sub.id})' class="p-2 text-green-500 hover:bg-green-50 rounded-lg dark:hover:bg-green-900/30" title="Pagar (Jogar p/ próximo mês)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button onclick='openEditSub(${JSON.stringify(sub)})' class="p-2 text-slate-300 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg dark:hover:bg-indigo-900/30 transition-colors">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                 <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                             </svg>
                        </button>
                    </div>
                </div>
            `;
            list.appendChild(div);
        });
    }

    // Modal
    function openModalSub() {
        document.getElementById('formSub').reset();
        document.getElementById('subId').value = '';
        document.getElementById('modalSubTitle').textContent = 'Nova Despesa Fixa';
        document.getElementById('btnDeleteSub').classList.add('hidden');
        document.getElementById('subDate').valueAsDate = new Date();
        document.getElementById('subCategory').value = 'Contas';
        document.getElementById('subIcon').value = '💡';
        toggleModal('modalSub', true);
    }

    function openEditSub(sub) {
        document.getElementById('subId').value = sub.id;
        document.getElementById('subName').value = sub.service_name;
        document.getElementById('subAmount').value = sub.amount;
        document.getElementById('subCycle').value = sub.billing_cycle;
        document.getElementById('subDate').value = sub.next_billing_date;
        document.getElementById('subCategory').value = sub.category || '';
        document.getElementById('subIcon').value = sub.icon || '📦';

        document.getElementById('modalSubTitle').textContent = 'Editar Despesa';
        document.getElementById('btnDeleteSub').classList.remove('hidden');
        toggleModal('modalSub', true);
    }

    function closeModalSub() {
        toggleModal('modalSub', false);
    }

    function toggleModal(id, show) {
        const modal = document.getElementById(id);
        const content = modal.querySelector('div');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        } else {
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }
    }

    async function saveSub(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('api/subscriptions.php', {
                method: 'POST',
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (json.success) {
                closeModalSub();
                loadSubs();
            } else {
                alert('Erro: ' + json.error);
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function deleteSubAction() {
        if (!confirm('Remover esta despesa fixa?')) return;
        const id = document.getElementById('subId').value;
        try {
            const res = await fetch('api/subscriptions.php', {
                method: 'DELETE',
                body: JSON.stringify({ id: id })
            });
            const json = await res.json();
            if (json.success) {
                closeModalSub();
                loadSubs();
            }
        } catch (e) { console.error(e); }
    }

    async function renewSub(id) {
        if (!confirm('Confirmar novo vencimento para o próximo mês?')) return;
        try {
            const res = await fetch('api/subscriptions.php', {
                method: 'POST',
                body: JSON.stringify({ id: id, action: 'renew' })
            });
            const json = await res.json();
            if (json.success) {
                loadSubs();
            }
        } catch (e) { console.error(e); }
    }
</script>