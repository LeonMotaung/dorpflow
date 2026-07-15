<?php
/**
 * DorpFlow ERP - Main System Settings and Configuration
 */

// Define absolute path roots
define('ROOT_PATH', dirname(__DIR__));
define('APP_URL', 'http://localhost/dorpflow');

// Global Platform Meta
define('PLATFORM_NAME', 'DorpFlow');
define('CSD_VENDOR_NUMBER', 'MAAA0982319');
define('SUPPORT_EMAIL', 'support@dorpflow.com');
define('SALES_EMAIL', 'sales@dorpflow.com');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', 3600); // 1 hour session timeout

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Get active resolved tenant name
 * Resolves by subdomain in production or fallback parameter "?tenant=X" in development
 */
function getActiveTenant() {
    // 1. Check development query parameter overrides
    if (isset($_GET['tenant']) && !empty($_GET['tenant'])) {
        $_SESSION['tenant'] = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['tenant']);
        return $_SESSION['tenant'];
    }
    
    // 2. Check active session
    if (isset($_SESSION['tenant']) && !empty($_SESSION['tenant'])) {
        return $_SESSION['tenant'];
    }
    
    // 3. Resolve by subdomain
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $parts = explode('.', $host);
    if (count($parts) > 2 && $parts[0] !== 'www' && $parts[0] !== 'localhost') {
        return preg_replace('/[^a-zA-Z0-9_-]/', '', $parts[0]);
    }
    
    // Default fallback to core (Super Admin context)
    return 'core';
}
