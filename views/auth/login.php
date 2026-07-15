<?php
/**
 * DorpFlow ERP - User Sign In Form with Premium Landing Page Theme
 */
?>
<style>
    :root {
        --primary: #0A2B4C;
        --primary-rgb: 10, 43, 76;
        --secondary: #2E7D32;
        --secondary-rgb: 46, 125, 50;
        --accent: #00BFA5;
        --accent-rgb: 0, 191, 165;
        --bg-color: #F8FAFC;
        --card-bg: #FFFFFF;
        --text-color: #1E293B;
        --text-muted: #64748B;
        --border-color: #E2E8F0;
        --glow-color: rgba(0, 191, 165, 0.15);
        
        --font-heading: 'Outfit', sans-serif;
        --font-body: 'Plus Jakarta Sans', sans-serif;
        
        --transition-smooth: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    body {
        font-family: var(--font-body) !important;
        background-color: var(--bg-color) !important;
        overflow-x: hidden;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading) !important;
        font-weight: 700;
        color: #0F172A;
    }

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
        transition: var(--transition-smooth) !important;
    }
    
    .nav-link {
        font-weight: 500 !important;
        color: var(--text-color) !important;
        position: relative !important;
        padding: 0.5rem 1rem !important;
        transition: var(--transition-smooth) !important;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: var(--primary);
        transition: var(--transition-smooth);
        transform: translateX(-50%);
    }
    .nav-link:hover {
        color: var(--primary) !important;
    }
    .nav-link:hover::after {
        width: 80%;
    }

    /* Custom Buttons matching index.php */
    .btn-custom {
        font-family: var(--font-heading) !important;
        font-weight: 600 !important;
        padding: 10px 24px !important;
        border-radius: 12px !important;
        transition: var(--transition-smooth) !important;
    }
    
    .btn-primary-custom {
        background-color: var(--primary) !important;
        border: 1px solid var(--primary) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(10, 43, 76, 0.2) !important;
    }
    .btn-primary-custom:hover {
        background-color: #051627 !important;
        border-color: #051627 !important;
        color: #ffffff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(10, 43, 76, 0.35) !important;
    }

    .btn-accent-custom {
        background-color: var(--accent) !important;
        border: 1px solid var(--accent) !important;
        color: #0f172a !important;
        box-shadow: 0 4px 14px rgba(0, 191, 165, 0.25) !important;
    }
    .btn-accent-custom:hover {
        background-color: #00a892 !important;
        border-color: #00a892 !important;
        color: #0f172a !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(0, 191, 165, 0.35) !important;
    }

    /* Glassmorphic Login Card */
    .login-card {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 50px rgba(10, 43, 76, 0.05) !important;
        transition: var(--transition-smooth) !important;
        width: 100%;
        max-width: 450px;
    }
    .login-card:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 30px 60px rgba(10, 43, 76, 0.08) !important;
        border-color: rgba(10, 43, 76, 0.15) !important;
    }

    .gradient-text {
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Form Controls matching index.php contact form styles */
    .form-label {
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        color: #0f172a !important;
        margin-bottom: 6px !important;
    }
    .form-control {
        border: 1px solid #CBD5E1 !important;
        border-radius: 10px !important;
        padding: 11px 16px !important;
        font-size: 0.95rem !important;
        color: var(--text-color) !important;
        background-color: #ffffff !important;
        transition: var(--transition-smooth) !important;
    }
    .form-control:focus {
        box-shadow: 0 0 0 4px rgba(0, 191, 165, 0.15) !important;
        border-color: var(--accent) !important;
        outline: none !important;
    }

    /* Bootstrap Utility overrides */
    .bg-primary {
        background-color: var(--primary) !important;
    }
    .text-primary {
        color: var(--primary) !important;
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/index.php#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/index.php#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/index.php#solutions">Solutions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/index.php#pricing">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/index.php#trust">Municipalities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/contact.php">Contact</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo APP_URL; ?>/public/index.php/login" class="nav-link active px-2">Login</a>
                <a href="<?php echo APP_URL; ?>/contact.php" class="btn btn-custom btn-accent-custom text-nowrap">Book Demo</a>
            </div>
        </div>
    </div>
</nav>

<!-- LOGIN CARD CONTAINER -->
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; padding-top: 100px; padding-bottom: 40px;">
    <div class="login-card overflow-hidden" data-aos="zoom-in" data-aos-duration="800">
        
        <!-- Header -->
        <div class="text-center py-4 border-bottom bg-white">
            <img src="<?php echo APP_URL; ?>/dorpflow.png" alt="DorpFlow Logo" style="max-height: 42px;">
            <p class="mb-0 text-muted mt-2" style="font-size:0.75rem; font-weight:700; letter-spacing:0.05em; color: var(--text-muted) !important;">MUNICIPAL OPERATIONS LOGIN</p>
        </div>

        <div class="card-body p-4">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/public/index.php/login" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Official / Personal Email</label>
                    <input type="email" name="email" class="form-control" id="loginEmail" placeholder="e.g. admin@stellenbosch.gov.za" required>
                </div>
                
                <div class="mb-3">
                    <label for="loginPass" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="loginPass" placeholder="••••••••" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label text-muted" style="font-size:0.8rem;" for="rememberMe">
                            Remember device
                        </label>
                    </div>
                    <a href="<?php echo APP_URL; ?>/public/index.php/forgot-password<?php echo isset($_GET['tenant']) ? '?tenant=' . urlencode($_GET['tenant']) : ''; ?>" class="text-primary text-decoration-none" style="font-size:0.8rem; font-weight:600;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-custom btn-primary-custom w-100 py-3 mb-2">Sign In</button>
            </form>
            
            <div class="text-center mt-4" style="font-size:0.75rem; color:#64748B;">
                POPIA Compliant Secure Authentication.
            </div>
        </div>
    </div>
</div>
