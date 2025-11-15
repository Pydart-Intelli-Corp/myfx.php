<?php
require_once 'config.php';
require_once 'includes/auth.php';

$page_title = 'Admin Login';
$login_error = '';
$login_success = '';

// Handle login form submission
if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validate_csrf_token($csrf_token)) {
        $login_error = 'Invalid security token. Please try again.';
    } elseif (empty($username) || empty($password)) {
        $login_error = 'Please enter both username and password.';
    } else {
        $result = AuthManager::login($username, $password);
        
        if ($result['success']) {
            $login_success = $result['message'];
            // Redirect based on user role
            $user = AuthManager::get_user();
            if ($user && $user['role'] === 'superadmin') {
                header('refresh:2;url=super-admin-dashboard.php');
            } else {
                header('refresh:2;url=dashboard.php');
            }
        } else {
            $login_error = 'Invalid credentials';
        }
    }
}

// Redirect if already authenticated
if (AuthManager::is_authenticated()) {
    $user = AuthManager::get_user();
    if ($user && $user['role'] === 'superadmin') {
        header('Location: super-admin-dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Myforexcart</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/logo1.png">
    <link rel="shortcut icon" type="image/png" href="assets/logo1.png">
    <link rel="apple-touch-icon" href="assets/logo1.png">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background-color: #0B1120;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .login-container {
            max-width: 28rem;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        
        .form-card {
            background: linear-gradient(135deg, #0B1120, #1a1f2e);
            padding: 3rem 2rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid #374151;
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 0.25rem;
            border-radius: 8px;
            object-fit: contain;
            object-position: center;
            background: transparent;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            image-rendering: pixelated;
            max-width: 100%;
            display: block;
        }
        
        .title {
            font-size: 1.25rem;
            color: #d1d5db;
            font-weight: 400;
            margin: 0;
        }
        
        .form-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #4b5563;
            border-radius: 0.375rem;
            background: linear-gradient(90deg, #0B1120, #151826);
            color: white;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s;
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        .form-input:focus {
            outline: none;
            ring: 2px;
            ring-color: #60a5fa;
            border-color: transparent;
            box-shadow: 0 0 0 2px #60a5fa;
        }
        
        .error-message {
            color: #fca5a5;
            font-size: 0.875rem;
            background: rgba(153, 27, 27, 0.2);
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #991b1b;
        }
        
        .success-message {
            color: #86efac;
            font-size: 0.875rem;
            background: rgba(21, 128, 61, 0.2);
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #15803d;
        }
        
        .submit-btn {
            width: 100%;
            background-color: #16a34a;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            outline: none;
        }
        
        .submit-btn:hover {
            background-color: #15803d;
        }
        
        .submit-btn:disabled {
            background-color: #166534;
            cursor: not-allowed;
        }
        
        .submit-btn:focus {
            outline: none;
            ring: 2px;
            ring-color: #4ade80;
            box-shadow: 0 0 0 2px #4ade80;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-card">
            <div class="card-header">
                <img src="assets/myfx.png" alt="Myforexcart Logo" class="logo" onerror="this.src='assets/logo.png'">
                <h2 class="title">Trading Admin Portal</h2>
            </div>
            
            <form method="POST" action="" class="form-container">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input" 
                        placeholder="Enter username" 
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter password" 
                        value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>" 
                        required
                    >
                </div>
                
                <?php if ($login_error): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($login_error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($login_success): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($login_success); ?> Redirecting to dashboard...
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="submit-btn">
                    Sign In
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const usernameField = document.getElementById("username");
            usernameField.focus();
        });
    </script>
</body>
</html>
?>