<?php
require_once __DIR__ . '/../../config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Username or Email already exists.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $profile_image = null;

            // Handle profile image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $target_dir = __DIR__ . "/../../image/";
                $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $new_file_name = uniqid('profile_') . '.' . $file_extension;
                $target_file = $target_dir . $new_file_name;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                    $profile_image = "image/" . $new_file_name;
                } else {
                    $error_message = "Error uploading profile image.";
                }
            }

            if (empty($error_message)) {
                $stmt = $conn->prepare("INSERT INTO admin_users (username, email, password_hash, profile_image, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
                $stmt->bind_param("ssss", $username, $email, $password_hash, $profile_image);

                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "User created successfully.";
                    header("Location: index.php");
                    exit();
                } else {
                    $error_message = "Error creating user: " . $stmt->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .profile-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Add New User</h1>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <img src="../../assets/images/default-avatar.png" 
                                     alt="Default Profile" class="profile-preview mb-3" id="profilePreview">
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Profile Image (Optional)</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image" 
                                           accept="image/*" onchange="previewImage(this)">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Create User</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('profilePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html> 