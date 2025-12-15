<?php
// api/export_excel.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();

$user_id = getCurrentUserId();

// Define filename
$filename = "extrato_financeiro_" . date('Y-m-d') . ".csv";

// Headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Add BOM for Excel UTF-8 compatibility
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Column Headers
fputcsv($output, ['ID', 'Data', 'Tipo', 'Descrição', 'Valor', 'Categoria', 'Conta', 'Status', 'Observação'], ';');

try {
    // Query ALL transactions ordered by date DESC
    $sql = "
        SELECT id, 'Receita' as tipo, descricao, valor, categoria, conta, pago, observacao, data 
        FROM receitas 
        WHERE user_id = ? 
        UNION ALL
        SELECT id, 'Despesa' as tipo, descricao, valor, categoria, conta, pago, observacao, data 
        FROM despesas 
        WHERE user_id = ? 
        ORDER BY data DESC, id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $user_id]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Format values
        $valor = number_format($row['valor'], 2, ',', '.');
        $status = $row['pago'] ? 'Pago/Recebido' : 'Pendente';
        $data = date('d/m/Y', strtotime($row['data']));

        // Write row
        fputcsv($output, [
            $row['id'],
            $data,
            $row['tipo'],
            $row['descricao'],
            $valor,
            $row['categoria'],
            $row['conta'] ?? '-',
            $status,
            $row['observacao'] ?? ''
        ], ';');
    }

} catch (Exception $e) {
    // If error, write to CSV
    fputcsv($output, ['Erro ao gerar relatório', $e->getMessage()]);
}

fclose($output);
exit;
?>