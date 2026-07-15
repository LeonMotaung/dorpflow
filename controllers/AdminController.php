<?php
/**
 * DorpFlow ERP - Municipality Tenant Administrator Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/User.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class AdminController extends Controller {

    public function __construct() {
        // Enforce that only Municipality Administrators or Department Managers can access these features
        Auth::requireRole(['Municipality Administrator', 'Department Manager']);
        
        // Self-repair: Ensure users table has id_number column
        $db = Database::getConnection();
        try {
            $db->query("SELECT id_number FROM users LIMIT 1");
        } catch (PDOException $e) {
            $db->exec("ALTER TABLE users ADD COLUMN id_number VARCHAR(20) DEFAULT NULL");
        }
    }

    /**
     * List all Departments
     */
    public function listDepartments() {
        $db = Database::getConnection();
        $departments = $db->query("
            SELECT d.*, u.full_name as manager_name 
            FROM departments d
            LEFT JOIN users u ON u.id = d.manager_id
            ORDER BY d.name ASC
        ")->fetchAll();

        $this->render('admin/departments_list', [
            'title' => 'Manage Departments | DorpFlow',
            'departments' => $departments
        ]);
    }

    /**
     * Show Create Department form
     */
    public function showCreateDepartment() {
        $userModel = new User();
        // Managers can be selected as department heads
        $managers = $userModel->getByRole('Department Manager');

        $this->render('admin/department_create', [
            'title' => 'Create Department | DorpFlow',
            'managers' => $managers,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Department creation POST
     */
    public function processCreateDepartment() {
        $this->validateCsrf();
        
        $name = $_POST['name'] ?? '';
        $managerId = $_POST['manager_id'] ?? NULL;
        $budget = $_POST['budget'] ?? 0.00;

        if (empty($name)) {
            $userModel = new User();
            $managers = $userModel->getByRole('Department Manager');
            $this->render('admin/department_create', [
                'error' => 'Department Name is required.',
                'managers' => $managers,
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO departments (name, manager_id, budget) VALUES (?, ?, ?)");
        $stmt->execute([$name, $managerId ?: NULL, $budget]);

        // Audit Log
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Created Department: $name with budget R" . number_format($budget, 2));

        $this->redirect('/departments');
    }

    /**
     * List all Employees / Staff
     */
    public function listEmployees() {
        $db = Database::getConnection();
        $employees = $db->query("
            SELECT u.*, r.name as role_name 
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE r.name != 'Resident' AND r.name != 'Super Admin'
            ORDER BY u.full_name ASC
        ")->fetchAll();

        $this->render('admin/employees_list', [
            'title' => 'Manage Staff Usernames | DorpFlow',
            'employees' => $employees
        ]);
    }

    /**
     * Show Create Employee form
     */
    public function showCreateEmployee() {
        // Check if onboarding is blocked by platform settings
        $coreDb = Database::getCoreConnection();
        $subdomain = getActiveTenant();
        $stmt = $coreDb->prepare("SELECT block_onboarding FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmt->execute([$subdomain]);
        $blockOnboarding = $stmt->fetchColumn() ?: 0;

        if ($blockOnboarding) {
            $_SESSION['error_message'] = "Onboarding new employees is currently blocked by your municipality settings.";
            $this->redirect('/employees');
            return;
        }

        $db = Database::getConnection();
        $roles = $db->query("SELECT * FROM roles WHERE name != 'Resident' AND name != 'Super Admin' ORDER BY name ASC")->fetchAll();
        $departments = $db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

        $this->render('admin/employee_create', [
            'title' => 'Add Staff Member | DorpFlow',
            'roles' => $roles,
            'departments' => $departments,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Employee Creation POST
     */
    public function processCreateEmployee() {
        $this->validateCsrf();
        
        // Check if onboarding is blocked
        $coreDb = Database::getCoreConnection();
        $subdomain = getActiveTenant();
        $stmtBlock = $coreDb->prepare("SELECT block_onboarding FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmtBlock->execute([$subdomain]);
        if ($stmtBlock->fetchColumn()) {
            $_SESSION['error_message'] = "Onboarding new employees is currently blocked.";
            $this->redirect('/employees');
            return;
        }

        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $roleId = $_POST['role_id'] ?? '';
        $password = $_POST['password'] ?? '';
        $idNumber = $_POST['id_number'] ?? '';

        if (empty($fullName) || empty($email) || empty($phone) || empty($roleId) || empty($password) || empty($idNumber)) {
            $db = Database::getConnection();
            $roles = $db->query("SELECT * FROM roles WHERE name != 'Resident' AND name != 'Super Admin' ORDER BY name ASC")->fetchAll();
            $departments = $db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();
            $this->render('admin/employee_create', [
                'error' => 'All fields, including National ID Number, are required.',
                'roles' => $roles,
                'departments' => $departments,
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        $db = Database::getConnection();
        
        // Validate unique email
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $roles = $db->query("SELECT * FROM roles WHERE name != 'Resident' AND name != 'Super Admin' ORDER BY name ASC")->fetchAll();
            $departments = $db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();
            $this->render('admin/employee_create', [
                'error' => 'Email is already registered.',
                'roles' => $roles,
                'departments' => $departments,
                'csrf_token' => $this->getCsrfToken()
            ]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmtInsert = $db->prepare("
            INSERT INTO users (role_id, email, password_hash, full_name, phone, id_number)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmtInsert->execute([$roleId, $email, $passwordHash, $fullName, $phone, $idNumber]);

        // Audit Log
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Created municipal staff user account for $fullName ($email)");

        $this->redirect('/employees');
    }

    /**
     * Show Municipality Settings Page
     */
    public function showSettings() {
        Auth::requireRole(['Municipality Administrator']);
        
        $coreDb = Database::getCoreConnection();
        // Self-repair check for logo_path column
        try {
            $coreDb->query("SELECT logo_path FROM municipalities LIMIT 1");
        } catch (PDOException $e) {
            $coreDb->exec("ALTER TABLE municipalities ADD COLUMN logo_path VARCHAR(255) DEFAULT NULL");
        }

        $subdomain = getActiveTenant();
        $stmt = $coreDb->prepare("SELECT * FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmt->execute([$subdomain]);
        $muni = $stmt->fetch();

        if (!$muni) {
            die("Municipality profile for tenant [$subdomain] not found in core registry.");
        }

        $this->render('admin/settings', [
            'title' => 'Municipality Settings | DorpFlow',
            'muni' => $muni,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Municipality Settings Update
     */
    public function updateSettings() {
        $this->validateCsrf();
        Auth::requireRole(['Municipality Administrator']);

        $coreDb = Database::getCoreConnection();
        $subdomain = getActiveTenant();

        $stmt = $coreDb->prepare("SELECT * FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmt->execute([$subdomain]);
        $muni = $stmt->fetch();

        if (!$muni) {
            die("Municipality profile not found.");
        }

        $apiKey = $_POST['api_key'] ?? '';
        $apiSecret = $_POST['api_secret'] ?? '';

        if (empty($apiKey) || empty($apiSecret)) {
            $this->redirect('/admin/settings');
            return;
        }

        // Handle file upload for logo
        $logoPath = $muni['logo_path'];
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['logo']['tmp_name'];
            $fileName = $_FILES['logo']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadDir = ROOT_PATH . '/public/uploads/logos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $newFileName = $subdomain . '_logo_' . time() . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $logoPath = '/public/uploads/logos/' . $newFileName;
                    // Invalidate session cache
                    unset($_SESSION['tenant_logo_' . $subdomain]);
                }
            }
        }

        $blockOnboarding = isset($_POST['block_onboarding']) ? 1 : 0;

        // Update core record
        $stmtUpdate = $coreDb->prepare("
            UPDATE municipalities 
            SET api_key = ?, api_secret = ?, logo_path = ?, block_onboarding = ?
            WHERE subdomain = ?
        ");
        $stmtUpdate->execute([$apiKey, $apiSecret, $logoPath, $blockOnboarding, $subdomain]);

        // Audit Log
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Updated municipality settings, logo profile, and REST API credentials.");

        // Update session cache
        $_SESSION['tenant_logo_' . $subdomain] = APP_URL . $logoPath;

        $_SESSION['success_message'] = "Municipality settings updated successfully!";
        $this->redirect('/admin/settings');
    }

    /**
     * Show Edit Employee Form
     */
    public function showEditEmployee($params) {
        $id = $params['id'];
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $employee = $stmt->fetch();

        if (!$employee) {
            die("Employee #$id not found.");
        }

        $roles = $db->query("SELECT * FROM roles WHERE name != 'Resident' AND name != 'Super Admin' ORDER BY name ASC")->fetchAll();
        
        $this->render('admin/employee_edit', [
            'title' => 'Edit Employee | DorpFlow',
            'employee' => $employee,
            'roles' => $roles,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Edit Employee POST updates
     */
    public function processEditEmployee() {
        $this->validateCsrf();
        $db = Database::getConnection();

        $id = $_POST['id'] ?? '';
        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $roleId = $_POST['role_id'] ?? '';
        $idNumber = $_POST['id_number'] ?? '';
        $salary = $_POST['salary'] ?? 0.00;
        $isLocked = isset($_POST['is_locked']) ? 1 : 0;

        if (empty($id) || empty($fullName) || empty($email) || empty($phone) || empty($roleId) || empty($idNumber)) {
            $_SESSION['error_message'] = "All fields are required.";
            $this->redirect("/employees/edit/$id");
            return;
        }

        // Validate unique email (excluding current user)
        $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
        $stmtCheck->execute([$email, $id]);
        if ($stmtCheck->fetch()) {
            $_SESSION['error_message'] = "Email is already registered by another employee.";
            $this->redirect("/employees/edit/$id");
            return;
        }

        // Update employee details
        $stmtUpdate = $db->prepare("
            UPDATE users 
            SET role_id = ?, email = ?, full_name = ?, phone = ?, id_number = ?, salary = ?, is_locked = ?
            WHERE id = ?
        ");
        $stmtUpdate->execute([$roleId, $email, $fullName, $phone, $idNumber, $salary, $isLocked, $id]);

        // If optional password is provided
        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmtPass = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmtPass->execute([$hash, $id]);
        }

        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Updated details and status for employee $fullName (#$id). Account locked status: $isLocked.");

        $_SESSION['success_message'] = "Employee details updated successfully.";
        $this->redirect('/employees');
    }
}
