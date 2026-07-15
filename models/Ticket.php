<?php
/**
 * DorpFlow ERP - Ticket & Service Request Database Model
 */

require_once ROOT_PATH . '/core/Model.php';

class Ticket extends Model {
    protected $table = 'tickets';

    /**
     * Get tickets with complete joined department, technician, reporter, and ward details
     */
    public function getDetailedList($conditions = '', $params = []) {
        $sql = "
            SELECT t.*, 
                   d.name as department_name, 
                   u.full_name as technician_name, 
                   rep.full_name as reporter_name,
                   w.ward_number, w.councilor_name
            FROM tickets t
            LEFT JOIN departments d ON d.id = t.department_id
            LEFT JOIN users u ON u.id = t.technician_id
            LEFT JOIN users rep ON rep.id = t.reporter_id
            LEFT JOIN Wards w ON w.id = t.ward_id
        ";
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . $conditions;
        }
        
        $sql .= " ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch ticket details for view page
     */
    public function getDetails($id) {
        $list = $this->getDetailedList("t.id = ?", [$id]);
        return $list ? $list[0] : null;
    }

    /**
     * Get ticket logs timeline
     */
    public function getTimeline($ticketId) {
        $stmt = $this->db->prepare("
            SELECT h.*, u.full_name, r.name as role_name
            FROM ticket_history h
            JOIN users u ON u.id = h.user_id
            JOIN roles r ON r.id = u.role_id
            WHERE h.ticket_id = ?
            ORDER BY h.created_at ASC
        ");
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll();
    }

    /**
     * Get public & internal comments
     */
    public function getComments($ticketId, $includeInternal = false) {
        $sql = "
            SELECT c.*, u.full_name, r.name as role_name
            FROM ticket_comments c
            JOIN users u ON u.id = c.user_id
            JOIN roles r ON r.id = u.role_id
            WHERE c.ticket_id = ?
        ";
        if (!$includeInternal) {
            $sql .= " AND c.is_internal = 0";
        }
        $sql .= " ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll();
    }

    /**
     * Log history event
     */
    public function logHistory($ticketId, $userId, $action, $before = null, $after = null) {
        $stmt = $this->db->prepare("
            INSERT INTO ticket_history (ticket_id, user_id, action, state_before, state_after)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$ticketId, $userId, $action, $before, $after]);
    }

    /**
     * Add comment
     */
    public function addComment($ticketId, $userId, $message, $isInternal = 0, $filePath = null) {
        $stmt = $this->db->prepare("
            INSERT INTO ticket_comments (ticket_id, user_id, message, is_internal, file_path)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$ticketId, $userId, $message, $isInternal, $filePath]);
    }
}
