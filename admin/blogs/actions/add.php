<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

$errors = [];

// Validate required fields
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$summary = trim($_POST['summary'] ?? '');
$content = trim($_POST['content'] ?? '');
$status = trim($_POST['status'] ?? '');
$tags = trim($_POST['tags'] ?? '');
$external_link = trim($_POST['external_link'] ?? '');
$social_links = $_POST['social_links'] ?? [];

if (empty($title)) $errors[] = "Title is required.";
if (empty($content)) $errors[] = "Content is required.";
if (empty($category)) $errors[] = "Category is required.";
if (empty($status)) $errors[] = "Status is required.";

// Validate status
$valid_statuses = ['draft', 'published'];
if (!in_array($status, $valid_statuses)) $errors[] = "Invalid status selected.";

// Validate category
$valid_categories = ['criminal', 'family', 'cheque', 'consumer', 'labour', 'high court', 'supreme court', 'other'];
if (!in_array($category, $valid_categories)) $errors[] = "Invalid category value.";

// Validate external link if provided
if (!empty($external_link) && !filter_var($external_link, FILTER_VALIDATE_URL)) {
    $errors[] = "Invalid external link URL.";
}

// Validate social media links
foreach ($social_links as $platform => $url) {
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid URL for {$platform}.";
    }
}

// Generate slug from title
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
if (empty($slug)) {
    $errors[] = "Could not generate a valid slug from the title. Please use a more descriptive title.";
}

// Check if slug is unique
$stmt = $conn->prepare("SELECT id FROM articles WHERE slug = ?");
$stmt->bind_param('s', $slug);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $errors[] = "Could not generate a unique slug. Please try a different title.";
}

// Handle cover image upload
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

// Debug: log all file upload info
error_log(print_r($_FILES, true));

// Handle video upload
$video_url = null;
if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['video/mp4'];
    $max_size = 100 * 1024 * 1024; // 100MB
    if (!in_array($_FILES['video']['type'], $allowed_types)) {
        $errors[] = "Invalid video format. Only MP4 is allowed.";
    } elseif ($_FILES['video']['size'] > $max_size) {
        $errors[] = "Video size exceeds 100MB limit.";
    } else {
        $upload_dir = __DIR__ . '/../../../uploads/articles/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid('article_video_') . '.' . $file_extension;
        $target_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['video']['tmp_name'], $target_path)) {
            $video_url = 'uploads/articles/' . $file_name;
        } else {
            $errors[] = "Failed to upload video.";
        }
    }
} else if (isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) {
    $errors[] = "Video upload error: " . $_FILES['video']['error'];
}

if (empty($errors)) {
    try {
        $conn->begin_transaction();
        $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
        // Get the next order_index
        $order_index = 1;
        $result = $conn->query("SELECT MAX(order_index) AS max_order FROM articles");
        if ($result && $row = $result->fetch_assoc()) {
            $order_index = (int)$row['max_order'] + 1;
        }
        $query = "INSERT INTO articles (title, slug, content, summary, category, status, tags, external_link, cover_image, video_url, author_id, published_at, updated_at, order_index) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssssssssssisi",
            $title,
            $slug,
            $content,
            $summary,
            $category,
            $status,
            $tags,
            $external_link,
            $cover_image,
            $video_url,
            $_SESSION['user_id'],
            $published_at,
            $order_index
        );
        $stmt->execute();
        $article_id = $conn->insert_id;
        // Insert social links
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
        // Renumber all order_index values to start from 1 and increment by 1
        $result = $conn->query("SELECT id FROM articles ORDER BY order_index ASC, id ASC");
        if ($result) {
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                $conn->query("UPDATE articles SET order_index = $i WHERE id = " . (int)$row['id']);
                $i++;
            }
        }
        $_SESSION['blog_success'] = "Blog added successfully.";
        header("Location: ../index.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $errors[] = "Error adding article: " . $e->getMessage();
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
    header("Location: ../add.php");
    exit();
} 