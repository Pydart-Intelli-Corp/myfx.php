<?php
require_once 'config.php';
require_once 'includes/auth.php';

// Redirect if already authenticated
if (AuthManager::is_authenticated()) {
    header('Location: dashboard.php');
    exit();
}

// Redirect to login page
header('Location: login.php');
exit();
?>