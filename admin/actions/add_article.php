<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Function to generate a URL-friendly slug
function create_slug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]+/', '-', $string); // Replace non-alphanumeric with dashes
    $string = trim($string, '-'); // Trim dashes from beginning and end
    $string = preg_replace('/-+/', '-', $string); // Replace multiple dashes with a single dash
    return $string;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $external_link = trim($_POST['external_link'] ?? '');
    $author_id = $_SESSION['admin_id'];
    $social_links = $_POST['social_links'] ?? [];

    // Store old data in session to repopulate form on error
    $_SESSION['old_data'] = $_POST;

    $_SESSION['article_errors'] = []; // Use specific key for article errors

    // Validation
    if (empty($title)) {
        $_SESSION['article_errors'][] = "Title is required.";
    }
    if (empty($content)) {
        $_SESSION['article_errors'][] = "Content is required.";
    }
    if (empty($category)) {
        $_SESSION['article_errors'][] = "Category is required.";
    }
    if (empty($status)) {
        $_SESSION['article_errors'][] = "Status is required.";
    }
    
    // Validate status
    $valid_statuses = ['draft', 'published'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['article_errors'][] = "Invalid status selected";
    }

    // Validate category enum
    $valid_categories = ['criminal', 'family', 'cheque', 'consumer', 'labour', 'high court', 'supreme court', 'other'];
    if (!in_array($category, $valid_categories)) {
        $_SESSION['article_errors'][] = "Invalid category value.";
    }

    // Generate and check slug
    $slug = create_slug($title);
    if (empty($slug)) {
         $_SESSION['article_errors'][] = "Could not generate a valid slug from the title. Please use a more descriptive title.";
    } else {
        $check_slug_query = "SELECT id FROM articles WHERE slug = ?";
        $check_slug_stmt = $conn->prepare($check_slug_query);
        $check_slug_stmt->bind_param("s", $slug);
        $check_slug_stmt->execute();
        $check_slug_result = $check_slug_stmt->get_result();
        if ($check_slug_result->num_rows > 0) {
            // Append a unique identifier if slug already exists (e.g., timestamp or random string)
            $slug = $slug . '-' . time(); // Simple timestamp for uniqueness
             $check_slug_stmt->close();
             // Re-check uniqueness with new slug (optional, but safer for high traffic)
             $check_slug_stmt = $conn->prepare("SELECT id FROM articles WHERE slug = ?");
             $check_slug_stmt->bind_param("s", $slug);
             $check_slug_stmt->execute();
             $check_slug_result = $check_slug_stmt->get_result();
             if ($check_slug_result->num_rows > 0) {
                 $_SESSION['article_errors'][] = "Could not generate a unique slug. Please try a different title.";
             }
        }
         $check_slug_stmt->close();
    }

    // Handle cover image upload (optional)
    $cover_image_path = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../uploads/articles/'; // Specify your upload directory
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
        $new_file_name = $slug . '.' . $file_extension; // Use slug for filename
        $target_file = $upload_dir . $new_file_name;
        $imageFileType = strtolower($file_extension);

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['cover_image']['tmp_name']);
        if($check !== false) {
             // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $_SESSION['article_errors'][] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for cover image.";
            }
             // Check file size (e.g., 5MB max)
            if ($_FILES['cover_image']['size'] > 5000000) {
                $_SESSION['article_errors'][] = "Sorry, your file is too large (max 5MB).";
            }

            // Try to upload file
            if (empty($_SESSION['article_errors'])) {
                 if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                    $cover_image_path = '../uploads/articles/' . $new_file_name; // Path to store in DB
                } else {
                    $_SESSION['article_errors'][] = "Sorry, there was an error uploading your cover image.";
                }
            }
        } else {
            $_SESSION['article_errors'][] = "File is not an image.";
        }
    }

    // If there are errors, redirect back with errors and old data
    if (!empty($_SESSION['article_errors'])) {
        // Errors are already in $_SESSION['article_errors']
        header("Location: ../add_article.php");
        exit();
    }

    // Insert article into database
    $query = "INSERT INTO articles (title, slug, summary, content, category, status, tags, external_link, author_id, cover_image, published_at, updated_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    // Set published_at based on status
    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
    $updated_at = date('Y-m-d H:i:s'); // Always set updated_at to current time

    $stmt->bind_param("ssssssssisss", 
        $title,
        $slug,
        $summary,
        $content,
        $category,
        $status,
        $tags,
        $external_link,
        $author_id,
        $cover_image_path,
        $published_at,
        $updated_at
    );

    if ($stmt->execute()) {
        $article_id = $conn->insert_id; // Get the ID of the newly inserted article

        // Insert social media links if any
        if (!empty($social_links)) {
            $social_query = "INSERT INTO article_social_links (article_id, platform, url) VALUES (?, ?, ?)";
            $social_stmt = $conn->prepare($social_query);

            foreach ($social_links as $platform => $url) {
                if (!empty($url)) {
                    // Validate URL format
                    if (filter_var($url, FILTER_VALIDATE_URL)) {
                        $social_stmt->bind_param("iss", $article_id, $platform, $url);
                        $social_stmt->execute();
                    } else {
                        $_SESSION['article_errors'][] = "Invalid URL format for $platform link.";
                    }
                }
            }
            $social_stmt->close();
        }

        $_SESSION['article_success'] = "Article added successfully.";
        // Clear old data after successful submission
        unset($_SESSION['old_data']);
    } else {
        $_SESSION['article_errors'][] = "Error adding article: " . $conn->error;
        // Keep old data to repopulate form
    }

    $stmt->close();

    header("Location: ../articles.php");
    exit();

} else {
    // If not a POST request, redirect to add article page
    header("Location: ../add_article.php");
    exit();
} 