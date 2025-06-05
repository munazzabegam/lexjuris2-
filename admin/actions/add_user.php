<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $is_active = $_POST['is_active'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['user_error'] = "All fields are required.";
        header("Location: ../users.php");
        exit();
    }

    // Check if username or email already exists
    $check_query = "SELECT id FROM admin_users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['user_error'] = "Username or email already exists.";
        header("Location: ../users.php");
        exit();
    }

    // Handle profile image upload
    $profile_image = null;
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
            $profile_image = 'uploads/profile_images/' . $new_filename;
        } else {
            $_SESSION['user_error'] = "Error uploading profile image.";
            header("Location: ../users.php");
            exit();
        }
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $insert_query = "INSERT INTO admin_users (username, email, password_hash, profile_image, is_active, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ssssi", $username, $email, $password_hash, $profile_image, $is_active);

    if ($insert_stmt->execute()) {
        $_SESSION['user_success'] = "User added successfully.";
    } else {
        $_SESSION['user_error'] = "Error adding user: " . $conn->error;
    }

    header("Location: ../users.php");
    exit();
} else {
    header("Location: ../users.php");
    exit();
} 