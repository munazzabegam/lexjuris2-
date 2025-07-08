<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $external_link = trim($_POST['external_link'] ?? '');
    $social_links = $_POST['social_links'] ?? [];

    $errors = [];

    // Validate required fields
    if (empty($title)) $errors[] = "Title is required";
    if (empty($category)) $errors[] = "Category is required";
    if (empty($content)) $errors[] = "Content is required";
    if (empty($status)) $errors[] = "Status is required";

    // Validate category
    $valid_categories = ['criminal', 'family', 'cheque', 'consumer', 'labour', 'high court', 'supreme court', 'other'];
    if (!in_array($category, $valid_categories)) $errors[] = "Invalid category selected";

    // Validate status
    $valid_statuses = ['draft', 'published'];
    if (!in_array($status, $valid_statuses)) $errors[] = "Invalid status selected";

    // Validate external link if provided
    if (!empty($external_link) && !filter_var($external_link, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid external link URL";
    }

    // Validate social media links
    foreach ($social_links as $platform => $url) {
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            $errors[] = "Invalid URL for {$platform}";
        }
    }

    // Handle cover image upload if provided
    $cover_image = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['cover_image']['type'], $allowed_types)) {
            $errors[] = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
        } elseif ($_FILES['cover_image']['size'] > $max_size) {
            $errors[] = "Image size exceeds 5MB limit.";
        } else {
            $upload_dir = __DIR__ . '/../../../uploads/articles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('article_') . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_path)) {
                $cover_image = 'uploads/articles/' . $file_name;
            } else {
                $errors[] = "Failed to upload cover image.";
            }
        }
    }

    if (empty($errors)) {
        try {
            $conn->begin_transaction();

            // Update article
            $query = "UPDATE articles SET 
                     title = ?, 
                     category = ?, 
                     summary = ?, 
                     content = ?, 
                     status = ?, 
                     tags = ?, 
                     external_link = ?,
                     updated_at = ?";
            
            $params = [$title, $category, $summary, $content, $status, $tags, $external_link, date('Y-m-d H:i:s')];
            $types = "ssssssss";

            // Handle published_at based on status change
            if ($status === 'published') {
                // Check if article was previously unpublished
                $check_query = "SELECT published_at FROM articles WHERE id = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("i", $article_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $article = $check_result->fetch_assoc();
                
                if (!$article['published_at']) {
                    $query .= ", published_at = ?";
                    $params[] = date('Y-m-d H:i:s');
                    $types .= "s";
                }
            }

            if ($cover_image) {
                $query .= ", cover_image = ?";
                $params[] = $cover_image;
                $types .= "s";
            }

            $query .= " WHERE id = ?";
            $params[] = $article_id;
            $types .= "i";

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            // Update social media links
            // First, delete existing links
            $delete_query = "DELETE FROM article_social_links WHERE article_id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("i", $article_id);
            $delete_stmt->execute();

            // Then insert new links
            if (!empty($social_links)) {
                $insert_query = "INSERT INTO article_social_links (article_id, platform, url) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);

                foreach ($social_links as $platform => $url) {
                    if (!empty($url)) {
                        $insert_stmt->bind_param("iss", $article_id, $platform, $url);
                        $insert_stmt->execute();
                    }
                }
            }

            $conn->commit();
            $_SESSION['article_success'] = "Article updated successfully";
            header("Location: ../view.php?id=" . $article_id);
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "An error occurred while updating the article: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $_SESSION['article_errors'] = $errors;
        $_SESSION['old_data'] = [
            'title' => $title,
            'category' => $category,
            'summary' => $summary,
            'content' => $content,
            'status' => $status,
            'tags' => $tags,
            'external_link' => $external_link,
            'social_links' => $social_links
        ];
        header("Location: ../edit.php?id=" . $article_id);
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
} 