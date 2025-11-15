<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyFX – Trade Smart. Trade Global | True ECN Trading Platform</title>
    <meta name="description" content="Experience institutional-grade execution with MyFX. True ECN trading, ultra-low spreads from 0.0 pips, and lightning-fast execution.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/logo1.png">
    <link rel="shortcut icon" type="image/png" href="assets/logo1.png">
    <link rel="apple-touch-icon" href="assets/logo1.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #1da448;
            --primary-red: #FF3B30;
            --dark-bg: #0A0E27;
            --light-bg: #F8F9FA;
            --card-bg: #FFFFFF;
            --text-dark: #1A1A1A;
            --text-light: #6C757D;
            --gradient-green: linear-gradient(135deg, #1da448 0%, #158a37 100%);
            --gradient-red: linear-gradient(135deg, #FF3B30 0%, #CC2E24 100%);
            --gradient-overlay: linear-gradient(135deg, rgba(29, 164, 72, 0.1) 0%, rgba(255, 59, 48, 0.05) 100%);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-dark);
            background: var(--light-bg);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(10, 14, 39, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            transition: var(--transition);
        }
        
        .navbar.scrolled {
            background: rgba(10, 14, 39, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
        }
        
        .logo img {
            height: 40px;
            width: auto;
        }
        
        .nav-menu {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            align-items: center;
        }
        
        .nav-menu a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            position: relative;
        }
        
        .nav-menu a:hover {
            color: var(--primary-green);
        }
        
        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-green);
            transition: var(--transition);
        }
        
        .nav-menu a:hover::after {
            width: 100%;
        }
        
        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn {
            padding: 0.75rem 1.75rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: var(--gradient-green);
            color: white;
            box-shadow: 0 4px 15px rgba(29, 164, 72, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 164, 72, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
        }
        
        .btn-outline:hover {
            background: var(--primary-green);
            color: white;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: #0A0E27;
            padding-top: 80px;
            overflow: hidden;
        }
        
        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10, 14, 39, 0.85) 0%, rgba(10, 14, 39, 0.7) 100%);
            z-index: 1;
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: var(--primary-green);
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 300px;
            height: 300px;
            background: var(--primary-red);
            bottom: 20%;
            right: 15%;
            animation-delay: 5s;
        }
        
        .shape-3 {
            width: 250px;
            height: 250px;
            background: var(--primary-green);
            top: 50%;
            right: 10%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -30px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }
        
        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .hero-content {
            max-width: 900px;
            text-align: center;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .hero-content h1 .highlight {
            background: var(--gradient-green);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-content .subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .hero-content .description {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-item .number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-green);
            display: block;
        }
        
        .stat-item .label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 0.25rem;
        }
        
        .hero-visual {
            position: relative;
        }
        
        .trading-card {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            animation: slideUp 1s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .chart-container {
            height: 300px;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .mini-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .mini-card {
            background: var(--light-bg);
            padding: 1rem;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .mini-card .pair {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .mini-card .price {
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .mini-card .change {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .mini-card .change.positive {
            color: var(--primary-green);
        }
        
        .mini-card .change.negative {
            color: var(--primary-red);
        }
        
        /* Features Section */
        .features {
            padding: 6rem 2rem;
            background: white;
        }
        
        .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 4rem;
        }
        
        .section-label {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            background: rgba(29, 164, 72, 0.1);
            color: var(--primary-green);
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .section-header h2 {
            font-size: 2.75rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        
        .section-header p {
            font-size: 1.15rem;
            color: var(--text-light);
            line-height: 1.7;
        }
        
        .features-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 24px;
            padding: 3rem 2.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(29, 164, 72, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .feature-card:hover::before {
            left: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(29, 164, 72, 0.15);
            border-color: var(--primary-green);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: var(--gradient-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.2rem;
            color: white;
            transition: all 0.4s ease;
            box-shadow: 0 8px 20px rgba(29, 164, 72, 0.3);
        }
        
        .feature-card:hover .feature-icon {
            transform: rotateY(360deg) scale(1.1);
            box-shadow: 0 12px 30px rgba(29, 164, 72, 0.5);
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
            transition: color 0.3s ease;
        }
        
        .feature-card:hover h3 {
            color: var(--primary-green);
        }
        
        .feature-card p {
            color: var(--text-light);
            line-height: 1.8;
            font-size: 1.05rem;
        }
        
        .feature-card .feature-number {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(29, 164, 72, 0.1);
            color: var(--primary-green);
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-number {
            background: var(--primary-green);
            color: white;
            transform: rotate(360deg);
        }
        
        /* Instruments Section */
        .instruments {
            padding: 6rem 2rem;
            background: var(--light-bg);
        }
        
        .instruments-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .instruments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .instrument-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 2px solid transparent;
            overflow: hidden;
            position: relative;
        }
        
        .instrument-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-green);
            transform: scaleX(0);
            transition: var(--transition);
        }
        
        .instrument-card:hover::before {
            transform: scaleX(1);
        }
        
        .instrument-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .instrument-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .instrument-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        
        .instrument-card .count {
            color: var(--primary-green);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        
        .instrument-card p {
            color: var(--text-light);
            line-height: 1.6;
        }
        
        /* Platform Section */
        .platform {
            padding: 6rem 2rem;
            background: white;
        }
        
        .platform-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .platform-image {
            position: relative;
        }
        
        .platform-image video {
            width: 100%;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }
        
        .platform-features {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .platform-feature {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }
        
        .platform-feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: var(--gradient-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }
        
        .platform-feature-content h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }
        
        .platform-feature-content p {
            color: var(--text-light);
            line-height: 1.6;
        }
        
        /* Live Prices Section */
        .live-prices {
            padding: 6rem 2rem;
            background: var(--dark-bg);
            color: white;
        }
        
        .live-prices .section-label {
            background: rgba(29, 164, 72, 0.2);
        }
        
        .live-prices .section-header h2 {
            color: white;
        }
        
        .live-prices .section-header p {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .price-table {
            max-width: 1200px;
            margin: 3rem auto 0;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .price-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            align-items: center;
            transition: var(--transition);
        }
        
        .price-row:hover {
            background: rgba(29, 164, 72, 0.1);
        }
        
        .price-row:first-child {
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        
        .price-row:last-child {
            border-bottom: none;
        }
        
        .pair-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .pair-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .pair-name {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .price-change.up {
            color: var(--primary-green);
        }
        
        .price-change.down {
            color: var(--primary-red);
        }
        
        .mini-chart {
            height: 30px;
        }
        
        /* Trading Solutions Section */
        .solution-card {
            position: relative;
        }
        
        .solution-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(29, 164, 72, 0.05), transparent);
            transition: left 0.5s ease;
        }
        
        .solution-card:hover::before {
            left: 100%;
        }
        
        .solution-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-green);
        }
        
        .solution-card:hover .solution-icon {
            transform: rotateY(360deg) scale(1.1);
            box-shadow: 0 12px 30px rgba(29, 164, 72, 0.4);
        }
        
        .solution-card:hover .solution-btn {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(29, 164, 72, 0.4);
        }
        
        .solution-card .solution-icon {
            transition: all 0.4s ease;
        }
        
        .solution-card .solution-btn {
            transition: all 0.3s ease;
        }
        
        .solution-card .solution-btn:hover {
            transform: translateY(-4px);
        }
        
        @media (max-width: 768px) {
            .solutions-grid {
                grid-template-columns: 1fr !important;
                gap: 2rem !important;
            }
            
            .solution-card {
                padding: 2rem !important;
            }
            
            .solution-features {
                grid-template-columns: 1fr !important;
            }
        }

        /* CTA Section */
        .cta {
            padding: 8rem 2rem;
            background: var(--dark-bg);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .cta::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(29, 164, 72, 0.3) 0%, transparent 70%);
            animation: pulse-glow 4s infinite alternate;
            border-radius: 50%;
        }
        
        .cta::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 59, 48, 0.2) 0%, transparent 70%);
            animation: pulse-glow 4s infinite alternate-reverse;
            border-radius: 50%;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.6;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.9;
            }
        }
        
        .cta-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        
        .cta-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .cta h2 {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #1da448 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .cta-subtitle {
            font-size: 1.35rem;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .cta-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
            margin: 4rem 0;
        }
        
        .cta-step {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 3rem 2.5rem;
            border-radius: 24px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-step::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-green);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .cta-step:hover::before {
            transform: scaleX(1);
        }
        
        .cta-step:hover {
            transform: translateY(-10px);
            background: rgba(29, 164, 72, 0.15);
            border-color: var(--primary-green);
            box-shadow: 0 20px 40px rgba(29, 164, 72, 0.3);
        }
        
        .step-number {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--gradient-green);
            color: white;
            font-weight: 800;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(29, 164, 72, 0.4);
            transition: all 0.4s ease;
        }
        
        .cta-step:hover .step-number {
            transform: scale(1.15) rotate(360deg);
            box-shadow: 0 12px 30px rgba(29, 164, 72, 0.6);
        }
        
        .cta-step h4 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }
        
        .cta-step p {
            font-size: 1.05rem;
            opacity: 0.85;
            line-height: 1.6;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 3rem;
            flex-wrap: wrap;
        }
        
        .btn-white {
            background: white;
            color: var(--primary-green);
            font-weight: 700;
            font-size: 1.15rem;
            padding: 1.25rem 3rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        
        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
        }
        
        .btn-outline-white {
            background: transparent;
            border: 2px solid white;
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 1.25rem 3rem;
        }
        
        .btn-outline-white:hover {
            background: white;
            color: var(--dark-bg);
        }
        
        .cta-footer {
            text-align: center;
            margin-top: 4rem;
            padding-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .cta-footer h5 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: white;
        }
        
        .cta-footer p {
            font-size: 1.1rem;
            opacity: 0.8;
            line-height: 1.7;
        }
        
        .cta-footer a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .cta-footer a:hover {
            color: white;
            text-decoration: underline;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-bg);
            color: white;
            padding: 4rem 2rem 2rem;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .footer-about h3 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .footer-about img {
            height: 50px;
            margin-bottom: 1rem;
        }
        
        .footer-about p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .social-link:hover {
            background: var(--primary-green);
            transform: translateY(-3px);
        }
        
        .footer-column h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 0.75rem;
        }
        
        .footer-column ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-column ul li a:hover {
            color: var(--primary-green);
            padding-left: 5px;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .footer-links {
            display: flex;
            gap: 2rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            color: var(--primary-green);
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .platform-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .platform-image {
                order: 1;
            }
            
            .platform-content {
                order: 2;
            }
            
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 0;
            }
            
            .nav-container {
                padding: 0.75rem 1rem;
            }
            
            .logo img {
                height: 32px;
            }
            
            .nav-buttons {
                display: none;
            }
            
            .nav-menu {
                position: fixed;
                top: 60px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 60px);
                background: var(--dark-bg);
                flex-direction: column;
                padding: 2rem;
                gap: 1.5rem;
                box-shadow: var(--shadow-lg);
                transition: var(--transition);
                align-items: flex-start;
            }
            
            .nav-menu.active {
                left: 0;
            }
            
            .mobile-menu-toggle {
                display: block;
                font-size: 1.25rem;
            }
            
            .hero {
                padding-top: 60px;
            }
            
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .section-header h2 {
                font-size: 2rem;
            }
            
            .features-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }
        
        @media (max-width: 640px) {
            .features-container {
                grid-template-columns: 1fr;
            }
            
            .cta h2 {
                font-size: 2.5rem;
            }
            
            .cta-subtitle {
                font-size: 1.1rem;
            }
            
            .cta-steps {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .cta-buttons {
                flex-direction: column;
            }
            
            .btn-white,
            .btn-outline-white {
                width: 100%;
            }
            
            .price-row {
                grid-template-columns: 2fr 1fr 1fr;
                font-size: 0.85rem;
            }
            
            .price-row > *:nth-child(4),
            .price-row > *:nth-child(5) {
                display: none;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .nav-container {
                padding: 1rem;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        /* Scroll Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Loading Animation */
        .pulse-animation {
            animation: pulse-grow 2s infinite;
        }
        
        @keyframes pulse-grow {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="homescreen.php" class="logo">
                <img src="assets/logo.png" alt="MyFX Logo">
            </a>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#" onclick="downloadLicenses(event)">Licenses</a></li>
                <li><a href="#platform">Platform</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#partner">Partner</a></li>
            </ul>
            
            <div class="nav-buttons">
                <a href="https://trade.myforexcart.com/login/" class="btn btn-outline">Sign In</a>
                <a href="https://trade.myforexcart.com/register/" class="btn btn-primary">Register</a>
            </div>
            
            <button class="mobile-menu-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero" id="home">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="assets/hero.mp4" type="video/mp4">
        </video>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        
        <div class="hero-container">
            <div class="hero-content" data-aos="fade-up">
                <div class="subtitle">MyFX – Trade Smart. Trade Global.</div>
                <h1>
                    Unlock the Power of <span class="highlight">True ECN Trading</span>
                </h1>
                <p class="description">
                    Experience institutional-grade execution, razor-thin spreads, and deep liquidity — all built for traders who demand performance. Trade directly on the world's most trusted financial markets with transparency and speed.
                </p>
                
                <div class="hero-buttons">
                    <a href="https://trade.myforexcart.com/register/" class="btn btn-primary">Open Live Account</a>
                    <a href="https://trade.myforexcart.com/MyFX-H.apk" class="btn btn-outline">Download App</a>
                    <a href="https://cp.rakizcapitals.com/register?ref=IB-296-X4AE" class="btn btn-outline">MetaTrader</a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="number">24/7</span>
                        <span class="label">Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label">Why Choose MyFX</span>
            <h2>Built for Professional Traders</h2>
            <p>Experience the advantages of true ECN trading with institutional-grade infrastructure</p>
        </div>
        
        <div class="features-container">
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="0">
                <div class="feature-number">01</div>
                <div class="feature-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <h3>True ECN Model</h3>
                <p>Direct access to Tier-1 liquidity providers with no dealing desk interference. Your trades are executed on the real market with complete transparency.</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="100">
                <div class="feature-number">02</div>
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Ultra-Low Spreads</h3>
                <p>Trade major pairs from 0.0 pips with raw ECN pricing. Get the best possible execution prices with no markups or hidden fees.</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="200">
                <div class="feature-number">03</div>
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Lightning-Fast Execution</h3>
                <p>Orders executed in milliseconds with average execution speed of 0.03 seconds. No slippage, no requotes, just pure speed.</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="300">
                <div class="feature-number">04</div>
                <div class="feature-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Flexible Leverage</h3>
                <p>Trade with leverage up to 1:1000 and maximize your trading potential. Adjust leverage based on your risk appetite.</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="400">
                <div class="feature-number">05</div>
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Regulated & Secure</h3>
                <p>Your funds are protected with segregated accounts and tier-1 banking partners. Trade with confidence and complete peace of mind.</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="500">
                <div class="feature-number">06</div>
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>24/7 Expert Support</h3>
                <p>Multilingual support team available round the clock. Get help whenever you need it via live chat, email, or phone.</p>
            </div>
        </div>
    </section>
    
    <!-- Instruments Section -->
    <section class="instruments" id="instruments">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label">Trading Instruments</span>
            <h2>Trade Across Global Markets</h2>
            <p>Access a diverse range of trading instruments with tight spreads and deep liquidity</p>
        </div>
        
        <div class="instruments-container">
            <div class="instruments-grid">
                <div class="instrument-card" data-aos="zoom-in" data-aos-delay="0">
                    <div class="instrument-icon">💱</div>
                    <h3>Forex</h3>
                    <div class="count">70+ Currency Pairs</div>
                    <p>Trade major, minor, and exotic forex pairs with spreads from 0.0 pips. EUR/USD, GBP/USD, USD/JPY, and more.</p>
                </div>
                
                <div class="instrument-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="instrument-icon">🥇</div>
                    <h3>Commodities</h3>
                    <div class="count">Gold, Silver, Oil</div>
                    <p>Trade precious metals and energy commodities with competitive pricing. Hedge inflation and diversify your portfolio.</p>
                </div>
                
                <div class="instrument-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="instrument-icon">📊</div>
                    <h3>Indices</h3>
                    <div class="count">15+ Global Indices</div>
                    <p>Access major stock indices including US30, NAS100, GER40, UK100, and more with low margins.</p>
                </div>
                
                <div class="instrument-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="instrument-icon">₿</div>
                    <h3>Crypto CFDs</h3>
                    <div class="count">20+ Cryptocurrencies</div>
                    <p>Trade Bitcoin, Ethereum, and top altcoins 24/7 with tight spreads and high leverage options.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Platform Section -->
    <section class="platform" id="platform">
        <div class="section-header" data-aos="fade-up" style="margin-bottom: 3rem;">
            <span class="section-label">Smart Trading Technology</span>
            <h2>Professional Trading Platforms</h2>
            <p>Empowered by cutting-edge MT5 platform with advanced features</p>
        </div>
        
        <div class="platform-container">
            <div class="platform-image" data-aos="fade-right">
                <video autoplay muted loop playsinline>
                    <source src="assets/1.mp4" type="video/mp4">
                </video>
            </div>
            
            <div class="platform-content" data-aos="fade-left">
                <div class="platform-features">
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-chart-area"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Advanced Charting</h4>
                            <p>100+ technical indicators, multiple timeframes, and professional drawing tools for in-depth market analysis.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>AI-Powered Analytics</h4>
                            <p>Get real-time market insights powered by artificial intelligence to make smarter trading decisions.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-copy"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Copy Trading</h4>
                            <p>Follow and automatically copy successful traders. Learn from the best while earning consistent returns.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Mobile Trading</h4>
                            <p>Trade on-the-go with our powerful mobile apps for iOS and Android. Full platform features in your pocket.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Trading Management Solutions Section -->
    <section class="platform" style="background: var(--light-bg);">
        <div class="section-header" data-aos="fade-up" style="margin-bottom: 3rem;">
            <span class="section-label">Trading Solutions</span>
            <h2>Professional Money Management</h2>
            <p>Advanced trading solutions for fund managers, investors, and traders who want to maximize their potential</p>
        </div>
        
        <div class="platform-container">
            <div class="platform-image" data-aos="fade-right">
                <video autoplay muted loop playsinline>
                    <source src="assets/5.mp4" type="video/mp4">
                </video>
            </div>
            
            <div class="platform-content" data-aos="fade-left">
                <div class="platform-features">
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>MAMM</h4>
                            <p><strong>(Multi-Account Money Management)</strong><br>Trade multiple accounts simultaneously under one master account. Perfect for fund managers who want efficient control and transparency across all client accounts.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>PAMM</h4>
                            <p><strong>(Percent Allocation Money Management)</strong><br>Invest smartly by joining expert-managed accounts. Profits and losses are distributed based on each investor's share — simple, secure, and performance-driven.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-copy"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Copy Trade</h4>
                            <p>Copy the trades of top-performing traders in real time. Ideal for beginners who want to earn like professionals without manual trading.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Risk Management</h4>
                            <p>Advanced risk controls and real-time monitoring across all managed accounts with transparent reporting and instant notifications.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Advanced Analytics Section -->
    <section class="platform">
        <div class="section-header" data-aos="fade-up" style="margin-bottom: 3rem;">
            <span class="section-label">Market Intelligence</span>
            <h2>Real-Time Market Analytics</h2>
            <p>Stay ahead with institutional-grade market data and analytics tools</p>
        </div>
        
        <div class="platform-container">
            <div class="platform-image" data-aos="fade-right">
                <video autoplay muted loop playsinline>
                    <source src="assets/2.mp4" type="video/mp4">
                </video>
            </div>
            
            <div class="platform-content" data-aos="fade-left">
                <div class="platform-features">
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Live Market Data</h4>
                            <p>Access real-time streaming quotes from Tier-1 liquidity providers with sub-millisecond latency.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Smart Insights</h4>
                            <p>AI-powered market sentiment analysis and trading signals to help you make informed decisions.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Price Alerts</h4>
                            <p>Set custom price alerts and get instant notifications when markets reach your target levels.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Economic Calendar</h4>
                            <p>Track major economic events and news that impact markets with our integrated economic calendar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Risk Management Section -->
    <section class="platform">
        <div class="section-header" data-aos="fade-up" style="margin-bottom: 3rem;">
            <span class="section-label">Risk Management</span>
            <h2>Advanced Risk Controls</h2>
            <p>Protect your capital with professional-grade risk management tools</p>
        </div>
        
        <div class="platform-container">
            <div class="platform-image" data-aos="fade-right">
                <video autoplay muted loop playsinline>
                    <source src="assets/3.mp4" type="video/mp4">
                </video>
            </div>
            
            <div class="platform-content" data-aos="fade-left">
                <div class="platform-features">
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Stop Loss & Take Profit</h4>
                            <p>Set automated stop loss and take profit orders to protect your positions and lock in gains.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Margin Call Protection</h4>
                            <p>Real-time margin monitoring with early warning system to prevent unexpected liquidations.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Position Sizing</h4>
                            <p>Built-in position size calculator helps you manage risk based on your account size and risk tolerance.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Negative Balance Protection</h4>
                            <p>Trade with confidence knowing you can never lose more than your account balance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Account Types Section -->
    <section class="platform" style="background: var(--light-bg);">
        <div class="section-header" data-aos="fade-up" style="margin-bottom: 3rem;">
            <span class="section-label">Flexible Options</span>
            <h2>Choose Your Account Type</h2>
            <p>Multiple account options tailored to different trading styles and experience levels</p>
        </div>
        
        <div class="platform-container">
            <div class="platform-image" data-aos="fade-right">
                <video autoplay muted loop playsinline>
                    <source src="assets/4.mp4" type="video/mp4">
                </video>
            </div>
            
            <div class="platform-content" data-aos="fade-left">
                <div class="platform-features">
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Standard Account</h4>
                            <p>Perfect for beginners with low minimum deposit, competitive spreads, and no commission structure.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>ECN Pro Account</h4>
                            <p>Raw spreads from 0.0 pips with low commission. Ideal for active traders and scalpers.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>VIP Account</h4>
                            <p>Premium service with dedicated account manager, priority support, and exclusive trading benefits.</p>
                        </div>
                    </div>
                    
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="platform-feature-content">
                            <h4>Islamic Account</h4>
                            <p>Swap-free trading accounts compliant with Shariah law for our Muslim clients worldwide.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Live Prices Section -->
    <section class="live-prices" id="pricing">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label">Live Market Data</span>
            <h2>Real-Time Pricing</h2>
            <p>Watch live market prices with ultra-low spreads and transparent execution</p>
        </div>
        
        <div class="price-table" data-aos="fade-up">
            <div class="price-row">
                <div>Instrument</div>
                <div>Bid</div>
                <div>Ask</div>
                <div>Change</div>
                <div>Trend</div>
            </div>
            <div class="price-row">
                <div class="pair-info">
                    <div class="pair-icon">EU</div>
                    <div class="pair-name">EUR/USD</div>
                </div>
                <div>1.08745</div>
                <div>1.08755</div>
                <div class="price-change up">+0.24%</div>
                <div class="mini-chart">📈</div>
            </div>
            <div class="price-row">
                <div class="pair-info">
                    <div class="pair-icon" style="background: var(--gradient-red);">GU</div>
                    <div class="pair-name">GBP/USD</div>
                </div>
                <div>1.25425</div>
                <div>1.25435</div>
                <div class="price-change down">-0.18%</div>
                <div class="mini-chart">📉</div>
            </div>
            <div class="price-row">
                <div class="pair-info">
                    <div class="pair-icon">XAU</div>
                    <div class="pair-name">GOLD</div>
                </div>
                <div>2,032.45</div>
                <div>2,032.75</div>
                <div class="price-change up">+1.32%</div>
                <div class="mini-chart">📈</div>
            </div>
            <div class="price-row">
                <div class="pair-info">
                    <div class="pair-icon">BTC</div>
                    <div class="pair-name">BTC/USD</div>
                </div>
                <div>43,287</div>
                <div>43,295</div>
                <div class="price-change up">+2.45%</div>
                <div class="mini-chart">📈</div>
            </div>
            <div class="price-row">
                <div class="pair-info">
                    <div class="pair-icon">US30</div>
                    <div class="pair-name">US30</div>
                </div>
                <div>37,845</div>
                <div>37,850</div>
                <div class="price-change up">+0.85%</div>
                <div class="mini-chart">📈</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="partner">
        <div class="cta-container">
            <div class="cta-header" data-aos="fade-up">
                <h2>Start Trading Today</h2>
                <p class="cta-subtitle">Join thousands of traders who trust MyFX for their trading success. Get started in minutes with our simple 3-step process.</p>
            </div>
            
            <div class="cta-steps">
                <div class="cta-step" data-aos="zoom-in" data-aos-delay="0">
                    <div class="step-number">1</div>
                    <h4>Open an Account</h4>
                    <p>Quick and easy registration in under 3 minutes. No hidden requirements.</p>
                </div>
                <div class="cta-step" data-aos="zoom-in" data-aos-delay="100">
                    <div class="step-number">2</div>
                    <h4>Fund Instantly</h4>
                    <p>Multiple secure payment methods. Instant deposits available 24/7.</p>
                </div>
                <div class="cta-step" data-aos="zoom-in" data-aos-delay="200">
                    <div class="step-number">3</div>
                    <h4>Start Trading</h4>
                    <p>Access global markets immediately with our powerful platforms.</p>
                </div>
            </div>
            
            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="300">
                <a href="https://trade.myforexcart.com/register/" class="btn btn-white">Open Live Account</a>
                <a href="https://trade.myforexcart.com/MyFX-H.apk" class="btn btn-outline-white">Download App</a>
            </div>
            
            <div class="cta-footer" data-aos="fade-up" data-aos-delay="400">
                <h5>Partnership Opportunities Available</h5>
                <p>Join our IB & Affiliate Program and earn high commissions per referred trader.<br>
                <a href="#partner">Become a Partner Now →</a></p>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-about">
                    <img src="assets/myfx.png" alt="MyFX Logo">
                    <p>Your trusted partner for professional ECN trading. Experience institutional-grade execution with transparency and speed.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h4>Trading</h4>
                    <ul>
                        <li><a href="#">Forex</a></li>
                        <li><a href="#">Commodities</a></li>
                        <li><a href="#">Indices</a></li>
                        <li><a href="#">Cryptocurrencies</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Platforms</h4>
                    <ul>
                        <li><a href="#">MetaTrader 5</a></li>
                        <li><a href="#">Mobile Apps</a></li>
                        <li><a href="#">Web Platform</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Regulation</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Trading FAQ</a></li>
                        <li><a href="#">Account FAQ</a></li>
                        <li><a href="#">Contact Support</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 MyFX. All rights reserved.</p>
                <div class="footer-links">
                    <a href="privacy-policy.php">Privacy Policy</a>
                    <a href="terms-and-conditions.php">Terms of Service</a>
                    <a href="#">Risk Disclosure</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
        
        // Mobile Menu Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');
        
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
        
        // Close menu when clicking on a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                const icon = mobileToggle.querySelector('i');
                icon.classList.add('fa-bars');
                icon.classList.remove('fa-times');
            });
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        
        // Animate live prices
        function updatePrices() {
            const priceRows = document.querySelectorAll('.price-row:not(:first-child)');
            priceRows.forEach(row => {
                const bidCell = row.children[1];
                const askCell = row.children[2];
                const changeCell = row.children[3];
                
                if (bidCell && askCell && changeCell) {
                    const change = (Math.random() - 0.5) * 0.002;
                    const currentBid = parseFloat(bidCell.textContent.replace(',', ''));
                    const currentAsk = parseFloat(askCell.textContent.replace(',', ''));
                    
                    const newBid = (currentBid + change).toFixed(currentBid > 1000 ? 0 : 5);
                    const newAsk = (currentAsk + change).toFixed(currentAsk > 1000 ? 0 : 5);
                    
                    bidCell.textContent = newBid > 1000 ? parseFloat(newBid).toLocaleString() : newBid;
                    askCell.textContent = newAsk > 1000 ? parseFloat(newAsk).toLocaleString() : newAsk;
                    
                    // Highlight change
                    bidCell.style.background = change > 0 ? 'rgba(29, 164, 72, 0.2)' : 'rgba(255, 59, 48, 0.2)';
                    askCell.style.background = change > 0 ? 'rgba(29, 164, 72, 0.2)' : 'rgba(255, 59, 48, 0.2)';
                    
                    setTimeout(() => {
                        bidCell.style.background = '';
                        askCell.style.background = '';
                    }, 500);
                }
            });
        }
        
        setInterval(updatePrices, 3000);
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
        
        // Add pulse animation to CTA button
        const ctaButton = document.querySelector('.cta .btn-white');
        if (ctaButton) {
            setInterval(() => {
                ctaButton.classList.add('pulse-animation');
                setTimeout(() => {
                    ctaButton.classList.remove('pulse-animation');
                }, 2000);
            }, 5000);
        }
        
        // Download licenses function
        function downloadLicenses(event) {
            event.preventDefault();
            
            // Create temporary links and trigger downloads
            const licenses = [
                'assets/Nisabudheen-SBS.pdf',
                'assets/Nisabudheen-En.pdf'
            ];
            
            licenses.forEach((file, index) => {
                setTimeout(() => {
                    const link = document.createElement('a');
                    link.href = file;
                    link.download = file.split('/').pop();
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }, index * 500); // Delay between downloads
            });
        }
    </script>
</body>
</html>
