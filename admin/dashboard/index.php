<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get statistics
try {
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

    // Total FAQ
    $stmt = $conn->prepare("SELECT COUNT(*) FROM faq");
    $stmt->execute();
    $stmt->bind_result($totalFaq);
    $stmt->fetch();
    $stmt->close();

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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- css-link -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        body { background: #f6f7fb; }
        .welcome-banner {
            background: var(--primary-color);
            color: #fff;
            border-radius: 14px;
            padding: 2.2rem 2rem 1.5rem 2rem;
            margin-bottom: 2rem;
            font-family: 'Roboto', sans-serif;
        }
        .welcome-banner h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }
        .welcome-banner p {
            font-size: 1.1rem;
            color: #fff8e1;
            margin-bottom: 0;
        }
        .stat-cards {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .stat-card {
            flex: 1 1 200px;
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            min-width: 200px;
        }
        .stat-card .icon {
            font-size: 2.2rem;
            border-radius: 50%;
            padding: 0.7rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-users { background: #2563eb; }
        .stat-articles { background: #f59e42; }
        .stat-testimonials { background: #22c55e; }
        .stat-faq { background: #7c3aed; }
        .stat-card .info {
            display: flex;
            flex-direction: column;
        }
        .stat-card .label {
            font-size: 1.1rem;
            color: #888;
            font-weight: 500;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #22223b;
        }
        .dashboard-sections {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .dashboard-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem 1.2rem;
            flex: 1 1 350px;
            min-width: 320px;
        }
        .dashboard-section h5 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
        }
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }
        .quick-action-btn {
            background: #f6f7fb;
            border: 2px dashed #e0e7ff;
            border-radius: 10px;
            padding: 1.2rem 0.5rem;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: #bc8414;
            cursor: pointer;
            transition: background 0.2s, border 0.2s;
        }
        .quick-action-btn:hover {
            background: #fffbe6;
            border-color: #bc8414;
        }
        .quick-action-btn i {
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .recent-activity {
            min-height: 90px;
            color: #888;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }
        .system-info {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem 1.2rem;
            margin-top: 2rem;
        }
        .system-info h5 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
        }
        .system-info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .system-info-list li {
            margin-bottom: 0.7rem;
            color: #444;
            font-size: 1rem;
        }
        @media (max-width: 991px) {
            .stat-cards, .dashboard-sections { flex-direction: column; gap: 1rem; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../components/topnavbar.php'; ?>
        <div class="container-fluid p-3">
            <div class="welcome-banner mb-4">
                <h2>Welcome back, admin!</h2>
                <p>Here's what's happening with your site today.</p>
            </div>
            <div class="stat-cards mb-4">
                <div class="stat-card">
                    <div class="icon stat-users"><i class="fas fa-users"></i></div>
                    <div class="info">
                        <span class="label">Total Users</span>
                        <span class="value"><?php echo number_format($totalUsers); ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon stat-articles"><i class="fas fa-newspaper"></i></div>
                    <div class="info">
                        <span class="label">Total Articles</span>
                        <span class="value"><?php echo number_format($totalArticles); ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon stat-testimonials"><i class="fas fa-star"></i></div>
                    <div class="info">
                        <span class="label">Testimonials</span>
                        <span class="value"><?php echo number_format($totalTestimonials); ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon stat-faq"><i class="fas fa-question-circle"></i></div>
                    <div class="info">
                        <span class="label">FAQ Entries</span>
                        <span class="value"><?php echo number_format($totalFaq); ?></span>
                    </div>
                </div>
            </div>
            <div class="dashboard-sections mb-4">
                <div class="dashboard-section">
                    <h5>Quick Actions</h5>
                    <div class="quick-actions">
                        <a href="../articles/add.php" class="quick-action-btn"><i class="fas fa-plus"></i>New Article</a>
                        <a href="../users/create.php" class="quick-action-btn"><i class="fas fa-user-plus"></i>New User</a>
                        <a href="../testimonials/create.php" class="quick-action-btn"><i class="fas fa-star"></i>New Testimonial</a>
                        <a href="../team_members/create.php" class="quick-action-btn"><i class="fas fa-users-cog"></i>Add Team Member</a>
                    </div>
                </div>
                <div class="dashboard-section">
                    <h5>Recent Activity</h5>
                    <div class="recent-activity">No recent activity.</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 