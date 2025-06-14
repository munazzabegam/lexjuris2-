<?php
$page_title = "Our Cases - Lex Juris";
$current_page = "cases";

require_once 'config/database.php';

// Pagination settings
$cases_per_page = 6; // Number of cases to display per page
$current_page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page_num - 1) * $cases_per_page;

// Search and Filter functionality
$search_query_param = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';
$tag_filter = isset($_GET['tag']) ? trim($_GET['tag']) : '';

// Build the query to fetch cases
$query = "SELECT * FROM cases WHERE 1=1";

if (!empty($search_query_param)) {
    $search_query_param = $conn->real_escape_string($search_query_param);
    $query .= " AND (title LIKE '%$search_query_param%' OR description LIKE '%$search_query_param%' OR category LIKE '%$search_query_param%')";
}

if (!empty($category_filter)) {
    $category_filter = $conn->real_escape_string($category_filter);
    $query .= " AND category = '$category_filter'";
}

if (!empty($tag_filter)) {
    $tag_filter = $conn->real_escape_string($tag_filter);
    $query .= " AND FIND_IN_SET('$tag_filter', tags)";
}

// Get total cases for pagination
$total_query = str_replace("SELECT * FROM cases WHERE 1=1", "SELECT COUNT(*) FROM cases WHERE 1=1", $query); // Corrected to use the same WHERE clause
$total_result = $conn->query($total_query);
$total_cases = $total_result->fetch_row()[0];
$total_pages = ceil($total_cases / $cases_per_page);

// Add pagination and order to main query
$query .= " ORDER BY created_at DESC LIMIT $offset, $cases_per_page";

$result = $conn->query($query);
$cases = $result->fetch_all(MYSQLI_ASSOC);

// Get categories with case counts
$categories_query = "SELECT category, COUNT(*) as count 
                    FROM cases 
                    GROUP BY category 
                    ORDER BY count DESC";
$categories_result = $conn->query($categories_query);
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Get recent cases
$recent_cases_query = "SELECT id, title, created_at 
                        FROM cases 
                        ORDER BY created_at DESC 
                        LIMIT 5";
$recent_cases_result = $conn->query($recent_cases_query);
$recent_cases = $recent_cases_result->fetch_all(MYSQLI_ASSOC);

// Get all unique tags (MariaDB compatible)
$tags_query = "SELECT DISTINCT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.tags, ',', n.n), ',', -1)) as tag 
               FROM cases t JOIN (SELECT a.N + b.N * 10 + 1 as n 
                                   FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a 
                                   CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b) n 
               ON n.n <= 1 + (LENGTH(t.tags) - LENGTH(REPLACE(t.tags, ',', ''))) 
               WHERE t.tags IS NOT NULL AND t.tags != ''";
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
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        .case-item {
            height: 100%;
            transition: all 0.3s ease;
        }
        .case-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .case-item .p-4 {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .case-item .card-content {
            flex-grow: 1;
        }
        .case-item .card-footer {
            margin-top: auto;
            padding-top: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

<!-- Cases Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Case Posts -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <?php
                    if (!empty($cases)) {
                        foreach ($cases as $case) {
                    ?>
                    <div class="col-lg-6 col-md-6">
                        <div class="case-item bg-light rounded overflow-hidden">
                            <div class="p-4">
                                <div class="card-content">
                                    <h5 class="mb-3"><?php echo htmlspecialchars($case['title']); ?></h5>
                                    <p class="mb-3"><?php echo htmlspecialchars(substr(strip_tags($case['description']), 0, 150)) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-warning"><i class="fa fa-folder-open me-2"></i><?php echo htmlspecialchars($case['category']); ?></small>
                                        <small class="text-muted"><i class="fa fa-calendar-alt me-2"></i><?php echo date('M d, Y', strtotime($case['created_at'])); ?></small>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="admin/cases/view.php?id=<?php echo htmlspecialchars($case['id']); ?>" class="btn btn-sm btn-warning">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center"><p class="lead">No cases to display at the moment. Please try a different search!</p></div>';
                    }
                    ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Case pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $current_page_num <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page_num - 1; ?><?php echo !empty($search_query_param) ? '&search_query=' . urlencode($search_query_param) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?><?php echo !empty($tag_filter) ? '&tag=' . urlencode($tag_filter) : ''; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i === $current_page_num ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search_query_param) ? '&search_query=' . urlencode($search_query_param) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?><?php echo !empty($tag_filter) ? '&tag=' . urlencode($tag_filter) : ''; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $current_page_num >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page_num + 1; ?><?php echo !empty($search_query_param) ? '&search_query=' . urlencode($search_query_param) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?><?php echo !empty($tag_filter) ? '&tag=' . urlencode($tag_filter) : ''; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Search Widget -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Search</h4>
                        <form class="d-flex" method="GET" action="">
                            <input class="form-control me-2" type="search" name="search_query" placeholder="Search cases..." value="<?php echo htmlspecialchars($search_query_param); ?>">
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
                                <a href="?category=<?php echo urlencode($category['category']); ?><?php echo !empty($search_query_param) ? '&search_query=' . urlencode($search_query_param) : ''; ?>" class="text-decoration-none <?php echo $category_filter === $category['category'] ? 'text-warning' : ''; ?>">
                                    <?php echo htmlspecialchars($category['category']); ?>
                                    <span class="badge bg-warning float-end"><?php echo $category['count']; ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Recent Cases Widget -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Recent Cases</h4>
                        <ul class="list-unstyled">
                            <?php foreach ($recent_cases as $rcase): ?>
                            <li class="mb-3">
                                <a href="admin/cases/view.php?id=<?php echo htmlspecialchars($rcase['id']); ?>" class="text-decoration-none">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($rcase['title']); ?></h6>
                                    <small class="text-muted"><?php echo date('F j, Y', strtotime($rcase['created_at'])); ?></small>
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
                            <a href="?tag=<?php echo urlencode($tag['tag']); ?><?php echo !empty($search_query_param) ? '&search_query=' . urlencode($search_query_param) : ''; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>" class="btn btn-sm btn-outline-warning me-2 mb-2"><?php echo htmlspecialchars($tag['tag']); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cases End -->

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>
</body>
</html> 