<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['testimonial_id'])) {
    $testimonial_id = $_POST['testimonial_id'];

    // Fetch photo path before deleting the testimonial record
    $stmt = $conn->prepare("SELECT photo FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $testimonial_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $testimonial = $result->fetch_assoc();
    $photo_path = $testimonial['photo'] ?? null;
    $stmt->close();

    // Delete testimonial from database
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $testimonial_id);

    if ($stmt->execute()) {
        // If there was a photo, delete the file from the server
        if ($photo_path && file_exists(__DIR__ . '/../../../' . $photo_path)) {
            unlink(__DIR__ . '/../../../' . $photo_path);
        }
        $_SESSION['testimonial_success'] = "Testimonial deleted successfully!";
    } else {
        $_SESSION['testimonial_error'] = "Error deleting testimonial: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['testimonial_error'] = "Invalid request.";
}

header("Location: ../index.php");
exit(); 