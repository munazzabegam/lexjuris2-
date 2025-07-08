<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;

    if (!$article_id) {
        $_SESSION['article_error'] = "Invalid article ID";
        header("Location: ../index.php");
        exit();
    }

    try {
        $conn->begin_transaction();

        // Get article details to delete cover image if exists
        $query = "SELECT cover_image FROM articles WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();

        // Delete social media links
        $delete_social_query = "DELETE FROM article_social_links WHERE article_id = ?";
        $delete_social_stmt = $conn->prepare($delete_social_query);
        $delete_social_stmt->bind_param("i", $article_id);
        $delete_social_stmt->execute();

        // Delete article
        $delete_query = "DELETE FROM articles WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $article_id);
        $delete_stmt->execute();

        if ($delete_stmt->affected_rows === 0) {
            throw new Exception("Article not found");
        }

        // Delete cover image if exists
        if (!empty($article['cover_image'])) {
            $image_path = __DIR__ . '/../../../' . $article['cover_image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $conn->commit();
        $_SESSION['article_success'] = "Article deleted successfully";
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Article deletion error: " . $e->getMessage());
        $_SESSION['article_error'] = "An error occurred while deleting the article. Please try again. (" . $e->getMessage() . ")";
    }

    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
} 