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
}
