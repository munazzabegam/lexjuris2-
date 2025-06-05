<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Get FAQ ID from URL
$faq_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$faq_id) {
    $_SESSION['faq_error'] = "Invalid FAQ ID";
    header("Location: faqs.php");
    exit();
}

// Fetch FAQ details
$query = "SELECT * FROM faq WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faq_id);
$stmt->execute();
$result = $stmt->get_result();
$faq = $result->fetch_assoc();

if (!$faq) {
    $_SESSION['faq_error'] = "FAQ not found";
    header("Location: faqs.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View FAQ - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <link href="assets/css/faq.css" rel="stylesheet">
    <style>
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .card-title {
            color: #666;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .card-text {
            color: #333;
            font-size: 1rem;
            line-height: 1.6;
        }
        .badge-active {
            background-color: #28a745;
            color: white;
            padding: 0.5em 1em;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        .badge-inactive {
            background-color: #dc3545;
            color: white;
            padding: 0.5em 1em;
            border-radius: 4px;
            font-size: 0.85rem;
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
        <div class="section-header">
            <h4>View FAQ</h4>
            <div class="action-buttons">
                <a href="faqs.php" class="btn btn-secondary btn-action">
                    <i class="fas fa-arrow-left"></i>Back to FAQs
                </a>
                <a href="edit_faq.php?id=<?php echo $faq_id; ?>" class="btn btn-primary btn-action">
                    <i class="fas fa-edit"></i>Edit FAQ
                </a>
            </div>
        </div>

        <div class="faq-card faq-view">
            <div class="mb-4">
                <h5 class="card-title">Question</h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($faq['question'])); ?></p>
            </div>
            <div class="mb-4">
                <h5 class="card-title">Answer</h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Order Index</h5>
                    <p class="card-text"><?php echo htmlspecialchars($faq['order_index']); ?></p>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Status</h5>
                    <p class="card-text">
                        <span class="badge <?php echo $faq['is_active'] ? 'badge-active' : 'badge-inactive'; ?>">
                            <?php echo $faq['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4">
                    <h5 class="card-title">Author</h5>
                    <p class="card-text"><?php echo htmlspecialchars($faq['author_name'] ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-4">
                    <h5 class="card-title">Created At</h5>
                    <p class="card-text"><?php echo date('F j, Y, g:i a', strtotime($faq['created_at'])); ?></p>
                </div>
                <div class="col-md-4">
                    <h5 class="card-title">Updated At</h5>
                    <p class="card-text"><?php echo date('F j, Y, g:i a', strtotime($faq['updated_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 