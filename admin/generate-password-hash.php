<?php
/**
 * Password Hash Generator
 * Use this to generate a new password hash
 */

// Generate hash for "admin123"
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h1>Password Hash Generator</h1>";
echo "<p><strong>Password:</strong> " . htmlspecialchars($password) . "</p>";
echo "<p><strong>Hash:</strong> <code>" . htmlspecialchars($hash) . "</code></p>";

// Verify it works
if (password_verify($password, $hash)) {
    echo "<p style='color: green;'>✅ Hash verification successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Hash verification failed!</p>";
}

echo "<hr>";
echo "<h2>SQL Update Command:</h2>";
echo "<pre>";
echo "UPDATE admin_users SET password = '" . htmlspecialchars($hash) . "' WHERE username = 'admin';";
echo "</pre>";

echo "<hr>";
echo "<h2>Test Different Passwords:</h2>";
echo "<form method='GET'>";
echo "<input type='text' name='pwd' placeholder='Enter password' value='" . htmlspecialchars($_GET['pwd'] ?? '') . "'>";
echo "<button type='submit'>Generate Hash</button>";
echo "</form>";

if (isset($_GET['pwd']) && !empty($_GET['pwd'])) {
    $test_pwd = $_GET['pwd'];
    $test_hash = password_hash($test_pwd, PASSWORD_DEFAULT);
    echo "<p><strong>Password:</strong> " . htmlspecialchars($test_pwd) . "</p>";
    echo "<p><strong>Hash:</strong> <code>" . htmlspecialchars($test_hash) . "</code></p>";
    echo "<p><strong>SQL:</strong> <code>UPDATE admin_users SET password = '" . htmlspecialchars($test_hash) . "' WHERE username = 'admin';</code></p>";
}
?>

