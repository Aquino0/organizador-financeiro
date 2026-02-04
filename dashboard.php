<?php
require_once 'src/auth.php';
require_once 'src/layout.php';
requireAuth();

renderHeader('Dashboard');
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Month Selector -->
    <div class="flex items-center justify-center gap-4 mb-8">
        <button onclick="changeMonth(-1)"
            class="active-press p-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors shadow-sm bg-white dark:bg-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <h2 id="monthLabel"
            class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white capitalize min-w-[160px] text-center select-none">
            Carregando...
        </h2>

        <button onclick="changeMonth(1)"
            class="active-press p-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors shadow-sm bg-white dark:bg-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Skeleton KPIs (Hidden by default, toggled via JS) -->
    <div id="skeletonKPIs" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 hidden">
        <!-- 3 Generic Skeleton Cards -->
        <?php for ($i = 0; $i < 3; $i++): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-start">
                    <div class="space-y-3 w-full">
                        <div class="h-4 w-1/3 bg-slate-200 dark:bg-slate-700 rounded animate-pulse"></div>
                        <div class="h-8 w-2/3 bg-slate-200 dark:bg-slate-700 rounded animate-pulse"></div>
                    </div>
                    <div class="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded-xl animate-pulse"></div>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Real KPIs -->
    <div id="realKPIs" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Receitas (Pastel Green Theme) -->
        <div
            class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl shadow-sm p-6 border border-emerald-100 dark:border-emerald-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-emerald-200 uppercase tracking-wider">Renda
                        Mensal</p>
                    <h3 class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2" id="totalReceitas">R$
                        0,00</h3>
                </div>
                <div class="p-3 bg-white dark:bg-emerald-800 rounded-xl text-emerald-500 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Despesas (Pastel Red Theme) -->
        <div
            class="bg-rose-50 dark:bg-rose-900/20 rounded-2xl shadow-sm p-6 border border-rose-100 dark:border-rose-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-rose-200 uppercase tracking-wider">Despesas
                        Mensais</p>
                    <h3 class="text-3xl font-bold text-rose-600 dark:text-rose-400 mt-2" id="totalDespesas">R$ 0,00</h3>
                </div>
                <div class="p-3 bg-white dark:bg-rose-800 rounded-xl text-rose-500 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Balanço (Pastel Blue Theme) -->
        <div
            class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl shadow-sm p-6 border border-blue-100 dark:border-blue-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-blue-200 uppercase tracking-wider">Saldo
                        Acumulado</p>
                    <h3 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2" id="balancoGeral">R$ 0,00</h3>
                </div>
                <div class="p-3 bg-white dark:bg-blue-800 rounded-xl text-blue-500 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div id="statusBadge"
                    class="inline-flex px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide bg-white/50 text-slate-500">
                    Calculando...
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <!-- Chart Section -->
        <div class="relative">
            <!-- Skeleton Chart -->
            <div id="skeletonChart" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 hidden h-full">
                <div class="h-6 w-1/2 bg-slate-200 dark:bg-slate-700 rounded animate-pulse mb-6"></div>
                <div class="h-64 bg-slate-200 dark:bg-slate-700 rounded-lg animate-pulse"></div>
            </div>

            <!-- Real Chart -->
            <div id="realChart" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 h-full">
                <h4 class="text-lg font-semibold mb-4 text-slate-800 dark:text-white">Movimentações Financeiras</h4>
                <div class="relative h-64 w-full">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Ranking -->
        <div class="space-y-6">
            <!-- Actions (Keep visible or skeleton? Let's keep visible as they are static mostly, or skeleton if strict) -->
            <!-- Let's keep actions visible for instant interactivity, but add skeleton for ranking -->
            <div class="grid grid-cols-2 gap-4">
                <a href="lancamentos.php"
                    class="active-press flex flex-col items-center justify-center p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/50 transition cursor-pointer">
                    <div
                        class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white mb-2 shadow-lg shadow-green-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="font-medium text-green-700 dark:text-green-400">Nova Receita</span>
                </a>
                <a href="lancamentos.php"
                    class="active-press flex flex-col items-center justify-center p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/50 transition cursor-pointer">
                    <div
                        class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white mb-2 shadow-lg shadow-red-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="font-medium text-red-700 dark:text-red-400">Nova Despesa</span>
                </a>
            </div>

            <!-- Ranking -->
            <div class="relative">
                <!-- Skeleton Ranking -->
                <div id="skeletonRanking" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 hidden">
                    <div class="h-6 w-2/3 bg-slate-200 dark:bg-slate-700 rounded animate-pulse mb-6"></div>
                    <div class="space-y-4">
                        <div class="h-8 w-full bg-slate-200 dark:bg-slate-700 rounded animate-pulse"></div>
                        <div class="h-8 w-full bg-slate-200 dark:bg-slate-700 rounded animate-pulse"></div>
                        <div class="h-8 w-full bg-slate-200 dark:bg-slate-700 rounded animate-pulse"></div>
                    </div>
                </div>

                <!-- Real Ranking -->
                <div id="realRanking" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                    <h4 class="text-lg font-semibold mb-4 text-slate-800 dark:text-white">Top Categorias (Despesas)</h4>
                    <div id="categoryRanking" class="space-y-3">
                        <p class="text-sm text-slate-500">Carregando...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let currentDate = new Date();
    let financeChart = null;

    function getMonthName(date) {
        return date.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
    }

    function changeMonth(delta) {
        currentDate.setMonth(currentDate.getMonth() + delta);
        updateUI();
    }

    function updateUI() {
        document.getElementById('monthLabel').textContent = getMonthName(currentDate);
        loadDashboardData();
    }

    function toggleSkeleton(show) {
        const skeletons = ['skeletonKPIs', 'skeletonChart', 'skeletonRanking'];
        const reals = ['realKPIs', 'realChart', 'realRanking'];

        skeletons.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (show) el.classList.remove('hidden');
                else el.classList.add('hidden');
            }
        });

        reals.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (show) el.classList.add('hidden');
                else el.classList.remove('hidden');
            }
        });
    }

    async function loadDashboardData() {
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const currentMonthParam = `${year}-${month}`;

        // Show Skeleton
        toggleSkeleton(true);

        try {
            // Fetch KPIs and Lists in parallel
            const results = await Promise.allSettled([
                fetch(`api/dashboard_stats.php?mes=${currentMonthParam}`),
                fetch(`api/list_receitas.php?mes=${currentMonthParam}`),
                fetch(`api/list_despesas.php?mes=${currentMonthParam}`)
            ]);

            // Artificial delay to show off skeleton (optional, remove in production if desired)
            await new Promise(r => setTimeout(r, 500));

            // Check for failures
            const failed = results.filter(r => r.status === 'rejected' || (r.value && !r.value.ok));
            if (failed.length > 0) {
                // ... Error handling ...
                throw new Error("Erro ao buscar dados");
            }

            const stats = await results[0].value.json();
            const receitas = await results[1].value.json();
            const despesas = await results[2].value.json();

            // Hide Skeleton
            toggleSkeleton(false);

            // Update KPIs (From Stats API - Realized Only)
            document.getElementById('totalReceitas').textContent = stats.renda_mensal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            document.getElementById('totalDespesas').textContent = stats.despesas_mensal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            document.getElementById('balancoGeral').textContent = stats.saldo_acumulado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

            // Update Badge based on ACUMULADO
            const badge = document.getElementById('statusBadge');
            if (stats.saldo_acumulado >= 0) {
                badge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400';
                badge.textContent = 'CAIXA POSITIVO';
            } else {
                badge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400';
                badge.textContent = 'Caixa no Vermelho';
            }

            // Render Chart & Ranking (Using Lists - filtering for Paid for consistency?)
            const receitasPagas = receitas.filter(r => r.pago === true || r.pago === 't' || r.pago === 1);
            const despesasPagas = despesas.filter(d => d.pago === true || d.pago === 't' || d.pago === 1);

            renderChart(receitasPagas, despesasPagas);
            renderRanking(despesasPagas);

        } catch (e) {
            console.error("Erro ao carregar dados", e);
            document.getElementById('categoryRanking').innerHTML = `<p class="text-sm text-red-500">Erro: ${e.message}</p>`;
            alert("Erro ao carregar Dashboard: " + e.message);
        }
    }

    function renderChart(receitas, despesas) {
        const ctx = document.getElementById('financeChart').getContext('2d');

        // Destroy previous chart if exists
        if (financeChart) {
            financeChart.destroy();
        }

        // Simplification: Group by day for the chart
        const labels = [...new Set([...receitas.map(x => x.data), ...despesas.map(x => x.data)])].sort().slice(-7); // Last 7 active days

        if (labels.length === 0) {
            labels.push('Sem dados'); // Avoid empty chart crash
        }

        const dataReceitas = labels.map(date => {
            return receitas.filter(r => r.data === date).reduce((acc, curr) => acc + parseFloat(curr.valor), 0);
        });
        const dataDespesas = labels.map(date => {
            return despesas.filter(d => d.data === date).reduce((acc, curr) => acc + parseFloat(curr.valor), 0);
        });

        financeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.map(d => d === 'Sem dados' ? d : d.split('-').reverse().slice(0, 2).join('/')),
                datasets: [
                    {
                        label: 'Receitas',
                        data: dataReceitas,
                        backgroundColor: '#22c55e',
                        borderRadius: 4
                    },
                    {
                        label: 'Despesas',
                        data: dataDespesas,
                        backgroundColor: '#ef4444',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    function renderRanking(despesas) {
        const categories = {};
        despesas.forEach(d => {
            categories[d.categoria] = (categories[d.categoria] || 0) + parseFloat(d.valor);
        });

        const sorted = Object.entries(categories).sort((a, b) => b[1] - a[1]).slice(0, 5);
        const container = document.getElementById('categoryRanking');

        if (sorted.length === 0) {
            container.innerHTML = '<p class="text-sm text-slate-500">Nenhuma despesa registrada.</p>';
            return;
        }

        container.innerHTML = sorted.map(([cat, val]) => `
<div class="flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-8 h-1 rounded bg-slate-200 dark:bg-slate-700 overflow-hidden">
            <div class="h-full bg-blue-500" style="width: 100%"></div>
        </div> <!-- Just a visual indicator -->
        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">${cat}</span>
    </div>
    <span class="text-sm font-bold text-slate-800 dark:text-white">${val.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        })}</span>
</div>
`).join('');
    }

    document.addEventListener('DOMContentLoaded', updateUI);
</script>

<?php renderFooter(); ?>