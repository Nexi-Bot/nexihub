<!DOCTYPE html>
<html>
<head>
    <title>Contract Debug Test</title>
</head>
<body>
    <h1>Contract Debug Test</h1>
    <div id="output"></div>
    
    <script>
        <?php
        require_once __DIR__ . '/config/config.php';
        
        // Force session for Oliver
        $_SESSION['contract_user_id'] = 1;
        $_SESSION['contract_staff_id'] = 1;
        $_SESSION['contract_user_email'] = 'ollie.r@nexihub.uk';
        $_SESSION['contract_user_name'] = 'Oliver Reaney';
        
        try {
            if (DB_TYPE === 'sqlite') {
                $db_path = realpath(__DIR__ . "/database/nexihub.db");
                $db = new PDO("sqlite:" . $db_path);
            } else {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $db = new PDO($dsn, DB_USER, DB_PASS);
            }
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $db->prepare("
                SELECT ct.*,
                       sc.id as contract_record_id,
                       sc.is_signed,
                       sc.signed_at, sc.signature_data,
                       sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
                       sc.is_under_17, sc.guardian_full_name, sc.guardian_email,
                       sc.guardian_signature_data, sc.signed_timestamp,
                       sp.shareholder_percentage, sp.is_shareholder
                FROM contract_templates ct
                INNER JOIN staff_contracts sc ON ct.id = sc.template_id 
                LEFT JOIN staff_profiles sp ON sc.staff_id = sp.id
                WHERE sc.staff_id = ?
                ORDER BY ct.name, sc.id DESC
            ");
            $stmt->execute([1]);
            $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $contracts = [];
            echo "console.error('Database error: " . addslashes($e->getMessage()) . "');";
        }
        ?>
        
        const contracts = <?php echo json_encode($contracts); ?>;
        
        console.log('=== FULL CONTRACT DEBUG ===');
        console.log('Raw contracts data:', contracts);
        console.log('Number of contracts:', contracts.length);
        
        const output = document.getElementById('output');
        let html = '<h2>Contracts Data</h2>';
        
        contracts.forEach((contract, index) => {
            html += `<div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
                <h3>Contract ${index}</h3>
                <p><strong>Name:</strong> ${contract.name}</p>
                <p><strong>Contract Record ID:</strong> ${contract.contract_record_id}</p>
                <p><strong>Is Signed:</strong> ${contract.is_signed} (Type: ${typeof contract.is_signed})</p>
                <p><strong>Signed At:</strong> ${contract.signed_at || 'NULL'}</p>
                <p><strong>Has Signature:</strong> ${contract.signature_data ? 'YES' : 'NO'}</p>
                ${contract.is_signed ? 
                    `<button onclick="testViewContract(${contract.contract_record_id})">Test View Contract</button>
                     <button onclick="testDownloadPDF(${contract.contract_record_id})">Test Download PDF</button>` 
                    : '<p><em>Contract not signed</em></p>'
                }
            </div>`;
        });
        
        output.innerHTML = html;
        
        function testViewContract(contractId) {
            console.log('Testing viewContract with ID:', contractId);
            
            const contract = contracts.find(c => String(c.contract_record_id) === String(contractId));
            if (!contract) {
                console.error('Contract not found with ID:', contractId);
                alert('Contract not found');
                return;
            }
            
            const isSigned = (contract.is_signed == 1 || contract.is_signed === "1");
            if (!isSigned) {
                console.error('Contract not signed:', contractId, 'is_signed value:', contract.is_signed);
                alert('Contract not signed');
                return;
            }
            
            console.log('Contract found and signed:', contract);
            alert('Success! Contract found and signed.');
        }
        
        function testDownloadPDF(contractId) {
            console.log('Testing PDF download with ID:', contractId);
            const staffId = <?php echo json_encode($_SESSION['contract_staff_id'] ?? 0); ?>;
            const url = `download-pdf.php?contract_id=${contractId}&staff_id=${staffId}`;
            console.log('Would open URL:', url);
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
