<?php
/**
 * DorpFlow ERP - Base Model class
 */

require_once ROOT_PATH . '/core/Database.php';

abstract class Model {
    protected $db;
    protected $table;

    public function __construct() {
        // Resolve target connection dynamically
        $this->db = Database::getConnection();
    }

    /**
     * Find a record by its primary ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get all records from a table
     */
    public function all($orderBy = 'id DESC') {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    /**
     * Delete a record by ID
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Insert a record dynamically
     */
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->db->lastInsertId();
    }

    /**
     * Update a record dynamically by ID
     */
    public function update($id, $data) {
        $fields = '';
        foreach (array_keys($data) as $column) {
            $fields .= "{$column} = ?, ";
        }
        $fields = rtrim($fields, ', ');

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        $values = array_values($data);
        $values[] = $id; // Append ID for where clause
        
        return $stmt->execute($values);
    }
}
