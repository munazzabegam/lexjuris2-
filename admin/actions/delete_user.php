<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    
    // Prevent self-deletion
    if ($user_id == $_SESSION['admin_id']) {
        $_SESSION['user_error'] = "You cannot delete your own account.";
        header("Location: ../users.php");
        exit();
    }

    // Delete user
    $delete_query = "DELETE FROM admin_users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $user_id);

    if ($delete_stmt->execute()) {
        $_SESSION['user_success'] = "User deleted successfully.";
    } else {
        $_SESSION['user_error'] = "Error deleting user: " . $conn->error;
    }
} else {
    $_SESSION['user_error'] = "Invalid request.";
}

header("Location: ../users.php");
exit(); 