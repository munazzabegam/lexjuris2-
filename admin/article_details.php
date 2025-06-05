<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Get article ID from URL
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$article_id) {
    header("Location: articles.php");
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
    header("Location: articles.php");
    exit();
}

// Fetch social media links
$social_query = "SELECT platform, url FROM article_social_links WHERE article_id = ?";
$social_stmt = $conn->prepare($social_query);
$social_stmt->bind_param("i", $article_id);
$social_stmt->execute();
$social_result = $social_stmt->get_result();
$social_links = $social_result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Article Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
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
    </style>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    <?php include 'components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Article Details</h4>
                        <div class="article-actions">
                            <a href="edit_article.php?id=<?php echo $article_id; ?>" class="btn btn-primary btn-action">
                                <i class="fas fa-edit"></i>Edit Article
                            </a>
                            <form action="actions/delete_article.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');" style="display: inline;">
                                <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                                <button type="submit" class="btn btn-danger btn-action">
                                    <i class="fas fa-trash"></i>Delete Article
                                </button>
                            </form>
                            <a href="articles.php" class="btn btn-secondary btn-action">
                                <i class="fas fa-arrow-left"></i>Back to Articles
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="article-header">
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
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
                        <?php echo date('F d, Y', strtotime($article['published_at'])); ?>
                    </span>
                    <span class="article-meta-item">
                        <i class="fas fa-clock"></i>
                        <?php echo date('h:i A', strtotime($article['published_at'])); ?>
                    </span>
                    <span class="article-meta-item">
                        <i class="fas fa-tag"></i>
                        <?php echo htmlspecialchars($article['status']); ?>
                    </span>
                    <span class="article-meta-item">
                        <i class="fas fa-edit"></i>
                        Last Updated: <?php echo date('F d, Y h:i A', strtotime($article['updated_at'])); ?>
                    </span>
                </div>
            </div>

            <div class="article-content">
                <?php if (!empty($article['summary'])): ?>
                    <div class="article-summary">
                        <?php echo nl2br(htmlspecialchars($article['summary'])); ?>
                    </div>
                <?php endif; ?>

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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 