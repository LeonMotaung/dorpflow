<?php
/**
 * DorpFlow - Smart Municipal Operations Platform Contact & Scheduling Portal
 * Pure PHP, Bootstrap 5.3, custom CSS, AOS, and Vanilla JS.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact the DorpFlow Public Sector procurement team. Schedule a platform demo, get CSD vendor details, and request technical municipal consulting.">
    <meta name="keywords" content="contact DorpFlow, book demo DorpFlow, municipal procurement SA, central supplier database vendor, POPIA compliance, local government software">
    <meta name="author" content="DorpFlow Systems">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://dorpflow.co.za/contact.php">
    <meta property="og:title" content="Contact DorpFlow | Book a Municipal Demo">
    <meta property="og:description" content="Schedule a live demo, request pricing proposals, or obtain our CSD registration details. POPIA compliant.">
    <meta property="og:image" content="dorpflow.png">

    <title>Contact DorpFlow | Schedule a Municipal Demo</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="dorpflow2.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- AOS (Animate on Scroll) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Custom Styling matching index.php -->
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
            font-family: var(--font-body);
            color: var(--text-color);
            background-color: var(--bg-color);
            overflow-x: hidden;
            position: relative;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 700;
            color: #0F172A;
        }

        /* Ambient floating shapes */
        .ambient-shape-1 {
            position: absolute;
            top: 2%;
            left: -8%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(10, 43, 76, 0.08) 0%, rgba(0, 191, 165, 0.02) 70%);
            filter: blur(80px);
            z-index: -1;
            border-radius: 50%;
            pointer-events: none;
        }

        .ambient-shape-2 {
            position: absolute;
            top: 50%;
            right: -8%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(46, 125, 50, 0.06) 0%, rgba(10, 43, 76, 0.01) 70%);
            filter: blur(80px);
            z-index: -1;
            border-radius: 50%;
            pointer-events: none;
        }

        /* Glassmorphism Navigation */
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid rgba(226, 232, 240, 1);
            transition: var(--transition-smooth);
        }
        .navbar-brand img {
            max-height: 38px;
            transition: var(--transition-smooth);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--text-color);
            position: relative;
            padding: 0.5rem 1rem;
            transition: var(--transition-smooth);
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

        .btn-custom {
            font-family: var(--font-heading);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 12px;
            transition: var(--transition-smooth);
        }
        .btn-primary-custom {
            background-color: var(--primary);
            border: 1px solid var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(10, 43, 76, 0.2);
        }
        .btn-primary-custom:hover {
            background-color: #051627;
            border-color: #051627;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(10, 43, 76, 0.35);
        }

        .btn-accent-custom {
            background-color: var(--accent);
            border: 1px solid var(--accent);
            color: #0f172a;
            box-shadow: 0 4px 14px rgba(0, 191, 165, 0.25);
        }
        .btn-accent-custom:hover {
            background-color: #00a892;
            border-color: #00a892;
            color: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 191, 165, 0.35);
        }

        /* Hero banner */
        .contact-hero {
            padding-top: 150px;
            padding-bottom: 60px;
            text-align: center;
        }
        .hero-badge {
            background: rgba(10, 43, 76, 0.06);
            border: 1px solid rgba(10, 43, 76, 0.12);
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Forms and Widgets */
        .contact-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(10, 43, 76, 0.03);
            height: 100%;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 11px 16px;
            font-size: 0.95rem;
            color: var(--text-color);
            background-color: #ffffff;
            transition: var(--transition-smooth);
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 4px rgba(0, 191, 165, 0.15);
            border-color: var(--accent);
            outline: none;
        }

        /* Interactive Calendar Widget */
        .calendar-wrapper {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            background: #F8FAFC;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .calendar-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            text-align: center;
            margin-bottom: 20px;
        }
        .calendar-day-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }
        .calendar-cell {
            aspect-ratio: 1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-smooth);
            background: #ffffff;
            border: 1px solid var(--border-color);
        }
        .calendar-cell:hover:not(.disabled) {
            background: rgba(0, 191, 165, 0.1);
            color: var(--accent);
            border-color: var(--accent);
        }
        .calendar-cell.active {
            background: var(--accent) !important;
            color: #0f172a !important;
            border-color: var(--accent) !important;
            box-shadow: 0 4px 10px rgba(0, 191, 165, 0.3);
        }
        .calendar-cell.disabled {
            background: #F1F5F9;
            color: #CBD5E1;
            cursor: not-allowed;
            border-color: #E2E8F0;
        }

        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 15px;
        }
        .time-slot {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: var(--transition-smooth);
        }
        .time-slot:hover:not(.disabled) {
            border-color: var(--primary);
            color: var(--primary);
        }
        .time-slot.active {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }
        .time-slot.disabled {
            background: #F1F5F9;
            color: #CBD5E1;
            cursor: not-allowed;
        }

        /* Procurement / CSD Badge */
        .procurement-badge {
            background: rgba(46, 125, 50, 0.08);
            border: 1px solid rgba(46, 125, 50, 0.15);
            border-radius: 12px;
            padding: 16px 20px;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
        }
        .procurement-badge i {
            font-size: 1.6rem;
        }
        .procurement-badge h6 {
            margin-bottom: 2px;
            color: var(--secondary);
            font-weight: 700;
        }

        /* Address and Map Mock */
        .contact-info-list {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
        }
        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
        }
        .contact-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(10, 43, 76, 0.06);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .contact-info-details h6 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 2px;
            color: #0f172a;
        }
        .contact-info-details p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        .map-mockup {
            height: 200px;
            background: #E2E8F0;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .map-grid-line {
            position: absolute;
            background: rgba(0,0,0,0.03);
        }
        .map-dot {
            width: 16px;
            height: 16px;
            background: var(--accent);
            border-radius: 50%;
            position: relative;
            z-index: 2;
        }
        .map-dot::after {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            width: 32px;
            height: 32px;
            border: 3px solid var(--accent);
            border-radius: 50%;
            animation: pulse-ring 1.8s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.33); opacity: 1; }
            80%, 100% { opacity: 0; }
        }

        /* Footer */
        footer {
            background-color: #0f172a;
            color: #94A3B8;
            padding: 80px 0 30px 0;
            border-top: 1px solid rgba(255,255,255,0.05);
            margin-top: 100px;
        }
        footer h5 {
            color: #ffffff;
            font-size: 1.05rem;
            margin-bottom: 24px;
        }
        footer ul {
            padding: 0;
            list-style: none;
            margin: 0;
        }
        footer ul li {
            margin-bottom: 12px;
        }
        footer ul li a {
            color: #94A3B8;
            text-decoration: none;
            transition: var(--transition-smooth);
            font-size: 0.95rem;
        }
        footer ul li a:hover {
            color: var(--accent);
            padding-left: 4px;
        }
        .footer-social-icons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            transition: var(--transition-smooth);
        }
        .social-btn:hover {
            background: var(--accent);
            color: #0f172a;
            transform: translateY(-3px);
        }
        .footer-bottom {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.05);
            font-size: 0.85rem;
        }
        .newsletter-form .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px 0 0 8px;
            color: #ffffff;
            padding: 10px 16px;
        }
        .newsletter-form .form-control::placeholder {
            color: #64748b;
        }
        .newsletter-form .form-control:focus {
            box-shadow: none;
            border-color: var(--accent);
            background: rgba(255,255,255,0.08);
        }
        .newsletter-form .btn {
            border-radius: 0 8px 8px 0;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- Ambient background shapes -->
    <div class="ambient-shape-1"></div>
    <div class="ambient-shape-2"></div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top glass-nav" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="dorpflow.png" alt="DorpFlow Logo" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#solutions">Solutions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#trust">Municipalities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="public/index.php/login" class="nav-link px-2">Login</a>
                    <a href="public/index.php/register" class="nav-link px-2">Register</a>
                    <a href="contact.php" class="btn btn-custom btn-accent-custom text-nowrap">Book Demo</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO HEADER -->
    <header class="contact-hero">
        <div class="container" data-aos="fade-up" data-aos-duration="800">
            <div class="hero-badge">
                <i class="fa-solid fa-file-invoice-dollar text-accent"></i> National Supplier Database Registered
            </div>
            <h1 class="display-4 fw-extrabold mb-3">Connect With Our <span class="gradient-text">Public Sector</span> Team</h1>
            <p class="text-muted mx-auto" style="max-width: 620px; font-size:1.1rem; line-height: 1.6;">
                Request custom RFPs, query implementation SLAs, check national Treasury CSD vendor profiles, or schedule an live administrative demo.
            </p>
        </div>
    </header>

    <!-- CONTENT PORTAL GRID -->
    <main class="container">
        <div class="row g-5">
            
            <!-- LEFT COLUMN: CONTACT FORM -->
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="contact-card">
                    <h3 class="mb-4" style="font-size: 1.5rem; font-weight:700;"><i class="fa-regular fa-envelope me-2 text-accent"></i>Send us a message</h3>
                    
                    <form id="govContactForm" onsubmit="handleFormSubmit(event)">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="contactName">Full Name *</label>
                                <input type="text" class="form-control" id="contactName" placeholder="e.g. Sizwe Dube" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="contactEmail">Official Municipal Email *</label>
                                <input type="email" class="form-control" id="contactEmail" placeholder="s.dube@mangaung.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="contactPhone">Contact Hotline Number *</label>
                                <input type="tel" class="form-control" id="contactPhone" placeholder="e.g. +27 82 555 1234" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="contactOrg">Municipality / Organization *</label>
                                <input type="text" class="form-control" id="contactOrg" placeholder="e.g. Mangaung Metro" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="contactProvince">Province *</label>
                                <select class="form-select" id="contactProvince" required>
                                    <option value="" disabled selected>Select Province</option>
                                    <option>Eastern Cape</option>
                                    <option>Free State</option>
                                    <option>Gauteng</option>
                                    <option>KwaZulu-Natal</option>
                                    <option>Limpopo</option>
                                    <option>Mpumalanga</option>
                                    <option>North West</option>
                                    <option>Northern Cape</option>
                                    <option>Western Cape</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="contactRole">Designation / Role *</label>
                                <select class="form-select" id="contactRole" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option>Municipal Manager</option>
                                    <option>Chief Financial Officer (CFO)</option>
                                    <option>IT Director / Manager</option>
                                    <option>Ward Councillor / Committee</option>
                                    <option>Citizen Reporter</option>
                                    <option>Contractor / Vendor</option>
                                    <option>Other Public Sector</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="contactSubject">Subject *</label>
                                <input type="text" class="form-control" id="contactSubject" placeholder="e.g. Platform RFP Inquiry" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="contactMsg">Message / Inquiry Details *</label>
                                <textarea class="form-control" id="contactMsg" rows="4" placeholder="Detail your procurement requirements or query here..." required></textarea>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check text-start">
                                    <input class="form-check-input" type="checkbox" value="" id="popiaCheck" required>
                                    <label class="form-check-label text-muted" style="font-size:0.75rem; line-height: 1.4;" for="popiaCheck">
                                        I hereby consent to DorpFlow processing this data to contact our offices, in accordance with the Protection of Personal Information Act (POPIA).
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-custom btn-primary-custom w-100 py-3" id="submitBtn">
                                    <span id="submitText">Submit Inquiry</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: CONTACT DETAILS & DEMO SCHEDULER -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="d-flex flex-column gap-4">
                    
                    <!-- PROCUREMENT DETAIL BAR -->
                    <div class="procurement-badge">
                        <div class="contact-info-icon bg-success-subtle text-success">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <div>
                            <h6>National Treasury Registry</h6>
                            <p class="mb-0" style="font-size: 0.8rem; color: #1e293b;">
                                Government Central Supplier Database (CSD) Vendor Number: <strong class="text-success">MAAA0982319</strong>
                            </p>
                        </div>
                    </div>

                    <!-- DIRECT CONTACT DETAILS -->
                    <div class="contact-card p-4">
                        <h4 class="mb-4" style="font-size: 1.25rem; font-weight:700;">Direct Procurement Contact Channels</h4>
                        
                        <ul class="contact-info-list">
                            <li class="contact-info-item">
                                <div class="contact-info-icon"><i class="fa-solid fa-phone"></i></div>
                                <div class="contact-info-details">
                                    <h6>Procurement Hotline</h6>
                                    <p>+27 (0) 12 555 0199 (Head Office)</p>
                                </div>
                            </li>
                            <li class="contact-info-item">
                                <div class="contact-info-icon"><i class="fa-solid fa-envelope"></i></div>
                                <div class="contact-info-details">
                                    <h6>Sales & RFPs</h6>
                                    <p>sales@dorpflow.com</p>
                                </div>
                            </li>
                            <li class="contact-info-item">
                                <div class="contact-info-icon"><i class="fa-solid fa-headset"></i></div>
                                <div class="contact-info-details">
                                    <h6>Technical Helpdesk</h6>
                                    <p>support@dorpflow.com</p>
                                </div>
                            </li>
                            <li class="contact-info-item">
                                <div class="contact-info-icon"><i class="fa-solid fa-location-dot"></i></div>
                                <div class="contact-info-details">
                                    <h6>Pretoria Headquarters</h6>
                                    <p>124 Jacaranda Ave, Hatfield, Pretoria, 0083</p>
                                </div>
                            </li>
                            <li class="contact-info-item">
                                <div class="contact-info-icon"><i class="fa-solid fa-building-user"></i></div>
                                <div class="contact-info-details">
                                    <h6>Free State Regional Office</h6>
                                    <p>1013 Butayi St, Namahadi, Frankfort, 9830</p>
                                    <p class="mb-0 text-muted" style="font-size:0.85rem;"><i class="fa-solid fa-phone me-1"></i> 084 866 2418</p>
                                </div>
                            </li>
                        </ul>

                        <!-- Address Mock Map -->
                        <div class="map-mockup">
                            <!-- Styled Abstract Map Grids -->
                            <div class="map-grid-line" style="width:100%; height:2px; top:40%;"></div>
                            <div class="map-grid-line" style="width:100%; height:2px; top:70%;"></div>
                            <div class="map-grid-line" style="width:2px; height:100%; left:30%;"></div>
                            <div class="map-grid-line" style="width:2px; height:100%; left:65%;"></div>
                            <div class="map-dot"></div>
                            <div class="text-dark bg-white shadow-sm rounded px-2 py-1" style="position:absolute; top:45%; left:68%; font-size:0.65rem; font-weight:700;">
                                <i class="fa-solid fa-building me-1 text-primary"></i> DorpFlow HQ
                            </div>
                        </div>
                    </div>

                    <!-- INTERACTIVE DEMO SCHEDULER -->
                    <div class="contact-card p-4">
                        <h4 class="mb-2" style="font-size: 1.25rem; font-weight:700;"><i class="fa-regular fa-calendar-check me-2 text-accent"></i>Book an Administrative Demo</h4>
                        <p class="text-muted mb-4" style="font-size:0.8rem;">Select a date and available time slot to schedule a live discovery screen share.</p>
                        
                        <div class="calendar-wrapper">
                            <div class="calendar-header">
                                <span class="calendar-title" id="calMonth">July 2026</span>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="fa-solid fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="fa-solid fa-chevron-right"></i></button>
                                </div>
                            </div>
                            
                            <!-- Calendar grid -->
                            <div class="calendar-grid" id="calendarGrid">
                                <div class="calendar-day-label">M</div>
                                <div class="calendar-day-label">T</div>
                                <div class="calendar-day-label">W</div>
                                <div class="calendar-day-label">T</div>
                                <div class="calendar-day-label">F</div>
                                <div class="calendar-day-label">S</div>
                                <div class="calendar-day-label">S</div>
                                
                                <!-- Mock dates starting from Monday -->
                                <div class="calendar-cell disabled">29</div>
                                <div class="calendar-cell disabled">30</div>
                                <div class="calendar-cell" data-day="1">1</div>
                                <div class="calendar-cell" data-day="2">2</div>
                                <div class="calendar-cell" data-day="3">3</div>
                                <div class="calendar-cell disabled">4</div>
                                <div class="calendar-cell disabled">5</div>
                                
                                <div class="calendar-cell" data-day="6">6</div>
                                <div class="calendar-cell" data-day="7">7</div>
                                <div class="calendar-cell active" data-day="8">8</div>
                                <div class="calendar-cell" data-day="9">9</div>
                                <div class="calendar-cell" data-day="10">10</div>
                                <div class="calendar-cell disabled">11</div>
                                <div class="calendar-cell disabled">12</div>
                                
                                <div class="calendar-cell" data-day="13">13</div>
                                <div class="calendar-cell" data-day="14">14</div>
                                <div class="calendar-cell" data-day="15">15</div>
                                <div class="calendar-cell" data-day="16">16</div>
                                <div class="calendar-cell" data-day="17">17</div>
                                <div class="calendar-cell disabled">18</div>
                                <div class="calendar-cell disabled">19</div>
                            </div>

                            <!-- Time slot selector -->
                            <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">AVAILABLE SLOTS (SAST)</span>
                            <div class="time-slots-grid" id="slotsGrid">
                                <div class="time-slot active" data-slot="09:00 AM">09:00 AM</div>
                                <div class="time-slot" data-slot="10:30 AM">10:30 AM</div>
                                <div class="time-slot" data-slot="11:30 AM">11:30 AM</div>
                                <div class="time-slot" data-slot="01:00 PM">01:00 PM</div>
                                <div class="time-slot" data-slot="02:30 PM">02:30 PM</div>
                                <div class="time-slot disabled" data-slot="03:30 PM">03:30 PM</div>
                            </div>

                            <button type="button" class="btn btn-custom btn-primary-custom w-100 mt-4 btn-sm" onclick="bookScheduledDemo()">
                                Book Selected Slot
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <img src="dorpflow.png" alt="DorpFlow Logo" class="mb-3" style="max-height: 40px; filter: brightness(0) invert(1);">
                    <p style="font-size:0.9rem; color: #94A3B8;">Empowering South African municipalities through smart, reliable digital operations. Connecting citizens and local government transparently.</p>
                    <div class="footer-social-icons">
                        <a href="#" class="social-btn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" class="social-btn"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h5>Solutions</h5>
                    <ul>
                        <li><a href="index.php#solutions">Water Department</a></li>
                        <li><a href="index.php#solutions">Electricity & Outages</a></li>
                        <li><a href="index.php#solutions">Roads & Stormwater</a></li>
                        <li><a href="index.php#solutions">Waste Management</a></li>
                        <li><a href="index.php#solutions">Municipal Fleet Dispatch</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="index.php#features">Platform Features</a></li>
                        <li><a href="index.php#pricing">Subscription Pricing</a></li>
                        <li><a href="index.php#faq">Frequently Asked Questions</a></li>
                        <li><a href="contact.php">Schedule Public Sector Demo</a></li>
                        <li><a href="contact.php">Contact Support</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h5>Newsletter & Regulatory</h5>
                    <p style="font-size:0.85rem; color: #94A3B8;">Subscribe to our quarterly service delivery newsletter.</p>
                    <form onsubmit="event.preventDefault(); alert('Subscribed to municipal updates newsletter.');" class="input-group newsletter-form mb-3">
                        <input type="email" class="form-control" placeholder="Office email..." required>
                        <button class="btn btn-primary-custom" type="submit">Join</button>
                    </form>
                    <div style="font-size:0.75rem; color: #64748B;">
                        POPIA Compliant • Promotion of Access to Information Act (PAIA) Certified.
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom text-center">
                <p class="mb-0" style="color: #64748B;">&copy; <?php echo date('Y'); ?> DorpFlow Systems (Pty) Ltd. All rights reserved. Registered South African Government Vendor ID: Vendor-DF7299.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- AOS (Animate on Scroll) -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <!-- Interactive JS scripts -->
    <script>
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // Contact Form Submission Action
        function handleFormSubmit(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            
            // Add loading spinner
            submitBtn.disabled = true;
            submitText.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Sending Inquiry...';
            
            setTimeout(() => {
                alert('Thank you for your message! Our procurement consulting team will contact your office with CSD papers and pricing proposals within 24 hours.');
                document.getElementById('govContactForm').reset();
                submitBtn.disabled = false;
                submitText.innerHTML = 'Submit Inquiry';
            }, 1500);
        }

        // Calendar Slot Selection
        let selectedDay = 8;
        let selectedSlot = '09:00 AM';

        const cells = document.querySelectorAll('#calendarGrid .calendar-cell:not(.disabled)');
        cells.forEach(cell => {
            cell.addEventListener('click', function() {
                cells.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                selectedDay = parseInt(this.getAttribute('data-day'));
            });
        });

        const slots = document.querySelectorAll('#slotsGrid .time-slot:not(.disabled)');
        slots.forEach(slot => {
            slot.addEventListener('click', function() {
                slots.forEach(s => s.classList.remove('active'));
                this.classList.add('active');
                selectedSlot = this.getAttribute('data-slot');
            });
        });

        // Book Demo Button action
        function bookScheduledDemo() {
            alert(`Demo Booking Registered! Date: ${selectedDay} July 2026. Time Slot: ${selectedSlot} (SAST). A calendar invite and MS Teams invitation link has been dispatched to your email address.`);
        }
    </script>
</body>
</html>
