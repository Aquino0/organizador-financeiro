<?php
// src/layout.php

function renderHeader($title = 'ORGANIZADOR FINANCEIRO')
{
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR" class="h-full bg-slate-100 dark:bg-slate-900">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title><?php echo $title; ?></title>
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#10b981">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            slate: {
                                850: '#1e293b', // Custom dark color if needed
                            }
                        }
                    }
                }
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <!-- Google Fonts: Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">

        <link rel="manifest" href="manifest.json">
        <meta name="theme-color" content="#3b82f6">
        <style>
            .safe-area-bottom {
                padding-bottom: env(safe-area-inset-bottom);
            }

            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>

    <body class="h-full flex flex-col text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-900">

        <!-- Navbar Superior -->
        <header class="bg-white dark:bg-slate-800 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-2">
                        <!-- logo removed -->
                        <a href="dashboard.php"
                            class="font-bold text-xl tracking-tight text-slate-800 dark:text-white hover:opacity-80 transition-opacity">Organizador
                            Financeiro</a>
                    </div>
                    <!-- Topbar Actions -->
                    <div class="flex items-center gap-2 sm:gap-4">

                        <!-- Btn Adicionar (Redireciona para Lançamentos) -->
                        <a href="lancamentos.php"
                            class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">Adicionar</span>
                        </a>

                        <!-- Btn Exportar -->
                        <div class="relative group hidden sm:block">
                            <button
                                class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Exportar</span>
                            </button>
                            <div
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="api/export_excel.php" target="_blank"
                                    class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 first:rounded-t-xl">Excel
                                    (.csv)</a>
                                <button onclick="exportPDF()"
                                    class="w-full text-left block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 last:rounded-b-xl">PDF
                                    (.pdf)</button>
                            </div>
                        </div>

                        <!-- Btn Atualizar -->
                        <button
                            onclick="if(typeof window.refreshData === 'function'){ window.refreshData(); } else { window.location.reload(); }"
                            class="hidden sm:flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Atualizar</span>
                        </button>

                        <!-- Btn Configurações -->
                        <a href="configuracoes.php"
                            class="hidden sm:flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Configurações</span>
                        </a>

                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-700 mx-2 hidden sm:block"></div>

                        <!-- Notification Bell -->
                        <div class="relative x-notification-container">
                            <button onclick="toggleNotifications()"
                                class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <!-- Badge -->
                                <span id="notifBadge"
                                    class="absolute top-1 right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white dark:border-slate-800 hidden"></span>
                            </button>

                            <!-- Dropdown -->
                            <div id="notifDropdown"
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 hidden z-50 overflow-hidden">
                                <div
                                    class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                    <h3 class="font-bold text-slate-800 dark:text-white">Notificações</h3>
                                    <button onclick="clearNotifications()"
                                        class="text-xs text-slate-400 hover:text-red-500">Limpar</button>
                                </div>
                                <div id="notifList" class="max-h-64 overflow-y-auto">
                                    <div class="p-4 text-center text-slate-400 text-sm">Nenhuma notificação</div>
                                </div>
                            </div>
                        </div>

                        <!-- Theme Toggle -->
                        <!-- Theme Toggle -->
                        <button id="themeToggle"
                            class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <!-- Sun Icon -->
                            <svg id="sunIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Moon Icon -->
                            <svg id="moonIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <?php if (isset($_SESSION['user_name'])): ?>
                            <!-- User Menu -->
                            <div class="relative group">
                                <button class="flex items-center gap-2 focus:outline-none">
                                    <div
                                        class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs overflow-hidden border border-blue-200">
                                        <?php if (!empty($_SESSION['foto_perfil'])): ?>
                                            <img src="<?php echo $_SESSION['foto_perfil']; ?>" alt="Avatar"
                                                class="h-full w-full object-cover">
                                        <?php else: ?>
                                            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 2)); ?>
                                        <?php endif; ?>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                                    <div class="px-4 py-3 border-b border-slate-50 dark:border-slate-700">
                                        <p class="text-sm font-bold text-slate-800 dark:text-white">
                                            <?php echo $_SESSION['user_name']; ?>
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                            <?php echo $_SESSION['user_email'] ?? 'Usuario'; ?>
                                        </p>
                                    </div>
                                    <button onclick="openProfileModal()"
                                        class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Minha Conta
                                    </button>
                                    <button onclick="logout()"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Sair
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Minha Conta Modal -->
        <div id="profileModal"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Minha Conta</h2>
                        <button onclick="closeProfileModal()" class="text-slate-400 hover:text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-slate-200 dark:border-slate-700 mb-6">
                        <button onclick="switchTab('perfil')" id="tab-perfil"
                            class="flex-1 pb-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 transition-colors">Perfil</button>
                        <button onclick="switchTab('seguranca')" id="tab-seguranca"
                            class="flex-1 pb-3 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent transition-colors">Segurança</button>
                    </div>

                    <!-- Content Perfil -->
                    <div id="content-perfil">
                        <form id="formFoto" class="flex flex-col items-center gap-4">
                            <div class="relative group cursor-pointer"
                                onclick="document.getElementById('fotoInput').click()">
                                <div
                                    class="h-24 w-24 rounded-full bg-slate-100 overflow-hidden border-2 border-slate-200 dark:border-slate-700">
                                    <img id="previewFoto"
                                        src="<?php echo !empty($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : ''; ?>"
                                        class="h-full w-full object-cover <?php echo empty($_SESSION['foto_perfil']) ? 'hidden' : ''; ?>">
                                    <div id="placeholderFoto"
                                        class="h-full w-full flex items-center justify-center text-slate-400 <?php echo !empty($_SESSION['foto_perfil']) ? 'hidden' : ''; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div
                                    class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs font-bold">Alterar</span>
                                </div>
                            </div>
                            <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden"
                                onchange="previewImage(this)">

                            <div class="w-full">
                                <label class="block text-xs text-slate-500 uppercase font-bold mb-1">Seu Nome</label>
                                <input type="text" name="nome" value="<?php echo $_SESSION['user_name']; ?>"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500 text-center">
                            </div>

                            <p class="text-xs text-slate-500 text-center">Clique na imagem para alterar.<br>JPG, PNG ou WebP
                                (Máx 5MB).</p>

                            <button type="submit"
                                class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors mt-2">Salvar
                                Perfil</button>
                        </form>
                    </div>

                    <!-- Content Seguranca -->
                    <div id="content-seguranca" class="hidden space-y-4">
                        <form id="formSenha">
                            <div>
                                <label class="block text-xs text-slate-500 uppercase font-bold mb-1">Senha Atual</label>
                                <input type="password" name="current_password" required
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 uppercase font-bold mb-1">Nova Senha</label>
                                <input type="password" name="new_password" required minlength="6"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit"
                                class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors mt-2">Alterar
                                Senha</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <script>
            // --- Notification System (Already defined above) ---
            // Re-using addNotification from global scope if available

            // --- Profile Modal Logic ---
            function openProfileModal() {
                document.getElementById('profileModal').classList.remove('hidden');
            }

            function closeProfileModal() {
                document.getElementById('profileModal').classList.add('hidden');
            }

            function switchTab(tab) {
                const colors = ['text-blue-600', 'border-blue-600'];
                const grays = ['text-slate-500', 'border-transparent'];

                if (tab === 'perfil') {
                    document.getElementById('content-perfil').classList.remove('hidden');
                    document.getElementById('content-seguranca').classList.add('hidden');

                    document.getElementById('tab-perfil').classList.add(...colors);
                    document.getElementById('tab-perfil').classList.remove(...grays);

                    document.getElementById('tab-seguranca').classList.remove(...colors);
                    document.getElementById('tab-seguranca').classList.add(...grays);
                } else {
                    document.getElementById('content-perfil').classList.add('hidden');
                    document.getElementById('content-seguranca').classList.remove('hidden');

                    document.getElementById('tab-seguranca').classList.add(...colors);
                    document.getElementById('tab-seguranca').classList.remove(...grays);

                    document.getElementById('tab-perfil').classList.remove(...colors);
                    document.getElementById('tab-perfil').classList.add(...grays);
                }
            }

            function previewImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('previewFoto').src = e.target.result;
                        document.getElementById('previewFoto').classList.remove('hidden');
                        document.getElementById('placeholderFoto').classList.add('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Forms
            document.getElementById('formFoto').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);
                // FormData automatically captures inputs, including 'nome' and 'foto' if present.
                // Note: file input must verify file presence or not.

                // If user didn't select a file, formData still sends 'foto' as empty file object usually.
                // The API checks optional file.

                try {
                    const res = await fetch('api/update_profile.php', { method: 'POST', body: formData });
                    const data = await res.json();

                    if (data.success) {
                        addNotification('Sucesso', 'Perfil atualizado!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        addNotification('Erro', data.error || 'Erro ao salvar', 'error');
                    }
                } catch (err) {
                    addNotification('Erro', 'Erro de conexão', 'error');
                }
            });

            document.getElementById('formSenha').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);
                const obj = Object.fromEntries(formData);

                try {
                    const res = await fetch('api/change_password.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(obj)
                    });
                    const data = await res.json();

                    if (data.success) {
                        addNotification('Sucesso', 'Senha alterada com sucesso!', 'success');
                        e.target.reset();
                        closeProfileModal();
                    } else {
                        addNotification('Erro', data.error || 'Erro ao alterar', 'error');
                    }
                } catch (err) {
                    addNotification('Erro', 'Erro de conexão', 'error');
                }
            });

            // Notification System
            const NotificationSystem = {
                items: JSON.parse(localStorage.getItem('notificationHistory') || '[]'),

                add(title, message, type = 'info', dedupeId = null) {
                    if (dedupeId) {
                        this.items = this.items.filter(i => i.dedupeId !== dedupeId);
                    }

                    const newItem = {
                        id: Date.now(),
                        dedupeId,
                        title,
                        message,
                        type,
                        time: new Date().toISOString(),
                        read: false
                    };

                    this.items.unshift(newItem);
                    if (this.items.length > 20) this.items.pop();

                    this.save();
                    this.render();
                    this.showBadge();
                },

                markAllRead() {
                    this.items.forEach(i => i.read = true);
                    this.save();
                    this.showBadge();
                },

                clear() {
                    this.items = [];
                    this.save();
                    this.render();
                    this.showBadge();
                },

                save() {
                    localStorage.setItem('notificationHistory', JSON.stringify(this.items));
                },

                getUnreadCount() {
                    return this.items.filter(i => !i.read).length;
                },

                render() {
                    const list = document.getElementById('notifList');
                    if (!list) return;

                    if (this.items.length === 0) {
                        list.innerHTML = '<div class="p-4 text-center text-slate-400 text-sm">Nenhuma notificação</div>';
                        return;
                    }

                    list.innerHTML = this.items.map(item => {
                        let icon = '';
                        let color = '';
                        if (item.type === 'success') { icon = '✓'; color = 'green'; }
                        else if (item.type === 'error') { icon = '✕'; color = 'red'; }
                        else if (item.type === 'warning') { icon = '!'; color = 'amber'; }
                        else { icon = 'ℹ'; color = 'blue'; }

                        return `
                        <div class="px-4 py-3 border-b border-slate-50 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors ${item.read ? 'opacity-75' : 'bg-blue-50/50 dark:bg-blue-900/10'}">
                            <div class="flex gap-3">
                                <div class="h-8 w-8 rounded-full bg-${color}-100 text-${color}-600 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                    ${icon}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">${item.title}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">${item.message}</p>
                                    <p class="text-[10px] text-slate-400 mt-1">${new Date(item.time).toLocaleTimeString()}</p>
                                </div>
                            </div>
                        </div>
                        `;
                    }).join('');
                },

                showBadge() {
                    const badge = document.getElementById('notifBadge');
                    if (!badge) return;

                    if (this.getUnreadCount() > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            };

            // Global Functions
            window.toggleNotifications = function () {
                const drop = document.getElementById('notifDropdown');
                if (drop.classList.contains('hidden')) {
                    drop.classList.remove('hidden');
                    NotificationSystem.markAllRead();
                    NotificationSystem.render();
                } else {
                    drop.classList.add('hidden');
                }
            };

            window.clearNotifications = function () {
                NotificationSystem.clear();
            };

            // Close on click outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.x-notification-container')) {
                    document.getElementById('notifDropdown')?.classList.add('hidden');
                }
            });

            // Check Overdue Logic
            async function checkOverdueExpenses() {
                try {
                    const res = await fetch('api/check_overdue.php');
                    const data = await res.json();

                    if (data.overdue) {
                        const msg = `Você tem ${data.count} despesa(s) vencida(s) totalizando R$ ${data.total_formatted}.`;
                        NotificationSystem.add('Contas em Atraso', msg, 'warning', 'overdue_warning');
                    }
                } catch (e) {
                    console.error('Erro ao verificar atrasos', e);
                }
            }

            // Init
            document.addEventListener('DOMContentLoaded', () => {
                NotificationSystem.render();
                NotificationSystem.showBadge();
                checkOverdueExpenses(); // Check on load
            });

            // Expose helper (support dedupeId)
            window.addNotification = (t, m, ty, did = null) => NotificationSystem.add(t, m, ty, did);
            // Theme Logic
            const themeToggleBtn = document.getElementById('themeToggle');
            const moonIcon = document.getElementById('moonIcon');
            const sunIcon = document.getElementById('sunIcon');

            // Check LocalStorage
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                moonIcon.classList.remove('hidden');
                sunIcon.classList.add('hidden');
            }

            // Toggle
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        moonIcon.classList.remove('hidden');
                        sunIcon.classList.add('hidden');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                        moonIcon.classList.add('hidden');
                        sunIcon.classList.remove('hidden');
                    }
                });
            }

            // PDF Export Logic
            function exportPDF() {
                const element = document.querySelector('main'); // Capture main content
                const opt = {
                    margin: 10,
                    filename: 'relatorio_financeiro.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                // Show loading feedback
                const btn = event.target;
                const originalText = btn.innerText;
                btn.innerText = 'Gerando...';

                html2pdf().set(opt).from(element).save().then(() => {
                    btn.innerText = originalText;
                }).catch(err => {
                    console.error(err);
                    alert('Erro ao gerar PDF');
                    btn.innerText = originalText;
                });
            }

            async function logout() {
                if (confirm('Tem certeza que deseja sair?')) {
                    window.location.href = 'index.php?logout=true';
                }
            }
        </script>

        <main class="flex-1 overflow-y-auto pb-24 safe-area-bottom">
            <?php
}

function renderFooter()
{
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>
        </main>

        <!-- Navbar Inferior (Mobile First) -->
        <nav
            class="fixed bottom-0 w-full bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 pb-safe-area safe-area-bottom z-40">
            <div class="flex justify-around items-center h-16">
                <a href="dashboard.php"
                    class="flex flex-col items-center p-2 <?php echo $current_page == 'dashboard.php' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span class="text-xs mt-1">Home</span>
                </a>

                <!-- FAB Button (Center) -->
                <div class="relative -top-5">
                    <button onclick="openQuickAddModal()"
                        class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 dark:bg-blue-500 text-white shadow-lg shadow-blue-600/30 hover:bg-blue-700 dark:hover:bg-blue-600 transition-all transform hover:scale-105 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>

                <a href="lancamentos.php"
                    class="flex flex-col items-center p-2 <?php echo $current_page == 'lancamentos.php' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                    <span class="text-xs mt-1">Extrato</span>
                </a>

                <!-- Orçamento Link
                <a href="orcamento.php"
                    class="flex flex-col items-center p-2 <?php echo $current_page == 'orcamento.php' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                    </svg>
                    <span class="text-xs mt-1">Orçamento</span>
                </a>
                -->

                <!-- Comparativo Link
                <a href="orcado_realizado.php"
                    class="flex flex-col items-center p-2 <?php echo $current_page == 'orcado_realizado.php' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span class="text-xs mt-1">Comparativo</span>
                </a>
                -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="usuarios.php"
                        class="flex flex-col items-center p-2 <?php echo $current_page == 'usuarios.php' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span class="text-xs mt-1">Usuários</span>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
        <?php
        // Include Global Quick Add Modal
        require_once __DIR__ . '/components/quick_add_modal.php';

        // Includes Toast Component
        require_once __DIR__ . '/components/toast.php';
        ?>
    </body>

    </html>
    <?php
}
?>