<?php
// api/budget_stats.php
// Prevent HTML warnings from breaking JSON immediately
error_reporting(0);
ini_set('display_errors', 0);

require_once '../src/db.php';
require_once '../src/auth.php';
require_once '../src/utils.php';

requireAuth();
$user_id = getCurrentUserId();
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

try {
    // 0. Fetch User Configs
    $stmtUser = $pdo->prepare("SELECT configs_reserva FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $userConfig = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $savedReserve = $userConfig ? floatval($userConfig['configs_reserva']) : 20.0;

    // 1. Fetch Categories (Metadata)
    // We need strict order and colors
    // Note: We now have `sub_type` column (default 'fixa', or 'variavel')
    $stmtCats = $pdo->prepare("SELECT id, nome, tipo, sub_type, cor FROM categories WHERE user_id = ? ORDER BY ordem ASC, nome ASC");
    $stmtCats->execute([$user_id]);
    $allCats = $stmtCats->fetchAll(PDO::FETCH_ASSOC);

    $catIncomeData = [];
    $catExpenseDataFixed = [];
    $catExpenseDataVariable = [];

    $mapIncome = [];
    $mapExpenseFixed = [];
    $mapExpenseVariable = [];

    // Init Arrays
    foreach ($allCats as $cat) {
        $entry = [
            'id' => $cat['id'],
            'name' => $cat['nome'],
            'color' => $cat['cor'] ?? '#cbd5e1',
            'values' => array_fill(0, 12, 0.0),
            'total_used' => 0 // For metadata, populated elsewhere or calculated? (Currently calculated by summing)
        ];

        if ($cat['tipo'] === 'receita') {
            $catIncomeData[] = $entry;
            $mapIncome[$cat['nome']] = count($catIncomeData) - 1;
        } else {
            // Check sub_type for expenses
            $sub = $cat['sub_type'] ?? 'fixa';
            if ($sub === 'variavel') {
                $catExpenseDataVariable[] = $entry;
                $mapExpenseVariable[$cat['nome']] = count($catExpenseDataVariable) - 1;
            } else {
                $catExpenseDataFixed[] = $entry;
                $mapExpenseFixed[$cat['nome']] = count($catExpenseDataFixed) - 1;
            }
        }
    }

    // 2. Fetch Forecast Data & Populate
    // Helper Closure modified to handle 'budget' (is_forecast=TRUE) vs 'realized' (pago=TRUE)
    $processRows = function ($table, $mode, &$dataListFix, &$mapFix, &$dataListVar = null, &$mapVar = null) use ($pdo, $user_id, $year) {
        // Logic:
        // $mode === 'budget': Fetch Forecast (is_forecast = TRUE). Key: 'values'
        // $mode === 'realized': Fetch Paid (pago = TRUE, ignore is_forecast). Key: 'values_realized'

        $whereClause = "";
        if ($mode === 'budget') {
            $whereClause = "AND is_forecast = TRUE";
            $key = 'values';
        } else {
            // Realized: Must be PAID. We verify boolean true.
            $whereClause = "AND pago = TRUE";
            $key = 'values_realized';
        }

        $sql = "
            SELECT 
                EXTRACT(MONTH FROM data) as mes, 
                categoria, 
                SUM(valor) as total 
            FROM $table 
            WHERE user_id = ? 
              AND EXTRACT(YEAR FROM data) = ? 
              $whereClause
            GROUP BY mes, categoria
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $year]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $m = intval($row['mes']) - 1;
            $catName = $row['categoria'];
            $val = floatval($row['total']);

            // Fixed Map?
            if (isset($mapFix[$catName])) {
                $idx = $mapFix[$catName];
                // $key is determined inside closure, but closure signature in previous Replace might have been confusing.
                // Wait, I need to check how I defined $processRows in previous step.
                // It was: $processRows = function ($table, $mode, &$dataListFix, &$mapFix, &$dataListVar = null, &$mapVar = null)
                // And inside: $key = ...
                $dataListFix[$idx][$key][$m] = $val;
            }
            // Variable Map?
            elseif ($dataListVar !== null && isset($mapVar[$catName])) {
                $idx = $mapVar[$catName];
                $dataListVar[$idx][$key][$m] = $val;
            }
            // Category Not Found in User's Category List?
            else {
                // We need to count this! It's a valid transaction just with a text category not in table.
                // We'll treat it as 'Variable' or 'Fixed'? Hard to say. Default to Variable for Expenses, Income for Income.
                // We need to append to the list and map on the fly.

                // Determine list to append to
                $targetList = &$dataListVar;
                $targetMap = &$mapVar;

                // If Income table, we only have Fix list (Income list) passed as first arg usually?
                // Logic check:
                // Call 1 (Income): $processRows('receitas', ..., $catIncomeData, $mapIncome, null, null)
                // Call 2 (Expenses): $processRows('despesas', ..., $catExpenseDataFixed, $mapExpenseFixed, $catExpenseDataVariable, $mapExpenseVariable)

                if ($targetList === null) {
                    // This means it's the Income call (only 2 array args passed)
                    // So fallback to $dataListFix (which is $catIncomeData)
                    $targetList = &$dataListFix;
                    $targetMap = &$mapFix;
                }

                // Add new entry if not exists in map
                if (!isset($targetMap[$catName])) {
                    $newEntry = [
                        'id' => 'temp_' . md5($catName),
                        'name' => $catName,
                        'color' => '#94a3b8', // Slate 400 (Grey) for unknown
                        'values' => array_fill(0, 12, 0.0),
                        'values_realized' => array_fill(0, 12, 0.0)
                    ];
                    $targetList[] = $newEntry;
                    $targetMap[$catName] = count($targetList) - 1;
                }

                // Now set value
                $idx = $targetMap[$catName];
                $targetList[$idx][$key][$m] = $val;
            }
        }
    };

    // Initialize values_realized arrays
    foreach ($catIncomeData as &$cat)
        $cat['values_realized'] = array_fill(0, 12, 0.0);
    foreach ($catExpenseDataFixed as &$cat)
        $cat['values_realized'] = array_fill(0, 12, 0.0);
    foreach ($catExpenseDataVariable as &$cat)
        $cat['values_realized'] = array_fill(0, 12, 0.0);
    unset($cat); // Break ref

    // Fetch Budgeted (Forecast)
    $processRows('receitas', 'budget', $catIncomeData, $mapIncome);
    $processRows('despesas', 'budget', $catExpenseDataFixed, $mapExpenseFixed, $catExpenseDataVariable, $mapExpenseVariable);

    // Fetch Realized (Paid)
    $processRows('receitas', 'realized', $catIncomeData, $mapIncome);
    $processRows('despesas', 'realized', $catExpenseDataFixed, $mapExpenseFixed, $catExpenseDataVariable, $mapExpenseVariable);

    // 3. Calculate Totals (Summing Visible Rows Only)
    $monthly_income = array_fill(0, 12, 0.0);
    $monthly_expenses_fixed = array_fill(0, 12, 0.0);
    $monthly_expenses_variable = array_fill(0, 12, 0.0);
    $monthly_expenses_total = array_fill(0, 12, 0.0);

    // Realized Totals
    $monthly_income_realized = array_fill(0, 12, 0.0);
    $monthly_expenses_realized = array_fill(0, 12, 0.0);

    foreach ($catIncomeData as $cat) {
        foreach ($cat['values'] as $i => $val) {
            $monthly_income[$i] += $val;
        }
        foreach ($cat['values_realized'] as $i => $val) {
            $monthly_income_realized[$i] += $val;
        }
    }
    foreach ($catExpenseDataFixed as $cat) {
        foreach ($cat['values'] as $i => $val) {
            $monthly_expenses_fixed[$i] += $val;
            $monthly_expenses_total[$i] += $val;
        }
        foreach ($cat['values_realized'] as $i => $val) {
            $monthly_expenses_realized[$i] += $val;
        }
    }
    foreach ($catExpenseDataVariable as $cat) {
        foreach ($cat['values'] as $i => $val) {
            $monthly_expenses_variable[$i] += $val;
            $monthly_expenses_total[$i] += $val;
        }
        foreach ($cat['values_realized'] as $i => $val) {
            $monthly_expenses_realized[$i] += $val;
        }
    }

    // 4. Calculate Balances & Grand Totals
    $monthly_balance = array_fill(0, 12, 0.0);
    $accumulated_balance = array_fill(0, 12, 0.0);
    $running_balance = 0.0;
    $total_income = 0.0;
    $total_expenses = 0.0;

    for ($i = 0; $i < 12; $i++) {
        $inc = $monthly_income[$i];
        $exp = $monthly_expenses_total[$i];

        $bal = $inc - $exp;
        $monthly_balance[$i] = $bal;

        $running_balance += $bal;
        $accumulated_balance[$i] = $running_balance;

        $total_income += $inc;
        $total_expenses += $exp;
    }

    $balance = $total_income - $total_expenses;
    $savings_rate = ($total_income > 0) ? (($balance / $total_income) * 100) : 0;
    $committed_rate = ($total_income > 0) ? (($total_expenses / $total_income) * 100) : 0;
    $stmtTop = $pdo->prepare("
        SELECT descricao, valor, categoria, data 
        FROM despesas 
        WHERE user_id = ? AND EXTRACT(YEAR FROM data) = ?
        ORDER BY valor DESC 
        LIMIT 3
    ");
    $stmtTop->execute([$user_id, $year]);
    $top_expenses = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

    // 6. Response
    jsonResponse([
        'year' => $year,
        'total_income' => $total_income,
        'total_expenses' => $total_expenses,
        'balance' => $balance,
        'savings_rate' => $savings_rate,
        'committed_rate' => $committed_rate,

        'monthly_income' => $monthly_income,

        // Split totals for UI headers
        'monthly_expenses_fixed' => $monthly_expenses_fixed,
        'monthly_expenses_variable' => $monthly_expenses_variable,
        'monthly_expenses' => $monthly_expenses_total, // Keep legacy key for global summary if needed

        'monthly_balance' => $monthly_balance,
        'accumulated_balance' => $accumulated_balance,

        'monthly_income_realized' => $monthly_income_realized,
        'monthly_expenses_realized' => $monthly_expenses_realized,

        'category_income' => $catIncomeData,
        'category_expenses_fixed' => $catExpenseDataFixed, // Split list
        'category_expenses_variable' => $catExpenseDataVariable, // Split list

        // Backward compatibility? 
        // 'category_expenses' => array_merge($catExpenseDataFixed, $catExpenseDataVariable), 
        // Actually, let's keep it clean and use new keys in frontend.
        // But renderTable might look for 'category_expenses'. 
        // We should probably check frontend first. 
        // Looking at renderTable(), it checks `if (data.category_expenses)`.
        // So we should probably send `category_expenses` as well OR update frontend to look for new keys.
        // Since I'm updating frontend anyway, I'll use new keys.

        'top_expenses' => $top_expenses,
        'user_config_reserve' => $savedReserve
    ]);

} catch (Exception $e) {
    // Return error as JSON so frontend handles it gracefully-ish
    jsonResponse(['error' => $e->getMessage()], 500);
}
?>