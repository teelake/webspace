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
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
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

