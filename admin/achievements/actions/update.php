<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $number_value = $_POST['number_value'] ?? '';
    $label = $_POST['label'] ?? '';

    if (empty($id) || empty($number_value) || empty($label)) {
        $_SESSION['achievement_error'] = "All fields are required.";
        header("Location: ../edit.php?id=" . $id);
        exit();
    }

    $query = "UPDATE achievements SET number_value = ?, label = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $number_value, $label, $id);

    if ($stmt->execute()) {
        $_SESSION['achievement_success'] = "Achievement updated successfully!";
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['achievement_error'] = "Error updating achievement: " . $stmt->error;
        header("Location: ../edit.php?id=" . $id);
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
    exit();
} 