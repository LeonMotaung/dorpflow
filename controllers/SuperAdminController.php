<?php
/**
 * DorpFlow ERP - Super Admin Dashboard Controller (Core Context)
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/Municipality.php';
require_once ROOT_PATH . '/models/SMSLog.php';

class SuperAdminController extends Controller {

    public function __construct() {
        Auth::requireRole(['Super Admin']);
    }

    /**
     * Show Super Admin dashboard
     */
    public function dashboard() {
        $muniModel = new Municipality();
        $overview = $muniModel->getBillingOverview();

        $db = Database::getCoreConnection();
        $totalSms = $db->query("SELECT COUNT(*) FROM sms_gateway_logs")->fetchColumn();
        $totalProfit = $db->query("SELECT SUM(profit) FROM sms_gateway_logs")->fetchColumn() ?: 0.00;
        
        $this->render('dashboard/super_admin', [
            'title' => 'Platform Control Room | DorpFlow',
            'municipalities' => $overview,
            'total_sms' => $totalSms,
            'total_profit' => $totalProfit,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * List all municipalities
     */
    public function listMunicipalities() {
        $muniModel = new Municipality();
        $municipalities = $muniModel->all();

        $this->render('superadmin/municipalities_list', [
            'title' => 'Municipal Tenancy Registry | DorpFlow',
            'municipalities' => $municipalities,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Provision a new Municipality database and key
     */
    public function createMunicipality() {
        $this->validateCsrf();

        $name = $_POST['name'] ?? '';
        $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['subdomain'] ?? ''));
        $plan = $_POST['plan'] ?? 'Enterprise';
        $price = $_POST['price'] ?? 15000.00;

        if (empty($name) || empty($subdomain)) {
            $this->redirect('/superadmin/municipalities?error=validation');
        }

        $dbName = 'dorpflow_tenant_' . $subdomain;
        $apiKey = 'df_key_' . bin2hex(random_bytes(16));
        $apiSecret = 'df_sec_' . bin2hex(random_bytes(24));

        $core = Database::getCoreConnection();
        
        // Check for duplicates
        $stmt = $core->prepare("SELECT id FROM municipalities WHERE subdomain = ? OR db_name = ? LIMIT 1");
        $stmt->execute([$subdomain, $dbName]);
        if ($stmt->fetch()) {
            $this->redirect('/superadmin/municipalities?error=duplicate');
        }

        // Insert Municipality
        $stmtMuni = $core->prepare("
            INSERT INTO municipalities (name, subdomain, db_name, status, api_key, api_secret, sms_balance, email_balance, storage_limit_mb, storage_used_mb)
            VALUES (?, ?, ?, 'active', ?, ?, 5000, 20000, 5000, 100)
        ");
        $stmtMuni->execute([$name, $subdomain, $dbName, $apiKey, $apiSecret]);
        $muniId = $core->lastInsertId();

        // Insert Subscription
        $stmtSub = $core->prepare("
            INSERT INTO subscriptions (municipality_id, plan, price, billing_cycle, status, next_billing_date)
            VALUES (?, ?, ?, 'monthly', 'active', DATE_ADD(CURDATE(), INTERVAL 1 MONTH))
        ");
        $stmtSub->execute([$muniId, $plan, $price]);

        // Dynamic Tenant Database Provisioning
        try {
            $core->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $dbConfig = require ROOT_PATH . '/config/database.php';
            $dbHost = $dbConfig['tenant_template']['host'] ?? '127.0.0.1';
            $dbUser = $dbConfig['tenant_template']['username'] ?? 'root';
            $dbPass = $dbConfig['tenant_template']['password'] ?? '';

            $tenantPdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // Run Schema Template
            $schemaSql = file_get_contents(ROOT_PATH . '/db/dorpflow_tenant_template.sql');
            $tenantPdo->exec($schemaSql);

            // Seed Roles
            $roles = [
                ['Super Admin', 'Full access control over everything'],
                ['Municipality Administrator', 'Controls municipality departments and configuration'],
                ['Department Manager', 'Manages department workflows, budgets, and technicians'],
                ['Supervisor', 'Verifies citizen tickets and schedules dispatches'],
                ['Call Centre Agent', 'Logs and routes reports received via phone/walk-ins'],
                ['Receptionist', 'Captures walk-in citizen inquiries'],
                ['Technician', 'Field worker resolving maintenance and service delivery requests'],
                ['Municipal Employee', 'General municipal internal accounts'],
                ['Resident', 'Citizen reporting faults and tracking resolutions'],
                ['Guest', 'Unauthenticated dashboard viewer']
            ];
            $stmtRole = $tenantPdo->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
            foreach ($roles as $r) {
                $stmtRole->execute($r);
            }
            $roleIds = $tenantPdo->query("SELECT name, id FROM roles")->fetchAll(PDO::FETCH_KEY_PAIR);

            // Seed Default Admin User
            $hash = password_hash('password', PASSWORD_DEFAULT);
            $stmtUser = $tenantPdo->prepare("INSERT INTO users (role_id, email, password_hash, full_name, phone) VALUES (?, ?, ?, ?, ?)");
            $stmtUser->execute([$roleIds['Municipality Administrator'], 'admin@' . $subdomain . '.gov.za', $hash, "$name Admin", '0125550101']);
            
            // Seed Default Wards
            $wards = [
                [1, 'Cllr John Smith'],
                [2, 'Cllr Jane Doe'],
                [3, 'Cllr Thabo Mbeki']
            ];
            $stmtWard = $tenantPdo->prepare("INSERT INTO Wards (ward_number, councilor_name) VALUES (?, ?)");
            foreach ($wards as $w) {
                $stmtWard->execute($w);
            }

        } catch (Exception $e) {
            // Log issue
            error_log("Failed to provision database $dbName: " . $e->getMessage());
        }

        $this->redirect('/superadmin/municipalities?success=provisioned');
    }

    /**
     * List all Subscriptions
     */
    public function listSubscriptions() {
        $db = Database::getCoreConnection();
        $subscriptions = $db->query("
            SELECT s.*, m.name as municipality_name 
            FROM subscriptions s
            LEFT JOIN municipalities m ON m.id = s.municipality_id
            ORDER BY s.created_at DESC
        ")->fetchAll();

        $this->render('superadmin/subscriptions_list', [
            'title' => 'SaaS Subscription Ledgers | DorpFlow',
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * List SMS Logs
     */
    public function listSmsGateways() {
        $db = Database::getCoreConnection();
        $logs = $db->query("
            SELECT s.*, m.name as municipality_name 
            FROM sms_gateway_logs s
            LEFT JOIN municipalities m ON m.id = s.municipality_id
            ORDER BY s.created_at DESC
            LIMIT 100
        ")->fetchAll();

        $this->render('superadmin/sms_gateways_list', [
            'title' => 'SMS Gateway Loggers | DorpFlow',
            'logs' => $logs
        ]);
    }

    /**
     * List Email Logs
     */
    public function listEmailGateways() {
        $db = Database::getCoreConnection();
        $logs = $db->query("
            SELECT e.*, m.name as municipality_name 
            FROM email_gateway_logs e
            LEFT JOIN municipalities m ON m.id = e.municipality_id
            ORDER BY e.created_at DESC
            LIMIT 100
        ")->fetchAll();

        $this->render('superadmin/email_gateways_list', [
            'title' => 'Email SMTP Loggers | DorpFlow',
            'logs' => $logs
        ]);
    }

    /**
     * System Settings Config
     */
    public function showSystemConfig() {
        $this->render('superadmin/system_config', [
            'title' => 'SaaS Global Config | DorpFlow',
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Delete a Municipality and its associated Database
     */
    public function deleteMunicipality() {
        $this->validateCsrf();
        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            $this->redirect('/superadmin/municipalities?error=invalid');
        }

        $core = Database::getCoreConnection();

        // Retrieve db name
        $stmt = $core->prepare("SELECT db_name FROM municipalities WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $muni = $stmt->fetch();

        if ($muni) {
            $dbName = $muni['db_name'];
            
            // 1. Drop associated dynamic database
            try {
                $core->exec("DROP DATABASE IF EXISTS `$dbName`");
            } catch (Exception $e) {
                error_log("Failed to drop database $dbName: " . $e->getMessage());
            }

            // 2. Delete registrations from core database
            $stmtDelMuni = $core->prepare("DELETE FROM municipalities WHERE id = ?");
            $stmtDelMuni->execute([$id]);
        }

        $this->redirect('/superadmin/municipalities?success=deleted');
    }

    /**
     * List all API requests and usage stats
     */
    public function listApiUsage() {
        $db = Database::getCoreConnection();
        
        // Fetch detailed recent requests log
        $logs = $db->query("
            SELECT a.*, m.name as municipality_name 
            FROM api_requests_log a
            LEFT JOIN municipalities m ON m.id = a.municipality_id
            ORDER BY a.created_at DESC
            LIMIT 100
        ")->fetchAll();

        // Fetch aggregated hits count per municipality
        $summary = $db->query("
            SELECT m.name, m.subdomain, COUNT(a.id) as total_hits 
            FROM municipalities m
            LEFT JOIN api_requests_log a ON a.municipality_id = m.id
            GROUP BY m.id
        ")->fetchAll();

        $this->render('superadmin/api_usage', [
            'title' => 'API Usage Monitor | DorpFlow',
            'logs' => $logs,
            'summary' => $summary
        ]);
    }

    /**
     * Generate PDF Invoice/Bill for a Municipality
     */
    public function generateInvoice() {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            die("Invalid Municipality ID.");
        }

        $db = Database::getCoreConnection();
        
        // Fetch Municipality
        $stmt = $db->prepare("SELECT * FROM municipalities WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $muni = $stmt->fetch();

        if (!$muni) {
            die("Municipality not found.");
        }

        // Fetch Subscription
        $stmtSub = $db->prepare("SELECT * FROM subscriptions WHERE municipality_id = ? LIMIT 1");
        $stmtSub->execute([$id]);
        $sub = $stmtSub->fetch();

        $planName = $sub['plan'] ?? 'Enterprise Tier';
        $licenseFee = $sub['price'] ?? 15000.00;

        // Fetch actual SMS dispatches count in current month
        $stmtSmsCount = $db->prepare("SELECT COUNT(*) FROM sms_gateway_logs WHERE municipality_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE())");
        $stmtSmsCount->execute([$id]);
        $smsSent = $stmtSmsCount->fetchColumn() ?: 0;
        $smsCost = $smsSent * 0.35; // R0.35 per SMS

        // Fetch actual Email dispatches count in current month
        $stmtEmailCount = $db->prepare("SELECT COUNT(*) FROM email_gateway_logs WHERE municipality_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE())");
        $stmtEmailCount->execute([$id]);
        $emailSent = $stmtEmailCount->fetchColumn() ?: 0;
        $emailCost = $emailSent * 0.05; // R0.05 per Email

        // Pricing calculations
        $subtotal = $licenseFee + $smsCost + $emailCost;
        $vat = $subtotal * 0.15;
        $totalDue = $subtotal + $vat;

        // Load FPDF
        require_once ROOT_PATH . '/core/fpdf.php';

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        
        // Brand Header (Logo on Left, Invoice title on Right)
        $logoPath = ROOT_PATH . '/dorpflow.png';
        $logoHeight = 12; // default fallback in mm
        $logoWidth = 35;  // width in mm
        if (file_exists($logoPath)) {
            list($wPx, $hPx) = getimagesize($logoPath);
            if ($wPx > 0) {
                $logoHeight = ($hPx / $wPx) * $logoWidth;
            }
            $pdf->Image($logoPath, 10, 8, $logoWidth);
        }
        
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetTextColor(10, 43, 76); // Brand Navy
        $pdf->Cell(190, 6, 'Dorpflow', 0, 1, 'R');
        
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(100, 110, 120);
        $pdf->Cell(190, 4, 'MONTHLY BILLING INVOICE', 0, 1, 'R');
        
        // Draw line separator clearing the dynamic logo height
        $lineY = 8 + $logoHeight + 4;
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(10, $lineY, 200, $lineY);
        
        // Move Y cursor to clear the line and logo
        $pdf->SetY($lineY + 6);
        
        // Platform vendor details
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(10, 43, 76);
        $pdf->Cell(100, 5, 'PROVIDER DETAILS:', 0, 0);
        $pdf->Cell(90, 5, 'BILL TO:', 0, 1);
        
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(80, 90, 100);
        $pdf->Cell(100, 5, 'DorpFlow Operations HQ', 0, 0);
        $pdf->Cell(90, 5, $muni['name'], 0, 1);
        
        $pdf->Cell(100, 5, '124 Jacaranda Ave, Hatfield, Pretoria, 0083', 0, 0);
        $pdf->Cell(90, 5, 'Subdomain: ' . $muni['subdomain'] . '.dorpflow.gov.za', 0, 1);
        
        $pdf->Cell(100, 5, 'CSD Vendor Number: MAAA0982319', 0, 0);
        $pdf->Cell(90, 5, 'Billing Cycle: Monthly', 0, 1);
        
        $pdf->Cell(100, 5, 'support@dorpflow.gov.za', 0, 0);
        $pdf->Cell(90, 5, 'Status: Active', 0, 1);

        $pdf->Ln(10);
        
        // Invoice Meta Table
        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(10, 43, 76);
        $pdf->Cell(45, 8, 'INVOICE NUMBER', 1, 0, 'C', true);
        $pdf->Cell(45, 8, 'DATE GENERATED', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'BILLING CYCLE RANGE', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'PAYMENT DUE DATE', 1, 1, 'C', true);
        
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(80, 90, 100);
        $invoiceNum = 'DF-' . date('Ym') . '-' . str_pad($muni['id'], 4, '0', STR_PAD_LEFT);
        $pdf->Cell(45, 8, $invoiceNum, 1, 0, 'C');
        $pdf->Cell(45, 8, date('d M Y'), 1, 0, 'C');
        $pdf->Cell(50, 8, date('01 M Y') . ' - ' . date('t M Y'), 1, 0, 'C');
        $pdf->Cell(50, 8, date('10 M Y', strtotime('+1 month')), 1, 1, 'C');
        
        $pdf->Ln(10);
        
        // Breakdown Table Headers
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(10, 43, 76); // Brand Primary
        $pdf->Cell(90, 8, 'BILLING COMPONENT DESCRIPTION', 1, 0, 'L', true);
        $pdf->Cell(30, 8, 'QUANTITY / UNIT', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'UNIT PRICE', 1, 0, 'R', true);
        $pdf->Cell(35, 8, 'SUBTOTAL', 1, 1, 'R', true);
        
        // Rows
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(80, 90, 100);
        
        // 1. Subscription License
        $pdf->Cell(90, 8, $planName . ' Plan SaaS Subscription License', 1, 0, 'L');
        $pdf->Cell(30, 8, '1 Month', 1, 0, 'C');
        $pdf->Cell(35, 8, 'R' . number_format($licenseFee, 2), 1, 0, 'R');
        $pdf->Cell(35, 8, 'R' . number_format($licenseFee, 2), 1, 1, 'R');
        
        // 2. SMS Dispatches
        $pdf->Cell(90, 8, 'Global SMS Gateway Dispatches', 1, 0, 'L');
        $pdf->Cell(30, 8, $smsSent . ' dispatches', 1, 0, 'C');
        $pdf->Cell(35, 8, 'R0.35', 1, 0, 'R');
        $pdf->Cell(35, 8, 'R' . number_format($smsCost, 2), 1, 1, 'R');
        
        // 3. Email relays
        $pdf->Cell(90, 8, 'Email SMTP Gateway Deliveries', 1, 0, 'L');
        $pdf->Cell(30, 8, $emailSent . ' relays', 1, 0, 'C');
        $pdf->Cell(35, 8, 'R0.05', 1, 0, 'R');
        $pdf->Cell(35, 8, 'R' . number_format($emailCost, 2), 1, 1, 'R');
        
        // Calculations section
        $pdf->Cell(120, 8, '', 0, 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(10, 43, 76);
        $pdf->Cell(35, 8, 'Subtotal Excl. VAT:', 1, 0, 'R');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(35, 8, 'R' . number_format($subtotal, 2), 1, 1, 'R');
        
        $pdf->Cell(120, 8, '', 0, 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(35, 8, 'VAT (15%):', 1, 0, 'R');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(35, 8, 'R' . number_format($vat, 2), 1, 1, 'R');
        
        $pdf->Cell(120, 8, '', 0, 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 191, 165); // Brand Accent (#00BFA5)
        $pdf->Cell(35, 10, 'TOTAL DUE:', 1, 0, 'R', true);
        $pdf->Cell(35, 10, 'R' . number_format($totalDue, 2), 1, 1, 'R', true);
        
        $pdf->Ln(15);
        
        // Footer Note
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetTextColor(120, 130, 140);
        $pdf->MultiCell(190, 4, "Thank you for partnering with DorpFlow to digitize municipal operations and accelerate service delivery. Please ensure payment is processed within 10 days of the due date to avoid service suspensions.", 0, 'C');
        
        // Stream PDF
        $pdf->Output('I', $invoiceNum . '.pdf');
        exit;
    }
}
