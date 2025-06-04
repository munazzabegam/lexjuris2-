<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

// Get case ID from URL
$case_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($case_id <= 0) {
    header("Location: cases.php");
    exit();
}

// Fetch case details
$query = "SELECT c.*, a.username as author_name 
          FROM cases c 
          LEFT JOIN admin_users a ON c.author_id = a.id 
          WHERE c.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();
$case = $result->fetch_assoc();

if (!$case) {
    header("Location: cases.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Details - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .case-details {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .case-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .case-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .case-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .case-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .case-meta-item i {
            color: #bc841c;
            width: 16px;
        }

        .case-section {
            margin-bottom: 2rem;
        }

        .case-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .case-description {
            color: #444;
            line-height: 1.6;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            text-indent: 0;
        }

        .case-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .case-tag {
            background: rgba(188, 132, 20, 0.1);
            color: #bc841c;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .case-status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-pending {
            background: rgba(188, 132, 20, 0.1);
            color: #bc841c;
        }

        .status-closed {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 4px;
        }

        .btn-action i {
            margin-right: 0.5rem;
        }

        .case-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .case-info-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
        }

        .case-info-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .case-info-value {
            font-weight: 500;
            color: #333;
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
                        <h4 class="mb-0">Case Details</h4>
                        <div>
                            <a href="edit_case.php?id=<?php echo $case_id; ?>" class="btn btn-primary me-2">
                                <i class="fas fa-edit me-2"></i>Edit Case
                            </a>
                            <a href="cases.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cases
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="case-details">
                <div class="case-header">
                    <h1 class="case-title"><?php echo htmlspecialchars($case['title']); ?></h1>
                    <div class="case-meta">
                        <span class="case-meta-item">
                            <i class="fas fa-gavel"></i>
                            <?php echo ucfirst(htmlspecialchars($case['category'])); ?>
                        </span>
                        <span class="case-meta-item">
                            <i class="fas fa-hashtag"></i>
                            <?php echo htmlspecialchars($case['case_number']); ?>
                        </span>
                        <span class="case-meta-item">
                            <i class="fas fa-calendar"></i>
                            <?php echo date('F d, Y', strtotime($case['created_at'])); ?>
                        </span>
                        <span class="case-meta-item">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($case['author_name']); ?>
                        </span>
                    </div>
                </div>

                <div class="case-info-grid">
                    <div class="case-info-item">
                        <div class="case-info-label">Status</div>
                        <div class="case-info-value">
                            <span class="case-status status-<?php echo strtolower(str_replace(' ', '-', $case['status'])); ?>">
                                <?php echo htmlspecialchars($case['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="case-info-item">
                        <div class="case-info-label">Category</div>
                        <div class="case-info-value">
                            <?php echo ucfirst(htmlspecialchars($case['category'])); ?>
                        </div>
                    </div>
                    <div class="case-info-item">
                        <div class="case-info-label">Created</div>
                        <div class="case-info-value">
                            <?php echo date('F d, Y', strtotime($case['created_at'])); ?>
                        </div>
                    </div>
                    <div class="case-info-item">
                        <div class="case-info-label">Last Updated</div>
                        <div class="case-info-value">
                            <?php echo date('F d, Y', strtotime($case['updated_at'])); ?>
                        </div>
                    </div>
                </div>

                <div class="case-section">
                    <h2 class="case-section-title">Description</h2>
                    <div class="case-description">
                        <?php echo nl2br(htmlspecialchars($case['description'])); ?>
                    </div>
                </div>

                <?php if (!empty($case['link'])): ?>
                <div class="case-section">
                    <h2 class="case-section-title">External Link</h2>
                    <a href="<?php echo htmlspecialchars($case['link']); ?>" target="_blank" class="btn btn-outline-primary btn-action">
                        <i class="fas fa-external-link-alt"></i>Open Link
                    </a>
                </div>
                <?php endif; ?>

                <?php if (!empty($case['tags'])): ?>
                <div class="case-section">
                    <h2 class="case-section-title">Tags</h2>
                    <div class="case-tags">
                        <?php foreach (explode(',', $case['tags']) as $tag): ?>
                            <span class="case-tag"><?php echo trim(htmlspecialchars($tag)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html> 