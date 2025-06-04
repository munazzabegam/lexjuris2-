<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Fetch categories for the dropdown
$categories_query = "SELECT DISTINCT category FROM articles WHERE category IS NOT NULL";
$categories_result = $conn->query($categories_query);
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

$errors = $_SESSION['article_errors'] ?? [];
unset($_SESSION['article_errors']);
$success = $_SESSION['article_success'] ?? null;
unset($_SESSION['article_success']);
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Article - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .add-article-form {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem 0.75rem;
            color: #333;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #bc841c;
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.15);
        }

        .form-select {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem 0.75rem;
            color: #333;
            transition: border-color 0.15s ease-in-out;
        }

        .form-select:focus {
            border-color: #bc841c;
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.15);
        }

        .form-text {
            color: #666;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 4px;
        }

        .btn-action i {
            margin-right: 0.5rem;
        }

        .btn-primary {
            background-color: #bc841c;
            border-color: #bc841c;
        }

        .btn-primary:hover {
            background-color: #a67316;
            border-color: #a67316;
        }

        .btn-outline-primary {
            color: #bc841c;
            border-color: #bc841c;
        }

        .btn-outline-primary:hover {
            background-color: #bc841c;
            border-color: #bc841c;
        }

         textarea.form-control {
            min-height: 200px;
            line-height: 1.6;
            font-size: 0.95rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            resize: vertical;
        }

        textarea.form-control:focus {
            background-color: #fff;
            border-color: #bc841c;
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.15);
        }

        .description-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }

        .description-wrapper .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
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
                        <h4 class="mb-0">Add New Article</h4>
                        <a href="articles.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Articles
                        </a>
                    </div>
                </div>
            </div>

            <div class="add-article-form">
                <div class="form-header">
                    <h1 class="form-title">Add New Article</h1>
                    <p class="form-subtitle">Enter the details for the new article below</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form action="actions/add_article.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-section">
                        <h2 class="form-section-title">Basic Information</h2>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($old_data['title'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="criminal" <?php echo (isset($old_data['category']) && $old_data['category'] === 'criminal') ? 'selected' : ''; ?>>Criminal</option>
                                <option value="family" <?php echo (isset($old_data['category']) && $old_data['category'] === 'family') ? 'selected' : ''; ?>>Family</option>
                                <option value="cheque" <?php echo (isset($old_data['category']) && $old_data['category'] === 'cheque') ? 'selected' : ''; ?>>Cheque</option>
                                <option value="consumer" <?php echo (isset($old_data['category']) && $old_data['category'] === 'consumer') ? 'selected' : ''; ?>>Consumer</option>
                                <option value="labour" <?php echo (isset($old_data['category']) && $old_data['category'] === 'labour') ? 'selected' : ''; ?>>Labour</option>
                                <option value="high court" <?php echo (isset($old_data['category']) && $old_data['category'] === 'high court') ? 'selected' : ''; ?>>High Court</option>
                                <option value="supreme court" <?php echo (isset($old_data['category']) && $old_data['category'] === 'supreme court') ? 'selected' : ''; ?>>Supreme Court</option>
                                <option value="other" <?php echo (isset($old_data['category']) && $old_data['category'] === 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control" id="summary" name="summary" rows="3"><?php echo htmlspecialchars($old_data['summary'] ?? ''); ?></textarea>
                             <div class="form-text">Brief summary of the article (optional).</div>
                        </div>
                    </div>

                     <div class="form-section">
                        <h2 class="form-section-title">Content</h2>
                         <div class="description-wrapper">
                             <label for="content" class="form-label">Content</label>
                             <textarea class="form-control" id="content" name="content" rows="15" required><?php echo htmlspecialchars($old_data['content'] ?? ''); ?></textarea>
                         </div>
                    </div>

                    <div class="form-section">
                        <h2 class="form-section-title">Media & Settings</h2>
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                             <label for="cover_image" class="form-label">Cover Image</label>
                             <input type="file" class="form-control" id="cover_image" name="cover_image">
                             <div class="form-text">Upload a cover image for the article (optional).</div>
                        </div>
                         <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="tags" name="tags" value="<?php echo htmlspecialchars($old_data['tags'] ?? ''); ?>">
                            <div class="form-text">Comma-separated tags (optional).</div>
                        </div>
                         <div class="mb-3">
                            <label for="external_link" class="form-label">External Link</label>
                            <input type="url" class="form-control" id="external_link" name="external_link" value="<?php echo htmlspecialchars($old_data['external_link'] ?? ''); ?>">
                            <div class="form-text">Optional link to an external resource.</div>
                        </div>

                        <div class="form-section">
                            <h2 class="form-section-title">Social Media Links</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="linkedin" class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control" id="linkedin" name="social_links[linkedin]" value="<?php echo htmlspecialchars($old_data['social_links']['linkedin'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="twitter" class="form-label">Twitter</label>
                                    <input type="url" class="form-control" id="twitter" name="social_links[twitter]" value="<?php echo htmlspecialchars($old_data['social_links']['twitter'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="url" class="form-control" id="facebook" name="social_links[facebook]" value="<?php echo htmlspecialchars($old_data['social_links']['facebook'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="url" class="form-control" id="instagram" name="social_links[instagram]" value="<?php echo htmlspecialchars($old_data['social_links']['instagram'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="youtube" class="form-label">YouTube</label>
                                    <input type="url" class="form-control" id="youtube" name="social_links[youtube]" value="<?php echo htmlspecialchars($old_data['social_links']['youtube'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pinterest" class="form-label">Pinterest</label>
                                    <input type="url" class="form-control" id="pinterest" name="social_links[pinterest]" value="<?php echo htmlspecialchars($old_data['social_links']['pinterest'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="reddit" class="form-label">Reddit</label>
                                    <input type="url" class="form-control" id="reddit" name="social_links[reddit]" value="<?php echo htmlspecialchars($old_data['social_links']['reddit'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="other" class="form-label">Other Platform</label>
                                    <input type="url" class="form-control" id="other" name="social_links[other]" value="<?php echo htmlspecialchars($old_data['social_links']['other'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="articles.php" class="btn btn-secondary btn-action">
                            <i class="fas fa-times"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save"></i>Add Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 