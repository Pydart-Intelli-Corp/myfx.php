<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - MyFX Trading Platform</title>
    <meta name="description" content="MYFX Privacy Policy - Learn how we collect, use, and protect your personal information.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/logo1.png">
    <link rel="shortcut icon" type="image/png" href="assets/logo1.png">
    <link rel="apple-touch-icon" href="assets/logo1.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #1da448;
            --dark-bg: #0A0E27;
            --light-bg: #F8F9FA;
            --card-bg: #FFFFFF;
            --text-dark: #1A1A1A;
            --text-light: #6C757D;
            --gradient-green: linear-gradient(135deg, #1da448 0%, #158a37 100%);
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
            transition: all 0.3s ease;
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
        
        .back-btn {
            padding: 0.75rem 1.75rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            background: var(--gradient-green);
            color: white;
            box-shadow: 0 4px 15px rgba(29, 164, 72, 0.3);
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 164, 72, 0.4);
        }
        
        /* Content */
        .content-wrapper {
            max-width: 1000px;
            margin: 100px auto 60px;
            padding: 0 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .page-header .subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            line-height: 1.7;
        }
        
        .policy-content {
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .policy-content h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 2.5rem 0 1.5rem 0;
        }
        
        .policy-content h2:first-child {
            margin-top: 0;
        }
        
        .policy-content p {
            font-size: 1rem;
            color: var(--text-dark);
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        
        .policy-content ul {
            margin: 1rem 0 1.5rem 2rem;
            list-style-type: disc;
        }
        
        .policy-content ul li {
            font-size: 1rem;
            color: var(--text-dark);
            line-height: 1.8;
            margin-bottom: 0.75rem;
        }
        
        .policy-content strong {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .contact-info {
            background: linear-gradient(135deg, rgba(29, 164, 72, 0.05) 0%, rgba(29, 164, 72, 0.1) 100%);
            padding: 2rem;
            border-radius: 12px;
            margin-top: 2rem;
            border-left: 4px solid var(--primary-green);
        }
        
        .contact-info p {
            margin-bottom: 0.5rem;
        }
        
        .contact-info a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-bg);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 4rem;
        }
        
        .footer p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .policy-content {
                padding: 2rem 1.5rem;
            }
            
            .content-wrapper {
                padding: 0 1rem;
                margin-top: 80px;
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
            <a href="homescreen.php" class="back-btn">Back to Home</a>
        </div>
    </nav>
    
    <!-- Content -->
    <div class="content-wrapper">
        <div class="page-header">
            <h1>Privacy Policy</h1>
            <p class="subtitle">Last Updated: November 15, 2025</p>
        </div>
        
        <div class="policy-content">
            <h2>MYFX Respects Each Individual's Right to Privacy</h2>
            <p>At MYFX, we value and respect every individual's right to privacy. We are committed to upholding privacy standards in accordance with the Privacy Principles outlined in the Privacy Act 1993. This privacy policy explains our approach to handling and safeguarding the personal information we collect from individuals who apply for or receive our products and services.</p>
            
            <h2>Collection of Personal Information</h2>
            <p>When you initiate or maintain an account with MYFX, we collect personal information for various business purposes. This includes evaluating your account application, processing your requests and transactions, notifying you about products and services that may interest you, and providing client support. The personal information we collect may encompass the following:</p>
            
            <ul>
                <li>Information you provide in applications and other forms, such as your name, address, date of birth, occupation, employer, assets and income, telephone or mobile number, email address, tax file number, and nominated bank account details.</li>
                <li>Data regarding your transactions with us and our affiliates.</li>
                <li>Information obtained from consumer reporting agencies, including your credit history and creditworthiness, and from non-affiliated entities not associated with MYFX.</li>
                <li>Information you submit for identity verification, such as a passport, or receive from non-affiliated entities not associated with MYFX.</li>
            </ul>
            
            <p>We may also collaborate with selected third-party vendors, such as Google Analytics, to incorporate tracking technologies and remarketing services on our site. These technologies, including first-party and third-party cookies, enable us to analyse user activity on our site and assess the popularity of specific content. By accessing our site, you consent to the collection and use of your information by these third-party vendors. We encourage you to review their privacy policies and contact them directly for responses to your queries. We do not share personal information with these third-party vendors. If you prefer not to have your information collected or used by tracking technologies, you can visit the third-party vendor or use tools like the Network Advertising Initiative Opt-Out Tool or Digital Advertising Alliance Opt-Out Tool.</p>
            
            <p>Please note that obtaining a new computer, upgrading your browser, or altering your browser's cookies files may clear certain opt-out cookies, plug-ins, or settings.</p>
            
            <h2>Protection of Personal Information</h2>
            <p>We implement comprehensive measures to safeguard your personal information from unauthorised access, misuse, loss, modification, or disclosure. Access to your personal information is restricted to employees who require it to conduct our business, service your account, and provide you with a wide range of products and services. Our employees are mandated to maintain the confidentiality of your personal information and adhere to established protocols. We employ physical, electronic, and procedural safeguards to protect your personal information. We do not disclose your name or personal information to anyone except when authorised by law.</p>
            
            <h2>Cookies and Hyperlinks</h2>
            <p>Cookies are small files used by websites to track visitor data. MYFX may set and access MYFX cookies on your computer, enabling us to track which advertisements and promotions direct users to our site. MYFX and its affiliates or divisions may employ such cookies.</p>
            
            <p>Our websites may contain hyperlinks or "links" to other sites, and other sites may link to our sites. These external websites may have their privacy policies, and MYFX's privacy policy exclusively pertains to MYFX and information collected by us. We do not have control over the security or usage of information provided or collected by these external sites. If you choose to link to one of these sites, you may be required to provide registration or other information. It's crucial to recognise that this information goes to a third party, and you should familiarise yourself with the privacy policy provided by that third party.</p>
            
            <h2>Security Technology at MYFX Markets</h2>
            <p>MYFX takes active measures to protect the information you submit to us. Our technology is designed to secure your data while it's being transmitted to us, preventing interception by any party other than MYFX Markets. We also employ additional safeguards, including firewalls, authentication systems (e.g., passwords and personal identification numbers), and access control mechanisms to prevent unauthorised access to systems and data. However, you should be aware that using services provided through our website involves transmitting data over the internet, which is subject to inherent risks. While we take reasonable security precautions, you may be exposed to unauthorised programs transmitted by third parties, electronic trespassing, and potential information and data failures. Although we and our suppliers have privacy and security features in place to reduce these risks, we cannot guarantee their complete elimination. Therefore, you acknowledge that we are not liable for breaches of confidentiality or unauthorised access and use resulting from such events.</p>
            
            <h2>Sharing Information with Our Affiliates</h2>
            <p>For business purposes, we may share the personal information described above with our affiliates, as permitted by applicable law. Our affiliates include companies controlled or owned by us, or companies controlling or under common control with us. These affiliates may encompass financial service companies, such as dealers, other brokers, futures commission merchants, and advisors.</p>
            
            <h2>Disclosure to Non-Affiliated Third Parties and Regulatory Bodies</h2>
            <p>In order to support the products and services we provide, we may share the personal information described above with third-party service providers and non-affiliated joint marketers. These entities may not be affiliated with us and can include various institutions, such as advisors, dealers, brokers, trust companies, and banks, with whom we have joint marketing agreements. We may also share your information with companies under contract to provide services on our behalf, including service providers handling statement and transaction confirmation preparation, data processing, computer software maintenance and development, transaction processing, and marketing services. These entities are legally bound to maintain the confidentiality of your personal information. In compliance with applicable laws and regulations, we may also be obligated to disclose your personal information to regulatory and governmental bodies, such as the FSP, as required.</p>
            
            <h2>Accessing and Reviewing Your Personal Information</h2>
            <p>Under applicable data protection laws, you have specific rights to access, correct, and request further information about the personal data we collect and hold about you. To exercise these rights, please contact our designated representative at <a href="mailto:customer.admin@myfxcart.com">customer.admin@myfxcart.com</a>. In some cases, you may be required to provide additional information to facilitate the processing of your request. Please note that failing to provide accurate and complete personal information may impact our ability to provide our services, including access to our websites.</p>
            
            <div class="contact-info">
                <p><strong>Contact Us:</strong></p>
                <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                <p>Email: <a href="mailto:customer.admin@myfxcart.com">customer.admin@myfxcart.com</a></p>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 MyFX. All rights reserved.</p>
    </footer>
</body>
</html>
