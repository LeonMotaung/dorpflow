<?php
/**
 * DorpFlow ERP - Resident Billing & Online Payments Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class BillingController extends Controller {

    public function __construct() {
        Auth::requireRole(['Resident']);
    }

    /**
     * Show citizen utility invoices & billing histories
     */
    public function showResidentBilling() {
        $db = Database::getConnection();
        
        // Fetch resident telemetry billing invoices
        $bills = $db->query("SELECT * FROM iot_telemetry ORDER BY created_at DESC")->fetchAll();

        // Calculate unpaid bills subtotal
        $unpaidTotal = 0;
        foreach ($bills as $b) {
            if ($b['status'] !== 'paid') {
                $unpaidTotal += $b['cost'];
            }
        }

        $this->render('dashboard/resident_billing', [
            'title' => 'Citizen Utility billing Ledger | DorpFlow',
            'bills' => $bills,
            'unpaid_total' => $unpaidTotal,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Peach Payments / Yoco checkout simulation gates
     */
    public function processPeachCheckout() {
        $this->validateCsrf();

        $amount = $_POST['amount'] ?? 0.00;
        $cardNumber = $_POST['card_number'] ?? '';
        $cardHolder = $_POST['card_holder'] ?? '';

        if (empty($cardNumber) || $amount <= 0) {
            $this->redirect('/resident/billing?error=invalid_payment');
        }

        $db = Database::getConnection();

        // 1. Simulate API Payment verification to Yoco/Peach Payments
        // In production: dispatch Peach API request with merchant tokens
        $paymentSuccess = true; // Simulated success

        if ($paymentSuccess) {
            // 2. Mark all unpaid bills as paid in the database
            $db->exec("UPDATE iot_telemetry SET status = 'paid' WHERE status != 'paid'");

            // 3. Log activity audit
            $audit = new AuditLog();
            $audit->log($_SESSION['user_id'], "Completed Peach Payments checkout. Amount processed: R" . number_format($amount, 2));

            // 4. Send SMS Receipt Notification via Core gateway
            $core = Database::getCoreConnection();
            $userPhone = $db->query("SELECT phone FROM users WHERE id = " . intval($_SESSION['user_id']))->fetchColumn();
            
            $msg = "DorpFlow Billing: Thank you! Payment of R" . number_format($amount, 2) . " received. Reference: PAY-" . bin2hex(random_bytes(4)) . ". Your account is fully paid.";
            
            $stmtSms = $core->prepare("
                INSERT INTO sms_gateway_logs (municipality_id, recipient, message, provider, status)
                VALUES ((SELECT id FROM municipalities WHERE subdomain = ?), ?, ?, 'Peach-Pay', 'delivered')
            ");
            $stmtSms->execute([getActiveTenant(), $userPhone, $msg]);

            $this->redirect('/resident/billing?success=payment_completed');
        } else {
            $this->redirect('/resident/billing?error=payment_failed');
        }
    }
}
