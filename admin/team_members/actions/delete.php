<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team_member_id'])) {
    $team_member_id = (int)$_POST['team_member_id'];
    
    // Fetch photo path before deleting the record
    $stmt = $conn->prepare("SELECT photo FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $team_member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $team_member = $result->fetch_assoc();
    
    if ($team_member && $team_member['photo']) {
        $photo_path = __DIR__ . '/../../../' . $team_member['photo'];
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }

    $stmt = $conn->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $team_member_id);
    
    if ($stmt->execute()) {
        $_SESSION['team_member_success'] = "Team member deleted successfully.";
    } else {
        $_SESSION['team_member_error'] = "Error deleting team member: " . $conn->error;
    }
} else {
    $_SESSION['team_member_error'] = "Invalid request.";
}

header("Location: ../index.php");
exit();
?> 