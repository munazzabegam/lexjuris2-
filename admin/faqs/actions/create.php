<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validate input
    if (empty($question) || empty($answer)) {
        $_SESSION['faq_error'] = "Question and answer are required.";
        header("Location: ../create.php");
        exit();
    }
    
    try {
        // Begin transaction
        $conn->begin_transaction();
        
        // Get the maximum order_index
        $result = $conn->query("SELECT MAX(order_index) as max_order FROM faqs");
        $row = $result->fetch_assoc();
        $order_index = ($row['max_order'] ?? 0) + 1;
        
        // Insert the new FAQ
        $stmt = $conn->prepare("INSERT INTO faqs (question, answer, is_active, order_index, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssii", $question, $answer, $is_active, $order_index);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            // Commit transaction
            $conn->commit();
            $_SESSION['faq_success'] = "FAQ created successfully.";
        } else {
            throw new Exception("Failed to create FAQ.");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['faq_error'] = "Error creating FAQ: " . $e->getMessage();
    }
} else {
    $_SESSION['faq_error'] = "Invalid request.";
}

// Redirect back to FAQs list
header("Location: ../index.php");
exit();
?> 