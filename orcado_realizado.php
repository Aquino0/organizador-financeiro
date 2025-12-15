<?php
// orcado_realizado.php
require_once 'src/auth.php';
requireAuth();
require_once 'src/layout.php';

renderHeader('Comparativo Mensal');
?>

<!-- Custom Styles for Animations (Ported from React App) -->
<style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-10px) rotate(2deg);
        }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slide-up 0.5s ease-out forwards;
    }

    @keyframes pulse-glow {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .animate-pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    @keyframes bounce-soft {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .animate-bounce-soft {
        animation: bounce-soft 2s ease-in-out infinite;
    }

    /* Gradients */
    .gradient-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .gradient-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .gradient-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .gradient-accent {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .dark .glass-card {
        background: rgba(30, 41, 59, 0.9);
    }
</style>

<div class="min-h-screen pb-24">
    <!-- Header Hero Removed -->

    <main class="max-w-6xl mx-auto px-4">
        <!-- Month Selector -->
        <div class="flex items-center justify-center gap-4 mb-8 animate-slide-up">
            <button onclick="changeMonth(-1)"
                class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <h2 id="monthLabel"
                class="text-2xl font-bold text-slate-800 dark:text-white capitalize min-w-[200px] text-center">
                Carregando...
            </h2>

            <button onclick="changeMonth(1)"
                class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Financial Overview (Main Balance Card) -->
        <div id="financialOverview" class="mb-8 opacity-0 animate-slide-up" style="animation-delay: 100ms">
            <!-- Injected by JS -->
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Receitas Recebidas -->
            <div class="relative p-6 rounded-2xl text-white shadow-lg overflow-hidden animate-slide-up gradient-success"
                style="animation-delay: 200ms">
                <div class="absolute -right-4 -top-4 text-7xl opacity-20 animate-float">💰</div>
                <div class="relative z-10">
                    <p class="text-sm font-medium opacity-90 mb-1">Receitas Recebidas</p>
                    <p id="sumIncReceived" class="text-3xl font-extrabold tracking-tight">R$ --</p>
                    <p id="sumIncSub" class="text-xs mt-2 opacity-80">-- fontes recebidas</p>
                </div>
            </div>

            <!-- Receitas Pendentes -->
            <div class="relative p-6 rounded-2xl text-white shadow-lg overflow-hidden animate-slide-up gradient-primary"
                style="animation-delay: 300ms">
                <div class="absolute -right-4 -top-4 text-7xl opacity-20 animate-float">⏳</div>
                <div class="relative z-10">
                    <p class="text-sm font-medium opacity-90 mb-1">Receitas Pendentes</p>
                    <p id="sumIncPending" class="text-3xl font-extrabold tracking-tight">R$ --</p>
                    <p class="text-xs mt-2 opacity-80">Ainda por receber</p>
                </div>
            </div>

            <!-- Total Gasto -->
            <div id="cardGasto"
                class="relative p-6 rounded-2xl text-white shadow-lg overflow-hidden animate-slide-up gradient-primary"
                style="animation-delay: 400ms">
                <div class="absolute -right-4 -top-4 text-7xl opacity-20 animate-float">💸</div>
                <div class="relative z-10">
                    <p class="text-sm font-medium opacity-90 mb-1">Total Gasto</p>
                    <p id="valGasto" class="text-3xl font-extrabold tracking-tight">R$ --</p>
                    <p id="subGasto" class="text-xs mt-2 opacity-80">--% do orçamento</p>
                </div>
            </div>

            <!-- Economia -->
            <div id="cardEconomia"
                class="relative p-6 rounded-2xl text-white shadow-lg overflow-hidden animate-slide-up gradient-success"
                style="animation-delay: 500ms">
                <div id="iconEconomia" class="absolute -right-4 -top-4 text-7xl opacity-20 animate-float">🎯</div>
                <div class="relative z-10">
                    <p id="titleEconomia" class="text-sm font-medium opacity-90 mb-1">Economia</p>
                    <p id="valEconomia" class="text-3xl font-extrabold tracking-tight">R$ --</p>
                    <p id="subEconomia" class="text-xs mt-2 opacity-80">Continue assim!</p>
                </div>
            </div>
        </div>

        <!-- Income Section Header -->
        <div class="mb-6 flex items-center justify-between opacity-0 animate-slide-up" style="animation-delay: 600ms">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="text-2xl">💵</span> Receitas
            </h2>
            <span id="badgeIncObs"
                class="px-3 py-1 bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 text-sm font-semibold rounded-full">
                -- recebidas
            </span>
        </div>

        <!-- Income Grid -->
        <div id="incomeGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <!-- Injected by JS -->
        </div>

        <!-- Expenses Balance Indicator -->
        <div id="balanceIndicator"
            class="mb-8 p-6 rounded-2xl border-2 bg-white dark:bg-slate-800 shadow-sm opacity-0 animate-slide-up">
            <!-- Injected by JS -->
        </div>

        <!-- Expenses Section Header -->
        <div class="mb-6 flex items-center justify-between opacity-0 animate-slide-up" style="animation-delay: 1.1s">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="text-2xl">📋</span> Despesas por Categoria
            </h2>
            <span id="badgeOverBudget"
                class="hidden px-3 py-1 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 text-sm font-semibold rounded-full animate-pulse-glow">
                ⚠️ 0 categorias estouradas
            </span>
        </div>

        <!-- Expenses Grid -->
        <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Cards injected by JS -->
        </div>

        <!-- Tips Section -->
        <div class="mt-12 p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm opacity-0 animate-slide-up"
            style="animation-delay: 1.5s">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <span class="text-2xl">💡</span> Dicas do Mês
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-start gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                    <span class="text-2xl">🌟</span>
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-white text-sm">Diversifique receitas</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Busque novas fontes de renda passiva</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                    <span class="text-2xl">🎯</span>
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-white text-sm">Meta: economizar 20%</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Reserve parte das receitas para
                            emergências</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                    <span class="text-2xl">📈</span>
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-white text-sm">Invista suas economias</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Faça seu dinheiro trabalhar por você</p>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
    // State
    let currentDate = new Date(); // Starts today
    let currentData = null; // Store fetched data

    function getMonthName(date) {
        return date.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
    }

    async function changeMonth(delta) {
        // Update Date
        currentDate.setMonth(currentDate.getMonth() + delta);
        updateUI();
    }

    async function fetchYearData(year) {
        try {
            const res = await fetch(`api/budget_stats.php?year=${year}`);
            const data = await res.json();
            if (data.error) throw new Error(data.error);
            return data;
        } catch (e) {
            console.error(e);
            alert('Erro ao carregar dados');
            return null;
        }
    }

    async function updateUI() {
        const year = currentDate.getFullYear();
        const monthIndex = currentDate.getMonth(); // 0-11

        document.getElementById('monthLabel').textContent = '📅 ' + getMonthName(currentDate);

        // Fetch if needed (cache simplest: just user currentData if matches year)
        if (!currentData || currentData.year !== year) {
            currentData = await fetchYearData(year);
        }

        if (!currentData) return;

        // Extract Month Data
        renderDashboard(currentData, monthIndex);
    }

    function renderDashboard(data, m) {
        // 1. Calculations
        // Income
        const totalIncExpected = data.monthly_income[m] || 0;
        const totalIncReceived = data.monthly_income_realized[m] || 0;
        const incPending = Math.max(0, totalIncExpected - totalIncReceived);

        // Expenses
        const totalExpBudgeted = data.monthly_expenses[m] || 0;
        const totalExpSpent = data.monthly_expenses_realized[m] || 0;
        const savings = totalExpBudgeted - totalExpSpent; // Positive = Under Budget (Economy)

        // 2. Financial Overview (New Component)
        renderFinancialOverview(totalIncReceived, totalExpSpent);

        // 3. Summary Cards
        // Income Cards
        document.getElementById('sumIncReceived').textContent = fMoney(totalIncReceived);
        document.getElementById('sumIncPending').textContent = fMoney(incPending);
        // Note: Count of received sources needs category iteration

        // Expense Cards
        const elCardGasto = document.getElementById('cardGasto');
        document.getElementById('valGasto').textContent = fMoney(totalExpSpent);
        const percent = totalExpBudgeted > 0 ? (totalExpSpent / totalExpBudgeted) * 100 : 0;
        document.getElementById('subGasto').textContent = percent.toFixed(1) + '% do orçamento';

        if (totalExpSpent > totalExpBudgeted) {
            elCardGasto.className = elCardGasto.className.replace('gradient-primary', 'gradient-danger').replace('gradient-success', 'gradient-danger');
        } else {
            elCardGasto.className = elCardGasto.className.replace('gradient-danger', 'gradient-primary').replace('gradient-success', 'gradient-primary');
        }

        const elCardEco = document.getElementById('cardEconomia');
        document.getElementById('valEconomia').textContent = "R$ " + Math.abs(savings).toLocaleString('pt-BR', { minimumFractionDigits: 2 });

        if (savings >= 0) {
            document.getElementById('titleEconomia').textContent = "Economia";
            document.getElementById('iconEconomia').textContent = "🎯";
            document.getElementById('subEconomia').textContent = "Continue assim!";
            elCardEco.className = elCardEco.className.replace('gradient-danger', 'gradient-success');
        } else {
            document.getElementById('titleEconomia').textContent = "Excedido";
            document.getElementById('iconEconomia').textContent = "😰";
            document.getElementById('subEconomia').textContent = "Reveja seus gastos";
            elCardEco.className = elCardEco.className.replace('gradient-success', 'gradient-danger');
        }

        // 4. Income Grid & Count
        const completedIncCount = renderIncomeGrid(data, m);
        document.getElementById('sumIncSub').textContent = `${completedIncCount} fontes recebidas`;
        document.getElementById('badgeIncObs').textContent = `${completedIncCount} recebidas`; // Simple text update

        // 5. Balance Indicator
        renderBalanceIndicator(totalExpBudgeted, totalExpSpent, percent, savings < 0);

        // 6. Categories Grid
        renderExpenseGrid(data, m);
    }

    function renderFinancialOverview(totalIncome, totalExpenses) {
        const balance = totalIncome - totalExpenses;
        const isPositive = balance >= 0;
        const savingsRate = totalIncome > 0 ? (balance / totalIncome) * 100 : 0;

        let status = {};
        if (balance > totalIncome * 0.3) {
            status = { emoji: "🏆", msg: "Fantástico! Mestre das finanças!", bg: "gradient-success" };
        } else if (balance > 0) {
            status = { emoji: "✨", msg: "Ótimo! Finanças no positivo!", bg: "gradient-success" };
        } else if (balance === 0) {
            status = { emoji: "😅", msg: "Empate! Receitas e despesas iguais", bg: "gradient-accent" };
        } else {
            status = { emoji: "😰", msg: "Atenção! Despesas maiores", bg: "gradient-danger" };
        }

        const html = `
        <div class="relative p-6 md:p-8 rounded-3xl text-white shadow-lg overflow-hidden ${status.bg}">
            <div class="absolute -right-8 -top-8 text-9xl opacity-10 animate-float">${status.emoji}</div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-4xl animate-bounce-soft">${status.emoji}</span>
                    <p class="text-lg opacity-90 font-medium">${status.msg}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                             <span class="text-2xl">💰</span>
                             <span class="text-sm opacity-80">Receitas</span>
                        </div>
                        <p class="text-2xl font-extrabold">${fMoney(totalIncome)}</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                             <span class="text-2xl">💸</span>
                             <span class="text-sm opacity-80">Despesas</span>
                        </div>
                        <p class="text-2xl font-extrabold">${fMoney(totalExpenses)}</p>
                    </div>

                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 border-2 border-white/30">
                        <div class="flex items-center gap-2 mb-2">
                             <span class="text-2xl">${isPositive ? "🎯" : "📉"}</span>
                             <span class="text-sm opacity-80">Saldo</span>
                        </div>
                        <p class="text-2xl font-extrabold">${isPositive ? '+' : '-'} ${fMoney(Math.abs(balance))}</p>
                    </div>
                </div>

                <div class="mt-6 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm opacity-80">Taxa de Economia</span>
                        <span class="font-bold text-lg">${savingsRate.toFixed(1)}%</span>
                    </div>
                    <div class="h-3 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-1000 ease-out" style="width: ${Math.max(0, Math.min(savingsRate, 100))}%"></div>
                    </div>
                </div>
            </div>
        </div>
        `;
        document.getElementById('financialOverview').innerHTML = html;
    }

    function renderIncomeGrid(data, m) {
        const grid = document.getElementById('incomeGrid');
        grid.innerHTML = '';

        const list = data.category_income || [];
        let completedCount = 0;

        list.forEach((cat, index) => {
            const expected = cat.values[m] || 0;
            const received = cat.values_realized[m] || 0;
            // Emoji hash
            const emoji = getIncomeEmoji(cat.name);

            if (expected === 0 && received === 0) return;

            const percentage = Math.min((received / expected) * 100, 100);
            const isComplete = received >= expected;
            if (isComplete) completedCount++;
            const isPartial = percentage >= 50 && percentage < 100;
            const remaining = expected - received;

            let status = { bg: "bg-amber-50 dark:bg-amber-900/10 border-amber-100 dark:border-amber-800", text: "text-amber-600", bar: "bg-amber-500", emoji: "📅", label: "Pendente" };
            if (isComplete) {
                status = { bg: "bg-emerald-50 dark:bg-emerald-900/10 border-emerald-100 dark:border-emerald-800", text: "text-emerald-600", bar: "bg-emerald-500", emoji: "🎉", label: "Recebido!" };
            } else if (isPartial) {
                status = { bg: "bg-blue-50 dark:bg-blue-900/10 border-blue-100 dark:border-blue-800", text: "text-blue-600", bar: "bg-blue-500", emoji: "⏳", label: "Em andamento" };
            }

            const html = `
            <div class="relative p-5 rounded-2xl border-2 bg-white dark:bg-slate-800 shadow-sm transition-all duration-300 hover:scale-[1.02] hover:shadow-lg animate-slide-up ${status.bg}" style="animation-delay: ${700 + index * 50}ms">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl animate-bounce-soft" style="animation-delay: ${900 + index * 50}ms">${emoji}</span>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">${cat.name}</h3>
                            <span class="text-xs font-semibold ${status.text}">
                                ${status.emoji} ${status.label}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Recebido</span>
                        <span class="font-bold text-slate-800 dark:text-white">${fMoney(received)}</span>
                    </div>

                    <div class="relative h-3 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                        <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-700 ease-out ${status.bar} ${isComplete ? 'animate-pulse-glow' : ''}" style="width: ${percentage}%"></div>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Esperado</span>
                        <span class="font-semibold text-slate-800 dark:text-white">${fMoney(expected)}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
                        <span class="text-sm text-slate-400">${isComplete ? 'Extra' : 'Falta receber'}</span>
                        <span class="font-bold text-lg ${status.text}">
                             ${isComplete && received > expected ? '+' : ''} ${fMoney(Math.abs(remaining))}
                        </span>
                    </div>
                </div>
            </div>
             `;
            grid.insertAdjacentHTML('beforeend', html);
        });
        return completedCount;
    }

    function renderBalanceIndicator(totalBudget, totalSpent, percentage, isOver) {
        const div = document.getElementById('balanceIndicator');
        // Render innerHTML directly for simplicity

        let config = { bg: "bg-emerald-50 border-emerald-200", text: "text-emerald-600", bar: "bg-emerald-500", emoji: "🎉", title: "Parabéns!", msg: "Excelente gestão financeira" };
        if (isOver) config = { bg: "bg-red-50 border-red-200", text: "text-red-600", bar: "bg-red-500", emoji: "🚨", title: "Ops! Estourou!", msg: "Hora de rever gastos" };
        else if (percentage >= 80) config = { bg: "bg-amber-50 border-amber-200", text: "text-amber-600", bar: "bg-amber-500", emoji: "⚠️", title: "Cuidado!", msg: "Quase no limite" };
        else if (percentage >= 50) config = { bg: "bg-blue-50 border-blue-200", text: "text-blue-600", bar: "bg-blue-500", emoji: "💪", title: "Tudo sob controle!", msg: "Continue assim" };

        if (document.documentElement.classList.contains('dark')) {
            if (isOver) config.bg = "bg-red-900/10 border-red-800";
            else if (percentage >= 80) config.bg = "bg-amber-900/10 border-amber-800";
            else if (percentage >= 50) config.bg = "bg-blue-900/10 border-blue-800";
            else config.bg = "bg-emerald-900/10 border-emerald-800";
        }

        // Apply classes to container
        div.className = `mb-8 p-6 rounded-2xl border-2 shadow-sm animate-slide-up transition-colors duration-300 dark:bg-slate-800 ${config.bg}`;

        div.innerHTML = `
            <div class="flex items-center gap-4">
                <span class="text-5xl animate-bounce-soft">${config.emoji}</span>
                <div class="flex-1">
                    <h2 class="text-xl font-bold ${config.text}">${config.title}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">${config.msg}</p>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Progresso do mês</span>
                    <span class="font-bold ${config.text}">${percentage.toFixed(1)}%</span>
                </div>
                
                <div class="relative h-4 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                    <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-1000 ease-out ${config.bar}" style="width: ${Math.min(percentage, 100)}%"></div>
                    ${percentage > 100 ? `<div class="absolute inset-y-0 right-0 bg-red-500/50 animate-pulse-glow rounded-r-full" style="width: ${Math.min(percentage - 100, 20)}%"></div>` : ''}
                </div>

                <div class="flex justify-between text-xs text-slate-400 pt-1">
                    <span>R$ 0</span>
                    <span>${fMoney(totalBudget)}</span>
                </div>
            </div>
        `;
    }

    function renderExpenseGrid(data, m) {
        const grid = document.getElementById('categoriesGrid');
        grid.innerHTML = '';

        // Helper to combine fix/var
        let allCats = [];
        const process = (list) => {
            if (!list) return;
            list.forEach(c => {
                allCats.push({
                    name: c.name,
                    budgeted: c.values[m] || 0,
                    spent: c.values_realized[m] || 0,
                    emoji: getEmoji(c.name)
                });
            });
        };
        process(data.category_expenses_fixed);
        process(data.category_expenses_variable);
        allCats.sort((a, b) => b.spent - a.spent);

        // Count Over
        const overCount = allCats.filter(c => c.spent > c.budgeted && c.budgeted > 0).length;
        const badge = document.getElementById('badgeOverBudget');
        if (overCount > 0) {
            badge.classList.remove('hidden');
            badge.textContent = `⚠️ ${overCount} categoria${overCount > 1 ? 's' : ''} estourada${overCount > 1 ? 's' : ''}`;
        } else {
            badge.classList.add('hidden');
        }

        allCats.forEach((cat, index) => {
            if (cat.budgeted === 0 && cat.spent === 0) return;

            const percentage = cat.budgeted > 0 ? (cat.spent / cat.budgeted) * 100 : (cat.spent > 0 ? 100 : 0);
            const isOver = cat.spent > cat.budgeted;
            const remaining = cat.budgeted - cat.spent;

            let status = { bg: "bg-emerald-50 dark:bg-emerald-900/10 border-emerald-100 dark:border-emerald-800", text: "text-emerald-600", bar: "bg-emerald-500", label: "Tudo certo!", emoji: "😊" };
            if (isOver) {
                status = { bg: "bg-red-50 dark:bg-red-900/10 border-red-100 dark:border-red-800", text: "text-red-600", bar: "bg-red-500", label: "Estourou!", emoji: "😱" };
            } else if (percentage >= 80) {
                status = { bg: "bg-amber-50 dark:bg-amber-900/10 border-amber-100 dark:border-amber-800", text: "text-amber-600", bar: "bg-amber-500", label: "Atenção!", emoji: "😰" };
            }

            const html = `
            <div class="relative p-5 rounded-2xl border-2 bg-white dark:bg-slate-800 shadow-sm transition-all duration-300 hover:scale-[1.02] hover:shadow-lg animate-slide-up ${status.bg}" style="animation-delay: ${1200 + index * 50}ms">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl animate-bounce-soft" style="animation-delay: ${1400 + index * 50}ms">${cat.emoji}</span>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">${cat.name}</h3>
                            <span class="text-xs font-semibold ${status.text}">
                                ${status.emoji} ${status.label}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Gasto</span>
                        <span class="font-bold text-slate-800 dark:text-white">${fMoney(cat.spent)}</span>
                    </div>

                    <div class="relative h-3 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                        <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-700 ease-out ${status.bar} ${isOver ? 'animate-pulse-glow' : ''}" style="width: ${Math.min(percentage, 100)}%"></div>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Orçado</span>
                        <span class="font-semibold text-slate-800 dark:text-white">${fMoney(cat.budgeted)}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
                        <span class="text-sm text-slate-400">${isOver ? 'Excedido' : 'Disponível'}</span>
                        <span class="font-bold text-lg ${status.text}">
                            ${isOver ? '-' : '+'} ${fMoney(Math.abs(remaining))}
                        </span>
                    </div>
                </div>
            </div>
            `;
            grid.insertAdjacentHTML('beforeend', html);
        });
    }

    function getEmoji(name) {
        const lower = name.toLowerCase();
        if (lower.includes('alimentac') || lower.includes('comida') || lower.includes('mercado')) return '🍔';
        if (lower.includes('transporte') || lower.includes('uber') || lower.includes('combustivel')) return '🚗';
        if (lower.includes('lazer') || lower.includes('cinema')) return '🎮';
        if (lower.includes('saude') || lower.includes('medico') || lower.includes('farmacia')) return '💊';
        if (lower.includes('educa') || lower.includes('escola') || lower.includes('livro')) return '📚';
        if (lower.includes('moradia') || lower.includes('casa') || lower.includes('aluguel')) return '🏠';
        if (lower.includes('roupa') || lower.includes('vestuario')) return '👗';
        if (lower.includes('celular') || lower.includes('internet') || lower.includes('assinatura')) return '📱';
        return '📦';
    }

    function getIncomeEmoji(name) {
        const lower = name.toLowerCase();
        if (lower.includes('salario') || lower.includes('salário')) return '💼';
        if (lower.includes('freela') || lower.includes('extra')) return '💻';
        if (lower.includes('invest') || lower.includes('rendimento')) return '📈';
        if (lower.includes('aluguel')) return '🏘️';
        if (lower.includes('presente')) return '🎁';
        return '💰';
    }

    function fMoney(val) {
        return val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    // Init
    updateUI();
</script>

<?php renderFooter(); ?>