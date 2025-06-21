<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM contact WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['contact_success'] = "Contact number deleted successfully.";
        } else {
            $_SESSION['contact_error'] = "Contact number not found or already deleted.";
        }
    } else {
        $_SESSION['contact_error'] = "Error deleting contact number: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['contact_error'] = "Invalid request. No ID provided.";
}

header("Location: ../index.php");
exit(); 