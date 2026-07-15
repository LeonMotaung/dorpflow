<?php
/**
 * DorpFlow ERP - SaaS Municipal Onboarding Register Gateway
 */
?>
<style>
    /* Ambient floating shapes matching index.php */
    .ambient-shape-1 {
        position: absolute;
        top: 5%;
        left: -5%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(10, 43, 76, 0.08) 0%, rgba(0, 191, 165, 0.02) 70%);
        filter: blur(80px);
        z-index: -1;
        border-radius: 50%;
        pointer-events: none;
        animation: drift 25s infinite alternate;
    }

    .ambient-shape-2 {
        position: absolute;
        top: 45%;
        right: -5%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(46, 125, 50, 0.06) 0%, rgba(10, 43, 76, 0.01) 70%);
        filter: blur(80px);
        z-index: -1;
        border-radius: 50%;
        pointer-events: none;
        animation: drift-reverse 30s infinite alternate;
    }

    @keyframes drift {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(60px, 80px) scale(1.1); }
    }

    @keyframes drift-reverse {
        0% { transform: translate(0, 0) scale(1.1); }
        100% { transform: translate(-80px, -50px) scale(0.9); }
    }

    /* Glassmorphic Navbar matching index.php */
    .glass-nav {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(12px) saturate(180%) !important;
        border-bottom: 1px solid rgba(226, 232, 240, 1) !important;
    }
    
    .nav-link {
        font-weight: 500 !important;
        color: var(--text-color) !important;
    }

    /* Glassmorphic Card */
    .register-card {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 50px rgba(10, 43, 76, 0.05) !important;
        max-width: 500px;
        width: 100%;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .register-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 30px 60px rgba(10, 43, 76, 0.08);
    }

    .btn-custom {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        padding: 12px 28px !important;
        border-radius: 12px !important;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
</style>

<!-- Ambient background shapes -->
<div class="ambient-shape-1"></div>
<div class="ambient-shape-2"></div>

<!-- STICKY NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top glass-nav" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo APP_URL; ?>/index.php">
            <img src="<?php echo APP_URL; ?>/dorpflow.png" alt="DorpFlow Logo" style="max-height:38px;">
        </a>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0"></ul>
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo APP_URL; ?>/public/index.php/login" class="nav-link px-2">Login</a>
                <a href="<?php echo APP_URL; ?>/contact.php" class="btn btn-custom btn-accent-custom text-nowrap">Book Demo</a>
            </div>
        </div>
    </div>
</nav>

<!-- MAIN CONTAINER -->
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; padding-top: 100px; padding-bottom: 40px;">
    <div class="register-card overflow-hidden text-center p-5">
        <div class="mb-4">
            <i class="fa-solid fa-building-circle-check text-accent fs-1"></i>
        </div>
        <h4 class="fw-bold mb-3">Register Municipality</h4>
        <p class="text-muted mb-4" style="line-height: 1.6;">
            DorpFlow is an enterprise SaaS platform. Due to POPIA compliance, database isolation, and security provisioning, individual municipalities must be onboarded directly by our Technical Procurement team.
        </p>
        <div class="alert alert-info py-3 mb-4 rounded-3 text-start" style="font-size:0.85rem;">
            <i class="fa-solid fa-info-circle me-2 text-primary"></i> If you are a resident or citizen looking to log a ticket, please access your municipality's specific subdomain (e.g. <code>stellenbosch.dorpflow.gov.za/register</code>).
        </div>
        <div class="d-grid gap-3">
            <a href="<?php echo APP_URL; ?>/contact.php" class="btn btn-custom btn-primary-custom py-3"><i class="fa-regular fa-calendar-check me-2"></i> Book Onboarding & Demo</a>
            <a href="<?php echo APP_URL; ?>/public/index.php/login" class="btn btn-custom btn-outline-secondary py-3 text-dark" style="background:#F8FAFC; border-color:#E2E8F0;"><i class="fa-solid fa-arrow-left me-2"></i> Return to Sign In</a>
        </div>
    </div>
</div>
