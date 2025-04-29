<?php
session_start();

// Prevent direct access 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: AdminLogin.php');
    exit;
}

// CSRF protection
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['login_error'] = 'Security validation failed. Please try again.';
    header('Location: AdminLogin.php');
    exit;
}

// Clear the CSRF token
unset($_SESSION['csrf_token']);

// Validate form inputs
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Check for empty fields
if (!$email || empty($password)) {
    $_SESSION['login_error'] = 'Please enter both email and password.';
    header('Location: AdminLogin.php');
    exit;
}

// Connect to database (replace with your actual database configuration)
try {
    $dsn = 'mysql:host=localhost;dbname=pet_health_tracker;charset=utf8mb4';
    $pdo = new PDO($dsn, 'db_username', 'db_password', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    // Query for admin user
    $stmt = $pdo->prepare('SELECT id, password_hash, full_name FROM admin_users WHERE email = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Verify user exists and password is correct
    if (!$user || !password_verify($password, $user['password_hash'])) {
        // Log the failed attempt (for security purposes)
        error_log("Failed login attempt for email: $email from IP: " . $_SERVER['REMOTE_ADDR']);
        
        // Rate limiting - increment failed attempts
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 1;
            $_SESSION['first_attempt_time'] = time();
        } else {
            $_SESSION['login_attempts']++;
        }
        
        // Implement simple rate limiting
        if ($_SESSION['login_attempts'] >= 5) {
            // Check if we're within the timeout window (15 minutes)
            if (time() - $_SESSION['first_attempt_time'] < 900) {
                $_SESSION['login_error'] = 'Too many failed attempts. Please try again later.';
                header('Location: AdminLogin.php');
                exit;
            } else {
                // Reset the counter if it's been more than 15 minutes
                $_SESSION['login_attempts'] = 1;
                $_SESSION['first_attempt_time'] = time();
            }
        }
        
        $_SESSION['login_error'] = 'Invalid email or password.';
        header('Location: AdminLogin.php');
        exit;
    }
    
    // Reset login attempts on successful login
    unset($_SESSION['login_attempts']);
    unset($_SESSION['first_attempt_time']);
    
    // Set session variables for authenticated user
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_name'] = $user['full_name'];
    
    // Update last login timestamp
    $stmt = $pdo->prepare('UPDATE admin_users SET last_login = NOW() WHERE id = ?');
    $stmt->execute([$user['id']]);
    
    // Set remember me cookie if selected
    if ($remember) {
        // Generate a secure token
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        
        // Hash the validator before storing in database
        $hashed_validator = password_hash($validator, PASSWORD_DEFAULT);
        
        // Set expiry date (30 days)
        $expiry = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60);
        
        // Store in database
        $stmt = $pdo->prepare('INSERT INTO auth_tokens (user_id, selector, hashed_validator, expiry) 
                              VALUES (?, ?, ?, ?)');
        $stmt->execute([$user['id'], $selector, $hashed_validator, $expiry]);
        
        // Set cookie with combined token
        $combined_token = $selector . ':' . $validator;
        
        // Set cookie for 30 days
        setcookie(
            'remember_me',
            $combined_token,
            time() + 30 * 24 * 60 * 60, // 30 days
            '/',
            '', // domain
            true, // secure
            true  // httponly
        );
    }
    
    // Log the successful login
    error_log("Successful login for admin: $email");
    
    // Redirect to admin dashboard
    header('Location: admin_dashboard.php');
    exit;
    
} catch (PDOException $e) {
    // Log the error
    error_log('Database error: ' . $e->getMessage());
    
    $_SESSION['login_error'] = 'An error occurred during login. Please try again later.';
    header('Location: AdminLogin.php');
    exit;
} 