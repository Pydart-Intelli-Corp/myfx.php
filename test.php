<?php
echo "<h1>Myforexcart PHP Setup Test</h1>";

// Test PHP version
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// Test file permissions
$testDir = __DIR__ . '/data';
echo "<p><strong>Data Directory:</strong> " . ($testDir) . "</p>";
echo "<p><strong>Directory Exists:</strong> " . (is_dir($testDir) ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Directory Writable:</strong> " . (is_writable($testDir) ? 'Yes' : 'No') . "</p>";

// Test config loading
try {
    require_once 'config.php';
    echo "<p><strong>Config Loading:</strong> ✅ Success</p>";
    echo "<p><strong>Site Name:</strong> " . SITE_NAME . "</p>";
} catch (Exception $e) {
    echo "<p><strong>Config Loading:</strong> ❌ Error: " . $e->getMessage() . "</p>";
}

// Test auth class
try {
    require_once 'includes/auth.php';
    echo "<p><strong>Auth Class:</strong> ✅ Success</p>";
} catch (Exception $e) {
    echo "<p><strong>Auth Class:</strong> ❌ Error: " . $e->getMessage() . "</p>";
}

// Test data file creation
if (file_exists(METRICS_FILE)) {
    echo "<p><strong>Metrics File:</strong> ✅ Created</p>";
} else {
    echo "<p><strong>Metrics File:</strong> ⏳ Will be created on first run</p>";
}

if (file_exists(ACCOUNTS_FILE)) {
    echo "<p><strong>Accounts File:</strong> ✅ Created</p>";
} else {
    echo "<p><strong>Accounts File:</strong> ⏳ Will be created on first run</p>";
}

// Test session functionality
session_start();
$_SESSION['test'] = 'working';
if ($_SESSION['test'] === 'working') {
    echo "<p><strong>Sessions:</strong> ✅ Working</p>";
} else {
    echo "<p><strong>Sessions:</strong> ❌ Not working</p>";
}

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li><a href='index.php'>Visit Home Page</a></li>";
echo "<li><a href='login.php'>Test Login (admin / Access@myfx)</a></li>";
echo "<li><a href='dashboard.php'>Access Dashboard (after login)</a></li>";
echo "</ol>";

echo "<p><em>Delete this test.php file before production deployment.</em></p>";
?>