<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - IAFinance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-16 w-auto" src="assets/logo.png" alt="IAFinance">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Criar nova conta</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form id="registerForm" class="space-y-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                    <div class="mt-1">
                        <input id="nome" name="nome" type="text" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                    <div class="mt-1">
                        <input id="senha" name="senha" type="password" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        Cadastrar
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Já tem uma conta?</span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="index.php" class="font-medium text-blue-600 hover:text-blue-500">
                        Entrar
                    </a>
                </div>
            </div>

            <div id="msg" class="mt-4 text-center text-sm hidden"></div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                const res = await fetch('api/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();

                const msg = document.getElementById('msg');
                msg.classList.remove('hidden');

                if (res.ok) {
                    msg.innerHTML = '<span class="text-green-600">Cadastro realizado! Redirecionando...</span>';
                    setTimeout(() => window.location.href = 'index.php', 2000);
                } else {
                    msg.innerHTML = `<span class="text-red-500">${result.error || 'Erro ao cadastrar'}</span>`;
                }
            } catch (err) {
                console.error(err);
            }
        });
    </script>
</body>

</html>