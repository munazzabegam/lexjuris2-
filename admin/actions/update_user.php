<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $is_active = $_POST['is_active'];

    // Validate input
    if (empty($username) || empty($email)) {
        $_SESSION['user_error'] = "Required fields cannot be empty.";
        header("Location: ../users.php");
        exit();
    }

    // Check if username or email already exists for other users
    $check_query = "SELECT id FROM admin_users WHERE (username = ? OR email = ?) AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ssi", $username, $email, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['user_error'] = "Username or email already exists.";
        header("Location: ../users.php");
        exit();
    }

    // Get current profile image
    $current_image_query = "SELECT profile_image FROM admin_users WHERE id = ?";
    $current_image_stmt = $conn->prepare($current_image_query);
    $current_image_stmt->bind_param("i", $user_id);
    $current_image_stmt->execute();
    $current_image_result = $current_image_stmt->get_result();
    $current_image = $current_image_result->fetch_assoc()['profile_image'];

    // Handle profile image upload
    $profile_image = $current_image; // Keep existing image by default
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../uploads/profile_images/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['user_error'] = "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF";
            header("Location: ../users.php");
            exit();
        }

        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
            // Delete old profile image if it exists
            if ($current_image && file_exists(__DIR__ . '/../../' . $current_image)) {
                unlink(__DIR__ . '/../../' . $current_image);
            }
            $profile_image = 'uploads/profile_images/' . $new_filename;
        } else {
            $_SESSION['user_error'] = "Error uploading profile image.";
            header("Location: ../users.php");
            exit();
        }
    }

    // Prepare update query
    if (!empty($password)) {
        // Update with new password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE admin_users SET username = ?, email = ?, password_hash = ?, profile_image = ?, is_active = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssii", $username, $email, $password_hash, $profile_image, $is_active, $user_id);
    } else {
        // Update without changing password
        $update_query = "UPDATE admin_users SET username = ?, email = ?, profile_image = ?, is_active = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssii", $username, $email, $profile_image, $is_active, $user_id);
    }

    if ($update_stmt->execute()) {
        $_SESSION['user_success'] = "User updated successfully.";
    } else {
        $_SESSION['user_error'] = "Error updating user: " . $conn->error;
    }

    header("Location: ../users.php");
    exit();
} else {
    header("Location: ../users.php");
    exit();
} 