<?php
/**
 * Update Admin Credentials
 * This script will update the admin password to "Temp_Pass123"
 * Run this once, then delete this file for security
 */

require_once __DIR__ . '/../config/config.php';

// Security: Only allow this to run if a secret key is provided
$secret_key = $_GET['key'] ?? '';
$allowed_key = 'update2024'; // Change this to something random, then delete this file after use

if ($secret_key !== $allowed_key) {
    die('Access denied. Provide the correct key parameter: ?key=update2024');
}

$db = new Database();
$conn = $db->getConnection();

// New credentials
$new_username = 'teelake'; // Change username to teelake
$new_password = 'Temp_Pass123';
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Update Admin Credentials</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>Update Admin Credentials</h1>";

// Check current admin user
$current_user = $conn->query("SELECT * FROM admin_users WHERE username = 'admin'")->fetch();

if (!$current_user) {
    echo "<div class='error'>❌ Admin user not found!</div>";
    echo "</body></html>";
    exit;
}

echo "<div class='info'>
    <h3>Current Admin User:</h3>
    <p><strong>ID:</strong> {$current_user['id']}</p>
    <p><strong>Username:</strong> {$current_user['username']}</p>
    <p><strong>Email:</strong> {$current_user['email']}</p>
</div>";

// Update credentials
try {
    // Update username and password
    $stmt = $conn->prepare("UPDATE admin_users SET username = ?, password = ? WHERE username = 'admin'");
    $result = $stmt->execute([$new_username, $password_hash]);
    
    if ($result) {
        echo "<div class='success'>
            <h3>✅ Credentials Updated Successfully!</h3>
            <p><strong>New Username:</strong> {$new_username}</p>
            <p><strong>New Password:</strong> {$new_password}</p>
            <p><strong>Password Hash:</strong> <code>" . htmlspecialchars($password_hash) . "</code></p>
        </div>";
        
        // Verify the update
        $updated_user = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
        $updated_user->execute([$new_username]);
        $user = $updated_user->fetch();
        
        if ($user) {
            echo "<div class='info'>
                <h3>Verification:</h3>
                <p>✅ User found with new username: <strong>{$user['username']}</strong></p>
                <p>✅ Password hash updated successfully</p>
            </div>";
            
            // Test password verification
            if (password_verify($new_password, $user['password'])) {
                echo "<div class='success'>✅ Password verification test: PASSED</div>";
            } else {
                echo "<div class='error'>❌ Password verification test: FAILED</div>";
            }
        }
        
        echo "<div class='info'>
            <h3>Next Steps:</h3>
            <ol>
                <li>Try logging in with the new credentials</li>
                <li><strong>⚠️ IMPORTANT:</strong> Delete this file (<code>admin/update-credentials.php</code>) immediately for security!</li>
                <li>Change the password again after first login for better security</li>
            </ol>
        </div>";
        
        echo "<p><a href='/admin/login' style='display: inline-block; background: #1D4ED8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;'>Go to Login Page</a></p>";
        
    } else {
        echo "<div class='error'>❌ Failed to update credentials.</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Show all admin users
echo "<hr>";
echo "<h2>All Admin Users:</h2>";
$users = $conn->query("SELECT id, username, email, created_at FROM admin_users ORDER BY id")->fetchAll();
echo "<table>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td><strong>" . htmlspecialchars($user['username']) . "</strong></td>";
    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
    echo "<td>" . $user['created_at'] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "</body></html>";
?>

