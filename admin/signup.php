<?php
require_once __DIR__ . '/../config/database.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // Check if username or email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if ($count > 0) {
            $errors[] = "Username or email already exists";
        }
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Debug: Log the hashed password
            error_log("Hashed password for user {$username}: " . $hashed_password);
            
            $stmt = $conn->prepare("INSERT INTO admin_users (username, email, password_hash, is_active) VALUES (?, ?, ?, FALSE)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $result = $stmt->execute();
            
            if ($result) {
                $success = true;
                error_log("User {$username} registered successfully");
            } else {
                $errors[] = "Registration failed. Please try again later.";
                error_log("Failed to insert user {$username} into database");
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $errors[] = "Registration failed. Please try again later.";
            error_log("Registration error for user {$username}: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Lex Guris Admin</title>
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
        .signup-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .signup-header {
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .signup-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .signup-brand .logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        .signup-brand .logo-name h4 {
            margin: 0;
            padding: 0;
            color: #bc8414;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }
        .signup-brand .logo-name h4 span {
            color: #000;
            font-weight: 700;
            margin: 0;
            padding: 0;
        }
        .signup-header h1 {
            display: none;
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 5px;
        }
        .btn-signup {
            background: #4361ee;
            color: white;
            padding: 0.75rem;
            border-radius: 5px;
            width: 100%;
            font-weight: 500;
        }
        .btn-signup:hover {
            background: #3f37c9;
            color: white;
        }
        .login-link {
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
    <div class="signup-container">
        <div class="signup-header">
            <div class="signup-brand">
                <div class="logo me-2">
                    <img src="../image/logo.png" alt="Lex Juris Logo">
                </div>
                <div class="logo-name">
                    <h4>Lex<span>Juris</span></h4>
                </div>
            </div>
            <p class="text-muted">Create Admin Account</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Registration successful! <a href="login.php">Login here</a>
            </div>
        <?php endif; ?>

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
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-signup">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 