<?php
// metas.php
require_once 'src/auth.php';
requireAuth();
require_once 'src/layout.php';

renderHeader('Metas e Sonhos');
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                Meus Sonhos 🚀
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Defina objetivos e acompanhe sua evolução.</p>
        </div>
        <button onclick="openModalGoal()"
            class="flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-slate-500/30 transition-all transform hover:scale-105 active:scale-95 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
            Nova Meta
        </button>
    </div>

    <!-- Goals Grid -->
    <div id="goalsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Loading State -->
        <div class="col-span-full text-center py-20 text-slate-400">
            <svg class="animate-spin h-10 w-10 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Carregando sonhos...
        </div>
    </div>

</div>

<!-- Confetti Canvas (Hidden) -->
<canvas id="confettiCanvas" class="fixed inset-0 pointer-events-none z-50"></canvas>

<!-- Modal: Nova/Editar Meta -->
<div id="modalGoal"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-200"
        id="modalGoalContent">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modalGoalTitle">Nova Meta</h3>
            <button onclick="closeModalGoal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="formGoal" onsubmit="saveGoal(event)">
                <input type="hidden" name="id" id="goalId">

                <div class="space-y-4">
                    <!-- Icon e Titulo Row -->
                    <div class="flex gap-3">
                        <div class="w-1/4">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ícone</label>
                            <select name="icon" id="goalIcon"
                                class="w-full text-2xl h-[46px] rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center">
                                <option value="🎯">🎯</option>
                                <option value="✈️">✈️</option>
                                <option value="🚗">🚗</option>
                                <option value="🏠">🏠</option>
                                <option value="💰">💰</option>
                                <option value="💍">💍</option>
                                <option value="🎓">🎓</option>
                                <option value="🖥️">🖥️</option>
                                <option value="🏖️">🏖️</option>
                            </select>
                        </div>
                        <div class="w-3/4">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nome da Meta</label>
                            <input type="text" name="title" id="goalTitle" required placeholder="Ex: Viagem Europa"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                        </div>
                    </div>

                    <!-- Valor Alvo -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Valor Alvo (R$)</label>
                        <input type="number" step="0.01" name="target_amount" id="goalTarget" required
                            placeholder="0,00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-lg">
                    </div>

                    <!-- Valor Inicial (Apenas na criação) -->
                    <div id="initialAmountField">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Já tenho guardado
                            (R$)</label>
                        <input type="number" step="0.01" name="initial_amount" id="goalInitial" placeholder="0,00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    </div>

                    <!-- Prazo e Cor Row -->
                    <div class="flex gap-3">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Prazo
                                (Opcional)</label>
                            <input type="date" name="deadline" id="goalDeadline"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cor</label>
                            <select name="color" id="goalColor"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="blue">Azul</option>
                                <option value="green">Verde</option>
                                <option value="purple">Roxo</option>
                                <option value="rose">Rosa</option>
                                <option value="amber">Laranja</option>
                                <option value="cyan">Ciano</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="deleteGoalAction()" id="btnDeleteGoal"
                        class="hidden px-4 py-3 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button type="button" onclick="closeModalGoal()"
                        class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 font-medium transition-colors dark:bg-slate-700 dark:text-slate-300">Cancelar</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/30 transition-colors">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Depositar -->
<div id="modalDeposit"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-200"
        id="modalDepositContent">
        <div class="p-6">
            <h3 class="text-center text-lg font-bold text-slate-800 dark:text-white mb-2">Guardar Dinheiro 💰</h3>
            <p class="text-center text-slate-500 text-sm mb-6" id="depositGoalName">--</p>

            <form onsubmit="confirmDeposit(event)">
                <input type="hidden" id="depositGoalId">
                <div class="relative mb-6">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xl">R$</span>
                    <input type="number" step="0.01" id="depositAmount" required autofocus
                        class="w-full pl-12 pr-4 py-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 font-bold text-3xl text-center placeholder-slate-300">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeModalDeposit()"
                        class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 font-medium transition-colors dark:bg-slate-700 dark:text-slate-300">Cancelar</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-green-500 text-white rounded-xl hover:bg-green-600 font-bold shadow-lg shadow-green-500/30 transition-colors">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confetti Lib -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    // --- Logic ---
    let goals = [];

    document.addEventListener('DOMContentLoaded', loadGoals);

    async function loadGoals() {
        const grid = document.getElementById('goalsGrid');
        try {
            const res = await fetch('api/get_goals.php');
            const data = await res.json();
            goals = data;
            renderGoals(data);
        } catch (e) {
            console.error(e);
            grid.innerHTML = '<div class="col-span-full text-center text-red-500 py-10">Erro ao carregar dados.</div>';
        }
    }

    function renderGoals(list) {
        const grid = document.getElementById('goalsGrid');
        grid.innerHTML = '';

        if (list.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                    <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-full mb-4">
                        <span class="text-4xl">🌱</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 dark:text-white">Nenhuma meta ainda</h3>
                    <p class="text-slate-500 max-w-sm mt-2">Que tal começar a planejar seu próximo sonho? Clique em "Nova Meta" para começar.</p>
                </div>
            `;
            return;
        }

        list.forEach(goal => {
            const progress = parseFloat(goal.progress);
            const isCompleted = progress >= 100;
            const card = document.createElement('div');

            // Color Themes
            const colors = {
                blue: 'from-blue-500 to-blue-600 shadow-blue-500/20 text-blue-600 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400 border-blue-100 dark:border-blue-800',
                green: 'from-emerald-500 to-emerald-600 shadow-emerald-500/20 text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800',
                purple: 'from-purple-500 to-purple-600 shadow-purple-500/20 text-purple-600 bg-purple-50 dark:bg-purple-900/20 dark:text-purple-400 border-purple-100 dark:border-purple-800',
                rose: 'from-rose-500 to-rose-600 shadow-rose-500/20 text-rose-600 bg-rose-50 dark:bg-rose-900/20 dark:text-rose-400 border-rose-100 dark:border-rose-800',
                amber: 'from-amber-500 to-amber-600 shadow-amber-500/20 text-amber-600 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 border-amber-100 dark:border-amber-800',
                cyan: 'from-cyan-500 to-cyan-600 shadow-cyan-500/20 text-cyan-600 bg-cyan-50 dark:bg-cyan-900/20 dark:text-cyan-400 border-cyan-100 dark:border-cyan-800',
            };
            const theme = colors[goal.color] || colors.blue;

            // Parse theme classes
            // We need specific parts: progress bar gradient, text color for percentage, background for card
            // Let's simplify and use hardcoded maps based on color key
            const barGradient = `bg-gradient-to-r ${theme.split(' ').slice(0, 2).join(' ')}`;
            const textColor = theme.match(/text-[\w-]+/)[0];
            const cardBg = theme.match(/bg-[\w-\/]+/)[0];
            const cardBorder = theme.match(/border-[\w-]+/)[0];
            const darkBorder = theme.match(/dark:border-[\w-]+/)[0]; // simplistic parse

            card.className = `bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group hover:shadow-md transition-shadow`;

            card.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl ${cardBg} flex items-center justify-center text-3xl shadow-inner">
                            ${goal.icon}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white leading-tight">${goal.title}</h3>
                            <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                ${goal.days_left !== null ? `⏰ ${goal.days_left} dias restantes` : '📅 Sem prazo'}
                            </p>
                        </div>
                    </div>
                    <button onclick='openEditGoal(${JSON.stringify(goal)})' class="p-2 text-slate-300 hover:text-slate-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                             <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-end mb-2">
                         <span class="text-2xl font-bold text-slate-800 dark:text-white blur-sensitive">
                            R$ ${parseFloat(goal.current_amount).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                         </span>
                         <span class="text-xs font-bold ${textColor} bg-slate-100 dark:bg-slate-700/50 px-2 py-1 rounded-lg">
                            ${progress}%
                         </span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-700 h-3 rounded-full overflow-hidden">
                        <div class="h-full rounded-full ${barGradient} transition-all duration-1000 relative" style="width: ${progress}%">
                           ${isCompleted ? '<div class="absolute inset-0 bg-white/30 animate-pulse"></div>' : ''}
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-slate-400">
                        <span>Início</span>
                        <span>Meta: <span class="blur-sensitive">R$ ${parseFloat(goal.target_amount).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</span></span>
                    </div>
                </div>

                <button onclick="openModalDeposit(${goal.id}, '${goal.title}')" class="w-full py-3 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 text-slate-500 hover:border-green-400 hover:text-green-500 hover:bg-green-50 dark:hover:bg-green-900/10 transition-all font-bold flex items-center justify-center gap-2 group-hover:border-solid">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Adicionar Valor
                </button>
            `;
            grid.appendChild(card);
        });
    }

    // --- Modal Actions ---
    function openModalGoal() {
        document.getElementById('formGoal').reset();
        document.getElementById('goalId').value = '';
        document.getElementById('modalGoalTitle').textContent = 'Nova Meta';
        document.getElementById('initialAmountField').classList.remove('hidden');
        document.getElementById('btnDeleteGoal').classList.add('hidden');

        toggleModal('modalGoal', true);
    }

    function openEditGoal(goal) {
        document.getElementById('formGoal').reset();
        document.getElementById('goalId').value = goal.id;
        document.getElementById('modalGoalTitle').textContent = 'Editar Meta';
        document.getElementById('initialAmountField').classList.add('hidden'); // Cannot edit initial amount after creation easily logic-wise here
        document.getElementById('btnDeleteGoal').classList.remove('hidden');

        document.getElementById('goalTitle').value = goal.title;
        document.getElementById('goalTarget').value = goal.target_amount;
        document.getElementById('goalDeadline').value = goal.deadline || '';
        document.getElementById('goalColor').value = goal.color;
        document.getElementById('goalIcon').value = goal.icon;

        toggleModal('modalGoal', true);
    }

    function closeModalGoal() {
        toggleModal('modalGoal', false);
    }

    async function saveGoal(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('api/save_goal.php', {
                method: 'POST',
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (json.success) {
                closeModalGoal();
                loadGoals();
            } else {
                alert('Erro: ' + json.error);
            }
        } catch (e) {
            console.error(e);
            alert('Erro de conexão');
        }
    }

    // --- Deposit Logic ---
    let currentDepositId = null;

    function openModalDeposit(id, title) {
        currentDepositId = id;
        document.getElementById('depositGoalName').textContent = title;
        document.getElementById('depositAmount').value = '';
        toggleModal('modalDeposit', true);
        setTimeout(() => document.getElementById('depositAmount').focus(), 100);
    }

    function closeModalDeposit() {
        toggleModal('modalDeposit', false);
        currentDepositId = null;
    }

    async function confirmDeposit(e) {
        e.preventDefault();
        const amount = document.getElementById('depositAmount').value;
        if (!amount || amount <= 0) return;

        try {
            const res = await fetch('api/deposit_goal.php', {
                method: 'POST',
                body: JSON.stringify({ id: currentDepositId, amount: amount })
            });
            const json = await res.json();
            if (json.success) {
                closeModalDeposit();
                loadGoals();

                if (json.goal_reached) {
                    fireConfetti();
                }
            } else {
                alert('Erro: ' + json.error);
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function deleteGoalAction() {
        const id = document.getElementById('goalId').value;
        if (!id) return;
        if (!confirm('Excluir esta meta permanentemente?')) return;

        try {
            const res = await fetch('api/delete_goal.php', {
                method: 'POST',
                body: JSON.stringify({ id: id })
            });
            const json = await res.json();
            if (json.success) {
                closeModalGoal();
                loadGoals();
            } else {
                alert('Erro: ' + json.error);
            }
        } catch (e) {
            console.error(e);
        }
    }

    // --- Utils ---
    function toggleModal(id, show) {
        const modal = document.getElementById(id);
        const content = document.querySelector(`#${id} > div`);
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

    function fireConfetti() {
        const canvas = document.getElementById('confettiCanvas');
        const myConfetti = confetti.create(canvas, {
            resize: true,
            useWorker: true
        });
        myConfetti({
            particleCount: 150,
            spread: 70,
            origin: { y: 0.6 },
            zIndex: 9999
        });
    }
</script>