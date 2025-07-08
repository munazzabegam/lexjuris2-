<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get article ID from URL
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$article_id) {
    $_SESSION['blog_error'] = "Invalid blog ID";
    header("Location: index.php");
    exit();
}

// Fetch article details with author information
$query = "SELECT a.*, au.username as author_name 
          FROM articles a 
          LEFT JOIN admin_users au ON a.author_id = au.id 
          WHERE a.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    $_SESSION['blog_error'] = "Blog not found";
    header("Location: index.php");
    exit();
}

$blog_success = $_SESSION['blog_success'] ?? null;
unset($_SESSION['blog_success']);
$blog_error = $_SESSION['blog_error'] ?? null;
unset($_SESSION['blog_error']);

// Fetch social media links
$social_query = "SELECT platform, url FROM article_social_links WHERE article_id = ?";
$social_stmt = $conn->prepare($social_query);
$social_stmt->bind_param("i", $article_id);
$social_stmt->execute();
$social_result = $social_stmt->get_result();
$social_links = $social_result->fetch_all(MYSQLI_ASSOC);

// Fetch comments for this article
$comments_stmt = $conn->prepare("SELECT id, name, comment, created_at FROM blog_comments WHERE post_id = ? ORDER BY created_at DESC");
$comments_stmt->bind_param("i", $article_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
$comments = $comments_result->fetch_all(MYSQLI_ASSOC);
$comments_stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Blog Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .article-header {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .article-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .article-meta {
            display: flex;
            gap: 1.5rem;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .article-meta-item i {
            color: #bc841c;
        }

        .article-content {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .article-summary {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .article-body {
            font-size: 1rem;
            line-height: 1.8;
            color: #333;
        }

        .article-footer {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-link:hover {
            background: #e9ecef;
            color: #bc841c;
        }

        .social-link i {
            color: #bc841c;
        }

        .article-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 4px;
        }

        .btn-action i {
            margin-right: 0.5rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-published {
            background-color: #d4edda;
            color: #155724;
        }

        .status-draft {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-archived {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
            <?php if ($blog_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($blog_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($blog_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($blog_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Blog Details</h4>
                        <div class="article-actions">
                            <a href="edit.php?id=<?php echo $article_id; ?>" class="btn btn-primary btn-action">
                                <i class="fas fa-edit"></i>Edit Blog
                            </a>
                            <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog?');" style="display: inline;">
                                <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                                <button type="submit" class="btn btn-danger btn-action">
                                    <i class="fas fa-trash"></i>Delete Blog
                                </button>
                            </form>
                            <a href="index.php" class="btn btn-secondary btn-action">
                                <i class="fas fa-arrow-left"></i>Back to Blogs
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="article-header">
                <h1 class="blog-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span class="article-meta-item">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($article['author_name']); ?>
                    </span>
                    <span class="article-meta-item">
                        <i class="fas fa-folder"></i>
                        <?php echo ucfirst(htmlspecialchars($article['category'])); ?>
                    </span>
                    <span class="article-meta-item">
                        <i class="fas fa-calendar"></i>
                        <?php echo htmlspecialchars(date('F d, Y', strtotime($article['published_at']))); ?>
                    </span>
                    <span class="article-meta-item">
                        <i class="fas fa-clock"></i>
                        <?php echo htmlspecialchars(date('h:i A', strtotime($article['published_at']))); ?>
                    </span>
                    <span class="article-meta-item">
                        <i class="fas fa-tag"></i>
                        <span class="status-badge status-<?php echo strtolower($article['status']); ?>">
                            <?php echo ucfirst(htmlspecialchars($article['status'])); ?>
                        </span>
                    </span>
                    <?php if (!empty($article['updated_at']) && $article['updated_at'] !== '-' && $article['updated_at'] !== $article['published_at']): ?>
                        <span class="article-meta-item">
                            <i class="fas fa-edit"></i>
                            Last Updated: <?php echo htmlspecialchars(date('F d, Y h:i A', strtotime($article['updated_at']))); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($article['cover_image'])): ?>
                    <div class="mb-3 text-center">
                        <img src="../../<?php echo htmlspecialchars($article['cover_image']); ?>" alt="Cover Image" style="max-width:400px; height:auto; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                    </div>
                <?php endif; ?>
                <?php if (!empty($article['summary'])): ?>
                    <div class="article-summary">
                        <?php echo nl2br(htmlspecialchars($article['summary'])); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($article['external_link'])): ?>
                    <div class="mb-2">
                        <i class="fas fa-external-link-alt"></i>
                        <a href="<?php echo htmlspecialchars($article['external_link']); ?>" target="_blank">External Link</a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($article['tags'])): ?>
                    <div class="mb-2">
                        <i class="fas fa-tags"></i>
                        <?php foreach (explode(',', $article['tags']) as $tag): ?>
                            <span class="badge bg-secondary me-1"><?php echo htmlspecialchars(trim($tag)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="article-content">
                <div class="article-body">
                    <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                </div>
            </div>

            <?php if (!empty($social_links)): ?>
                <div class="article-footer">
                    <h5 class="mb-3">Social Media Links</h5>
                    <div class="social-links">
                        <?php foreach ($social_links as $link): ?>
                            <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="social-link">
                                <i class="fab fa-<?php echo htmlspecialchars($link['platform']); ?>"></i>
                                <?php echo ucfirst(htmlspecialchars($link['platform'])); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="article-footer mt-4">
                <h4>Comments</h4>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-start">
                            <div>
                                <strong><?php echo htmlspecialchars($comment['name']); ?></strong>
                                <span class="text-muted small ms-2"><?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?></span>
                                <div><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></div>
                            </div>
                            <form method="POST" action="actions/delete_comment.php" onsubmit="return confirm('Delete this comment?');">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                                <button type="submit" class="btn btn-sm btn-danger ms-3"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-muted">No comments yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html> 