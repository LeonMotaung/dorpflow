<?php
/**
 * DorpFlow ERP - Base Controller class
 */

abstract class Controller {
    
    /**
     * Render a view file with layout wrapping
     */
    protected function render($viewPath, $data = []) {
        // Extract data variables to local scope
        extract($data);
        
        // Define paths
        $headerPath = ROOT_PATH . '/views/layout/header.php';
        $footerPath = ROOT_PATH . '/views/layout/footer.php';
        $viewFile = ROOT_PATH . '/views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            if (file_exists($headerPath)) require $headerPath;
            require $viewFile;
            if (file_exists($footerPath)) require $footerPath;
        } else {
            die("View template view [$viewPath] not found in views folder.");
        }
    }

    /**
     * Return JSON formatted responses
     */
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a specific platform URL
     */
    protected function redirect($url) {
        header("Location: " . APP_URL . "/public/index.php" . $url);
        exit;
    }

    /**
     * Generate CSRF token and store it in session
     */
    protected function getCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token from request
     */
    protected function validateCsrf() {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (empty($token) || $token !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            die("CSRF Token Verification Failed. Secure Request Rejected.");
        }
    }

    /**
     * Clean and sanitize text inputs
     */
    protected function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
