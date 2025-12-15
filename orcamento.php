<?php
// orcamento.php
require_once 'src/auth.php';
require_once 'src/layout.php';

requireAuth();
renderHeader('Orçamento Anual');
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Title & Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Orçamento <span id="displayYear">2026</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Visão anual consolidada das suas finanças.</p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            <!-- Add Button -->
            <button onclick="openModalAdd()"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-lg shadow-blue-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Adicionar</span>
            </button>
            <!-- Config Button -->
            <button onclick="openModalCategory('despesa')"
                class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors"
                title="Configurar Categorias">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>

            <!-- Year Selector -->
            <div
                class="flex items-center gap-2 bg-white dark:bg-slate-800 p-1 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <button onclick="changeYear(-1)"
                    class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-md text-slate-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span id="currentYearLabel"
                    class="font-bold text-slate-700 dark:text-slate-200 min-w-[3rem] text-center">2026</span>
                <button onclick="changeYear(1)"
                    class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-md text-slate-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <!-- Renda Anual -->
        <div
            class="bg-emerald-100/50 dark:bg-emerald-900/20 rounded-2xl p-6 shadow-sm border border-emerald-100 dark:border-emerald-800 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.15-1.46-3.27-3.4h1.96c.1 1.05 1.18 1.91 2.53 1.91 1.35 0 2.53-.86 2.53-1.9s-1.18-1.91-2.53-1.91l-1.07-.13c-1.63-.2-3.42-.9-3.42-3.1 0-1.8 1.44-2.88 3.27-3.24V4h2.66v1.94c1.55.33 2.92 1.33 3.09 3.19h-1.96c-.1-1.08-1.15-1.94-2.43-1.94-1.28 0-2.43.86-2.43 1.94 0 1.03 1.15 1.9 2.43 1.9l1.07.13c1.63.2 3.42.9 3.42 3.1 0 1.83-1.44 2.91-3.27 3.27z" />
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400 mb-1">Renda Anual</p>
                <h3 id="valIncome" class="text-2xl font-bold text-emerald-800 dark:text-emerald-300">Carregando...</h3>
                <div class="flex items-center gap-1 mt-3">
                    <div
                        class="bg-emerald-200/50 text-emerald-700 dark:bg-emerald-800 dark:text-emerald-300 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                        Entradas
                    </div>
                </div>
            </div>
            <div
                class="absolute bottom-4 right-4 bg-emerald-200 dark:bg-emerald-800 p-2 rounded-xl text-emerald-600 dark:text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
            </div>
        </div>

        <!-- Despesas Anuais -->
        <div
            class="bg-rose-100/50 dark:bg-rose-900/20 rounded-2xl p-6 shadow-sm border border-rose-100 dark:border-rose-800 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-rose-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z" />
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-medium text-rose-700 dark:text-rose-400 mb-1">Despesas Anuais</p>
                <h3 id="valExpenses" class="text-2xl font-bold text-rose-800 dark:text-rose-300">Carregando...</h3>
                <div class="flex items-center gap-1 mt-3">
                    <div
                        class="bg-rose-200/50 text-rose-700 dark:bg-rose-800 dark:text-rose-300 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                        Saídas
                    </div>
                </div>
            </div>
            <div
                class="absolute bottom-4 right-4 bg-rose-200 dark:bg-rose-800 p-2 rounded-xl text-rose-600 dark:text-rose-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                </svg>
            </div>
        </div>

        <!-- Saldo Acumulado -->
        <div
            class="bg-blue-100/50 dark:bg-blue-900/20 rounded-2xl p-6 shadow-sm border border-blue-100 dark:border-blue-800 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 10h12v2H4zm0-4h12v2H4zm0 8h8v2H4zm10 0v6l5.5-3z" />
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-medium text-blue-700 dark:text-blue-400 mb-1">Saldo Acumulado</p>
                <h3 id="valBalance" class="text-2xl font-bold text-blue-800 dark:text-blue-300">Carregando...</h3>
                <div class="flex items-center gap-1 mt-3">
                    <div
                        class="bg-blue-200/50 text-blue-700 dark:bg-blue-800 dark:text-blue-300 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                        Líquido
                    </div>
                </div>
            </div>
            <div
                class="absolute bottom-4 right-4 bg-blue-200 dark:bg-blue-800 p-2 rounded-xl text-blue-600 dark:text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
        </div>

        <!-- Reserva Anual -->
        <div
            class="bg-[#F8EFE2] dark:bg-amber-900/30 rounded-2xl p-6 shadow-sm border border-amber-100 dark:border-amber-800 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                    <path d="M13.5 13.5h-3v-3h3v3z" />
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-medium text-amber-700 dark:text-amber-400 mb-1">Reserva Anual</p>
                <h3 id="valReserveTotal" class="text-2xl font-bold text-[#C27803] dark:text-amber-500">R$ 0,00</h3>
                <div class="flex items-center gap-1 mt-3">
                    <div
                        class="bg-amber-200/50 text-amber-800 dark:bg-amber-800 dark:text-amber-300 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                        Meta
                    </div>
                </div>
            </div>
            <div
                class="absolute bottom-4 right-4 bg-[#EAD8B1] dark:bg-amber-800 p-2 rounded-xl text-[#C27803] dark:text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

    </div>

    <!-- Second Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- % Metrics & Top Expenses -->
        <!-- % Economizado -->
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">% Economizado</p>
                    <h3 id="valSavingsRate" class="text-4xl font-bold text-slate-800 dark:text-white mt-2">--%</h3>
                </div>
                <!-- Circular indicator simple -->
                <div
                    class="h-12 w-12 rounded-full border-4 border-emerald-500 flex items-center justify-center text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    <span id="circleSavings">--</span>
                </div>
            </div>
            <div class="mt-4">
                <p id="msgSavings" class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">--</p>
                <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full mt-2 overflow-hidden">
                    <div id="barSavings" class="bg-emerald-500 h-full rounded-full transition-all duration-500"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- % Renda Comprometida -->
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">% Comprometida</p>
                    <h3 id="valCommitted" class="text-4xl font-bold text-slate-800 dark:text-white mt-2">--%</h3>
                </div>
                <div
                    class="h-12 w-12 rounded-full border-4 border-rose-500 flex items-center justify-center text-xs font-bold text-rose-600 dark:text-rose-400">
                    <span id="circleCommitted">--</span>
                </div>
            </div>
            <div class="mt-4">
                <p id="msgCommitted" class="text-sm text-rose-600 dark:text-rose-400 font-medium">--</p>
                <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full mt-2 overflow-hidden">
                    <div id="barCommitted" class="bg-rose-500 h-full rounded-full transition-all duration-500"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>
        <!-- Top Expense (Single Item Style) -->
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Maior Categoria</p>
                    <h3 id="valTopExpense" class="text-2xl lg:text-3xl font-bold text-slate-800 dark:text-white mt-2">--
                    </h3>
                </div>
                <!-- Circular indicator -->
                <div
                    class="h-12 w-12 rounded-full border-4 border-rose-500 flex items-center justify-center text-xs font-bold text-rose-600 dark:text-rose-400">
                    <span id="circleTopExpense">--</span>
                </div>
            </div>
            <div class="mt-4">
                <p id="msgTopExpense" class="text-sm text-rose-600 dark:text-rose-400 font-medium truncate">--</p>
                <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full mt-2 overflow-hidden">
                    <div id="barTopExpense" class="bg-rose-500 h-full rounded-full transition-all duration-500"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Pie Chart (Categories) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Despesas por Categoria</h3>
            <div class="relative h-64">
                <canvas id="chartPie"></canvas>
            </div>
            <!-- Custom Legend -->
            <div id="chartPieLegend" class="mt-4"></div>
        </div>

        <!-- Line Chart (Balance Evolution) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
                <h3 class="font-bold text-slate-800 dark:text-white">Evolução do Saldo</h3>
                <!-- Toggles -->
                <div class="flex bg-slate-100 dark:bg-slate-700/50 p-1 rounded-lg self-start sm:self-auto">
                    <button onclick="updateChartMode('mensal')" id="btnChartMensal"
                        class="px-3 py-1 text-xs font-medium rounded-md transition-all text-slate-500 hover:text-slate-700">Mensal</button>
                    <button onclick="updateChartMode('acumulado')" id="btnChartAcumulado"
                        class="px-3 py-1 text-xs font-medium rounded-md transition-all bg-white dark:bg-slate-600 text-blue-600 shadow-sm">Acumulado</button>
                    <button onclick="updateChartMode('despesas')" id="btnChartDespesas"
                        class="px-3 py-1 text-xs font-medium rounded-md transition-all text-slate-500 hover:text-slate-700">Despesas</button>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="chartLine"></canvas>
            </div>
        </div>

        <!-- Bar Chart (In/Out) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Renda vs Despesas</h3>
            <div class="relative h-64">
                <canvas id="chartBar"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div id="tableContainer"
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden transition-all duration-300">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 dark:text-white">Resumo Mensal Detalhado</h3>
            <button onclick="toggleFullscreen()" id="btnFullscreen"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                Ver tudo
            </button>
        </div>
        <!-- Instruction Banner -->
        <div
            class="mb-2 bg-blue-50/50 dark:bg-blue-900/10 px-4 py-2 rounded text-[11px] text-blue-600 dark:text-blue-400 font-medium flex items-center gap-2">
            <span>💡 Duplo clique para editar</span>
            <span>•</span>
            <span>Arraste para reordenar</span>
            <span>•</span>
            <span>Shift+Enter aplica a todos os meses</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-4 py-3 min-w-[150px]">Descrição</th>
                        <th class="px-2 py-3 text-right">Jan</th>
                        <th class="px-2 py-3 text-right">Fev</th>
                        <th class="px-2 py-3 text-right">Mar</th>
                        <th class="px-2 py-3 text-right">Abr</th>
                        <th class="px-2 py-3 text-right">Mai</th>
                        <th class="px-2 py-3 text-right">Jun</th>
                        <th class="px-2 py-3 text-right">Jul</th>
                        <th class="px-2 py-3 text-right">Ago</th>
                        <th class="px-2 py-3 text-right">Set</th>
                        <th class="px-2 py-3 text-right">Out</th>
                        <th class="px-2 py-3 text-right">Nov</th>
                        <th class="px-2 py-3 text-right">Dez</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-slate-700">
                    <!-- Dynamic Rows Here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Expenses (Moved below or keep? User wanted "like budget hub", budget hub has summary lists too. Keeping Top Expenses as supplementary) -->


    <!-- Orçado x Realizado Moved to separate page -->

    <!-- Modals -->

    <!-- Modal Adicionar Lançamento -->
    <div id="modalAdd" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl m-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Novo Lançamento</h3>
            <form id="formAdd">
                <input type="hidden" name="user_id" value="<?php echo getCurrentUserId(); ?>">

                <div class="space-y-4">
                    <!-- Tipo -->
                    <div class="flex bg-slate-100 dark:bg-slate-700 p-1 rounded-lg">
                        <button type="button" onclick="setAddType('receita')" id="btnAddReceita"
                            class="flex-1 py-1.5 rounded-md text-sm font-medium transition-colors text-slate-500">Receita</button>
                        <button type="button" onclick="setAddType('despesa')" id="btnAddDespesa"
                            class="flex-1 py-1.5 rounded-md text-sm font-medium transition-colors bg-white dark:bg-slate-600 text-slate-800 dark:text-white shadow-sm">Despesa</button>
                    </div>
                    <input type="hidden" name="tipo" id="inputType" value="despesa">



                    <!-- Valor -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Valor (R$)</label>
                        <input type="number" step="0.01" name="valor" required
                            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    </div>

                    <!-- Categoria -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Categoria</label>
                        <select name="categoria" id="selectCategoria"
                            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                            <!-- Populated via JS -->
                        </select>
                    </div>

                    <!-- Data -->
                    <!-- Data -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Mês de Referência</label>
                        <input type="month" name="data_mes" required value="<?php echo date('Y-m'); ?>"
                            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    </div>

                    <!-- Repeat Logic -->
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700 mt-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="checkRepeat" name="repeat"
                                class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500"
                                onchange="document.getElementById('divRepeatCount').classList.toggle('hidden', !this.checked)">
                            <label for="checkRepeat"
                                class="text-sm text-slate-700 dark:text-slate-300 select-none cursor-pointer">Repetir
                                este lançamento</label>
                        </div>
                        <style>
                            .hide-scrollbar::-webkit-scrollbar {
                                display: none;
                            }

                            /* Fullscreen Custom Styles */
                            .fullscreen-active {
                                position: fixed !important;
                                top: 0;
                                left: 0;
                                width: 100vw !important;
                                height: 100vh !important;
                                z-index: 9999;
                                border-radius: 0 !important;
                                display: flex;
                                flex-direction: column;
                            }

                            .fullscreen-active .overflow-x-auto {
                                flex: 1;
                                height: 100%;
                            }
                        </style>
                        <div id="divRepeatCount" class="hidden mt-2 ml-6">
                            <label class="block text-xs text-slate-500 mb-1">Por quantos meses?</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="repeat_count" min="2" max="120" value="2"
                                    class="w-20 px-2 py-1 text-sm rounded border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <span class="text-xs text-slate-400">(meses consecutivos)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="toggleModal('modalAdd')"
                        class="flex-1 py-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">Cancelar</button>
                    <button type="submit"
                        class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-lg shadow-blue-500/30 transition-shadow">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Configurações (Categorias) -->
    <div id="modalConfig" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg p-6 shadow-2xl m-4 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Gerenciar Categorias</h3>
                <button onclick="toggleModal('modalConfig')" class="text-slate-400 hover:text-slate-600"><svg
                        class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 space-y-6">
                <!-- Add New -->
                <form id="formNewCat" class="flex gap-2">
                    <input type="text" name="nome" placeholder="Nova Categoria..." required
                        class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <select name="tipo"
                        class="px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        <option value="despesa">Despesa</option>
                        <option value="receita">Receita</option>
                    </select>
                    <button type="submit" class="p-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600"><svg
                            class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg></button>
                </form>

                <!-- Lists -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">Despesas</h4>
                    <ul id="listCatDespesa" class="space-y-2"></ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">Receitas</h4>
                    <ul id="listCatReceita" class="space-y-2"></ul>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    console.log("Script loaded!");

    // Default to 2026
    let currentYear = 2026;
    const urlParams = new URLSearchParams(window.location.search);
    const yearParam = urlParams.get('year');
    if (yearParam) currentYear = parseInt(yearParam);

    let charts = {}; // Store chart instances to destroy on update
    let categoriesCache = { receitas: [], despesas: [] };

    function changeYear(delta) {
        currentYear += delta;
        // Update URL to persist year on reload
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('year', currentYear);
        window.history.pushState({}, '', newUrl);

        updateUI();
    }

    // ... (Actions and Init remain same) ...

    function fMoney(val) {
        if (val === undefined || val === null) return 'R$ 0,00';
        return val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function fNum(val) {
        if (val === undefined || val === null) return '0,00';
        return val.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // ...

    async function updateUI() {
        document.getElementById('currentYearLabel').textContent = currentYear;
        document.getElementById('displayYear').textContent = currentYear;

        const valIncome = document.getElementById('valIncome');
        const valExpenses = document.getElementById('valExpenses');
        const valBalance = document.getElementById('valBalance');

        valIncome.classList.add('opacity-50');
        valIncome.textContent = 'Carregando...';

        try {
            const res = await fetch(`api/budget_stats.php?year=${currentYear}`);

            // Debug: Check if response is JSON
            const contentType = res.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                const text = await res.text();
                console.error("API returned non-JSON:", text);
                if (text.includes("login") || text.includes("Login")) {
                    alert("Sessão expirada. Por favor, faça login novamente.");
                    window.location.href = 'login.php';
                    return;
                }
                throw new Error("Resposta inválida da API (Server Error)");
            }

            const data = await res.json();

            if (data.error) {
                console.error('API Error:', data.error);
                valIncome.textContent = 'Erro';
                alert('Erro ao carregar dados: ' + data.error);
                return;
            }

            // --- Summaries ---
            valIncome.textContent = fMoney(data.total_income);
            valExpenses.textContent = fMoney(data.total_expenses);

            valBalance.textContent = fMoney(data.balance);
            if (data.balance < 0) valBalance.classList.replace('text-slate-800', 'text-rose-600');
            else valBalance.classList.replace('text-rose-600', 'text-slate-800');

            // % Stats
            document.getElementById('valSavingsRate').textContent = Math.round(data.savings_rate) + '%';
            document.getElementById('circleSavings').textContent = Math.round(data.savings_rate) + '%';
            document.getElementById('barSavings').style.width = Math.min(100, Math.max(0, data.savings_rate)) + '%';

            let savingsMsg = 'Continue assim!';
            if (data.savings_rate < 10) savingsMsg = 'Atenção, reserva baixa.';
            if (data.savings_rate > 30) savingsMsg = 'Excelente!';
            document.getElementById('msgSavings').textContent = savingsMsg;


            document.getElementById('valCommitted').textContent = Math.round(data.committed_rate) + '%';
            document.getElementById('circleCommitted').textContent = Math.round(data.committed_rate) + '%';
            document.getElementById('barCommitted').style.width = Math.min(100, Math.max(0, data.committed_rate)) + '%';

            let commMsg = 'Saudável';
            if (data.committed_rate > 80) commMsg = 'Cuidado, gastos altos!';
            if (data.committed_rate > 100) commMsg = 'Crítico!';
            document.getElementById('msgCommitted').textContent = commMsg;

            // --- Top Expense (Highest Category Sum) ---
            // Combine lists for calculations
            const catList = [
                ...(data.category_expenses_fixed || []),
                ...(data.category_expenses_variable || [])
            ];
            let topCat = null;
            let maxCatVal = 0;

            catList.forEach(cat => {
                // Sum all monthly values for this category
                const total = cat.values.reduce((a, b) => a + b, 0);
                if (total > maxCatVal) {
                    maxCatVal = total;
                    topCat = cat;
                }
            });

            if (topCat && maxCatVal > 0) {
                const totalExp = parseFloat(data.total_expenses) || 1;
                const pct = Math.min(100, Math.round((maxCatVal / totalExp) * 100));

                document.getElementById('valTopExpense').textContent = fMoney(maxCatVal);
                document.getElementById('circleTopExpense').textContent = pct + '%';
                // Use category name
                document.getElementById('msgTopExpense').textContent = topCat.name || 'Geral';
                document.getElementById('barTopExpense').style.width = pct + '%';
            } else {
                document.getElementById('valTopExpense').textContent = 'R$ 0,00';
                document.getElementById('circleTopExpense').textContent = '0%';
                document.getElementById('msgTopExpense').textContent = 'Nenhuma despesa';
                document.getElementById('barTopExpense').style.width = '0%';
            }

            // Averages
            // Averages removed from HTML, so removed from JS to avoid null error.
            /* 
            document.getElementById('avgIncome').textContent = fMoney(data.monthly_average_income);
            document.getElementById('avgExpenses').textContent = fMoney(data.monthly_average_expenses);
            */

            // --- Render Charts ---
            renderCharts(data);

            // --- Render Table ---
            renderTable(data);

            /*
             * Render Comparison moved to orcado_realizado.php
             */

                } catch (e) {
            console.error("Error i            n updateUI:", e);
            alert("Erro ao atualizar interface: " + e.message);
        }
    } // End of updateUI

    function renderCharts(data) {
            // Colors
            const colors = {
                blue: '#3b82f6',
                emerald: '#10b981',
                rose: '#f43f5e',
                slate: '#64748b',
                purple: '#8b5cf6',
                orange: '#f97316'
            };
            const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

            // Destroy old
            if (charts.pie) charts.pie.destroy();
            if (charts.line) charts.line.destroy();
            if (charts.bar) charts.bar.destroy();

            // 1. Pie Chart (Fixed vs Variable)
            const totalFixed = (data.monthly_expenses_fixed || []).reduce((a, b) => a + b, 0);
            const totalVariable = (data.monthly_expenses_variable || []).reduce((a, b) => a + b, 0);
            const totalAll = totalFixed + totalVariable;

            const catLabels = ['Despesas Fixas', 'Despesas Variáveis'];
            const catValues = [totalFixed, totalVariable];
            const pieColors = [colors.rose, colors.purple];

            // Register Plugin if not globally registered (safe to try)
            if (typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }

            charts.pie = new Chart(document.getElementById('chartPie'), {
                type: 'pie',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catValues,
                        backgroundColor: pieColors,
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }, // Hide default legend
                        datalabels: {
                            color: '#fff',
                            font: { weight: 'bold', size: 12 },
                            formatter: (value, ctx) => {
                                if (totalAll === 0) return '0%';
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => { sum += data; });
                                let percentage = (value * 100 / sum).toFixed(1) + "%";
                                return percentage;
                            },
                            display: (context) => {
                                return context.dataset.data[context.dataIndex] > 0; // Only show if > 0
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed !== null) {
                                        label += fMoney(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    cutout: '0%' // Full Pie
                }
            });

            // Custom Legend HTML
            const legendContainer = document.getElementById('chartPieLegend'); // Ensure this element exists!
            if (legendContainer) {
                legendContainer.innerHTML = `
                <div class="flex flex-col gap-2 justify-center mt-2">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: ${colors.rose}"></span>
                            <span class="text-slate-600 dark:text-slate-300">Despesas Fixas</span>
                        </div>
                        <span class="font-bold text-slate-700 dark:text-slate-200">${fMoney(totalFixed)}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: ${colors.purple}"></span>
                            <span class="text-slate-600 dark:text-slate-300">Despesas Variáveis</span>
                        </div>
                        <span class="font-bold text-slate-700 dark:text-slate-200">${fMoney(totalVariable)}</span>
                    </div>
                </div>
            `;
            }

            // 2. Line Chart (Balance)
            charts.line = new Chart(document.getElementById('chartLine'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Acumulado', // Index 0
                            data: data.accumulated_balance,
                            borderColor: '#eab308', // Amber/Yellow
                            tension: 0.4,
                            fill: true,
                            backgroundColor: (ctx) => {
                                const grad = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                                grad.addColorStop(0, 'rgba(234, 179, 8, 0.2)');
                                grad.addColorStop(1, 'rgba(234, 179, 8, 0)');
                                return grad;
                            },
                            hidden: false // Default
                        },
                        {
                            label: 'Saldo Mensal', // Index 1
                            data: data.monthly_balance,
                            borderColor: colors.blue,
                            backgroundColor: colors.blue,
                            tension: 0.4,
                            fill: false,
                            hidden: true
                        },
                        {
                            label: 'Despesas', // Index 2
                            data: data.monthly_expenses,
                            borderColor: colors.rose,
                            backgroundColor: colors.rose,
                            tension: 0.4,
                            fill: false,
                            hidden: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        datalabels: { display: false } // Disable values on chart
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { borderDash: [2, 4] } }
                    }
                }
            });

            // 3. Bar Chart (Income vs Expense)
            charts.bar = new Chart(document.getElementById('chartBar'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Renda',
                            data: data.monthly_income,
                            backgroundColor: colors.emerald,
                            borderRadius: 4,
                            barPercentage: 0.4
                        },
                        {
                            label: 'Despesas',
                            data: data.monthly_expenses,
                            backgroundColor: colors.rose,
                            borderRadius: 4,
                            barPercentage: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        datalabels: { display: false } // Disable values on chart
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        function updateChartMode(mode) {
            if (!charts.line) return;

            const chart = charts.line;
            const btnM = document.getElementById('btnChartMensal');
            const btnA = document.getElementById('btnChartAcumulado');
            const btnD = document.getElementById('btnChartDespesas');

            // Reset Buttons
            const baseClass = "px-3 py-1 text-xs font-medium rounded-md transition-all text-slate-500 hover:text-slate-700 bg-transparent shadow-none";
            const activeClass = "px-3 py-1 text-xs font-medium rounded-md transition-all bg-white dark:bg-slate-600 text-blue-600 shadow-sm";

            btnM.className = baseClass;
            btnA.className = baseClass;
            btnD.className = baseClass;

            // Hide all first
            chart.data.datasets.forEach(ds => ds.hidden = true);

            if (mode === 'acumulado') {
                chart.data.datasets[0].hidden = false; // Acumulado
                btnA.className = activeClass;
                // Style adjustment if needed
                chart.data.datasets[0].fill = true;
            } else if (mode === 'mensal') {
                chart.data.datasets[1].hidden = false; // Mensal
                btnM.className = activeClass;
            } else if (mode === 'despesas') {
                chart.data.datasets[2].hidden = false; // Despesas
                btnD.className = activeClass;
            }

            chart.update();
        }

        // --- UI/UX Helpers ---
        let dragSrcEl = null;

        function handleDragStart(e) {
            console.log('Drag Start', this);
            dragSrcEl = this;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
            this.classList.add('opacity-50');
        }

        function handleDragOver(e) {
            if (e.preventDefault) e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        function handleDrop(e) {
            if (e.stopPropagation) e.stopPropagation();

            const targetRow = this;
            // Check if valid drop
            if (dragSrcEl === targetRow || dragSrcEl.dataset.type !== targetRow.dataset.type) {
                return false;
            }

            // DOM Swap Logic
            const tbody = targetRow.parentNode;
            const rect = targetRow.getBoundingClientRect();
            const relY = e.clientY - rect.top;

            if (relY < rect.height / 2) {
                tbody.insertBefore(dragSrcEl, targetRow);
            } else {
                tbody.insertBefore(dragSrcEl, targetRow.nextSibling);
            }

            dragSrcEl.classList.remove('opacity-50');

            // Save immediately
            saveOrder(dragSrcEl.dataset.type);
            return false;
        }

        async function saveOrder(type) {
            const rows = document.querySelectorAll(`tr[data-type="${type}"]`);
            const orderData = [];

            // Offset Logic:
            // Income (receita) and Fixed Expenses (despesa_fixa) start at 0 (or close to).
            // Variable Expenses (despesa_variavel) start at 1000.
            // This ensures unique ordering in the DB.
            let offset = 0;
            if (type === 'despesa_variavel') {
                offset = 1000;
            }

            // Show saving state
            if (rows.length > 0) {
                const originalOpacity = rows[0].style.opacity;
                rows.forEach((r, idx) => {
                    r.style.opacity = '0.7';
                    orderData.push({ id: parseInt(r.dataset.id), ordem: idx + offset });
                });
            }

            console.log('Saving order...', type, orderData);

            try {
                const res = await fetch('api/categories.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: orderData })
                });
                const json = await res.json();

                if (json.success) {
                    console.log('Order saved. Reloading table...');
                    // Slight delay to let DB settle if replicated, then reload full UI
                    setTimeout(() => {
                        updateUI();
                    }, 200);
                } else {
                    alert('Erro ao salvar ordem: ' + (json.error || 'Erro desconhecido'));
                    updateUI();
                }
            } catch (e) {
                console.error(e);
                alert('Erro de conexão ao salvar ordem');
                updateUI();
            }
        }

        async function moveCategory(id, direction) {
            // Find row
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (!row) return;

            const parent = row.parentNode;
            const type = row.dataset.type;

            // Find sibling to swap with
            if (direction === -1) {
                // Move Up
                const prev = row.previousElementSibling;
                // Check if prev is same type AND not a header/spacer
                if (prev && prev.dataset.type === type) {
                    parent.insertBefore(row, prev);
                    saveOrder(type);
                }
            } else {
                // Move Down
                const next = row.nextElementSibling;
                if (next && next.dataset.type === type) {
                    parent.insertBefore(next, row); // Insert next before row = swap
                    saveOrder(type);
                }
            }
        }



        async function duplicateCategory(id) {
            if (!confirm('Duplicar esta categoria e seus lançamentos?')) return;
            try {
                const res = await fetch('api/duplicate_category_row.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id, year: currentYear })
                });
                if (res.ok) updateUI();
            } catch (e) { console.error(e); }
        }

        async function deleteCategory(id) {
            if (!confirm('Tem certeza? Isso apagará a categoria da lista.')) return;
            try {
                const res = await fetch(`api/categories.php?id=${id}`, { method: 'DELETE' });
                if (res.ok) updateUI();
            } catch (e) { console.error(e); }
        }

        // --- Editing Logic ---
        function makeEditable(td) {
            if (td.querySelector('input')) return;

            const originalValue = td.innerText;
            const numericValue = parseMoney(originalValue);

            const input = document.createElement('input');
            input.type = 'number';
            input.step = '0.01';
            input.value = numericValue;
            input.className = 'w-full text-right p-1 rounded bg-white dark:bg-slate-900 border border-blue-400 focus:outline-none text-xs';

            const save = async (applyAll = false) => {
                const newVal = parseFloat(input.value);
                if (isNaN(newVal)) { td.innerText = originalValue; return; }

                // Optimistic UI
                td.innerText = fNum(newVal);
                if (applyAll) {
                    // Update all siblings for visual feedback instantly? 
                    // Hard to find them all cleanly without reload. 
                    // Let's just wait for API.
                    td.innerText = "Salving...";
                }

                const cat = td.dataset.cat;
                const month = td.dataset.month;
                const type = td.dataset.reqtype; // 'receita' or 'despesa'

                try {
                    const res = await fetch('api/update_forecast_cell.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            category: cat,
                            month: month,
                            year: currentYear,
                            type: type,
                            value: newVal,
                            apply_all: applyAll
                        })
                    });
                    if (res.ok) {
                        await updateUI();
                    } else {
                        td.innerText = originalValue;
                    }
                } catch (e) {
                    console.error(e);
                    td.innerText = originalValue;
                }
            };

            // Single robust listener setup
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (e.shiftKey) {
                        save(true); // Default behavior for Shift+Enter (Apply All)
                    } else {
                        input.blur(); // Default behavior (Save Single)
                    }
                }
            });

            // Blur always saves
            input.addEventListener('blur', () => save(false));

            td.innerHTML = '';
            td.appendChild(input);
            input.focus();
        }

        function makeCategoryEditable(span, id) {
            if (span.querySelector('input')) return;

            const originalName = span.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = originalName;
            input.className = 'w-full px-1 text-xs border border-blue-400 rounded focus:outline-none bg-white text-slate-800';

            const save = async () => {
                const newName = input.value.trim();
                if (!newName || newName === originalName) {
                    span.textContent = originalName;
                    return;
                }

                try {
                    const res = await fetch('api/categories.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id, nome: newName })
                    });
                    const json = await res.json();
                    if (json.success) {
                        updateUI(); // Refresh to ensure backend match
                    } else {
                        alert('Erro: ' + (json.error || 'Falha ao renomear'));
                        span.textContent = originalName;
                    }
                } catch (e) {
                    console.error(e);
                    span.textContent = originalName;
                }
            };

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    input.blur();
                }
            });

            input.addEventListener('blur', save);
            span.innerHTML = '';
            span.appendChild(input);
            input.focus();
        }

        function parseMoney(str) {
            if (!str) return 0;
            return parseFloat(str.replace(/\./g, '').replace(',', '.'));
        }

        let reservePercentage = 20;

        function renderTable(data) {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            // Instruction Header (Outside TBODY or First Row?)
            // Let's put it as a first row for visibility
            // Actually better to have it in the HTML structure above table, checking user request logic.
            // User image shows it above headers. 
            // We will inject it via HTML, but here we can add it as a row if needed.
            // Let's stick to standard rows.

            // 1. TOTAL RENDA HEADER
            const trIncHeader = document.createElement('tr');
            trIncHeader.className = 'bg-emerald-50 dark:bg-emerald-900/20 font-bold border-b border-emerald-100 dark:border-emerald-800';
            let htmlInc = `
            <td class="px-4 py-3 text-emerald-700 dark:text-emerald-400 text-sm flex items-center justify-between group">
                <span>TOTAL RENDA</span>
                <button onclick="openModalCategory('receita')" class="text-[9px] uppercase bg-white dark:bg-emerald-900 border border-emerald-200 rounded px-1.5 py-0 hover:bg-emerald-100 transition shadow-sm opacity-100 font-normal">+ Add</button>
            </td>
        `;
            data.monthly_income.forEach(val => {
                htmlInc += `<td class="px-1 py-2 text-right text-emerald-700 dark:text-emerald-400 text-[10px] sm:text-xs font-mono">${fNum(val)}</td>`;
            });
            trIncHeader.innerHTML = htmlInc;
            tbody.appendChild(trIncHeader);

            // 2. INCOME CATEGORIES (Children)
            // Sort by 'id' or 'index'? API returns sorted array 'category_income'
            if (data.category_income) {
                data.category_income.forEach(cat => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 dark:hover:bg-slate-800/50 border-b border-slate-50 dark:border-slate-800 transition-colors group';
                    tr.draggable = true;
                    tr.dataset.id = cat.id;
                    tr.dataset.type = 'receita'; // For sorting group

                    tr.addEventListener('dragstart', handleDragStart);
                    tr.addEventListener('dragover', handleDragOver);
                    tr.addEventListener('drop', handleDrop);

                    // Meta Cell
                    const metaHtml = `
                    <div class="flex items-center gap-1 pl-2">
                        <!-- Drag Handle -->
                        <span class="cursor-move text-slate-300 hover:text-slate-500 opacity-0 group-hover:opacity-100 transition-opacity">
                           <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                        </span>
                        
                        <!-- Category Name -->
                        <div class="flex items-center gap-1.5 w-full">
                             <span class="text-xs text-slate-700 dark:text-slate-200 font-medium truncate cursor-text hover:text-blue-600 border-b border-transparent hover:border-blue-300" ondblclick="makeCategoryEditable(this, ${cat.id})">${cat.name}</span>
                        </div>

                        <!-- Controls -->
                        <div class="flex items-center gap-0.5 ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="moveCategory(${cat.id}, -1)" title="Mover para Cima" class="text-slate-400 hover:text-blue-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <button onclick="moveCategory(${cat.id}, 1)" title="Mover para Baixo" class="text-slate-400 hover:text-blue-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="w-px h-2 bg-slate-300 mx-0.5"></div>
                            <button onclick="duplicateCategory(${cat.id})" title="Duplicar" class="text-slate-400 hover:text-blue-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                            </button>
                            <button onclick="deleteCategory(${cat.id})" title="Excluir" class="text-slate-400 hover:text-red-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                `;

                    let html = `<td class="py-2 w-48 max-w-[200px]">${metaHtml}</td>`;

                    // Values
                    cat.values.forEach((val, idx) => {
                        html += `<td 
                        class="px-1 py-1 text-right text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs tabular-nums cursor-pointer hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors"
                        ondblclick="makeEditable(this)"
                        data-cat="${cat.name}"
                        data-month="${idx + 1}"
                        data-reqtype="receita"
                   >${fNum(val)}</td>`;
                    });

                    tr.innerHTML = html;
                    tbody.appendChild(tr);
                });
            }


            // --- HELPER FOR RENDERING CATEGORY ROWS ---
            const renderCategoryRows = (list, type) => {
                if (!list) return;
                list.forEach(cat => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 dark:hover:bg-slate-800/50 border-b border-slate-50 dark:border-slate-800 transition-colors group';
                    tr.draggable = true;
                    tr.dataset.id = cat.id;
                    tr.dataset.type = type;

                    tr.addEventListener('dragstart', handleDragStart);
                    tr.addEventListener('dragover', handleDragOver);
                    tr.addEventListener('drop', handleDrop);

                    // Meta Cell
                    const metaHtml = `
                    <div class="flex items-center gap-1 pl-2">
                        <!-- Drag Handle -->
                        <span class="cursor-move text-slate-300 hover:text-slate-500 opacity-0 group-hover:opacity-100 transition-opacity">
                           <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                        </span>
                        
                        <!-- Category Name -->
                        <div class="flex items-center gap-1.5 w-full">
                             <span class="text-xs text-slate-700 dark:text-slate-200 font-medium truncate cursor-text hover:text-blue-600 border-b border-transparent hover:border-blue-300" ondblclick="makeCategoryEditable(this, ${cat.id})">${cat.name}</span>
                        </div>

                        <!-- Controls -->
                        <div class="flex items-center gap-0.5 ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="moveCategory(${cat.id}, -1)" title="Mover para Cima" class="text-slate-400 hover:text-blue-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <button onclick="moveCategory(${cat.id}, 1)" title="Mover para Baixo" class="text-slate-400 hover:text-blue-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="w-px h-2 bg-slate-300 mx-0.5"></div>
                            <button onclick="duplicateCategory(${cat.id})" title="Duplicar" class="text-slate-400 hover:text-blue-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                            </button>
                            <button onclick="deleteCategory(${cat.id})" title="Excluir" class="text-slate-400 hover:text-red-500 p-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                `;

                    let html = `<td class="py-2 w-48 max-w-[200px]">${metaHtml}</td>`;

                    // Values
                    cat.values.forEach((val, idx) => {
                        html += `<td 
                        class="px-1 py-1 text-right text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs tabular-nums cursor-pointer hover:bg-rose-50/50 dark:hover:bg-rose-900/10 transition-colors"
                        ondblclick="makeEditable(this)"
                        data-cat="${cat.name}"
                        data-month="${idx + 1}"
                        data-reqtype="despesa"
                   >${fNum(val)}</td>`;
                    });

                    tr.innerHTML = html;
                    tbody.appendChild(tr);
                });
            };

            // Spacer
            tbody.innerHTML += '<tr><td colspan="13" class="h-4"></td></tr>';

            // 3. TOTAL DESPESAS FIXAS HEADER
            const trExpHeader = document.createElement('tr');
            trExpHeader.className = 'bg-rose-50 dark:bg-rose-900/20 font-bold border-b border-rose-100 dark:border-rose-800';
            let htmlExp = `
            <td class="px-4 py-3 text-rose-700 dark:text-rose-400 text-sm flex items-center justify-between group">
                <span class="uppercase">TOTAL DESPESAS FIXAS</span>
                <button onclick="openModalCategory('despesa')" class="text-[9px] uppercase bg-white dark:bg-rose-900 border border-rose-200 rounded px-1.5 py-0 hover:bg-rose-100 transition shadow-sm opacity-100 font-normal">+ Add</button>
            </td>
        `;

            // FIXED Expenses Totals
            data.monthly_expenses_fixed.forEach(val => {
                htmlExp += `<td class="px-1 py-2 text-right text-rose-700 dark:text-rose-400 text-[10px] sm:text-xs font-mono">${fNum(val)}</td>`;
            });
            trExpHeader.innerHTML = htmlExp;
            tbody.appendChild(trExpHeader);

            // 4a. FIXED EXPENSE CATEGORIES
            renderCategoryRows(data.category_expenses_fixed, 'despesa_fixa'); // Assuming 'despesa' type for common logic

            // 5. TOTAL CARTÕES (VARIABLE) HEADER (New Section)
            // Only render if there are variable expenses to show, or always? Always for consistency with user request
            const trVarHeader = document.createElement('tr');
            trVarHeader.className = 'bg-purple-50 dark:bg-purple-900/20 font-bold border-b border-purple-100 dark:border-purple-800';
            let htmlVar = `
            <td class="px-4 py-3 text-purple-700 dark:text-purple-400 text-sm flex items-center justify-between group">
                <span class="uppercase">TOTAL CARTÕES (Variável)</span>
                <button onclick="openModalCategory('despesa')" class="text-[9px] uppercase bg-white dark:bg-purple-900 border border-purple-200 rounded px-1.5 py-0 hover:bg-purple-100 transition shadow-sm opacity-100 font-normal">+ Add</button>
            </td>
        `;
            // VARIABLE Expenses Totals
            data.monthly_expenses_variable.forEach(val => {
                htmlVar += `<td class="px-1 py-2 text-right text-purple-700 dark:text-purple-400 text-[10px] sm:text-xs font-mono">${fNum(val)}</td>`;
            });
            trVarHeader.innerHTML = htmlVar;
            tbody.appendChild(trVarHeader); // Append the variable header

            // 5b. VARIABLE EXPENSE CATEGORIES
            renderCategoryRows(data.category_expenses_variable, 'despesa_variavel');


            // Spacer
            tbody.innerHTML += '<tr><td colspan="13" class="h-6"></td></tr>';

            // --- SUMMARY SECTION (RESUMO MENSAL) ---

            // Header
            const trSum = document.createElement('tr');
            trSum.className = 'bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700';
            trSum.innerHTML = '<td colspan="13" class="px-4 py-2 text-slate-800 dark:text-slate-200 uppercase tracking-wider text-[10px] font-bold">RESUMO MENSAL</td>';
            tbody.appendChild(trSum);

            const createSummaryRow = (title, values, type) => {
                const tr = document.createElement('tr');
                let bgClass = '', textClass = 'font-bold';

                if (type === 'income') { bgClass = 'bg-emerald-50 dark:bg-emerald-900/20'; textClass = 'text-emerald-700 dark:text-emerald-400 font-bold'; }
                if (type === 'expense') { bgClass = 'bg-rose-50 dark:bg-rose-900/20'; textClass = 'text-rose-700 dark:text-rose-400 font-bold'; }
                if (type === 'balance') { bgClass = 'bg-slate-50 dark:bg-slate-800/50'; textClass = 'text-slate-900 dark:text-white font-bold'; }
                if (type === 'accumulated') { bgClass = 'bg-white dark:bg-slate-900'; textClass = 'text-blue-600 dark:text-blue-400 font-bold'; }
                if (type === 'reserve') { bgClass = 'bg-amber-50 dark:bg-amber-900/20'; textClass = 'text-amber-700 dark:text-amber-500 font-bold'; }

                tr.className = `${bgClass} border-b border-slate-100 dark:border-slate-700/50`;

                let titleHtml = title;
                if (type === 'reserve') {
                    titleHtml = `
                    <div class="flex items-center gap-2">
                        <span>RESERVA</span>
                        <input type="number" id="inputReserve" value="${reservePercentage}" min="0" max="100" 
                            class="w-12 px-1 py-0.5 text-center text-xs border border-amber-300 rounded focus:outline-none focus:border-amber-500 bg-white"
                            onchange="updateReserve(this.value)">
                        <span>%</span>
                    </div>
                `;
                }

                let html = `<td class="px-4 py-2 text-xs ${textClass} whitespace-nowrap">${titleHtml}</td>`;
                values.forEach(val => {
                    html += `<td class="px-2 py-2 text-right text-xs font-mono ${textClass}">${fNum(val)}</td>`;
                });
                tr.innerHTML = html;
                tbody.appendChild(tr);
            };

            createSummaryRow('Renda', data.monthly_income, 'income');
            createSummaryRow('Despesas', data.monthly_expenses, 'expense');
            createSummaryRow('Saldo do Mês', data.monthly_balance, 'balance');
            createSummaryRow('Saldo Acumulado', data.accumulated_balance, 'accumulated');

            if (data.user_config_reserve !== undefined) {
                if (!window.reserveLoaded) {
                    reservePercentage = parseFloat(data.user_config_reserve);
                    // document.getElementById('inputReserve').value = reservePercentage; // Removed: Element doesn't exist yet!
                    window.reserveLoaded = true;
                }
            }

            let totalReserveAnnual = 0;
            const reserveValues = data.monthly_income.map(inc => {
                const r = (inc * reservePercentage / 100);
                totalReserveAnnual += r;
                return r;
            });
            createSummaryRow('RESERVA', reserveValues, 'reserve');

            const elTotalReserve = document.getElementById('valReserveTotal');
            if (elTotalReserve) elTotalReserve.textContent = fMoney(totalReserveAnnual);
        }

        async function updateReserve(val) {
            reservePercentage = parseFloat(val) || 0;
            // Recalculate only local UI first for speed
            // Actually we need to re-run renderTableArrays logic... which is inside updateUI
            // But updateUI fetches data. We should separate render.
            // For now, let's just re-fetch or use local cache of data? 
            // We have `currentData` global? No, `updateUI` fetches.
            // Let's just save to DB and then refresh.

            try {
                await fetch('api/update_reserve.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ valor: reservePercentage })
                });
                updateUI();
            } catch (e) { console.error(e); }
        }

        // --- Modal & Actions Logic ---

        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el) {
                if (el.classList.contains('hidden')) {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                } else {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                }
            }
        }

        function openModalAdd() {
            toggleModal('modalAdd');
            // Reset and init
            document.getElementById('formAdd').reset();
            // Default to Despesa
            setAddType('despesa');
        }

        function setAddType(type) {
            document.getElementById('inputType').value = type;
            const btnRec = document.getElementById('btnAddReceita');
            const btnDes = document.getElementById('btnAddDespesa');

            const activeClass = "flex-1 py-1.5 rounded-md text-sm font-medium transition-colors bg-white dark:bg-slate-600 text-slate-800 dark:text-white shadow-sm";
            const inactiveClass = "flex-1 py-1.5 rounded-md text-sm font-medium transition-colors text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-600";

            if (type === 'receita') {
                btnRec.className = activeClass;
                btnDes.className = inactiveClass;
            } else {
                btnDes.className = activeClass;
                btnRec.className = inactiveClass;
            }

            // Refresh categories in the select
            loadCategoriesSelect(type);
        }

        // Initial load for Add Modal Select
        async function loadCategoriesSelect(type) {
            const sel = document.getElementById('selectCategoria');
            sel.innerHTML = '<option>Carregando...</option>';
            try {
                const res = await fetch('api/categories.php');
                const data = await res.json();
                sel.innerHTML = '';

                const list = type === 'receita' ? (data.receitas || []) : (data.despesas || []);

                if (list.length === 0) {
                    const opt = document.createElement('option');
                    opt.value = "";
                    opt.textContent = "Nenhuma categoria cadastrada";
                    sel.appendChild(opt);
                } else {
                    list.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.nome;
                        opt.textContent = c.nome;
                        sel.appendChild(opt);
                    });
                }
            } catch (e) {
                console.error(e);
                sel.innerHTML = '<option value="">Erro ao carregar</option>';
            }
        }

        // Config Modal
        async function openModalCategory(type) {
            // Pre-select tab logic if needed, for now just open and list
            toggleModal('modalConfig');
            loadCategoriesList();
        }

        async function loadCategoriesList() {
            // Fetch all
            try {
                const res = await fetch('api/categories.php');
                const data = await res.json();

                const listRec = document.getElementById('listCatReceita');
                const listDes = document.getElementById('listCatDespesa');
                listRec.innerHTML = '';
                listDes.innerHTML = '';

                // Helper to render items
                const renderItem = (c, list) => {
                    const li = document.createElement('li');
                    li.className = 'flex justify-between items-center text-sm p-2 bg-slate-50 dark:bg-slate-700/50 rounded';

                    const usedVal = parseFloat(c.total_used) || 0;
                    const usedDisplay = usedVal > 0 ? `<span class="text-xs text-slate-400 mr-2 font-mono">(${fMoney(usedVal)})</span>` : '';

                    li.innerHTML = `
                    <span class="text-slate-700 dark:text-slate-300 flex-1">${c.nome}</span>
                    <div class="flex items-center">
                        ${usedDisplay}
                        <button onclick="deleteCategoryReal(${c.id})" class="text-red-400 hover:text-red-600 p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                `;
                    list.appendChild(li);
                };

                if (data.receitas) data.receitas.forEach(c => renderItem(c, listRec));
                if (data.despesas) data.despesas.forEach(c => renderItem(c, listDes));

            } catch (e) { console.error(e); }
        }

        // Forms
        document.getElementById('formAdd').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);

            // Handle Month Input (YYYY-MM)
            const dateInput = fd.get('data_mes'); // e.g., 2025-12
            let yearOfInput = currentYear;
            if (dateInput && dateInput.length >= 4) {
                yearOfInput = parseInt(dateInput.substring(0, 4));
            }

            const data = Object.fromEntries(fd.entries());

            // Context: Orcamento Page -> Always Forecast
            data.is_forecast = true;

            try {
                const res = await fetch('api/add_transaction.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const json = await res.json();
                if (json.success) {
                    toggleModal('modalAdd');
                    e.target.reset();

                    // If added for a different year, switch to it?
                    if (yearOfInput !== currentYear) {
                        if (confirm(`Lançamento adicionado em ${yearOfInput}. Deseja visualizar este ano?`)) {
                            currentYear = yearOfInput;
                            // Update URL
                            const newUrl = new URL(window.location);
                            newUrl.searchParams.set('year', currentYear);
                            window.history.pushState({}, '', newUrl);
                        }
                    }

                    updateUI(); // Refresh dash
                    // alert('Adicionado com sucesso!');
                } else {
                    alert('Erro: ' + (json.error || 'Falha ao salvar.'));
                }
            } catch (err) {
                console.error(err);
                alert('Erro de conexão.');
            }
        });

        document.getElementById('formNewCat').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);
            const data = Object.fromEntries(fd.entries()); // nome, tipo

            try {
                const res = await fetch('api/categories.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'create', ...data })
                });
                const json = await res.json();
                if (json.success) {
                    document.getElementById('formNewCat').reset();
                    loadCategoriesList(); // Refresh list inside modal
                    updateUI(); // Refresh main table to show new cat row
                } else {
                    alert('Erro: ' + json.error);
                }
            } catch (err) { console.error(err); }
        });

        // Placeholders for Table Actions
        function duplicateCategory(id) {
            // To Implement: Backend duplication
            alert("Funcionalidade em desenvolvimento: Duplicar Categoria " + id);
        }
        async function deleteCategory(id) {
            if (!confirm('Tem certeza que deseja excluir esta categoria?')) return;

            try {
                const res = await fetch(`api/categories.php?id=${id}`, { method: 'DELETE' });
                const json = await res.json();
                if (json.success) {
                    // Remove from local list or just reload
                    loadCategoriesList();
                    // Update select in other modal if open?
                } else {
                    alert('Erro: ' + (json.error || 'Falha ao excluir'));
                }
            } catch (e) {
                console.error(e);
                alert('Erro de conexão');
            }
        }

        async function deleteCategoryReal(id) {
            if (!confirm("Excluir categoria permanentemente?")) return;
            try {
                const res = await fetch(`api/categories.php?id=${id}`, {
                    method: 'DELETE'
                });
                const json = await res.json();
                if (json.success) {
                    loadCategoriesList();
                    updateUI();
                } else {
                    alert(json.error);
                }
            } catch (e) { console.error(e); }
        }


        // Initialize
        updateUI();
        // Fullscreen Logic
        function toggleFullscreen() {
            const container = document.getElementById('tableContainer');
            const btn = document.getElementById('btnFullscreen');

            if (!container.classList.contains('fullscreen-active')) {
                // Enter Fullscreen
                container.classList.add('fullscreen-active');
                btn.textContent = 'Sair da Tela Cheia';
                document.body.style.overflow = 'hidden'; // Prevent scrolling background
            } else {
                // Exit Fullscreen
                container.classList.remove('fullscreen-active');
                btn.textContent = 'Ver tudo';
                document.body.style.overflow = '';
            }
        }
</script>
<?php renderFooter(); ?>