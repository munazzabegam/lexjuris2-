<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Get statistics
try {
    // Total cases
    $stmt = $conn->prepare("SELECT COUNT(*) FROM cases");
    $stmt->execute();
    $stmt->bind_result($totalCases);
    $stmt->fetch();
    $stmt->close();

    // Total users
    $stmt = $conn->prepare("SELECT COUNT(*) FROM admin_users");
    $stmt->execute();
    $stmt->bind_result($totalUsers);
    $stmt->fetch();
    $stmt->close();

    // Total articles
    $stmt = $conn->prepare("SELECT COUNT(*) FROM articles");
    $stmt->execute();
    $stmt->bind_result($totalArticles);
    $stmt->fetch();
    $stmt->close();

    // Total testimonials
    $stmt = $conn->prepare("SELECT COUNT(*) FROM testimonials");
    $stmt->execute();
    $stmt->bind_result($totalTestimonials);
    $stmt->fetch();
    $stmt->close();

    // Recent cases with client information
    $recentCases = [];
    $query = "SELECT c.*, a.username as author_name 
              FROM cases c 
              LEFT JOIN admin_users a ON c.author_id = a.id 
              ORDER BY c.created_at DESC 
              LIMIT 5";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recentCases[] = $row;
        }
        $result->free();
    }

} catch (mysqli_sql_exception $e) {
    error_log("Dashboard query failed: " . $e->getMessage());
    $error = "Failed to load dashboard data. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lex Guris - Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- css-link -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <?php include 'components/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php include 'components/topnavbar.php'; ?>

        <!-- Dashboard Content -->
        <div class="container-fluid p-3">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <div class="title">Total Cases</div>
                        <div class="value"><?php echo number_format($totalCases); ?></div>
                        <div class="trend up">
                            <i class="fas fa-arrow-up"></i> Active
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="title">Active Users</div>
                        <div class="value"><?php echo number_format($totalUsers); ?></div>
                        <div class="trend up">
                            <i class="fas fa-arrow-up"></i> Registered
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="title">Articles</div>
                        <div class="value"><?php echo number_format($totalArticles); ?></div>
                        <div class="trend up">
                            <i class="fas fa-arrow-up"></i> Published
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="title">Testimonials</div>
                        <div class="value"><?php echo number_format($totalTestimonials); ?></div>
                        <div class="trend up">
                            <i class="fas fa-arrow-up"></i> Reviews
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Cases Table -->
            <div class="row">
                <div class="col-12">
                    <div class="table-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Recent Cases</h5>
                            <a href="cases.php" class="btn btn-primary btn-sm">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Case ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentCases)): ?>
                                        <?php foreach ($recentCases as $case): ?>
                                            <tr>
                                                <td>#<?php echo htmlspecialchars($case['case_number']); ?></td>
                                                <td><?php echo htmlspecialchars($case['title']); ?></td>
                                                <td><?php echo htmlspecialchars($case['author_name'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo strtolower($case['status']); ?>">
                                                        <i class="fas fa-circle"></i>
                                                        <?php echo htmlspecialchars($case['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('Y-m-d', strtotime($case['created_at'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No recent cases found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 