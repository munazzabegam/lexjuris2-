<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $case_number = trim($_POST['case_number']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $status = trim($_POST['status']);
    $link = !empty($_POST['link']) ? trim($_POST['link']) : null;
    $tags = !empty($_POST['tags']) ? trim($_POST['tags']) : null;

    // Validate required fields
    if (empty($case_number) || empty($title) || empty($description) || empty($category) || empty($status)) {
        $_SESSION['case_error'] = "Please fill in all required fields.";
        header("Location: ../add.php");
        exit();
    }

    // Validate case number length
    if (strlen($case_number) > 50) {
        $_SESSION['case_error'] = "Case number must not exceed 50 characters.";
        header("Location: ../add.php");
        exit();
    }

    // Validate title length
    if (strlen($title) > 255) {
        $_SESSION['case_error'] = "Title must not exceed 255 characters.";
        header("Location: ../add.php");
        exit();
    }

    // Validate status enum
    $valid_statuses = ['Open', 'Closed', 'In Progress'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['case_error'] = "Invalid status value.";
        header("Location: ../add.php");
        exit();
    }

    // Validate category enum
    $valid_categories = ['criminal', 'family', 'cheque', 'consumer', 'labour', 'high court', 'supreme court', 'other'];
    if (!in_array($category, $valid_categories)) {
        $_SESSION['case_error'] = "Invalid category value.";
        header("Location: ../add.php");
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
        header("Location: ../add.php");
        exit();
    }

    try {
        $conn->begin_transaction();

        // Insert new case
        $query = "INSERT INTO cases (
            case_number, 
            title, 
            description, 
            category, 
            status, 
            link, 
            tags,
            author_id,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", 
            $case_number, 
            $title, 
            $description, 
            $category, 
            $status, 
            $link, 
            $tags,
            $_SESSION['user_id']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error creating case: " . $stmt->error);
        }

        $new_case_id = $conn->insert_id;
        $conn->commit();
        
        $_SESSION['case_success'] = "Case created successfully.";
        header("Location: ../view.php?id=" . $new_case_id);
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['case_error'] = "Error creating case: " . $e->getMessage();
        header("Location: ../add.php");
    }

    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?> 