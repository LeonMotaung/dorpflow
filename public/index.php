<?php
/**
 * DorpFlow ERP - Main Entry and Framework Bootstrap Gateway
 */

// Load Configurations
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Auth.php';

// Instantiate Router
$router = new Router();

// 1. Auth Routing
$router->add('login', 'AuthController', 'showLogin', 'GET');
$router->add('login', 'AuthController', 'processLogin', 'POST');
$router->add('register', 'AuthController', 'showRegister', 'GET');
$router->add('register', 'AuthController', 'processRegister', 'POST');
$router->add('forgot-password', 'AuthController', 'showForgotPassword', 'GET');
$router->add('forgot-password', 'AuthController', 'processForgotPassword', 'POST');
$router->add('reset-password', 'AuthController', 'showResetPassword', 'GET');
$router->add('reset-password', 'AuthController', 'processResetPassword', 'POST');
$router->add('logout', 'AuthController', 'logout', 'GET');
$router->add('login-2fa', 'AuthController', 'show2fa', 'GET');
$router->add('login-2fa', 'AuthController', 'process2fa', 'POST');

// 2. Dashboard Routing
$router->add('dashboard', 'AuthController', 'showDashboard', 'GET');
$router->add('superadmin/dashboard', 'SuperAdminController', 'dashboard', 'GET');
$router->add('superadmin/municipalities', 'SuperAdminController', 'listMunicipalities', 'GET');
$router->add('superadmin/municipalities/create', 'SuperAdminController', 'createMunicipality', 'POST');
$router->add('superadmin/municipalities/delete', 'SuperAdminController', 'deleteMunicipality', 'POST');
$router->add('superadmin/municipalities/invoice', 'SuperAdminController', 'generateInvoice', 'GET');
$router->add('superadmin/subscriptions', 'SuperAdminController', 'listSubscriptions', 'GET');
$router->add('superadmin/sms-gateways', 'SuperAdminController', 'listSmsGateways', 'GET');
$router->add('superadmin/email-gateways', 'SuperAdminController', 'listEmailGateways', 'GET');
$router->add('superadmin/system-config', 'SuperAdminController', 'showSystemConfig', 'GET');
$router->add('superadmin/api-usage', 'SuperAdminController', 'listApiUsage', 'GET');
$router->add('resident/dashboard', 'AuthController', 'showDashboard', 'GET');
$router->add('resident/billing', 'BillingController', 'showResidentBilling', 'GET');
$router->add('resident/billing/checkout', 'BillingController', 'processPeachCheckout', 'POST');
$router->add('technician/dashboard', 'AuthController', 'showDashboard', 'GET');

// 3. Ticket Operations
$router->add('tickets', 'TicketController', 'index', 'GET');
$router->add('tickets/create', 'TicketController', 'showCreate', 'GET');
$router->add('tickets/create', 'TicketController', 'processCreate', 'POST');
$router->add('tickets/view/{id}', 'TicketController', 'view', 'GET');
$router->add('tickets/assign', 'TicketController', 'updateAssign', 'POST');
$router->add('tickets/comment', 'TicketController', 'comment', 'POST');

// 4. Assets & Fleet Register
$router->add('assets', 'AssetController', 'index', 'GET');
$router->add('fleet', 'FleetController', 'index', 'GET');

// 5. Tenant Administration (Departments & Users)
$router->add('departments', 'AdminController', 'listDepartments', 'GET');
$router->add('departments/create', 'AdminController', 'showCreateDepartment', 'GET');
$router->add('departments/create', 'AdminController', 'processCreateDepartment', 'POST');
$router->add('employees', 'AdminController', 'listEmployees', 'GET');
$router->add('employees/create', 'AdminController', 'showCreateEmployee', 'GET');
$router->add('employees/create', 'AdminController', 'processCreateEmployee', 'POST');
$router->add('admin/settings', 'AdminController', 'showSettings', 'GET');
$router->add('admin/settings/update', 'AdminController', 'updateSettings', 'POST');

// 5. REST API Gateways
$router->add('api/v1/tickets', 'APIController', 'getTickets', 'GET');
$router->add('api/v1/assets', 'APIController', 'getAssets', 'GET');
$router->add('api/v1/telemetry', 'APIController', 'postTelemetry', 'POST');
$router->add('api/v1/prepaid/purchase', 'APIController', 'purchasePrepaidToken', 'POST');
$router->add('api/v1/webhooks/whatsapp', 'APIController', 'whatsappWebhook', 'POST');
$router->add('api/v1/scm/verify', 'APIController', 'verifySupplier', 'GET');

// 6. Smart City Operations
$router->add('status', 'SmartOpsController', 'showPublicStatus', 'GET');
$router->add('admin/iot-telemetry', 'SmartOpsController', 'showIotTelemetry', 'GET');
$router->add('admin/iot-telemetry/generate-bills', 'SmartOpsController', 'generateBills', 'POST');
$router->add('admin/tickets/dispatch', 'SmartOpsController', 'dispatchTechnician', 'POST');
$router->add('admin/loadshedding/simulate', 'SmartOpsController', 'simulateLoadsheddingAlert', 'POST');

// 7. SCM CSD Supplier Register & WhatsApp Bot
$router->add('admin/scm', 'SCMController', 'showScm', 'GET');
$router->add('admin/scm/add', 'SCMController', 'addSupplier', 'POST');
$router->add('admin/scm/delete', 'SCMController', 'deleteSupplier', 'POST');
$router->add('admin/whatsapp-bot', 'SCMController', 'showWhatsAppBot', 'GET');
$router->add('admin/whatsapp-bot', 'SCMController', 'processWhatsAppMessage', 'POST');
$router->add('admin/whatsapp-bot/reset', 'SCMController', 'resetConversation', 'GET');

// 8. AG Audit Export Console (Super Admin only)
$router->add('superadmin/ag-audit', 'ExporterController', 'showAuditConsole', 'GET');
$router->add('superadmin/ag-audit/export-csv', 'ExporterController', 'exportCsv', 'GET');
$router->add('superadmin/ag-audit/export-pdf', 'ExporterController', 'exportPdf', 'GET');

// Fallback dynamic mapping of dashboards based on roles for root directory mapping
$router->add('', 'AuthController', 'showLogin', 'GET');

// Dispatch request URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$router->dispatch($requestUri);
