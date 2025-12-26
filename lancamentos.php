<?php
// lancamentos.php
require_once 'src/auth.php';
requireAuth();
require_once 'src/layout.php';

renderHeader('Lançamentos');
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Top Bar: Title + Date Nav -->
    <div
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-4 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3 w-full md:w-auto">
            <h1 class="text-xl font-bold text-slate-800 dark:text-white">Lançamentos</h1>
            <button onclick="openGlobalAddModal()"
                class="p-1 rounded-full text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <button id="prevMonth" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <span id="currentMonthLabel"
                class="text-lg font-medium text-slate-700 dark:text-slate-200 capitalize w-32 text-center">Carregando...</span>
            <button id="nextMonth" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-2">

            <!-- Kebab Menu (Options) -->
            <div class="relative">
                <button onclick="toggleMenuOptions()" class="p-2 text-slate-500 hover:text-blue-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
                <!-- Dropdown -->
                <div id="menuOptionsDropdown"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 hidden z-20">
                    <button onclick="toggleSelectionMode()"
                        class="w-full text-left px-4 py-3 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Selecionar Vários
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs: Todos | Receitas | Despesas -->
    <div class="bg-slate-100 dark:bg-slate-800/50 p-1.5 rounded-xl mb-6 flex gap-1">
        <button onclick="setTabFilter('all')" id="tab-all"
            class="flex-1 py-2 rounded-lg text-sm font-bold transition-all shadow-sm bg-white dark:bg-slate-600 text-slate-800 dark:text-white">
            Todos
        </button>
        <button onclick="setTabFilter('receita')" id="tab-receita"
            class="flex-1 py-2 rounded-lg text-sm font-medium transition-all text-slate-500 dark:text-slate-400 hover:bg-white/50 dark:hover:bg-slate-700/50">
            Receitas
        </button>
        <button onclick="setTabFilter('despesa')" id="tab-despesa"
            class="flex-1 py-2 rounded-lg text-sm font-medium transition-all text-slate-500 dark:text-slate-400 hover:bg-white/50 dark:hover:bg-slate-700/50">
            Despesas
        </button>
    </div>

    <!-- Search Bar -->
    <div class="relative mb-6">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <input type="text" id="searchInput" placeholder="Filtrar por..."
            class="block w-full pl-10 pr-3 py-3 border-none rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-shadow">
    </div>

    <!-- Timeline List -->
    <div id="lancamentosList" class="space-y-8">
        <!-- JS vai preencher aqui -->
        <div class="text-center py-10 text-slate-500">Carregando lançamentos...</div>
    </div>

</div>


<!-- Transaction Detail Modal -->
<div id="detailModal"
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-3xl transform scale-95 transition-transform duration-300"
        id="detailModalContent">

        <!-- Close Button -->
        <button onclick="closeDetailModal()"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex flex-col md:flex-row h-full">

            <!-- Left Side: Main Info & Actions -->
            <div
                class="w-full md:w-5/12 p-8 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-700 flex flex-col items-center justify-center text-center relative overflow-hidden">
                <!-- Background decoration -->
                <div
                    class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-700 to-transparent opacity-50">
                </div>

                <!-- Icon -->
                <div id="modalIconBg"
                    class="h-20 w-20 rounded-full flex items-center justify-center mb-4 shadow-sm transition-colors duration-300">
                    <svg id="modalIcon" xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <!-- Icon path set by JS -->
                    </svg>
                </div>

                <!-- Description & Value -->
                <h2 id="modalDesc" class="text-xl font-bold text-slate-800 dark:text-white mb-1 capitalize">Descricao
                </h2>
                <h3 id="modalValue" class="text-2xl font-bold mb-8">R$ 0,00</h3>

                <!-- Action Buttons Row -->
                <div class="flex items-center justify-center gap-3">
                    <!-- Paid/Status Toggle -->
                    <button
                        class="p-3 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-colors"
                        title="Marcar como Pago/Recebido">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Edit -->
                    <button onclick="editCurrentItem()"
                        class="p-3 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-colors"
                        title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </button>

                    <!-- Copy -->
                    <button onclick="copyCurrentItem()"
                        class="p-3 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-amber-100 hover:text-amber-600 dark:hover:bg-amber-900/30 dark:hover:text-amber-400 transition-colors"
                        title="Duplicar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>

                    <!-- Delete -->
                    <button onclick="deleteCurrentItem()"
                        class="p-3 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-colors"
                        title="Excluir">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Right Side: Details -->
            <div class="w-full md:w-7/12 p-8">
                <div class="grid grid-cols-2 gap-y-6 gap-x-4">

                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide block mb-1">Categoria</span>
                        <span id="modalCategory"
                            class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase">--</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide block mb-1">Conta</span>
                        <span id="modalAccount"
                            class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase">--</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide block mb-1">Data</span>
                        <span id="modalDate" class="text-sm font-semibold text-slate-700 dark:text-slate-300">--</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide block mb-1">Tags</span>
                        <span id="modalTags" class="text-sm font-semibold text-slate-700 dark:text-slate-300">--</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide block mb-1">Anexo</span>
                        <span id="modalAttachment"
                            class="text-sm font-semibold text-slate-700 dark:text-slate-300">--</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide block mb-1">Observação</span>
                        <span id="modalObs" class="text-sm font-semibold text-slate-700 dark:text-slate-300">--</span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add/Edit/Copy Transaction Modal -->
<div id="addModal"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 relative overflow-hidden"
        id="addModalContent">

        <!-- Close Button (Top Right) -->
        <button onclick="closeAddModal()"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 z-10 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-8 pt-10">
            <h2 id="modalTitle" class="text-xl font-medium text-slate-700 dark:text-slate-200 mb-6">Novo Lançamento</h2>

            <form id="addForm" onsubmit="saveTransaction(event)">
                <input type="hidden" name="id" id="editId">

                <!-- Type Selection (Radio style matching image) -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="radio" name="tipo" value="despesa" class="peer sr-only">
                        <span
                            class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-transparent peer-checked:bg-red-500 flex items-center justify-center transition-all mr-2">
                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span
                            class="text-slate-600 dark:text-slate-300 group-hover:text-red-500 transition-colors font-medium">Despesa</span>
                    </label>

                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="radio" name="tipo" value="receita" class="peer sr-only">
                        <span
                            class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-transparent peer-checked:bg-green-500 flex items-center justify-center transition-all mr-2">
                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span
                            class="text-slate-600 dark:text-slate-300 group-hover:text-green-500 transition-colors font-medium">Receita</span>
                    </label>
                </div>

                <div class="space-y-5">
                    <!-- Description -->
                    <div class="relative">
                        <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Descrição</label>
                        <input type="text" name="descricao" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all font-medium placeholder-slate-300"
                            placeholder="Ex: Mercado">
                    </div>

                    <!-- Row: Value | Date -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Valor</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">R$</span>
                                <input type="number" step="0.01" name="valor" required
                                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all font-bold text-lg"
                                    placeholder="0,00">
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Data</label>
                            <input type="date" name="data" required
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all font-medium text-center">
                        </div>
                    </div>

                    <!-- Row: Account | Category -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <label
                                class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Conta/Cartão</label>
                            <select name="conta"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                                <option value="Dinheiro">Carteira</option>
                                <option value="Banco">Banco Principal</option>
                                <option value="Nubank">Nubank</option>
                                <option value="Inter">Inter</option>
                            </select>
                            <!-- Chevron -->
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <label
                                class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Categoria</label>
                            <select name="categoria"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                                <option value="Geral">Geral</option>
                                <option value="Salário">Salário</option>
                                <option value="Alimentação">Alimentação</option>
                                <option value="Transporte">Transporte</option>
                                <option value="Moradia">Moradia</option>
                                <option value="Lazer">Lazer</option>
                                <option value="Saúde">Saúde</option>
                                <option value="Outros">Outros</option>
                            </select>
                            <!-- Chevron -->
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Toggle (Pago/Recebido) -->
                <div class="flex items-center justify-end mt-4 mr-1">
                    <div
                        class="flex items-center gap-3 bg-slate-50 dark:bg-slate-700/50 px-4 py-2 rounded-full border border-slate-100 dark:border-slate-600">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            Status: <span id="statusLabel" class="font-bold text-slate-800 dark:text-white">Pago</span>
                        </span>

                        <label class="inline-flex items-center cursor-pointer relative">
                            <input type="checkbox" name="pago" value="1" class="sr-only peer" checked
                                onchange="document.getElementById('statusLabel').textContent = this.checked ? 'Pago' : 'Pendente'">
                            <div
                                class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-500">
                            </div>
                        </label>
                    </div>
                </div>
                <!-- Hidden Repetir Fields -->
                <div id="repetirFields"
                    class="hidden mt-4 bg-slate-50 dark:bg-slate-700/50 p-4 rounded-xl border border-slate-100 dark:border-slate-600 transition-all duration-300">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label
                                class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Frequência</label>
                            <select name="frequencia"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white outline-none">
                                <option value="mensal">Mensal</option>
                            </select>
                        </div>
                        <div class="w-1/3">
                            <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Vezes</label>
                            <input type="number" name="repetir" value="1" min="1" max="60"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white outline-none text-center">
                        </div>
                    </div>
                </div>

                <!-- Hidden Obs Field -->
                <div id="obsField" class="hidden mt-4 transition-all duration-300">
                    <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Observação</label>
                    <textarea name="observacao" rows="2"
                        class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all placeholder-slate-300"
                        placeholder="Detalhes..."></textarea>
                </div>
        </div>

        <!-- Bottom Section: Action Icons & Submit -->
        <div class="flex flex-col items-center gap-6 mt-8 mb-4">
            <!-- Action Icons Row -->
            <div class="flex justify-center gap-8">
                <!-- Repetir Button -->
                <button type="button" onclick="toggleRepetir()" id="btnRepetir"
                    class="flex flex-col items-center gap-2 group transition-opacity">
                    <div
                        class="w-12 h-12 rounded-full border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-400 group-hover:bg-slate-50 dark:group-hover:bg-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <span class="text-xs text-slate-400">Repetir</span>
                </button>

                <!-- Observação -->
                <button type="button" onclick="toggleObs()" id="btnObs" class="flex flex-col items-center gap-2 group">
                    <div
                        class="w-12 h-12 rounded-full border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-400 group-hover:text-blue-500 group-hover:border-blue-200 bg-white dark:bg-transparent hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <span class="text-xs text-slate-400 group-hover:text-blue-500">Observação</span>
                </button>

                <!-- Anexo (Placeholder) -->
                <button type="button"
                    class="flex flex-col items-center gap-2 group opacity-50 hover:opacity-100 transition-opacity">
                    <div
                        class="w-12 h-12 rounded-full border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-400 group-hover:bg-slate-50 dark:group-hover:bg-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </div>
                    <span class="text-xs text-slate-400">Anexo</span>
                </button>

                <!-- Tags (Placeholder) -->
                <button type="button"
                    class="flex flex-col items-center gap-2 group opacity-50 hover:opacity-100 transition-opacity">
                    <div
                        class="w-12 h-12 rounded-full border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-400 group-hover:bg-slate-50 dark:group-hover:bg-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <span class="text-xs text-slate-400">Tags</span>
                </button>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="h-16 w-16 rounded-full bg-green-500 hover:bg-green-600 text-white shadow-lg shadow-green-500/30 flex items-center justify-center transform hover:scale-105 active:scale-95 transition-all duration-200 border-4 border-white dark:border-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </div>
        </form>

        <script>
            // --- Obs and Repetir Toggle             Logic ---
            function toggleObs() {
                const field = document.getElementById('obsField');
                const btn = document.getElementById('btnObs');

                if (field.classList.contains('hidden')) {
                    field.classList.remove('hidden');
                    btn.querySelector('div').classList.remove('border-slate-200', 'dark:border-slate-600');
                    btn.querySelector('div').classList.add('border-blue-500', 'text-blue-500');
                } else {
                    field.classList.add('hidden');
                    btn.querySelector('div').classList.add('border-slate-200', 'dark:border-slate-600');
                    btn.querySelector('div').classList.remove('border-blue-500', 'text-blue-500');
                }
            }

            function toggleRepetir() {
                const field = document.getElementById('repetirFields');
                const btn = document.getElementById('btnRepetir');
                const input = document.querySelector('input[name="repetir"]');

                if (field.classList.contains('hidden')) {
                    field.classList.remove('hidden');
                    btn.querySelector('div').classList.remove('border-slate-200', 'dark:border-slate-600');
                    btn.querySelector('div').classList.add('border-blue-500', 'text-blue-500');
                    if (input.value == 1) input.value = 2; // Default to 2 if opening
                } else {
                    field.classList.add('hidden');
                    btn.querySelector('div').classList.add('border-slate-200', 'dark:border-slate-600');
                    btn.querySelector('div').classList.remove('border-blue-500', 'text-blue-500');
                    input.value = 1; // Reset to 1
                }
            }
        </script>

        <!-- Filter Modal -->
        <div id="filterModal"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-sm transform scale-95 transition-transform duration-300"
                id="filterModalContent">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Filtrar Lançamentos</h2>

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Tipo</label>
                            <div class="flex flex-col gap-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="filterTipo" value="all" checked
                                        class="form-radio text-blue-600 h-5 w-5" onchange="applyFilters()">
                                    <span class="ml-2 text-slate-700 dark:text-slate-300">Todos</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="filterTipo" value="receita"
                                        class="form-radio text-green-600 h-5 w-5" onchange="applyFilters()">
                                    <span class="ml-2 text-slate-700 dark:text-slate-300">Receitas</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="filterTipo" value="despesa"
                                        class="form-radio text-red-600 h-5 w-5" onchange="applyFilters()">
                                    <span class="ml-2 text-slate-700 dark:text-slate-300">Despesas</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button onclick="closeFilterModal()"
                            class="w-full py-3 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-200 transition-colors">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentDate = new Date();
            let allLancamentos = [];
            let currentItem = null; // Store current item for modal actions
            let filterState = {
                tipo: 'all',
                search: ''
            };
            let isSelectionMode = false;

            function toggleMenuOptions() {
                document.getElementById('menuOptionsDropdown').classList.toggle('hidden');
            }

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                const btn = document.querySelector('button[onclick="toggleMenuOptions()"]');
                const menu = document.getElementById('menuOptionsDropdown');
                if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });

            function toggleSelectionMode() {
                isSelectionMode = !isSelectionMode;
                toggleMenuOptions(); // Close menu

                const checkboxes = document.querySelectorAll('.selection-checkbox');
                checkboxes.forEach(el => el.classList.toggle('hidden', !isSelectionMode));

                // Show/Hide Floating Action Button for Delete
                updateSelectionUI();
            }

            function updateSelection() {
                // Just verify checks
                updateSelectionUI();
            }

            function updateSelectionUI() {
                const checked = document.querySelectorAll('.selection-checkbox input:checked');
                const fab = document.getElementById('fabDelete');
                if (isSelectionMode) {
                    if (!fab) createFabDelete();
                    const btn = document.getElementById('fabDelete');
                    if (checked.length > 0) {
                        btn.classList.remove('translate-y-24');
                        btn.querySelector('span').textContent = `Excluir (${checked.length})`;
                    } else {
                        btn.classList.add('translate-y-24');
                    }
                } else {
                    if (fab) fab.classList.add('translate-y-24');
                    // Uncheck all
                    document.querySelectorAll('.selection-checkbox input').forEach(i => i.checked = false);
                }
            }

            function createFabDelete() {
                const fab = document.createElement('div');
                fab.id = 'fabDelete';
                fab.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-40 transition-transform duration-300 translate-y-24';
                fab.innerHTML = `
            <button onclick="deleteSelected()" class="bg-red-500 text-white px-6 py-3 rounded-full shadow-xl font-bold flex items-center gap-2 hover:bg-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Excluir</span>
            </button>
        `;
                document.body.appendChild(fab);
            }

            async function deleteSelected() {
                const checked = document.querySelectorAll('.selection-checkbox input:checked');
                if (checked.length === 0) return;

                if (!confirm(`Tem certeza que deseja excluir ${checked.length} itens?`)) return;

                const items = Array.from(checked).map(cb => ({
                    id: cb.dataset.id,
                    tipo: cb.dataset.tipo
                }));

                try {
                    const res = await fetch('api/bulk_delete_transactions.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ items })
                    });
                    const json = await res.json();
                    if (json.success) {
                        // Success
                        fetchLancamentos(); // Refresh items
                    } else {
                        alert('Erro: ' + (json.error || 'Falha desconhecida'));
                    }
                } catch (e) {
                    console.error(e);
                    alert('Erro de conexão');
                }
            }

            function handleItemClick(e, item) {
                if (isSelectionMode) {
                    // If clicking item, toggle checkbox
                    // Avoid double toggle if clicked checkbox directly
                    if (e.target.tagName !== 'INPUT') {
                        const row = e.currentTarget.closest('.group');
                        const cb = row.querySelector('input[type="checkbox"]');
                        cb.checked = !cb.checked;
                        updateSelection();
                    }
                } else {
                    openDetailModal(item);
                }
            }


            const monthNames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
            ];

            function updateMonthLabel() {
                const label = document.getElementById('currentMonthLabel');
                label.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            }

            async function fetchLancamentos() {
                const year = currentDate.getFullYear();
                const month = String(currentDate.getMonth() + 1).padStart(2, '0');

                document.getElementById('lancamentosList').innerHTML = '<div class="text-center py-10 text-slate-500">Carregando...</div>';

                try {
                    const response = await fetch(`api/list_lancamentos.php?mes=${year}-${month}&t=${new Date().getTime()}`);
                    const data = await response.json();

                    allLancamentos = data;
                    applyFilters(); // Render with current filters
                } catch (error) {
                    console.error('Erro:', error);
                    document.getElementById('lancamentosList').innerHTML = '<div class="text-center py-10 text-red-500">Erro ao carregar dados.</div>';
                }
            }

            function applyFilters() {
                // Get filter values
                const searchVal = document.getElementById('searchInput').value.toLowerCase();
                const typeVal = filterState.tipo;

                // Filter logic
                const filtered = allLancamentos.filter(item => {
                    const matchesSearch = item.descricao.toLowerCase().includes(searchVal) || item.categoria.toLowerCase().includes(searchVal);
                    const matchesType = typeVal === 'all' || item.tipo === typeVal;
                    return matchesSearch && matchesType;
                });

                renderLancamentos(filtered);
            }

            // Connect search input
            document.getElementById('searchInput').addEventListener('input', applyFilters);

            function setTabFilter(type) {
                filterState.tipo = type;

                // Update UI classes
                ['all', 'receita', 'despesa'].forEach(t => {
                    const btn = document.getElementById(`tab-${t}`);
                    if (t === type) {
                        btn.className = 'flex-1 py-2 rounded-lg text-sm font-bold transition-all shadow-sm bg-white dark:bg-slate-600 text-slate-800 dark:text-white';
                    } else {
                        btn.className = 'flex-1 py-2 rounded-lg text-sm font-medium transition-all text-slate-500 dark:text-slate-400 hover:bg-white/50 dark:hover:bg-slate-700/50';
                    }
                });

                applyFilters();
            }

            function renderLancamentos(lancamentos) {
                const container = document.getElementById('lancamentosList');
                container.innerHTML = '';

                if (lancamentos.length === 0) {
                    container.innerHTML = '<div class="text-center py-10 text-slate-500">Nenhum lançamento encontrado.</div>';
                    return;
                }

                // Agrupar por dia
                const grouped = {};
                lancamentos.forEach(item => {
                    const day = item.data.split('-')[2]; // YYYY-MM-DD
                    if (!grouped[day]) grouped[day] = [];
                    grouped[day].push(item);
                });

                // Ordernar dias crescente (1, 2, 3...)
                const days = Object.keys(grouped).sort((a, b) => a - b);

                days.forEach(day => {
                    const items = grouped[day];

                    const isFiltered = filterState.tipo !== 'all' || document.getElementById('searchInput').value !== '';

                    // Get closing balance from the last item of the day (first in DESC list)
                    let closingBalance = 0;
                    let closingPrevisto = 0;
                    if (items.length > 0) {
                        closingBalance = parseFloat(items[0].saldo_acumulado);
                        // Fallback to 0 if undefined (backend not updated yet in dev)
                        closingPrevisto = items[0].saldo_previsto ? parseFloat(items[0].saldo_previsto) : 0;
                    }

                    // Saldo do dia row (agora com acumulado e previsto)
                    let summaryHtml = '';
                    if (!isFiltered) {
                        summaryHtml = `
                    <div class="flex flex-col items-end gap-1 mt-2 pr-2 opacity-70">
                        <div class="flex items-center gap-2">
                             <span class="text-[10px] text-slate-500 uppercase tracking-wider">Saldo acumulado</span>
                             <span class="font-bold text-xs ${closingBalance >= 0 ? 'text-blue-600' : 'text-red-600'}">
                                 R$ ${closingBalance.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                             </span>
                        </div>
                        <div class="flex items-center gap-2">
                             <span class="text-[10px] text-slate-400 uppercase tracking-wider">Saldo previsto</span>
                             <span class="font-bold text-xs ${closingPrevisto >= 0 ? 'text-slate-600 dark:text-slate-300' : 'text-red-400'}">
                                 R$ ${closingPrevisto.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                             </span>
                        </div>
                    </div>
                `;
                    }

                    // HTML do dia
                    const dayBlock = document.createElement('div');
                    // Reduced gap between date and items
                    dayBlock.className = 'flex gap-3';

                    // Coluna do Dia (Esquerda)
                    const dateCol = document.createElement('div');
                    // Adjusted width and padding
                    dateCol.className = 'flex-shrink-0 w-8 text-center pt-1';
                    dateCol.innerHTML = `<span class="text-xl font-bold text-slate-300 block">${day}</span>`;

                    // Lista de Itens (Direita)
                    const itemsCol = document.createElement('div');
                    // Reduced space between items
                    itemsCol.className = 'flex-grow space-y-2';

                    items.forEach((item, index) => {
                        const isReceita = item.tipo === 'receita';
                        const val = parseFloat(item.valor);
                        const formattedVal = val.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                        const iconBg = isReceita ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600';

                        // Status Logic
                        // Ensure we catch all Postgres/PHP truthy variations
                        // DEBUG: Log first few items to see what we get
                        if (index < 3) console.log('Item ID', item.id, 'Pago Raw:', item.pago, 'Type:', typeof item.pago);

                        const isPaid = item.pago === true || item.pago === 't' || item.pago === 'true' || item.pago === 1 || item.pago === '1';
                        const statusColor = isPaid ? 'text-green-500' : 'text-slate-300 hover:text-red-500';
                        const statusIcon = isPaid
                            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />' // Thumb Up
                            : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />'; // Thumb Down

                        const el = document.createElement('div');
                        // Compact: py-3 px-4 instead of p-4
                        el.innerHTML = `
                <div class="bg-white dark:bg-slate-800 py-3 px-4 rounded-xl shadow-sm flex justify-between items-center transition-transform hover:scale-[1.005] group border border-slate-100 dark:border-slate-700 relative">
                    
                    <!-- Selection Checkbox -->
                    <div class="selection-checkbox hidden mr-3">
                        <input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-red-500 focus:ring-red-500" 
                            onchange="updateSelection()" 
                            data-id="${item.id}" data-tipo="${item.tipo}">
                    </div>

                    <div class="flex items-center gap-3 flex-grow cursor-pointer" onclick='handleItemClick(event, ${JSON.stringify(item).replace(/'/g, "&#39;")})'>
                        <!-- Smaller Icon: h-8 w-8 -->
                        <div class="h-8 w-8 rounded-full ${iconBg} flex items-center justify-center flex-shrink-0">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 ${isReceita
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />'}
                             </svg>
                        </div>
                        <div>
                            <!-- Smaller text -->
                            <p class="text-sm font-bold text-slate-800 dark:text-white capitalize">${item.descricao}</p>
                            <p class="text-[10px] text-slate-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                ${item.categoria} ${item.conta ? `• ${item.conta}` : ''}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="text-right cursor-pointer" onclick='handleItemClick(event, ${JSON.stringify(item).replace(/'/g, "&#39;")})'>
                             <p class="text-sm font-bold ${isReceita ? 'text-green-500' : 'text-slate-800 dark:text-white'}">
                                 ${isReceita ? '+' : '-'}R$ ${formattedVal}
                             </p>
                        </div>
                        
                        <!-- Status Toggle Button (Smaller padding) -->
                        <button onclick="toggleStatus(${item.id}, '${item.tipo}', event)" class="p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors ${statusColor}" title="${isPaid ? 'Marcar como Não Pago' : 'Marcar como Pago'}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="${isPaid ? 'currentColor' : 'none'}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                ${statusIcon}
                            </svg>
                        </button>
                    </div>
                </div>
                `;
                        itemsCol.appendChild(el.firstElementChild);
                    });

                    // Add summary at the bottom of the list for that day
                    itemsCol.insertAdjacentHTML('beforeend', summaryHtml);

                    dayBlock.appendChild(dateCol);
                    dayBlock.appendChild(itemsCol);
                    container.appendChild(dayBlock);
                });
            }

            // Modal Logic
            function openDetailModal(item) {
                currentItem = item;
                const isReceita = item.tipo === 'receita';
                const val = parseFloat(item.valor);

                // Icon
                const iconSvg = isReceita
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />';

                document.getElementById('modalIcon').innerHTML = iconSvg;
                document.getElementById('modalIconBg').className = `h-20 w-20 rounded-full flex items-center justify-center mb-4 shadow-sm transition-colors duration-300 ${isReceita ? 'bg-green-500' : 'bg-red-500'}`;

                // Texts
                document.getElementById('modalDesc').textContent = item.descricao;
                const formattedVal = val.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                const valEl = document.getElementById('modalValue');
                valEl.textContent = (isReceita ? '+' : '-') + 'R$ ' + formattedVal;
                valEl.className = `text-3xl font-bold mb-8 ${isReceita ? 'text-green-500' : 'text-slate-800 dark:text-white'}`;

                // Details
                document.getElementById('modalCategory').textContent = item.categoria || '--';
                document.getElementById('modalAccount').textContent = item.conta || 'Principal';

                // Format Date DD/MM/YYYY
                const [y, m, d] = item.data.split('-');
                document.getElementById('modalDate').textContent = `${d}/${m}/${y}`;

                document.getElementById('modalTags').textContent = '--'; // Assuming tags are not in item object yet
                document.getElementById('modalAttachment').textContent = '--'; // Assuming attachment is not in item object yet
                document.getElementById('modalObs').textContent = item.observacao || '--'; // Assuming observacao is in item object

                // Show Modal
                const modal = document.getElementById('detailModal');
                modal.classList.remove('hidden');
                // small timeout for transition
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    document.getElementById('detailModalContent').classList.remove('scale-95');
                    document.getElementById('detailModalContent').classList.add('scale-100');
                }, 10);
            }

            function closeDetailModal() {
                const modal = document.getElementById('detailModal');
                modal.classList.add('opacity-0');
                document.getElementById('detailModalContent').classList.remove('scale-100');
                document.getElementById('detailModalContent').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // --- Modal Functions (ADD / EDIT) ---
            // --- Modal Functions (ADD / EDIT) ---

            // Global helper to load categories
            async function loadCats(type) {
                const catSelect = document.querySelector('select[name="categoria"]');
                // Don't wipe if we are just switching types and maybe already loaded? 
                // Actually better to show loading state to be sure.
                catSelect.innerHTML = '<option>Carregando...</option>';
                
                try {
                    // Added credentials: include for Vercel
                    const res = await fetch('api/categories.php', { credentials: 'include' });
                    const data = await res.json();
                    const items = type === 'receita' ? data.receitas : data.despesas;

                    catSelect.innerHTML = '';
                    if (!items || items.length === 0) {
                        const opt = document.createElement('option');
                        opt.value = 'Outros';
                        opt.textContent = 'Geral';
                        catSelect.appendChild(opt);
                    } else {
                        items.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.nome;
                            opt.textContent = c.nome;
                            catSelect.appendChild(opt);
                        });
                    }
                } catch (e) {
                    console.error(e);
                    catSelect.innerHTML = '<option value="Geral">Geral (Erro)</option>';
                }
            }

            async function openAddModal() {
                // Reset mode to ADD
                document.getElementById('modalTitle').textContent = 'Novo Lançamento';
                document.getElementById('addForm').reset();
                document.getElementById('editId').value = '';

                // Reset visual state
                document.getElementById('obsField').classList.add('hidden');
                document.getElementById('btnObs').querySelector('div').classList.remove('border-blue-500', 'text-blue-500');

                // Set default date to today
                const today = new Date().toISOString().split('T')[0];
                document.querySelector('input[name="data"]').value = today;

                // Categories Fetch
                const typeInputs = document.querySelectorAll('input[name="tipo"]');
                let currentType = document.querySelector('input[name="tipo"]:checked') ? document.querySelector('input[name="tipo"]:checked').value : 'despesa';

                // Context-aware default: If filtered by type, default to that type
                if (filterState.tipo === 'receita' || filterState.tipo === 'despesa') {
                    currentType = filterState.tipo;
                    const radio = document.querySelector(`input[name="tipo"][value="${currentType}"]`);
                    if (radio) radio.checked = true;
                }

                // Ensure one is checked if not
                if (!document.querySelector('input[name="tipo"]:checked')) {
                    document.querySelector('input[name="tipo"][value="despesa"]').checked = true;
                    currentType = 'despesa';
                }

                // Listen for type change to reload categories (Global listener, careful not to duplicate)
                // Better to assign onclick in HTML or verify if already assigned.
                typeInputs.forEach(input => {
                    input.onclick = () => loadCats(input.value);
                });

                // Load initially
                await loadCats(currentType);

                const modal = document.getElementById('addModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    document.getElementById('addModalContent').classList.remove('scale-95');
                    document.getElementById('addModalContent').classList.add('scale-100');
                }, 10);
            }

            function closeAddModal() {
                const modal = document.getElementById('addModal');
                modal.classList.add('opacity-0');
                document.getElementById('addModalContent').classList.remove('scale-100');
                document.getElementById('addModalContent').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            async function saveTransaction(e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                // Determine Mode (Create vs Update)
                const isUpdate = !!data.id;

                // Determine Endpoint
                let endpoint = '';
                if (isUpdate) {
                    endpoint = data.tipo === 'receita' ? 'api/update_receita.php' : 'api/update_despesa.php';
                } else {
                    endpoint = data.tipo === 'receita' ? 'api/create_receita.php' : 'api/create_despesa.php';
                }

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    const result = await response.json();

                    if (result.success) {
                        addNotification('Sucesso', 'Lançamento salvo com sucesso!', 'success');
                        closeAddModal();
                        fetchLancamentos(); // Refresh list
                    } else {
                        addNotification('Erro', result.error || 'Erro desconhecido', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    addNotification('Erro', 'Erro de conexão.', 'error');
                }
            }

            // --- Modal Functions (FILTER) ---
            function openFilterModal() {
                const modal = document.getElementById('filterModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    document.getElementById('filterModalContent').classList.remove('scale-95');
                    document.getElementById('filterModalContent').classList.add('scale-100');
                }, 10);
            }

            function closeFilterModal() {
                const modal = document.getElementById('filterModal');
                modal.classList.add('opacity-0');
                document.getElementById('filterModalContent').classList.remove('scale-100');
                document.getElementById('filterModalContent').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            async function deleteCurrentItem() {
                if (!currentItem) return;
                if (!confirm('Deseja realmente excluir este lançamento?')) return;

                // --- Optimistic UI Start ---
                // 1. Guardar estado atual para rollback se der erro
                const originalList = JSON.parse(JSON.stringify(allLancamentos));
                const itemToDelete = currentItem;

                // 2. Fechar modal imediatamente
                closeDetailModal();

                // 3. Remover localmente
                const index = allLancamentos.findIndex(i => i.id === currentItem.id && i.tipo === currentItem.tipo);
                if (index > -1) {
                    allLancamentos.splice(index, 1);

                    // 4. Recalcular saldos acumulados (do inicio ao fim para garantir consistência)
                    // Precisamos do saldo inicial. Podemos deduzir do primeiro item original.
                    let currentBalance = 0;

                    if (originalList.length > 0) {
                        // Recupera o saldo inicial antes do primeiro lançamento do mês
                        const firstItem = originalList[0];
                        const firstVal = parseFloat(firstItem.valor);
                        // Se era receita, somou. Se despesa, subtraiu.
                        // Saldo Inicial = Acumulado - (Impacto do Item)
                        const impact = firstItem.tipo === 'receita' ? firstVal : -firstVal;
                        currentBalance = parseFloat(firstItem.saldo_acumulado) - impact;
                    }

                    // Agora re-calcula tudo com a lista atualizada
                    allLancamentos.forEach(item => {
                        const val = parseFloat(item.valor);
                        if (item.tipo === 'receita') {
                            currentBalance += val;
                        } else {
                            currentBalance -= val;
                        }
                        item.saldo_acumulado = currentBalance;
                    });

                    // 5. Re-renderizar tela
                    renderLancamentos(allLancamentos);
                    applyFilters(); // Re-apply filters just in case
                }
                // --- Optimistic UI End ---

                // Determine endpoint based on type
                const endpoint = itemToDelete.tipo === 'receita' ? 'api/delete_receita.php' : 'api/delete_despesa.php';

                try {
                    // delete endpoints expect JSON
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: itemToDelete.id })
                    });
                    const result = await response.json();

                    if (result.success) {
                        // Sucesso silencioso, a UI já está certa.
                        // Opcional: Mostrar toast de sucesso
                        console.log('Item excluído com sucesso no servidor.');
                    } else {
                        // Erro no servidor: Rollback
                        alert('Erro ao excluir no servidor: ' + (result.error || 'Erro desconhecido'));
                        allLancamentos = originalList;
                        renderLancamentos(allLancamentos);
                        applyFilters(); // Re-apply filters just in case
                    }
                } catch (e) {
                    // Erro de rede: Rollback
                    alert('Erro de conexão ao excluir.');
                    allLancamentos = originalList;
                    renderLancamentos(allLancamentos);
                    applyFilters(); // Re-apply filters just in case
                }
            }

            async function editCurrentItem() {
                if (!currentItem) return;

                // 1. Close Detail Modal
                closeDetailModal();

                // 2. Populate Add/Edit Modal
                document.getElementById('modalTitle').textContent = 'Editar Lançamento';
                document.getElementById('editId').value = currentItem.id;

                // Set Type
                const radios = document.getElementsByName('tipo');
                for (let r of radios) {
                    if (r.value === currentItem.tipo) r.checked = true;
                    // Ensure click listener is attached
                    r.onclick = () => loadCats(r.value);
                }

                // Load categories and WAIT before setting value to ensure option exists
                // We use currentItem.tipo to load separate tables
                await loadCats(currentItem.tipo);

                // Set Fields
                document.querySelector('input[name="descricao"]').value = currentItem.descricao;
                document.querySelector('input[name="valor"]').value = currentItem.valor;
                document.querySelector('input[name="data"]').value = currentItem.data;
                document.querySelector('select[name="categoria"]').value = currentItem.categoria;
                document.querySelector('select[name="conta"]').value = currentItem.conta || 'Banco'; // placeholder logic

                // Handle Observacao
                const obsEl = document.querySelector('textarea[name="observacao"]');
                obsEl.value = currentItem.observacao || '';

                if (currentItem.observacao) {
                    document.getElementById('obsField').classList.remove('hidden');
                    document.getElementById('btnObs').querySelector('div').classList.add('border-blue-500', 'text-blue-500');
                } else {
                    document.getElementById('obsField').classList.add('hidden');
                    document.getElementById('btnObs').querySelector('div').classList.remove('border-blue-500', 'text-blue-500');
                }

                // 3. Open Modal
                const modal = document.getElementById('addModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    document.getElementById('addModalContent').classList.remove('scale-95');
                    document.getElementById('addModalContent').classList.add('scale-100');
                }, 10);
            }

            async function copyCurrentItem() {
                if (!currentItem) return;

                // 1. Close Detail Modal
                closeDetailModal();

                // 2. Populate Modal for "Copy"
                // Change title to "Duplicando [tipo]" to match image style slightly better or keep "Novo Lançamento (Cópia)"
                // Image said "Duplicando despesa".
                const typeLabel = currentItem.tipo === 'receita' ? 'Receita' : 'Despesa';
                document.getElementById('modalTitle').textContent = `Duplicando ${typeLabel}`;

                document.getElementById('editId').value = ''; // Empty ID = Create New

                // Set Type (force same type as original)
                const radios = document.getElementsByName('tipo');
                for (let r of radios) {
                    if (r.value === currentItem.tipo) r.checked = true;
                     r.onclick = () => loadCats(r.value);
                }

                // Load categories and WAIT
                await loadCats(currentItem.tipo);

                // Set Fields
                document.querySelector('input[name="descricao"]').value = currentItem.descricao;
                document.querySelector('input[name="descricao"]').value = currentItem.descricao; // Ensure it sticks

                document.querySelector('input[name="valor"]').value = currentItem.valor;
                document.querySelector('input[name="data"]').value = currentItem.data;
                document.querySelector('select[name="categoria"]').value = currentItem.categoria;
                document.querySelector('select[name="conta"]').value = currentItem.conta || 'Banco';

                const obsEl = document.querySelector('textarea[name="observacao"]');
                obsEl.value = currentItem.observacao || '';

                if (currentItem.observacao) {
                    document.getElementById('obsField').classList.remove('hidden');
                    document.getElementById('btnObs').querySelector('div').classList.add('border-blue-500', 'text-blue-500');
                } else {
                    document.getElementById('obsField').classList.add('hidden');
                    document.getElementById('btnObs').querySelector('div').classList.remove('border-blue-500', 'text-blue-500');
                }

                // 3. Open Modal
                const modal = document.getElementById('addModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    document.getElementById('addModalContent').classList.remove('scale-95');
                    document.getElementById('addModalContent').classList.add('scale-100');
                }, 10);
            }

            async function toggleStatus(id, tipo, event) {
                event.stopPropagation(); // Prevent opening modal

                try {
                    const response = await fetch('api/toggle_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id, tipo })
                    });
                    const result = await response.json();

                    if (result.success) {
                        // Update UI locally or reload
                        // Simplest: Reload list to reflect status
                        // fetchLancamentos(); 
                        // Better: Optimistic UI already handled logic if we wanted.

                        // Just notify and refresh
                        addNotification('Atualizado', 'Status alterado com sucesso!', 'success');
                        fetchLancamentos();
                    } else {
                        addNotification('Erro', 'Erro ao alterar status: ' + result.error, 'error');
                    }
                } catch (e) {
                    console.error(e);
                    addNotification('Erro', 'Erro ao alterar status', 'error');
                }
            }

            // Close on background click
            document.getElementById('detailModal').addEventListener('click', (e) => {
                if (e.target === document.getElementById('detailModal')) closeDetailModal();
            });
            document.getElementById('addModal').addEventListener('click', (e) => {
                if (e.target === document.getElementById('addModal')) closeAddModal();
            });
            document.getElementById('filterModal').addEventListener('click', (e) => {
                if (e.target === document.getElementById('filterModal')) closeFilterModal();
            });

            function calculateFooterBalance(lancamentos) {
                let balance = 0;
                lancamentos.forEach(item => {
                    const val = parseFloat(item.valor);
                    balance += (item.tipo === 'receita' ? val : -val);
                });

                // Note: This is "Saldo Previsto" based on the filtered month view, 
                // ideally it would be global balance, but for this UI context it often means "Balance of visible items" or "Running balance".
                // Let's use the API calculated balance logic if we want total, but for now sum of list is fine or we can fetch true balance from dashboard API.
                // For simplicity, let's display sum of visible items as "Saldo do Mês" or similar.
                document.getElementById('footerSaldo').textContent = 'R$ ' + balance.toLocaleString('pt-BR', { minimumFractionDigits: 2 });

                // Cor
                const el = document.getElementById('footerSaldo');
                if (balance >= 0) {
                    el.className = "text-xl font-bold text-blue-600 dark:text-blue-400";
                } else {
                    el.className = "text-xl font-bold text-red-600 dark:text-red-400";
                }
            }

            // Navigation Events
            document.getElementById('prevMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateMonthLabel();
                fetchLancamentos();
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                updateMonthLabel();
                fetchLancamentos();
            });

            // Init
            window.refreshData = fetchLancamentos;
            updateMonthLabel();
            fetchLancamentos();

        </script>

        <?php renderFooter(); ?>