<?php
/**
 * Blog Comment API
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
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
$name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
$email = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : '';
$website = isset($_POST['website']) ? sanitize($_POST['website']) : '';
$comment = isset($_POST['comment']) ? sanitize($_POST['comment']) : '';
$honeypot = isset($_POST['website_hidden']) ? trim($_POST['website_hidden']) : ''; // Honeypot
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Validation
if (empty($post_id)) {
    $response['message'] = 'Invalid post';
    echo json_encode($response);
    exit;
}

// Verify post exists
$stmt = $conn->prepare("SELECT id FROM blog_posts WHERE id = ? AND status = 1");
$stmt->execute([$post_id]);
if (!$stmt->fetch()) {
    $response['message'] = 'Post not found';
    echo json_encode($response);
    exit;
}

if (empty($name) || empty($email) || empty($comment)) {
    $response['message'] = 'Please fill in all required fields';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please enter a valid email address';
    echo json_encode($response);
    exit;
}

if (strlen($comment) < 10) {
    $response['message'] = 'Comment is too short (minimum 10 characters)';
    echo json_encode($response);
    exit;
}

if (strlen($comment) > 2000) {
    $response['message'] = 'Comment is too long (maximum 2000 characters)';
    echo json_encode($response);
    exit;
}

// Spam protection: Honeypot
if (!empty($honeypot)) {
    $response['success'] = true;
    $response['message'] = 'Thank you for your comment!';
    echo json_encode($response);
    exit;
}

// Spam protection: Rate limiting by IP
$stmt = $conn->prepare("SELECT COUNT(*) FROM blog_comments WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
$stmt->execute([$ip_address]);
$attempts = $stmt->fetchColumn();

if ($attempts >= 10) {
    $response['message'] = 'Too many comments. Please try again later.';
    echo json_encode($response);
    exit;
}

// Insert comment (pending approval)
try {
    $stmt = $conn->prepare("INSERT INTO blog_comments (post_id, parent_id, name, email, website, comment, ip_address, user_agent, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$post_id, $parent_id, $name, $email, $website ?: null, $comment, $ip_address, $user_agent]);
    
    // Update comments count (will be recalculated on approval)
    $stmt = $conn->prepare("UPDATE blog_posts SET comments_count = comments_count + 1 WHERE id = ?");
    $stmt->execute([$post_id]);
    
    // Notify admin (optional)
    $to = SITE_EMAIL;
    $subject = 'New Blog Comment Awaiting Approval';
    $message = "A new comment has been submitted on blog post ID: $post_id\n\n";
    $message .= "Name: $name\n";
    $message .= "Email: $email\n";
    $message .= "Comment: $comment\n\n";
    $message .= "Please review and approve in the admin panel.";
    
    $headers = "From: " . SITE_EMAIL . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    @mail($to, $subject, $message, $headers);
    
    $response['success'] = true;
    $response['message'] = 'Thank you for your comment! It will be reviewed before being published.';
} catch (PDOException $e) {
    error_log("Blog comment error: " . $e->getMessage());
    $response['message'] = 'Sorry, there was an error. Please try again later.';
}

echo json_encode($response);

