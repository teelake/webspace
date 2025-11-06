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
define('SITE_URL', 'http://localhost/webspace');
define('SITE_EMAIL', 'hello@webspace.ng');
define('SITE_PHONE_1', '09133905681');
define('SITE_PHONE_2', '08137449310');
define('REGISTRATION_NO', '8919272');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('ASSETS_URL', SITE_URL . '/assets/');
define('LOGO_URL', SITE_URL . '/assets/img/logo/');

// Admin Configuration
define('ADMIN_EMAIL', 'admin@webspace.ng');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Timezone
date_default_timezone_set('Africa/Lagos');

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database
require_once ROOT_PATH . '/config/database.php';

// Helper functions
require_once ROOT_PATH . '/includes/functions.php';

