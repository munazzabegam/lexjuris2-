<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (empty($phone)) {
        $_SESSION['contact_error'] = "Phone number is required.";
        header("Location: ../create.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO contact (phone, is_active) VALUES (?, ?)");
    $stmt->bind_param("si", $phone, $is_active);

    if ($stmt->execute()) {
        $_SESSION['contact_success'] = "Contact number added successfully.";
    } else {
        $_SESSION['contact_error'] = "Error adding contact number: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['contact_error'] = "Invalid request method.";
}

header("Location: ../index.php");
exit(); 