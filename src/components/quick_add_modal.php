<!-- Quick Add Modal Component -->
<!-- Derived from lancamentos.php -->
<div id="quickAddModal"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 relative overflow-hidden"
        id="quickAddModalContent">

        <!-- Close Button -->
        <button onclick="closeQuickAddModal()"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 z-10 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-8 pt-10">
            <h2 id="quickModalTitle" class="text-xl font-medium text-slate-700 dark:text-slate-200 mb-6">Novo Lançamento
                Rápido</h2>

            <form id="quickAddForm" onsubmit="submitQuickAdd(event)">
                <input type="hidden" name="id" id="quickAddId">

                <!-- Type Selection -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="radio" name="tipo" value="despesa" class="peer sr-only" checked
                            onchange="loadQuickCats('despesa')">
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
                        <input type="radio" name="tipo" value="receita" class="peer sr-only"
                            onchange="loadQuickCats('receita')">
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
                            placeholder="Ex: Almoço">
                    </div>

                    <!-- Value -->
                    <div class="relative">
                        <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Valor
                            (R$)</label>
                        <input type="number" step="0.01" name="valor" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all font-bold text-lg"
                            placeholder="0,00">
                    </div>

                    <!-- Category -->
                    <div class="relative">
                        <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Categoria</label>
                        <select name="categoria" id="quickCategorySelect" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all appearance-none">
                            <option value="">Carregando...</option>
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500 top-6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="relative">
                        <label class="block text-xs text-slate-400 mb-1 ml-1 uppercase tracking-wider">Data</label>
                        <input type="date" name="data" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btnQuickSave"
                        class="w-full py-4 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold shadow-lg shadow-green-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Salvar Lançamento</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Quick Add Logic
    function openQuickAddModal() {
        const modal = document.getElementById('quickAddModal');
        const dateInput = document.querySelector('#quickAddForm input[name="data"]');

        // Default to today
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }

        // Load Categories
        const type = document.querySelector('#quickAddForm input[name="tipo"]:checked').value;
        loadQuickCats(type);

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            document.getElementById('quickAddModalContent').classList.remove('scale-95');
            document.getElementById('quickAddModalContent').classList.add('scale-100');
        }, 10);
    }

    function closeQuickAddModal() {
        const modal = document.getElementById('quickAddModal');
        modal.classList.add('opacity-0');
        document.getElementById('quickAddModalContent').classList.remove('scale-100');
        document.getElementById('quickAddModalContent').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset form
            document.getElementById('quickAddForm').reset();
        }, 300);
    }

    async function loadQuickCats(type) {
        const select = document.getElementById('quickCategorySelect');
        select.innerHTML = '<option value="">Carregando...</option>';

        try {
            const response = await fetch(`api/categories.php?type=${type}`);
            const categories = await response.json();

            select.innerHTML = '';
            categories.forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.nome;
                opt.textContent = cat.nome;
                select.appendChild(opt);
            });
        } catch (e) {
            console.error('Erro ao carregar categorias', e);
            select.innerHTML = '<option value="Outros">Outros (Erro ao carregar)</option>';
        }
    }

    async function submitQuickAdd(e) {
        e.preventDefault();
        const btn = document.getElementById('btnQuickSave');
        const orgText = btn.innerHTML;
        btn.innerHTML = 'Salvando...';
        btn.disabled = true;

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        const endpoint = data.tipo === 'receita' ? 'api/create_receita.php' : 'api/create_despesa.php';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (result.success) {
                // Determine user-friendly feedback mechanism
                // If a "toast" function exists (from dashboard or other), use it.
                // Otherwise fallback to alert (temporarily until Toast task is done).

                // Check if we are on dashboard to refresh
                if (typeof loadDashboardData === 'function') {
                    loadDashboardData();
                }
                // Check if we are on lancamentos to refresh
                if (typeof window.refreshData === 'function') {
                    window.refreshData();
                }

                alert('Salvo com sucesso!');
                closeQuickAddModal();
            } else {
                alert('Erro: ' + (result.error || 'Erro desconhecido'));
            }
        } catch (err) {
            console.error(err);
            alert('Erro de conexão.');
        } finally {
            btn.innerHTML = orgText;
            btn.disabled = false;
        }
    }

    // Close on background click
    document.getElementById('quickAddModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('quickAddModal')) closeQuickAddModal();
    });
</script>