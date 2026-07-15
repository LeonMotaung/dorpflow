<?php
/**
 * DorpFlow ERP - Municipality Database Model (Core Context)
 */

require_once ROOT_PATH . '/core/Model.php';

class Municipality extends Model {
    protected $table = 'municipalities';

    public function __construct() {
        // Municipalities are tracked in the Core Database
        $this->db = Database::getCoreConnection();
    }

    /**
     * Get municipality configuration details from its subdomain
     */
    public function getBySubdomain($subdomain) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE subdomain = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$subdomain]);
        return $stmt->fetch();
    }

    /**
     * Fetch billing information and usage rates
     */
    public function getBillingOverview() {
        $sql = "SELECT m.id, m.name, m.subdomain, s.plan, s.price as sub_price, 
                (SELECT COUNT(*) FROM sms_gateway_logs WHERE municipality_id = m.id) as sms_sent,
                (SELECT COUNT(*) FROM email_gateway_logs WHERE municipality_id = m.id) as emails_sent,
                m.storage_used_mb, m.storage_limit_mb
                FROM {$this->table} m
                JOIN subscriptions s ON s.municipality_id = m.id";
        return $this->db->query($sql)->fetchAll();
    }
}
