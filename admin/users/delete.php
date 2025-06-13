<?php
require_once __DIR__ . '/../../config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

if (!$user_id) {
    $_SESSION['error_message'] = "Invalid user ID.";
    header("Location: index.php");
    exit();
}

// Prevent deleting own account
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error_message'] = "You cannot delete your own account.";
    header("Location: index.php");
    exit();
}

// Get user's profile image before deletion
$stmt = $conn->prepare("SELECT profile_image FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Delete the user
$stmt = $conn->prepare("DELETE FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Delete profile image if exists
    if ($user['profile_image'] && file_exists(__DIR__ . "/../../" . $user['profile_image'])) {
        unlink(__DIR__ . "/../../" . $user['profile_image']);
    }
    $_SESSION['success_message'] = "User deleted successfully.";
} else {
    $_SESSION['error_message'] = "Error deleting user: " . $stmt->error;
}

header("Location: index.php");
exit(); 