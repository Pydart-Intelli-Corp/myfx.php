<?php
require_once 'config.php';
require_once 'includes/auth.php';

// Logout the user
AuthManager::logout();

// Redirect to login page
header('Location: login.php?logout=1');
exit();
?>