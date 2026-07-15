<?php
/**
 * DorpFlow ERP - Main Layout Header template
 */
$user = Auth::user();
$tenant = getActiveTenant();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'DorpFlow Operations Control Center'; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Leaflet Maps CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">

    <!-- Global Platform CSS overrides -->
    <style>
        :root {
            --primary: #0A2B4C;
            --primary-light: #1E3A5F;
            --secondary: #2E7D32;
            --accent: #00BFA5;
            --bg-color: #F8FAFC;
            --text-color: #1E293B;
            --border-color: #E2E8F0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* Dashboard Shell Sidebar Layout */
        .wrapper {
            display: flex;
            align-items: stretch;
            min-height: 100vh;
        }

        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: var(--primary);
            color: #fff;
            transition: all 0.3s;
            position: fixed;
            height: 100vh;
            z-index: 999;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #ffffff;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            text-align: center;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.95rem;
            display: block;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: var(--primary-light);
            border-left: 4px solid var(--accent);
        }

        #sidebar ul li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        #content {
            width: calc(100% - 260px);
            padding: 40px;
            min-height: 100vh;
            transition: all 0.3s;
            margin-left: 260px;
        }

        @media (max-width: 991px) {
            #sidebar {
                margin-left: -260px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
                margin-left: 0;
            }
            #content.active {
                margin-left: 260px;
                width: calc(100% - 260px);
            }
        }

        /* Top navbar */
        .top-navbar {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 15px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        }

        .muni-badge {
            background: rgba(0, 191, 165, 0.08);
            border: 1px solid rgba(0, 191, 165, 0.15);
            color: #00897b;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<?php if ($user): ?>
<div class="wrapper">
    <!-- SIDEBAR -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?php echo getMunicipalityLogo(); ?>" alt="Logo" style="max-height: 35px; border-radius: 6px;" class="mb-2">
            <span class="text-muted d-block" style="font-size: 0.7rem; letter-spacing: 0.05em; font-weight:800;">
                <?php echo strtoupper($tenant); ?> CONSOLE
            </span>
        </div>

        <ul class="list-unstyled components">
            <?php if ($user['role'] === 'Super Admin'): ?>
                <!-- Super Admin Side Nav -->
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/dashboard"><i class="fa-solid fa-gauge"></i> Global Dashboard</a></li>
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/municipalities"><i class="fa-solid fa-building-flag"></i> Municipalities</a></li>
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/subscriptions"><i class="fa-solid fa-credit-card"></i> Subscriptions</a></li>
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/sms-gateways"><i class="fa-solid fa-mobile-screen"></i> SMS Gateways</a></li>
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/email-gateways"><i class="fa-solid fa-envelope"></i> Email Gateways</a></li>
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/system-config"><i class="fa-solid fa-sliders"></i> System Config</a></li>
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/api-usage"><i class="fa-solid fa-chart-line"></i> API Usage Logs</a></li>
                <li><a href="<?php echo APP_URL; ?>/public/index.php/superadmin/ag-audit"><i class="fa-solid fa-file-shield"></i> AG Audit Export</a></li>
            <?php else: ?>
                <!-- Tenant Side Nav (Municipalities) -->
                <?php if (in_array($user['role'], ['Municipality Administrator', 'Department Manager', 'Supervisor', 'Call Centre Agent'])): ?>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/dashboard"><i class="fa-solid fa-gauge"></i> Operations Room</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/tickets"><i class="fa-solid fa-ticket"></i> Service Tickets</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/assets"><i class="fa-solid fa-boxes-stacked"></i> Assets Inventory</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/fleet"><i class="fa-solid fa-truck-pickup"></i> Fleet Manager</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/departments"><i class="fa-solid fa-building-user"></i> Departments</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/employees"><i class="fa-solid fa-users"></i> Employees</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/admin/iot-telemetry"><i class="fa-solid fa-gauge-high"></i> IoT Telemetry</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/admin/scm"><i class="fa-solid fa-building-columns"></i> SCM Suppliers</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/admin/whatsapp-bot"><i class="fa-brands fa-whatsapp"></i> WhatsApp Bot</a></li>
                    <?php if ($user['role'] === 'Municipality Administrator'): ?>
                        <li><a href="<?php echo APP_URL; ?>/public/index.php/admin/settings"><i class="fa-solid fa-sliders"></i> Muni Settings</a></li>
                        <li><a href="<?php echo APP_URL; ?>/public/index.php/admin/hr-payroll"><i class="fa-solid fa-hand-holding-dollar"></i> HR & Payroll</a></li>
                        <li><a href="<?php echo APP_URL; ?>/public/index.php/admin/hr-payroll/applications"><i class="fa-solid fa-file-invoice"></i> Job Applications</a></li>
                    <?php endif; ?>
                <?php elseif ($user['role'] === 'Technician'): ?>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/technician/dashboard"><i class="fa-solid fa-truck-pickup"></i> Today's Dispatches</a></li>
                <?php elseif ($user['role'] === 'Resident'): ?>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/resident/dashboard"><i class="fa-solid fa-house-user"></i> My Citizen Hub</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/tickets/create"><i class="fa-solid fa-circle-plus"></i> File New Ticket</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/resident/billing"><i class="fa-solid fa-receipt"></i> Utility Billing</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/status"><i class="fa-solid fa-tower-broadcast"></i> Service Status</a></li>
                    <li><a href="<?php echo APP_URL; ?>/public/index.php/resident/careers"><i class="fa-solid fa-graduation-cap"></i> Muni Careers</a></li>
                <?php endif; ?>
            <?php endif; ?>
            
            <li class="mt-5"><a href="<?php echo APP_URL; ?>/public/index.php/logout" class="text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out</a></li>
        </ul>
    </nav>

    <!-- CONTENT SHELL -->
    <div id="content">
        <!-- Top Nav Bar within content shell -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">
                Welcome back, <strong><?php echo $user['name']; ?></strong> 
                <span class="badge bg-secondary-subtle text-secondary ms-2" style="font-size:0.75rem;"><?php echo $user['role']; ?></span>
            </h5>
            <div class="d-flex align-items-center gap-3">
                <span class="muni-badge">
                    <i class="fa-solid fa-building me-1"></i> <?php echo ucfirst($tenant); ?> Metro
                </span>
            </div>
        </div>
<?php endif; ?>
