<?php
/**
 * DorpFlow ERP - Password Reset Input View
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
    .reset-card {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 50px rgba(10, 43, 76, 0.05) !important;
        max-width: 480px;
        width: 100%;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reset-card:hover {
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
    }
    .form-control:focus {
        box-shadow: 0 0 0 4px rgba(0, 191, 165, 0.15) !important;
        border-color: var(--accent) !important;
        outline: none !important;
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
    <div class="reset-card overflow-hidden">
        
        <!-- Header -->
        <div class="text-center py-4 border-bottom bg-white">
            <img src="<?php echo APP_URL; ?>/dorpflow.png" alt="DorpFlow Logo" style="max-height: 42px;">
            <p class="mb-0 text-muted mt-2" style="font-size:0.75rem; font-weight:700; letter-spacing:0.05em;">CREATE NEW PASSWORD</p>
        </div>

        <div class="card-body p-4">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/public/index.php/reset-password<?php echo isset($_GET['tenant']) ? '?tenant=' . urlencode($_GET['tenant']) : ''; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                
                <div class="mb-3">
                    <label for="resetPass" class="form-label">New Password *</label>
                    <input type="password" name="password" class="form-control" id="resetPass" placeholder="••••••••" required minlength="6">
                </div>

                <div class="mb-4">
                    <label for="confirmPass" class="form-label">Confirm New Password *</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirmPass" placeholder="••••••••" required minlength="6">
                </div>

                <button type="submit" class="btn btn-custom btn-primary-custom w-100 py-3 mb-2">Set New Password</button>
            </form>
            
            <div class="text-center mt-3" style="font-size:0.8rem; color:#64748B;">
                Return to <a href="<?php echo APP_URL; ?>/public/index.php/login<?php echo isset($_GET['tenant']) ? '?tenant=' . urlencode($_GET['tenant']) : ''; ?>" class="text-primary text-decoration-none fw-semibold">Sign In here</a>.
            </div>
        </div>
    </div>
</div>
