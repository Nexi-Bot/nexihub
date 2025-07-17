<?php
require_once __DIR__ . '/../config/config.php';

echo "<h2>Contract Portal Login Test</h2>";

// Test if we can connect to the database in this context
echo "<h3>1. Database Connection Test</h3>";
try {
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
        echo "<p>✅ Connected to MySQL database</p>";
    } else {
        $db_path = __DIR__ . "/../database/nexihub.db";
        echo "<p>Attempting to connect to SQLite at: $db_path</p>";
        echo "<p>File exists: " . (file_exists($db_path) ? "YES" : "NO") . "</p>";
        
        $db = new PDO("sqlite:" . $db_path);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<p>✅ Connected to SQLite database</p>";
    }
} catch (PDOException $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

// Test contract user login
echo "<h3>2. Contract User Test</h3>";
try {
    $stmt = $db->prepare("SELECT * FROM contract_users WHERE email = ?");
    $stmt->execute(['contract@nexihub.uk']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>✅ Contract user found: " . $user['email'] . "</p>";
        echo "<p>Password hash in DB: " . substr($user['password'], 0, 20) . "...</p>";
        
        // Test password verification
        if (password_verify('test1212', $user['password'])) {
            echo "<p>✅ Password verification successful</p>";
        } else {
            echo "<p>❌ Password verification failed</p>";
        }
    } else {
        echo "<p>❌ Contract user not found</p>";
    }
} catch (PDOException $e) {
    echo "<p>❌ Error checking contract users: " . $e->getMessage() . "</p>";
}

// Test staff profiles for contract signing
echo "<h3>3. Staff Profiles Test</h3>";
try {
    $stmt = $db->query("SELECT COUNT(*) as count FROM staff_profiles");
    $result = $stmt->fetch();
    echo "<p>Staff profiles in database: " . $result['count'] . "</p>";
    
    // Check if there's a staff profile for contract signing
    $stmt = $db->query("SELECT * FROM staff_profiles LIMIT 1");
    $staff = $stmt->fetch();
    if ($staff) {
        echo "<p>Sample staff member: " . $staff['full_name'] . " (ID: " . $staff['id'] . ")</p>";
    }
} catch (PDOException $e) {
    echo "<p>❌ Error checking staff profiles: " . $e->getMessage() . "</p>";
}

// Test contract templates
echo "<h3>4. Contract Templates Test</h3>";
try {
    $stmt = $db->query("SELECT * FROM contract_templates");
    $templates = $stmt->fetchAll();
    echo "<p>Contract templates found: " . count($templates) . "</p>";
    foreach ($templates as $template) {
        echo "<li>" . $template['name'] . " (" . $template['type'] . ")</li>";
    }
} catch (PDOException $e) {
    echo "<p>❌ Error checking contract templates: " . $e->getMessage() . "</p>";
}

// Simulate login
echo "<h3>5. Login Simulation</h3>";
if (isset($user) && $user) {
    // Simulate successful login
    $_SESSION['contract_user_id'] = $user['id'];
    $_SESSION['contract_user_email'] = $user['email'];
    $_SESSION['contract_staff_id'] = 1; // Use first staff member
    
    echo "<p>✅ Session variables set:</p>";
    echo "<ul>";
    echo "<li>User ID: " . $_SESSION['contract_user_id'] . "</li>";
    echo "<li>Email: " . $_SESSION['contract_user_email'] . "</li>";
    echo "<li>Staff ID: " . $_SESSION['contract_staff_id'] . "</li>";
    echo "</ul>";
    
    echo "<p><a href='dashboard.php'>→ Go to Dashboard</a></p>";
} else {
    echo "<p>❌ Cannot simulate login - user not found</p>";
}
?>
