<?php
// admin_users.php
require_once __DIR__ . '/src/auth.php';
requireAdmin(); // Ensures they are admin
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/layout.php';

global $pdo;
$stmt = $pdo->query("SELECT id, nome, email, subscription_status, current_period_end FROM users ORDER BY id DESC LIMIT 50");
$users = $stmt->fetchAll();

renderHeader('Painel Administrativo | Organiza+');
?>

<main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900 min-h-[calc(100vh-4rem)] p-4 sm:p-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">Gerenciamento de Clientes</h1>
            <p class="text-slate-500 mt-1">Visão restrita de administrador. Conceda acessos gratuitos ou verifique assinaturas.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs uppercase font-bold text-slate-500 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Usuário</th>
                            <th class="px-6 py-4">Status de Pagamento</th>
                            <th class="px-6 py-4 text-right">Ação Rápida</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <?php foreach($users as $u): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($u['nome'] ?? 'Sem Nome') ?></div>
                                <div class="text-xs text-slate-400"><?= htmlspecialchars($u['email']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($u['subscription_status'] === 'active'): ?>
                                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-bold">Pago via Stripe</span>
                                <?php elseif ($u['subscription_status'] === 'lifetime'): ?>
                                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">Vitalício (Presente)</span>
                                <?php else: ?>
                                    <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-xs font-bold">Aguardando Pgto / Free</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col sm:flex-row gap-2 justify-end items-center">
                                    <?php if ($u['subscription_status'] !== 'lifetime'): ?>
                                        <button onclick="addBonus(<?= $u['id'] ?>, 30)" class="text-xs px-3 py-1 bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800 font-bold rounded-lg hover:bg-green-100 transition-colors">
                                            +30 Dias
                                        </button>
                                        <button onclick="addBonus(<?= $u['id'] ?>, 365)" class="text-xs px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800 font-bold rounded-lg hover:bg-blue-100 transition-colors">
                                            +1 Ano
                                        </button>
                                        <button onclick="grantLifetime(<?= $u['id'] ?>)" class="text-xs px-3 py-1 bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-800 font-bold rounded-lg hover:opacity-80 transition-opacity">
                                            VIP Vitalício
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($u['subscription_status'] === 'lifetime' || $u['subscription_status'] === 'active'): ?>
                                        <button onclick="revokeAccess(<?= $u['id'] ?>)" class="text-xs px-3 py-1 bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 font-bold rounded-lg hover:bg-red-100 transition-colors">
                                            Revogar
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
async function grantLifetime(userId) {
    if (!confirm("Tem certeza que deseja conceder acesso ilimitado sem cobrança a este usuário?")) return;
    
    try {
        const res = await fetch('api/admin_grant_lifetime.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId })
        });
        const data = await res.json();
        
        if (data.success) {
            alert('Acesso vitalício concedido com sucesso!');
            location.reload();
        } else {
            alert('Erro: ' + data.error);
        }
    } catch (e) {
        alert('Erro ao conectar ao servidor.');
    }
}

async function addBonus(userId, days) {
    if (!confirm(`Tem certeza que deseja adicionar +${days} dias de acesso gratuito para este usuário?`)) return;
    
    try {
        const res = await fetch('api/admin_add_bonus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, days: days })
        });
        const data = await res.json();
        
        if (data.success) {
            alert(`Foram adicionados ${days} dias de acesso na conta do usuário!`);
            location.reload();
        } else {
            alert('Erro: ' + data.error);
        }
    } catch (e) {
        alert('Erro ao conectar ao servidor.');
    }
}

async function revokeAccess(userId) {
    if (!confirm("Tem certeza que deseja REVOGAR todo o tempo bônus, assinaturas ativas e status VIP deste cliente? Ele perderá o acesso na mesma hora.")) return;
    
    try {
        const res = await fetch('api/admin_revoke_access.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId })
        });
        const data = await res.json();
        
        if (data.success) {
            alert('Acesso revogado com sucesso. O cliente já voltou para o plano Free.');
            location.reload();
        } else {
            alert('Erro: ' + data.error);
        }
    } catch (e) {
        alert('Erro ao conectar ao servidor.');
    }
}
</script>
</body>
</html>
