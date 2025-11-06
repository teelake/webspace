<?php
/**
 * Password Reset Utility
 * This script will reset the admin password to "admin123"
 * Run this once, then delete this file for security
 */

require_once __DIR__ . '/../config/config.php';

// Security: Only allow this to run if a secret key is provided
$secret_key = $_GET['key'] ?? '';
$allowed_key = 'reset2024'; // Change this to something random, then delete this file after use

if ($secret_key !== $allowed_key) {
    die('Access denied. Provide the correct key parameter.');
}

$db = new Database();
$conn = $db->getConnection();

// Generate new password hash for "admin123"
$new_password = 'admin123';
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Update admin password
try {
    $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
    $result = $stmt->execute([$password_hash]);
    
    if ($result) {
        echo "<h1>Password Reset Successful!</h1>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>New Password:</strong> admin123</p>";
        echo "<p style='color: green;'>✅ Password has been updated successfully!</p>";
        echo "<p><a href='/admin/login'>Go to Login Page</a></p>";
        echo "<hr>";
        echo "<p style='color: red;'><strong>⚠️ IMPORTANT:</strong> Delete this file (reset-password.php) immediately for security!</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to update password.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Also show current admin users
echo "<hr>";
echo "<h2>Current Admin Users:</h2>";
$users = $conn->query("SELECT id, username, email, created_at FROM admin_users")->fetchAll();
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
    echo "<td>" . $user['created_at'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>

