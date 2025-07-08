<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['article_id'])) {
    $comment_id = (int)$_POST['comment_id'];
    $article_id = (int)$_POST['article_id'];

    $stmt = $conn->prepare('DELETE FROM blog_comments WHERE id = ?');
    $stmt->bind_param('i', $comment_id);
    if ($stmt->execute()) {
        $_SESSION['article_success'] = 'Comment deleted successfully.';
    } else {
        $_SESSION['article_error'] = 'Failed to delete comment.';
    }
    $stmt->close();
    header('Location: ../view.php?id=' . $article_id);
    exit();
} else {
    $_SESSION['article_error'] = 'Invalid request.';
    header('Location: ../index.php');
    exit();
} 