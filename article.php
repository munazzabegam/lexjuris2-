<?php
$page_title = "Article - LexJuris";
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
$page_title = $article['title'] . " - LexJuris";

// Handle comment submission
$comment_success = $comment_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_article_id'])) {
    $comment_article_id = (int)$_POST['comment_article_id'];
    $comment_name = trim($_POST['comment_name'] ?? '');
    $comment_text = trim($_POST['comment_text'] ?? '');
    if ($comment_name && $comment_text && $comment_article_id === (int)$article['id']) {
        $stmt = $conn->prepare("INSERT INTO blog_comments (post_id, name, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $comment_article_id, $comment_name, $comment_text);
        if ($stmt->execute()) {
            $comment_success = "Comment added successfully.";
        } else {
            $comment_error = "Failed to add comment. Please try again.";
        }
        $stmt->close();
    } else {
        $comment_error = "Name and comment are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Legal Article - Expert legal insights and analysis from LexJuris Law Chamber, the best advocates in Mangalore, Karnataka. Comprehensive legal knowledge and case studies.">
    <meta name="keywords" content="legal article, legal analysis, case study, legal insights, advocate article, legal knowledge, law analysis, legal expertise">
    <title><?php echo $page_title; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">
    <link rel="manifest" href="assets/images/site.webmanifest">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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

                        <!-- Comments Section -->
                        <div class="mt-5">
                            <h3 class="mb-4">Comments</h3>
                            <?php
                            $comments_stmt = $conn->prepare("SELECT name, comment, created_at FROM blog_comments WHERE post_id = ? ORDER BY created_at DESC");
                            $comments_stmt->bind_param("i", $article['id']);
                            $comments_stmt->execute();
                            $comments_result = $comments_stmt->get_result();
                            if ($comments_result->num_rows > 0):
                                while ($comment = $comments_result->fetch_assoc()): ?>
                                    <div class="border rounded p-2 mb-2">
                                        <strong><?php echo htmlspecialchars($comment['name']); ?></strong>
                                        <span class="text-muted small ms-2"><?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?></span>
                                        <div><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></div>
                                    </div>
                                <?php endwhile;
                            else:
                                echo '<div class="text-muted">No comments yet.</div>';
                            endif;
                            $comments_stmt->close();
                            ?>
                            <!-- Leave a Comment Form -->
                            <div class="card mt-4 mb-2">
                                <div class="card-body">
                                    <h5 class="mb-3">Leave a Comment</h5>
                                    <?php if ($comment_success): ?>
                                        <div class="alert alert-success">Comment added successfully.</div>
                                    <?php elseif ($comment_error): ?>
                                        <div class="alert alert-danger"><?php echo htmlspecialchars($comment_error); ?></div>
                                    <?php endif; ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="comment_article_id" value="<?php echo $article['id']; ?>">
                                        <div class="mb-2">
                                            <label for="commentName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="commentName" name="comment_name" required>
                                        </div>
                                        <div class="mb-2">
                                            <label for="commentText" class="form-label">Comment</label>
                                            <textarea class="form-control" id="commentText" name="comment_text" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-warning">Submit Comment</button>
                                    </form>
                                </div>
                            </div>
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