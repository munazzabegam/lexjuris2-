<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['udupi_member_id'])) {
    $id = (int)$_POST['udupi_member_id'];
    
    if ($id <= 0) {
        $_SESSION['team_member_error'] = "Invalid member ID.";
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
            $_SESSION['team_member_error'] = "Member not found.";
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
                $photo_path = '../../' . $member['photo'];
                if (file_exists($photo_path)) {
                    unlink($photo_path);
                }
            }
            
            $_SESSION['team_member_success'] = "Udupi team member '" . htmlspecialchars($member['full_name']) . "' deleted successfully.";
        } else {
            $_SESSION['team_member_error'] = "Failed to delete Udupi team member.";
        }
        
    } catch (Exception $e) {
        $_SESSION['team_member_error'] = "Database error: " . $e->getMessage();
    }
} else {
    $_SESSION['team_member_error'] = "Invalid request.";
}

header('Location: ../index.php');
exit();
?> 