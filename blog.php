<?php
$page_title = "Our Blog - Lawyex";
$current_page = "blog";

require_once 'config/database.php';

// Pagination settings
$posts_per_page = 6;
$current_page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page_num - 1) * $posts_per_page;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';

// Build the query
$query = "SELECT a.*, u.username as author_name 
          FROM articles a 
          LEFT JOIN admin_users u ON a.author_id = u.id 
          WHERE a.status = 'published'";
                                                                                                                                            
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (a.title LIKE '%$search%' OR a.content LIKE '%$search%' OR a.summary LIKE '%$search%')";
}

if (!empty($category_filter)) {
    $category_filter = $conn->real_escape_string($category_filter);
    $query .= " AND a.category = '$category_filter'";
}

// Get total posts for pagination
$total_query = str_replace("SELECT a.*, u.username as author_name", "SELECT COUNT(*)", $query);
$total_result = $conn->query($total_query);
$total_posts = $total_result->fetch_row()[0];
$total_pages = ceil($total_posts / $posts_per_page);

// Add pagination to main query
$query .= " ORDER BY a.published_at DESC LIMIT $offset, $posts_per_page";
$result = $conn->query($query);
$blog_posts = $result->fetch_all(MYSQLI_ASSOC);

// Get categories with post counts
$categories_query = "SELECT category, COUNT(*) as count 
                    FROM articles 
                    WHERE status = 'published' 
                    GROUP BY category 
                    ORDER BY count DESC";
$categories_result = $conn->query($categories_query);
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Get recent posts
$recent_query = "SELECT title, published_at, slug 
                FROM articles 
                WHERE status = 'published' 
                ORDER BY published_at DESC 
                LIMIT 5";     
$recent_result = $conn->query($recent_query);
$recent_posts = $recent_result->fetch_all(MYSQLI_ASSOC);

// Get all unique tags
$tags_query = "SELECT DISTINCT TRIM(tag) as tag 
               FROM (
                   SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(tags, ',', n.n), ',', -1) as tag
                   FROM articles
                   CROSS JOIN (
                       SELECT a.N + b.N * 10 + 1 as n
                       FROM (SELECT 0 as N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a
                       CROSS JOIN (SELECT 0 as N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b
                   ) n
                   WHERE n.n <= 1 + (LENGTH(tags) - LENGTH(REPLACE(tags, ',', '')))
                   AND status = 'published'
                   AND tags IS NOT NULL 
                   AND tags != ''
               ) tags_table";
$tags_result = $conn->query($tags_query);
$tags = $tags_result->fetch_all(MYSQLI_ASSOC);
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

    <!-- Blog Section -->
    <section class="blog-section py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Blog Posts -->
                <div class="col-lg-8">
                    <?php if (empty($blog_posts)): ?>
                        <div class="alert alert-info">
                            No articles found. Please try a different search or category.
                        </div>
                    <?php else: ?>
                        <?php foreach ($blog_posts as $post): ?>
                        <div class="blog-card mb-4">
                            <div class="card-body">
                                <div class="blog-meta mb-2">
                                    <span><i class="far fa-calendar-alt me-2"></i><?php echo date('F j, Y', strtotime($post['published_at'])); ?></span>
                                    <span class="ms-3"><i class="far fa-user me-2"></i><?php echo htmlspecialchars($post['author_name']); ?></span>
                                    <span class="ms-3"><i class="far fa-folder me-2"></i><?php echo htmlspecialchars($post['category']); ?></span>
                                </div>
                                <h3 class="card-title mb-3"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <div class="row g-0">
                                    <div class="col-12 mb-3">
                                        <?php if (!empty($post['video_url'])): ?>
                                            <div class="ratio ratio-16x9 mb-3">
                                                <video controls class="rounded">
                                                    <source src="<?php echo htmlspecialchars($post['video_url']); ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        <?php elseif (!empty($post['cover_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($post['cover_image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; height: auto; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="assets/images/blog-default.jpg" class="img-fluid rounded" alt="Default Blog Image" style="width: 100%; height: auto; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12">
                                        <p class="card-text"><?php echo htmlspecialchars($post['summary'] ?? substr(strip_tags($post['content']), 0, 200) . '...'); ?></p>
                                        <a href="article.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="btn btn-warning">Read More</a>
                                        <!-- Social Media Icons -->
                                        <div class="social-icons mt-3">
                                            <?php
                                            // To implement social media icons, you'll need to fetch the social media links
                                            // from your database (e.g., from the 'articles' table or a related table).
                                            // For example, if you have columns like 'facebook_link', 'twitter_link', etc.:
                                            // if (!empty($post['facebook_link'])) {
                                            //     echo '<a href="' . htmlspecialchars($post['facebook_link']) . '" class="me-2 text-dark"><i class="fab fa-facebook-f"></i></a>';
                                            // }
                                            // if (!empty($post['twitter_link'])) {
                                            //     echo '<a href="' . htmlspecialchars($post['twitter_link']) . '" class="me-2 text-dark"><i class="fab fa-twitter"></i></a>';
                                            // }
                                            // if (!empty($post['linkedin_link'])) {
                                            //     echo '<a href="' . htmlspecialchars($post['linkedin_link']) . '" class="me-2 text-dark"><i class="fab fa-linkedin-in"></i></a>';
                                            // }
                                            // if (!empty($post['instagram_link'])) {
                                            //     echo '<a href="' . htmlspecialchars($post['instagram_link']) . '" class="me-2 text-dark"><i class="fab fa-instagram"></i></a>';
                                            // }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <nav aria-label="Blog pagination">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo $current_page_num <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $current_page_num - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo $i === $current_page_num ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo $current_page_num >= $total_pages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $current_page_num + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Search Widget -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">Search</h4>
                            <form class="d-flex" method="GET" action="">
                                <input class="form-control me-2" type="search" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-warning" type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">Categories</h4>
                            <ul class="list-unstyled">
                                <?php foreach ($categories as $category): ?>
                                <li class="mb-2">
                                    <a href="?category=<?php echo urlencode($category['category']); ?>" class="text-decoration-none <?php echo $category_filter === $category['category'] ? 'text-warning' : ''; ?>">
                                        <?php echo htmlspecialchars($category['category']); ?>
                                        <span class="badge bg-warning float-end"><?php echo $category['count']; ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">Recent Posts</h4>
                            <ul class="list-unstyled">
                                <?php foreach ($recent_posts as $post): ?>
                                <li class="mb-3">
                                    <a href="article.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="text-decoration-none">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($post['title']); ?></h6>
                                        <small class="text-muted"><?php echo date('F j, Y', strtotime($post['published_at'])); ?></small>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Tags</h4>
                            <div class="tags">
                                <?php foreach ($tags as $tag): ?>
                                <a href="?tag=<?php echo urlencode($tag['tag']); ?>" class="btn btn-sm btn-outline-warning me-2 mb-2"><?php echo htmlspecialchars($tag['tag']); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
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