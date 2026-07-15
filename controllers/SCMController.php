<?php
/**
 * DorpFlow ERP - SCM & WhatsApp Bot Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class SCMController extends Controller {

    public function __construct() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager']);
    }

    /**
     * Show SCM CSD Supplier Register
     */
    public function showScm() {
        $db = Database::getConnection();
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $stmt = $db->prepare("SELECT * FROM csd_suppliers WHERE supplier_number LIKE ? OR company_name LIKE ? ORDER BY company_name");
            $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
            $suppliers = $stmt->fetchAll();
        } else {
            $suppliers = $db->query("SELECT * FROM csd_suppliers ORDER BY company_name")->fetchAll();
        }

        $totalSuppliers = count($suppliers);
        $compliantCount = count(array_filter($suppliers, fn($s) => $s['tax_status'] === 'Compliant' && !$s['restricted']));
        $nonCompliantCount = count(array_filter($suppliers, fn($s) => $s['tax_status'] !== 'Compliant'));
        $restrictedCount = count(array_filter($suppliers, fn($s) => $s['restricted']));

        $this->render('admin/scm_verification', [
            'title' => 'SCM CSD Verification | DorpFlow',
            'suppliers' => $suppliers,
            'search' => $search,
            'totalSuppliers' => $totalSuppliers,
            'compliantCount' => $compliantCount,
            'nonCompliantCount' => $nonCompliantCount,
            'restrictedCount' => $restrictedCount,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Add a new supplier to the CSD register
     */
    public function addSupplier() {
        $this->validateCsrf();
        $db = Database::getConnection();

        $supplierNumber = strtoupper(trim($_POST['supplier_number'] ?? ''));
        $companyName = trim($_POST['company_name'] ?? '');
        $taxStatus = $_POST['tax_status'] ?? 'Compliant';
        $beeLevel = (int)($_POST['bee_level'] ?? 1);
        $restricted = isset($_POST['restricted']) ? 1 : 0;

        if (empty($supplierNumber) || empty($companyName)) {
            $this->redirect('/admin/scm?error=missing_fields');
        }

        $stmt = $db->prepare("
            INSERT INTO csd_suppliers (supplier_number, company_name, tax_status, bee_level, restricted)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$supplierNumber, $companyName, $taxStatus, $beeLevel, $restricted]);

        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Registered CSD supplier $supplierNumber ($companyName) in SCM register.");

        $this->redirect('/admin/scm?success=added');
    }

    /**
     * Delete a supplier from the CSD register
     */
    public function deleteSupplier() {
        $this->validateCsrf();
        $db = Database::getConnection();

        $supplierId = (int)($_POST['supplier_id'] ?? 0);
        if (!$supplierId) $this->redirect('/admin/scm?error=invalid');

        $stmt = $db->prepare("DELETE FROM csd_suppliers WHERE id = ?");
        $stmt->execute([$supplierId]);

        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Removed supplier ID $supplierId from the CSD SCM register.");

        $this->redirect('/admin/scm?success=deleted');
    }

    /**
     * WhatsApp Bot Simulator - Show Chat Interface
     */
    public function showWhatsAppBot() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);

        $db = Database::getConnection();

        // Fetch bot-generated tickets only (source = 'whatsapp_bot')
        $botTickets = [];
        try {
            $stmt = $db->query("SELECT ticket_number, category, description, status, created_at FROM tickets WHERE description LIKE '%[WhatsApp Bot]%' ORDER BY created_at DESC LIMIT 20");
            $botTickets = $stmt->fetchAll();
        } catch (Exception $e) {
            $botTickets = [];
        }

        // Build conversation history from session
        $conversation = $_SESSION['wa_conversation'] ?? [];

        $this->render('admin/whatsapp_simulator', [
            'title' => 'WhatsApp Bot Simulator | DorpFlow',
            'botTickets' => $botTickets,
            'conversation' => $conversation,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * WhatsApp Bot - Process citizen message and create a ticket
     */
    public function processWhatsAppMessage() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);
        $this->validateCsrf();

        $message = trim($_POST['citizen_message'] ?? '');

        if (empty($message)) {
            $this->redirect('/admin/whatsapp-bot');
        }

        // Init session conversation
        if (!isset($_SESSION['wa_conversation'])) {
            $_SESSION['wa_conversation'] = [];
        }

        // Add user message to conversation
        $_SESSION['wa_conversation'][] = [
            'side' => 'from-user',
            'text' => $message,
            'time' => date('H:i')
        ];

        // Parse message for category
        $messageLower = strtolower($message);
        $category = 'General';
        if (preg_match('/water|leak|pipe|flood|burst/i', $message)) {
            $category = 'Water';
        } elseif (preg_match('/electricity|power|outage|lights|transformer/i', $message)) {
            $category = 'Electricity';
        } elseif (preg_match('/road|pothole|street|stormwater|drain/i', $message)) {
            $category = 'Roads & Stormwater';
        } elseif (preg_match('/waste|rubbish|garbage|bin|refuse/i', $message)) {
            $category = 'Waste Management';
        }

        // Generate unique ticket number
        $prefix = strtoupper(substr($category, 0, 3));
        $ticketNumber = $prefix . '-WA' . rand(1000, 9999);

        // Insert ticket into tenant DB
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO tickets (ticket_number, reporter_id, category, description, status, priority)
            VALUES (?, NULL, ?, ?, 'Pending Review', 'Medium')
        ");
        $stmt->execute([$ticketNumber, $category, '[WhatsApp Bot] ' . $message]);

        // Bot reply
        $botReply = "✅ *Fault Report Received!*\n\n" .
            "Thank you for reporting this to DorpFlow Municipal Services.\n\n" .
            "📋 *Reference:* " . $ticketNumber . "\n" .
            "📂 *Category:* " . $category . "\n" .
            "📍 *Status:* Pending Review\n\n" .
            "Our team will assign a technician shortly. Reply TRACK " . $ticketNumber . " to check your report status.";

        $_SESSION['wa_conversation'][] = [
            'side' => '',
            'text' => $botReply,
            'time' => date('H:i')
        ];

        $this->redirect('/admin/whatsapp-bot');
    }

    /**
     * Reset conversation session
     */
    public function resetConversation() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);
        unset($_SESSION['wa_conversation']);
        $this->redirect('/admin/whatsapp-bot');
    }
}
