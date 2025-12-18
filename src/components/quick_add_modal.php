<div id="globalAddModal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300"
    style="font-family: 'Inter', sans-serif;">

    <!-- Modal Card -->
    <!-- Removed overflow-hidden from here to allow button to hang out -->
    <div class="bg-[#1e293b] rounded-3xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 relative border border-slate-700/50"
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
            <h2 id="globalModalTitle" class="text-xl font-bold text-white mb-6">Novo Lançamento</h2>

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
                        <span class="text-slate-300 font-medium peer-checked:text-white">Despesa</span>
                    </label>

                    <label class="group cursor-pointer flex items-center gap-2">
                        <input type="radio" name="tipo" value="receita" class="peer sr-only"
                            onchange="handleGlobalTypeChange('receita')">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-slate-400 peer-checked:border-[#22c55e] peer-checked:bg-[#22c55e] transition-all relative">
                        </div>
                        <span class="text-slate-300 font-medium peer-checked:text-white">Receita</span>
                    </label>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label
                        class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-wide">Descrição</label>
                    <input type="text" name="descricao" required
                        class="w-full bg-[#334155] text-white border border-slate-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-slate-400 font-medium transition-all"
                        placeholder="Ex: Mercado">
                </div>

                <!-- Row: Value & Date -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-wide">Valor</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-slate-400 font-bold">R$</span>
                            <input type="number" step="0.01" name="valor" required
                                class="w-full bg-[#334155] text-white border border-slate-600 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-lg placeholder-slate-500"
                                placeholder="0,00">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-wide">Data</label>
                        <input type="date" name="data" required
                            class="w-full bg-[#334155] text-white border border-slate-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                    </div>
                </div>

                <!-- Row: Account & Category -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-wide">Conta/Cartão</label>
                        <select name="conta"
                            class="w-full bg-[#334155] text-white border border-slate-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none font-medium">
                            <option value="Carteira">Carteira</option>
                            <option value="Banco">Banco</option>
                            <option value="Crédito">Cartão de Crédito</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-wide">Categoria</label>
                        <div class="relative">
                            <select name="categoria" id="globalCategorySelect" required
                                class="w-full bg-[#334155] text-white border border-green-500 rounded-lg px-4 py-3 focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none font-medium text-sm truncate">
                                <option value="">Carregando...</option>
                            </select>
                            <!-- Search Icon (Visual) -->
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

                <!-- Status Toggle (Paid) -->
                <div class="flex justify-end mb-8">
                    <div class="bg-[#334155] rounded-full px-4 py-2 flex items-center gap-3 border border-slate-600">
                        <span class="text-slate-300 text-sm font-medium">Status: <span id="globalStatusLabel"
                                class="text-white font-bold">A Pagar</span></span>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="pago" class="sr-only peer" id="globalStatusCheckbox"
                                onchange="toggleGlobalStatusLabel()">
                            <div
                                class="w-11 h-6 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Extra Options (Circular Buttons) -->
                <div class="flex justify-between px-4 mb-8">
                    <!-- Repeat Button -->
                    <button type="button" onclick="toggleGlobalRepeat()" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 rounded-full border border-slate-600 flex items-center justify-center text-slate-400 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors"
                            id="btnGlobalRepeatCircle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">Repetir</span>
                    </button>

                    <!-- Obs Button -->
                    <button type="button" onclick="toggleGlobalObs()" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 rounded-full border border-slate-600 flex items-center justify-center text-slate-400 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors"
                            id="btnGlobalObsCircle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">Obs</span>
                    </button>

                    <button type="button"
                        class="flex flex-col items-center gap-2 group opacity-50 hover:opacity-100 transition-opacity">
                        <div
                            class="w-12 h-12 rounded-full border border-slate-600 flex items-center justify-center text-slate-400 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">Anexo</span>
                    </button>

                    <button type="button"
                        class="flex flex-col items-center gap-2 group opacity-50 hover:opacity-100 transition-opacity">
                        <div
                            class="w-12 h-12 rounded-full border border-slate-600 flex items-center justify-center text-slate-400 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">Tags</span>
                    </button>
                </div>

                <!-- Hidden Fields Container -->
                <div id="hiddenFields" class="space-y-4 mb-6">
                    <!-- Obs Field -->
                    <div id="globalObsField" class="hidden">
                        <textarea name="observacao" rows="2"
                            class="w-full bg-[#334155] text-white border border-slate-600 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 text-sm"
                            placeholder="Observações adicionais..."></textarea>
                    </div>

                    <!-- Repeat Field -->
                    <div id="globalRepeatField" class="hidden">
                        <div class="bg-[#334155] p-3 rounded-lg border border-slate-600">
                            <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Repetir
                                Lançamento</label>
                            <div class="flex items-center gap-3">
                                <select name="repeat_type"
                                    class="bg-slate-700 text-white rounded px-3 py-2 text-sm border border-slate-600 focus:outline-none">
                                    <option value="fixed">Parcelado (x Vezes)</option>
                                    <option value="monthly">Fixo (Mensal)</option>
                                </select>
                                <input type="number" name="repeat_times" min="2" max="60" value="2"
                                    class="w-20 bg-slate-700 text-white rounded px-3 py-2 text-sm border border-slate-600 focus:outline-none"
                                    placeholder="2x">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Done Button (Floating Check) -->
                <div class="flex justify-center -mb-24 relative z-20">
                    <button type="button" onclick="document.getElementById('globalAddForm').requestSubmit()"
                        id="btnGlobalSave"
                        class="w-20 h-20 rounded-full bg-[#22c55e] hover:bg-green-600 text-white shadow-lg shadow-green-500/50 flex items-center justify-center transition-transform hover:scale-110 active:scale-95 disabled:opacity-75 disabled:cursor-not-allowed border-8 border-[#1e293b]">
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

            // Load Categories
            const typeInput = document.querySelector('#globalAddForm input[name="tipo"]:checked');
            if (typeInput) {
                const type = typeInput.value;
                loadGlobalCats(type);
            } else {
                console.warn('Nenhum tipo selecionado');
                loadGlobalCats('despesa'); // Default
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
            document.getElementById('globalRepeatField').classList.add('hidden');
            document.getElementById('btnGlobalObsCircle').classList.remove('border-blue-500', 'text-blue-500');
            document.getElementById('btnGlobalRepeatCircle').classList.remove('border-blue-500', 'text-blue-500');
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

    function toggleGlobalRepeat() {
        const field = document.getElementById('globalRepeatField');
        const btn = document.getElementById('btnGlobalRepeatCircle');

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
            label.classList.remove('text-white');
        } else {
            label.textContent = type === 'receita' ? 'A Receber' : 'A Pagar';
            label.classList.remove('text-green-400');
            label.classList.add('text-white');
        }
    }

    function handleGlobalTypeChange(type) {
        loadGlobalCats(type);
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
                body: JSON.stringify(data)
            });
            const result = await response.json();

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
                showToast('Erro de conexão. Verifique internet.', 'error');
            } else {
                alert('Erro de conexão.');
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