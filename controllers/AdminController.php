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
        
        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $roleId = $_POST['role_id'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($fullName) || empty($email) || empty($phone) || empty($roleId) || empty($password)) {
            $db = Database::getConnection();
            $roles = $db->query("SELECT * FROM roles WHERE name != 'Resident' AND name != 'Super Admin' ORDER BY name ASC")->fetchAll();
            $departments = $db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();
            $this->render('admin/employee_create', [
                'error' => 'All fields are required.',
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
            INSERT INTO users (role_id, email, password_hash, full_name, phone)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmtInsert->execute([$roleId, $email, $passwordHash, $fullName, $phone]);

        // Audit Log
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Created municipal staff user account for $fullName ($email)");

        $this->redirect('/employees');
    }
}
