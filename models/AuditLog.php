<?php
/**
 * DorpFlow ERP - Security Transaction Audit Model
 */

require_once ROOT_PATH . '/core/Model.php';

class AuditLog extends Model {
    protected $table = 'audit_logs';

    /**
     * Log a security or user action in tenant database
     */
    public function log($userId, $action) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $this->db->prepare("
            INSERT INTO audit_logs (user_id, action, ip_address)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$userId, $action, $ip]);
    }

    /**
     * Get recent logs joined with user names
     */
    public function getRecent($limit = 50) {
        $sql = "
            SELECT a.*, u.full_name, r.name as role_name
            FROM audit_logs a
            LEFT JOIN users u ON u.id = a.user_id
            LEFT JOIN roles r ON r.id = u.role_id
            ORDER BY a.created_at DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
