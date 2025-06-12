<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get FAQ ID from URL
$faq_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$faq_id) {
    $_SESSION['faq_error'] = "Invalid FAQ ID";
    header("Location: index.php");
    exit();
}

// Fetch FAQ details with author name
$query = "SELECT f.*, a.username as author_name FROM faq f LEFT JOIN admin_users a ON f.author_id = a.id WHERE f.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faq_id);
$stmt->execute();
$result = $stmt->get_result();
$faq = $result->fetch_assoc();

if (!$faq) {
    $_SESSION['faq_error'] = "FAQ not found";
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $order_index = (int)$_POST['order_index'];
    $is_active = (int)$_POST['is_active'];

    $update_query = "UPDATE faq SET question = ?, answer = ?, order_index = ?, is_active = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssiii", $question, $answer, $order_index, $is_active, $faq_id);

    if ($update_stmt->execute()) {
        $_SESSION['faq_success'] = "FAQ updated successfully";
        header("Location: index.php");
        exit();
    } else {
        $error = "Error updating FAQ: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit FAQ - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link href="../assets/css/faq.css" rel="stylesheet">
    <style>
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .form-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.6rem 0.8rem;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgba(188, 132, 20, 0.3);
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.1);
        }
        .form-text {
            color: #666;
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
        .alert {
            border-radius: 6px;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
<?php include '../components/sidebar.php'; ?>
<?php include '../components/topnavbar.php'; ?>

<div class="main-content">
    <div class="container-fluid p-3">
        <div class="section-header d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Edit FAQ</h4>
            <a href="index.php" class="btn btn-secondary btn-action">
                <i class="fas fa-arrow-left"></i>Back to FAQs
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="faq-card faq-edit card">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="question" class="form-label">Question</label>
                    <textarea class="form-control" id="question" name="question" rows="3" required><?php echo htmlspecialchars($faq['question']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="answer" class="form-label">Answer</label>
                    <textarea class="form-control" id="answer" name="answer" rows="5" required><?php echo htmlspecialchars($faq['answer']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="order_index" class="form-label">Order Index</label>
                        <input type="number" class="form-control" id="order_index" name="order_index" value="<?php echo htmlspecialchars($faq['order_index']); ?>">
                        <div class="form-text">Lower numbers appear first.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1" <?php echo $faq['is_active'] ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo !$faq['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Author</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($faq['author_name'] ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Updated</label>
                        <p class="form-control-static"><?php echo date('Y-m-d H:i', strtotime($faq['updated_at'])); ?></p>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-action">
                        <i class="fas fa-save"></i>Update FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 