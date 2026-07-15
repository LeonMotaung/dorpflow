-- ==========================================================
-- DorpFlow Tenant Template Database Schema
-- Run this schema for each isolated municipality database
-- ==========================================================

-- 1. Roles Table (Role-Based Access Control)
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(200)
) ENGINE=InnoDB;

-- 2. Users Table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `two_fa_secret` VARCHAR(32) DEFAULT NULL,
    `is_locked` TINYINT(1) DEFAULT 0,
    `failed_logins` INT DEFAULT 0,
    `remember_token` VARCHAR(64) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB;

-- 3. Departments Table
CREATE TABLE `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `manager_id` INT DEFAULT NULL,
    `budget` DECIMAL(12,2) DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. GIS Wards boundaries representation
CREATE TABLE `Wards` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ward_number` INT NOT NULL UNIQUE,
    `councilor_name` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Infrastructure Assets Management
CREATE TABLE `assets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `department_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `qr_code` VARCHAR(100) UNIQUE,
    `maintenance_schedule` VARCHAR(50) DEFAULT 'monthly',
    `lat` DECIMAL(10,8),
    `lng` DECIMAL(11,8),
    `status` VARCHAR(20) DEFAULT 'operational',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Municipal Fleet vehicle tracker
CREATE TABLE `fleet` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `vehicle_number` VARCHAR(20) NOT NULL UNIQUE,
    `model` VARCHAR(50) NOT NULL,
    `driver_id` INT DEFAULT NULL,
    `fuel_log` TEXT,
    `mileage` INT DEFAULT 0,
    `next_service_date` DATE,
    `status` VARCHAR(20) DEFAULT 'active',
    FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. Service Tickets Table (Complaints & Work Orders)
CREATE TABLE `tickets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_number` VARCHAR(20) NOT NULL UNIQUE,
    `reporter_id` INT DEFAULT NULL,
    `category` VARCHAR(50) NOT NULL,
    `description` TEXT NOT NULL,
    `status` VARCHAR(30) DEFAULT 'Pending Review',
    `priority` VARCHAR(20) DEFAULT 'Medium',
    `department_id` INT DEFAULT NULL,
    `technician_id` INT DEFAULT NULL,
    `ward_id` INT DEFAULT NULL,
    `lat` DECIMAL(10,8) DEFAULT NULL,
    `lng` DECIMAL(11,8) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`ward_id`) REFERENCES `Wards` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 8. Ticket Comments & File Uploads
CREATE TABLE `ticket_comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `message` TEXT NOT NULL,
    `is_internal` TINYINT(1) DEFAULT 0,
    `file_path` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. Ticket Workflow History Logs
CREATE TABLE `ticket_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `state_before` VARCHAR(30) DEFAULT NULL,
    `state_after` VARCHAR(30) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 10. Local Operations Audit Trail
CREATE TABLE `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `action` VARCHAR(200) NOT NULL,
    `ip_address` VARCHAR(45),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 11. IoT Smart Telemetry Table
CREATE TABLE `iot_telemetry` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `meter_number` VARCHAR(50) NOT NULL,
    `type` VARCHAR(20) NOT NULL,
    `reading` DECIMAL(10,2) NOT NULL,
    `cost` DECIMAL(10,2) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'unpaid',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 12. SCM CSD Supplier Register
CREATE TABLE `csd_suppliers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `supplier_number` VARCHAR(30) NOT NULL UNIQUE,
    `company_name` VARCHAR(100) NOT NULL,
    `tax_status` VARCHAR(20) DEFAULT 'Compliant',
    `restricted` TINYINT(1) DEFAULT 0,
    `bee_level` INT DEFAULT 1,
    `last_verified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
