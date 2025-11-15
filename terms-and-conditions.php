<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - MyFX Trading Platform</title>
    <meta name="description" content="MYFX Terms and Conditions - Read our complete terms of service for trading on our platform.">
    
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
            --warning-red: #dc2626;
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
        
        .terms-content {
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .terms-content h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 2.5rem 0 1.5rem 0;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .terms-content h2:first-child {
            margin-top: 0;
            border-top: none;
            padding-top: 0;
        }
        
        .terms-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 1.5rem 0 1rem 0;
        }
        
        .terms-content p {
            font-size: 1rem;
            color: var(--text-dark);
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        
        .terms-content ul {
            margin: 1rem 0 1.5rem 2rem;
            list-style-type: disc;
        }
        
        .terms-content ul li {
            font-size: 1rem;
            color: var(--text-dark);
            line-height: 1.8;
            margin-bottom: 0.75rem;
        }
        
        .terms-content strong {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .warning-box {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.05) 0%, rgba(220, 38, 38, 0.1) 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            border-left: 4px solid var(--warning-red);
        }
        
        .warning-box p {
            margin-bottom: 0.5rem;
            color: #991b1b;
            font-weight: 500;
        }
        
        .info-box {
            background: linear-gradient(135deg, rgba(29, 164, 72, 0.05) 0%, rgba(29, 164, 72, 0.1) 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            border-left: 4px solid var(--primary-green);
        }
        
        .info-box p {
            margin-bottom: 0.5rem;
        }
        
        .highlight {
            background: rgba(29, 164, 72, 0.1);
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            color: var(--primary-green);
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
            
            .terms-content {
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
            <h1>Terms and Conditions</h1>
            <p class="subtitle">Last Updated: November 15, 2025</p>
        </div>
        
        <div class="terms-content">
            <h2>1. Introduction</h2>
            <p>Welcome to <strong>MYFX</strong>, a forex trading and investment platform. By accessing or using our services, you agree to comply with the following Terms and Conditions. Please read them carefully.</p>
            
            <h2>2. Eligibility</h2>
            <ul>
                <li>You must be 18 years or older to use MYFX.</li>
                <li>You must be legally permitted to trade forex or CFDs in your country of residence.</li>
            </ul>
            
            <h2>3. Account Registration</h2>
            <p>By creating an account on MYFX, you agree to:</p>
            <ul>
                <li>Provide accurate and up-to-date personal information.</li>
                <li>Complete KYC verification whenever required.</li>
                <li>Maintain the confidentiality of your login credentials.</li>
                <li>Accept full responsibility for all activities performed under your account.</li>
            </ul>
            
            <h2>4. Trading Risk Disclosure</h2>
            <div class="warning-box">
                <p><strong>⚠️ High Risk Warning:</strong></p>
                <p>Forex, commodities, and CFD trading involve high financial risk. You may lose partial or full capital.</p>
            </div>
            <ul>
                <li>Leverage can magnify both profits and losses.</li>
                <li>You may lose partial or full capital.</li>
                <li>MYFX is not responsible for:
                    <ul>
                        <li>Market volatility</li>
                        <li>Misplaced trades</li>
                        <li>Economic events</li>
                        <li>Internet or device issues on the user side</li>
                    </ul>
                </li>
                <li>You trade entirely at your own risk.</li>
            </ul>
            
            <h2>5. Deposits & Withdrawals (USDT Only)</h2>
            
            <h3>5.1 Accepted Currency</h3>
            <p>MYFX supports <span class="highlight">USDT (Tether)</span> only for deposits and withdrawals.</p>
            
            <h3>5.2 Supported Networks</h3>
            <ul>
                <li>TRC20</li>
                <li>ERC20</li>
                <li>BEP20</li>
            </ul>
            <div class="warning-box">
                <p><strong>Important:</strong> Deposits made on an unsupported network may be permanently lost.</p>
            </div>
            
            <h3>5.3 Deposit Policy</h3>
            <ul>
                <li>Deposits must come from your own wallet.</li>
                <li>Sending funds from third-party wallets may result in delays or rejection.</li>
                <li>MYFX is not responsible for lost deposits due to:
                    <ul>
                        <li>Wrong network</li>
                        <li>Incorrect wallet address</li>
                        <li>Network congestion</li>
                    </ul>
                </li>
                <li>Deposits are credited only after the required number of blockchain confirmations.</li>
            </ul>
            
            <h3>5.4 Withdrawal Policy</h3>
            <ul>
                <li>Withdrawals are processed only in USDT to the wallet address you provide.</li>
                <li>Users must ensure the correct network and wallet address.</li>
                <li>Blockchain transfers are irreversible — MYFX cannot recover funds sent incorrectly.</li>
                <li>Withdrawal processing time: <strong>1 to 24 hours</strong>, depending on:
                    <ul>
                        <li>Security checks</li>
                        <li>Network status</li>
                        <li>Platform workload</li>
                    </ul>
                </li>
            </ul>
            
            <h3>5.5 Fees</h3>
            <ul>
                <li>Blockchain network/gas fees are deducted automatically.</li>
                <li>MYFX may apply additional processing fees (if applicable).</li>
                <li>The amount received may be lower after deductions.</li>
            </ul>
            
            <h3>5.6 Security Checks</h3>
            <ul>
                <li>MYFX may hold or review transactions if suspicious activity is detected.</li>
                <li>Large withdrawals may require additional verification.</li>
            </ul>
            
            <h3>5.7 No Chargebacks</h3>
            <div class="warning-box">
                <p>Since blockchain transactions are permanent, MYFX does not support chargebacks, reversals, or refunds for incorrect transfers.</p>
            </div>
            
            <h2>6. Fees & Charges</h2>
            <p>MYFX may charge:</p>
            <ul>
                <li>Spreads</li>
                <li>Commissions</li>
                <li>Swap (overnight) fees</li>
                <li>Withdrawal processing fees</li>
            </ul>
            <p>All applicable charges will be displayed on the MYFX platform.</p>
            
            <h2>7. Trading Platform & Execution</h2>
            <ul>
                <li>MYFX provides trade execution based on market liquidity and availability.</li>
                <li>During high-volatility periods, slippage or execution delays may occur.</li>
                <li>Platform maintenance may cause temporary downtime.</li>
                <li>MYFX does not guarantee:
                    <ul>
                        <li>Zero slippage</li>
                        <li>Specific execution speeds</li>
                        <li>Exact market prices</li>
                    </ul>
                </li>
            </ul>
            
            <h2>8. Prohibited Activities</h2>
            <p>Users are strictly prohibited from:</p>
            <ul>
                <li>Money laundering or illegal fund transfers</li>
                <li>Fraudulent or misleading activities</li>
                <li>Abusive trading strategies</li>
                <li>Multi-account manipulation</li>
                <li>Using EAs/bots for harmful arbitrage</li>
                <li>Attempting to manipulate system behavior</li>
            </ul>
            <div class="warning-box">
                <p>Violation may result in account suspension or termination.</p>
            </div>
            
            <h2>9. Referral / Affiliate Program</h2>
            <p>If MYFX provides referral incentives:</p>
            <ul>
                <li>Commissions are paid only on valid and real trading activity.</li>
                <li>Self-referrals, fake accounts, or suspicious activities may lead to cancellation of commissions.</li>
                <li>MYFX reserves the right to modify referral terms at any time.</li>
            </ul>
            
            <h2>10. AML (Anti-Money Laundering) Compliance</h2>
            <p>MYFX follows strict AML guidelines:</p>
            <ul>
                <li>Users may be required to submit ID, address proof, and source-of-funds documents.</li>
                <li>Suspicious transactions may be blocked or reported to authorities.</li>
                <li>MYFX reserves the right to freeze funds during investigations.</li>
            </ul>
            
            <h2>11. Privacy Policy</h2>
            <ul>
                <li>MYFX collects personal data for account operation and regulatory compliance.</li>
                <li>We do not sell or share personal information for marketing purposes.</li>
                <li>Data may be disclosed only to regulators or law enforcement when required.</li>
            </ul>
            <p>For full details, please read our <a href="privacy-policy.php" style="color: var(--primary-green); font-weight: 600;">Privacy Policy</a>.</p>
            
            <h2>12. Account Termination</h2>
            <p>MYFX may suspend or terminate accounts if:</p>
            <ul>
                <li>You violate any Terms & Conditions</li>
                <li>You provide false information</li>
                <li>Suspicious activity is detected</li>
                <li>KYC is not completed when required</li>
            </ul>
            <p>You may request account closure anytime if no funds or pending issues exist.</p>
            
            <h2>13. Limitation of Liability</h2>
            <p>MYFX is not responsible for:</p>
            <ul>
                <li>Trading losses</li>
                <li>Technical failures</li>
                <li>Network outages</li>
                <li>Third-party service disruptions</li>
                <li>Incorrect user actions or misuse of the platform</li>
            </ul>
            <p><strong>Users accept full responsibility for their trading decisions.</strong></p>
            
            <h2>14. Amendments</h2>
            <p>MYFX may update these Terms & Conditions at any time. Continued use of the platform means you accept the updated terms.</p>
            
            <div class="info-box">
                <p><strong>Contact Us:</strong></p>
                <p>If you have any questions about these Terms & Conditions, please contact us at:</p>
                <p>Email: <a href="mailto:customer.admin@myfxcart.com" style="color: var(--primary-green); font-weight: 600;">customer.admin@myfxcart.com</a></p>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 MyFX. All rights reserved.</p>
    </footer>
</body>
</html>
