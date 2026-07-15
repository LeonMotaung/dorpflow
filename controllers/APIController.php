<?php
/**
 * DorpFlow ERP - REST API Controller and Gateway Endpoints
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/models/Ticket.php';
require_once ROOT_PATH . '/models/Asset.php';

class APIController extends Controller {

    /**
     * Authenticate and authorize API Key & Secret headers
     */
    private function authenticateApi() {
        $apiKey = $_SERVER['HTTP_X_DORPFLOW_KEY'] ?? $_GET['api_key'] ?? '';
        $apiSecret = $_SERVER['HTTP_X_DORPFLOW_SECRET'] ?? $_GET['api_secret'] ?? '';

        if (empty($apiKey) || empty($apiSecret)) {
            $this->json(['error' => 'API Key and Secret headers are required (X-DorpFlow-Key / X-DorpFlow-Secret).'], 401);
        }

        // Validate credentials against global core database
        $core = Database::getCoreConnection();
        $stmt = $core->prepare("SELECT * FROM municipalities WHERE api_key = ? AND api_secret = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$apiKey, $apiSecret]);
        $municipality = $stmt->fetch();

        if (!$municipality) {
            $this->json(['error' => 'Invalid API credentials or deactivated municipality account.'], 403);
        }

        // Log authenticated API usage request
        try {
            $stmtLog = $core->prepare("
                INSERT INTO api_requests_log (municipality_id, endpoint, method, ip_address, response_code)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmtLog->execute([
                $municipality['id'],
                $_SERVER['REQUEST_URI'] ?? '/api',
                $_SERVER['REQUEST_METHOD'] ?? 'GET',
                $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                200
            ]);
        } catch (Exception $e) {
            error_log("Failed to write to api_requests_log: " . $e->getMessage());
        }

        // Force session database resolution context
        $_SESSION['tenant'] = $municipality['subdomain'];
        return $municipality;
    }

    /**
     * Endpoint: GET /api/v1/tickets
     */
    public function getTickets() {
        $muni = $this->authenticateApi();
        
        $ticketModel = new Ticket();
        $tickets = $ticketModel->getDetailedList();

        $this->json([
            'municipality' => $muni['name'],
            'total_tickets' => count($tickets),
            'tickets' => $tickets
        ]);
    }

    /**
     * Endpoint: GET /api/v1/assets
     */
    public function getAssets() {
        $muni = $this->authenticateApi();

        $assetModel = new Asset();
        $assets = $assetModel->getAssets();

        $this->json([
            'municipality' => $muni['name'],
            'total_assets' => count($assets),
            'assets' => $assets
        ]);
    }

    /**
     * Endpoint: POST /api/v1/telemetry
     */
    public function postTelemetry() {
        $muni = $this->authenticateApi();
        
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $meterNumber = trim($input['meter_number'] ?? '');
        $reading = isset($input['reading']) ? (float)$input['reading'] : null;

        if (empty($meterNumber) || $reading === null || $reading < 0) {
            $this->json(['error' => 'Missing or invalid parameters. Requires meter_number and non-negative reading.'], 400);
        }

        $type = 'water';
        if (preg_match('/ele|power/i', $meterNumber)) {
            $type = 'electricity';
        }

        $rate = ($type === 'water') ? 12.50 : 2.80;
        $cost = $reading * $rate;

        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO iot_telemetry (meter_number, type, reading, cost, status)
            VALUES (?, ?, ?, ?, 'unpaid')
        ");
        $stmt->execute([$meterNumber, $type, $reading, $cost]);
        $readingId = $db->lastInsertId();

        $this->json([
            'success' => true,
            'message' => 'IoT consumption reading logged successfully.',
            'reading_id' => (int)$readingId,
            'meter_type' => $type,
            'calculated_cost' => $cost
        ]);
    }

    /**
     * Endpoint: POST /api/v1/prepaid/purchase
     */
    public function purchasePrepaidToken() {
        $muni = $this->authenticateApi();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $meterNumber = trim($input['meter_number'] ?? '');
        $amount = isset($input['amount_rand']) ? (float)$input['amount_rand'] : 0.0;

        if (empty($meterNumber) || $amount <= 0.0) {
            $this->json(['error' => 'Missing or invalid parameters. Requires meter_number and positive amount_rand.'], 400);
        }

        $db = Database::getConnection();

        $db->exec("
            CREATE TABLE IF NOT EXISTS prepaid_purchases (
                id INT AUTO_INCREMENT PRIMARY KEY,
                meter_number VARCHAR(50) NOT NULL,
                amount_rand DECIMAL(10,2) NOT NULL,
                token_code VARCHAR(30) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;
        ");

        $tokenParts = [];
        for ($i = 0; $i < 5; $i++) {
            $tokenParts[] = sprintf("%04d", rand(0, 9999));
        }
        $tokenCode = implode('-', $tokenParts);

        $stmt = $db->prepare("
            INSERT INTO prepaid_purchases (meter_number, amount_rand, token_code)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$meterNumber, $amount, $tokenCode]);
        $purchaseId = $db->lastInsertId();

        try {
            $stmtAudit = $db->prepare("
                INSERT INTO audit_logs (user_id, action, ip_address)
                VALUES (NULL, ?, ?)
            ");
            $stmtAudit->execute([
                "API Prepaid Token Purchased: R" . number_format($amount, 2) . " for meter " . $meterNumber,
                $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            ]);
        } catch (Exception $e) {
            // Ignore audit log error
        }

        $this->json([
            'success' => true,
            'message' => 'Prepaid token generated successfully.',
            'purchase_id' => (int)$purchaseId,
            'meter_number' => $meterNumber,
            'amount_rand' => $amount,
            'token_code' => $tokenCode
        ]);
    }

    /**
     * Endpoint: POST /api/v1/webhooks/whatsapp
     */
    public function whatsappWebhook() {
        $muni = $this->authenticateApi();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $from = trim($input['From'] ?? $input['sender'] ?? '');
        $body = trim($input['Body'] ?? $input['message'] ?? '');

        if (empty($from) || empty($body)) {
            $this->json(['error' => 'Missing parameter. Requires From/sender and Body/message.'], 400);
        }

        $bodyLower = strtolower($body);
        $category = 'General';
        if (preg_match('/water|leak|pipe|flood|burst/i', $bodyLower)) {
            $category = 'Water';
        } elseif (preg_match('/electricity|power|outage|lights|transformer/i', $bodyLower)) {
            $category = 'Electricity';
        } elseif (preg_match('/road|pothole|street|stormwater|drain/i', $bodyLower)) {
            $category = 'Roads & Stormwater';
        } elseif (preg_match('/waste|rubbish|garbage|bin|refuse/i', $bodyLower)) {
            $category = 'Waste Management';
        }

        $prefix = strtoupper(substr($category, 0, 3));
        if (strlen($prefix) < 3) $prefix = str_pad($prefix, 3, 'X');
        $ticketNumber = $prefix . '-WA' . rand(1000, 9999);

        $db = Database::getConnection();
        
        $wardId = null;
        try {
            $stmtUser = $db->prepare("SELECT ward_id FROM users WHERE phone = ? LIMIT 1");
            $stmtUser->execute([$from]);
            $wardId = $stmtUser->fetchColumn() ?: null;
        } catch (Exception $e) {
            // Ignore
        }

        $stmt = $db->prepare("
            INSERT INTO tickets (ticket_number, reporter_id, category, description, status, priority, ward_id)
            VALUES (?, NULL, ?, ?, 'Pending Review', 'Medium', ?)
        ");
        $stmt->execute([$ticketNumber, $category, '[WhatsApp Webhook] ' . $body, $wardId]);
        $ticketId = $db->lastInsertId();

        $this->json([
            'success' => true,
            'message' => 'Fault ticket logged via WhatsApp webhook.',
            'ticket_id' => (int)$ticketId,
            'ticket_number' => $ticketNumber,
            'category' => $category,
            'status' => 'Pending Review'
        ]);
    }

    /**
     * Endpoint: GET /api/v1/scm/verify
     */
    public function verifySupplier() {
        $muni = $this->authenticateApi();

        $supplierNumber = trim($_GET['supplier_number'] ?? '');

        if (empty($supplierNumber)) {
            $this->json(['error' => 'Missing parameter. supplier_number parameter is required.'], 400);
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT supplier_number, company_name, tax_status, restricted, bee_level, last_verified_at 
            FROM csd_suppliers 
            WHERE supplier_number = ? 
            LIMIT 1
        ");
        $stmt->execute([$supplierNumber]);
        $supplier = $stmt->fetch();

        if (!$supplier) {
            $this->json(['error' => 'Supplier not found in municipal CSD register.'], 404);
        }

        $supplier['restricted'] = (bool)$supplier['restricted'];
        $supplier['bee_level'] = (int)$supplier['bee_level'];

        $this->json([
            'success' => true,
            'supplier' => $supplier
        ]);
    }
}
