<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$faq = null;
if (isset($_GET['id'])) {
    $faq_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($faq_id) {
        $stmt = $conn->prepare("SELECT * FROM faq WHERE id = ?");
        $stmt->bind_param("i", $faq_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $faq = $result->fetch_assoc();
        $stmt->close();
    }

    if (!$faq) {
        $_SESSION['faq_error'] = "FAQ not found.";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View FAQ - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }
        .faq-question {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }
        .faq-answer {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 1.5rem;
        }
        .faq-meta {
            font-size: 0.9rem;
            color: #777;
        }
        .faq-meta span {
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>FAQ Details</h4>
                    <div>
                        <a href="edit.php?id=<?php echo $faq['id']; ?>" class="btn btn-primary btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="index.php" class="btn btn-secondary btn-action">
                            <i class="fas fa-arrow-left"></i> Back to FAQs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="faq-question"><?php echo htmlspecialchars($faq['question']); ?></p>
                    <div class="faq-answer">
                        <?php echo $faq['answer']; // Answer is HTML, no htmlspecialchars ?>
                    </div>
                    <div class="faq-meta">
                        <span>Status: <?php echo $faq['is_active'] ? 'Active' : 'Inactive'; ?></span>
                        <span>Order: <?php echo htmlspecialchars($faq['order_index']); ?></span>
                        <span>Created: <?php echo date('Y-m-d H:i', strtotime($faq['created_at'])); ?></span>
                        <span>Updated: <?php echo $faq['updated_at'] ? date('Y-m-d H:i', strtotime($faq['updated_at'])) : 'N/A'; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 