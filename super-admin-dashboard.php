<?php
require_once 'config.php';
require_once 'includes/auth.php';

$page_title = 'Super Admin Dashboard';

// Require authentication and super admin role
AuthManager::require_auth();
$user = AuthManager::get_user();

// Debug information (remove in production)
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
    echo "<!-- Debug Info:\n";
    echo "User: " . json_encode($user) . "\n";
    echo "Session: " . json_encode($_SESSION ?? []) . "\n";
    echo "-->\n";
}

if (!$user || $user['role'] !== 'superadmin') {
    // Log the redirect reason for debugging
    error_log("Super admin access denied. User: " . json_encode($user) . " | Session: " . json_encode($_SESSION ?? []));
    
    // If this is a POST request or AJAX, return JSON error instead of redirect
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Access denied: Super admin role required']);
        exit();
    }
    
    header('Location: login.php');
    exit();
}

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

        .super-admin-badge {
            background: linear-gradient(45deg, #dc2626, #ef4444);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
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
            margin-bottom: 0.5rem;
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
        
        .edit-btn {
            color: #60a5fa;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.75rem;
            text-decoration: underline;
        }
        
        .edit-btn:hover {
            color: #93c5fd;
        }
        
        .edit-controls {
            display: flex;
            gap: 0.25rem;
        }
        
        .save-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .save-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        
        .cancel-btn {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
        }

        .cancel-btn:hover {
            background: linear-gradient(135deg, #475569, #334155);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.4);
        }
        
        .edit-input {
            background: linear-gradient(90deg, #0f172a, #1e293b);
            color: #60a5fa;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1.2rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
        }

        .edit-input:focus {
            outline: none;
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background: linear-gradient(90deg, #1e293b, #334155);
        }

        .edit-input-small {
            background: linear-gradient(90deg, #0B1120, #151826);
            color: white;
            border: 1px solid #4b5563;
            border-radius: 0.25rem;
            padding: 0.125rem 0.25rem;
            font-size: 0.875rem;
            width: 80px;
        }

        .trend-select {
            background: linear-gradient(90deg, #0B1120, #151826);
            color: white;
            border: 1px solid #4b5563;
            border-radius: 0.25rem;
            padding: 0.125rem 0.25rem;
            font-size: 0.875rem;
            width: 70px;
        }
        
        /* External Section Styles */
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

        .external-box.editable:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.1);
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 20px;
            padding: 0;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .add-account-modal {
            backdrop-filter: blur(20px);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 2rem 1rem 2rem;
            margin-bottom: 0;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }

        .modal-title-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .modal-icon {
            font-size: 2rem;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 0.75rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-header h3 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .modal-description {
            padding: 1rem 2rem;
            text-align: center;
        }

        .modal-description p {
            color: rgba(148, 163, 184, 0.8);
            margin: 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .close {
            color: rgba(148, 163, 184, 0.6);
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: white;
        }

        #addAccountForm {
            padding: 0 2rem 2rem 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-row:has(.form-group:nth-child(2)) {
            grid-template-columns: 1fr 1fr;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            color: #e2e8f0;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            letter-spacing: 0.025em;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 1rem;
            background: rgba(30, 41, 59, 0.8);
            border: 2px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input::placeholder {
            color: rgba(148, 163, 184, 0.5);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: rgba(30, 41, 59, 0.9);
        }

        .input-with-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-currency {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #22c55e;
            font-weight: 700;
            font-size: 1rem;
            z-index: 10;
            pointer-events: none;
            user-select: none;
        }

        .input-with-icon input {
            padding-left: 2.75rem !important;
            text-align: left;
        }

        .input-with-icon input:focus {
            padding-left: 2.75rem !important;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(148, 163, 184, 0.1);
        }

        .modal-actions .btn {
            flex: 1;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-gray {
            background: linear-gradient(135deg, rgba(107, 114, 128, 0.8) 0%, rgba(75, 85, 99, 0.8) 100%);
            color: white;
            border: 2px solid rgba(148, 163, 184, 0.2);
        }

        .btn-gray:hover {
            background: linear-gradient(135deg, rgba(107, 114, 128, 1) 0%, rgba(75, 85, 99, 1) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.3);
        }

        .btn-green {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: 2px solid rgba(34, 197, 94, 0.3);
        }

        .btn-green:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
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

        .accounts-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .accounts-header-text {
            flex: 1;
        }
        
        .accounts-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }

        .add-account-btn {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .add-account-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
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
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .btn-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .btn-green:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }
        
        .btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .btn-blue:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
        }

        .edit-form {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            animation: editFadeIn 0.3s ease-out;
        }

        @keyframes editFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .edit-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(59, 130, 246, 0.3);
        }

        .edit-icon {
            font-size: 1.2rem;
        }

        .edit-title {
            font-weight: 600;
            color: #60a5fa;
            font-size: 0.9rem;
        }

        .edit-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .edit-label {
            font-size: 0.85rem;
            color: #e2e8f0;
            font-weight: 500;
            min-width: 80px;
        }

        .edit-info {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        .info-icon {
            font-size: 1rem;
            margin-top: 1px;
        }

        .info-text strong {
            color: #10b981;
            font-size: 0.8rem;
        }

        .info-text small {
            color: #94a3b8;
            font-size: 0.7rem;
            line-height: 1.3;
        }

        .edit-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        /* Books Container Styles - Compact & Beautiful */
        .books-container {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            padding: 0.5rem;
        }

        .book-section {
            flex: 1;
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid rgba(148, 163, 184, 0.15);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
        }

        .book-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.08);
        }

        .book-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 0.875rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }

        .book-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #f8fafc;
            margin: 0;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.025em;
        }

        .book-controls {
            display: flex;
            gap: 0.4rem;
        }

        .book-controls button {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .book-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: auto auto auto;
            gap: 0.875rem;
        }

        .deposit-card {
            grid-column: span 2;
        }

        .profit-card {
            grid-row: 2;
        }

        .brokerage-card {
            grid-row: 2;
        }

        .withdrawal-card {
            grid-row: 3;
        }

        .trade-volume-card {
            grid-row: 3;
        }

        .book-metrics .metric-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.4) 100%);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 14px;
            padding: 0.875rem;
            position: relative;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .book-metrics .metric-card.editable:hover {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.6) 100%);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px -8px rgba(59, 130, 246, 0.2);
        }

        .book-metrics .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.4rem;
        }

        .book-metrics .metric-label {
            font-size: 0.8rem;
            color: #cbd5e1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .book-metrics .metric-value {
            font-size: 1.3rem;
            font-weight: 800;
            color: #f8fafc;
            margin-bottom: 0.5rem;
        }

        .book-metrics .metric-change {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .book-metrics .metric-progress {
            width: 100%;
            height: 4px;
            background-color: #374151;
            border-radius: 2px;
            overflow: hidden;
        }

        .book-metrics .metric-progress-bar {
            height: 100%;
            transition: width 0.3s ease;
        }

        .book-metrics .edit-btn {
            padding: 2px 6px;
            font-size: 0.75rem;
            border-radius: 4px;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            margin: 10% auto;
            padding: 0;
            border: 1px solid #374151;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #374151;
        }

        .modal-header h3 {
            margin: 0;
            color: #f9fafb;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .close {
            color: #9ca3af;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #f9fafb;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #f9fafb;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #374151;
            border-radius: 8px;
            background-color: #1f2937;
            color: #f9fafb;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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

        /* Responsive Design - Compact & Beautiful */
        @media (max-width: 1024px) {
            .book-metrics {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .books-container {
                flex-direction: column;
                gap: 1.25rem;
                padding: 0.25rem;
            }

            .book-section {
                padding: 1rem;
            }
            
            .book-title {
                font-size: 1.1rem;
            }

            .book-controls {
                flex-direction: column;
                gap: 0.3rem;
            }
            
            .book-controls button {
                padding: 0.35rem 0.7rem;
                font-size: 0.75rem;
            }

            .book-metrics .metric-value {
                font-size: 1.1rem;
            }
            
            .book-metrics .metric-label {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .books-container {
                gap: 1rem;
                margin-bottom: 2rem;
            }
            
            .book-section {
                padding: 0.875rem;
                border-radius: 16px;
            }
            
            .book-metrics {
                gap: 0.75rem;
            }
            
            .book-metrics .metric-card {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <img src="assets/myfx.png" alt="Myforexcart Logo" class="header-logo">
                <span class="header-title">Super Admin Dashboard</span>
                <span class="super-admin-badge">Super Admin</span>
            </div>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- External Deposit & Supply Section -->
        <div class="external-boxes">
            <div class="external-box editable">
                <div class="external-box-header">
                    <span class="external-box-label">Deposit</span>
                    <button class="edit-btn" onclick="editExternalMetric('deposit')">Edit</button>
                </div>
                <div class="external-box-value" id="new_deposit" data-metric="external_deposit">
                    $<?php echo number_format($metrics['external_deposit']['value']); ?>
                </div>
                <div class="external-box-change <?php echo $metrics['external_deposit']['trend']; ?>">
                    <?php echo ($metrics['external_deposit']['change'] >= 0 ? '+' : '') . $metrics['external_deposit']['change']; ?>%
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="external-box-progress">
                    <div class="external-box-progress-bar up" style="width: 0%;"></div>
                </div>
            </div>

            <div class="external-box editable">
                <div class="external-box-header">
                    <span class="external-box-label">Supply</span>
                    <button class="edit-btn" onclick="editExternalMetric('supply')">Edit</button>
                </div>
                <div class="external-box-value" id="supply" data-metric="external_supply">
                    $<?php echo number_format($metrics['external_supply']['value']); ?>
                </div>
                <div class="external-box-change <?php echo $metrics['external_supply']['trend']; ?>">
                    <?php echo ($metrics['external_supply']['change'] >= 0 ? '+' : '') . $metrics['external_supply']['change']; ?>%
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="external-box-progress">
                    <div class="external-box-progress-bar up" style="width: 0%;"></div>
                </div>
            </div>
        </div>

        <!-- Trading Books Section - Super Admin with Edit Controls -->
        <div class="books-container">
            <!-- Book A -->
            <div class="book-section">
                <div class="book-header">
                    <h2 class="book-title">Book A</h2>
                </div>
                <div class="book-metrics">
                    <div class="metric-card editable deposit-card">
                        <div class="metric-header">
                            <span class="metric-label">Deposit</span>
                            <button class="edit-btn" onclick="editBookMetric('bookA', 'deposit')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookA_deposit" data-metric="book_a_deposit">
                            $<?php echo number_format($metrics['book_a_deposit']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_a_deposit']['trend']; ?>">
                            <?php echo ($metrics['book_a_deposit']['change'] >= 0 ? '+' : '') . $metrics['book_a_deposit']['change']; ?>%
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar up" style="width: 0%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable profit-card">
                        <div class="metric-header">
                            <span class="metric-label">Profit</span>
                            <button class="edit-btn" onclick="editBookMetric('bookA', 'profit')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookA_profit" data-metric="book_a_profit_loss">
                            $<?php echo number_format($metrics['book_a_profit_loss']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_a_profit_loss']['trend']; ?>">
                            <?php echo ($metrics['book_a_profit_loss']['change'] >= 0 ? '+' : '') . $metrics['book_a_profit_loss']['change']; ?>%
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar up" style="width: 0%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable brokerage-card">
                        <div class="metric-header">
                            <span class="metric-label">Brokerage</span>
                            <button class="edit-btn" onclick="editBookMetric('bookA', 'brokerage')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookA_brokerage" data-metric="book_a_profit_loss">
                            $<?php echo number_format($metrics['book_a_profit_loss']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_a_profit_loss']['trend']; ?>">
                            <?php echo ($metrics['book_a_profit_loss']['change'] >= 0 ? '+' : '') . $metrics['book_a_profit_loss']['change']; ?>%
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar up" style="width: 0%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable withdrawal-card">
                        <div class="metric-header">
                            <span class="metric-label">Withdrawal</span>
                            <button class="edit-btn" onclick="editBookMetric('bookA', 'withdrawal')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookA_withdrawal" data-metric="book_a_withdraw">
                            $<?php echo number_format($metrics['book_a_withdraw']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_a_withdraw']['trend']; ?>">
                            <?php echo ($metrics['book_a_withdraw']['change'] >= 0 ? '+' : '') . $metrics['book_a_withdraw']['change']; ?>%
                            <?php if ($metrics['book_a_withdraw']['trend'] === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($metrics['book_a_withdraw']['trend'] === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $metrics['book_a_withdraw']['trend']; ?>" style="width: <?php echo min(abs($metrics['book_a_withdraw']['change']), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable trade-volume-card">
                        <div class="metric-header">
                            <span class="metric-label">Trade Volume</span>
                            <button class="edit-btn" onclick="editBookMetric('bookA', 'tradeVolume')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookA_tradeVolume" data-metric="book_a_trade_volume">
                            $<?php echo number_format($metrics['book_a_trade_volume']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_a_trade_volume']['trend']; ?>">
                            <?php echo ($metrics['book_a_trade_volume']['change'] >= 0 ? '+' : '') . $metrics['book_a_trade_volume']['change']; ?>%
                            <?php if ($metrics['book_a_trade_volume']['trend'] === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($metrics['book_a_trade_volume']['trend'] === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $metrics['book_a_trade_volume']['trend']; ?>" style="width: <?php echo min(abs($metrics['book_a_trade_volume']['change']), 100); ?>%;"></div>
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
                    <div class="metric-card editable deposit-card">
                        <div class="metric-header">
                            <span class="metric-label">Deposit</span>
                            <button class="edit-btn" onclick="editBookMetric('bookB', 'deposit')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookB_deposit" data-metric="book_b_deposit">
                            $<?php echo number_format($metrics['book_b_deposit']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_b_deposit']['trend']; ?>">
                            <?php echo ($metrics['book_b_deposit']['change'] >= 0 ? '+' : '') . $metrics['book_b_deposit']['change']; ?>%
                            <?php if ($metrics['book_b_deposit']['trend'] === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($metrics['book_b_deposit']['trend'] === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $metrics['book_b_deposit']['trend']; ?>" style="width: <?php echo min(abs($metrics['book_b_deposit']['change']), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable profit-card">
                        <div class="metric-header">
                            <span class="metric-label">Profit</span>
                            <button class="edit-btn" onclick="editBookMetric('bookB', 'profit')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookB_profit" data-metric="book_b_profit_loss">
                            $<?php echo number_format($metrics['book_b_profit_loss']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_b_profit_loss']['trend']; ?>">
                            <?php echo ($metrics['book_b_profit_loss']['change'] >= 0 ? '+' : '') . $metrics['book_b_profit_loss']['change']; ?>%
                            <?php if ($metrics['book_b_profit_loss']['trend'] === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($metrics['book_b_profit_loss']['trend'] === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $metrics['book_b_profit_loss']['trend']; ?>" style="width: <?php echo min(abs($metrics['book_b_profit_loss']['change']), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable brokerage-card">
                        <div class="metric-header">
                            <span class="metric-label">Brokerage</span>
                            <button class="edit-btn" onclick="editBookMetric('bookB', 'brokerage')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookB_brokerage" data-metric="book_b_profit_loss">
                            $<?php echo number_format($metrics['book_b_profit_loss']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_b_profit_loss']['trend']; ?>">
                            <?php echo ($metrics['book_b_profit_loss']['change'] >= 0 ? '+' : '') . $metrics['book_b_profit_loss']['change']; ?>%
                            <?php if ($metrics['book_b_profit_loss']['trend'] === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($metrics['book_b_profit_loss']['trend'] === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $metrics['book_b_profit_loss']['trend']; ?>" style="width: <?php echo min(abs($metrics['book_b_profit_loss']['change']), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable withdrawal-card">
                        <div class="metric-header">
                            <span class="metric-label">Withdrawal</span>
                            <button class="edit-btn" onclick="editBookMetric('bookB', 'withdrawal')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookB_withdrawal" data-metric="book_b_withdraw">
                            $<?php echo number_format($metrics['book_b_withdraw']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_b_withdraw']['trend']; ?>">
                            <?php echo ($metrics['book_b_withdraw']['change'] >= 0 ? '+' : '') . $metrics['book_b_withdraw']['change']; ?>%
                            <?php if ($metrics['book_b_withdraw']['trend'] === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($metrics['book_b_withdraw']['trend'] === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $metrics['book_b_withdraw']['trend']; ?>" style="width: <?php echo min(abs($metrics['book_b_withdraw']['change']), 100); ?>%;"></div>
                        </div>
                    </div>

                    <div class="metric-card editable trade-volume-card">
                        <div class="metric-header">
                            <span class="metric-label">Trade Volume</span>
                            <button class="edit-btn" onclick="editBookMetric('bookB', 'tradeVolume')">Edit</button>
                        </div>
                        <div class="metric-value" id="bookB_tradeVolume" data-metric="book_b_trade_volume">
                            $<?php echo number_format($metrics['book_b_trade_volume']['value']); ?>
                        </div>
                        <div class="metric-change <?php echo $metrics['book_b_trade_volume']['trend']; ?>">
                            <?php echo ($metrics['book_b_trade_volume']['change'] >= 0 ? '+' : '') . $metrics['book_b_trade_volume']['change']; ?>%
                            <?php if ($metrics['book_b_trade_volume']['trend'] === 'up'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php elseif ($metrics['book_b_trade_volume']['trend'] === 'down'): ?>
                            <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar <?php echo $metrics['book_b_trade_volume']['trend']; ?>" style="width: <?php echo min(abs($metrics['book_b_trade_volume']['change']), 100); ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Accounts Section -->
        <div class="accounts-section">
            <div class="accounts-header">
                <div class="accounts-header-content">
                    <div class="accounts-header-text">
                        <h2 class="accounts-title">Live Accounts - Full Management</h2>
                        <p class="accounts-subtitle">Super Admin access to all account data and controls</p>
                    </div>
                    <button class="btn btn-green add-account-btn" onclick="showAddAccountModal()">+ Add New Account</button>
                </div>
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
                                    <span class="account-field" id="username-<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['username']); ?></span>
                                    <input type="text" class="account-edit-input" id="username-input-<?php echo $account['id']; ?>" value="<?php echo htmlspecialchars($account['username']); ?>" style="display: none;">
                                </td>
                                <td style="color: white;">
                                    <span class="account-field" id="balance-<?php echo $account['id']; ?>">$<?php echo number_format($account['balance']); ?></span>
                                    <input type="number" class="account-edit-input" id="balance-input-<?php echo $account['id']; ?>" value="<?php echo $account['balance']; ?>" style="display: none;">
                                </td>
                                <td class="<?php echo $account['equity'] > $account['balance'] ? 'equity-positive' : 'equity-negative'; ?>" style="font-weight: 500;">
                                    <span class="account-field" id="equity-<?php echo $account['id']; ?>">$<?php echo number_format($account['equity']); ?></span>
                                    <input type="number" class="account-edit-input" id="equity-input-<?php echo $account['id']; ?>" value="<?php echo $account['equity']; ?>" style="display: none;">
                                </td>
                                <td style="color: white;">
                                    <span class="account-field" id="margin-<?php echo $account['id']; ?>">$<?php echo number_format($account['margin']); ?></span>
                                    <input type="number" class="account-edit-input" id="margin-input-<?php echo $account['id']; ?>" value="<?php echo $account['margin']; ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="account-field" id="status-<?php echo $account['id']; ?>">
                                        <span class="status-badge <?php echo getStatusBadge($account['status']); ?>">
                                            <?php echo ucfirst($account['status']); ?>
                                        </span>
                                    </span>
                                    <select class="account-edit-input" id="status-input-<?php echo $account['id']; ?>" style="display: none;">
                                        <option value="active" <?php echo $account['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $account['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="margin_call" <?php echo $account['status'] == 'margin_call' ? 'selected' : ''; ?>>Margin Call</option>
                                        <option value="suspended" <?php echo $account['status'] == 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                    </select>
                                </td>
                                <td style="color: #d1d5db; font-size: 0.875rem;">
                                    <span class="account-field" id="last_activity-<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['last_activity']); ?></span>
                                    <input type="datetime-local" class="account-edit-input" id="last_activity-input-<?php echo $account['id']; ?>" value="<?php echo date('Y-m-d\TH:i', strtotime($account['last_activity'])); ?>" style="display: none;">
                                </td>
                                <td>
                                    <button class="edit-btn" id="edit-btn-<?php echo $account['id']; ?>" onclick="editAccount('<?php echo $account['id']; ?>')">Edit</button>
                                    <button class="save-btn" id="save-btn-<?php echo $account['id']; ?>" onclick="saveAccount('<?php echo $account['id']; ?>')" style="display: none;">Save</button>
                                    <button class="cancel-btn" id="cancel-btn-<?php echo $account['id']; ?>" onclick="cancelAccountEdit('<?php echo $account['id']; ?>')" style="display: none;">Cancel</button>
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

    <script>
        function editMetric(metric) {
            const currentValue = document.getElementById(metric + '_value').textContent;
            const cleanValue = currentValue.replace('$', '').replace(',', '');
            
            // Get current change and trend from the metric data
            const metrics = <?php echo json_encode($metrics); ?>;
            const currentChange = metrics[metric].change;
            const currentTrend = metrics[metric].trend;
            
            const input = document.createElement('input');
            input.type = 'text';
            input.value = cleanValue;
            input.className = 'edit-input';
            input.id = metric + '_input';
            
            const valueElement = document.getElementById(metric + '_value');
            const headerElement = valueElement.parentElement.querySelector('.metric-header');
            
            // Replace the value with input and additional controls
            const editContainer = document.createElement('div');
            editContainer.appendChild(input);
            
            // Add change percentage input
            const editRow1 = document.createElement('div');
            editRow1.className = 'edit-row';
            editRow1.innerHTML = `
                <label>Change:</label>
                <input type="number" step="0.1" value="${currentChange}" class="edit-input-small" id="${metric}_change">
                <span style="color: #d1d5db; font-size: 0.75rem;">%</span>
            `;
            editContainer.appendChild(editRow1);
            
            // Add trend selector
            const editRow2 = document.createElement('div');
            editRow2.className = 'edit-row';
            editRow2.innerHTML = `
                <label>Trend:</label>
                <select class="trend-select" id="${metric}_trend">
                    <option value="up" ${currentTrend === 'up' ? 'selected' : ''}>Up</option>
                    <option value="down" ${currentTrend === 'down' ? 'selected' : ''}>Down</option>
                </select>
            `;
            editContainer.appendChild(editRow2);
            
            valueElement.parentElement.replaceChild(editContainer, valueElement);
            
            // Replace edit button with save/cancel buttons
            headerElement.innerHTML = `
                <span class="metric-label">${metric.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())}</span>
                <div class="edit-controls">
                    <button class="save-btn" onclick="saveMetric('${metric}')">Save</button>
                    <button class="cancel-btn" onclick="cancelEdit('${metric}', '${currentValue}')">Cancel</button>
                </div>
            `;
            
            input.focus();
        }
        
        function saveMetric(metric) {
            const input = document.getElementById(metric + '_input');
            const changeInput = document.getElementById(metric + '_change');
            const trendSelect = document.getElementById(metric + '_trend');
            
            const value = parseFloat(input.value.replace(/,/g, ''));
            const change = parseFloat(changeInput.value);
            const trend = trendSelect.value;
            
            if (isNaN(value) || isNaN(change)) {
                alert('Please enter valid numbers');
                return;
            }
            
            const formattedValue = '$' + value.toLocaleString();
            
            // Send AJAX request to save the metric
            fetch('ajax/save_metric.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `key=${metric}&value=${encodeURIComponent(formattedValue)}&change=${change}&trend=${trend}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the page to show updated values
                    location.reload();
                } else {
                    alert('Error saving metric: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving metric');
            });
        }
        
        function editExternalMetric(type) {
            console.log('Editing external metric:', type);
            let metricKey;
            if (type === 'deposit') {
                metricKey = 'external_deposit';
            } else if (type === 'supply') {
                metricKey = 'external_supply';
            } else if (type === 'withdraw') {
                metricKey = 'external_withdraw';
            } else if (type === 'profit') {
                metricKey = 'external_profit_loss';
            }
            
            console.log('Metric key:', metricKey);
            if (metricKey) {
                editMetricByKey(metricKey);
            } else {
                console.error('Invalid metric type:', type);
            }
        }
        
        function editBookMetric(book, type) {
            console.log('Editing book metric:', book, type);
            let metricKey;
            const bookPrefix = book === 'bookA' ? 'book_a' : 'book_b';
            
            if (type === 'deposit') {
                metricKey = bookPrefix + '_deposit';
            } else if (type === 'withdrawal') {
                metricKey = bookPrefix + '_withdraw';
            } else if (type === 'tradeVolume') {
                metricKey = bookPrefix + '_trade_volume';
            } else if (type === 'profit' || type === 'brokerage') {
                metricKey = bookPrefix + '_profit_loss';
            }
            
            console.log('Metric key:', metricKey);
            if (metricKey) {
                editMetricByKey(metricKey);
            } else {
                console.error('Invalid metric type:', type, 'for book:', book);
            }
        }
        
        function editMetricByKey(metricKey) {
            console.log('editMetricByKey called with:', metricKey);
            const valueElement = document.querySelector(`[data-metric="${metricKey}"]`);
            if (!valueElement) {
                console.error('Metric element not found for key:', metricKey);
                alert('Error: Metric element not found. Please refresh the page and try again.');
                return;
            }
            
            console.log('Found value element:', valueElement);
            const currentValue = valueElement.textContent;
            const cleanValue = currentValue.replace('$', '').replace(/,/g, '');
            console.log('Current value:', currentValue, 'Clean value:', cleanValue);
            
            // Get current metrics data
            const metrics = <?php echo json_encode($metrics); ?>;
            console.log('Available metrics:', metrics);
            const currentMetric = metrics[metricKey];
            if (!currentMetric) {
                console.error('Metric data not found for key:', metricKey);
                console.log('Available metric keys:', Object.keys(metrics));
                alert('Error: Metric data not found. Please refresh the page and try again.');
                return;
            }
            
            const currentChange = currentMetric.change;
            const currentTrend = currentMetric.trend;
            
            // Create edit interface
            const editContainer = document.createElement('div');
            editContainer.className = 'edit-container';
            
            const input = document.createElement('input');
            input.type = 'text';
            input.value = cleanValue;
            input.className = 'edit-input';
            input.id = metricKey + '_input';
            
            // Change and trend are now computed automatically server-side
            
            editContainer.innerHTML = `
                <div class="edit-form">
                    <div class="edit-header">
                        <span class="edit-icon">✏️</span>
                        <span class="edit-title">Edit Metric Value</span>
                    </div>
                    <div class="edit-row">
                        <label class="edit-label">New Value: $</label>
                        ${input.outerHTML}
                    </div>
                    <div class="edit-buttons">
                        <button class="save-btn" onclick="saveMetricByKey('${metricKey}')">Save</button>
                        <button class="cancel-btn" onclick="cancelMetricEdit('${metricKey}')">Cancel</button>
                    </div>
                </div>
            `;
            
            valueElement.parentElement.replaceChild(editContainer, valueElement);
            document.getElementById(metricKey + '_input').focus();
        }
        
        function saveMetricByKey(metricKey) {
            console.log('saveMetricByKey called with:', metricKey);
            const input = document.getElementById(metricKey + '_input');
            
            if (!input) {
                console.error('Input field not found for:', metricKey + '_input');
                alert('Error: Input field not found');
                return;
            }
            
            const rawValue = input.value.replace(/,/g, '');
            const value = parseFloat(rawValue);
            
            console.log('Raw value:', rawValue, 'Parsed value:', value);
            
            if (isNaN(value)) {
                alert('Please enter a valid number');
                return;
            }
            
            console.log('Sending AJAX request to save metric...');
            // Send AJAX request to save the metric (change and trend computed server-side)
            fetch('ajax/save_metric.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `key=${metricKey}&value=${encodeURIComponent(value)}`
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message briefly
                    const successMsg = document.createElement('div');
                    successMsg.textContent = '✅ Metric updated successfully!';
                    successMsg.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:15px 20px;border-radius:8px;z-index:1000;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.2);';
                    document.body.appendChild(successMsg);
                    
                    // Wait 2 seconds before reloading to show the success message
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    console.error('Save failed:', data);
                    alert('Error saving metric: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('Error saving metric: ' + error.message + '. Please try again.');
            });
        }
        
        function cancelMetricEdit(metricKey) {
            // Simply reload to restore original state without potential session issues
            location.reload();
        }

        function editAccount(accountId) {
            // Hide display elements and show input elements
            const fields = ['username', 'balance', 'equity', 'margin', 'status', 'last_activity'];
            
            fields.forEach(field => {
                const displayElement = document.getElementById(field + '-' + accountId);
                const inputElement = document.getElementById(field + '-input-' + accountId);
                
                if (displayElement && inputElement) {
                    displayElement.style.display = 'none';
                    inputElement.style.display = 'block';
                }
            });

            // Toggle buttons
            document.getElementById('edit-btn-' + accountId).style.display = 'none';
            document.getElementById('save-btn-' + accountId).style.display = 'inline-block';
            document.getElementById('cancel-btn-' + accountId).style.display = 'inline-block';
        }

        function cancelAccountEdit(accountId) {
            // Show display elements and hide input elements
            const fields = ['username', 'balance', 'equity', 'margin', 'status', 'last_activity'];
            
            fields.forEach(field => {
                const displayElement = document.getElementById(field + '-' + accountId);
                const inputElement = document.getElementById(field + '-input-' + accountId);
                
                if (displayElement && inputElement) {
                    displayElement.style.display = 'block';
                    inputElement.style.display = 'none';
                }
            });

            // Toggle buttons
            document.getElementById('edit-btn-' + accountId).style.display = 'inline-block';
            document.getElementById('save-btn-' + accountId).style.display = 'none';
            document.getElementById('cancel-btn-' + accountId).style.display = 'none';
        }

        function saveAccount(accountId) {
            console.log('Saving account:', accountId);
            
            // Get values from input fields
            const username = document.getElementById('username-input-' + accountId).value;
            const balance = document.getElementById('balance-input-' + accountId).value;
            const equity = document.getElementById('equity-input-' + accountId).value;
            const margin = document.getElementById('margin-input-' + accountId).value;
            const status = document.getElementById('status-input-' + accountId).value;
            const last_activity = document.getElementById('last_activity-input-' + accountId).value;

            // Validate inputs
            if (!username.trim()) {
                alert('Username cannot be empty');
                return;
            }
            
            if (isNaN(balance) || balance < 0) {
                alert('Balance must be a positive number');
                return;
            }

            // Create form data
            const formData = new FormData();
            formData.append('action', 'update_account');
            formData.append('account_id', accountId);
            formData.append('username', username);
            formData.append('balance', balance);
            formData.append('equity', equity);
            formData.append('margin', margin);
            formData.append('status', status);
            formData.append('last_activity', last_activity);

            console.log('Sending account update request...');

            // Send AJAX request
            fetch('ajax/save_account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update display values
                    document.getElementById('username-' + accountId).textContent = username;
                    document.getElementById('balance-' + accountId).textContent = '$' + parseInt(balance).toLocaleString();
                    document.getElementById('equity-' + accountId).textContent = '$' + parseInt(equity).toLocaleString();
                    document.getElementById('margin-' + accountId).textContent = '$' + parseInt(margin).toLocaleString();
                    
                    // Update status badge
                    const statusElement = document.getElementById('status-' + accountId);
                    let statusClass = 'status-' + status.replace('_', '-');
                    statusElement.innerHTML = '<span class="status-badge ' + statusClass + '">' + status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ') + '</span>';
                    
                    document.getElementById('last_activity-' + accountId).textContent = new Date(last_activity).toLocaleString();

                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.textContent = '✅ Account updated successfully!';
                    successMsg.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:15px 20px;border-radius:8px;z-index:1000;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.2);';
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);

                    // Cancel edit mode
                    cancelAccountEdit(accountId);
                } else {
                    console.error('Update failed:', data);
                    alert('Error updating account: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('Error updating account: ' + error.message);
            });
        }

        function addNewAccount() {
            alert('Add new account functionality would be implemented here');
            // This would open a modal or form to add a new account
        }
        
        function logout() {
            window.location.href = 'logout.php';
        }
        
        function refreshAccounts() {
            location.reload();
        }

        // Add new account functionality
        function showAddAccountModal() {
            document.getElementById('addAccountModal').style.display = 'flex';
        }

        function hideAddAccountModal() {
            document.getElementById('addAccountModal').style.display = 'none';
            document.getElementById('addAccountForm').reset();
        }

        function saveNewAccount() {
            console.log('Saving new account...');
            const form = document.getElementById('addAccountForm');
            
            // Get form values for validation
            const username = form.username.value.trim();
            const balance = parseFloat(form.balance.value);
            const equity = parseFloat(form.equity.value);
            const margin = parseFloat(form.margin.value);
            const status = form.status.value;
            
            // Client-side validation
            if (!username) {
                alert('Username is required');
                return;
            }
            
            if (isNaN(balance) || balance < 0) {
                alert('Balance must be a positive number');
                return;
            }
            
            if (isNaN(equity) || equity < 0) {
                alert('Equity must be a positive number');
                return;
            }
            
            if (isNaN(margin) || margin < 0) {
                alert('Margin must be a positive number');
                return;
            }
            
            if (!status) {
                alert('Please select an account status');
                return;
            }
            
            const formData = new FormData(form);
            
            fetch('ajax/add_account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.textContent = '✅ Account created successfully!';
                    successMsg.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:15px 20px;border-radius:8px;z-index:1000;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.2);';
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                    
                    hideAddAccountModal();
                    
                    // Reload page after a short delay to show the success message
                    setTimeout(() => {
                        refreshAccounts();
                    }, 1500);
                } else {
                    console.error('Add account failed:', data);
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('Error adding account: ' + error.message);
            });
        }
        
        // Session management to prevent automatic redirects
        function keepSessionAlive() {
            fetch('ajax/keep_session.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).catch(error => {
                console.log('Session refresh failed:', error);
            });
        }
        
        // Keep session alive every 30 minutes
        setInterval(keepSessionAlive, 30 * 60 * 1000);
        
        // Initial session refresh
        keepSessionAlive();
    </script>

    <!-- Add Account Modal -->
    <div id="addAccountModal" class="modal" style="display: none;">
        <div class="modal-content add-account-modal">
            <div class="modal-header">
                <div class="modal-title-section">
                    <div class="modal-icon">🏦</div>
                    <h3>Create New Trading Account</h3>
                </div>
                <span class="close" onclick="hideAddAccountModal()">&times;</span>
            </div>
            
            <div class="modal-description">
                <p>💼 Set up a new live trading account with initial balance and trading parameters</p>
            </div>
            
            <form id="addAccountForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">👤 Account Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter unique username" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="balance">💰 Initial Balance</label>
                        <div class="input-with-icon">
                            <span class="input-currency">$</span>
                            <input type="number" id="balance" name="balance" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="equity">📈 Account Equity</label>
                        <div class="input-with-icon">
                            <span class="input-currency">$</span>
                            <input type="number" id="equity" name="equity" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="margin">🎯 Margin Requirement</label>
                        <div class="input-with-icon">
                            <span class="input-currency">$</span>
                            <input type="number" id="margin" name="margin" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">⚡ Account Status</label>
                        <select id="status" name="status" required>
                            <option value="">Select status...</option>
                            <option value="active">🟢 Active</option>
                            <option value="inactive">🟡 Inactive</option>
                            <option value="margin_call">🟠 Margin Call</option>
                            <option value="suspended">🔴 Suspended</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-gray" onclick="hideAddAccountModal()">
                        ❌ Cancel
                    </button>
                    <button type="button" class="btn btn-green" onclick="saveNewAccount()">
                        ✅ Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>