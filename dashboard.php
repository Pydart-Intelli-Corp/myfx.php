<?php
require_once 'config.php';
require_once 'includes/auth.php';

$page_title = 'Trading Dashboard';

// Require authentication
AuthManager::require_auth();

// Default metrics structure matching Next.js exactly
$default_metrics = [
    'tradingVolume' => [
        'value' => '$2,847,392',
        'change' => 12.4,
        'trend' => 'up'
    ],
    'totalCapital' => [
        'value' => '$18,924,581',
        'change' => 8.7,
        'trend' => 'up'
    ],
    'commission' => [
        'value' => '$84,573',
        'change' => -2.3,
        'trend' => 'down'
    ]
];

// Load metrics from MySQL database
$metrics = DataManager::get_metrics();
if (empty($metrics)) {
    $metrics = $default_metrics;
}

// Load accounts from MySQL database
$live_accounts = DataManager::get_accounts();
if (empty($live_accounts)) {
    // Default live accounts data if none in database
    $live_accounts = [
        [
            'id' => '1001',
            'username' => 'trader_001',
            'balance' => 50000,
            'equity' => 52340,
            'margin' => 1200,
            'status' => 'active',
            'last_activity' => '2 min ago'
        ],
        [
            'id' => '1002',
            'username' => 'trader_002',
            'balance' => 25000,
            'equity' => 23890,
            'margin' => 800,
            'status' => 'active',
            'last_activity' => '5 min ago'
        ],
        [
            'id' => '1003',
            'username' => 'trader_003',
            'balance' => 75000,
            'equity' => 76200,
            'margin' => 2500,
            'status' => 'inactive',
            'last_activity' => '1 hour ago'
        ],
        [
            'id' => '1004',
            'username' => 'trader_004',
            'balance' => 30000,
            'equity' => 28500,
            'margin' => 950,
            'status' => 'suspended',
            'last_activity' => '3 hours ago'
        ]
    ];
}

// Function to get status badge class
function getStatusBadge($status) {
    switch ($status) {
        case 'active': return 'status-active';
        case 'inactive': return 'status-inactive';
        case 'suspended': return 'status-suspended';
        default: return 'status-default';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Myforexcart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            background-color: #0B1120;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: white;
        }
        
        /* Header */
        .header {
            background: linear-gradient(90deg, #0B1120, #1a1f2e);
            border-bottom: 1px solid #374151;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            max-width: 80rem;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 4rem;
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .header-logo {
            width: 48px;
            height: 48px;
            margin-right: 1.5rem;
            border-radius: 8px;
        }
        
        .header-title {
            font-size: 1rem;
            color: #d1d5db;
            font-weight: 600;
        }
        
        .logout-btn {
            background-color: #dc2626;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }
        
        .logout-btn:hover {
            background-color: #b91c1c;
        }
        
        /* Main Content */
        .main-content {
            max-width: 80rem;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        /* Grid */
        .grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        
        @media (max-width: 768px) {
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
        
        /* Cards */
        .card {
            background: linear-gradient(135deg, #0B1120, #1a1f2e);
            border: 1px solid #374151;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .metric-card {
            position: relative;
        }
        
        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .metric-label {
            color: #d1d5db;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .metric-value {
            font-size: 1.875rem;
            font-weight: bold;
            color: white;
        }
        
        .metric-change {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .metric-change.up {
            color: #34d399;
        }
        
        .metric-change.down {
            color: #f87171;
        }
        
        .metric-progress {
            margin-top: 1rem;
            height: 0.5rem;
            background: linear-gradient(90deg, #0B1120, #151826);
            border-radius: 9999px;
            overflow: hidden;
        }
        
        .metric-progress-bar {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.3s ease;
        }
        
        .metric-progress-bar.up {
            background-color: #10b981;
        }
        
        .metric-progress-bar.down {
            background-color: #ef4444;
        }
        
        /* External Section Styles */
        .external-section {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.3) 100%);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .external-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .external-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .external-subtitle {
            color: rgba(148, 163, 184, 0.8);
            font-size: 0.875rem;
            margin: 0;
        }

        .external-boxes {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .external-box {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.4) 100%);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 14px;
            padding: 1.25rem;
            position: relative;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .external-box:hover {
            transform: translateY(-2px);
            border-color: rgba(34, 197, 94, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .external-box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .external-box-label {
            color: rgba(148, 163, 184, 0.9);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .external-box-value {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .external-box-change {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }

        .external-box-change.up {
            color: #22c55e;
        }

        .external-box-change.down {
            color: #ef4444;
        }

        .external-box-progress {
            height: 4px;
            background: rgba(148, 163, 184, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }

        .external-box-progress-bar {
            height: 100%;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .external-box-progress-bar.up {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
        }

        .external-box-progress-bar.down {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        }
        
        /* Table */
        .accounts-section {
            margin-top: 2rem;
            background: linear-gradient(135deg, #0B1120, #1a1f2e);
            border: 1px solid #374151;
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .accounts-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #4b5563;
        }
        
        .accounts-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .accounts-subtitle {
            color: #d1d5db;
            font-size: 0.875rem;
        }
        
        .accounts-table {
            width: 100%;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: linear-gradient(90deg, #0B1120, #151826);
        }
        
        th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            color: #d1d5db;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        tbody tr {
            border-bottom: 1px solid #4b5563;
            transition: background-color 0.2s;
        }
        
        tbody tr:hover {
            background: linear-gradient(90deg, #0B1120, #151826);
        }
        
        td {
            padding: 1rem 1.5rem;
            white-space: nowrap;
        }
        
        .equity-positive {
            color: #34d399;
        }
        
        .equity-negative {
            color: #f87171;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #065f46;
            color: #a7f3d0;
        }
        
        .status-inactive {
            background-color: #92400e;
            color: #fde68a;
        }
        
        .status-suspended {
            background-color: #991b1b;
            color: #fca5a5;
        }
        
        .accounts-footer {
            padding: 1rem 1.5rem;
            background: linear-gradient(90deg, rgba(11, 17, 32, 0.8), rgba(21, 24, 38, 0.8));
            border-top: 1px solid #4b5563;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .accounts-count {
            color: #d1d5db;
            font-size: 0.875rem;
        }
        
        .footer-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.25rem 0.75rem;
            border: none;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .btn-green {
            background-color: #059669;
            color: white;
        }
        
        .btn-green:hover {
            background-color: #047857;
        }
        
        .btn-blue {
            background-color: #2563eb;
            color: white;
        }
        
        .btn-blue:hover {
            background-color: #1d4ed8;
        }

        /* Account Edit Styles */
        .edit-btn, .save-btn, .cancel-btn {
            padding: 4px 8px;
            border: none;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            margin-right: 4px;
            transition: background-color 0.2s;
        }

        .edit-btn {
            background-color: #f59e0b;
            color: white;
        }

        .edit-btn:hover {
            background-color: #d97706;
        }

        .save-btn {
            background-color: #059669;
            color: white;
        }

        .save-btn:hover {
            background-color: #047857;
        }

        .cancel-btn {
            background-color: #dc2626;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #b91c1c;
        }



        /* Books Container Styles - Compact & Beautiful */
        .books-container {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .book-section {
            flex: 1;
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid rgba(148, 163, 184, 0.1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .book-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }

        .book-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #f8fafc;
            margin: 0;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .add-deposit-btn button {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .book-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: auto auto auto;
            gap: 0.75rem;
        }

        .book-metrics .deposit-card {
            grid-column: span 2;
            grid-row: 1;
        }

        .book-metrics .profit-card {
            grid-column: 1;
            grid-row: 2;
        }

        .book-metrics .brokerage-card {
            grid-column: 2;
            grid-row: 2;
        }

        .book-metrics .withdrawal-card {
            grid-column: 1;
            grid-row: 3;
        }

        .book-metrics .trade-volume-card {
            grid-column: 2;
            grid-row: 3;
        }

        .book-metrics .metric-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 15px;
            padding: 0.75rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .book-metrics .metric-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.2);
        }

        .book-metrics .metric-header {
            margin-bottom: 0.4rem;
        }

        .book-metrics .metric-label {
            font-size: 0.75rem;
            color: #cbd5e1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .book-metrics .metric-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: #f1f5f9;
            margin-bottom: 0.3rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .book-metrics .metric-change {
            display: flex;
            align-items: center;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .book-metrics .metric-progress {
            width: 100%;
            height: 3px;
            background-color: rgba(71, 85, 105, 0.3);
            border-radius: 3px;
            overflow: hidden;
        }

        .book-metrics .metric-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #34d399);
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 3px;
        }

        /* Enhanced Responsive Design */
        @media (max-width: 1024px) {
            .book-metrics {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .books-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .book-section {
                padding: 1rem;
            }
            
            .book-title {
                font-size: 1.1rem;
            }
            
            .add-deposit-btn button {
                padding: 0.35rem 0.7rem;
                font-size: 0.75rem;
            }
        }

        /* Modal Styles - Compact & Beautiful */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            padding: 0;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 20px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            transform: scale(1);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: scale(0.9) translateY(-20px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }

        .modal-header h3 {
            margin: 0;
            color: #f8fafc;
            font-size: 1.1rem;
            font-weight: 700;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #94a3b8;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            padding: 0.25rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .close:hover {
            color: #f8fafc;
            background: rgba(239, 68, 68, 0.1);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            color: #e2e8f0;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.4) 100%);
            color: #f8fafc;
            font-size: 0.9rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.2), 0 0 0 1px rgba(59, 130, 246, 0.3);
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.6) 100%);
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(148, 163, 184, 0.1);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
            padding: 0.6rem 1.25rem;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
            transform: translateY(-1px);
        }

        .btn-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.6rem 1.25rem;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-green:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <img src="assets/myfx.png" alt="Myforexcart Logo" class="header-logo">
                <span class="header-title">Trading Admin Dashboard</span>
            </div>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- External Deposit & Supply Section -->
        <div class="external-boxes">
            <div class="external-box">
                <div class="external-box-header">
                    <span class="external-box-label">Deposit</span>
                </div>
                <div class="external-box-value" id="new_deposit">$<?php echo number_format($metrics['external_deposit']['value'] ?? 0); ?></div>
                <?php 
                $deposit_change = $metrics['external_deposit']['change'] ?? 0;
                $deposit_trend = $metrics['external_deposit']['trend'] ?? 'neutral';
                $deposit_class = $deposit_trend === 'up' ? 'up' : ($deposit_trend === 'down' ? 'down' : 'neutral');
                $deposit_sign = $deposit_change > 0 ? '+' : ($deposit_change < 0 ? '' : '+');
                ?>
                <div class="external-box-change <?php echo $deposit_class; ?>">
                    <?php echo $deposit_sign . number_format($deposit_change, 1); ?>%
                    <?php if ($deposit_trend === 'up'): ?>
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <?php elseif ($deposit_trend === 'down'): ?>
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <?php endif; ?>
                </div>
                <div class="external-box-progress">
                    <div class="external-box-progress-bar <?php echo $deposit_class; ?>" style="width: <?php echo min(abs($deposit_change), 100); ?>%;"></div>
                </div>
            </div>

            <div class="external-box">
                <div class="external-box-header">
                    <span class="external-box-label">Supply</span>
                </div>
                <div class="external-box-value" id="supply">$<?php echo number_format($metrics['external_supply']['value'] ?? 0); ?></div>
                <?php 
                $supply_change = $metrics['external_supply']['change'] ?? 0;
                $supply_trend = $metrics['external_supply']['trend'] ?? 'neutral';
                $supply_class = $supply_trend === 'up' ? 'up' : ($supply_trend === 'down' ? 'down' : 'neutral');
                $supply_sign = $supply_change > 0 ? '+' : ($supply_change < 0 ? '' : '+');
                ?>
                <div class="external-box-change <?php echo $supply_class; ?>">
                    <?php echo $supply_sign . number_format($supply_change, 1); ?>%
                    <?php if ($supply_trend === 'up'): ?>
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <?php elseif ($supply_trend === 'down'): ?>
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <?php endif; ?>
                </div>
                <div class="external-box-progress">
                    <div class="external-box-progress-bar <?php echo $supply_class; ?>" style="width: <?php echo min(abs($supply_change), 100); ?>%;"></div>
                </div>
            </div>
        </div>

        <!-- Trading Books Section -->
        <div class="books-container">
            <!-- Book A -->
            <div class="book-section">
                <div class="book-header">
                    <h2 class="book-title">Book A</h2>
                </div>
                <div class="book-metrics">
                    <div class="metric-card deposit-card">
                        <div class="metric-header">
                            <span class="metric-label">Deposit</span>
                        </div>
                        <div class="metric-value" id="bookA_deposit">$<?php echo number_format($metrics['book_a_deposit']['value'] ?? 0); ?></div>
                        <?php 
                        $bookA_deposit_change = $metrics['book_a_deposit']['change'] ?? 0;
                        $bookA_deposit_trend = $metrics['book_a_deposit']['trend'] ?? 'neutral';
                        $bookA_deposit_class = $bookA_deposit_trend === 'up' ? 'up' : ($bookA_deposit_trend === 'down' ? 'down' : 'neutral');
                        $bookA_deposit_sign = $bookA_deposit_change > 0 ? '+' : ($bookA_deposit_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookA_deposit_class; ?>">
                            <?php echo $bookA_deposit_sign . number_format($bookA_deposit_change, 1); ?>%
                            <?php if ($bookA_deposit_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookA_deposit_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookA_deposit_class; ?>" style="width: <?php echo min(abs($bookA_deposit_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card profit-card">
                        <div class="metric-header">
                            <span class="metric-label">Profit</span>
                        </div>
                        <div class="metric-value" id="bookA_profit">$<?php echo number_format($metrics['book_a_profit_loss']['value'] ?? 0); ?></div>
                        <?php 
                        $bookA_profit_change = $metrics['book_a_profit_loss']['change'] ?? 0;
                        $bookA_profit_trend = $metrics['book_a_profit_loss']['trend'] ?? 'neutral';
                        $bookA_profit_class = $bookA_profit_trend === 'up' ? 'up' : ($bookA_profit_trend === 'down' ? 'down' : 'neutral');
                        $bookA_profit_sign = $bookA_profit_change > 0 ? '+' : ($bookA_profit_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookA_profit_class; ?>">
                            <?php echo $bookA_profit_sign . number_format($bookA_profit_change, 1); ?>%
                            <?php if ($bookA_profit_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookA_profit_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookA_profit_class; ?>" style="width: <?php echo min(abs($bookA_profit_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card brokerage-card">
                        <div class="metric-header">
                            <span class="metric-label">Brokerage</span>
                        </div>
                        <div class="metric-value" id="bookA_brokerage">$<?php echo number_format($metrics['book_a_profit_loss']['value'] ?? 0); ?></div>
                        <?php 
                        $bookA_brokerage_change = $metrics['book_a_profit_loss']['change'] ?? 0;
                        $bookA_brokerage_trend = $metrics['book_a_profit_loss']['trend'] ?? 'neutral';
                        $bookA_brokerage_class = $bookA_brokerage_trend === 'up' ? 'up' : ($bookA_brokerage_trend === 'down' ? 'down' : 'neutral');
                        $bookA_brokerage_sign = $bookA_brokerage_change > 0 ? '+' : ($bookA_brokerage_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookA_brokerage_class; ?>">
                            <?php echo $bookA_brokerage_sign . number_format($bookA_brokerage_change, 1); ?>%
                            <?php if ($bookA_brokerage_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookA_brokerage_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookA_brokerage_class; ?>" style="width: <?php echo min(abs($bookA_brokerage_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card withdrawal-card">
                        <div class="metric-header">
                            <span class="metric-label">Withdrawal</span>
                        </div>
                        <div class="metric-value" id="bookA_withdrawal">$<?php echo number_format($metrics['book_a_withdraw']['value'] ?? 0); ?></div>
                        <?php 
                        $bookA_withdrawal_change = $metrics['book_a_withdraw']['change'] ?? 0;
                        $bookA_withdrawal_trend = $metrics['book_a_withdraw']['trend'] ?? 'neutral';
                        $bookA_withdrawal_class = $bookA_withdrawal_trend === 'up' ? 'up' : ($bookA_withdrawal_trend === 'down' ? 'down' : 'neutral');
                        $bookA_withdrawal_sign = $bookA_withdrawal_change > 0 ? '+' : ($bookA_withdrawal_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookA_withdrawal_class; ?>">
                            <?php echo $bookA_withdrawal_sign . number_format($bookA_withdrawal_change, 1); ?>%
                            <?php if ($bookA_withdrawal_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookA_withdrawal_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookA_withdrawal_class; ?>" style="width: <?php echo min(abs($bookA_withdrawal_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card trade-volume-card">
                        <div class="metric-header">
                            <span class="metric-label">Trade Volume</span>
                        </div>
                        <div class="metric-value" id="bookA_tradeVolume">$<?php echo number_format($metrics['book_a_trade_volume']['value'] ?? 0); ?></div>
                        <?php 
                        $bookA_tradeVolume_change = $metrics['book_a_trade_volume']['change'] ?? 0;
                        $bookA_tradeVolume_trend = $metrics['book_a_trade_volume']['trend'] ?? 'neutral';
                        $bookA_tradeVolume_class = $bookA_tradeVolume_trend === 'up' ? 'up' : ($bookA_tradeVolume_trend === 'down' ? 'down' : 'neutral');
                        $bookA_tradeVolume_sign = $bookA_tradeVolume_change > 0 ? '+' : ($bookA_tradeVolume_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookA_tradeVolume_class; ?>">
                            <?php echo $bookA_tradeVolume_sign . number_format($bookA_tradeVolume_change, 1); ?>%
                            <?php if ($bookA_tradeVolume_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookA_tradeVolume_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookA_tradeVolume_class; ?>" style="width: <?php echo min(abs($bookA_tradeVolume_change), 100); ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book B -->
            <div class="book-section">
                <div class="book-header">
                    <h2 class="book-title">Book B</h2>
                </div>
                <div class="book-metrics">
                    <div class="metric-card deposit-card">
                        <div class="metric-header">
                            <span class="metric-label">Deposit</span>
                        </div>
                        <div class="metric-value" id="bookB_deposit">$<?php echo number_format($metrics['book_b_deposit']['value'] ?? 0); ?></div>
                        <?php 
                        $bookB_deposit_change = $metrics['book_b_deposit']['change'] ?? 0;
                        $bookB_deposit_trend = $metrics['book_b_deposit']['trend'] ?? 'neutral';
                        $bookB_deposit_class = $bookB_deposit_trend === 'up' ? 'up' : ($bookB_deposit_trend === 'down' ? 'down' : 'neutral');
                        $bookB_deposit_sign = $bookB_deposit_change > 0 ? '+' : ($bookB_deposit_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookB_deposit_class; ?>">
                            <?php echo $bookB_deposit_sign . number_format($bookB_deposit_change, 1); ?>%
                            <?php if ($bookB_deposit_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookB_deposit_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookB_deposit_class; ?>" style="width: <?php echo min(abs($bookB_deposit_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card profit-card">
                        <div class="metric-header">
                            <span class="metric-label">Profit</span>
                        </div>
                        <div class="metric-value" id="bookB_profit">$<?php echo number_format($metrics['book_b_profit_loss']['value'] ?? 0); ?></div>
                        <?php 
                        $bookB_profit_change = $metrics['book_b_profit_loss']['change'] ?? 0;
                        $bookB_profit_trend = $metrics['book_b_profit_loss']['trend'] ?? 'neutral';
                        $bookB_profit_class = $bookB_profit_trend === 'up' ? 'up' : ($bookB_profit_trend === 'down' ? 'down' : 'neutral');
                        $bookB_profit_sign = $bookB_profit_change > 0 ? '+' : ($bookB_profit_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookB_profit_class; ?>">
                            <?php echo $bookB_profit_sign . number_format($bookB_profit_change, 1); ?>%
                            <?php if ($bookB_profit_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookB_profit_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookB_profit_class; ?>" style="width: <?php echo min(abs($bookB_profit_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card brokerage-card">
                        <div class="metric-header">
                            <span class="metric-label">Brokerage</span>
                        </div>
                        <div class="metric-value" id="bookB_brokerage">$<?php echo number_format($metrics['book_b_profit_loss']['value'] ?? 0); ?></div>
                        <?php 
                        $bookB_brokerage_change = $metrics['book_b_profit_loss']['change'] ?? 0;
                        $bookB_brokerage_trend = $metrics['book_b_profit_loss']['trend'] ?? 'neutral';
                        $bookB_brokerage_class = $bookB_brokerage_trend === 'up' ? 'up' : ($bookB_brokerage_trend === 'down' ? 'down' : 'neutral');
                        $bookB_brokerage_sign = $bookB_brokerage_change > 0 ? '+' : ($bookB_brokerage_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookB_brokerage_class; ?>">
                            <?php echo $bookB_brokerage_sign . number_format($bookB_brokerage_change, 1); ?>%
                            <?php if ($bookB_brokerage_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookB_brokerage_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookB_brokerage_class; ?>" style="width: <?php echo min(abs($bookB_brokerage_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card withdrawal-card">
                        <div class="metric-header">
                            <span class="metric-label">Withdrawal</span>
                        </div>
                        <div class="metric-value" id="bookB_withdrawal">$<?php echo number_format($metrics['book_b_withdraw']['value'] ?? 0); ?></div>
                        <?php 
                        $bookB_withdrawal_change = $metrics['book_b_withdraw']['change'] ?? 0;
                        $bookB_withdrawal_trend = $metrics['book_b_withdraw']['trend'] ?? 'neutral';
                        $bookB_withdrawal_class = $bookB_withdrawal_trend === 'up' ? 'up' : ($bookB_withdrawal_trend === 'down' ? 'down' : 'neutral');
                        $bookB_withdrawal_sign = $bookB_withdrawal_change > 0 ? '+' : ($bookB_withdrawal_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookB_withdrawal_class; ?>">
                            <?php echo $bookB_withdrawal_sign . number_format($bookB_withdrawal_change, 1); ?>%
                            <?php if ($bookB_withdrawal_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookB_withdrawal_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookB_withdrawal_class; ?>" style="width: <?php echo min(abs($bookB_withdrawal_change), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card trade-volume-card">
                        <div class="metric-header">
                            <span class="metric-label">Trade Volume</span>
                        </div>
                        <div class="metric-value" id="bookB_tradeVolume">$<?php echo number_format($metrics['book_b_trade_volume']['value'] ?? 0); ?></div>
                        <?php 
                        $bookB_tradeVolume_change = $metrics['book_b_trade_volume']['change'] ?? 0;
                        $bookB_tradeVolume_trend = $metrics['book_b_trade_volume']['trend'] ?? 'neutral';
                        $bookB_tradeVolume_class = $bookB_tradeVolume_trend === 'up' ? 'up' : ($bookB_tradeVolume_trend === 'down' ? 'down' : 'neutral');
                        $bookB_tradeVolume_sign = $bookB_tradeVolume_change > 0 ? '+' : ($bookB_tradeVolume_change < 0 ? '' : '+');
                        ?>
                        <div class="metric-change <?php echo $bookB_tradeVolume_class; ?>">
                            <?php echo $bookB_tradeVolume_sign . number_format($bookB_tradeVolume_change, 1); ?>%
                            <?php if ($bookB_tradeVolume_trend === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($bookB_tradeVolume_trend === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $bookB_tradeVolume_class; ?>" style="width: <?php echo min(abs($bookB_tradeVolume_change), 100); ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Accounts Section -->
        <div class="accounts-section">
            <div class="accounts-header">
                <h2 class="accounts-title">Live Accounts</h2>
                <p class="accounts-subtitle">Real-time trading account monitoring</p>
            </div>
            <div class="accounts-table">
                <table>
                    <thead>
                        <tr>
                            <th>Account ID</th>
                            <th>Username</th>
                            <th>Balance</th>
                            <th>Equity</th>
                            <th>Margin</th>
                            <th>Status</th>
                            <th>Last Activity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($live_accounts as $account): ?>
                            <tr id="account-row-<?php echo $account['id']; ?>">
                                <td style="color: white; font-weight: 500;">#<?php echo htmlspecialchars($account['id']); ?></td>
                                <td style="color: white;">
                                    <?php echo htmlspecialchars($account['username']); ?>
                                </td>
                                <td style="color: white;">
                                    $<?php echo number_format($account['balance']); ?>
                                </td>
                                <td class="<?php echo $account['equity'] > $account['balance'] ? 'equity-positive' : 'equity-negative'; ?>" style="font-weight: 500;">
                                    $<?php echo number_format($account['equity']); ?>
                                </td>
                                <td style="color: white;">
                                    $<?php echo number_format($account['margin']); ?>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo getStatusBadge($account['status']); ?>">
                                        <?php echo ucfirst($account['status']); ?>
                                    </span>
                                </td>
                                <td style="color: #d1d5db; font-size: 0.875rem;">
                                    <?php echo htmlspecialchars($account['last_activity']); ?>
                                </td>
                                <td>
                                    <span style="color: #6b7280; font-size: 0.875rem;">Read Only</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="accounts-footer">
                <span class="accounts-count">Showing <?php echo count($live_accounts); ?> accounts</span>
                <div class="footer-buttons">
                    <button class="btn btn-green" onclick="refreshAccounts()">Refresh</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Deposit Modal -->
    <div id="depositModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add Deposit</h3>
                <span class="close" onclick="closeDepositModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="depositForm">
                    <input type="hidden" id="bookType" name="bookType">
                    <div class="form-group">
                        <label for="depositAmount">Amount ($)</label>
                        <input type="number" id="depositAmount" name="amount" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="depositDescription">Description</label>
                        <textarea id="depositDescription" name="description" rows="3" placeholder="Enter deposit details..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeDepositModal()">Cancel</button>
                        <button type="submit" class="btn btn-green">Add Deposit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }
        
        function refreshAccounts() {
            location.reload();
        }



        // Deposit Modal Functions
        function showDepositModal(bookType) {
            document.getElementById('depositModal').style.display = 'block';
            document.getElementById('bookType').value = bookType;
            document.getElementById('modalTitle').textContent = `Add Deposit to ${bookType === 'bookA' ? 'Book A' : 'Book B'}`;
            document.getElementById('depositAmount').focus();
        }

        function closeDepositModal() {
            document.getElementById('depositModal').style.display = 'none';
            document.getElementById('depositForm').reset();
        }

        // Handle deposit form submission
        document.getElementById('depositForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const bookType = formData.get('bookType');
            const amount = parseFloat(formData.get('amount'));
            const description = formData.get('description');

            // For now, just update the display (in a real app, this would save to database)
            const currentValue = document.getElementById(bookType + '_deposit').textContent;
            const currentAmount = parseFloat(currentValue.replace(/[$,]/g, '')) || 0;
            const newAmount = currentAmount + amount;
            
            document.getElementById(bookType + '_deposit').textContent = '$' + newAmount.toLocaleString();
            
            // Close modal and show success message
            closeDepositModal();
            alert(`Deposit of $${amount.toLocaleString()} added to ${bookType === 'bookA' ? 'Book A' : 'Book B'} successfully!`);
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('depositModal');
            if (event.target == modal) {
                closeDepositModal();
            }
        }
    </script>
</body>
</html>