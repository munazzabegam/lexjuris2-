<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $contact_id = $_POST['contact_id'] ?? 0; // Hidden input from edit.php or derived

    if ($contact_id == 0) {
        // If no ID is provided, try to find or create the single entry
        $result = $conn->query("SELECT id FROM contact LIMIT 1");
        if ($result->num_rows > 0) {
            $contact_id = $result->fetch_assoc()['id'];
        } else {
            // Create a new entry if none exists
            $conn->query("INSERT INTO contact (phone, is_active) VALUES (?, ?)");
            $stmt = $conn->prepare("INSERT INTO contact (phone, is_active) VALUES (?, ?)");
            $stmt->bind_param("si", $phone, $is_active);
            $stmt->execute();
            $contact_id = $conn->insert_id;
        }
    }

    $stmt = $conn->prepare("UPDATE contact SET phone = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("sii", $phone, $is_active, $contact_id);

    if ($stmt->execute()) {
        $_SESSION['contact_success'] = "Contact information updated successfully.";
    } else {
        $_SESSION['contact_error'] = "Error updating contact information: " . $conn->error;
    }
} else {
    $_SESSION['contact_error'] = "Invalid request.";
}

header("Location: ../index.php");
exit();
?> 