<?php
/**
 * DorpFlow - Smart Municipal Operations Platform Landing Page
 * Pure PHP, Bootstrap 5.3, custom CSS, Chart.js, AOS, and Vanilla JS.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DorpFlow is the leading smart municipal service delivery platform for South Africa. Modern work orders, GIS ticketing, asset management, and citizen portal built to empower local government.">
    <meta name="keywords" content="DorpFlow, municipal service delivery, local government SA, municipal software, citizen portal, staff ticketing, GIS mapping, fleet management, work order technician">
    <meta name="author" content="DorpFlow Systems">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://dorpflow.co.za/">
    <meta property="og:title" content="DorpFlow | Modern Municipal Service Delivery Platform">
    <meta property="og:description" content="Improve service delivery, assign work orders, track assets, and connect with residents using South Africa's premier smart municipality system.">
    <meta property="og:image" content="dorpflow.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="DorpFlow | Modern Municipal Service Delivery Platform">
    <meta property="twitter:description" content="Improve service delivery, assign work orders, track assets, and connect with residents using South Africa's premier smart municipality system.">
    <meta property="twitter:image" content="dorpflow.png">

    <title>DorpFlow | Modern Municipal Service Delivery Platform</title>

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

    <!-- Custom Styling -->
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
            top: 40%;
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

        /* Glassmorphism helpers */
        .glass-nav {
            background: rgba(248, 250, 252, 0.85);
            backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
            transition: var(--transition-smooth);
        }
        .glass-nav.scrolled {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 30px rgba(10, 43, 76, 0.05);
            border-bottom: 1px solid rgba(226, 232, 240, 1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(10, 43, 76, 0.02);
            transition: var(--transition-smooth);
        }
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(10, 43, 76, 0.06);
            border-color: rgba(10, 43, 76, 0.2);
        }

        /* Typography and Accent Styles */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-bg-accent {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: #ffffff;
        }

        /* Sticky Navbar overrides */
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
            background-color: #0b3d68;
            border-color: #0b3d68;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(10, 43, 76, 0.3);
        }

        .btn-secondary-custom {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        .btn-secondary-custom:hover {
            background-color: rgba(10, 43, 76, 0.05);
            color: var(--primary);
            transform: translateY(-2px);
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

        /* Hero Section */
        .hero-section {
            padding-top: 160px;
            padding-bottom: 100px;
            position: relative;
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
            margin-bottom: 24px;
        }
        .hero-badge i {
            font-size: 0.75rem;
            color: var(--accent);
        }

        .hero-heading {
            font-size: 3.8rem;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 24px;
        }
        @media (max-width: 991px) {
            .hero-heading {
                font-size: 2.8rem;
            }
        }

        .hero-lead {
            font-size: 1.15rem;
            line-height: 1.6;
            color: var(--text-muted);
            margin-bottom: 32px;
            max-width: 580px;
        }

        .hero-checklist {
            display: flex;
            flex-wrap: wrap;
            gap: 16px 24px;
            margin-top: 40px;
            padding: 0;
            list-style: none;
        }
        .hero-checklist li {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .hero-checklist li i {
            color: var(--secondary);
            font-size: 1.1rem;
        }

        /* Premium Dashboard Illustration */
        .dashboard-mockup-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            min-height: 480px;
        }
        .dashboard-main-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 1);
            border-radius: 16px;
            box-shadow: 0 30px 60px rgba(10, 43, 76, 0.08);
            width: 100%;
            overflow: hidden;
            position: relative;
        }
        .dashboard-header {
            background: #F8FAFC;
            border-bottom: 1px solid rgba(226, 232, 240, 1);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .window-controls {
            display: flex;
            gap: 6px;
        }
        .window-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .window-dot.red { background-color: #FF5F56; }
        .window-dot.yellow { background-color: #FFBD2E; }
        .window-dot.green { background-color: #27C93F; }

        .dashboard-body {
            padding: 20px;
            background-color: #F8FAFC;
        }

        .floating-stat-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 15px 35px rgba(10, 43, 76, 0.08);
            border-radius: 12px;
            padding: 12px 18px;
            z-index: 10;
            transition: var(--transition-smooth);
        }
        .floating-stat-card:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 20px 45px rgba(10, 43, 76, 0.12);
        }

        .stat-card-1 {
            top: 20px;
            left: -30px;
            animation: bounce 6s infinite alternate;
        }
        .stat-card-2 {
            bottom: 40px;
            left: -40px;
            animation: bounce 8s infinite alternate-reverse;
        }
        .stat-card-3 {
            top: -20px;
            right: 10px;
            animation: bounce 7s infinite alternate;
        }
        .stat-card-4 {
            bottom: 60px;
            right: -30px;
            animation: bounce 5s infinite alternate-reverse;
        }

        @keyframes bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

        .active-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: var(--accent);
            border-radius: 50%;
            position: relative;
        }
        .active-dot::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            width: 16px;
            height: 16px;
            border: 2px solid var(--accent);
            border-radius: 50%;
            animation: pulse-ring 1.8s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.33); opacity: 1; }
            80%, 100% { opacity: 0; }
        }

        /* Trust Section */
        .trust-section {
            padding: 60px 0;
            background-color: #ffffff;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .municipality-logo {
            font-size: 1.1rem;
            font-weight: 700;
            color: #94A3B8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            filter: grayscale(100%);
            opacity: 0.55;
            transition: var(--transition-smooth);
            cursor: pointer;
            padding: 10px;
        }
        .municipality-logo:hover {
            filter: grayscale(0%);
            opacity: 0.95;
            color: var(--primary);
        }

        /* Live Statistics */
        .stats-section {
            padding: 100px 0;
            position: relative;
        }
        .stat-box {
            padding: 30px;
            text-align: center;
            border-radius: 20px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(10, 43, 76, 0.02);
            transition: var(--transition-smooth);
        }
        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(10, 43, 76, 0.05);
        }
        .stat-number {
            font-family: var(--font-heading);
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 10px;
            line-height: 1;
        }

        /* Features Section */
        .section-header {
            max-width: 700px;
            margin: 0 auto 60px auto;
            text-align: center;
        }
        .section-tag {
            color: var(--accent);
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
            display: inline-block;
        }
        .section-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 35px 30px;
            height: 100%;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            opacity: 0;
            transition: var(--transition-smooth);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(10, 43, 76, 0.06);
            border-color: rgba(10, 43, 76, 0.15);
        }
        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            background: rgba(10, 43, 76, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: var(--primary);
            font-size: 1.5rem;
            transition: var(--transition-smooth);
        }
        .feature-card:hover .feature-icon-wrapper {
            background: var(--primary);
            color: #ffffff;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .feature-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* How it works stepping timeline */
        .timeline-section {
            padding: 100px 0;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }
        .timeline-container {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: nowrap;
            margin-top: 60px;
            padding: 40px 0;
        }
        @media (max-width: 991px) {
            .timeline-container {
                flex-direction: column;
                gap: 40px;
                padding-left: 30px;
            }
        }

        .timeline-progress-bar {
            position: absolute;
            top: 68px;
            left: 5%;
            right: 5%;
            height: 4px;
            background: #E2E8F0;
            z-index: 1;
        }
        .timeline-progress-active {
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            transition: width 0.8s ease;
        }
        @media (max-width: 991px) {
            .timeline-progress-bar {
                top: 0;
                left: 48px;
                bottom: 0;
                width: 4px;
                height: 90%;
            }
            .timeline-progress-active {
                width: 100%;
                height: 0%;
                transition: height 0.8s ease;
            }
        }

        .timeline-step {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 11%;
            cursor: pointer;
            transition: var(--transition-smooth);
        }
        @media (max-width: 991px) {
            .timeline-step {
                width: 100%;
                text-align: left;
                display: flex;
                align-items: center;
                gap: 20px;
            }
        }

        .timeline-node {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #ffffff;
            border: 4px solid #E2E8F0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 1.25rem;
            margin-bottom: 16px;
            transition: var(--transition-smooth);
        }
        @media (max-width: 991px) {
            .timeline-node {
                margin-bottom: 0;
                flex-shrink: 0;
            }
        }

        .timeline-step.active .timeline-node {
            border-color: var(--accent);
            color: var(--accent);
            background: #ffffff;
            box-shadow: 0 0 20px rgba(0, 191, 165, 0.4);
        }
        .timeline-step.completed .timeline-node {
            border-color: var(--primary);
            color: #ffffff;
            background: var(--primary);
        }

        .timeline-label {
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-color);
            transition: var(--transition-smooth);
        }
        .timeline-step.active .timeline-label {
            color: var(--primary);
        }

        .timeline-detail-box {
            margin-top: 40px;
            min-height: 120px;
            padding: 30px;
            border-radius: 16px;
            background: var(--bg-color);
            border: 1px solid var(--border-color);
        }

        /* Solutions department grid */
        .solutions-section {
            padding: 100px 0;
        }

        .dept-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition-smooth);
            height: 100%;
        }
        .dept-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 15px 35px rgba(10, 43, 76, 0.04);
            border-color: var(--accent);
        }

        .dept-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(46, 125, 50, 0.08);
            color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: var(--transition-smooth);
        }
        .dept-card:hover .dept-icon {
            background: var(--secondary);
            color: #ffffff;
        }
        
        .dept-info h4 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .dept-info p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        .dept-badge {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
            margin-top: 4px;
            display: inline-block;
        }

        /* Screenshots / Interactive Mockups section */
        .mockups-section {
            padding: 100px 0;
            background: #0F172A;
            color: #ffffff;
        }
        .mockups-section .section-title {
            color: #ffffff;
        }

        .mockup-nav-tabs {
            border: none;
            justify-content: center;
            gap: 12px;
            margin-bottom: 50px;
        }
        .mockup-nav-tabs .nav-link {
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94A3B8;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.03);
            padding: 10px 24px;
            font-weight: 600;
        }
        .mockup-nav-tabs .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
        }
        .mockup-nav-tabs .nav-link.active {
            background: var(--accent);
            color: #0f172a !important;
            border-color: var(--accent);
        }

        /* Laptop hardware mock frame */
        .laptop-frame {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 20px;
            padding: 18px 18px 0 18px;
            background: #1e293b;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .laptop-screen-bezel {
            background: #000000;
            border-radius: 10px 10px 0 0;
            padding: 10px;
            position: relative;
            border: 2px solid #0f172a;
        }
        .laptop-screen-content {
            background: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            min-height: 480px;
            color: var(--text-color);
            position: relative;
        }
        .laptop-screen-content .admin-ui {
            background-color: #F8FAFC;
            min-height: 480px;
        }
        .laptop-camera {
            position: absolute;
            top: 3px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #1e293b;
            border-radius: 50%;
        }
        .laptop-base {
            position: relative;
            max-width: 1060px;
            margin: 0 auto;
            height: 16px;
            background: #334155;
            border-radius: 0 0 12px 12px;
            border-bottom: 3px solid #1e293b;
        }
        .laptop-base::after {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 6px;
            background: #0f172a;
            border-radius: 0 0 6px 6px;
        }

        /* Dash UI specific styles inside mockups */
        .mock-sidebar {
            width: 70px;
            background: #0A2B4C;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
            color: rgba(255,255,255,0.7);
        }
        .mock-sidebar-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-smooth);
        }
        .mock-sidebar-icon.active, .mock-sidebar-icon:hover {
            background: rgba(255,255,255,0.15);
            color: #ffffff;
        }

        .mock-topbar {
            background: #ffffff;
            border-bottom: 1px solid #E2E8F0;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .mock-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.01);
        }

        .mock-table {
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        .mock-table th {
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 2px solid #E2E8F0;
            padding: 8px;
        }
        .mock-table td {
            padding: 8px;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
        }

        /* Why DorpFlow Comparison Table */
        .comparison-section {
            padding: 100px 0;
        }

        .comparison-table {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }
        .comparison-table tr {
            transition: var(--transition-smooth);
        }
        .comparison-table th {
            border: none;
            padding: 20px;
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 1.2rem;
        }
        .comparison-table td {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 20px;
            font-size: 1rem;
            vertical-align: middle;
        }
        .comparison-table td:first-child {
            border-left: 1px solid var(--border-color);
            border-radius: 12px 0 0 12px;
            font-weight: 600;
            color: #0f172a;
        }
        .comparison-table td:last-child {
            border-right: 1px solid var(--border-color);
            border-radius: 0 12px 12px 0;
        }

        .comparison-table tr:hover td {
            background: rgba(10, 43, 76, 0.01);
            transform: scale(1.002);
            border-color: rgba(10, 43, 76, 0.15);
        }

        .comp-legacy {
            color: #DC2626;
            background: rgba(220, 38, 38, 0.03) !important;
            border-left: 4px solid #DC2626 !important;
        }
        .comp-dorpflow {
            color: #16A34A;
            background: rgba(22, 163, 74, 0.03) !important;
            border-left: 4px solid #16A34A !important;
            font-weight: 600;
        }

        /* Testimonials */
        .testimonials-section {
            padding: 100px 0;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .testimonial-card {
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            transition: var(--transition-smooth);
        }
        .testimonial-card:hover {
            background: #ffffff;
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(10, 43, 76, 0.05);
            border-color: var(--primary);
        }
        .quote-icon {
            font-size: 2.5rem;
            color: rgba(10, 43, 76, 0.08);
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .testimonial-text {
            font-size: 0.95rem;
            line-height: 1.7;
            color: var(--text-color);
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
        }
        .testimonial-user {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .testimonial-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .user-name {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 2px;
            color: #0f172a;
        }
        .user-role {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Pricing Section */
        .pricing-section {
            padding: 100px 0;
        }

        .pricing-toggle {
            background: #E2E8F0;
            padding: 4px;
            border-radius: 30px;
            display: inline-flex;
            gap: 4px;
            margin-bottom: 50px;
        }
        .pricing-toggle .btn {
            border-radius: 20px;
            font-weight: 600;
            padding: 8px 24px;
            font-size: 0.9rem;
            border: none;
            transition: var(--transition-smooth);
        }
        .pricing-toggle .btn.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .pricing-toggle .btn:not(.active) {
            color: var(--text-muted);
            background: transparent;
        }

        .pricing-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: var(--transition-smooth);
            position: relative;
        }
        .pricing-card.popular {
            border: 2px solid var(--primary);
            box-shadow: 0 20px 45px rgba(10, 43, 76, 0.08);
            transform: scale(1.03);
        }
        @media (max-width: 991px) {
            .pricing-card.popular {
                transform: scale(1);
            }
        }
        .pricing-card.popular .popular-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .plan-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 24px;
        }
        .plan-price-wrapper {
            margin-bottom: 30px;
        }
        .plan-price {
            font-size: 2.8rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }
        .plan-period {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .pricing-features-list {
            padding: 0;
            margin: 0 0 35px 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .pricing-features-list li {
            font-size: 0.95rem;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .pricing-features-list li i {
            font-size: 1rem;
        }
        .pricing-features-list li i.fa-circle-check {
            color: var(--secondary);
        }
        .pricing-features-list li.disabled {
            color: #94A3B8;
            text-decoration: line-through;
        }
        .pricing-features-list li.disabled i {
            color: #CBD5E1;
        }

        /* FAQ Section */
        .faq-section {
            padding: 100px 0;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .accordion-item {
            border: 1px solid var(--border-color) !important;
            border-radius: 12px !important;
            margin-bottom: 16px;
            overflow: hidden;
            background: var(--bg-color);
        }
        .accordion-button {
            font-family: var(--font-heading);
            font-weight: 600;
            color: #0f172a;
            padding: 20px;
            background: var(--bg-color);
            border: none !important;
            box-shadow: none !important;
        }
        .accordion-button:not(.collapsed) {
            background: rgba(10, 43, 76, 0.04);
            color: var(--primary);
        }
        .accordion-body {
            padding: 20px;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.7;
            background: #ffffff;
        }

        /* Call To Action Banner */
        .cta-section {
            padding: 100px 0;
            position: relative;
        }
        .cta-box {
            border-radius: 30px;
            padding: 80px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(10, 43, 76, 0.15);
        }
        .cta-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 80% 20%, rgba(0, 191, 165, 0.45) 0%, rgba(10, 43, 76, 0) 50%),
                        radial-gradient(circle at 10% 80%, rgba(46, 125, 50, 0.45) 0%, rgba(10, 43, 76, 0) 50%);
            opacity: 0.6;
            z-index: 1;
        }
        .cta-content {
            position: relative;
            z-index: 2;
            max-width: 750px;
            margin: 0 auto;
        }
        .cta-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: #ffffff;
        }
        @media (max-width: 767px) {
            .cta-title {
                font-size: 2.2rem;
            }
        }
        .cta-desc {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 40px;
        }

        /* Footer */
        footer {
            background-color: #0f172a;
            color: #94A3B8;
            padding: 80px 0 30px 0;
            border-top: 1px solid rgba(255,255,255,0.05);
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

        /* Custom Newsletter Form style */
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
            <a class="navbar-brand d-flex align-items-center" href="#home">
                <img src="dorpflow.png" alt="DorpFlow Logo" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#solutions">Solutions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#trust">Municipalities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
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

    <!-- HERO SECTION -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-badge">
                        <i class="fa-solid fa-circle-nodes"></i> Smart Municipal Operations Platform
                    </div>
                    <h1 class="hero-heading">
                        Modern Municipal <br>
                        <span class="gradient-text">Service Delivery</span>, <br>
                        Powered by DorpFlow.
                    </h1>
                    <p class="hero-lead">
                        Improve service delivery, manage municipal departments, assign technicians, track service requests, and keep residents informed through one intelligent platform.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="contact.php" class="btn btn-custom btn-primary-custom px-4 py-3">Request a Demo</a>
                        <a href="#" class="btn btn-custom btn-secondary-custom px-4 py-3" data-bs-toggle="modal" data-bs-target="#videoModal">
                            <i class="fa-solid fa-play me-2"></i> Watch Video
                        </a>
                    </div>
                    <ul class="hero-checklist">
                        <li><i class="fa-solid fa-circle-check"></i> Service Delivery</li>
                        <li><i class="fa-solid fa-circle-check"></i> Work Orders</li>
                        <li><i class="fa-solid fa-circle-check"></i> Asset Management</li>
                        <li><i class="fa-solid fa-circle-check"></i> AI Ticketing</li>
                    </ul>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="dashboard-mockup-wrapper">
                        <!-- Stat card 1 -->
                        <div class="floating-stat-card stat-card-1 d-flex align-items-center gap-3">
                            <div class="feature-icon-wrapper mb-0 bg-success-subtle text-success" style="width:40px; height:40px; border-radius:8px; font-size: 1rem;">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">SLA MET RATE</small>
                                <strong style="font-size: 1.1rem; color:#0f172a;">96.4%</strong>
                            </div>
                        </div>

                        <!-- Stat card 2 -->
                        <div class="floating-stat-card stat-card-2 d-flex align-items-center gap-3">
                            <div class="active-dot"></div>
                            <div>
                                <small class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">ACTIVE TECHNICIANS</small>
                                <strong style="font-size: 1.1rem; color:#0f172a;">48 Dispatched</strong>
                            </div>
                        </div>

                        <!-- Stat card 3 -->
                        <div class="floating-stat-card stat-card-3 d-flex align-items-center gap-3">
                            <div class="feature-icon-wrapper mb-0 bg-primary-subtle text-primary" style="width:40px; height:40px; border-radius:8px; font-size: 1rem;">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">AVG. RESOLUTION</small>
                                <strong style="font-size: 1.1rem; color:#0f172a;">2.4 Hours</strong>
                            </div>
                        </div>

                        <!-- Main mock dashboard window -->
                        <div class="dashboard-main-card">
                            <div class="dashboard-header">
                                <div class="window-controls">
                                    <div class="window-dot red"></div>
                                    <div class="window-dot yellow"></div>
                                    <div class="window-dot green"></div>
                                </div>
                                <div class="text-muted" style="font-size:0.8rem; font-weight:600;">
                                    <i class="fa-solid fa-lock me-1"></i> admin.dorpflow.com
                                </div>
                                <div style="width: 40px;"></div>
                            </div>
                            <div class="dashboard-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <div class="mock-card p-3">
                                            <small class="text-muted d-block mb-1" style="font-size:0.7rem; font-weight:600;">TOTAL REQUESTS</small>
                                            <h4 class="mb-0" style="font-size:1.4rem;">1,482</h4>
                                            <span class="text-success" style="font-size:0.7rem; font-weight:600;"><i class="fa-solid fa-arrow-trend-up"></i> +12% this week</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mock-card p-3">
                                            <small class="text-muted d-block mb-1" style="font-size:0.7rem; font-weight:600;">CRITICAL TASKS</small>
                                            <h4 class="mb-0 text-danger" style="font-size:1.4rem;">18 Pending</h4>
                                            <span class="text-muted" style="font-size:0.7rem;">Assigned to departments</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mock-card mb-3 p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0" style="font-size:0.85rem; font-weight:700;">Resolution Velocity</h5>
                                        <span class="badge bg-primary-subtle text-primary" style="font-size:0.65rem;">Live</span>
                                    </div>
                                    <div style="height: 120px; width: 100%;">
                                        <!-- Line Chart Canvas -->
                                        <canvas id="heroMockChart"></canvas>
                                    </div>
                                </div>

                                <div class="mock-card p-3">
                                    <h5 class="mb-2" style="font-size:0.85rem; font-weight:700;">Recent Citizen Reports</h5>
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2" style="font-size:0.75rem;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-danger-subtle text-danger" style="font-size:0.65rem;">Water</span>
                                            <span>Burst Pipe - Ward 4</span>
                                        </div>
                                        <span class="text-muted" style="font-size:0.65rem;">2m ago</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2" style="font-size:0.75rem;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-warning-subtle text-warning" style="font-size:0.65rem;">Roads</span>
                                            <span>Pothole reported - Park Dr</span>
                                        </div>
                                        <span class="text-muted" style="font-size:0.65rem;">12m ago</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between" style="font-size:0.75rem;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success-subtle text-success" style="font-size:0.65rem;">Waste</span>
                                            <span>Illegal dumping cleared</span>
                                        </div>
                                        <span class="text-muted" style="font-size:0.65rem;">45m ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST SECTION -->
    <section class="trust-section" id="trust">
        <div class="container text-center">
            <p class="text-uppercase text-muted fw-bold mb-4" style="font-size: 0.8rem; letter-spacing: 0.15em;">Built for South African Municipalities</p>
            <div class="row align-items-center justify-content-center g-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="municipality-logo">
                        <i class="fa-solid fa-mountain-sun"></i> City of Cape Town
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="municipality-logo">
                        <i class="fa-solid fa-city"></i> Joburg Metro
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="municipality-logo">
                        <i class="fa-solid fa-bridge"></i> Nelson Mandela Bay
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="municipality-logo">
                        <i class="fa-solid fa-tree-deciduous"></i> Tshwane Metro
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="municipality-logo">
                        <i class="fa-solid fa-water"></i> eThekwini Metro
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LIVE STATISTICS -->
    <section class="stats-section bg-light" id="stats">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="100">
                    <div class="stat-box">
                        <div class="stat-number" data-target="10000">0</div>
                        <h5 class="text-muted fw-bold" style="font-size:0.9rem;">Tickets Processed</h5>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="200">
                    <div class="stat-box">
                        <div class="stat-number" data-target="99.8" data-decimal="true">0%</div>
                        <h5 class="text-muted fw-bold" style="font-size:0.9rem;">System Uptime</h5>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                    <div class="stat-box">
                        <div class="stat-number" data-target="150">0</div>
                        <h5 class="text-muted fw-bold" style="font-size:0.9rem;">Departments</h5>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                    <div class="stat-box">
                        <div class="stat-number" data-target="24">24/7</div>
                        <h5 class="text-muted fw-bold" style="font-size:0.9rem;">Citizen Support</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features-section py-5" id="features">
        <div class="container py-5">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag">Powerful Modules</span>
                <h2 class="section-title">Designed for modern government operations</h2>
                <p class="text-muted">DorpFlow consolidates all municipal workflows into a single cloud-native dashboard, accelerating public service and ensuring accountability.</p>
            </div>
            
            <div class="row g-4">
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h3 class="feature-title">Citizen Portal</h3>
                        <p class="feature-desc">Residents can report issues online, upload geo-tagged photos of service failures, and track repair status in real-time.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <h3 class="feature-title">Staff Ticketing</h3>
                        <p class="feature-desc">Municipal employees can log citizen calls, resolve walk-in queries, track histories, and create tickets internally.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-sitemap"></i>
                        </div>
                        <h3 class="feature-title">Department Management</h3>
                        <p class="feature-desc">Automatically route tickets to corresponding departments (Water, Electricity, Roads) based on category and region.</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-mobile-screen-button"></i>
                        </div>
                        <h3 class="feature-title">Technician Mobile Dashboard</h3>
                        <p class="feature-desc">Field technicians receive instant work orders on their mobile app, navigate with GPS, and submit photos of completed work.</p>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <h3 class="feature-title">Asset Management</h3>
                        <p class="feature-desc">Map infrastructure assets like water pumps, transformers, and streetlights. Track maintenance logs and reduce replacement costs.</p>
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h3 class="feature-title">GIS Mapping</h3>
                        <p class="feature-desc">Interactive geographic charts display complaints, identifying recurring hotspots for proactive infrastructure planning.</p>
                    </div>
                </div>
                <!-- Card 7 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-truck-pickup"></i>
                        </div>
                        <h3 class="feature-title">Fleet Management</h3>
                        <p class="feature-desc">Track municipal response vehicles with real-time GPS, optimize route planning, and audit fuel expenditures.</p>
                    </div>
                </div>
                <!-- Card 8 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Analytics</h3>
                        <p class="feature-desc">Generate monthly service delivery reports, track average resolution durations, and identify outstanding ward backlogs.</p>
                    </div>
                </div>
                <!-- Card 9 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-brain"></i>
                        </div>
                        <h3 class="feature-title">AI Assistant</h3>
                        <p class="feature-desc">Auto-classify reports from photos or text, assess severity, detect duplicate complaints, and pre-fill task routes.</p>
                    </div>
                </div>
                <!-- Card 10 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <h3 class="feature-title">Notifications</h3>
                        <p class="feature-desc">Keep citizens and management informed. Auto-trigger email, WhatsApp, and SMS status changes instantly.</p>
                    </div>
                </div>
                <!-- Card 11 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 class="feature-title">SLA Tracking</h3>
                        <p class="feature-desc">Enforce service level targets. Escalate overdue work items automatically to department supervisors.</p>
                    </div>
                </div>
                <!-- Card 12 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-file-shield"></i>
                        </div>
                        <h3 class="feature-title">Document Management</h3>
                        <p class="feature-desc">Store regulatory files, technical building blueprints, road repairs specifications, and citizen receipts securely.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS TIMELINE -->
    <section class="timeline-section" id="how-it-works">
        <div class="container">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag">Seamless Workflows</span>
                <h2 class="section-title">The Lifespan of a Service Request</h2>
                <p class="text-muted">See how DorpFlow connects citizens, municipal control rooms, and field technicians to close complaints rapidly.</p>
            </div>

            <div class="timeline-container" id="timelineContainer">
                <div class="timeline-progress-bar">
                    <div class="timeline-progress-active" id="timelineProgress"></div>
                </div>
                
                <div class="timeline-step completed" data-step="1" data-title="Resident Reports" data-desc="Resident files a geo-tagged report (e.g. water leak) via the portal, app, or USSD code.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-house-user"></i>
                    </div>
                    <div class="timeline-label">Resident</div>
                </div>
                
                <div class="timeline-step" data-step="2" data-title="Ticket Created" data-desc="The system automatically creates a structured ticket, generates a unique ID, and sends a confirmation SMS.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <div class="timeline-label">Ticket Created</div>
                </div>
                
                <div class="timeline-step" data-step="3" data-title="Supervisor Reviews" data-desc="The municipal control room reviews the ticket, verifies details, and accepts the request.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="timeline-label">Supervisor Reviews</div>
                </div>
                
                <div class="timeline-step" data-step="4" data-title="Department Assigned" data-desc="Based on the report category, the ticket is dispatched to the relevant department head for resource allocation.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div class="timeline-label">Department Assigned</div>
                </div>
                
                <div class="timeline-step" data-step="5" data-title="Technician Dispatched" data-desc="A technician is assigned on their mobile app, receives map directions, and travels to the site.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-truck-pickup"></i>
                    </div>
                    <div class="timeline-label">Technician Dispatched</div>
                </div>
                
                <div class="timeline-step" data-step="6" data-title="Work Completed" data-desc="Technician repairs the issue, uploads 'after' photos, and flags the ticket resolved.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="timeline-label">Work Completed</div>
                </div>
                
                <div class="timeline-step" data-step="7" data-title="Resident Confirms" data-desc="Citizen receives a WhatsApp/SMS alert to confirm they are satisfied with the outcome.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <div class="timeline-label">Resident Confirms</div>
                </div>
                
                <div class="timeline-step" data-step="8" data-title="Ticket Closed" data-desc="The SLA is archived, metrics are logged on the analytics board, and the ticket is officially closed.">
                    <div class="timeline-node">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="timeline-label">Closed</div>
                </div>
            </div>

            <!-- Detail box below steps -->
            <div class="timeline-detail-box" data-aos="fade-up" data-aos-duration="600">
                <h4 id="timelineDetailTitle" class="gradient-text mb-2">Resident Reports</h4>
                <p id="timelineDetailDesc" class="mb-0 text-muted">Resident files a geo-tagged report (e.g. water leak) via the portal, app, or USSD code.</p>
            </div>
        </div>
    </section>

    <!-- SOLUTIONS GRID -->
    <section class="solutions-section bg-light" id="solutions">
        <div class="container">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag">Tailored Solutions</span>
                <h2 class="section-title">Departmental Workspaces</h2>
                <p class="text-muted">Custom settings and operational control dashboards optimized for every branch of municipal government.</p>
            </div>

            <div class="row g-4">
                <!-- Dept 1 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="100">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-droplet"></i></div>
                        <div class="dept-info">
                            <h4>Water Department</h4>
                            <p>Burst pipes, leaks, meters</p>
                            <span class="dept-badge bg-success-subtle text-success">SLA: 98% Speed</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 2 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="200">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-bolt"></i></div>
                        <div class="dept-info">
                            <h4>Electricity Department</h4>
                            <p>Substations, outages, lights</p>
                            <span class="dept-badge bg-success-subtle text-success">Active Dispatch</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 3 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-road"></i></div>
                        <div class="dept-info">
                            <h4>Roads</h4>
                            <p>Potholes, traffic lights, drains</p>
                            <span class="dept-badge bg-primary-subtle text-primary">GPS Monitored</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 4 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-trash-can"></i></div>
                        <div class="dept-info">
                            <h4>Waste Management</h4>
                            <p>Refuse collection, dumping</p>
                            <span class="dept-badge bg-success-subtle text-success">Smart Routes</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 5 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="100">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-tree"></i></div>
                        <div class="dept-info">
                            <h4>Parks</h4>
                            <p>Verge cutting, parks, trees</p>
                            <span class="dept-badge bg-muted text-muted">Seasonal Logs</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 6 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="200">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-house-chimney"></i></div>
                        <div class="dept-info">
                            <h4>Housing</h4>
                            <p>Hostels, RDP housing registries</p>
                            <span class="dept-badge bg-warning-subtle text-warning">Audited Vault</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 7 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-coins"></i></div>
                        <div class="dept-info">
                            <h4>Finance</h4>
                            <p>Rates, water bills, receipts</p>
                            <span class="dept-badge bg-primary-subtle text-primary">POPIA Secure</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 8 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-headset"></i></div>
                        <div class="dept-info">
                            <h4>IT Helpdesk</h4>
                            <p>Internal network, emails</p>
                            <span class="dept-badge bg-success-subtle text-success">99.9% Uptime</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 9 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="100">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-users-gear"></i></div>
                        <div class="dept-info">
                            <h4>HR Helpdesk</h4>
                            <p>Leave requests, timesheets</p>
                            <span class="dept-badge bg-muted text-muted">Self-Service</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 10 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="200">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-truck-monster"></i></div>
                        <div class="dept-info">
                            <h4>Fleet</h4>
                            <p>Vehicle service, repairs</p>
                            <span class="dept-badge bg-danger-subtle text-danger">Real-Time GPS</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 11 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="dept-info">
                            <h4>Public Safety</h4>
                            <p>Metro police, traffic control</p>
                            <span class="dept-badge bg-primary-subtle text-primary">Emergency Hot</span>
                        </div>
                    </div>
                </div>
                <!-- Dept 12 -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                    <div class="dept-card">
                        <div class="dept-icon"><i class="fa-solid fa-building-circle-check"></i></div>
                        <div class="dept-info">
                            <h4>Facilities Management</h4>
                            <p>Community halls, clinics</p>
                            <span class="dept-badge bg-success-subtle text-success">Scheduled Audits</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INTERACTIVE SCREENSHOTS / MOCKUPS -->
    <section class="mockups-section" id="screenshots">
        <div class="container">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag" style="color: var(--accent);">Operational Previews</span>
                <h2 class="section-title">See DorpFlow in Action</h2>
                <p class="text-muted" style="color: #94A3B8 !important;">Experience specialized portals engineered for every tier of municipal administration and citizenship.</p>
            </div>

            <!-- Tab Navs -->
            <ul class="nav nav-tabs mockup-nav-tabs" id="mockupTabs" role="tablist" data-aos="fade-up" data-aos-duration="800">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-pane" type="button" role="tab" aria-controls="admin-pane" aria-selected="true">Admin Dashboard</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="resident-tab" data-bs-toggle="tab" data-bs-target="#resident-pane" type="button" role="tab" aria-controls="resident-pane" aria-selected="false">Resident Dashboard</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="technician-tab" data-bs-toggle="tab" data-bs-target="#technician-pane" type="button" role="tab" aria-controls="technician-pane" aria-selected="false">Technician Dashboard</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manager-tab" data-bs-toggle="tab" data-bs-target="#manager-pane" type="button" role="tab" aria-controls="manager-pane" aria-selected="false">Manager Dashboard</button>
                </li>
            </ul>

            <!-- Laptop Outer Frame -->
            <div class="laptop-frame" data-aos="zoom-in" data-aos-duration="1000">
                <div class="laptop-screen-bezel">
                    <div class="laptop-camera"></div>
                    <div class="laptop-screen-content tab-content" id="mockupTabContent">
                        
                        <!-- PANE 1: ADMIN DASHBOARD -->
                        <div class="tab-pane fade show active admin-ui" id="admin-pane" role="tabpanel" aria-labelledby="admin-tab">
                            <div class="d-flex" style="min-height: 480px;">
                                <!-- Mock Sidebar -->
                                <div class="mock-sidebar">
                                    <div class="mock-sidebar-icon active"><i class="fa-solid fa-chart-pie"></i></div>
                                    <div class="mock-sidebar-icon"><i class="fa-solid fa-list-check"></i></div>
                                    <div class="mock-sidebar-icon"><i class="fa-solid fa-users"></i></div>
                                    <div class="mock-sidebar-icon"><i class="fa-solid fa-map"></i></div>
                                    <div class="mock-sidebar-icon"><i class="fa-solid fa-gear"></i></div>
                                </div>
                                <!-- Mock Main Panel -->
                                <div class="flex-grow-1 d-flex flex-column">
                                    <div class="mock-topbar">
                                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-building me-2 text-primary"></i>Tshwane Metro Control Room</h6>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Online</span>
                                            <i class="fa-regular fa-bell"></i>
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size:0.8rem; font-weight:700;">AM</div>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-light flex-grow-1" style="overflow-y: auto;">
                                        <!-- Row 1 Cards -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-4">
                                                <div class="mock-card py-2 px-3">
                                                    <small class="text-muted d-block" style="font-size:0.65rem;">BURST WATER PIPES</small>
                                                    <h5 class="mb-0" style="font-size: 1.1rem; color: #DC2626;">24 Unresolved</h5>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="mock-card py-2 px-3">
                                                    <small class="text-muted d-block" style="font-size:0.65rem;">ELECTRICITY FAULTS</small>
                                                    <h5 class="mb-0" style="font-size: 1.1rem; color: #D97706;">12 In Progress</h5>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="mock-card py-2 px-3">
                                                    <small class="text-muted d-block" style="font-size:0.65rem;">SLA DISPATCH SPEED</small>
                                                    <h5 class="mb-0" style="font-size: 1.1rem; color: #16A34A;">94.2% Met</h5>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Table and Mini Chart -->
                                        <div class="row g-3">
                                            <div class="col-7">
                                                <div class="mock-card">
                                                    <h6 class="mb-2" style="font-size: 0.8rem; font-weight:700;">Open Work Orders</h6>
                                                    <table class="table mock-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Ticket ID</th>
                                                                <th>Location</th>
                                                                <th>Technician</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>#WAT-4029</td>
                                                                <td>Mamelodi Sector 4</td>
                                                                <td>J. Pieterse</td>
                                                                <td><span class="badge bg-warning text-dark">Dispatched</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>#ELE-3922</td>
                                                                <td>Pretoria East</td>
                                                                <td>S. Gumede</td>
                                                                <td><span class="badge bg-danger">Escalated</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>#RDS-2911</td>
                                                                <td>Centurion Ward 2</td>
                                                                <td>V. Naidoo</td>
                                                                <td><span class="badge bg-info text-dark">In Progress</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="mock-card">
                                                    <h6 class="mb-2" style="font-size: 0.8rem; font-weight:700;">Reports by Department</h6>
                                                    <div style="height: 120px;">
                                                        <canvas id="mockChartAdmin"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PANE 2: RESIDENT DASHBOARD -->
                        <div class="tab-pane fade admin-ui" id="resident-pane" role="tabpanel" aria-labelledby="resident-tab">
                            <div class="d-flex flex-column" style="min-height: 480px;">
                                <div class="mock-topbar bg-white">
                                    <h6 class="mb-0 fw-bold text-success"><i class="fa-solid fa-people-roof me-2"></i>DorpFlow Citizen Service Portal</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark">Ward 14 (Hillside)</span>
                                        <button class="btn btn-sm btn-outline-primary" style="font-size:0.75rem;">Log Out</button>
                                    </div>
                                </div>
                                <div class="p-4 bg-light flex-grow-1 d-flex justify-content-center align-items-center">
                                    <div class="bg-white rounded-3 shadow-sm border p-4 w-100" style="max-width: 500px;">
                                        <h5 class="mb-3 text-center" style="font-size:1.1rem; font-weight:700;">Report Service Delivery Fault</h5>
                                        <form onsubmit="event.preventDefault(); alert('Demo Ticket Submitted Successfully!');">
                                            <div class="mb-3">
                                                <label class="form-label" style="font-size:0.75rem; font-weight:600;">Category</label>
                                                <select class="form-select form-select-sm">
                                                    <option>Water Leak / Burst Pipe</option>
                                                    <option>Electricity Outage / Cable Theft</option>
                                                    <option>Pothole / Damaged Road</option>
                                                    <option>Refuse Collection Missed</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-size:0.75rem; font-weight:600;">Description</label>
                                                <textarea class="form-control form-control-sm" rows="2" placeholder="Describe the problem..."></textarea>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" style="font-size:0.75rem;"><i class="fa-solid fa-camera me-1"></i> Add Photo</button>
                                                </div>
                                                <div class="col-6">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" style="font-size:0.75rem;"><i class="fa-solid fa-location-crosshairs me-1"></i> Auto GPS</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary-custom w-100" style="font-size:0.85rem;">Submit Ticket</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PANE 3: TECHNICIAN DASHBOARD -->
                        <div class="tab-pane fade admin-ui" id="technician-pane" role="tabpanel" aria-labelledby="technician-tab">
                            <div class="d-flex justify-content-center align-items-center bg-dark" style="min-height: 480px; padding: 20px 0;">
                                <!-- Phone Mock Frame -->
                                <div class="bg-black p-3 rounded-5 border border-secondary shadow-lg" style="width: 260px; border-width: 4px !important;">
                                    <div class="bg-white rounded-4 overflow-hidden" style="height: 380px; font-size:0.8rem; color:#1E293B;">
                                        <!-- App Header -->
                                        <div class="bg-primary text-white p-2 d-flex justify-content-between align-items-center">
                                            <span style="font-size:0.65rem; font-weight:600;"><i class="fa-solid fa-truck-pickup me-1"></i>DorpFlow Field</span>
                                            <span class="badge bg-success" style="font-size:0.5rem;">Online</span>
                                        </div>
                                        <!-- App Content -->
                                        <div class="p-3 bg-light d-flex flex-column justify-content-between" style="height: 340px;">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="text-muted" style="font-size:0.6; font-size: 0.6rem;">CURRENT TASK</span>
                                                    <span class="badge bg-danger" style="font-size:0.5rem;">High Priority</span>
                                                </div>
                                                <h6 class="mb-1" style="font-size:0.85rem; font-weight:700;">Repair Water Meter</h6>
                                                <p class="text-muted mb-2" style="font-size:0.7rem;"><i class="fa-solid fa-location-dot text-danger"></i> 42 Jacaranda St, Ward 12</p>
                                                
                                                <div class="border rounded p-2 bg-white mb-2" style="font-size:0.65rem;">
                                                    <strong>Citizen Notes:</strong> "Meter has been spraying water since 08:00 AM."
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-sm btn-primary" style="font-size:0.7rem;"><i class="fa-solid fa-map-location-dot me-1"></i> Navigate GPS</button>
                                                <button class="btn btn-sm btn-success" style="font-size:0.7rem;" onclick="alert('Work marked as complete in demo mode.');"><i class="fa-solid fa-check me-1"></i> Mark Completed</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PANE 4: MANAGER DASHBOARD -->
                        <div class="tab-pane fade admin-ui" id="manager-pane" role="tabpanel" aria-labelledby="manager-tab">
                            <div class="d-flex flex-column" style="min-height: 480px;">
                                <div class="mock-topbar bg-white">
                                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-chart-line me-2 text-success"></i>Municipal Performance Analytics</h6>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem;"><i class="fa-solid fa-file-export me-1"></i> Export PDF</button>
                                    </div>
                                </div>
                                <div class="p-3 bg-light flex-grow-1" style="overflow-y: auto;">
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <div class="mock-card">
                                                <h6 class="mb-2" style="font-size: 0.8rem; font-weight:700;">Departmental Performance (Weekly)</h6>
                                                <div style="height: 150px;">
                                                    <canvas id="mockChartManagerPie"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mock-card">
                                                <h6 class="mb-2" style="font-size: 0.8rem; font-weight:700;">SLA Resolution Performance</h6>
                                                <div style="height: 150px;">
                                                    <canvas id="mockChartManagerBar"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mock-card">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0" style="font-size:0.8rem; font-weight:700;">Municipal Ward Efficiency</h6>
                                            <span class="text-success" style="font-size:0.7rem; font-weight:600;"><i class="fa-solid fa-circle-check"></i> Overall target met</span>
                                        </div>
                                        <div class="row g-2 text-center" style="font-size:0.75rem;">
                                            <div class="col-3 border-end">
                                                <span class="text-muted d-block">Ward 1</span>
                                                <strong class="text-success">98.2% Resolved</strong>
                                            </div>
                                            <div class="col-3 border-end">
                                                <span class="text-muted d-block">Ward 2</span>
                                                <strong class="text-success">95.4% Resolved</strong>
                                            </div>
                                            <div class="col-3 border-end">
                                                <span class="text-muted d-block">Ward 3</span>
                                                <strong class="text-warning">89.1% Resolved</strong>
                                            </div>
                                            <div class="col-3">
                                                <span class="text-muted d-block">Ward 4</span>
                                                <strong class="text-danger">76.3% Resolved</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Laptop Stand -->
            <div class="laptop-base" data-aos="fade-up" data-aos-duration="800"></div>
        </div>
    </section>

    <!-- WHY DORPFLOW COMPARISON -->
    <section class="comparison-section" id="why-dorpflow">
        <div class="container">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag">Platform Comparison</span>
                <h2 class="section-title">Why Municipalities Choose DorpFlow</h2>
                <p class="text-muted">A direct comparison of legacy administration procedures versus modern digital dispatch.</p>
            </div>

            <div class="table-responsive" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <table class="table comparison-table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 30%;">Operational Parameter</th>
                            <th scope="col" style="width: 35%; text-align: left;" class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Traditional System</th>
                            <th scope="col" style="width: 35%; text-align: left;" class="text-success"><i class="fa-solid fa-square-check"></i> DorpFlow System</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Report Channels</td>
                            <td class="comp-legacy">Phone Calls, long physical queues, undocumented logs</td>
                            <td class="comp-dorpflow">Online Tickets, SMS integration, USSD channels, AI Ticketing</td>
                        </tr>
                        <tr>
                            <td>Job Dispatch</td>
                            <td class="comp-legacy">Paper Files, manual printed job-cards, hand-delivered instructions</td>
                            <td class="comp-dorpflow">Digital Workflow, automated assignment, mobile technician routing</td>
                        </tr>
                        <tr>
                            <td>Request Tracking</td>
                            <td class="comp-legacy">No Tracking, lost files, repeat complaints</td>
                            <td class="comp-dorpflow">Real-Time Tracking, SMS updates, GPS technician progress tracking</td>
                        </tr>
                        <tr>
                            <td>Department Oversight</td>
                            <td class="comp-legacy">Slow Reporting, weekly audits, delayed spreadsheet reporting</td>
                            <td class="comp-dorpflow">Analytics Dashboard, live departmental charts, GIS mapping</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag">Partner Success</span>
                <h2 class="section-title">Trusted by officials & residents alike</h2>
                <p class="text-muted">Feedback from municipal officials, engineers, and citizens actively using our infrastructure solution.</p>
            </div>

            <div class="row g-4">
                <!-- Testimonial 1 -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon"></i>
                        <p class="testimonial-text">
                            "With DorpFlow, our department's response time to burst water pipes dropped from 48 hours to just 4. The automated escalation routes ensure outstanding tickets are resolved instantly."
                        </p>
                        <div class="testimonial-user">
                            <div class="testimonial-avatar">SD</div>
                            <div>
                                <h6 class="user-name">Sizwe Dube</h6>
                                <p class="user-role">Municipal Manager, Mangaung</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon"></i>
                        <p class="testimonial-text">
                            "Managing the control room used to be a stressful job card nightmare. Now I can dispatch tickets to technicians with a click, tracking their live GPS coordinates directly from the dashboard."
                        </p>
                        <div class="testimonial-user">
                            <div class="testimonial-avatar">EN</div>
                            <div>
                                <h6 class="user-name">Elena Ndlovu</h6>
                                <p class="user-role">Control Room Supervisor</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 3 -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon"></i>
                        <p class="testimonial-text">
                            "The mobile app makes my field shifts simple. I get my work orders with exact location maps, capture before-and-after photos, and don't need to return to headquarters for paper sign-offs."
                        </p>
                        <div class="testimonial-user">
                            <div class="testimonial-avatar">JP</div>
                            <div>
                                <h6 class="user-name">Jaco Pieterse</h6>
                                <p class="user-role">Field Technician, Electricity</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 4 -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon"></i>
                        <p class="testimonial-text">
                            "I submitted a photo of a broken streetlight in our ward and received an automated SMS reference code. Within 24 hours, technicians fixed the light and texted me when it was resolved."
                        </p>
                        <div class="testimonial-user">
                            <div class="testimonial-avatar">SM</div>
                            <div>
                                <h6 class="user-name">Sarah Miller</h6>
                                <p class="user-role">Resident, Hillside Ward 14</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section class="pricing-section bg-light" id="pricing">
        <div class="container text-center">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag">Pricing Plans</span>
                <h2 class="section-title">Tailored Municipal Subscriptions</h2>
                <p class="text-muted">Flexible tiers designed for small municipalities, large districts, and high-volume metropolitans.</p>
            </div>

            <!-- Switch monthly / annual -->
            <div class="pricing-toggle" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <button class="btn active" id="btnMonthly">Monthly Billed</button>
                <button class="btn" id="btnAnnually">Annually Billed <span class="badge bg-success ms-1">Save 20%</span></button>
            </div>

            <div class="row g-4 justify-content-center align-items-stretch">
                <!-- Plan 1 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="pricing-card">
                        <div>
                            <div class="plan-name">Starter Plan</div>
                            <p class="plan-desc">For small local councils and local administrative boards.</p>
                            <div class="plan-price-wrapper">
                                <span class="plan-price" id="priceLocal">R4,999</span>
                                <span class="plan-period">/ month</span>
                            </div>
                            <ul class="pricing-features-list text-start">
                                <li><i class="fa-solid fa-circle-check"></i> Up to 15 Staff Accounts</li>
                                <li><i class="fa-solid fa-circle-check"></i> Citizen Fault Portal</li>
                                <li><i class="fa-solid fa-circle-check"></i> Water & Electricity Routing</li>
                                <li><i class="fa-solid fa-circle-check"></i> SMS & Email Alerts</li>
                                <li class="disabled"><i class="fa-solid fa-circle-xmark"></i> AI Smart Classifier</li>
                                <li class="disabled"><i class="fa-solid fa-circle-xmark"></i> GPS Fleet tracking</li>
                            </ul>
                        </div>
                        <a href="contact.php" class="btn btn-custom btn-secondary-custom w-100">Get Started</a>
                    </div>
                </div>

                <!-- Plan 2 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="pricing-card popular">
                        <div class="popular-badge">Most Popular</div>
                        <div>
                            <div class="plan-name">Professional Plan</div>
                            <p class="plan-desc">Perfect for growing municipalities and regional councils.</p>
                            <div class="plan-price-wrapper">
                                <span class="plan-price" id="priceDistrict">R14,999</span>
                                <span class="plan-period">/ month</span>
                            </div>
                            <ul class="pricing-features-list text-start">
                                <li><i class="fa-solid fa-circle-check"></i> Unlimited Staff Accounts</li>
                                <li><i class="fa-solid fa-circle-check"></i> Full Citizen Portal & Mobile App</li>
                                <li><i class="fa-solid fa-circle-check"></i> All 12 Departments Enabled</li>
                                <li><i class="fa-solid fa-circle-check"></i> GPS Fleet & Route Optimization</li>
                                <li><i class="fa-solid fa-circle-check"></i> Advanced SLA escalations</li>
                                <li class="disabled"><i class="fa-solid fa-circle-xmark"></i> Dedicated Server Instance</li>
                            </ul>
                        </div>
                        <a href="contact.php" class="btn btn-custom btn-primary-custom w-100">Choose Plan</a>
                    </div>
                </div>

                <!-- Plan 3 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                    <div class="pricing-card">
                        <div>
                            <div class="plan-name">Enterprise Plan</div>
                            <p class="plan-desc">For large cities requiring dedicated instances and custom SLAs.</p>
                            <div class="plan-price-wrapper">
                                <span class="plan-price">Custom Pricing</span>
                                <span class="plan-period">Enterprise</span>
                            </div>
                            <ul class="pricing-features-list text-start">
                                <li><i class="fa-solid fa-circle-check"></i> Multiple Municipal instances</li>
                                <li><i class="fa-solid fa-circle-check"></i> Custom GIS & GIS integrations</li>
                                <li><i class="fa-solid fa-circle-check"></i> AI ticket auto-assignment</li>
                                <li><i class="fa-solid fa-circle-check"></i> Local hosting options (AWS/Azure)</li>
                                <li><i class="fa-solid fa-circle-check"></i> Dedicated SLA Support Account</li>
                                <li><i class="fa-solid fa-circle-check"></i> Custom API Integrations</li>
                            </ul>
                        </div>
                        <a href="contact.php" class="btn btn-custom btn-secondary-custom w-100">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="faq-section" id="faq">
        <div class="container">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <span class="section-tag">Got Questions?</span>
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="text-muted">Everything you need to know about implementing DorpFlow in your local government structure.</p>
            </div>

            <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        
                        <!-- FAQ 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Is DorpFlow secure and POPIA compliant?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, absolutely. DorpFlow takes security and POPIA compliance extremely seriously. All personal data collected (names, phone numbers, email addresses, and locations) is fully encrypted in transit and at rest. We adhere strictly to South Africa's POPIA legislation and offer tools for users to request data purging or view their recorded information.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What support SLA and uptime guarantees do you provide?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We guarantee a 99.8% platform uptime backed by our service level agreements. For our District and Metro tiers, we provide 24/7 priority support with emergency critical response times under 30 minutes, managed by our dedicated South African engineering team.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Where are your cloud servers hosted?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    DorpFlow is hosted locally in Microsoft Azure and AWS hyper-scale data centers located in Cape Town and Johannesburg. This guarantees ultra-low latencies for field technicians and ensures that all governmental records remain within South Africa's borders.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    How long does the implementation and onboarding process take?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    A standard Local Board or District deployment takes between 4 to 6 weeks. This includes importing existing asset lists, establishing municipal departments, configuring SLA timelines, and setting up communication channels. Larger custom metro solutions may take 8 to 12 weeks.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Do you provide training for technicians and control room supervisors?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. We host interactive training workshops for control room dispatchers and supervisors. For field workers, we provide an intuitive mobile walkthrough and short, multilingual video guides to ensure immediate comfort with the application.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 6 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    Can we migrate data from our legacy spreadsheet or database systems?
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our engineering team handles data ingestion. We assist with migrating details on past service requests, geographic coordinate lists of utility poles/transformers, ward boundaries, and technician schedules from older CSV/Excel files or legacy SQL database platforms.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="cta-section bg-light" id="contact">
        <div class="container">
            <div class="cta-box gradient-bg-accent" data-aos="zoom-in" data-aos-duration="1000">
                <div class="cta-content">
                    <h2 class="cta-title">Ready to modernize your municipality?</h2>
                    <p class="cta-desc">Join dozens of South African local governments improving service efficiency, enhancing audit reports, and rebuilding citizen trust with DorpFlow.</p>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-7">
                            <form onsubmit="event.preventDefault(); alert('Thank you for booking a demo! Our public sector team will contact your office within 24 hours.');" class="d-flex gap-2">
                                <input type="email" class="form-control border-0 rounded-3 py-3" placeholder="Enter municipal email..." required style="box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <button type="submit" class="btn btn-custom btn-accent-custom px-4 text-nowrap">Book Demo</button>
                            </form>
                            <div class="mt-3 text-white-50" style="font-size: 0.85rem;">
                                Or contact our public sector team directly: <strong class="text-white">sales@dorpflow.com</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <li><a href="#solutions">Water Department</a></li>
                        <li><a href="#solutions">Electricity & Outages</a></li>
                        <li><a href="#solutions">Roads & Stormwater</a></li>
                        <li><a href="#solutions">Waste Management</a></li>
                        <li><a href="#solutions">Municipal Fleet Dispatch</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="#features">Platform Features</a></li>
                        <li><a href="#pricing">Subscription Pricing</a></li>
                        <li><a href="#faq">Frequently Asked Questions</a></li>
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

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bg-transparent">
                <div class="modal-header border-0 p-0 mb-2 justify-content-end">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 rounded-4 overflow-hidden shadow-lg" style="aspect-ratio: 16/9; background: #000;">
                    <!-- A beautiful CSS animated player or placeholder video explaining the system -->
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-white p-5 text-center">
                        <i class="fa-solid fa-circle-play text-accent mb-3 animate-pulse" style="font-size: 4rem;"></i>
                        <h4 class="text-white">DorpFlow Operational Overview Video</h4>
                        <p class="text-muted-light mb-0" style="max-width: 500px;">This 2-minute overview demonstrates control room dispatching, citizen updates, and mobile technician workflows in action.</p>
                        <button class="btn btn-accent-custom mt-4 btn-sm btn-custom px-4" data-bs-dismiss="modal">Request Live Personal Demo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- AOS (Animate on Scroll) -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Javascript Actions and Interactive Functions -->
    <script>
        // Init AOS
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNavbar');
            if (window.scrollY > 40) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Chart.js Mock Dashboard Graphs
        window.addEventListener('load', function() {
            // Chart 1: Hero mockup chart (Resolution Velocity)
            const ctxHero = document.getElementById('heroMockChart').getContext('2d');
            new Chart(ctxHero, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Resolution velocity (Hours)',
                        data: [4.2, 3.8, 2.9, 2.7, 2.4, 1.8, 1.5],
                        borderColor: '#00BFA5',
                        backgroundColor: 'rgba(0, 191, 165, 0.08)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#00BFA5'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                        y: { 
                            grid: { color: '#F1F5F9' },
                            ticks: { 
                                font: { size: 9 },
                                callback: function(value) { return value + 'h'; }
                            } 
                        }
                    }
                }
            });

            // Chart 2: Admin Mock (Reports by Department)
            const ctxAdmin = document.getElementById('mockChartAdmin').getContext('2d');
            new Chart(ctxAdmin, {
                type: 'doughnut',
                data: {
                    labels: ['Water', 'Electricity', 'Roads', 'Waste'],
                    datasets: [{
                        data: [40, 25, 20, 15],
                        backgroundColor: ['#0A2B4C', '#D97706', '#00BFA5', '#2E7D32'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 8 },
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Chart 3: Manager Pie (Weekly tickets)
            const ctxMgrPie = document.getElementById('mockChartManagerPie').getContext('2d');
            new Chart(ctxMgrPie, {
                type: 'pie',
                data: {
                    labels: ['Resolved', 'In Progress', 'Overdue'],
                    datasets: [{
                        data: [72, 20, 8],
                        backgroundColor: ['#2E7D32', '#00BFA5', '#DC2626'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 8 },
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Chart 4: Manager Bar (SLA efficiency)
            const ctxMgrBar = document.getElementById('mockChartManagerBar').getContext('2d');
            new Chart(ctxMgrBar, {
                type: 'bar',
                data: {
                    labels: ['Water', 'Elect', 'Roads', 'Waste'],
                    datasets: [{
                        label: 'SLA Speed Met %',
                        data: [98, 92, 85, 96],
                        backgroundColor: '#0A2B4C',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { ticks: { font: { size: 8 } } },
                        y: { 
                            max: 100,
                            ticks: { 
                                font: { size: 8 },
                                callback: function(value) { return value + '%'; }
                            } 
                        }
                    }
                }
            });
        });

        // Interactive timeline stepping actions
        const timelineSteps = document.querySelectorAll('.timeline-step');
        const timelineProgress = document.getElementById('timelineProgress');
        const detailTitle = document.getElementById('timelineDetailTitle');
        const detailDesc = document.getElementById('timelineDetailDesc');

        timelineSteps.forEach((step, idx) => {
            step.addEventListener('mouseenter', function() {
                const targetStep = parseInt(this.getAttribute('data-step'));
                updateTimelineToStep(targetStep);
            });
        });

        function updateTimelineToStep(stepNum) {
            // Update active states
            timelineSteps.forEach((s) => {
                const currentStep = parseInt(s.getAttribute('data-step'));
                s.classList.remove('active', 'completed');
                if (currentStep < stepNum) {
                    s.classList.add('completed');
                } else if (currentStep === stepNum) {
                    s.classList.add('active');
                    // Update detail box text
                    detailTitle.textContent = s.getAttribute('data-title');
                    detailDesc.textContent = s.getAttribute('data-desc');
                }
            });

            // Update line width percentage
            const progressPercent = ((stepNum - 1) / (timelineSteps.length - 1)) * 100;
            if (window.innerWidth <= 991) {
                // Vertical progress height
                timelineProgress.style.height = progressPercent + '%';
                timelineProgress.style.width = '100%';
            } else {
                // Horizontal progress width
                timelineProgress.style.width = progressPercent + '%';
                timelineProgress.style.height = '100%';
            }
        }

        // Live statistics animated numbers observer
        const statNumbers = document.querySelectorAll('.stat-number');
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const targetEl = entry.target;
                    const finalVal = parseFloat(targetEl.getAttribute('data-target'));
                    const isDecimal = targetEl.getAttribute('data-decimal') === 'true';
                    animateNumber(targetEl, finalVal, isDecimal);
                    statsObserver.unobserve(targetEl);
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(n => statsObserver.observe(n));

        function animateNumber(element, finalVal, isDecimal) {
            let startVal = 0;
            const duration = 2000; // 2 seconds
            const startTime = performance.now();

            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // easeOutQuad curve
                const easeProgress = progress * (2 - progress);
                const currentVal = startVal + (finalVal - startVal) * easeProgress;

                if (isDecimal) {
                    element.textContent = currentVal.toFixed(1) + '%';
                } else {
                    element.textContent = Math.floor(currentVal).toLocaleString() + (finalVal >= 10000 ? '+' : '');
                }

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    if (isDecimal) {
                        element.textContent = finalVal.toFixed(1) + '%';
                    } else {
                        element.textContent = finalVal.toLocaleString() + (finalVal >= 10000 ? '+' : '');
                    }
                }
            }
            requestAnimationFrame(updateCounter);
        }

        // Pricing Toggle actions
        const btnMonthly = document.getElementById('btnMonthly');
        const btnAnnually = document.getElementById('btnAnnually');
        const priceLocal = document.getElementById('priceLocal');
        const priceDistrict = document.getElementById('priceDistrict');

        btnMonthly.addEventListener('click', function() {
            this.classList.add('active');
            btnAnnually.classList.remove('active');
            priceLocal.textContent = 'R4,999';
            priceDistrict.textContent = 'R14,999';
        });

        btnAnnually.addEventListener('click', function() {
            this.classList.add('active');
            btnMonthly.classList.remove('active');
            // 20% discount prices
            priceLocal.textContent = 'R3,999';
            priceDistrict.textContent = 'R11,999';
        });
    </script>
</body>
</html>
