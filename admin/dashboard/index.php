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
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../assets/images/favicon.png">
    <link rel="apple-touch-icon" href="../../assets/images/favicon.png">
    <link rel="manifest" href="../../assets/images/site.webmanifest">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- css-link -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        body {
            background: #f7f7f9;
            font-family: 'Roboto', sans-serif;
        }
        .dashboard-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 16px;
        }
        .dashboard-title {
            font-size: 2rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 2rem;
            letter-spacing: -1px;
        }
        .welcome-banner {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 2.2rem 2rem 1.5rem 2rem;
            margin-bottom: 2rem;
        }
        .welcome-banner h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.3rem;
        }
        .welcome-banner p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 0;
        }
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        .stat-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            min-width: 0;
            transition: box-shadow 0.2s;
        }
        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .stat-card .icon-circle {
            background: rgba(188, 132, 20, 0.12);
            color: var(--primary-color);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-right: 1rem;
        }
        .stat-card .title {
            font-size: 1.1rem;
            color: #666;
            font-weight: 500;
            margin-bottom: 0.2rem;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #22223b;
        }
        @media (max-width: 600px) {
            .dashboard-title { font-size: 1.4rem; }
            .stat-card { padding: 1.2rem 1rem; }
            .stat-value { font-size: 1.5rem; }
        }
        .quick-actions-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem 1.2rem;
            margin-bottom: 2rem;
        }
        .quick-actions-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            color: var(--text-color);
        }
        .quick-actions-list {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
        }
        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(188, 132, 20, 0.08);
            color: var(--primary-color);
            border: 1px solid rgba(188, 132, 20, 0.15);
            border-radius: 8px;
            padding: 0.7rem 1.2rem;
            font-weight: 500;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.2s, border 0.2s, color 0.2s;
        }
        .quick-action-btn i {
            font-size: 1.2rem;
        }
        .quick-action-btn:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
            text-decoration: none;
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
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                        <span class="icon-circle"><i class="fas fa-users"></i></span>
                        <div>
                            <div class="title">Total Users</div>
                            <div class="value"><?php echo number_format($totalUsers); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                        <span class="icon-circle"><i class="fas fa-newspaper"></i></span>
                        <div>
                            <div class="title">Total Articles</div>
                            <div class="value"><?php echo number_format($totalArticles); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                        <span class="icon-circle"><i class="fas fa-star"></i></span>
                        <div>
                            <div class="title">Testimonials</div>
                            <div class="value"><?php echo number_format($totalTestimonials); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                        <span class="icon-circle"><i class="fas fa-question-circle"></i></span>
                        <div>
                            <div class="title">FAQ Entries</div>
                            <div class="value"><?php echo number_format($totalFaq); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-sections mb-4">
                <div class="quick-actions-card">
                    <div class="quick-actions-title">Quick Actions</div>
                    <div class="quick-actions-list">
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