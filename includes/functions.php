<?php
/**
 * Helper Functions
 * Webspace Innovation Hub Limited
 */

/**
 * Sanitize input data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Generate slug from string
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Format date
 */
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Truncate text
 */
function truncate($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Get admin URL path
 */
function getAdminPath() {
    return defined('ADMIN_PATH') ? ADMIN_PATH : 'admin';
}

/**
 * Get admin URL (with clean URLs support - removes .php extension)
 */
function adminUrl($path = '') {
    $admin_path = getAdminPath();
    // Remove .php extension for clean URLs
    $path = str_replace('.php', '', $path);
    return '/' . $admin_path . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . adminUrl('login.php'));
        exit;
    }
}

/**
 * Check and handle login attempts (rate limiting)
 */
function checkLoginAttempts($username) {
    global $conn;
    if (!isset($conn)) {
        $db = new Database();
        $conn = $db->getConnection();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $max_attempts = defined('MAX_LOGIN_ATTEMPTS') ? MAX_LOGIN_ATTEMPTS : 5;
    $lockout_time = defined('LOGIN_LOCKOUT_TIME') ? LOGIN_LOCKOUT_TIME : 900;
    
    // Check recent failed attempts
    $stmt = $conn->prepare("SELECT COUNT(*) as attempts, MAX(attempted_at) as last_attempt 
                            FROM login_attempts 
                            WHERE (username = ? OR ip_address = ?) 
                            AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND) 
                            AND success = 0");
    $stmt->execute([$username, $ip, $lockout_time]);
    $result = $stmt->fetch();
    
    if ($result && $result['attempts'] >= $max_attempts) {
        return [
            'locked' => true,
            'message' => 'Too many failed login attempts. Please try again in ' . round($lockout_time / 60) . ' minutes.'
        ];
    }
    
    return ['locked' => false];
}

/**
 * Log login attempt
 */
function logLoginAttempt($username, $success, $ip = null) {
    global $conn;
    if (!isset($conn)) {
        $db = new Database();
        $conn = $db->getConnection();
    }
    
    if ($ip === null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    $stmt = $conn->prepare("INSERT INTO login_attempts (username, ip_address, success, attempted_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$username, $ip, $success ? 1 : 0]);
}

/**
 * Upload image
 */
function uploadImage($file, $folder = 'general') {
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large'];
    }
    
    $folderPath = UPLOAD_PATH . $folder . '/';
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0755, true);
    }
    
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $filepath = $folderPath . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true,
            'filename' => $filename,
            'path' => $folder . '/' . $filename,
            'url' => UPLOAD_URL . $folder . '/' . $filename
        ];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

/**
 * Delete image
 */
function deleteImage($path) {
    $filepath = UPLOAD_PATH . $path;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Get setting value
 */
function getSetting($key, $default = '') {
    global $conn;
    if (!isset($conn)) {
        $db = new Database();
        $conn = $db->getConnection();
    }
    
    $stmt = $conn->prepare("SELECT value FROM settings WHERE `key` = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['value'] : $default;
}

/**
 * Set setting value
 */
function setSetting($key, $value) {
    global $conn;
    if (!isset($conn)) {
        $db = new Database();
        $conn = $db->getConnection();
    }
    
    $stmt = $conn->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
    return $stmt->execute([$key, $value, $value]);
}

/**
 * Check for spam/bot submission (honeypot + rate limiting)
 */
function isSpamSubmission($honeypot = '', $ip = '') {
    // Honeypot check - if filled, it's a bot
    if (!empty($honeypot)) {
        return true;
    }
    
    // Rate limiting - check if same email submitted multiple times in last hour
    $email = $_POST['email'] ?? '';
    if (empty($email)) {
        return false;
    }
    
    global $conn;
    if (!isset($conn)) {
        $db = new Database();
        $conn = $db->getConnection();
    }
    
    // Check submissions in last hour from same email
    $stmt = $conn->prepare("SELECT COUNT(*) FROM contact_messages WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) AND email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    
    // Allow max 3 submissions per hour per email
    if ($count >= 3) {
        return true;
    }
    
    return false;
}

/**
 * Verify form submission timing (human check)
 */
function isValidFormTiming($minSeconds = 3) {
    if (!isset($_SESSION['form_start_time'])) {
        $_SESSION['form_start_time'] = time();
        return false;
    }
    
    $elapsed = time() - $_SESSION['form_start_time'];
    
    // Forms filled too quickly are likely bots
    if ($elapsed < $minSeconds) {
        return false;
    }
    
    unset($_SESSION['form_start_time']);
    return true;
}

/**
 * Calculate reading time for blog post
 */
function calculateReadingTime($content) {
    // Average reading speed: 200 words per minute
    $words = str_word_count(strip_tags($content));
    $minutes = ceil($words / 200);
    return max(1, $minutes); // Minimum 1 minute
}

/**
 * Get blog post comments
 */
function getBlogComments($post_id, $parent_id = null, $status = 'approved') {
    global $conn;
    if (!isset($conn)) {
        $db = new Database();
        $conn = $db->getConnection();
    }
    
    $sql = "SELECT * FROM blog_comments WHERE post_id = ? AND status = ?";
    $params = [$post_id, $status];
    
    if ($parent_id === null) {
        $sql .= " AND parent_id IS NULL";
    } else {
        $sql .= " AND parent_id = ?";
        $params[] = $parent_id;
    }
    
    $sql .= " ORDER BY created_at ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Check if user has liked a post (by IP)
 */
function hasLikedPost($post_id, $ip_address) {
    global $conn;
    if (!isset($conn)) {
        $db = new Database();
        $conn = $db->getConnection();
    }
    
    $stmt = $conn->prepare("SELECT id FROM blog_likes WHERE post_id = ? AND ip_address = ?");
    $stmt->execute([$post_id, $ip_address]);
    return $stmt->fetch() ? true : false;
}

