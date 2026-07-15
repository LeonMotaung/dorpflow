<?php
/**
 * DorpFlow ERP - Smart City Operations Controller (Tenant Context)
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/models/Ticket.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class SmartOpsController extends Controller {

    /**
     * Public Service Outages & Status Board
     */
    public function showPublicStatus() {
        $tenant = getActiveTenant();
        if ($tenant === 'core') {
            die("Select a specific municipality context to view its status board.");
        }

        $db = Database::getConnection();
        
        // Fetch operational tickets
        $tickets = $db->query("
            SELECT ticket_number, category, description, status, priority, created_at 
            FROM tickets 
            WHERE status != 'Completed'
            ORDER BY created_at DESC
        ")->fetchAll();

        // Count category metrics
        $powerOutages = $db->query("SELECT COUNT(*) FROM tickets WHERE category = 'Electricity' AND status != 'Completed'")->fetchColumn();
        $waterOutages = $db->query("SELECT COUNT(*) FROM tickets WHERE category = 'Water' AND status != 'Completed'")->fetchColumn();
        
        $this->render('dashboard/public_status', [
            'title' => 'Service Delivery Status Board | DorpFlow',
            'tickets' => $tickets,
            'power_outages' => $powerOutages,
            'water_outages' => $waterOutages,
            'tenant' => $tenant
        ]);
    }

    /**
     * IoT Telemetry logs console
     */
    public function showIotTelemetry() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager']);
        
        $db = Database::getConnection();
        $logs = $db->query("SELECT * FROM iot_telemetry ORDER BY created_at DESC")->fetchAll();

        $this->render('admin/iot_telemetry', [
            'title' => 'IoT Smart Meter Logs | DorpFlow',
            'logs' => $logs,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Auto-Generate Utility billing ledger from readings
     */
    public function generateBills() {
        Auth::requireRole(['Municipality Administrator']);
        $this->validateCsrf();

        $db = Database::getConnection();
        
        // Mark all unpaid telemetry logs as paid or trigger billing dispatch
        $db->exec("UPDATE iot_telemetry SET status = 'billing_sent' WHERE status = 'unpaid'");

        // Log audit
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Triggered monthly utility bill cycles from IoT telemetries.");

        $this->redirect('/admin/iot-telemetry?success=billing_generated');
    }

    /**
     * Auto-Dispatch closest technician using GPS geofencing (Haversine formula)
     */
    public function dispatchTechnician() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);
        $this->validateCsrf();

        $ticketId = $_POST['ticket_id'] ?? '';
        if (empty($ticketId)) {
            $this->redirect('/dashboard?error=invalid');
        }

        $db = Database::getConnection();
        
        // Fetch Ticket coordinates
        $stmt = $db->prepare("SELECT id, lat, lng, category, ticket_number FROM tickets WHERE id = ? LIMIT 1");
        $stmt->execute([$ticketId]);
        $ticket = $stmt->fetch();

        if (!$ticket || empty($ticket['lat']) || empty($ticket['lng'])) {
            $this->redirect('/dashboard?error=coordinates_missing');
        }

        $tLat = (float)$ticket['lat'];
        $tLng = (float)$ticket['lng'];

        // Fetch active technicians
        $roleIds = $db->query("SELECT id FROM roles WHERE name = 'Technician'")->fetchColumn();
        $stmtTechs = $db->prepare("SELECT id, full_name, phone FROM users WHERE role_id = ? AND is_locked = 0");
        $stmtTechs->execute([$roleIds]);
        $technicians = $stmtTechs->fetchAll();

        if (empty($technicians)) {
            $this->redirect('/dashboard?error=no_techs_available');
        }

        $closestTech = null;
        $minDistance = 999999; // in kilometers

        // Mock current coordinate range near the municipality center
        $mockLocations = [
            1 => ['lat' => -33.9310, 'lng' => 18.8590], // Tech 1
            2 => ['lat' => -33.9450, 'lng' => 18.8450], // Tech 2
            3 => ['lat' => -33.9210, 'lng' => 18.8850]  // Backup
        ];

        foreach ($technicians as $tech) {
            $techLoc = $mockLocations[$tech['id']] ?? ['lat' => -33.9300, 'lng' => 18.8600];
            
            // Haversine formula calculation
            $earthRadius = 6371; // km
            $dLat = deg2rad($techLoc['lat'] - $tLat);
            $dLng = deg2rad($techLoc['lng'] - $tLng);
            
            $a = sin($dLat/2) * sin($dLat/2) +
                 cos(deg2rad($tLat)) * cos(deg2rad($techLoc['lat'])) *
                 sin($dLng/2) * sin($dLng/2);
            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
            $distance = $earthRadius * $c;

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestTech = $tech;
            }
        }

        if ($closestTech) {
            // Assign technician to ticket
            $stmtAssign = $db->prepare("UPDATE tickets SET technician_id = ?, status = 'In Progress' WHERE id = ?");
            $stmtAssign->execute([$closestTech['id'], $ticketId]);

            // Log ticket history
            $stmtHist = $db->prepare("
                INSERT INTO ticket_history (ticket_id, user_id, action, state_before, state_after)
                VALUES (?, ?, 'Auto-dispatched closest technician via Geofenced GPS calculation.', 'Pending Review', 'In Progress')
            ");
            $stmtHist->execute([$ticketId, $_SESSION['user_id']]);

            // Simulated SMS dispatch
            $core = Database::getCoreConnection();
            $msg = "DorpFlow Dispatch: Hello " . $closestTech['full_name'] . ", ticket #" . $ticket['ticket_number'] . " (" . $ticket['category'] . ") is assigned to you. Location: " . $ticket['lat'] . "," . $ticket['lng'] . ". Est. distance: " . round($minDistance, 2) . "km.";
            
            $stmtSms = $core->prepare("
                INSERT INTO sms_gateway_logs (municipality_id, recipient, message, provider, status)
                VALUES ((SELECT id FROM municipalities WHERE subdomain = ?), ?, ?, 'DorpFlow-GPS', 'delivered')
            ");
            $stmtSms->execute([getActiveTenant(), $closestTech['phone'], $msg]);
        }

        $this->redirect('/dashboard?success=dispatched&tech=' . urlencode($closestTech['full_name']) . '&dist=' . round($minDistance, 2));
    }

    /**
     * Simulate Eskom Loadshedding SMS dispatches to supervisors
     */
    public function simulateLoadsheddingAlert() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager']);
        $this->validateCsrf();

        $stage = $_POST['stage'] ?? 0;
        $tenant = getActiveTenant();

        $db = Database::getConnection();
        
        // Fetch all department managers/supervisors
        $users = $db->query("
            SELECT u.id, u.full_name, u.phone 
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE r.name IN ('Department Manager', 'Supervisor')
        ")->fetchAll();

        $core = Database::getCoreConnection();
        $stmtMuniId = $core->prepare("SELECT id FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmtMuniId->execute([$tenant]);
        $muniId = $stmtMuniId->fetchColumn();

        $stmtSms = $core->prepare("
            INSERT INTO sms_gateway_logs (municipality_id, recipient, message, provider, status)
            VALUES (?, ?, ?, 'Eskom-Alert', 'delivered')
        ");

        foreach ($users as $u) {
            $msg = "Eskom Outage Alert: Stage $stage loadshedding is now active. Verify water pump backups and initiate substation lock routines.";
            $stmtSms->execute([$muniId, $u['phone'], $msg]);
        }

        // Log audit
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Triggered loadshedding Stage $stage SMS dispatches to supervisors.");

        $this->redirect('/dashboard?success=loadshedding_alert_sent');
    }

    /**
     * Show Municipality Communications Broadcast Console
     */
    public function showBroadcast() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);
        
        $db = Database::getConnection();
        $core = Database::getCoreConnection();
        $tenant = getActiveTenant();

        // 1. Fetch SMS Balance
        $stmtMuni = $core->prepare("SELECT id, name, sms_balance FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmtMuni->execute([$tenant]);
        $muni = $stmtMuni->fetch();

        // 2. Count recipients
        $residentCount = $db->query("
            SELECT COUNT(*) FROM users u 
            JOIN roles r ON r.id = u.role_id 
            WHERE r.name = 'Resident' AND u.phone IS NOT NULL AND u.phone != ''
        ")->fetchColumn() ?: 0;

        $staffCount = $db->query("
            SELECT COUNT(*) FROM users u 
            JOIN roles r ON r.id = u.role_id 
            WHERE r.name != 'Resident' AND r.name != 'Super Admin' AND u.phone IS NOT NULL AND u.phone != ''
        ")->fetchColumn() ?: 0;

        $techCount = $db->query("
            SELECT COUNT(*) FROM users u 
            JOIN roles r ON r.id = u.role_id 
            WHERE r.name = 'Technician' AND u.phone IS NOT NULL AND u.phone != ''
        ")->fetchColumn() ?: 0;

        // 3. Fetch past 20 messages log
        $stmtLogs = $core->prepare("
            SELECT * FROM sms_gateway_logs 
            WHERE municipality_id = ? 
            ORDER BY created_at DESC 
            LIMIT 20
        ");
        $stmtLogs->execute([$muni['id']]);
        $pastLogs = $stmtLogs->fetchAll();

        $this->render('admin/broadcast', [
            'title' => 'Muni Communication Console | DorpFlow',
            'muni' => $muni,
            'resident_count' => $residentCount,
            'staff_count' => $staffCount,
            'tech_count' => $techCount,
            'past_logs' => $pastLogs,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process and dispatch SMS/WhatsApp Broadcast
     */
    public function sendBroadcast() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);
        $this->validateCsrf();

        $recipientType = $_POST['recipient_type'] ?? '';
        $customNumber = $_POST['custom_number'] ?? '';
        $messageText = $_POST['message_text'] ?? '';
        $provider = $_POST['provider'] ?? 'DorpFlow-SMS'; // SMS or WhatsApp

        if (empty($messageText) || empty($recipientType)) {
            $_SESSION['error_message'] = "Message text and recipient selection are required.";
            $this->redirect('/admin/broadcast');
            return;
        }

        $db = Database::getConnection();
        $core = Database::getCoreConnection();
        $tenant = getActiveTenant();

        // 1. Fetch Municipality Core Balance
        $stmtMuni = $core->prepare("SELECT id, name, sms_balance FROM municipalities WHERE subdomain = ? LIMIT 1");
        $stmtMuni->execute([$tenant]);
        $muni = $stmtMuni->fetch();

        if (!$muni) {
            die("Municipality core profile not found.");
        }

        // 2. Resolve target numbers
        $numbers = [];
        if ($recipientType === 'all') {
            $numbers = $db->query("
                SELECT phone FROM users u 
                JOIN roles r ON r.id = u.role_id 
                WHERE r.name = 'Resident' AND u.phone IS NOT NULL AND u.phone != ''
            ")->fetchAll(PDO::FETCH_COLUMN);
        } elseif ($recipientType === 'staff') {
            $numbers = $db->query("
                SELECT phone FROM users u 
                JOIN roles r ON r.id = u.role_id 
                WHERE r.name != 'Resident' AND r.name != 'Super Admin' AND u.phone IS NOT NULL AND u.phone != ''
            ")->fetchAll(PDO::FETCH_COLUMN);
        } elseif ($recipientType === 'technicians') {
            $numbers = $db->query("
                SELECT phone FROM users u 
                JOIN roles r ON r.id = u.role_id 
                WHERE r.name = 'Technician' AND u.phone IS NOT NULL AND u.phone != ''
            ")->fetchAll(PDO::FETCH_COLUMN);
        } elseif ($recipientType === 'custom') {
            if (empty($customNumber)) {
                $_SESSION['error_message'] = "Custom phone number is required.";
                $this->redirect('/admin/broadcast');
                return;
            }
            $numbers = [$customNumber];
        }

        $recipientCount = count($numbers);
        if ($recipientCount === 0) {
            $_SESSION['error_message'] = "No valid phone numbers found for the selected recipient group.";
            $this->redirect('/admin/broadcast');
            return;
        }

        // 3. Balance verification
        if ($muni['sms_balance'] < $recipientCount) {
            $_SESSION['error_message'] = "Insufficient SMS credits. Required: $recipientCount, Available: " . $muni['sms_balance'] . ". Please contact system sales to purchase additional credits.";
            $this->redirect('/admin/broadcast');
            return;
        }

        // 4. Batch dispatch simulation & logging
        $stmtSms = $core->prepare("
            INSERT INTO sms_gateway_logs (municipality_id, recipient, message, provider, status, cost, profit)
            VALUES (?, ?, ?, ?, 'delivered', 0.25, 0.10)
        ");

        foreach ($numbers as $phone) {
            $stmtSms->execute([
                $muni['id'],
                $phone,
                $messageText,
                $provider
            ]);
        }

        // 5. Deduct Balance
        $stmtDeduct = $core->prepare("UPDATE municipalities SET sms_balance = sms_balance - ? WHERE id = ?");
        $stmtDeduct->execute([$recipientCount, $muni['id']]);

        // Audit Log
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Dispatched a $provider broadcast to $recipientCount numbers. Target group: $recipientType.");

        $_SESSION['success_message'] = "Broadcast successfully queued and dispatched to $recipientCount numbers via the $provider gateway.";
        $this->redirect('/admin/broadcast');
    }
}
