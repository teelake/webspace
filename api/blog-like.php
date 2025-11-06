<?php
/**
 * Blog Like API
 * Webspace Innovation Hub Limited
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

$db = new Database();
$conn = $db->getConnection();

$response = ['success' => false, 'message' => '', 'liked' => false, 'likes_count' => 0];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Get and sanitize input
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : 'toggle'; // 'like' or 'unlike' or 'toggle'
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Validation
if (empty($post_id)) {
    $response['message'] = 'Invalid post';
    echo json_encode($response);
    exit;
}

// Verify post exists
$stmt = $conn->prepare("SELECT id, likes_count FROM blog_posts WHERE id = ? AND status = 1");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    $response['message'] = 'Post not found';
    echo json_encode($response);
    exit;
}

// Check if already liked
$stmt = $conn->prepare("SELECT id FROM blog_likes WHERE post_id = ? AND ip_address = ?");
$stmt->execute([$post_id, $ip_address]);
$existing_like = $stmt->fetch();

$liked = $existing_like ? true : false;

try {
    if ($action === 'unlike' || ($action === 'toggle' && $liked)) {
        // Unlike
        if ($liked) {
            $stmt = $conn->prepare("DELETE FROM blog_likes WHERE post_id = ? AND ip_address = ?");
            $stmt->execute([$post_id, $ip_address]);
            
            // Update likes count
            $new_count = max(0, $post['likes_count'] - 1);
            $stmt = $conn->prepare("UPDATE blog_posts SET likes_count = ? WHERE id = ?");
            $stmt->execute([$new_count, $post_id]);
            
            $response['success'] = true;
            $response['liked'] = false;
            $response['likes_count'] = $new_count;
            $response['message'] = 'Like removed';
        }
    } else {
        // Like
        if (!$liked) {
            $stmt = $conn->prepare("INSERT INTO blog_likes (post_id, ip_address, user_agent) VALUES (?, ?, ?)");
            $stmt->execute([$post_id, $ip_address, $user_agent]);
            
            // Update likes count
            $new_count = $post['likes_count'] + 1;
            $stmt = $conn->prepare("UPDATE blog_posts SET likes_count = ? WHERE id = ?");
            $stmt->execute([$new_count, $post_id]);
            
            $response['success'] = true;
            $response['liked'] = true;
            $response['likes_count'] = $new_count;
            $response['message'] = 'Post liked!';
        } else {
            $response['success'] = true;
            $response['liked'] = true;
            $response['likes_count'] = $post['likes_count'];
            $response['message'] = 'Already liked';
        }
    }
} catch (PDOException $e) {
    error_log("Blog like error: " . $e->getMessage());
    $response['message'] = 'Sorry, there was an error. Please try again later.';
}

echo json_encode($response);

