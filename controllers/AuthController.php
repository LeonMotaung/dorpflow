<?php
/**
 * DorpFlow ERP - Authentication Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/User.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class AuthController extends Controller {

    /**
     * Render Login View
     */
    public function showLogin() {
        if (Auth::isLoggedIn()) {
            $this->redirectDashboard();
        }
        $this->render('auth/login', [
            'title' => 'Login | DorpFlow',
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Render Registration View
     */
    public function showRegister() {
        if (Auth::isLoggedIn()) {
            $this->redirectDashboard();
        }
        
        $tenant = getActiveTenant();
        
        if ($tenant === 'core') {
            // Render municipality registration gateway (redirects to contact/demo booking)
            $this->render('auth/register_muni', [
                'title' => 'Register Municipality | DorpFlow'
            ]);
            return;
        }

        // Fetch Wards for the resident registration dropdown
        $db = Database::getConnection();
        $wards = $db->query("SELECT * FROM Wards ORDER BY ward_number ASC")->fetchAll();

        $this->render('auth/register_resident', [
            'title' => 'Resident Registration | DorpFlow',
            'wards' => $wards,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Resident User Registration POST
     */
    public function processRegister() {
        $this->validateCsrf();
        
        $tenant = getActiveTenant();
        if ($tenant === 'core') {
            die("Direct municipality registration must be completed through Sales.");
        }

        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $wardId = $_POST['ward_id'] ?? NULL;

        if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
            // Re-render with error
            $db = Database::getConnection();
            $wards = $db->query("SELECT * FROM Wards ORDER BY ward_number ASC")->fetchAll();
            $this->render('auth/register_resident', [
                'error' => 'All fields except Ward are required.',
                'wards' => $wards,
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        $db = Database::getConnection();
        
        // Check if email already registered
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $wards = $db->query("SELECT * FROM Wards ORDER BY ward_number ASC")->fetchAll();
            $this->render('auth/register_resident', [
                'error' => 'Email is already registered under this municipality.',
                'wards' => $wards,
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        // Fetch Resident role ID
        $stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Resident' LIMIT 1");
        $stmt->execute();
        $role = $stmt->fetch();
        $roleId = $role ? $role['id'] : 5; // Default fallback to 5

        // Insert new user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmtInsert = $db->prepare("
            INSERT INTO users (role_id, email, password_hash, full_name, phone)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmtInsert->execute([$roleId, $email, $passwordHash, $fullName, $phone]);
        $userId = $db->lastInsertId();

        // Audit Log
        $audit = new AuditLog();
        $audit->log($userId, "Resident registered successfully from IP {$_SERVER['REMOTE_ADDR']}");

        // Automatically log in
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $fullName;
        $_SESSION['user_role'] = 'Resident';
        $_SESSION['user_role_id'] = $roleId;

        $this->redirectDashboard();
    }

    /**
     * Render Forgot Password view
     */
    public function showForgotPassword() {
        $this->render('auth/forgot_password', [
            'title' => 'Forgot Password | DorpFlow',
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Forgot Password request POST
     */
    public function processForgotPassword() {
        $this->validateCsrf();
        $email = $_POST['email'] ?? '';
        $tenant = getActiveTenant();

        if (empty($email)) {
            $this->render('auth/forgot_password', [
                'error' => 'Email address is required.',
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        // Connect to appropriate database context
        $db = ($tenant === 'core') ? Database::getCoreConnection() : Database::getConnection();
        
        // Check if user exists
        if ($tenant === 'core') {
            if ($email === 'super@dorpflow.com') {
                $token = bin2hex(random_bytes(16));
                $_SESSION['mock_superadmin_reset_token'] = $token;
                
                $resetUrl = APP_URL . "/public/index.php/reset-password?token=$token&email=" . urlencode($email) . "&tenant=core";
                
                $this->render('auth/forgot_password', [
                    'success' => 'Reset instructions generated successfully.',
                    'reset_url' => $resetUrl,
                    'csrf_token' => $this->getCsrfToken()
                ]);
            } else {
                $this->render('auth/forgot_password', [
                    'error' => 'Email address not found.',
                    'csrf_token' => $this->getCsrfToken()
                ]);
            }
            return;
        }

        // Tenant user lookup
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(16));
            // Store token in remember_token column
            $stmtUpdate = $db->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmtUpdate->execute([$token, $user['id']]);

            $resetUrl = APP_URL . "/public/index.php/reset-password?token=$token&email=" . urlencode($email);
            if (isset($_GET['tenant'])) {
                $resetUrl .= "&tenant=" . urlencode($_GET['tenant']);
            }

            $this->render('auth/forgot_password', [
                'success' => 'Reset instructions generated successfully.',
                'reset_url' => $resetUrl,
                'csrf_token' => $this->getCsrfToken()
            ]);
        } else {
            $this->render('auth/forgot_password', [
                'error' => 'Email address not registered under this municipality.',
                'csrf_token' => $this->getCsrfToken()
            ]);
        }
    }

    /**
     * Render Password Reset input form
     */
    public function showResetPassword() {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';
        $tenant = getActiveTenant();

        if (empty($token) || empty($email)) {
            die("Invalid or expired password reset parameters.");
        }

        // Verify token
        if ($tenant === 'core') {
            $mockToken = $_SESSION['mock_superadmin_reset_token'] ?? '';
            if ($token !== $mockToken || $email !== 'super@dorpflow.com') {
                die("Invalid or expired password reset token.");
            }
        } else {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND remember_token = ? LIMIT 1");
            $stmt->execute([$email, $token]);
            if (!$stmt->fetch()) {
                die("Invalid or expired password reset token.");
            }
        }

        $this->render('auth/reset_password', [
            'title' => 'Reset Password | DorpFlow',
            'token' => $token,
            'email' => $email,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Password Reset input form POST
     */
    public function processResetPassword() {
        $this->validateCsrf();
        $token = $_POST['token'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $tenant = getActiveTenant();

        if (empty($token) || empty($email) || empty($password) || empty($confirm)) {
            die("Required parameters missing.");
        }

        if ($password !== $confirm) {
            $this->render('auth/reset_password', [
                'error' => 'Passwords do not match.',
                'token' => $token,
                'email' => $email,
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        if ($tenant === 'core') {
            $mockToken = $_SESSION['mock_superadmin_reset_token'] ?? '';
            if ($token !== $mockToken || $email !== 'super@dorpflow.com') {
                die("Invalid or expired password reset token.");
            }

            unset($_SESSION['mock_superadmin_reset_token']);
            $this->redirect("/login?reset=success");
            return;
        }

        // Tenant user reset
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND remember_token = ? LIMIT 1");
        $stmt->execute([$email, $token]);
        $user = $stmt->fetch();

        if ($user) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmtUpdate = $db->prepare("UPDATE users SET password_hash = ?, remember_token = NULL WHERE id = ?");
            $stmtUpdate->execute([$hash, $user['id']]);

            // Log audit
            $audit = new AuditLog();
            $audit->log($user['id'], "Password reset completed successfully");

            $url = "/login?reset=success";
            if (isset($_GET['tenant'])) {
                $url .= "&tenant=" . urlencode($_GET['tenant']);
            }
            $this->redirect($url);
        } else {
            die("Invalid or expired password reset token.");
        }
    }

    /**
     * Process User Authentication POST
     */
    public function processLogin() {
        $this->validateCsrf();
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->render('auth/login', [
                'error' => 'Email and Password are required.',
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        // Resolving Auth context (Core vs Tenant)
        $tenant = getActiveTenant();
        
        if ($tenant === 'core') {
            // Core database login (Super Admin)
            if ($email === 'super@dorpflow.com' && $password === 'password') {
                $_SESSION['user_id'] = 9999;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = 'Super Administrator';
                $_SESSION['user_role'] = 'Super Admin';
                $_SESSION['user_role_id'] = 1;
                $this->redirect('/superadmin/dashboard');
            } else {
                $this->render('auth/login', [
                    'error' => 'Invalid Super Admin credentials.',
                    'csrf_token' => $this->getCsrfToken()
                ]);
            }
            return;
        }

        // Tenant specific user login
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            if ($user['is_locked']) {
                $this->render('auth/login', [
                    'error' => 'Account is locked. Contact your department manager.',
                    'csrf_token' => $this->getCsrfToken()
                ]);
                return;
            }

            // Staff roles require 2FA Multi-Factor Verification
            if (in_array($user['role_name'], ['Municipality Administrator', 'Department Manager', 'Supervisor'])) {
                $_SESSION['2fa_temp_user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['full_name'],
                    'role' => $user['role_name'],
                    'role_id' => $user['role_id']
                ];
                $_SESSION['2fa_otp'] = rand(111111, 999999);
                $_SESSION['tenant'] = $tenant;
                
                $this->redirect('/login-2fa');
                return;
            }

            // Set up sessions directly for non-staff (Residents, Technicians)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['user_role_id'] = $user['role_id'];

            // Log activity
            $audit = new AuditLog();
            $audit->log($user['id'], "User logged in successfully from IP {$_SERVER['REMOTE_ADDR']}");

            $this->redirectDashboard();
        } else {
            $this->render('auth/login', [
                'error' => 'Invalid email or password.',
                'csrf_token' => $this->getCsrfToken()
            ]);
        }
    }

    /**
     * Process User Sign Out
     */
    public function logout() {
        if (Auth::isLoggedIn()) {
            $userId = $_SESSION['user_id'];
            $tenant = getActiveTenant();
            if ($tenant !== 'core') {
                $audit = new AuditLog();
                $audit->log($userId, "User logged out");
            }
        }
        
        // Destroy sessions
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: " . APP_URL . "/public/index.php/login");
        exit;
    }

    /**
     * Show Dashboard based on logged-in user role
     */
    public function showDashboard() {
        Auth::requireRole(['Any']);
        $role = $_SESSION['user_role'] ?? '';
        
        if ($role === 'Super Admin') {
            $this->redirect('/superadmin/dashboard');
        } elseif ($role === 'Municipality Administrator' || $role === 'Department Manager' || $role === 'Supervisor') {
            $this->render('dashboard/tenant_admin', [
                'title' => 'Operations Room | DorpFlow'
            ]);
        } elseif ($role === 'Technician') {
            $this->render('dashboard/technician', [
                'title' => 'Technician Dispatch | DorpFlow'
            ]);
        } elseif ($role === 'Resident') {
            $this->render('dashboard/resident', [
                'title' => 'Citizen Portal | DorpFlow'
            ]);
        } else {
            $this->redirect('/login');
        }
    }

    /**
     * Helper to redirect users based on role
     */
    private function redirectDashboard() {
        $role = $_SESSION['user_role'] ?? '';
        if ($role === 'Super Admin') {
            $this->redirect('/superadmin/dashboard');
        } else {
            $this->redirect('/dashboard');
        }
    }

    /**
     * Render the 2FA OTP Challenge View
     */
    public function show2fa() {
        if (!isset($_SESSION['2fa_temp_user']) || !isset($_SESSION['2fa_otp'])) {
            $this->redirect('/login');
        }

        $this->render('auth/login_2fa', [
            'title' => 'MFA Verification | DorpFlow',
            'email' => $_SESSION['2fa_temp_user']['email'],
            'simulated_otp' => $_SESSION['2fa_otp'],
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Validate the submitted 2FA OTP Code
     */
    public function process2fa() {
        $this->validateCsrf();

        if (!isset($_SESSION['2fa_temp_user']) || !isset($_SESSION['2fa_otp'])) {
            $this->redirect('/login');
        }

        $otpCode = $_POST['otp_code'] ?? '';

        if ($otpCode == $_SESSION['2fa_otp']) {
            $tempUser = $_SESSION['2fa_temp_user'];
            
            // Set up active sessions
            $_SESSION['user_id'] = $tempUser['id'];
            $_SESSION['user_email'] = $tempUser['email'];
            $_SESSION['user_name'] = $tempUser['name'];
            $_SESSION['user_role'] = $tempUser['role'];
            $_SESSION['user_role_id'] = $tempUser['role_id'];

            unset($_SESSION['2fa_temp_user']);
            unset($_SESSION['2fa_otp']);

            // Log activity
            $audit = new AuditLog();
            $audit->log($_SESSION['user_id'], "User completed 2FA MFA from IP {$_SERVER['REMOTE_ADDR']}");

            $this->redirectDashboard();
        } else {
            $this->render('auth/login_2fa', [
                'title' => 'MFA Verification | DorpFlow',
                'email' => $_SESSION['2fa_temp_user']['email'],
                'simulated_otp' => $_SESSION['2fa_otp'],
                'error' => 'Invalid verification code. Please try again.',
                'csrf_token' => $this->getCsrfToken()
            ]);
        }
    }
}
