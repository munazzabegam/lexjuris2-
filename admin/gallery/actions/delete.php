<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gallery_id'])) {
    $gallery_id = (int)$_POST['gallery_id'];
    
    // Fetch image path before deleting the record
    $stmt = $conn->prepare("SELECT image FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gallery_image = $result->fetch_assoc();
    
    if ($gallery_image && $gallery_image['image']) {
        $image_path = __DIR__ . '/../../../' . $gallery_image['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    
    if ($stmt->execute()) {
        $_SESSION['gallery_success'] = "Gallery image deleted successfully.";
    } else {
        $_SESSION['gallery_error'] = "Error deleting gallery image: " . $conn->error;
    }
} else {
    $_SESSION['gallery_error'] = "Invalid request.";
}

header("Location: ../index.php");
exit();
?> 