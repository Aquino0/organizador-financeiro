<?php
// configuracoes.php
require_once 'src/auth.php';
require_once 'src/layout.php';
requireAuth();

renderHeader('Configurações');
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Categorias</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Receitas -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-green-600 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                        clip-rule="evenodd" />
                </svg>
                Receitas
            </h2>

            <div class="flex gap-2 mb-4">
                <button onclick="openCatModal('receita')"
                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors font-bold flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Adicionar Categoria
                </button>
            </div>
 
            <ul id="listReceitas" class="space-y-2 max-h-96 overflow-y-auto pr-1">
                <!-- Items -->
            </ul>
        </div>
 
        <!-- Despesas -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-red-600 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                Despesas
            </h2>
 
            <div class="flex gap-2 mb-4">
                <button onclick="openCatModal('despesa')"
                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors font-bold flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Adicionar Categoria
                </button>
            </div>
 
            <ul id="listDespesas" class="space-y-2 max-h-96 overflow-y-auto pr-1">
                <!-- Items -->
            </ul>
        </div>
 
    </div>
</div>

<!-- Modal Criando Categoria -->
<div id="catModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl relative flex flex-col max-h-[90vh] overflow-hidden transform transition-all">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
            <h3 id="modalTitle" class="text-xl font-bold text-slate-800 dark:text-white">Criando categoria de despesa</h3>
            <button onclick="closeCatModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Body (Scrollable) -->
        <div class="p-6 overflow-y-auto flex-1 space-y-6">
            <input type="hidden" id="modalCatType" value="despesa">
            <input type="hidden" id="selectedIcon" value="folder">
            <input type="hidden" id="selectedColor" value="#ec4899"> <!-- Pink Default -->



            <!-- Pre-selection Avatar Display & Name Input -->
            <div class="flex items-center gap-4">
                <div id="previewAvatar" class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl shadow-inner transition-colors duration-300" style="background-color: #ec4899">
                    <i id="previewIcon" class="fa-solid fa-folder"></i>
                </div>
                <div class="flex-1">
                    <label class="block text-xs text-slate-400 mb-1 uppercase tracking-wider">Nome da categoria</label>
                    <input type="text" id="modalCatName" required
                        class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium"
                        placeholder="Ex: Alimentação">
                </div>
            </div>



            <!-- Collapsible: Choose Icon -->
            <div>
                <button type="button" onclick="toggleCollapse('iconCollapse')" class="w-full flex justify-between items-center py-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                    <span class="text-sm font-semibold uppercase tracking-wider">Escolha um ícone</span>
                    <svg id="iconCollapseArrow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div id="iconCollapse" class="mt-4 grid grid-cols-7 gap-3 max-h-40 overflow-y-auto pr-1">
                    <!-- Icons generated via JS -->
                </div>
            </div>

            <!-- Collapsible: Choose Color -->
            <div>
                <button type="button" onclick="toggleCollapse('colorCollapse')" class="w-full flex justify-between items-center py-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                    <span class="text-sm font-semibold uppercase tracking-wider">Escolha uma cor</span>
                    <svg id="colorCollapseArrow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div id="colorCollapse" class="mt-4 grid grid-cols-8 gap-3">
                    <!-- Colors generated via JS -->
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-100 dark:border-slate-700 flex justify-end">
            <button onclick="commitAddCategory()" id="btnCreateCategory" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg font-bold transition-all transform hover:scale-105 active:scale-95">
                Criar categoria
            </button>
        </div>

    </div>
</div>

<!-- FontAwesome link placeholder just in case -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    let ALL_CATEGORIES = [];

    async function loadCategories() {
        try {
            const res = await fetch('api/categories.php', { credentials: 'include' });
            if (!res.ok) throw new Error(`HTTP Error ${res.status}`);

            const text = await res.text();
            const data = JSON.parse(text);
            
            // Save to global for subcategories logic
            ALL_CATEGORIES = [
                ...(data.receitas || []),
                ...(data.despesas || [])
            ];

            // Render, passing separate lists but respecting SUB-Categories 
            renderList('listReceitas', data.receitas || [], 'receita');
            renderList('listDespesas', data.despesas || [], 'despesa');
        } catch (e) {
            console.error(e);
        }
    }

    function renderList(elementId, items, type) {
        const list = document.getElementById(elementId);
        list.innerHTML = '';

        if (!items || items.length === 0) {
            list.innerHTML = '<li class="text-slate-400 text-sm px-3">Nenhuma categoria encontrada.</li>';
            return;
        }

        items.forEach(cat => {
            const li = document.createElement('li');
            li.className = 'flex justify-between items-center bg-slate-50 dark:bg-slate-700/50 px-3 py-2 rounded-lg group';
            
            // Add padding for subcategories
            if (cat.parent_id) {
                li.className += ' ml-6 border-l-2 border-slate-300 dark:border-slate-500';
            }

            li.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs shadow-sm shadow-inner" style="background-color: ${cat.cor}">
                        <i class="fa-solid fa-${cat.icone || 'folder'}"></i>
                    </div>
                    <div>
                        <span class="text-slate-700 dark:text-slate-200 font-medium">${cat.nome}</span>
                        ${cat.parent_id ? `<span class="text-[10px] text-slate-400 block -mt-1">Subcategoria</span>` : ''}
                    </div>
                </div>
                <button onclick="deleteCategory(${cat.id})" class="text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all p-1" title="Excluir">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            list.appendChild(li);
        });
    }

    // --- Modal Controls ---
    function openCatModal(type) {
        document.getElementById('modalCatType').value = type;
        document.getElementById('modalTitle').textContent = type === 'receita' ? 'Criando categoria de receita' : 'Criando categoria de despesa';
        document.getElementById('catModal').classList.remove('hidden');
        
        document.getElementById('modalCatName').value = '';
        selectIcon('folder');
        selectColor('#ec4899'); // Default pink
    }

    function closeCatModal() {
        document.getElementById('catModal').classList.add('hidden');
    }

    function toggleCollapse(id) {
        document.getElementById(id).classList.toggle('hidden');
        document.getElementById(id + 'Arrow').classList.toggle('rotate-180');
    }



    // --- Grids Generation ---
    const AVAILABLE_ICONS = [
        'folder', 'bag-shopping', 'cart-shopping', 'utensils', 'wine-glass', 'shirt', 
        'graduation-cap', 'face-smile', 'plane', 'building', 'music', 'basketball', 
        'umbrella', 'book', 'briefcase', 'wallet', 'credit-card', 'bolt', 'chart-line', 
        'trophy', 'eye', 'heart', 'flag', 'gamepad', 'hand-holding-heart', 'users', 
        'dumbbell', 'plus', 'house', 'chart-bar', 'image', 'dollar-sign', 'lock', 
        'motorcycle', 'mug-hot', 'list-ul', 'ellipsis-h', 'palette', 'file-lines', 
        'user', 'paw', 'shield-halved', 'anchor', 'star', 'tag', 'truck', 'tree', 'gear'
    ];

    const AVAILABLE_COLORS = [
        '#ec4899', '#8b5cf6', '#4f46e5', '#3b82f6', '#3b82f6', '#be185d', '#ef4444', '#f87171', 
        '#22c55e', '#f97316', '#eab308', '#a855f7', '#06b6d4', '#475569', '#14b8a6', '#047857',
        '#10b981', '#1e293b', '#64748b', '#2dd4bf', '#059669', '#15803d', '#4ade80', '#e11d48'
    ];

    function generateGrids() {
        document.getElementById('iconCollapse').innerHTML = AVAILABLE_ICONS.map(icon => `
            <button type="button" onclick="selectIcon('${icon}')" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-200 transition-all transform hover:scale-110">
                <i class="fa-solid fa-${icon}"></i>
            </button>
        `).join('');

        document.getElementById('colorCollapse').innerHTML = AVAILABLE_COLORS.map(color => `
            <button type="button" onclick="selectColor('${color}')" class="w-8 h-8 rounded-full shadow-sm transform hover:scale-125 transition-transform" style="background-color: ${color}"></button>
        `).join('');
    }

    function selectIcon(icon) {
        document.getElementById('selectedIcon').value = icon;
        document.getElementById('previewIcon').className = `fa-solid fa-${icon}`;
    }

    function selectColor(color) {
        document.getElementById('selectedColor').value = color;
        document.getElementById('previewAvatar').style.backgroundColor = color;
    }

    // --- Create Category Logic ---
    async function commitAddCategory() {
        const type = document.getElementById('modalCatType').value;
        const nome = document.getElementById('modalCatName').value.trim();
        const icone = document.getElementById('selectedIcon').value;
        const cor = document.getElementById('selectedColor').value;
        const parent_id = null;

        if (!nome) {
            alert('Por favor, preencha o nome da categoria.');
            return;
        }

        try {
            const res = await fetch('api/categories.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nome, tipo: type, cor, icone, parent_id }),
                credentials: 'include'
            });
            const result = await res.json();

            if (result.success) {
                closeCatModal();
                loadCategories(); // Reload lists
            } else {
                alert('Erro: ' + (result.error || 'Falha ao criar'));
            }
        } catch (e) {
            console.error(e);
            alert('Erro de conexão ao adicionar.');
        }
    }

    async function deleteCategory(id) {
        if (!confirm('Excluir categoria? Isso também excluirá as subcategorias vinculadas.')) return;

        try {
            const res = await fetch(`api/categories.php?id=${id}`, {
                method: 'DELETE',
                credentials: 'include'
            });
            const result = await res.json();

            if (result.success) {
                loadCategories();
            } else {
                alert('Erro ao excluir: ' + (result.error || 'Desconhecido'));
            }
        } catch (e) {
            console.error(e);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadCategories();
        generateGrids(); 
    });
</script>

<?php renderFooter(); ?>