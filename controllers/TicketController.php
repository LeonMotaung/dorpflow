<?php
/**
 * DorpFlow ERP - Ticket and Service Request Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/Ticket.php';
require_once ROOT_PATH . '/models/User.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class TicketController extends Controller {

    /**
     * Show list of all tickets for department/municipality admin
     */
    public function index() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor', 'Call Centre Agent']);
        
        $ticketModel = new Ticket();
        $tickets = $ticketModel->getDetailedList();

        $this->render('tickets/list', [
            'title' => 'Service Requests | DorpFlow',
            'tickets' => $tickets
        ]);
    }

    /**
     * View detailed ticket info
     */
    public function view($params) {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor', 'Technician', 'Resident']);
        
        $id = $params['id'];
        $ticketModel = new Ticket();
        $ticket = $ticketModel->getDetails($id);

        if (!$ticket) {
            die("Ticket #$id not found.");
        }

        // Get technicians list for dropdown (if role is Supervisor/Manager)
        $userModel = new User();
        $technicians = $userModel->getByRole('Technician');

        // Fetch comments and history log
        $includeInternal = Auth::check(['Municipality Administrator', 'Department Manager', 'Supervisor', 'Technician']);
        $comments = $ticketModel->getComments($id, $includeInternal);
        $timeline = $ticketModel->getTimeline($id);

        $this->render('tickets/view', [
            'title' => "Ticket {$ticket['ticket_number']} | DorpFlow",
            'ticket' => $ticket,
            'technicians' => $technicians,
            'comments' => $comments,
            'timeline' => $timeline,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Show create ticket form
     */
    public function showCreate() {
        Auth::requireRole(['Resident', 'Call Centre Agent', 'Supervisor', 'Municipality Administrator']);
        
        $this->render('tickets/create', [
            'title' => 'Log New Service Request | DorpFlow',
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process new ticket submission POST
     */
    public function processCreate() {
        $this->validateCsrf();
        Auth::requireRole(['Resident', 'Call Centre Agent', 'Supervisor', 'Municipality Administrator']);

        $category = $_POST['category'] ?? '';
        $priority = $_POST['priority'] ?? 'Medium';
        $description = $_POST['description'] ?? '';
        $lat = $_POST['lat'] ?? NULL;
        $lng = $_POST['lng'] ?? NULL;
        $ward = $_POST['ward'] ?? 1;

        if (empty($category) || empty($description)) {
            $this->render('tickets/create', ['error' => 'Category and Description are required.']);
            return;
        }

        $ticketModel = new Ticket();
        $ticketNum = strtoupper(substr($category, 0, 3)) . '-' . rand(1000, 9999);
        
        $data = [
            'ticket_number' => $ticketNum,
            'reporter_id' => $_SESSION['user_id'],
            'category' => $category,
            'description' => $description,
            'priority' => $priority,
            'ward_id' => $ward,
            'lat' => $lat,
            'lng' => $lng,
            'status' => 'Pending Review'
        ];

        $ticketId = $ticketModel->insert($data);

        // Log history
        $ticketModel->logHistory($ticketId, $_SESSION['user_id'], 'Logged request', NULL, 'Pending Review');

        // Log audit
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Created ticket $ticketNum");

        $this->redirect("/tickets/view/$ticketId");
    }

    /**
     * Process ticket dispatching (Assigning Dept/Tech/Escalation)
     */
    public function updateAssign() {
        $this->validateCsrf();
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);

        $ticketId = $_POST['ticket_id'] ?? '';
        $techId = $_POST['technician_id'] ?? NULL;
        $status = $_POST['status'] ?? 'Assigned';

        $ticketModel = new Ticket();
        $ticket = $ticketModel->find($ticketId);

        if ($ticket) {
            $ticketModel->update($ticketId, [
                'technician_id' => $techId,
                'status' => $status
            ]);

            // Log history
            $ticketModel->logHistory($ticketId, $_SESSION['user_id'], "Assigned technician and updated status", $ticket['status'], $status);

            // Log audit
            $audit = new AuditLog();
            $audit->log($_SESSION['user_id'], "Dispatched ticket ID $ticketId to technician ID $techId");
        }

        $this->redirect("/tickets/view/$ticketId");
    }

    /**
     * Add comment to ticket
     */
    public function comment() {
        $this->validateCsrf();
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor', 'Technician', 'Resident']);

        $ticketId = $_POST['ticket_id'] ?? '';
        $message = $_POST['message'] ?? '';
        $isInternal = isset($_POST['is_internal']) ? 1 : 0;

        if (!empty($message)) {
            $ticketModel = new Ticket();
            $ticketModel->addComment($ticketId, $_SESSION['user_id'], $message, $isInternal);
        }

        $this->redirect("/tickets/view/$ticketId");
    }
}
