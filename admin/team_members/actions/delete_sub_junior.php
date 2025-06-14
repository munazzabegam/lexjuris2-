<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

$sub_junior_member_id = isset($_POST['sub_junior_member_id']) ? (int)$_POST['sub_junior_member_id'] : 0;

if (!$sub_junior_member_id) {
    $_SESSION['sub_junior_member_error'] = "Invalid sub junior team member ID.";
    header("Location: ../index.php");
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Get the photo path before deleting the record
    $stmt = $conn->prepare("SELECT photo FROM sub_junior_team_members WHERE id = ?");
    $stmt->bind_param("i", $sub_junior_member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sub_junior_member = $result->fetch_assoc();

    // Delete the sub junior team member
    $stmt = $conn->prepare("DELETE FROM sub_junior_team_members WHERE id = ?");
    $stmt->bind_param("i", $sub_junior_member_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Delete the photo file if it exists
        if ($sub_junior_member && $sub_junior_member['photo'] && file_exists(__DIR__ . '/../../../' . $sub_junior_member['photo'])) {
            unlink(__DIR__ . '/../../../' . $sub_junior_member['photo']);
        }

        $conn->commit();
        $_SESSION['sub_junior_member_success'] = "Sub Junior Team member deleted successfully.";
    } else {
        throw new Exception("No sub junior team member found with the specified ID.");
    }
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['sub_junior_member_error'] = "Error deleting sub junior team member: " . $e->getMessage();
}

header("Location: ../index.php");
exit();
?> 