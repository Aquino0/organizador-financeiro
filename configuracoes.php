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
                <input type="text" id="newReceita" placeholder="Nova Receita..."
                    class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                <button onclick="addCategory('receita')"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors font-bold">+</button>
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
                <input type="text" id="newDespesa" placeholder="Nova Despesa..."
                    class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                <button onclick="addCategory('despesa')"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors font-bold">+</button>
            </div>

            <ul id="listDespesas" class="space-y-2 max-h-96 overflow-y-auto pr-1">
                <!-- Items -->
            </ul>
        </div>

    </div>
</div>

<script>
    async function loadCategories() {
        try {
            const res = await fetch('api/categories.php');
            const data = await res.json();

            renderList('listReceitas', data.receitas, 'receita');
            renderList('listDespesas', data.despesas, 'despesa');
        } catch (e) {
            console.error(e);
            alert('Erro ao carregar categorias');
        }
    }

    function renderList(elementId, items, type) {
        const list = document.getElementById(elementId);
        list.innerHTML = '';

        items.forEach(cat => {
            const li = document.createElement('li');
            li.className = 'flex justify-between items-center bg-slate-50 dark:bg-slate-700/50 px-3 py-2 rounded-lg group';
            li.innerHTML = `
                <span class="text-slate-700 dark:text-slate-200 font-medium">${cat.nome}</span>
                <button onclick="deleteCategory(${cat.id})" class="text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all p-1" title="Excluir">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            list.appendChild(li);
        });
    }

    async function addCategory(type) {
        const input = document.getElementById(type === 'receita' ? 'newReceita' : 'newDespesa');
        const nome = input.value.trim();

        if (!nome) return;

        try {
            const res = await fetch('api/categories.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nome, tipo: type })
            });
            const result = await res.json();

            if (result.success) {
                input.value = '';
                loadCategories(); // Reload
            } else {
                alert('Erro: ' + (result.error || 'Falha ao criar'));
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function deleteCategory(id) {
        if (!confirm('Excluir categoria?')) return;

        try {
            const res = await fetch(`api/categories.php?id=${id}`, { method: 'DELETE' });
            const result = await res.json();

            if (result.success) {
                loadCategories();
            } else {
                alert('Erro ao excluir');
            }
        } catch (e) {
            console.error(e);
        }
    }

    document.addEventListener('DOMContentLoaded', loadCategories);
</script>

<?php renderFooter(); ?>