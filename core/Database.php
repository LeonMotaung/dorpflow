<?php
/**
 * DorpFlow ERP - Custom Dynamic Database Connection Factory
 */

class Database {
    private static $corePdo = null;
    private static $tenantPdo = null;

    /**
     * Get connections to the core global database
     */
    public static function getCoreConnection() {
        if (self::$corePdo !== null) {
            return self::$corePdo;
        }

        $config = require ROOT_PATH . '/config/database.php';
        $db = $config['core'];
        $dsn = "mysql:host={$db['host']};dbname={$db['db_name']};charset={$db['charset']}";
        
        try {
            self::$corePdo = new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false
            ]);
            return self::$corePdo;
        } catch (PDOException $e) {
            die("Core Connection failure: " . $e->getMessage());
        }
    }

    /**
     * Get dynamic connections to a specific tenant's database
     */
    public static function getTenantConnection($tenantDbName) {
        if (self::$tenantPdo !== null && self::$tenantPdo->query("SELECT DATABASE()")->fetchColumn() === $tenantDbName) {
            return self::$tenantPdo;
        }

        $config = require ROOT_PATH . '/config/database.php';
        $db = $config['tenant_template'];
        $dsn = "mysql:host={$db['host']};dbname={$tenantDbName};charset={$db['charset']}";
        
        try {
            self::$tenantPdo = new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false
            ]);
            return self::$tenantPdo;
        } catch (PDOException $e) {
            die("Tenant connection error database [$tenantDbName]: " . $e->getMessage());
        }
    }

    /**
     * Get active connection depending on context (Core vs Resolved Tenant)
     */
    public static function getConnection() {
        $tenant = getActiveTenant();
        if ($tenant === 'core') {
            return self::getCoreConnection();
        }

        // Fetch target database name from core
        $core = self::getCoreConnection();
        $stmt = $core->prepare("SELECT db_name FROM municipalities WHERE subdomain = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$tenant]);
        $dbName = $stmt->fetchColumn();
        
        if (!$dbName) {
            die("Resolved tenant [$tenant] does not exist or is deactivated by Super Admin.");
        }

        return self::getTenantConnection($dbName);
    }
}
