<div id="globalAddModal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300"
    style="font-family: 'Inter', sans-serif;">

    <!-- Modal Card -->
    <!-- Removed overflow-hidden from here to allow button to hang out -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 relative border border-slate-200 dark:border-slate-700/50"
        id="globalAddModalContent">

        <!-- Close Button -->
        <button onclick="closeGlobalAddModal()"
            class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Added pb-12 to give space for the button visually if needed, but the button uses negative margin -->
        <div class="p-6 pt-8 pb-10">
            <h2 id="globalModalTitle" class="text-xl font-bold text-slate-800 dark:text-white mb-6">Novo Lançamento</h2>

            <form id="globalAddForm" onsubmit="submitGlobalAdd(event)">
                <input type="hidden" name="id" id="globalAddId">

                <!-- Type Selection (Radio) -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="group cursor-pointer flex items-center gap-2">
                        <input type="radio" name="tipo" value="despesa" class="peer sr-only" checked
                            onchange="handleGlobalTypeChange('despesa')">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-slate-400 peer-checked:border-[#ef4444] peer-checked:bg-[#ef4444] transition-all relative">
                        </div>
                        <span class="text-slate-600 dark:text-slate-300 font-medium peer-checked:text-slate-800 dark:peer-checked:text-white">Despesa</span>
                    </label>

                    <label class="group cursor-pointer flex items-center gap-2">
                        <input type="radio" name="tipo" value="receita" class="peer sr-only"
                            onchange="handleGlobalTypeChange('receita')">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-slate-400 peer-checked:border-[#22c55e] peer-checked:bg-[#22c55e] transition-all relative">
                        </div>
                        <span class="text-slate-600 dark:text-slate-300 font-medium peer-checked:text-slate-800 dark:peer-checked:text-white">Receita</span>
                    </label>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label
                        class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Descrição</label>
                    <input type="text" name="descricao" required
                        class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-slate-400 font-medium transition-all"
                        placeholder="Ex: Mercado">
                </div>

                <!-- Row: Value & Date -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Valor</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-slate-400 font-bold">R$</span>
                            <input type="number" step="0.01" name="valor" required
                                class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-lg placeholder-slate-400"
                                placeholder="0,00">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Data</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="data" required
                                class="flex-1 bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium text-center">
                            
                            <!-- Status Toggle Icon -->
                            <label class="cursor-pointer select-none">
                                <input type="checkbox" name="pago" value="1" class="sr-only peer" id="globalStatusCheckbox" checked>
                                <!-- Thumbs UP (Pago) -->
                                <div class="hidden peer-checked:flex w-10 h-10 items-center justify-center text-green-600 dark:text-green-500 hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                                    </svg>
                                </div>
                                <!-- Thumbs DOWN (Pendente) -->
                                <div class="flex peer-checked:hidden w-10 h-10 items-center justify-center text-slate-400 dark:text-slate-500 hover:text-red-500 transition-all hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 3h-4v12h4V3zm-22 11c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.85.58-1.41V5c0-1.1-.9-2-2-2H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2z"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Row: Category & Account -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Categoria</label>
                        <div class="relative">
                            <select name="categoria" id="globalCategorySelect" required
                                class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-1 focus:ring-blue-500 appearance-none font-medium text-sm truncate">
                                <option value="">Carregando...</option>
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Conta</label>
                        <div class="relative">
                            <select name="conta" id="globalContaSelect"
                                class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-1 focus:ring-blue-500 appearance-none font-medium text-sm truncate">
                                <option value="">Carteira / Débito</option>
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Extra Options (Circular Buttons) -->
                <div class="flex justify-center gap-12 px-4 mb-8">

                    <!-- Obs Button -->
                    <button type="button" onclick="toggleGlobalObs()" class="flex flex-col items-center gap-2 group">
                        <div
                            class="w-12 h-12 rounded-full border border-slate-600 flex items-center justify-center text-slate-400 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors"
                            id="btnGlobalObsCircle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">Obs</span>
                    </button>
                </div>

                <!-- Hidden Fields Container -->
                <div id="hiddenFields" class="space-y-4 mb-6">
                    <!-- Obs Field -->
                    <div id="globalObsField" class="hidden">
                        <textarea name="observacao" rows="2"
                            class="w-full bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 text-sm"
                            placeholder="Observações adicionais..."></textarea>
                    </div>


                </div>

                <!-- Done Button (Floating Check) -->
                <div class="flex justify-center -mb-24 relative z-20">
                    <button type="button" onclick="document.getElementById('globalAddForm').requestSubmit()"
                        id="btnGlobalSave"
                        class="w-20 h-20 rounded-full bg-[#22c55e] hover:bg-green-600 text-white shadow-lg shadow-green-500/50 flex items-center justify-center transition-transform hover:scale-110 active:scale-95 disabled:opacity-75 disabled:cursor-not-allowed border-8 border-white dark:border-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Quick Add Logic (Global)
    function openGlobalAddModal() {
        try {
            console.log('Opening Global Add Modal...');
            // Force reset any stuck state
            const modal = document.getElementById('globalAddModal');
            if (!modal) {
                alert('Erro Fatal: Modal global não encontrado.');
                return;
            }

            const dateInput = document.querySelector('#globalAddForm input[name="data"]');

            // Default to today
            if (dateInput && !dateInput.value) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }

            // Load Categories & Contas
            const typeInput = document.querySelector('#globalAddForm input[name="tipo"]:checked');
            if (typeInput) {
                const type = typeInput.value;
                loadGlobalCats(type);
                loadGlobalContas(type);
            } else {
                console.warn('Nenhum tipo selecionado');
                loadGlobalCats('despesa'); // Default
                loadGlobalContas('despesa'); // Default
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                document.getElementById('globalAddModalContent').classList.remove('scale-95');
                document.getElementById('globalAddModalContent').classList.add('scale-100');
            }, 10);
        } catch (e) {
            console.error(e);
            alert('Erro ao abrir modal global: ' + e.message);
        }
    }

    function closeGlobalAddModal() {
        const modal = document.getElementById('globalAddModal');
        modal.classList.add('opacity-0');
        document.getElementById('globalAddModalContent').classList.remove('scale-100');
        document.getElementById('globalAddModalContent').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset form partly
            document.querySelector('#globalAddForm input[name="descricao"]').value = '';
            document.querySelector('#globalAddForm input[name="valor"]').value = '';

            // Reset toggles
            document.getElementById('globalObsField').classList.add('hidden');
            document.getElementById('btnGlobalObsCircle').classList.remove('border-blue-500', 'text-blue-500');
        }, 300);
    }

    function toggleGlobalObs() {
        const field = document.getElementById('globalObsField');
        const btn = document.getElementById('btnGlobalObsCircle');

        field.classList.toggle('hidden');
        if (!field.classList.contains('hidden')) {
            btn.classList.add('border-blue-500', 'text-blue-500');
        } else {
            btn.classList.remove('border-blue-500', 'text-blue-500');
        }
    }

    async function loadGlobalCats(type) {
        const select = document.getElementById('globalCategorySelect');
        select.innerHTML = '<option value="">Carregando...</option>';

        // Hardcoded Fallback (in case API fails consistently)
        const defaults = ['Alimentação', 'Transporte', 'Lazer', 'Saúde', 'Moradia', 'Educação', 'Outros'];

        try {
            console.log('Fetching categories for (Global):', type);
            // Added credentials: include to ensure session cookies are passed
            const response = await fetch('api/categories.php', { credentials: 'include' });

            // Debug: Check text before JSON if needed
            if (!response.ok) throw new Error(`HTTP Error ${response.status}`);

            const text = await response.text();
            try {
                var data = JSON.parse(text);
            } catch (e) {
                console.error('JSON Parse Error:', text);
                throw new Error('Erro JSON: ' + text.substring(0, 50));
            }
            console.log('Categories Loaded (Global):', data);

            // API returns { receitas: [], despesas: [] }
            let categories = [];
            if (data && type === 'receita') {
                categories = data.receitas || [];
            } else if (data) {
                categories = data.despesas || [];
            }

            select.innerHTML = '';

            if (!categories || categories.length === 0) {
                // Use Fallback if empty
                if (type === 'despesa') {
                    defaults.forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat;
                        opt.textContent = cat;
                        select.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = 'Outros';
                    opt.textContent = 'Geral (Sem categorias)';
                    select.appendChild(opt);
                }
            } else {
                categories.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.nome;
                    opt.textContent = cat.nome;
                    select.appendChild(opt);
                });
            }
        } catch (e) {
            console.error('Erro ao carregar categorias (Global):', e);
            select.innerHTML = '<option value="Outros">Erro (Usando Geral)</option>';
        }
    }

    function toggleGlobalStatusLabel() {
        const type = document.querySelector('#globalAddForm input[name="tipo"]:checked').value;
        const paid = document.getElementById('globalStatusCheckbox').checked;
        const label = document.getElementById('globalStatusLabel');

        if (paid) {
            label.textContent = type === 'receita' ? 'Recebido' : 'Pago';
            label.classList.add('text-green-400');
            label.classList.remove('text-slate-800', 'text-white');
        } else {
            label.textContent = type === 'receita' ? 'A Receber' : 'A Pagar';
            label.classList.remove('text-green-400');
            if (!document.documentElement.classList.contains('dark')) {
                label.classList.add('text-slate-800');
            } else {
                label.classList.add('text-white');
            }
        }
    }

    async function loadGlobalContas(type) {
        const select = document.getElementById('globalContaSelect');
        select.innerHTML = '<option value="">Carteira / Dinheiro</option><option value="Conta Corrente">Conta Corrente</option>';
        
        if (type === 'despesa') {
            try {
                const res = await fetch('api/list_cartoes.php', { credentials: 'include' });
                if (res.ok) {
                    const cards = await res.json();
                    if (cards.length > 0) {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = "💳 Cartões de Crédito";
                        cards.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = "cartao_" + c.id;
                            opt.textContent = c.nome;
                            optgroup.appendChild(opt);
                        });
                        select.appendChild(optgroup);
                    }
                }
            } catch (e) {
                console.error("Erro ao carregar cartões:", e);
            }
        }
    }

    function handleGlobalTypeChange(type) {
        loadGlobalCats(type);
        loadGlobalContas(type);
        toggleGlobalStatusLabel();
    }

    async function submitGlobalAdd(e) {
        e.preventDefault();
        const btn = document.getElementById('btnGlobalSave');
        const orgText = btn.innerHTML;
        // btn.innerHTML = '...'; // Icon is complex, just disable opacity
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.disabled = true;

        const formData = new FormData(e.target);

        const isPaid = document.getElementById('globalStatusCheckbox').checked;
        formData.set('pago', isPaid ? '1' : '0');

        const data = Object.fromEntries(formData.entries());
        data.pago = isPaid; // Update object too

        const endpoint = data.tipo === 'receita' ? 'api/create_receita.php' : 'api/create_despesa.php';

        try {
            // Handle repetition if 'repeat_type' is set and visible not implemented in backend yet.
            // For now, let's just send single request. The user asked for the button to work (toggle).
            // Actual recurrence logic would require backend update.

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
                credentials: 'include'
            });
            const text = await response.text();
            try {
                var result = JSON.parse(text);
            } catch (jsonErr) {
                console.error('JSON Error:', text);
                throw new Error('Resposta inválida do servidor: ' + text.substring(0, 100)); // Show preview
            }

            if (result.success) {
                if (typeof loadDashboardData === 'function') loadDashboardData();
                if (typeof window.refreshData === 'function') window.refreshData();

                if (typeof showToast === 'function') {
                    showToast('Lançamento salvo com sucesso! 🚀', 'success');
                } else {
                    alert('Salvo com sucesso!');
                }

                closeGlobalAddModal();
            } else {
                if (typeof showToast === 'function') {
                    showToast('Erro: ' + (result.error || 'Erro desconhecido'), 'error');
                } else {
                    alert('Erro: ' + (result.error || 'Erro desconhecido'));
                }
            }
        } catch (err) {
            console.error(err);
             if (typeof showToast === 'function') {
                showToast('Erro: ' + err.message, 'error');
            } else {
                alert('Erro: ' + err.message);
            }
        } finally {
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            btn.disabled = false;
        }
    }

    // Close on background click
    document.getElementById('globalAddModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('globalAddModal')) closeGlobalAddModal();
    });
</script>