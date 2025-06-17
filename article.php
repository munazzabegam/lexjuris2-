<?php
$page_title = "Article - Lawyex";
$current_page = "blog";

require_once 'config/database.php';

// Get article slug from URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header("Location: blog.php");
    exit();
}

// Fetch article details
$query = "SELECT a.*, u.username as author_name 
          FROM articles a 
          LEFT JOIN admin_users u ON a.author_id = u.id 
          WHERE a.slug = ? AND a.status = 'published'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    header("Location: blog.php");
    exit();
}

// Update page title
$page_title = $article['title'] . " - Lawyex";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Article Section -->
    <section class="article-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <article class="blog-post">
                        <h1 class="mb-4"><?php echo htmlspecialchars($article['title']); ?></h1>
                        
                        <div class="article-meta mb-4">
                            <span><i class="far fa-calendar-alt me-2"></i><?php echo date('F j, Y', strtotime($article['published_at'])); ?></span>
                            <span class="ms-3"><i class="far fa-user me-2"></i><?php echo htmlspecialchars($article['author_name']); ?></span>
                            <span class="ms-3"><i class="far fa-folder me-2"></i><?php echo htmlspecialchars($article['category']); ?></span>
                        </div>

                        <?php if (!empty($article['video_url'])): ?>
                            <div class="ratio ratio-16x9 mb-4">
                                <video controls class="rounded">
                                    <source src="<?php echo htmlspecialchars($article['video_url']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        <?php elseif (!empty($article['cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars($article['cover_image']); ?>" class="img-fluid rounded mb-4" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        <?php endif; ?>

                        <div class="article-content">
                            <?php echo $article['content']; ?>
                        </div>

                        <?php if (!empty($article['tags'])): ?>
                            <div class="article-tags mt-4">
                                <h5>Tags:</h5>
                                <?php
                                $tags = explode(',', $article['tags']);
                                foreach ($tags as $tag) {
                                    $tag = trim($tag);
                                    if (!empty($tag)) {
                                        echo '<a href="blog.php?tag=' . urlencode($tag) . '" class="btn btn-sm btn-outline-warning me-2 mb-2">' . htmlspecialchars($tag) . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </article>

                    <div class="mt-4">
                        <a href="blog.php" class="btn btn-warning">
                            <i class="fas fa-arrow-left me-2"></i>Back to Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html> 