<?php
/**
 * Password Hash Generator for Temp_Pass123
 * Use this to generate the password hash
 */

$password = 'Temp_Pass123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Password Hash Generator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .box { background: #f4f4f4; padding: 20px; border-radius: 5px; margin: 20px 0; }
        code { background: #fff; padding: 10px; display: block; border-radius: 3px; margin: 10px 0; word-break: break-all; }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Password Hash Generator</h1>
    
    <div class='box'>
        <h2>Password: Temp_Pass123</h2>
        <p><strong>Generated Hash:</strong></p>
        <code>{$hash}</code>
    </div>";

// Verify it works
if (password_verify($password, $hash)) {
    echo "<p class='success'>✅ Hash verification successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Hash verification failed!</p>";
}

echo "<div class='box'>
    <h2>SQL Update Command:</h2>
    <code>UPDATE admin_users SET password = '{$hash}' WHERE username = 'admin';</code>
</div>";

echo "<div class='box'>
    <h2>SQL Update Command (Change Username to teelake):</h2>
    <code>UPDATE admin_users SET username = 'teelake', password = '{$hash}' WHERE username = 'admin';</code>
</div>";

echo "</body></html>";
?>

