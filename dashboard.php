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

// Live accounts data matching Next.js exactly
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

// Load metrics from JSON if exists
$metrics_data = load_data('metrics.json');
if ($metrics_data && isset($metrics_data['metrics'])) {
    $metrics = array_merge($default_metrics, $metrics_data['metrics']);
} else {
    $metrics = $default_metrics;
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
            color: #34d399;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.75rem;
            text-decoration: underline;
        }
        
        .cancel-btn {
            color: #f87171;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.75rem;
            text-decoration: underline;
        }
        
        .edit-input {
            background: linear-gradient(90deg, #0B1120, #151826);
            color: white;
            border: 1px solid #4b5563;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            width: 100%;
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
        <!-- Trading Metrics Cards -->
        <div class="grid grid-3">
            <!-- Trading Volume -->
            <div class="card metric-card">
                <div class="metric-header">
                    <span class="metric-label">Trading Volume</span>
                    <button class="edit-btn" onclick="editMetric('tradingVolume')">Edit</button>
                </div>
                <div class="metric-value" id="tradingVolume_value"><?php echo htmlspecialchars($metrics['tradingVolume']['value']); ?></div>
                <div class="metric-change <?php echo $metrics['tradingVolume']['trend']; ?>">
                    <?php echo $metrics['tradingVolume']['trend'] === 'up' ? '+' : ''; ?><?php echo $metrics['tradingVolume']['change']; ?>%
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <?php if ($metrics['tradingVolume']['trend'] === 'up'): ?>
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        <?php else: ?>
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        <?php endif; ?>
                    </svg>
                </div>
                <div class="metric-progress">
                    <div class="metric-progress-bar <?php echo $metrics['tradingVolume']['trend']; ?>" style="width: <?php echo min(abs($metrics['tradingVolume']['change']) * 5, 100); ?>%;"></div>
                </div>
            </div>

            <!-- Total Capital -->
            <div class="card metric-card">
                <div class="metric-header">
                    <span class="metric-label">Total Capital</span>
                    <button class="edit-btn" onclick="editMetric('totalCapital')">Edit</button>
                </div>
                <div class="metric-value" id="totalCapital_value"><?php echo htmlspecialchars($metrics['totalCapital']['value']); ?></div>
                <div class="metric-change <?php echo $metrics['totalCapital']['trend']; ?>">
                    <?php echo $metrics['totalCapital']['trend'] === 'up' ? '+' : ''; ?><?php echo $metrics['totalCapital']['change']; ?>%
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <?php if ($metrics['totalCapital']['trend'] === 'up'): ?>
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        <?php else: ?>
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        <?php endif; ?>
                    </svg>
                </div>
                <div class="metric-progress">
                    <div class="metric-progress-bar <?php echo $metrics['totalCapital']['trend']; ?>" style="width: <?php echo min(abs($metrics['totalCapital']['change']) * 5, 100); ?>%;"></div>
                </div>
            </div>

            <!-- Commission -->
            <div class="card metric-card">
                <div class="metric-header">
                    <span class="metric-label">Commission</span>
                    <button class="edit-btn" onclick="editMetric('commission')">Edit</button>
                </div>
                <div class="metric-value" id="commission_value"><?php echo htmlspecialchars($metrics['commission']['value']); ?></div>
                <div class="metric-change <?php echo $metrics['commission']['trend']; ?>">
                    <?php echo $metrics['commission']['trend'] === 'up' ? '+' : ''; ?><?php echo $metrics['commission']['change']; ?>%
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.25rem;" fill="currentColor" viewBox="0 0 20 20">
                        <?php if ($metrics['commission']['trend'] === 'up'): ?>
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        <?php else: ?>
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        <?php endif; ?>
                    </svg>
                </div>
                <div class="metric-progress">
                    <div class="metric-progress-bar <?php echo $metrics['commission']['trend']; ?>" style="width: <?php echo min(abs($metrics['commission']['change']) * 5, 100); ?>%;"></div>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($live_accounts as $account): ?>
                            <tr>
                                <td style="color: white; font-weight: 500;">#<?php echo htmlspecialchars($account['id']); ?></td>
                                <td style="color: white;"><?php echo htmlspecialchars($account['username']); ?></td>
                                <td style="color: white;">$<?php echo number_format($account['balance']); ?></td>
                                <td class="<?php echo $account['equity'] > $account['balance'] ? 'equity-positive' : 'equity-negative'; ?>" style="font-weight: 500;">
                                    $<?php echo number_format($account['equity']); ?>
                                </td>
                                <td style="color: white;">$<?php echo number_format($account['margin']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo getStatusBadge($account['status']); ?>">
                                        <?php echo ucfirst($account['status']); ?>
                                    </span>
                                </td>
                                <td style="color: #d1d5db; font-size: 0.875rem;"><?php echo htmlspecialchars($account['last_activity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="accounts-footer">
                <span class="accounts-count">Showing <?php echo count($live_accounts); ?> accounts</span>
                <div class="footer-buttons">
                    <button class="btn btn-green" onclick="refreshAccounts()">Refresh</button>
                    <button class="btn btn-blue" onclick="exportAccounts()">Export</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        function editMetric(metric) {
            const currentValue = document.getElementById(metric + '_value').textContent;
            const cleanValue = currentValue.replace('$', '').replace(',', '');
            
            const input = document.createElement('input');
            input.type = 'text';
            input.value = cleanValue;
            input.className = 'edit-input';
            input.id = metric + '_input';
            
            const valueElement = document.getElementById(metric + '_value');
            const headerElement = valueElement.parentElement.querySelector('.metric-header');
            
            // Replace the value with input
            valueElement.parentElement.replaceChild(input, valueElement);
            
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
            const value = parseFloat(input.value.replace(/,/g, ''));
            
            if (isNaN(value)) {
                alert('Please enter a valid number');
                return;
            }
            
            const formattedValue = '$' + value.toLocaleString();
            
            // Send AJAX request to save the metric
            fetch('ajax/save_metric.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `key=${metric}&value=${encodeURIComponent(formattedValue)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Use the saved value from server response
                    const savedValue = data.data.value;
                    
                    // Update the display with the actual saved value
                    const newValueElement = document.createElement('div');
                    newValueElement.className = 'metric-value';
                    newValueElement.id = metric + '_value';
                    newValueElement.textContent = savedValue;
                    
                    input.parentElement.replaceChild(newValueElement, input);
                    
                    // Restore edit button
                    const headerElement = newValueElement.parentElement.querySelector('.metric-header');
                    headerElement.innerHTML = `
                        <span class="metric-label">${metric.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())}</span>
                        <button class="edit-btn" onclick="editMetric('${metric}')">Edit</button>
                    `;
                    
                    console.log('Metric saved successfully');
                } else {
                    alert('Error saving metric: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving metric');
            });
        }
        
        function cancelEdit(metric, originalValue) {
            const input = document.getElementById(metric + '_input');
            
            // Restore original value
            const valueElement = document.createElement('div');
            valueElement.className = 'metric-value';
            valueElement.id = metric + '_value';
            valueElement.textContent = originalValue;
            
            input.parentElement.replaceChild(valueElement, input);
            
            // Restore edit button
            const headerElement = valueElement.parentElement.querySelector('.metric-header');
            headerElement.innerHTML = `
                <span class="metric-label">${metric.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())}</span>
                <button class="edit-btn" onclick="editMetric('${metric}')">Edit</button>
            `;
        }
        
        function logout() {
            window.location.href = 'logout.php';
        }
        
        function refreshAccounts() {
            location.reload();
        }
        
        function exportAccounts() {
            alert('Export functionality would be implemented here');
        }
    </script>
</body>
</html>