<?php
require_once 'config/config.php';

try {
    $stmt = $pdo->prepare('SELECT id, name, type, is_assignable FROM contract_templates ORDER BY id');
    $stmt->execute();
    $contracts = $stmt->fetchAll();
    
    echo "Current contracts in database:\n";
    foreach ($contracts as $contract) {
        echo "- ID: {$contract['id']}, Name: {$contract['name']}, Type: {$contract['type']}, Assignable: " . ($contract['is_assignable'] ? 'Yes' : 'No') . "\n";
    }
    echo "\nTotal contracts: " . count($contracts) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
