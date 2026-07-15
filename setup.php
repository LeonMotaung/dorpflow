<?php
/**
 * DorpFlow ERP - Database Auto-Setup and Seeding Script
 * Run this script to create the core and tenant databases with high-fidelity mock data.
 */
header('Content-Type: text/plain; charset=utf-8');

$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    // 1. Initial connection without database
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "==================================================\n";
    echo "       DORPFLOW ERP DATABASE AUTO-SETUP            \n";
    echo "==================================================\n\n";

    // 2. Drop and Recreate Core Database
    echo "[*] Creating Core Database: dorpflow_core...\n";
    $pdo->exec("DROP DATABASE IF EXISTS dorpflow_core");
    $pdo->exec("CREATE DATABASE dorpflow_core CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE dorpflow_core");
    
    // Core Tables Schema
    $coreSchema = "
    CREATE TABLE municipalities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        subdomain VARCHAR(50) NOT NULL UNIQUE,
        db_name VARCHAR(50) NOT NULL UNIQUE,
        status VARCHAR(20) DEFAULT 'active',
        api_key VARCHAR(64) NOT NULL UNIQUE,
        api_secret VARCHAR(64) NOT NULL,
        sms_balance INT DEFAULT 5000,
        email_balance INT DEFAULT 20000,
        storage_limit_mb INT DEFAULT 5000,
        storage_used_mb INT DEFAULT 120,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE subscriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        municipality_id INT NOT NULL,
        plan VARCHAR(20) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        billing_cycle VARCHAR(20) DEFAULT 'monthly',
        status VARCHAR(20) DEFAULT 'active',
        next_billing_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE sms_gateway_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        municipality_id INT NOT NULL,
        recipient VARCHAR(20) NOT NULL,
        message TEXT NOT NULL,
        provider VARCHAR(20) DEFAULT 'DorpFlow-SMS',
        status VARCHAR(20) DEFAULT 'delivered',
        cost DECIMAL(4,2) DEFAULT 0.25,
        profit DECIMAL(4,2) DEFAULT 0.10,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE email_gateway_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        municipality_id INT NOT NULL,
        recipient VARCHAR(100) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        status VARCHAR(20) DEFAULT 'delivered',
        open_count INT DEFAULT 0,
        bounced TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE global_audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        action VARCHAR(100) NOT NULL,
        user_type VARCHAR(50) NOT NULL,
        details TEXT,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE api_requests_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        municipality_id INT NOT NULL,
        endpoint VARCHAR(100) NOT NULL,
        method VARCHAR(10) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        response_code INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (municipality_id) REFERENCES municipalities(id) ON DELETE CASCADE
    );";
    
    $pdo->exec($coreSchema);
    echo "[+] Core Schema created successfully.\n";

    // Core Data seeding removed to start with a clean platform state (0 active councils, 0 ARR, 0 SMS, etc.)
    echo "[+] Core database initialized (clean state).\n\n";

    // 3. Create Tenant Databases
    $tenants = [
        'dorpflow_tenant_stellenbosch' => 'Stellenbosch',
        'dorpflow_tenant_tshwane' => 'Tshwane'
    ];

    foreach ($tenants as $dbName => $displayName) {
        echo "[*] Creating Tenant Database: $dbName ($displayName)...\n";
        $pdo->exec("DROP DATABASE IF EXISTS $dbName");
        $pdo->exec("CREATE DATABASE $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE $dbName");

        // Tenant Schema
        $tenantSchema = "
        CREATE TABLE roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            description VARCHAR(200)
        );

        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_id INT NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            two_fa_secret VARCHAR(32) DEFAULT NULL,
            is_locked TINYINT(1) DEFAULT 0,
            failed_logins INT DEFAULT 0,
            remember_token VARCHAR(64) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            manager_id INT DEFAULT NULL,
            budget DECIMAL(12,2) DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE Wards (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ward_number INT NOT NULL UNIQUE,
            councilor_name VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE assets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            type VARCHAR(50) NOT NULL,
            qr_code VARCHAR(100) UNIQUE,
            maintenance_schedule VARCHAR(50) DEFAULT 'monthly',
            lat DECIMAL(10,8),
            lng DECIMAL(11,8),
            status VARCHAR(20) DEFAULT 'operational',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE fleet (
            id INT AUTO_INCREMENT PRIMARY KEY,
            vehicle_number VARCHAR(20) NOT NULL UNIQUE,
            model VARCHAR(50) NOT NULL,
            driver_id INT DEFAULT NULL,
            fuel_log TEXT,
            mileage INT DEFAULT 0,
            next_service_date DATE,
            status VARCHAR(20) DEFAULT 'active'
        );

        CREATE TABLE tickets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ticket_number VARCHAR(20) NOT NULL UNIQUE,
            reporter_id INT DEFAULT NULL,
            category VARCHAR(50) NOT NULL,
            description TEXT NOT NULL,
            status VARCHAR(30) DEFAULT 'Pending Review',
            priority VARCHAR(20) DEFAULT 'Medium',
            department_id INT DEFAULT NULL,
            technician_id INT DEFAULT NULL,
            ward_id INT DEFAULT NULL,
            lat DECIMAL(10,8) DEFAULT NULL,
            lng DECIMAL(11,8) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );

        CREATE TABLE ticket_comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ticket_id INT NOT NULL,
            user_id INT NOT NULL,
            message TEXT NOT NULL,
            is_internal TINYINT(1) DEFAULT 0,
            file_path VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE ticket_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ticket_id INT NOT NULL,
            user_id INT NOT NULL,
            action VARCHAR(100) NOT NULL,
            state_before VARCHAR(30) DEFAULT NULL,
            state_after VARCHAR(30) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT DEFAULT NULL,
            action VARCHAR(200) NOT NULL,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE iot_telemetry (
            id INT AUTO_INCREMENT PRIMARY KEY,
            meter_number VARCHAR(50) NOT NULL,
            type VARCHAR(20) NOT NULL,
            reading DECIMAL(10,2) NOT NULL,
            cost DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'unpaid',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE csd_suppliers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            supplier_number VARCHAR(30) NOT NULL UNIQUE,
            company_name VARCHAR(100) NOT NULL,
            tax_status VARCHAR(20) DEFAULT 'Compliant',
            restricted TINYINT(1) DEFAULT 0,
            bee_level INT DEFAULT 1,
            last_verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";

        $pdo->exec($tenantSchema);
        echo "[+] $displayName Schema created successfully.\n";

        // Seed Roles
        $roles = [
            ['Super Admin', 'Full access control over everything'],
            ['Municipality Administrator', 'Controls municipality departments and configuration'],
            ['Department Manager', 'Manages department workflows, budgets, and technicians'],
            ['Supervisor', 'Verifies citizen tickets and schedules dispatches'],
            ['Call Centre Agent', 'Logs and routes reports received via phone/walk-ins'],
            ['Receptionist', 'Captures walk-in citizen inquiries'],
            ['Technician', 'Field worker resolving maintenance and service delivery requests'],
            ['Municipal Employee', 'General municipal internal accounts'],
            ['Resident', 'Citizen reporting faults and tracking resolutions'],
            ['Guest', 'Unauthenticated dashboard viewer']
        ];
        
        $stmtRole = $pdo->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
        foreach ($roles as $r) {
            $stmtRole->execute($r);
        }

        // Fetch roles to link users
        $roleIds = $pdo->query("SELECT name, id FROM roles")->fetchAll(PDO::FETCH_KEY_PAIR);

        // Seed Users
        $hash = password_hash('password', PASSWORD_DEFAULT);
        $stmtUser = $pdo->prepare("INSERT INTO users (role_id, email, password_hash, full_name, phone) VALUES (?, ?, ?, ?, ?)");
        
        // Admin
        $stmtUser->execute([$roleIds['Municipality Administrator'], 'admin@' . strtolower($displayName) . '.gov.za', $hash, "$displayName Admin", '0125550101']);
        
        // Manager
        $stmtUser->execute([$roleIds['Department Manager'], 'manager@' . strtolower($displayName) . '.gov.za', $hash, "Elena Ndlovu", '0125550102']);
        $mgrId = $pdo->lastInsertId();

        // Supervisor
        $stmtUser->execute([$roleIds['Supervisor'], 'supervisor@' . strtolower($displayName) . '.gov.za', $hash, "Thabo Molefe", '0125550103']);
        
        // Technicians
        $stmtUser->execute([$roleIds['Technician'], 'tech1@' . strtolower($displayName) . '.gov.za', $hash, "Jaco Pieterse", '0825554321']);
        $techId1 = $pdo->lastInsertId();
        
        $stmtUser->execute([$roleIds['Technician'], 'tech2@' . strtolower($displayName) . '.gov.za', $hash, "Sipho Gumede", '0835556543']);
        $techId2 = $pdo->lastInsertId();

        // Resident
        $stmtUser->execute([$roleIds['Resident'], 'citizen@gmail.com', $hash, "Sarah Miller", '0715559876']);
        $resId = $pdo->lastInsertId();

        // Seed Departments
        $depts = [
            ['Water Department', $mgrId, 12000000.00],
            ['Electricity Department', NULL, 18500000.00],
            ['Roads & Stormwater', NULL, 9000000.00],
            ['Waste Management', NULL, 4500000.00],
            ['Fleet Workshop', NULL, 2500000.00],
            ['IT Helpdesk', NULL, 1500000.00]
        ];
        $stmtDept = $pdo->prepare("INSERT INTO departments (name, manager_id, budget) VALUES (?, ?, ?)");
        foreach ($depts as $d) {
            $stmtDept->execute($d);
        }
        $deptIds = $pdo->query("SELECT name, id FROM departments")->fetchAll(PDO::FETCH_KEY_PAIR);

        // Seed Wards
        $wards = [
            [1, 'Cllr. Pieter Van Der Merwe'],
            [2, 'Cllr. Naledi Radebe'],
            [3, 'Cllr. Yusuf Patel'],
            [14, 'Cllr. Sarah Miller (Acting)']
        ];
        $stmtWard = $pdo->prepare("INSERT INTO Wards (ward_number, councilor_name) VALUES (?, ?)");
        foreach ($wards as $w) {
            $stmtWard->execute($w);
        }
        $wardIds = $pdo->query("SELECT ward_number, id FROM Wards")->fetchAll(PDO::FETCH_KEY_PAIR);

        // Seed Assets
        $assets = [
            [$deptIds['Water Department'], 'Main Water Pump A', 'Water Pump', 'QR-WAT-A01', 'monthly', -33.9321, 18.8602],
            [$deptIds['Water Department'], 'Substation Reserve Pump B', 'Water Pump', 'QR-WAT-B02', 'weekly', -33.9355, 18.8688],
            [$deptIds['Electricity Department'], 'Zone 4 Transformer', 'Transformer', 'QR-ELE-T04', 'monthly', -33.9411, 18.8542],
            [$deptIds['Electricity Department'], 'Hatfield Streetlight Grid C', 'Street Lights', 'QR-ELE-L20', 'annual', -33.9298, 18.8711]
        ];
        $stmtAsset = $pdo->prepare("INSERT INTO assets (department_id, name, type, qr_code, maintenance_schedule, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($assets as $a) {
            $stmtAsset->execute($a);
        }

        // Seed Fleet
        $fleet = [
            ['STE-WAT-01GP', 'Toyota Hilux 2.4 GD-6', $techId1, 'Log: 12L fuel added on 05 July', 145000, date('Y-m-d', strtotime('+15 days'))],
            ['STE-ELE-02GP', 'Isuzu D-Max Cherry Picker', $techId2, 'Log: Serviced on 12 June', 89000, date('Y-m-d', strtotime('+3 days'))]
        ];
        $stmtFleet = $pdo->prepare("INSERT INTO fleet (vehicle_number, model, driver_id, fuel_log, mileage, next_service_date) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($fleet as $f) {
            $stmtFleet->execute($f);
        }

        // Seed Tickets
        $tickets = [
            ['WAT-3921', $resId, 'Water', 'Large water leak spraying on corner of Church St. Asset is flooding nearby verge.', 'Pending Review', 'Medium', $deptIds['Water Department'], NULL, $wardIds[1], -33.9312, 18.8615],
            ['ELE-4011', $resId, 'Electricity', 'Complete power outage affecting Block C. Transformer sparks seen.', 'In Progress', 'High', $deptIds['Electricity Department'], $techId2, $wardIds[2], -33.9410, 18.8540],
            ['RDS-1022', NULL, 'Roads & Stormwater', 'Deep pothole reported in middle of lanes. High risk of tire damage.', 'Completed', 'Critical', $deptIds['Roads & Stormwater'], $techId1, $wardIds[14], -33.9288, 18.8715]
        ];
        $stmtTicket = $pdo->prepare("INSERT INTO tickets (ticket_number, reporter_id, category, description, status, priority, department_id, technician_id, ward_id, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($tickets as $t) {
            $stmtTicket->execute($t);
        }
        $ticketIds = $pdo->query("SELECT ticket_number, id FROM tickets")->fetchAll(PDO::FETCH_KEY_PAIR);

        // Seed Ticket Comments
        $stmtComm = $pdo->prepare("INSERT INTO ticket_comments (ticket_id, user_id, message, is_internal) VALUES (?, ?, ?, ?)");
        $stmtComm->execute([$ticketIds['ELE-4011'], $mgrId, 'Checked grid monitoring log. Voltage spike recorded prior to failure.', 1]);
        $stmtComm->execute([$ticketIds['ELE-4011'], $techId2, 'Arrived on scene. Initiating testing on primary insulator coils.', 0]);

        // Seed Ticket History
        $stmtHist = $pdo->prepare("INSERT INTO ticket_history (ticket_id, user_id, action, state_before, state_after) VALUES (?, ?, ?, ?, ?)");
        $stmtHist->execute([$ticketIds['WAT-3921'], $resId, 'Created ticket via Citizen Portal', NULL, 'Pending Review']);
        $stmtHist->execute([$ticketIds['ELE-4011'], $mgrId, 'Assigned technician and escalated priority', 'Pending Review', 'In Progress']);

        // Seed IoT Telemetry
        $meters = [
            ['MTR-WAT-908231', 'water', 450.20, 450.20 * 12.50, 'unpaid'],
            ['MTR-WAT-881203', 'water', 120.50, 120.50 * 12.50, 'paid'],
            ['MTR-ELE-449102', 'electricity', 890.00, 890.00 * 2.80, 'unpaid'],
            ['MTR-ELE-301982', 'electricity', 320.00, 320.00 * 2.80, 'paid']
        ];
        $stmtIot = $pdo->prepare("INSERT INTO iot_telemetry (meter_number, type, reading, cost, status) VALUES (?, ?, ?, ?, ?)");
        foreach ($meters as $m) {
            $stmtIot->execute($m);
        }

        // Seed CSD Suppliers
        $suppliers = [
            ['MAAA0182910', 'Vuka Infrastructure Solutions', 'Compliant', 0, 1],
            ['MAAA0291039', 'Mzanzi Water Works', 'Compliant', 0, 2],
            ['MAAA0092318', 'Kasi Electrical Outlets', 'Non-Compliant', 0, 3],
            ['MAAA0771822', 'RESTRICTED Corp South Africa', 'Compliant', 1, 4]
        ];
        $stmtSupplier = $pdo->prepare("INSERT INTO csd_suppliers (supplier_number, company_name, tax_status, restricted, bee_level) VALUES (?, ?, ?, ?, ?)");
        foreach ($suppliers as $s) {
            $stmtSupplier->execute($s);
        }

        echo "[+] $displayName seeded with active operations data.\n\n";
    }

    echo "==================================================\n";
    echo "       DATABASE BUILD AND SEED COMPLETED           \n";
    echo "==================================================\n";

} catch (PDOException $e) {
    echo "\n[ERROR] Setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
