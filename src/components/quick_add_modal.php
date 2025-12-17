<!-- Quick Add Modal Component (High Fidelity Dark Theme) -->
<div id="quickAddModal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300"
    style="font-family: 'Inter', sans-serif;">

    <!-- Modal Card -->
    <div class="bg-[#1e293b] rounded-3xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 relative overflow-hidden border border-slate-700/50"
        id="quickAddModalContent">

        <!-- Close Button -->
        <button onclick="closeQuickAddModal()"
            class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-6 pt-8">
            <h2 id="quickModalTitle" class="text-xl font-bold text-white mb-6">Novo Lançamento</h2>

            <form id="quickAddForm" onsubmit="submitQuickAdd(event)">
                <input type="hidden" name="id" id="quickAddId">

                <!-- Type Selection (Radio) -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="group cursor-pointer flex items-center gap-2">
                        <input type="radio" name="tipo" value="despesa" class="peer sr-only" checked
                            onchange="handleTypeChange('despesa')">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-slate-400 peer-checked:border-[#ef4444] peer-checked:bg-[#ef4444] transition-all relative">
                        </div>
                        <span class="text-slate-300 font-medium peer-checked:text-white">Despesa</span>
                    </label>

                    <label class="group cursor-pointer flex items-center gap-2">
                        <input type="radio" name="tipo" value="receita" class="peer sr-only"
                            onchange="handleTypeChange('receita')">
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
                            <select name="categoria" id="quickCategorySelect" required
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
                        <span class="text-slate-300 text-sm font-medium">Status: <span id="statusLabel"
                                class="text-white font-bold">A Pagar</span></span>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="pago" class="sr-only peer" id="statusCheckbox"
                                onchange="toggleStatusLabel()">
                            <div
                                class="w-11 h-6 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Extra Options (Circular Buttons) -->
                <div class="flex justify-between px-4 mb-8">
                    <button type="button"
                        class="flex flex-col items-center gap-2 group opacity-50 hover:opacity-100 transition-opacity">
                        <div
                            class="w-12 h-12 rounded-full border border-slate-600 flex items-center justify-center text-slate-400 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">Repetir</span>
                    </button>

                    <button type="button" onclick="toggleObs()" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 rounded-full border border-slate-600 flex items-center justify-center text-slate-400 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors"
                            id="btnObsCircle">
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

                <!-- Hidden Obs Field -->
                <div id="quickObsField" class="hidden mb-6">
                    <textarea name="observacao" rows="2"
                        class="w-full bg-[#334155] text-white border border-slate-600 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 text-sm"
                        placeholder="Observações adicionais..."></textarea>
                </div>

                <!-- Done Button (Floating Check) -->
                <div class="flex justify-center -mb-16 relative z-20">
                    <button type="button" onclick="document.getElementById('quickAddForm').requestSubmit()"
                        id="btnQuickSave"
                        class="w-16 h-16 rounded-full bg-[#22c55e] hover:bg-green-600 text-white shadow-lg shadow-green-500/50 flex items-center justify-center transition-transform hover:scale-110 active:scale-95 disabled:opacity-75 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
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
    // Quick Add Logic
    function openQuickAddModal() {
        const modal = document.getElementById('quickAddModal');
        const dateInput = document.querySelector('#quickAddForm input[name="data"]');

        // Default to today if empty
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }

        // Load Categories based on initial/current type
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
            // Reset form partly? User might want to keep date. Reset description/value.
            document.querySelector('#quickAddForm input[name="descricao"]').value = '';
            document.querySelector('#quickAddForm input[name="valor"]').value = '';
        }, 300);
    }

    function handleTypeChange(type) {
        loadQuickCats(type);
        toggleStatusLabel(); // Type affects label (Recebido vs Pago)
    }

    function toggleStatusLabel() {
        const type = document.querySelector('#quickAddForm input[name="tipo"]:checked').value;
        const paid = document.getElementById('statusCheckbox').checked;
        const label = document.getElementById('statusLabel');

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

    function toggleObs() {
        const field = document.getElementById('quickObsField');
        const btn = document.getElementById('btnObsCircle');

        field.classList.toggle('hidden');
        if (!field.classList.contains('hidden')) {
            btn.classList.add('border-blue-500', 'text-blue-500');
        } else {
            btn.classList.remove('border-blue-500', 'text-blue-500');
        }
    }

    async function loadQuickCats(type) {
        const select = document.getElementById('quickCategorySelect');
        // Do not verify if identical to optimize, always reload to be safe or use simple cache
        // For now, load.

        // Visual Loading State
        select.style.opacity = '0.5';

        try {
            // FIX: Using relative path 'api/categories.php' might fail if user is in deep url. Use absolute.
            const response = await fetch('/api/categories.php');
            const data = await response.json();

            // API returns { receitas: [], despesas: [] }
            const list = type === 'receita' ? data.receitas : data.despesas;

            select.innerHTML = '';

            if (list.length === 0) {
                const opt = document.createElement('option');
                opt.value = 'Outros';
                opt.textContent = 'Geral (Sem categorias)';
                select.appendChild(opt);
            } else {
                list.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.nome;
                    opt.textContent = cat.nome;
                    select.appendChild(opt);
                });
            }
        } catch (e) {
            console.error('Erro ao carregar categorias', e);
            select.innerHTML = '<option value="Outros">Outros</option>';
        } finally {
            select.style.opacity = '1';
        }
    }

    async function submitQuickAdd(e) {
        e.preventDefault();
        const btn = document.getElementById('btnQuickSave');
        // Visual Disable
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        const formData = new FormData(e.target);

        // Manual conversion for checkbox (pago)
        const isPaid = document.getElementById('statusCheckbox').checked;
        formData.set('pago', isPaid ? '1' : '0');
        // Note: API expects 'pago' as boolean or 1/0? db.php uses param binding. 
        // create_despesa.php checks: $pago = isset($data['pago']) ? (bool)$data['pago'] : false;

        const data = Object.fromEntries(formData.entries());
        // Fix Pago in Object
        data.pago = isPaid;

        const endpoint = data.tipo === 'receita' ? '/api/create_receita.php' : '/api/create_despesa.php';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (result.success) {
                // Determine user-friendly feedback mechanism

                // Refresh logic
                if (typeof loadDashboardData === 'function') {
                    loadDashboardData();
                }
                if (typeof window.refreshData === 'function') {
                    window.refreshData();
                }

                alert('Salvo com sucesso! ✅');
                closeQuickAddModal();
            } else {
                alert('Erro: ' + (result.error || 'Erro desconhecido'));
            }
        } catch (err) {
            console.error(err);
            alert('Erro de conexão. Verifique sua internet.');
        } finally {
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Close on background click
    document.getElementById('quickAddModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('quickAddModal')) closeQuickAddModal();
    });
</script>