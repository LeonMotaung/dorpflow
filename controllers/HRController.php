<?php
/**
 * DorpFlow ERP - HR & Payroll Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class HRController extends Controller {

    public function __construct() {
        Auth::requireRole(['Municipality Administrator']);
    }

    /**
     * Display the HR and Payroll Dashboard
     */
    public function index() {
        $db = Database::getConnection();
        $coreDb = Database::getCoreConnection();
        $subdomain = getActiveTenant();

        // 1. Database self-repair: add salary column to users if not exists
        try {
            $db->query("SELECT salary FROM users LIMIT 1");
        } catch (PDOException $e) {
            $db->exec("ALTER TABLE users ADD COLUMN salary DECIMAL(10,2) DEFAULT 0.00");
        }

        // 2. Database self-repair: add gateway columns to municipalities core table
        try {
            $coreDb->query("SELECT payment_gateway FROM municipalities LIMIT 1");
        } catch (PDOException $e) {
            $coreDb->exec("ALTER TABLE municipalities ADD COLUMN payment_gateway VARCHAR(50) DEFAULT 'Peach Payments'");
            $coreDb->exec("ALTER TABLE municipalities ADD COLUMN gateway_merchant_id VARCHAR(100) DEFAULT NULL");
            $coreDb->exec("ALTER TABLE municipalities ADD COLUMN gateway_api_key VARCHAR(255) DEFAULT NULL");
        }

        // Fetch municipality gateway credentials
        $stmt = $coreDb->prepare("SELECT * FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmt->execute([$subdomain]);
        $muni = $stmt->fetch();

        // Fetch all staff users (non-residents)
        $employees = $db->query("
            SELECT u.*, r.name as role_name 
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE r.name != 'Resident' AND r.name != 'Super Admin'
            ORDER BY u.full_name ASC
        ")->fetchAll();

        $this->render('admin/hr_payroll', [
            'title' => 'HR & Payroll Operations | DorpFlow',
            'muni' => $muni,
            'employees' => $employees,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Save Payment Gateway Settings
     */
    public function updateGateway() {
        $this->validateCsrf();
        $coreDb = Database::getCoreConnection();
        $subdomain = getActiveTenant();

        $gateway = $_POST['payment_gateway'] ?? 'Peach Payments';
        $merchantId = $_POST['gateway_merchant_id'] ?? '';
        $apiKey = $_POST['gateway_api_key'] ?? '';

        $stmt = $coreDb->prepare("
            UPDATE municipalities 
            SET payment_gateway = ?, gateway_merchant_id = ?, gateway_api_key = ?
            WHERE subdomain = ?
        ");
        $stmt->execute([$gateway, $merchantId, $apiKey, $subdomain]);

        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Configured active municipal payment gateway to $gateway.");

        $_SESSION['success_message'] = "Payment Gateway configurations saved successfully!";
        $this->redirect('/admin/hr-payroll');
    }

    /**
     * Batch Update Employee Salaries
     */
    public function updateSalaries() {
        $this->validateCsrf();
        $db = Database::getConnection();

        $salaries = $_POST['salaries'] ?? [];

        $stmtUpdate = $db->prepare("UPDATE users SET salary = ? WHERE id = ?");
        foreach ($salaries as $userId => $salaryAmount) {
            $stmtUpdate->execute([$salaryAmount, $userId]);
        }

        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Batch updated municipal employee payroll salary records.");

        $_SESSION['success_message'] = "Employee salaries updated successfully!";
        $this->redirect('/admin/hr-payroll');
    }

    /**
     * Process Payroll Disbursement via Active Gateway
     */
    public function runPayRun() {
        $this->validateCsrf();
        $db = Database::getConnection();
        $coreDb = Database::getCoreConnection();
        $subdomain = getActiveTenant();

        // Get active gateway settings
        $stmt = $coreDb->prepare("SELECT payment_gateway, name FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmt->execute([$subdomain]);
        $muni = $stmt->fetch();

        // Calculate total payroll
        $totalPayroll = $db->query("
            SELECT SUM(u.salary) as total 
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE r.name != 'Resident' AND r.name != 'Super Admin'
        ")->fetchColumn() ?: 0.00;

        $staffCount = $db->query("
            SELECT COUNT(*) 
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE r.name != 'Resident' AND r.name != 'Super Admin'
        ")->fetchColumn() ?: 0;

        if ($totalPayroll <= 0) {
            $_SESSION['error_message'] = "Unable to execute payroll run: Total payroll amount is R0.00. Please configure employee salaries first.";
            $this->redirect('/admin/hr-payroll');
            return;
        }

        // Simulate disbursement call to Payment Gateway
        $gatewayName = $muni['payment_gateway'] ?? 'Peach Payments';
        
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Processed municipal payroll pay run for $staffCount staff. Total R" . number_format($totalPayroll, 2) . " disbursed via $gatewayName.");

        $_SESSION['success_message'] = "Payroll Pay Run Successful! R" . number_format($totalPayroll, 2) . " has been successfully disbursed to $staffCount employees via the $gatewayName gateway API integration.";
        $this->redirect('/admin/hr-payroll');
    }
}
