<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['social_link_id'])) {
    $social_link_id = (int)$_POST['social_link_id'];
    
    $stmt = $conn->prepare("DELETE FROM social_links WHERE id = ?");
    $stmt->bind_param("i", $social_link_id);
    
    if ($stmt->execute()) {
        $_SESSION['social_link_success'] = "Social link deleted successfully.";
    } else {
        $_SESSION['social_link_error'] = "Error deleting social link: " . $conn->error;
    }
} else {
    $_SESSION['social_link_error'] = "Invalid request.";
}

header("Location: ../index.php");
exit();
?> 