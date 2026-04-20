<?php
// planos.php
require_once __DIR__ . '/src/auth.php'; // ensure logged in
requireAuth();
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/layout.php';

// Check if user is already active, if so, redirect them to dashboard
$userId = getCurrentUserId();
$stmt = $pdo->prepare("SELECT subscription_status, current_period_end FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($user && ($user['subscription_status'] === 'active' || $user['subscription_status'] === 'lifetime')) {
    header("Location: dashboard.php");
    exit;
}

renderHeader('Escolha seu Plano | Organiza+');
?>

<main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900 min-h-[calc(100vh-4rem)] flex items-center justify-center p-4">
    <div class="max-w-4xl mx-auto w-full">
        
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-slate-800 dark:text-white mb-4">Invista na sua paz financeira</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                Você chegou ao limite da versão gratuita ou seu plano expirou. Escolha um dos pacotes abaixo para desbloquear o Organiza+ e continue tomando as melhores decisões com o seu dinheiro.
            </p>
        </div>

        <?php if (empty($user['current_period_end'])): ?>
        <div class="max-w-3xl mx-auto mb-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl p-1 relative overflow-hidden shadow-xl" id="trialBanner">
            <div class="bg-white dark:bg-slate-900 rounded-[22px] p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                <div>
                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-xs font-bold rounded-full uppercase tracking-wider mb-2">Oferta de Boas Vindas</span>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Desbloqueie 20 Dias Grátis</h3>
                    <p class="text-slate-500 dark:text-slate-400">Teste todas as funcionalidades Premium sem compromisso. Cancele ou mude de plano quando quiser.</p>
                </div>
                <div class="flex-shrink-0 w-full md:w-auto">
                    <button onclick="startFreeTrial()" class="w-full md:w-auto px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1">
                        Começar Teste Agora
                    </button>
                </div>
            </div>
            <!-- Decoração de fundo mágica -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 z-0"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 z-0"></div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            
            <!-- Botão/Card Plano Mensal -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 p-8 flex flex-col hover:border-blue-500 transition-colors">
                <div class="mb-4">
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Mensal</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Flexibilidade Total</h3>
                <div class="my-4 flex items-baseline gap-2">
                    <span class="text-5xl font-extrabold text-slate-800 dark:text-white">R$29</span>
                    <span class="text-lg text-slate-500 font-medium">/mês</span>
                </div>
                <ul class="flex-1 space-y-4 mb-8 text-slate-600 dark:text-slate-300">
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Lançamentos ilimitados
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Exportação em PDF e Excel
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Metas e Categorias customizadas
                    </li>
                </ul>
                <!-- We will add the logic later to call process_checkout.php -->
                <a href="api/create_checkout_session.php?plan=monthly" class="w-full text-center py-4 rounded-xl font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 transition-colors border border-blue-200 dark:border-slate-600">
                    Assinar Plano Mensal
                </a>
            </div>

            <!-- Botão/Card Plano Anual -->
            <div class="bg-blue-600 rounded-3xl shadow-2xl p-8 flex flex-col relative transform md:-translate-y-4 border-2 border-blue-400">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="bg-gradient-to-r from-amber-400 to-amber-600 text-white text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-lg">Mais Popular - 20% OFF</span>
                </div>
                <div class="mb-4 mt-2">
                    <span class="bg-blue-500/50 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Anual</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Compromisso Organiza+</h3>
                <div class="my-4 flex items-baseline gap-2">
                    <span class="text-5xl font-extrabold text-white">R$249</span>
                    <span class="text-lg text-blue-200 font-medium">/ano</span>
                </div>
                <p class="text-blue-200 mb-6 text-sm">O equivalente a apenas R$ 20,75 por mês. Pague menos, organize mais.</p>
                <ul class="flex-1 space-y-4 mb-8 text-blue-50">
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tudo do plano Mensal
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Desconto no plano a longo prazo
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Suporte e atualizações prioritárias
                    </li>
                </ul>
                <a href="api/create_checkout_session.php?plan=yearly" class="w-full text-center py-4 rounded-xl font-bold text-blue-600 bg-white hover:bg-slate-50 transition-colors shadow-lg">
                    Assinar Plano Anual
                </a>
            </div>

        </div>
        
        <div class="mt-8 text-center text-sm text-slate-500">
            Dúvidas? <a href="mailto:suporte@organizaplus.com" class="text-blue-600 hover:underline">Fale com o suporte.</a>
        </div>
    </div>
</main>

<script>
    document.body.classList.remove('privacy-mode');

    async function startFreeTrial() {
        // Simple loading state
        const btn = document.querySelector('#trialBanner button');
        const oldText = btn.innerHTML;
        btn.innerHTML = 'Ativando...';
        btn.disabled = true;

        try {
            const res = await fetch('api/start_trial.php', { method: 'POST' });
            const data = await res.json();
            
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                alert('Erro: ' + data.error);
                btn.innerHTML = oldText;
                btn.disabled = false;
            }
        } catch (e) {
            alert('Erro ao conectar.');
            btn.innerHTML = oldText;
            btn.disabled = false;
        }
    }
</script>
</body>
</html>
