<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = isset($_POST['case_id']) ? (int)$_POST['case_id'] : 0;

    if (!$case_id) {
        $_SESSION['case_error'] = "Invalid case ID";
        header("Location: ../cases.php");
        exit();
    }

    try {
        $conn->begin_transaction();

        // Note: Cases table currently does not have related tables like social links or images,
        // so direct deletion from the 'cases' table is sufficient for now.

        // Delete case
        $delete_query = "DELETE FROM cases WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $case_id);
        $delete_stmt->execute();

        if ($delete_stmt->affected_rows === 0) {
            throw new Exception("Case not found");
        }

        $conn->commit();
        $_SESSION['case_success'] = "Case deleted successfully";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['case_error'] = "An error occurred while deleting the case: " . $e->getMessage();
    }

    header("Location: ../cases.php");
    exit();
} else {
    header("Location: ../cases.php");
    exit();
} 