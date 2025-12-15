<?php
// api/import_data_2026.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';

// Allow running from CLI or Browser (authenticated)
if (php_sapi_name() !== 'cli') {
    requireAuth();
}

if (php_sapi_name() === 'cli') {
    $user_id = 3; // Confirmed: cristopheraquino20@gmail.com is ID 3
} else {
    $user_id = getCurrentUserId();
}
$year = 2026;

echo "Iniciando importação para o ano $year...\n";

// --- DADOS DO USUÁRIO ---
// [Nome da Categoria] => [Array de 12 valores]
// Se o valor for único (float), repete para os 12 meses.
// Se for array, usa os valores específicos (Jan a Dez).

$receitas = [
    'Minha Renda' => 12000.00,
    'Renda - Conjugê' => 500.00,
    'Ajuda de Custo - Irmão' => 300.00,
    'Locação de Moto' => 1400.00
];

$despesas = [
    'Prestações (Financiamento) Carro' => 1650.00,
    'Prestações (Financiamento) Casa' => 393.60,
    'Garagem' => 300.00,
    'Supermercado' => 1200.00,
    'Açougue' => 300.00,
    'Telefone' => 69.88,
    'Youtube' => 12.00,
    'Seguro do Moto' => 151.20,

    // Variáveis / Cartões
    'Cartão Jonata (dia 5) Santander' => [
        707.06,
        449.66,
        363.74,
        363.74,
        300.00,
        300.00,
        300.00,
        0,
        0,
        0,
        0,
        0
    ],
    'Cartão Jonata (dia 10) MP' => [
        1011.73,
        621.89,
        391.83,
        391.83,
        199.35,
        199.35,
        199.35,
        199.35,
        39.35,
        0,
        0,
        0
    ],
    'Cartão Jonata (dia 10) Inter' => [
        170.00,
        170.00,
        170.00,
        170.00,
        170.00,
        170.00,
        170.00,
        0,
        0,
        0,
        0,
        0
    ],
    'Cartão Cristopher (dia 10)' => [
        1068.14,
        1068.14,
        1068.14,
        1068.14,
        1068.14,
        1068.14,
        1068.14,
        1068.14,
        1068.14,
        1068.14,
        0,
        0
    ],
    'Cartão Cristopher (dia 20)' => [
        625.00,
        625.00,
        625.00,
        625.00,
        625.00,
        0,
        0,
        0,
        0,
        0,
        0,
        0
    ],
    'Cartão Cristopher (dia 25)' => [
        588.38,
        551.38,
        551.38,
        551.38,
        384.88,
        384.88,
        79.88,
        0,
        0,
        0,
        0,
        0
    ]
];


try {
    $pdo->beginTransaction();

    // 1. Limpar previsões de 2026 existentes (para evitar duplicação em re-run)
    echo "Limpando previsões antigas de $year...\n";
    $stmtDelRec = $pdo->prepare("DELETE FROM receitas WHERE user_id = ? AND EXTRACT(YEAR FROM data) = ? AND is_forecast = TRUE");
    $stmtDelRec->execute([$user_id, $year]);

    $stmtDelDes = $pdo->prepare("DELETE FROM despesas WHERE user_id = ? AND EXTRACT(YEAR FROM data) = ? AND is_forecast = TRUE");
    $stmtDelDes->execute([$user_id, $year]);


    // Helper para processar array ou valor fixo
    function getValues($input)
    {
        if (is_array($input)) {
            // Pad with 0 if less than 12
            return array_pad($input, 12, 0);
        }
        return array_fill(0, 12, $input);
    }

    // Helper para criar categoria se não existir
    function ensureCategory($pdo, $uid, $nome, $tipo)
    {
        // Check exist
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE user_id = ? AND nome = ? AND tipo = ?");
        $stmt->execute([$uid, $nome, $tipo]);
        if ($stmt->fetch())
            return; // Exists

        // Create
        $stmt = $pdo->prepare("INSERT INTO categories (user_id, nome, tipo, cor) VALUES (?, ?, ?, ?)");
        // Cores aleatórias ou fixas? Vamos usar cinza padrão por enquanto
        $cor = ($tipo === 'receita') ? '#22c55e' : '#ef4444';
        $stmt->execute([$uid, $nome, $tipo, $cor]);
        echo "Categoria criada: $nome ($tipo)\n";
    }

    // Processar Receitas
    echo "Importando Receitas...\n";
    $stmtInsRec = $pdo->prepare("INSERT INTO receitas (user_id, descricao, valor, categoria, data, is_forecast, pago) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($receitas as $nome => $dados) {
        ensureCategory($pdo, $user_id, $nome, 'receita');
        $valores = getValues($dados);

        foreach ($valores as $mesIdx => $valor) {
            if ($valor <= 0)
                continue; // Skip zeros

            $mes = $mesIdx + 1;
            $dataStr = sprintf("%d-%02d-01", $year, $mes);

            // Descrição igual Categoria para orçamento
            $stmtInsRec->execute([$user_id, $nome, $valor, $nome, $dataStr, 'true', 'false']);
        }
    }

    // Processar Despesas
    echo "Importando Despesas...\n";
    $stmtInsDes = $pdo->prepare("INSERT INTO despesas (user_id, descricao, valor, categoria, data, is_forecast, pago) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($despesas as $nome => $dados) {
        ensureCategory($pdo, $user_id, $nome, 'despesa');
        $valores = getValues($dados);

        foreach ($valores as $mesIdx => $valor) {
            if ($valor <= 0)
                continue; // Skip zeros

            $mes = $mesIdx + 1;
            $dataStr = sprintf("%d-%02d-01", $year, $mes);

            $stmtInsDes->execute([$user_id, $nome, $valor, $nome, $dataStr, 'true', 'false']);
        }
    }

    $pdo->commit();
    echo "Importação concluída com sucesso!\n";

} catch (Exception $e) {
    if ($pdo->inTransaction())
        $pdo->rollBack();
    echo "Erro: " . $e->getMessage() . "\n";
    http_response_code(500);
}
?>