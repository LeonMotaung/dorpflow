<?php
/**
 * DorpFlow ERP - Multi-Factor Authentication (2FA) Challenge View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'MFA Challenge | DorpFlow'; ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: rgb(10, 43, 76);
            --accent: rgb(0, 191, 165);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(10, 43, 76, 0.08);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
        }
        .btn-accent-custom {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #0f172a;
            font-weight: 600;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-accent-custom:hover {
            background-color: #00a892;
            border-color: #00a892;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="login-card p-5">
        <div class="text-center mb-4">
            <img src="<?php echo getMunicipalityLogo(); ?>" alt="Logo" style="max-height: 45px; border-radius: 6px;" class="mb-3">
            <h4 class="fw-bold text-primary mb-1">MFA Security Check</h4>
            <p class="text-muted small">We've sent a 2FA OTP code to protect your administrative console permissions.</p>
        </div>

        <!-- Simulated OTP Banner for local testing -->
        <div class="alert alert-info border-0 text-center mb-4" style="background-color: rgba(0, 191, 165, 0.1); color: var(--primary);">
            <i class="fa-solid fa-envelope-open-text me-1"></i> <strong>Simulated OTP Sent:</strong>
            <span class="d-block mt-1 fw-bold fs-4 tracking-wider text-dark"><?php echo $simulated_otp; ?></span>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger border-0 small text-center mb-3">
                <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo APP_URL; ?>/public/index.php/login-2fa" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="mb-4">
                <label class="form-label fw-semibold text-primary">Enter 6-Digit Verification Code</label>
                <input type="text" name="otp_code" class="form-control text-center fs-4 fw-bold" placeholder="000 000" maxlength="6" autofocus required autocomplete="off">
            </div>

            <button type="submit" class="btn btn-accent-custom w-100 py-3"><i class="fa-solid fa-shield-check me-1"></i> Verify & Authenticate</button>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <a href="<?php echo APP_URL; ?>/public/index.php/login" class="text-decoration-none text-muted small"><i class="fa-solid fa-arrow-left me-1"></i> Return to login screen</a>
        </div>
    </div>
</body>
</html>
