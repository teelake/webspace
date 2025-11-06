<?php
/**
 * Clear Login Attempts
 * This script clears all failed login attempts from the database
 * Useful if you're locked out during development
 */

require_once __DIR__ . '/../config/config.php';

// Security: Only allow this to run if a secret key is provided
$secret_key = $_GET['key'] ?? '';
$allowed_key = 'clear2024'; // Change this, then delete this file after use

if ($secret_key !== $allowed_key) {
    die('Access denied. Provide the correct key parameter: ?key=clear2024');
}

$db = new Database();
$conn = $db->getConnection();

try {
    // Clear all failed login attempts
    $stmt = $conn->prepare("DELETE FROM login_attempts WHERE success = 0");
    $result = $stmt->execute();
    
    $deleted = $stmt->rowCount();
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Clear Login Attempts</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <h1>Clear Login Attempts</h1>";
    
    if ($result) {
        echo "<div class='success'>
            <h3>✅ Success!</h3>
            <p>Cleared <strong>{$deleted}</strong> failed login attempt(s) from the database.</p>
            <p>You can now try logging in again.</p>
        </div>";
    } else {
        echo "<div class='info'>
            <p>No failed login attempts found to clear.</p>
        </div>";
    }
    
    // Show remaining attempts
    $remaining = $conn->query("SELECT COUNT(*) FROM login_attempts WHERE success = 0")->fetchColumn();
    echo "<div class='info'>
        <p>Remaining failed attempts in database: <strong>{$remaining}</strong></p>
    </div>";
    
    echo "<p><a href='/admin/login' style='display: inline-block; background: #1D4ED8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;'>Go to Login Page</a></p>";
    
    echo "<div class='info'>
        <p><strong>⚠️ IMPORTANT:</strong> Delete this file (<code>admin/clear-login-attempts.php</code>) after use for security!</p>
    </div>";
    
    echo "</body></html>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px;'>
        <h3>❌ Error</h3>
        <p>" . htmlspecialchars($e->getMessage()) . "</p>
    </div>";
}
?>

