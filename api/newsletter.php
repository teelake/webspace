<?php
/**
 * Newsletter Subscription API
 * Webspace Innovation Hub Limited
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

$db = new Database();
$conn = $db->getConnection();

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Get and sanitize input
$email = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : '';
$honeypot = isset($_POST['website']) ? trim($_POST['website']) : ''; // Honeypot field
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Validation
if (empty($email)) {
    $response['message'] = 'Email is required';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please enter a valid email address';
    echo json_encode($response);
    exit;
}

// Spam protection: Honeypot check
if (!empty($honeypot)) {
    // Bot detected - silently fail
    $response['success'] = true;
    $response['message'] = 'Thank you for subscribing!';
    echo json_encode($response);
    exit;
}

// Spam protection: Rate limiting by IP
$stmt = $conn->prepare("SELECT COUNT(*) FROM newsletter_attempts WHERE ip_address = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
$stmt->execute([$ip_address]);
$attempts = $stmt->fetchColumn();

if ($attempts >= 5) {
    $response['message'] = 'Too many requests. Please try again later.';
    echo json_encode($response);
    exit;
}

// Spam protection: Rate limiting by email
$stmt = $conn->prepare("SELECT COUNT(*) FROM newsletter_attempts WHERE email = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
$stmt->execute([$email]);
$email_attempts = $stmt->fetchColumn();

if ($email_attempts >= 3) {
    $response['message'] = 'Too many requests. Please try again later.';
    echo json_encode($response);
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id, status FROM newsletter_subscriptions WHERE email = ?");
$stmt->execute([$email]);
$existing = $stmt->fetch();

if ($existing) {
    if ($existing['status'] === 'active') {
        $response['message'] = 'This email is already subscribed to our newsletter.';
        echo json_encode($response);
        exit;
    } else {
        // Re-subscribe
        $stmt = $conn->prepare("UPDATE newsletter_subscriptions SET status = 'active', subscribed_at = NOW(), unsubscribed_at = NULL, ip_address = ?, user_agent = ? WHERE email = ?");
        $stmt->execute([$ip_address, $user_agent, $email]);
        
        // Log attempt
        $stmt = $conn->prepare("INSERT INTO newsletter_attempts (email, ip_address) VALUES (?, ?)");
        $stmt->execute([$email, $ip_address]);
        
        $response['success'] = true;
        $response['message'] = 'Thank you for re-subscribing!';
        echo json_encode($response);
        exit;
    }
}

// Insert new subscription
try {
    $stmt = $conn->prepare("INSERT INTO newsletter_subscriptions (email, ip_address, user_agent) VALUES (?, ?, ?)");
    $stmt->execute([$email, $ip_address, $user_agent]);
    
    // Log attempt
    $stmt = $conn->prepare("INSERT INTO newsletter_attempts (email, ip_address) VALUES (?, ?)");
    $stmt->execute([$email, $ip_address]);
    
    // Send confirmation email (optional)
    $to = $email;
    $subject = 'Welcome to ' . SITE_NAME . ' Newsletter';
    $message = "Thank you for subscribing to our newsletter!\n\n";
    $message .= "You'll receive the latest updates, insights, and tips from " . SITE_NAME . ".\n\n";
    $message .= "If you didn't subscribe, please ignore this email.\n\n";
    $message .= "Best regards,\n" . SITE_NAME;
    
    $headers = "From: " . SITE_EMAIL . "\r\n";
    $headers .= "Reply-To: " . SITE_EMAIL . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    @mail($to, $subject, $message, $headers);
    
    $response['success'] = true;
    $response['message'] = 'Thank you for subscribing! Please check your email for confirmation.';
} catch (PDOException $e) {
    error_log("Newsletter subscription error: " . $e->getMessage());
    $response['message'] = 'Sorry, there was an error. Please try again later.';
}

echo json_encode($response);

