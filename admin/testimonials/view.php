<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$testimonial = null;
$testimonial_id = $_GET['id'] ?? 0;

if ($testimonial_id) {
    $stmt = $conn->prepare("SELECT t.*, au.username AS author_name FROM testimonials t LEFT JOIN admin_users au ON t.user_id = au.id WHERE t.id = ?");
    $stmt->bind_param("i", $testimonial_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $testimonial = $result->fetch_assoc();

    if (!$testimonial) {
        $_SESSION['testimonial_error'] = "Testimonial not found.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['testimonial_error'] = "Testimonial ID not provided.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Testimonial - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Custom styles for the view card */
        .view-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .view-card h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 0.75rem;
        }
        .view-group {
            margin-bottom: 1rem;
        }
        .view-label {
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 0.25rem;
        }
        .view-value {
            color: #333;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }
        .btn-action {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .testimonial-photo-view {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">View Testimonial Details</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Testimonials
                        </a>
                    </div>
                </div>
            </div>

            <div class="view-card">
                <div class="view-group">
                    <span class="view-label">Name:</span>
                    <span class="view-value"><?php echo htmlspecialchars($testimonial['name']); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Position:</span>
                    <span class="view-value"><?php echo htmlspecialchars($testimonial['position'] ?? 'N/A'); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Company:</span>
                    <span class="view-value"><?php echo htmlspecialchars($testimonial['company'] ?? 'N/A'); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Photo:</span>
                    <div class="view-value">
                        <?php if ($testimonial['photo']): ?>
                            <img src="../../<?php echo htmlspecialchars($testimonial['photo']); ?>" alt="Testimonial Photo" class="testimonial-photo-view">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                </div>
                <div class="view-group">
                    <span class="view-label">Testimonial Content:</span>
                    <p class="view-value"><?php echo nl2br(htmlspecialchars($testimonial['testimonial'])); ?></p>
                </div>
                <div class="view-group">
                    <span class="view-label">Status:</span>
                    <span class="view-value">
                        <span class="badge <?php echo $testimonial['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo $testimonial['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </span>
                </div>
                <div class="view-group">
                    <span class="view-label">Order Index:</span>
                    <span class="view-value"><?php echo htmlspecialchars($testimonial['order_index']); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Date Added:</span>
                    <span class="view-value"><?php echo date('Y-m-d H:i:s', strtotime($testimonial['date_added'])); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Added By:</span>
                    <span class="view-value"><?php echo htmlspecialchars($testimonial['author_name'] ?? 'N/A'); ?></span>
                </div>

                <div class="mt-4">
                    <a href="edit.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-primary btn-action">
                        <i class="fas fa-edit me-2"></i> Edit Testimonial
                    </a>
                    <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?');" style="display: inline;">
                        <input type="hidden" name="testimonial_id" value="<?php echo $testimonial['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-action">
                            <i class="fas fa-trash me-2"></i> Delete Testimonial
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html> 