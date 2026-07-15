<?php
/**
 * DorpFlow ERP - User Database Model (Tenant Context)
 */

require_once ROOT_PATH . '/core/Model.php';

class User extends Model {
    protected $table = 'users';

    /**
     * Authenticate user credentials
     */
    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.email = ? LIMIT 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    /**
     * Fetch users by specific role categories (e.g. Technicians)
     */
    public function getByRole($roleName) {
        $stmt = $this->db->prepare("
            SELECT u.* 
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE r.name = ?
        ");
        $stmt->execute([$roleName]);
        return $stmt->fetchAll();
    }
}
