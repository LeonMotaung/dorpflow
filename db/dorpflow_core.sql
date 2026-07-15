-- ==========================================================
-- DorpFlow Core Database Schema
-- Manages SaaS metadata, tenant mappings, and gateway logs
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `dorpflow_core` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dorpflow_core`;

-- 1. Municipalities Table (Tenant Registries)
CREATE TABLE `municipalities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `subdomain` VARCHAR(50) NOT NULL UNIQUE,
    `db_name` VARCHAR(50) NOT NULL UNIQUE,
    `status` VARCHAR(20) DEFAULT 'active',
    `api_key` VARCHAR(64) NOT NULL UNIQUE,
    `api_secret` VARCHAR(64) NOT NULL,
    `sms_balance` INT DEFAULT 5000,
    `email_balance` INT DEFAULT 20000,
    `storage_limit_mb` INT DEFAULT 5000,
    `storage_used_mb` INT DEFAULT 120,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Subscriptions Ledger
CREATE TABLE `subscriptions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `municipality_id` INT NOT NULL,
    `plan` VARCHAR(20) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `billing_cycle` VARCHAR(20) DEFAULT 'monthly',
    `status` VARCHAR(20) DEFAULT 'active',
    `next_billing_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Core SMS Gateway Transaction Tracker
CREATE TABLE `sms_gateway_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `municipality_id` INT NOT NULL,
    `recipient` VARCHAR(20) NOT NULL,
    `message` TEXT NOT NULL,
    `provider` VARCHAR(20) DEFAULT 'DorpFlow-SMS',
    `status` VARCHAR(20) DEFAULT 'delivered',
    `cost` DECIMAL(4,2) DEFAULT 0.25,
    `profit` DECIMAL(4,2) DEFAULT 0.10,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Core Email Gateway Transaction Tracker
CREATE TABLE `email_gateway_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `municipality_id` INT NOT NULL,
    `recipient` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'delivered',
    `open_count` INT DEFAULT 0,
    `bounced` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Global SaaS Audit Ledger
CREATE TABLE `global_audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `action` VARCHAR(100) NOT NULL,
    `user_type` VARCHAR(50) NOT NULL,
    `details` TEXT,
    `ip_address` VARCHAR(45),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
