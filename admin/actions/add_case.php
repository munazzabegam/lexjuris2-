<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $case_number = trim($_POST['case_number']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $status = trim($_POST['status']);
    $link = !empty($_POST['link']) ? trim($_POST['link']) : null;
    $tags = !empty($_POST['tags']) ? trim($_POST['tags']) : null;
    $author_id = $_SESSION['admin_id'];

    // Validate required fields
    if (empty($case_number) || empty($title) || empty($description) || empty($category) || empty($status)) {
        $_SESSION['case_error'] = "Please fill in all required fields.";
        header("Location: ../cases.php");
        exit();
    }

    // Validate case number length
    if (strlen($case_number) > 50) {
        $_SESSION['case_error'] = "Case number must not exceed 50 characters.";
        header("Location: ../cases.php");
        exit();
    }

    // Validate title length
    if (strlen($title) > 255) {
        $_SESSION['case_error'] = "Title must not exceed 255 characters.";
        header("Location: ../cases.php");
        exit();
    }

    // Validate status enum
    $valid_statuses = ['Open', 'Closed', 'In Progress'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['case_error'] = "Invalid status value.";
        header("Location: ../cases.php");
        exit();
    }

    // Validate category enum
    $valid_categories = ['criminal', 'family', 'cheque', 'consumer', 'high court', 'supreme court', 'labour', 'other'];
    if (!in_array($category, $valid_categories)) {
        $_SESSION['case_error'] = "Invalid category value.";
        header("Location: ../cases.php");
        exit();
    }

    // Check if case number already exists
    $check_query = "SELECT id FROM cases WHERE case_number = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $case_number);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['case_error'] = "Case number already exists.";
        header("Location: ../cases.php");
        exit();
    }

    // Insert new case
    $query = "INSERT INTO cases (case_number, title, description, category, status, link, tags, author_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", 
        $case_number, 
        $title, 
        $description, 
        $category, 
        $status, 
        $link, 
        $tags, 
        $author_id
    );

    if ($stmt->execute()) {
        $_SESSION['case_success'] = "Case added successfully.";
    } else {
        $_SESSION['case_error'] = "Error adding case: " . $conn->error;
    }

    $stmt->close();
    header("Location: ../cases.php");
    exit();
} else {
    header("Location: ../cases.php");
    exit();
} 