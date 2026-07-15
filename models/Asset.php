<?php
/**
 * DorpFlow ERP - Infrastructure Asset Database Model
 */

require_once ROOT_PATH . '/core/Model.php';

class Asset extends Model {
    protected $table = 'assets';

    /**
     * Fetch assets with department names
     */
    public function getAssets() {
        $sql = "
            SELECT a.*, d.name as department_name 
            FROM assets a
            JOIN departments d ON d.id = a.department_id
            ORDER BY a.name ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}
