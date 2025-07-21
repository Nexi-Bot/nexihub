<?php
require_once __DIR__ . '/config/config.php';

echo "=== CONTRACT DEBUG REPORT ===\n\n";

echo "1. Database Connection Info:\n";
echo "Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
echo "Database: " . ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite' ? 'SQLite' : 'MySQL') . "\n\n";

echo "2. Contract Templates Table Schema:\n";
if ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
    $stmt = $pdo->query("PRAGMA table_info(contract_templates)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['name']} ({$row['type']}) " . ($row['notnull'] ? 'NOT NULL' : 'NULL') . "\n";
    }
} else {
    $stmt = $pdo->query("DESCRIBE contract_templates");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['Field']} ({$row['Type']}) " . ($row['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
}

echo "\n3. All Contract Templates (RAW):\n";
$stmt = $pdo->prepare("SELECT id, name, type, is_assignable, created_at FROM contract_templates ORDER BY id");
$stmt->execute();
$all_contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total contracts found: " . count($all_contracts) . "\n";
foreach ($all_contracts as $contract) {
    echo "ID: {$contract['id']}, Name: '{$contract['name']}', Type: {$contract['type']}, Assignable: " . ($contract['is_assignable'] ? 'Yes' : 'No') . ", Created: {$contract['created_at']}\n";
}

echo "\n4. Assignable Contracts Only:\n";
$stmt = $pdo->prepare("SELECT id, name, type FROM contract_templates WHERE is_assignable = 1 ORDER BY name");
$stmt->execute();
$assignable = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Assignable contracts found: " . count($assignable) . "\n";
foreach ($assignable as $contract) {
    echo "- {$contract['name']} (Type: {$contract['type']})\n";
}

echo "\n5. Testing Dashboard Query:\n";
$stmt = $pdo->prepare("SELECT * FROM contract_templates WHERE is_assignable = 1 ORDER BY name");
$stmt->execute();
$dashboard_contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Dashboard query result count: " . count($dashboard_contracts) . "\n";

echo "\n6. Checking for Duplicates:\n";
$names = [];
foreach ($all_contracts as $contract) {
    if (isset($names[$contract['name']])) {
        echo "DUPLICATE FOUND: '{$contract['name']}' appears multiple times!\n";
    } else {
        $names[$contract['name']] = true;
    }
}

echo "\nDone.\n";
?>
