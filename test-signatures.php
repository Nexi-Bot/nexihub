<?php
require_once 'config/config.php';

try {
    // Connect to database
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Create sample signature data (base64 encoded small PNG images)
    $sample_signature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    $guardian_signature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    
    // Get existing staff_contracts to update with signature data
    $stmt = $db->prepare("SELECT * FROM staff_contracts WHERE is_signed = 1 LIMIT 2");
    $stmt->execute();
    $contracts = $stmt->fetchAll();
    
    if (empty($contracts)) {
        echo "No signed contracts found. Creating test signatures...\n";
        
        // Get some templates and staff
        $templates = $db->query("SELECT id FROM contract_templates LIMIT 2")->fetchAll();
        $staff = $db->query("SELECT id FROM staff_profiles LIMIT 2")->fetchAll();
        
        if (!empty($templates) && !empty($staff)) {
            foreach ($templates as $i => $template) {
                $staff_id = $staff[$i % count($staff)]['id'];
                $template_id = $template['id'];
                
                // Check if contract already exists
                $existing = $db->prepare("SELECT id FROM staff_contracts WHERE staff_id = ? AND template_id = ?");
                $existing->execute([$staff_id, $template_id]);
                
                if (!$existing->fetch()) {
                    // Insert new signed contract
                    $stmt = $db->prepare("
                        INSERT INTO staff_contracts (
                            staff_id, template_id, is_signed, signed_at,
                            signature_data, signer_full_name, signer_position,
                            signer_date_of_birth, is_under_17, signed_timestamp
                        ) VALUES (?, ?, 1, ?, ?, ?, ?, ?, 0, ?)
                    ");
                    
                    $signed_at = date('Y-m-d H:i:s');
                    $stmt->execute([
                        $staff_id,
                        $template_id,
                        $signed_at,
                        $sample_signature,
                        'John Doe',
                        'Software Developer',
                        '1990-05-15',
                        $signed_at
                    ]);
                    
                    echo "Created signed contract for staff_id: $staff_id, template_id: $template_id\n";
                }
            }
        }
    } else {
        // Update existing contracts with sample signature data
        foreach ($contracts as $contract) {
            $stmt = $db->prepare("
                UPDATE staff_contracts 
                SET signature_data = ?,
                    signer_full_name = COALESCE(signer_full_name, 'John Doe'),
                    signer_position = COALESCE(signer_position, 'Software Developer'),
                    signer_date_of_birth = COALESCE(signer_date_of_birth, '1990-05-15'),
                    signed_timestamp = COALESCE(signed_timestamp, signed_at)
                WHERE id = ?
            ");
            $stmt->execute([$sample_signature, $contract['id']]);
            echo "Updated contract ID: " . $contract['id'] . " with signature data\n";
        }
    }
    
    // Also create a contract for a minor (under 17) with guardian signature
    $minor_template = $db->query("SELECT id FROM contract_templates LIMIT 1")->fetch();
    if ($minor_template) {
        // Check if we have a staff profile marked as under 17, or create one
        $minor_staff = $db->query("SELECT id FROM staff_profiles WHERE date_of_birth > '2008-01-01' LIMIT 1")->fetch();
        
        if (!$minor_staff) {
            // Create a minor staff profile
            $stmt = $db->prepare("
                INSERT INTO staff_profiles (full_name, nexi_email, job_title, department, date_of_birth, staff_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                'Alice Smith',
                'alice.smith@nexibot.com',
                'Junior Intern',
                'Development',
                '2008-03-20',
                'NEXI-' . rand(1000, 9999)
            ]);
            $minor_staff_id = $db->lastInsertId();
            echo "Created minor staff profile with ID: $minor_staff_id\n";
        } else {
            $minor_staff_id = $minor_staff['id'];
        }
        
        // Check if contract exists for this minor
        $existing = $db->prepare("SELECT id FROM staff_contracts WHERE staff_id = ? AND template_id = ?");
        $existing->execute([$minor_staff_id, $minor_template['id']]);
        
        if (!$existing->fetch()) {
            // Create signed contract with guardian signature
            $stmt = $db->prepare("
                INSERT INTO staff_contracts (
                    staff_id, template_id, is_signed, signed_at,
                    signature_data, signer_full_name, signer_position,
                    signer_date_of_birth, is_under_17,
                    guardian_full_name, guardian_email, guardian_signature_data,
                    signed_timestamp
                ) VALUES (?, ?, 1, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?)
            ");
            
            $signed_at = date('Y-m-d H:i:s');
            $stmt->execute([
                $minor_staff_id,
                $minor_template['id'],
                $signed_at,
                $sample_signature,
                'Alice Smith',
                'Junior Intern',
                '2008-03-20',
                'Mary Smith',
                'mary.smith@example.com',
                $guardian_signature,
                $signed_at
            ]);
            
            echo "Created signed contract with guardian signature for minor staff\n";
        }
    }
    
    echo "Test signature data creation completed!\n";
    echo "You can now test viewing signed contracts in the portal.\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
