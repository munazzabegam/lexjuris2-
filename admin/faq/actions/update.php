<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faq_id = (int)$_POST['faq_id'];
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validate input
    if (empty($question) || empty($answer)) {
        $_SESSION['faq_error'] = "Question and answer are required.";
        header("Location: ../edit.php?id=" . $faq_id);
        exit();
    }
    
    try {
        // Begin transaction
        $conn->begin_transaction();
        
        // Update the FAQ
        $stmt = $conn->prepare("UPDATE faq SET question = ?, answer = ?, is_active = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssii", $question, $answer, $is_active, $faq_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            // Commit transaction
            $conn->commit();
            $_SESSION['faq_success'] = "FAQ updated successfully.";
        } else {
            throw new Exception("No changes were made or FAQ not found.");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['faq_error'] = "Error updating FAQ: " . $e->getMessage();
    }
} else {
    $_SESSION['faq_error'] = "Invalid request.";
}

// Redirect back to FAQ list
header("Location: ../index.php");
exit();
?> 