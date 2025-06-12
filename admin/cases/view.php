<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get case ID from URL
$case_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($case_id <= 0) {
    $_SESSION['case_error'] = "Invalid case ID.";
    header("Location: index.php");
    exit();
}

// Get case details
$stmt = $conn->prepare("
    SELECT c.*, a.username as author_name
    FROM cases c 
    LEFT JOIN admin_users a ON c.author_id = a.id 
    WHERE c.id = ?
");
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();
$case = $result->fetch_assoc();

if (!$case) {
    $_SESSION['case_error'] = "Case not found.";
    header("Location: index.php");
    exit();
}

$case_success = $_SESSION['case_success'] ?? null;
unset($_SESSION['case_success']);
$case_error = $_SESSION['case_error'] ?? null;
unset($_SESSION['case_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Case - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .view-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .case-header {
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .case-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .case-meta {
            color: #666;
            font-size: 0.9rem;
        }
        .case-meta span {
            margin-right: 1rem;
        }
        .case-meta i {
            margin-right: 0.3rem;
        }
        .case-content {
            margin-top: 1.5rem;
        }
        .case-content h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }
        .case-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .badge {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }
        .tags {
            margin-top: 1rem;
        }
        .tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #f0f0f0;
            border-radius: 15px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if ($case_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($case_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($case_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($case_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">View Case</h4>
                        <div>
                            <a href="edit.php?id=<?php echo $case['id']; ?>" class="btn btn-outline-primary me-2">
                                <i class="fas fa-edit me-2"></i>Edit Case
                            </a>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cases
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="view-card">
                <div class="case-header">
                    <h1 class="case-title"><?php echo htmlspecialchars($case['title']); ?></h1>
                    <div class="case-meta">
                        <span><i class="fas fa-hashtag"></i><?php echo htmlspecialchars($case['case_number']); ?></span>
                        <span><i class="fas fa-folder"></i><?php echo ucfirst(htmlspecialchars($case['category'])); ?></span>
                        <span>
                            <i class="fas fa-circle"></i>
                            <span class="badge bg-<?php 
                                if ($case['status'] === 'Open') echo 'success'; 
                                else if ($case['status'] === 'In Progress') echo 'warning';
                                else if ($case['status'] === 'Closed') echo 'secondary';
                            ?>">
                                <?php echo htmlspecialchars($case['status']); ?>
                            </span>
                        </span>
                        <span><i class="fas fa-user"></i><?php echo htmlspecialchars($case['author_name'] ?? 'N/A'); ?></span>
                        <span><i class="fas fa-calendar"></i><?php echo date('F j, Y', strtotime($case['created_at'])); ?></span>
                        <?php if ($case['updated_at']): ?>
                            <span><i class="fas fa-clock"></i>Last updated: <?php echo date('F j, Y', strtotime($case['updated_at'])); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($case['image_path'])): ?>
                <div class="text-center">
                    <img src="<?php echo htmlspecialchars($case['image_path']); ?>" alt="Case featured image" class="case-image">
                </div>
                <?php endif; ?>

                <div class="case-content">
                    <h3>Description</h3>
                    <p><?php echo !empty($case['description']) ? nl2br(htmlspecialchars($case['description'])) : 'No description available.'; ?></p>

                    <?php if (!empty($case['link'])): ?>
                    <h3>External Link</h3>
                    <p><a href="<?php echo htmlspecialchars($case['link']); ?>" target="_blank"><?php echo htmlspecialchars($case['link']); ?></a></p>
                    <?php endif; ?>

                    <?php if (!empty($case['tags'])): ?>
                    <div class="tags">
                        <h3>Tags</h3>
                        <?php 
                        $tags = explode(',', $case['tags']);
                        foreach ($tags as $tag): 
                            $tag = trim($tag);
                            if (!empty($tag)):
                        ?>
                            <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html> 