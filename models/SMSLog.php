<?php
/**
 * DorpFlow ERP - SMS Gateway Database Model (Core Context)
 */

require_once ROOT_PATH . '/core/Model.php';

class SMSLog extends Model {
    protected $table = 'sms_gateway_logs';

    public function __construct() {
        // SMS logs are stored globally in Core Database
        $this->db = Database::getCoreConnection();
    }

    /**
     * Get logs for a specific tenant municipality
     */
    public function getByMunicipality($municipalityId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE municipality_id = ? ORDER BY created_at DESC");
        $stmt->execute([$municipalityId]);
        return $stmt->fetchAll();
    }
}
