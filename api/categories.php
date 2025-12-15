<?php
// api/categories.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/utils.php';

requireAuth();
$user_id = getCurrentUserId();
$method = $_SERVER['REQUEST_METHOD'];

// GET: List Categories
if ($method === 'GET') {
    // Include total usage sum for each category
    $stmt = $pdo->prepare("
        SELECT c.id, c.nome, c.tipo, c.cor, c.ordem,
        (
            CASE 
                WHEN c.tipo = 'receita' THEN (SELECT COALESCE(SUM(valor), 0) FROM receitas r WHERE r.user_id = c.user_id AND r.categoria = c.nome)
                ELSE (SELECT COALESCE(SUM(valor), 0) FROM despesas d WHERE d.user_id = c.user_id AND d.categoria = c.nome)
            END
        ) as total_used
        FROM categories c 
        WHERE c.user_id = ? 
        ORDER BY c.ordem ASC, c.nome ASC
    ");
    $stmt->execute([$user_id]);
    $all = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $receitas = array_filter($all, fn($c) => $c['tipo'] === 'receita');
    $despesas = array_filter($all, fn($c) => $c['tipo'] === 'despesa');

    jsonResponse([
        'receitas' => array_values($receitas),
        'despesas' => array_values($despesas)
    ]);
}

// POST: Add Category
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nome = trim($data['nome'] ?? '');
    $tipo = $data['tipo'] ?? 'despesa'; // receita or despesa
    $cor = $data['cor'] ?? '#64748b';

    if (empty($nome) || !in_array($tipo, ['receita', 'despesa'])) {
        jsonResponse(['error' => 'Dados inválidos'], 400);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO categories (user_id, nome, tipo, cor) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $nome, $tipo, $cor]);
        jsonResponse(['success' => true, 'id' => $pdo->lastInsertId(), 'nome' => $nome, 'tipo' => $tipo, 'cor' => $cor]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate
            jsonResponse(['error' => 'Categoria já existe'], 409);
        }
        jsonResponse(['error' => 'Erro ao criar categoria'], 500);
    }
}

// PUT: Update Category (Name, Color, Order)
if ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    file_put_contents('/tmp/debug_crm.txt', "Time: " . date('Y-m-d H:i:s') . "\nUser: $user_id\nData: " . print_r($data, true) . "\n", FILE_APPEND);
    // Batch update order?
    if (isset($data['order']) && is_array($data['order'])) {
        // Expects [{id: 1, ordem: 0}, {id: 2, ordem: 1}]
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE categories SET ordem = ? WHERE id = ? AND user_id = ?");
            foreach ($data['order'] as $item) {
                $stmt->execute([$item['ordem'], $item['id'], $user_id]);
            }
            $pdo->commit();
            jsonResponse(['success' => true]);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonResponse(['error' => 'Erro ao reordenar: ' . $e->getMessage()], 500);
        }
        exit;
    }

    // Single Update
    $id = intval($data['id'] ?? 0);
    if (!$id)
        jsonResponse(['error' => 'ID inválido'], 400);

    $fields = [];
    $params = [];

    if (isset($data['nome'])) {
        $fields[] = "nome = ?";
        $params[] = trim($data['nome']);
    }
    if (isset($data['cor'])) {
        $fields[] = "cor = ?";
        $params[] = $data['cor']; // Hex color
    }
    if (isset($data['ordem'])) {
        $fields[] = "ordem = ?";
        $params[] = intval($data['ordem']);
    }

    if (empty($fields)) {
        jsonResponse(['error' => 'Nenhum dado para atualizar'], 400);
    }

    $params[] = $id;
    $params[] = $user_id;

    try {
        $sql = "UPDATE categories SET " . implode(', ', $fields) . " WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        jsonResponse(['success' => true]);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
    }
}

// DELETE: Remove Category
if ($method === 'DELETE') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$id)
        jsonResponse(['error' => 'ID inválido'], 400);

    try {
        // Prevent deletion if used? 
        // For now, let's allow deletion. The transactions will keep the string 'categoria' 
        // because we stored it as string in despesas/receitas tables (denormalized-ish).
        // If we strictly normalized, we would have category_id in despesas. 
        // Since it's VARCHAR in despesas, deleting here just removes it from the "Menu". 
        // The historic data remains safe. Perfect.

        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);

        if ($stmt->rowCount() > 0) {
            jsonResponse(['success' => true]);
        } else {
            jsonResponse(['error' => 'Categoria não encontrada'], 404);
        }
    } catch (Exception $e) {
        jsonResponse(['error' => $e->getMessage()], 500);
    }
}
?>