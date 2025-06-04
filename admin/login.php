<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errors[] = "Both username and password are required";
    } else {
        try {
            // Debug: Log login attempt
            error_log("Login attempt for username: " . $username);
            
            $stmt = $conn->prepare("SELECT id, username, password_hash, is_active FROM admin_users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                // Debug: Log user found
                error_log("User found in database. ID: " . $user['id']);
                
                if (password_verify($password, $user['password_hash'])) {
                    // Debug: Log password verification
                    error_log("Password verified successfully for user: " . $username);
                    
                    if ($user['is_active']) {
                        // Update last login timestamp
                        $updateStmt = $conn->prepare("UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                        $updateStmt->bind_param("i", $user['id']);
                        $updateStmt->execute();

                        // Set session variables
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_username'] = $user['username'];

                        // Debug: Log successful login
                        error_log("User {$username} logged in successfully");
                        
                        // Redirect to dashboard
                        header('Location: dashboard.php');
                        exit();
                    } else {
                        $errors[] = "Your account is inactive. Please contact the administrator.";
                        error_log("Inactive account login attempt: " . $username);
                    }
                } else {
                    $errors[] = "Invalid username or password";
                    error_log("Password verification failed for user: " . $username);
                }
            } else {
                $errors[] = "Invalid username or password";
                error_log("User not found: " . $username);
            }
        } catch (mysqli_sql_exception $e) {
            $errors[] = "Login failed. Please try again later.";
            error_log("Login error for user {$username}: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lex Guris Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .login-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .login-brand .logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .login-brand .logo-name h4 {
            margin: 0;
            padding: 0;
            color: #bc8414;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .login-brand .logo-name h4 span {
            color: #000;
            font-weight: 700;
            margin: 0;
            padding: 0;
        }

        .login-header h1 {
            display: none;
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 5px;
        }
        .btn-login {
            background: #4361ee;
            color: white;
            padding: 0.75rem;
            border-radius: 5px;
            width: 100%;
            font-weight: 500;
        }
        .btn-login:hover {
            background: #3f37c9;
            color: white;
        }
        .signup-link {
            text-align: center;
            margin-top: 1rem;
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-brand">
                <div class="logo me-2">
                    <img src="../image/logo.png" alt="Lex Juris Logo">
                </div>
                <div class="logo-name">
                    <h4>Lex<span>Juris</span></h4>
                </div>
            </div>
            <p class="text-muted">Admin Login</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 