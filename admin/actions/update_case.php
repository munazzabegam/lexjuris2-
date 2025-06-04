<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $case_id = (int)$_POST['case_id'];
    $case_number = trim($_POST['case_number']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $status = trim($_POST['status']);
    $link = !empty($_POST['link']) ? trim($_POST['link']) : null;
    $tags = !empty($_POST['tags']) ? trim($_POST['tags']) : null;

    // Validate required fields
    if (empty($case_number) || empty($title) || empty($description) || empty($category) || empty($status)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: ../edit_case.php?id=" . $case_id);
        exit();
    }

    // Validate case number length
    if (strlen($case_number) > 50) {
        $_SESSION['error'] = "Case number must not exceed 50 characters.";
        header("Location: ../edit_case.php?id=" . $case_id);
        exit();
    }

    // Validate title length
    if (strlen($title) > 255) {
        $_SESSION['error'] = "Title must not exceed 255 characters.";
        header("Location: ../edit_case.php?id=" . $case_id);
        exit();
    }

    // Validate status enum
    $valid_statuses = ['Open', 'Closed', 'In Progress'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status value.";
        header("Location: ../edit_case.php?id=" . $case_id);
        exit();
    }

    // Validate category enum
    $valid_categories = ['criminal', 'family', 'cheque', 'consumer', 'high court', 'supreme court'];
    if (!in_array($category, $valid_categories)) {
        $_SESSION['error'] = "Invalid category value.";
        header("Location: ../edit_case.php?id=" . $case_id);
        exit();
    }

    // Check if case number already exists (excluding current case)
    $check_query = "SELECT id FROM cases WHERE case_number = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("si", $case_number, $case_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Case number already exists.";
        header("Location: ../edit_case.php?id=" . $case_id);
        exit();
    }

    // Update case
    $query = "UPDATE cases SET 
              case_number = ?, 
              title = ?, 
              description = ?, 
              category = ?, 
              status = ?, 
              link = ?, 
              tags = ? 
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", 
        $case_number, 
        $title, 
        $description, 
        $category, 
        $status, 
        $link, 
        $tags, 
        $case_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Case updated successfully.";
        header("Location: ../case_details.php?id=" . $case_id);
    } else {
        $_SESSION['error'] = "Error updating case: " . $conn->error;
        header("Location: ../edit_case.php?id=" . $case_id);
    }

    $stmt->close();
    exit();
} else {
    header("Location: ../cases.php");
    exit();
} 