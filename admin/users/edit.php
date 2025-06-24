<?php
require_once __DIR__ . '/../../config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$error_message = '';
$success_message = '';
$user = null;

// Get user ID from URL
$user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$user_id) {
    $_SESSION['error_message'] = "Invalid User ID.";
    header("Location: index.php");
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT id, username, email, profile_image, is_active FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $profile_image = $user['profile_image']; // Keep existing image by default

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../../uploads/images/";
        $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid('profile_') . '.' . $file_extension;
        $target_file = $target_dir . $new_file_name;
        
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            if ($user['profile_image'] && file_exists(__DIR__ . "/../../" . $user['profile_image'])) {
                unlink(__DIR__ . "/../../" . $user['profile_image']);
            }
            $profile_image = "uploads/images/" . $new_file_name;
        } else {
            $error_message = "Error uploading profile image.";
        }
    }

    if (empty($error_message)) {
        if (!empty($password)) {
            if ($password !== $confirm_password) {
                $error_message = "Passwords do not match.";
            } elseif (strlen($password) < 6) {
                $error_message = "Password must be at least 6 characters long.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE admin_users SET password_hash = ?, profile_image = ? WHERE id = ?");
                $stmt->bind_param("ssi", $password_hash, $profile_image, $user_id);
            }
        } else {
            // Only update profile image if no password is provided
            $stmt = $conn->prepare("UPDATE admin_users SET profile_image = ? WHERE id = ?");
            $stmt->bind_param("si", $profile_image, $user_id);
        }

        if (isset($stmt) && $stmt->execute()) {
            $_SESSION['success_message'] = "User updated successfully.";
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Error updating user: " . ($stmt->error ?? 'Unknown error');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .profile-preview {
            max-width: 150px; /* Smaller image */
            max-height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
        }
        .form-label {
            font-weight: 600;
            color: #555;
            font-size: 0.95rem;
        }
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.6rem 0.8rem;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgba(188, 132, 20, 0.3);
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.1);
        }
        .btn-action {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 6px;
        }
        .btn-action i {
            margin-right: 0.4em;
        }
    </style>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Edit User</h4>
                <a href="index.php" class="btn btn-outline-secondary btn-action">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $user_id); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <?php if ($user['profile_image'] && file_exists(__DIR__ . '/../../' . $user['profile_image'])): ?>
                                    <img src="../../<?php echo htmlspecialchars($user['profile_image']); ?>" 
                                         alt="Profile" class="profile-preview mb-3">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>&background=bc8414&color=fff" 
                                         alt="Default Profile" class="profile-preview mb-3">
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Change Profile Image</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="username_display" class="form-label">Username:</label>
                                    <p class="form-control-static" id="username_display"><?php echo htmlspecialchars($user['username']); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label for="email_display" class="form-label">Email:</label>
                                    <p class="form-control-static" id="email_display"><?php echo htmlspecialchars($user['email']); ?></p>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-action">Update User</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 