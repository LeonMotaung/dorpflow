<?php
/**
 * DorpFlow ERP - Security and Authorization Middleware
 */

class Auth {
    
    /**
     * Check if a user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get the active user's details
     */
    public static function user() {
        if (!self::isLoggedIn()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
            'role_id' => $_SESSION['user_role_id']
        ];
    }

    /**
     * Enforce a specific role or group of roles
     */
    public static function requireRole($allowedRoles = []) {
        if (!self::isLoggedIn()) {
            // Store redirect URL
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: " . APP_URL . "/public/index.php/login");
            exit;
        }

        $userRole = $_SESSION['user_role'];
        if (!in_array($userRole, $allowedRoles) && !in_array('Any', $allowedRoles)) {
            http_response_code(403);
            die("Unauthorized Access: Your account designation [$userRole] does not have permission to view this resource.");
        }
    }

    /**
     * Verify if the user has specific permission level
     */
    public static function check($allowedRoles = []) {
        if (!self::isLoggedIn()) return false;
        return in_array($_SESSION['user_role'], $allowedRoles);
    }
}
