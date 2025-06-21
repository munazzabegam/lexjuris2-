<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        $_SESSION['contact_error'] = "Invalid request. No ID provided.";
        header("Location: ../index.php");
        exit();
    }

    $id = $_POST['id'];
    $phone = $_POST['phone'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (empty($phone)) {
        $_SESSION['contact_error'] = "Phone number is required.";
        // Redirect back to the edit form with the specific id
        header("Location: ../edit.php?id=" . $id);
        exit();
    }

    $stmt = $conn->prepare("UPDATE contact SET phone = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("sii", $phone, $is_active, $id);

    if ($stmt->execute()) {
        $_SESSION['contact_success'] = "Contact number updated successfully.";
    } else {
        $_SESSION['contact_error'] = "Error updating contact number: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['contact_error'] = "Invalid request method.";
}

header("Location: ../index.php");
exit();
?> 