<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login - Organizador Financeiro</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { primary: "hsl(152 55% 45%)" }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="settings" class="w-8 h-8"></i>
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mb-2">Configuração Necessária</h1>
        <p class="text-slate-600 mb-8">
            O Login com Google requer configuração de credenciais no backend (Client ID e Secret).
            <br><br>
            Como este é um ambiente local, esta funcionalidade está em modo de demonstração.
        </p>

        <a href="index.php"
            class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition-colors w-full">
            Voltar para Login
        </a>
    </div>
    <script>lucide.createIcons();</script>
</body>

</html>