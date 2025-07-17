<?php
require_once __DIR__ . '/../config/config.php';

echo "<h2>Login Test & Session Debug</h2>";

// If form submitted, process login
if ($_POST['email'] ?? false) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    echo "<h3>Processing Login</h3>";
    echo "<p>Email: $email</p>";
    
    try {
        // Database connection
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $db = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
            echo "<p>✅ Connected to MySQL</p>";
        } else {
            $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<p>✅ Connected to SQLite</p>";
        }
        
        // Check user credentials
        $stmt = $db->prepare("SELECT * FROM contract_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            echo "<p>✅ Login successful!</p>";
            
            // Set session variables
            $_SESSION['contract_user_id'] = $user['id'];
            $_SESSION['contract_user_email'] = $user['email'];
            
            // Find staff profile (use first one for testing)
            $stmt = $db->query("SELECT id FROM staff_profiles LIMIT 1");
            $staff = $stmt->fetch();
            if ($staff) {
                $_SESSION['contract_staff_id'] = $staff['id'];
                echo "<p>✅ Staff ID set: " . $staff['id'] . "</p>";
            }
            
            echo "<p>Session variables:</p>";
            echo "<pre>" . print_r($_SESSION, true) . "</pre>";
            
            echo "<p><strong><a href='dashboard.php'>→ Go to Dashboard</a></strong></p>";
        } else {
            echo "<p>❌ Login failed - invalid credentials</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
    }
}

// Show current session status
echo "<h3>Current Session Status</h3>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session data:</p>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// Show login form
?>
<h3>Login Form</h3>
<form method="POST">
    <p>
        <label>Email:</label><br>
        <input type="email" name="email" value="contract@nexihub.uk" required style="width: 300px; padding: 5px;">
    </p>
    <p>
        <label>Password:</label><br>
        <input type="password" name="password" value="test1212" required style="width: 300px; padding: 5px;">
    </p>
    <p>
        <button type="submit" style="padding: 10px 20px; background: #e64f21; color: white; border: none; border-radius: 5px;">Login</button>
    </p>
</form>

<h3>Quick Links</h3>
<ul>
    <li><a href="index.php">Contract Portal Login Page</a></li>
    <li><a href="dashboard.php">Dashboard (requires login)</a></li>
    <li><a href="test-db.php">Database Connection Test</a></li>
</ul>
