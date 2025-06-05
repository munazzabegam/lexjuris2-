<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $order_index = isset($_POST['order_index']) ? (int)$_POST['order_index'] : 0;
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;

    $errors = [];

    // Validation
    if (empty($question)) {
        $errors[] = "Question is required.";
    }
    if (empty($answer)) {
        $errors[] = "Answer is required.";
    }
    
    // Validate is_active value
    if ($is_active !== 0 && $is_active !== 1) {
        $errors[] = "Invalid value for status.";
    }

    if (empty($errors)) {
        // Insert FAQ into database
        $query = "INSERT INTO faq (question, answer, order_index, is_active) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        $stmt->bind_param("ssis",
            $question,
            $answer,
            $order_index,
            $is_active
        );

        if ($stmt->execute()) {
            $_SESSION['faq_success'] = "FAQ added successfully.";
        } else {
            $_SESSION['faq_error'] = "Error adding FAQ: " . $conn->error;
        }

        $stmt->close();

    } else {
        $_SESSION['faq_error'] = "Please fix the following errors: " . implode(", ", $errors);
        // Optionally store old data to repopulate the form, excluding category
        $_SESSION['old_faq_data'] = [
            'question' => $question,
            'answer' => $answer,
            'order_index' => $order_index,
            'is_active' => $is_active
        ];
    }

    header("Location: ../faqs.php");
    exit();

} else {
    // If not a POST request, redirect to FAQ page
    header("Location: ../faqs.php");
    exit();
} 