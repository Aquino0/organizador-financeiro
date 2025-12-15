<?php
require_once 'src/auth.php';
require_once 'src/layout.php';
requireAdmin();

renderHeader('Usuários');
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Gerenciar Usuários</h2>
        <button onclick="document.getElementById('modalNovoUsuario').classList.remove('hidden')"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
            Novo Usuário
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-lg">
        <ul id="listaUsuarios" class="divide-y divide-gray-200 dark:divide-gray-700">
            <li class="p-4 text-center text-slate-500">Carregando...</li>
        </ul>
    </div>
</div>

<!-- Modal Novo Usuario -->
<div id="modalNovoUsuario" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-slate-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">Novo Usuário</h3>
            <form id="formNovoUsuario" class="mt-4 text-left">
                <div class="mb-4">
                    <label class="block text-slate-700 dark:text-slate-300 text-sm font-bold mb-2">Nome</label>
                    <input type="text" name="nome" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-slate-700 dark:text-white dark:border-slate-600">
                </div>
                <div class="mb-4">
                    <label class="block text-slate-700 dark:text-slate-300 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-slate-700 dark:text-white dark:border-slate-600">
                </div>
                <div class="mb-4">
                    <label class="block text-slate-700 dark:text-slate-300 text-sm font-bold mb-2">Senha</label>
                    <input type="password" name="senha" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-slate-700 dark:text-white dark:border-slate-600">
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('modalNovoUsuario').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    async function loadUsuarios() {
        const res = await fetch('api/list_usuarios.php');
        const data = await res.json();

        const container = document.getElementById('listaUsuarios');
        container.innerHTML = '';

        data.forEach(user => {
            const li = document.createElement('li');
            li.className = 'px-4 py-4 sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center justify-between';
            li.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                        ${user.nome.charAt(0).toUpperCase()}
                    </span>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400">${user.nome}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">${user.email} (${user.role})</div>
                </div>
            </div>
            <div>
                 <button onclick="deleteUsuario(${user.id})" class="text-red-400 hover:text-red-600 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
            container.appendChild(li);
        });
    }

    async function deleteUsuario(id) {
        if (!confirm('Excluir este usuário?')) return;
        const res = await fetch('api/delete_user.php', {
            method: 'POST',
            body: JSON.stringify({ id })
        });
        const result = await res.json();
        if (res.ok) {
            loadUsuarios();
        } else {
            alert(result.error);
        }
    }

    document.getElementById('formNovoUsuario').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        // Using register API for admin to create user too
        const res = await fetch('api/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (res.ok) {
            document.getElementById('modalNovoUsuario').classList.add('hidden');
            e.target.reset();
            loadUsuarios();
        } else {
            alert('Erro ao criar usuário');
        }
    });

    loadUsuarios();
</script>

<?php renderFooter(); ?>