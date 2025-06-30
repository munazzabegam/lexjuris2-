<?php
session_start();
require_once '../../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $full_name = trim($_POST['full_name']);
    $education = trim($_POST['education']);
    $contact = trim($_POST['contact']);
    $portfolio = trim($_POST['portfolio']);
    $order_index = (int)$_POST['order_index'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validation
    if (empty($full_name)) {
        $_SESSION['error_message'] = "Full name is required.";
        header('Location: ../edit.php?id=' . $id);
        exit();
    }

    // Get current member data
    $query = "SELECT photo FROM udupi_team_members WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error_message'] = "Member not found.";
        header('Location: ../index.php');
        exit();
    }
    
    $current_member = $result->fetch_assoc();
    $photo_path = $current_member['photo'];

    // Handle file upload if new photo is provided
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../uploads/team_photos/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['photo']['name']);
        $file_extension = strtolower($file_info['extension']);
        
        // Check file type
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error_message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header('Location: ../edit.php?id=' . $id);
            exit();
        }

        // Check file size (2MB limit)
        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            $_SESSION['error_message'] = "File size must be less than 2MB.";
            header('Location: ../edit.php?id=' . $id);
            exit();
        }

        // Generate unique filename
        $filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
            // Delete old photo if it exists
            if (!empty($current_member['photo'])) {
                $old_photo_path = '../../../' . $current_member['photo'];
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
            $photo_path = 'uploads/team_photos/' . $filename;
        } else {
            $_SESSION['error_message'] = "Failed to upload photo.";
            header('Location: ../edit.php?id=' . $id);
            exit();
        }
    }

    try {
        // Update database
        $query = "UPDATE udupi_team_members SET full_name = ?, education = ?, photo = ?, contact = ?, portfolio = ?, order_index = ?, is_active = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssiii", $full_name, $education, $photo_path, $contact, $portfolio, $order_index, $is_active, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Udupi team member updated successfully.";
            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['error_message'] = "Failed to update team member.";
            header('Location: ../edit.php?id=' . $id);
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header('Location: ../edit.php?id=' . $id);
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
?> 