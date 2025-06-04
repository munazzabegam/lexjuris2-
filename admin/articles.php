<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Fetch articles with author info
$query = "SELECT a.*, u.username as author_name 
          FROM articles a 
          LEFT JOIN admin_users u ON a.author_id = u.id 
          ORDER BY a.published_at DESC, a.updated_at DESC";
$result = $conn->query($query);
$articles = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    $result->free();
}

// Read and clear session messages
$article_success = $_SESSION['article_success'] ?? null;
unset($_SESSION['article_success']);
$article_error = $_SESSION['article_error'] ?? null;
unset($_SESSION['article_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .table-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .table-card .card-header {
            background: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0 0 0.75rem 0;
            margin-bottom: 0.75rem;
        }
        .table th {
            font-weight: 600;
            color: #666;
            border-top: none;
            padding: 0.75rem;
            font-size: 0.85rem;
        }
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            font-size: 0.95rem;
        }
        .btn-action {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 6px;
        }
        .btn-action i {
            margin-right: 0.4em;
        }
    </style>
</head>
<body>
<?php include 'components/sidebar.php'; ?>
<?php include 'components/topnavbar.php'; ?>
<div class="main-content">
    <div class="container-fluid p-3">
        <?php if ($article_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($article_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($article_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($article_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Articles Management</h4>
            <a href="add_article.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Article
            </a>
        </div>
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>All Articles</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($articles)): ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($article['id']); ?></td>
                                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                                    <td><?php echo htmlspecialchars($article['author_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $article['status'] === 'published' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($article['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $article['published_at'] ? date('Y-m-d', strtotime($article['published_at'])) : '-'; ?></td>
                                    <td>
                                        <a href="article_details.php?id=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-eye"></i>View</a>
                                        <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-secondary btn-action"><i class="fas fa-edit"></i>Edit</a>
                                        <form action="actions/delete_article.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');" style="display: inline;">
                                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action"><i class="fas fa-trash"></i>Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No articles found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 