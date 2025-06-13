<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? NULL;
    $company = $_POST['company'] ?? NULL;
    $testimonial_content = $_POST['testimonial'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $user_id = $_SESSION['user_id']; // Assuming user_id is set in session from login

    // Handle photo upload
    $photo_path = NULL;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/testimonials/";
        if (!is_dir(__DIR__ . '/../../../' . $target_dir)) {
            mkdir(__DIR__ . '/../../../' . $target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid('testimonial_') . '.' . $file_extension;
        $target_file = __DIR__ . '/../../../' . $target_dir . $new_file_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = $target_dir . $new_file_name;
        } else {
            $_SESSION['testimonial_error'] = "Failed to upload photo.";
            header("Location: ../create.php");
            exit();
        }
    }

    // Find the current maximum order_index and add 1
    $stmt_order = $conn->prepare("SELECT MAX(order_index) AS max_order FROM testimonials");
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();
    $row_order = $result_order->fetch_assoc();
    $new_order_index = ($row_order['max_order'] !== null) ? $row_order['max_order'] + 1 : 1;

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO testimonials (user_id, name, position, company, photo, testimonial, order_index, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssi", $user_id, $name, $position, $company, $photo_path, $testimonial_content, $new_order_index, $is_active);

    if ($stmt->execute()) {
        $_SESSION['testimonial_success'] = "Testimonial added successfully!";
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['testimonial_error'] = "Error: " . $stmt->error;
        // If photo was uploaded, attempt to delete it on error
        if ($photo_path && file_exists(__DIR__ . '/../../../' . $photo_path)) {
            unlink(__DIR__ . '/../../../' . $photo_path);
        }
        header("Location: ../create.php");
        exit();
    }
} else {
    $_SESSION['testimonial_error'] = "Invalid request method.";
    header("Location: ../index.php");
    exit();
} 