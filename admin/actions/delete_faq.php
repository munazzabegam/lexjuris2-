<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faq_id'])) {
    $faq_id = (int)$_POST['faq_id'];
    
    // Delete the FAQ
    $query = "DELETE FROM faq WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $faq_id);
    
    if ($stmt->execute()) {
        $_SESSION['faq_success'] = "FAQ deleted successfully.";
    } else {
        $_SESSION['faq_error'] = "Error deleting FAQ: " . $conn->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['faq_error'] = "Invalid request.";
}

header("Location: ../faqs.php");
exit(); 