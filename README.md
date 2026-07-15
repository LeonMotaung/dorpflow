# 🏛️ DorpFlow ERP

<p align="center">
  <img src="dorpflow.png" alt="DorpFlow Logo" width="280">
</p>

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue.svg)](#)
[![Database](https://img.shields.io/badge/Database-MySQL-orange.svg)](#)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](#)

DorpFlow is a premium, high-fidelity **Multi-Tenant SaaS ERP platform** designed for South African Municipalities to streamline service delivery, track utility billing, verify supply chain compliance, automate fault-reporting, and maintain global accountability audits.

---

## 🚀 Key Modules & Features

### 1. 🔑 Security & Authentication
*   **Role-Based Access Control (RBAC):** Strict segregation of duties across roles: *Super Admin, Municipality Administrator, Department Manager, Supervisor, Call Centre Agent, Receptionist, Technician, and Resident*.
*   **Two-Factor Multi-Factor Authentication (MFA/2FA):** Automatic OTP validation flow for administrative municipal staff logins.

### 2. 📡 IoT Smart Meter & Billing Engine
*   **Telemetry Console:** Real-time water and electricity consumption dashboard.
*   **Automatic Bill Generation:** Auto-calculates utility tariffs and generates monthly invoices.
*   **Peach Payments Integration:** Ready-to-go payment gateways for residents to settle invoices securely.

### 3. 💬 WhatsApp Fault-Reporting Bot Simulator
*   **Conversational Reporting:** Simulator replicating a WhatsApp chat interface for citizen fault reporting.
*   **NLP Categorization:** Automatically parses incoming messages for keywords (*water leak, outage, pothole*) to route reports to appropriate municipal departments.
*   **Instant Ticket Creation:** Automatically generates municipal service tickets and provides real-time trackable reference numbers.

### 4. 🔗 Supply Chain Management (SCM) CSD Registry
*   **National Treasury CSD Integration:** Panel to verify and manage local suppliers.
*   **B-BBEE Score Meter:** Dynamic visual representation of suppliers' B-BBEE levels.
*   **Compliance & Restrictions Tracker:** Flags non-compliant or restricted businesses to prevent irregular SCM expenditures.

### 5. 🛡️ Auditor-General (AG) System Audit Console
*   **SaaS Audit Ledger:** Core platform-wide ledger logging sensitive administrator actions, IP addresses, and actions.
*   **Multi-Format Export:** Landscape PDF and BOM-encoded CSV exports matching Auditor-General requirements.
*   **Tenancy Filter:** Allows filtering across distinct municipal subdomains.

---

## 📂 Codebase Directory Layout

```text
dorpflow/
├── config/             # Database and core platform configuration templates
├── controllers/        # Business logic controllers (Exporter, SCM, SmartOps, Auth, etc.)
├── core/               # Custom MVC core routing framework, Auth helper, Database factory, FPDF
├── db/                 # Database schema blueprints (Core & Tenant templates)
├── models/             # Database queries and schema definitions (User, Ticket, AuditLog, etc.)
├── public/             # Publicly accessible directory (CSS, JS, entry Router index.php)
├── views/              # Front-end templates (Super Admin, Resident dashboard, WhatsApp simulator)
├── setup.php           # Automated database initialization and tenancy seeding script
└── README.md           # Documentation
```

---

## 🛠️ Installation & Setup

### Prerequisites
*   **XAMPP / WampServer** (PHP 8.0+ & MySQL/MariaDB)
*   **Apache Rewrite Module** enabled for core MVC routing.

### Step-by-Step Installation

1.  **Clone the Project:**
    Clone the codebase into your local web root (e.g. `C:\xampp\htdocs\dorpflow`):
    ```bash
    git clone https://github.com/LeonMotaung/dorpflow.git
    ```

2.  **Configure Virtual Hosts / Subdomains:**
    To support multi-tenancy subdomains (e.g., `stellenbosch.dorpflow.local` and `tshwane.dorpflow.local`), map the local domains in your hosts file:
    ```text
    # Windows: C:\Windows\System32\drivers\etc\hosts
    127.0.0.1   dorpflow.local
    127.0.0.1   stellenbosch.dorpflow.local
    127.0.0.1   tshwane.dorpflow.local
    ```

3.  **Database Initialization:**
    Run the automated configuration and builder script to initialize the core database and seed template databases:
    *   **Option A (CLI):**
        ```bash
        php setup.php
        ```
    *   **Option B (Web):**
        Navigate to `http://dorpflow.local/setup.php` in your browser.

---

## 💡 Architecture & Multi-Tenancy

DorpFlow uses a **database-per-tenant isolation architecture** to secure citizen and financial records.
*   **Core Context Database (`dorpflow_core`):** Stores SaaS global variables, active municipality subdomains, subscriptions, API access, and transaction/SMS logs.
*   **Tenant Databases (`dorpflow_tenant_*`):** Distinct, isolated databases dynamically resolved at request time through subdomain hostnames using `core/Database.php`.
