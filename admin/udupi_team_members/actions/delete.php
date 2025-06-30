<?php
session_start();
require_once '../../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if ($id <= 0) {
        $_SESSION['error_message'] = "Invalid member ID.";
        header('Location: ../index.php');
        exit();
    }

    try {
        // Get member data before deletion
        $query = "SELECT photo, full_name FROM udupi_team_members WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error_message'] = "Member not found.";
            header('Location: ../index.php');
            exit();
        }
        
        $member = $result->fetch_assoc();
        
        // Delete from database
        $delete_query = "DELETE FROM udupi_team_members WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // Delete photo file if it exists
            if (!empty($member['photo'])) {
                $photo_path = '../../../' . $member['photo'];
                if (file_exists($photo_path)) {
                    unlink($photo_path);
                }
            }
            
            $_SESSION['success_message'] = "Udupi team member '" . htmlspecialchars($member['full_name']) . "' deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete team member.";
        }
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

header('Location: ../index.php');
exit();
?> 