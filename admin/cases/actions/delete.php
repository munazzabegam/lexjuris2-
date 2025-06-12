<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = isset($_POST['case_id']) ? (int)$_POST['case_id'] : 0;

    if (!$case_id) {
        $_SESSION['case_error'] = "Invalid case ID";
        header("Location: ../index.php");
        exit();
    }

    try {
        $conn->begin_transaction();

        // First check if the case exists
        $check_query = "SELECT id FROM cases WHERE id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $case_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Case not found");
        }

        // Delete case
        $delete_query = "DELETE FROM cases WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $case_id);
        
        if (!$delete_stmt->execute()) {
            throw new Exception("Failed to delete case: " . $delete_stmt->error);
        }

        if ($delete_stmt->affected_rows === 0) {
            throw new Exception("No case was deleted");
        }

        $conn->commit();
        $_SESSION['case_success'] = "Case deleted successfully";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['case_error'] = "Error deleting case: " . $e->getMessage();
    }

    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?> 