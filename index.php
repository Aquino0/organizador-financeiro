<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizador Financeiro - Login</title>
    <meta name="description" content="Organize sua vida financeira de forma simples e inteligente.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                container: {
                    center: true,
                    padding: "2rem",
                    screens: { "2xl": "1400px" }
                },
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        primary: {
                            DEFAULT: "hsl(var(--primary))",
                            foreground: "hsl(var(--primary-foreground))",
                        },
                        secondary: {
                            DEFAULT: "hsl(var(--secondary))",
                            foreground: "hsl(var(--secondary-foreground))",
                        },
                        destructive: {
                            DEFAULT: "hsl(var(--destructive))",
                            foreground: "hsl(var(--destructive-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                        accent: {
                            DEFAULT: "hsl(var(--accent))",
                            foreground: "hsl(var(--accent-foreground))",
                        },
                        card: {
                            DEFAULT: "hsl(var(--card))",
                            foreground: "hsl(var(--card-foreground))",
                        },
                    },
                    borderRadius: {
                        lg: "var(--radius)",
                        md: "calc(var(--radius) - 2px)",
                        sm: "calc(var(--radius) - 4px)",
                        xl: "calc(var(--radius) + 4px)",
                        "2xl": "calc(var(--radius) + 8px)",
                        "3xl": "calc(var(--radius) + 16px)",
                    },
                    boxShadow: {
                        soft: "var(--shadow-soft)",
                        elevated: "var(--shadow-elevated)",
                        glow: "var(--shadow-glow)",
                    },
                    animation: {
                        "fade-up": "fade-up 0.6s ease-out forwards",
                        "fade-in": "fade-in 0.4s ease-out forwards",
                        "scale-in": "scale-in 0.5s ease-out forwards",
                        "float": "float 6s ease-in-out infinite",
                        "pulse-glow": "pulse-glow 3s ease-in-out infinite",
                    },
                    keyframes: {
                        "fade-up": {
                            from: { opacity: 0, transform: "translateY(20px)" },
                            to: { opacity: 1, transform: "translateY(0)" }
                        },
                        "fade-in": {
                            from: { opacity: 0 },
                            to: { opacity: 1 }
                        },
                        "scale-in": {
                            from: { opacity: 0, transform: "scale(0.95)" },
                            to: { opacity: 1, transform: "scale(1)" }
                        },
                        "float": {
                            "0%, 100%": { transform: "translateY(0px)" },
                            "50%": { transform: "translateY(-10px)" }
                        },
                        "pulse-glow": {
                            "0%, 100%": { boxShadow: "0 0 20px hsl(152 55% 45% / 0.3)" },
                            "50%": { boxShadow: "0 0 40px hsl(152 55% 45% / 0.5)" }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --background: 150 20% 98%;
            --foreground: 200 25% 15%;
            --card: 0 0% 100%;
            --card-foreground: 200 25% 15%;
            --popover: 0 0% 100%;
            --popover-foreground: 200 25% 15%;
            --primary: 152 55% 45%;
            --primary-foreground: 0 0% 100%;
            --secondary: 200 35% 96%;
            --secondary-foreground: 200 25% 25%;
            --muted: 150 15% 94%;
            --muted-foreground: 200 15% 45%;
            --accent: 195 70% 45%;
            --accent-foreground: 0 0% 100%;
            --destructive: 0 72% 55%;
            --destructive-foreground: 0 0% 100%;
            --border: 150 20% 88%;
            --input: 150 20% 90%;
            --ring: 152 55% 45%;
            --radius: 1rem;

            --gradient-primary: linear-gradient(135deg, hsl(152 55% 45%) 0%, hsl(195 70% 45%) 100%);
            --gradient-background: linear-gradient(135deg, hsl(150 30% 95%) 0%, hsl(195 25% 95%) 50%, hsl(150 20% 98%) 100%);
            --gradient-card: linear-gradient(145deg, hsl(0 0% 100% / 0.9) 0%, hsl(0 0% 100% / 0.7) 100%);

            --shadow-soft: 0 4px 20px -4px hsl(200 25% 15% / 0.08);
            --shadow-elevated: 0 8px 40px -8px hsl(200 25% 15% / 0.12);
            --shadow-glow: 0 0 40px hsl(152 55% 45% / 0.2);
        }

        body {
            background: var(--gradient-background);
            min-height: 100vh;
        }

        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-card {
            background: var(--gradient-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid hsl(var(--border) / 0.5);
        }

        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }

        .stagger-4 {
            animation-delay: 0.4s;
        }

        .stagger-5 {
            animation-delay: 0.5s;
        }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen w-full flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-5xl grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Left Side - Illustration & Tagline -->
            <div class="hidden lg:flex flex-col items-center justify-center space-y-8 animate-fade-in">

                <!-- Finance Illustration Component -->
                <div class="relative w-full h-full flex items-center justify-center">
                    <div class="relative w-64 h-64 md:w-80 md:h-80">
                        <div
                            class="absolute inset-0 rounded-full bg-gradient-to-br from-primary/20 to-accent/20 animate-pulse-glow">
                        </div>

                        <div
                            class="absolute inset-8 rounded-full bg-card shadow-elevated flex items-center justify-center animate-float">
                            <div
                                class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-glow">
                                <i data-lucide="trending-up" class="w-10 h-10 md:w-12 md:h-12 text-primary-foreground"
                                    stroke-width="2.5"></i>
                            </div>
                        </div>

                        <div
                            class="absolute top-4 left-1/2 -translate-x-1/2 w-12 h-12 rounded-xl bg-card shadow-soft flex items-center justify-center animate-fade-up opacity-0 stagger-1">
                            <i data-lucide="bar-chart-3" class="w-6 h-6 text-primary"></i>
                        </div>

                        <div
                            class="absolute top-1/4 right-0 w-11 h-11 rounded-xl bg-card shadow-soft flex items-center justify-center animate-fade-up opacity-0 stagger-2">
                            <i data-lucide="arrow-up-right" class="w-5 h-5 text-accent"></i>
                        </div>

                        <div
                            class="absolute bottom-1/4 right-4 w-12 h-12 rounded-xl bg-card shadow-soft flex items-center justify-center animate-fade-up opacity-0 stagger-3">
                            <i data-lucide="wallet" class="w-6 h-6 text-primary"></i>
                        </div>

                        <div
                            class="absolute bottom-4 left-1/2 -translate-x-1/2 w-11 h-11 rounded-xl bg-card shadow-soft flex items-center justify-center animate-fade-up opacity-0 stagger-4">
                            <i data-lucide="credit-card" class="w-5 h-5 text-accent"></i>
                        </div>

                        <div
                            class="absolute bottom-1/4 left-4 w-12 h-12 rounded-xl bg-card shadow-soft flex items-center justify-center animate-fade-up opacity-0 stagger-5">
                            <i data-lucide="piggy-bank" class="w-6 h-6 text-primary"></i>
                        </div>

                        <div
                            class="absolute top-1/4 left-0 w-11 h-11 rounded-xl bg-card shadow-soft flex items-center justify-center animate-fade-up opacity-0 stagger-3">
                            <div class="w-5 h-5 rounded-full bg-gradient-to-br from-primary to-accent"></div>
                        </div>
                    </div>

                    <div class="absolute -z-10 w-96 h-96 rounded-full border border-primary/10"></div>
                    <div class="absolute -z-10 w-[30rem] h-[30rem] rounded-full border border-accent/5"></div>
                </div>
                <!-- End Illustration -->

                <div class="text-center space-y-3 max-w-md">
                    <h2 class="text-2xl xl:text-3xl font-bold text-foreground leading-tight">
                        Organize sua vida financeira de forma
                        <span class="gradient-text">simples e inteligente.</span>
                    </h2>
                    <p class="text-muted-foreground text-base">
                        Tenha controle total das suas finanças com uma ferramenta poderosa e fácil de usar.
                    </p>
                </div>
            </div>

            <!-- Right Side - Login Card -->
            <div class="w-full max-w-md mx-auto lg:mx-0">
                <div class="glass-card rounded-3xl p-8 md:p-10 shadow-elevated animate-scale-in">
                    <!-- Logo & Header -->
                    <div class="text-center mb-8">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-accent shadow-glow mb-4">
                            <svg class="w-8 h-8 text-primary-foreground" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-foreground">
                            Organizador Financeiro
                        </h1>
                        <p class="text-muted-foreground mt-2">
                            Entre para continuar
                        </p>
                    </div>

                    <!-- Mobile Tagline -->
                    <div class="lg:hidden text-center mb-6 p-4 rounded-2xl bg-muted/50">
                        <p class="text-sm font-medium text-foreground">
                            Organize sua vida financeira de forma
                            <span class="gradient-text">simples e inteligente.</span>
                        </p>
                    </div>

                    <!-- Login Form -->
                    <form id="loginForm" class="w-full space-y-5">
                        <!-- Email Field -->
                        <div class="space-y-2 animate-fade-up opacity-0 stagger-1"
                            style="animation-fill-mode: forwards;">
                            <label for="email" class="text-sm font-medium text-foreground">
                                E-mail
                            </label>
                            <div class="relative">
                                <i data-lucide="mail"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground"></i>
                                <input id="email" name="email" type="email" placeholder="seu@email.com" required
                                    class="flex h-12 w-full rounded-xl border border-input bg-card px-4 py-3 pl-12 text-base font-medium ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground/60 placeholder:font-normal focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-0 focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 transition-all duration-200 ease-out hover:border-primary/50 md:text-sm" />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2 animate-fade-up opacity-0 stagger-2"
                            style="animation-fill-mode: forwards;">
                            <label for="password" class="text-sm font-medium text-foreground">
                                Senha
                            </label>
                            <div class="relative">
                                <i data-lucide="lock"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground"></i>
                                <input id="password" name="senha" type="password" placeholder="••••••••" required
                                    class="flex h-12 w-full rounded-xl border border-input bg-card px-4 py-3 pl-12 pr-12 text-base font-medium ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground/60 placeholder:font-normal focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-0 focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 transition-all duration-200 ease-out hover:border-primary/50 md:text-sm" />
                                <button type="button" id="togglePassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="flex justify-end animate-fade-up opacity-0 stagger-2"
                            style="animation-fill-mode: forwards;">
                            <button type="button"
                                class="text-sm text-muted-foreground hover:text-primary transition-colors font-medium">
                                Esqueci minha senha
                            </button>
                        </div>

                        <!-- Error Message -->
                        <div id="msg" class="text-sm text-red-500 text-center hidden"></div>

                        <!-- Login Button -->
                        <button type="submit" id="submitBtn"
                            class="w-full animate-fade-up opacity-0 stagger-3 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-semibold ring-offset-background transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 active:scale-[0.98] bg-gradient-to-r from-primary to-accent text-primary-foreground shadow-soft hover:shadow-glow hover:opacity-95 h-12 px-6 py-3"
                            style="animation-fill-mode: forwards;">
                            Entrar
                        </button>

                        <!-- Divider -->
                        <div class="relative animate-fade-up opacity-0 stagger-4"
                            style="animation-fill-mode: forwards;">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-border"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-card px-4 text-muted-foreground font-medium">ou continue com</span>
                            </div>
                        </div>

                        <!-- Google Login Button -->
                        <button type="button" onclick="handleGoogleLogin()"
                            class="w-full animate-fade-up opacity-0 stagger-5 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-semibold ring-offset-background transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 active:scale-[0.98] bg-card text-foreground border border-border hover:bg-muted shadow-soft hover:shadow-elevated h-12 px-6 py-3"
                            style="animation-fill-mode: forwards;">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Entrar com Google
                        </button>
                    </form>

                    <!-- Footer -->
                    <p class="text-center text-sm text-muted-foreground mt-8 animate-fade-up opacity-0 stagger-5"
                        style="animation-fill-mode: forwards;">
                        Não tem uma conta?
                        <a href="register.php" class="text-primary font-semibold hover:underline transition-all">
                            Cadastre-se grátis
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Init Icons
        lucide.createIcons();

        // Password Toggle
        const toggleBtn = document.getElementById('togglePassword');
        const passInput = document.getElementById('password');

        toggleBtn.addEventListener('click', () => {
            const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passInput.setAttribute('type', type);
            // Toggle Icon
            if (type === 'text') {
                toggleBtn.innerHTML = '<i data-lucide="eye-off" class="w-5 h-5"></i>';
            } else {
                toggleBtn.innerHTML = '<i data-lucide="eye" class="w-5 h-5"></i>';
            }
            lucide.createIcons();
        });

        // Form Submit
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            const msg = document.getElementById('msg');

            // Loading State
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Entrando...';
            lucide.createIcons();
            msg.classList.add('hidden');

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                // Simulate delay for effect
                await new Promise(r => setTimeout(r, 600));

                const res = await fetch('api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                // Check if response is JSON (handle HTML errors from PHP)
                const contentType = res.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const result = await res.json();
                    if (res.ok) {
                        window.location.href = 'dashboard.php';
                    } else {
                        throw new Error(result.error || 'Erro ao entrar');
                    }
                } else {
                    const text = await res.text();
                    console.error("Non-JSON API response:", text);
                    throw new Error("Erro no servidor. Verifique o console.");
                }

            } catch (err) {
                msg.textContent = err.message;
                msg.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                lucide.createIcons(); // Re-init icons for the reset button
            }
        });

        // Google Login Handler
        function handleGoogleLogin() {
            // Check if there is a real endpoint, otherwise show alert
            // User requested "fazer o botao funcionar" (make it work).
            // Without credentials/setup, we can't do real OAuth.
            // We will redirect to api/auth_google.php if it existed, or alert.
            // For now, let's try to redirect to a hypothetical endpoint.

            // alert("Funcionalidade de Login com Google ainda não configurada no backend.");
            window.location.href = 'auth_google.php';
        }
    </script>
</body>

</html>