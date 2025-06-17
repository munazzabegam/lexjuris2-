<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM achievements WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['achievement_success'] = "Achievement deleted successfully!";
    } else {
        $_SESSION['achievement_error'] = "Error deleting achievement: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    header("Location: ../index.php");
    exit();
} else {
    $_SESSION['achievement_error'] = "Achievement ID not provided.";
    header("Location: ../index.php");
    exit();
} 