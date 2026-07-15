<?php
/**
 * DorpFlow ERP - Municipal Fleet Database Model
 */

require_once ROOT_PATH . '/core/Model.php';

class Fleet extends Model {
    protected $table = 'fleet';

    /**
     * Fetch fleet inventory with driver names
     */
    public function getFleet() {
        $sql = "
            SELECT f.*, u.full_name as driver_name 
            FROM fleet f
            LEFT JOIN users u ON u.id = f.driver_id
            ORDER BY f.vehicle_number ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}
