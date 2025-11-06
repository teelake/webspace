<?php
/**
 * Main Configuration File
 * Webspace Innovation Hub Limited
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site Configuration
define('SITE_NAME', 'Webspace Innovation Hub Limited');
define('SITE_TAGLINE', 'Your Complete Digital Partner');
// Production URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
define('SITE_URL', $protocol . 'www.webspace.ng');
define('SITE_EMAIL', 'hello@webspace.ng');
define('SITE_PHONE_1', '09133905681');
define('SITE_PHONE_2', '08137449310');
define('REGISTRATION_NO', '8919272');

// Social Media Links
define('SOCIAL_FACEBOOK', 'https://facebook.com/webspace.ng');
define('SOCIAL_TWITTER', 'https://twitter.com/webspace_ng');
define('SOCIAL_LINKEDIN', 'https://linkedin.com/company/webspace-ng');
define('SOCIAL_INSTAGRAM', 'https://instagram.com/webspace.ng');
define('SOCIAL_YOUTUBE', 'https://youtube.com/@webspace.ng');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('ASSETS_URL', SITE_URL . '/assets/');
define('LOGO_URL', SITE_URL . '/assets/img/logo/');

// Admin Configuration
// IMPORTANT: Change this to a random, non-obvious folder name for security
// Example: 'cms-panel-2024', 'dashboard-x7k9', 'manage-system', etc.
// NOTE: This must match the actual folder name
define('ADMIN_PATH', 'admin'); // Change this to your preferred obscure name (must match folder name)
define('ADMIN_EMAIL', 'admin@webspace.ng');
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5); // Max failed login attempts
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes lockout after max attempts

// Timezone
date_default_timezone_set('Africa/Lagos');

// Error Reporting (PRODUCTION: errors logged but not displayed)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Hide errors from users
ini_set('log_errors', 1); // Log errors to server log

// Create logs directory if it doesn't exist
$logs_dir = ROOT_PATH . '/logs';
if (!is_dir($logs_dir)) {
    @mkdir($logs_dir, 0755, true);
}
ini_set('error_log', $logs_dir . '/php-errors.log');

// Include database
require_once ROOT_PATH . '/config/database.php';

// Helper functions
require_once ROOT_PATH . '/includes/functions.php';

