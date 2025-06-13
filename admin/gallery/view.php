<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$gallery_image = null;
$gallery_id = $_GET['id'] ?? 0;

if ($gallery_id) {
    $stmt = $conn->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gallery_image = $result->fetch_assoc();

    if (!$gallery_image) {
        $_SESSION['gallery_error'] = "Gallery image not found.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['gallery_error'] = "Gallery image ID not provided.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Gallery Image - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
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
        .gallery-image-view {
            max-width: 100%;
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
                        <h4 class="mb-0">View Gallery Image Details</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Gallery
                        </a>
                    </div>
                </div>
            </div>

            <div class="view-card">
                <div class="view-group">
                    <span class="view-label">Image:</span>
                    <div class="view-value">
                        <?php if ($gallery_image['image']): ?>
                            <img src="../../<?php echo htmlspecialchars($gallery_image['image']); ?>" alt="Gallery Image" class="gallery-image-view">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                </div>
                <div class="view-group">
                    <span class="view-label">Uploaded By:</span>
                    <span class="view-value"><?php echo htmlspecialchars($gallery_image['uploaded_by'] ?? 'N/A'); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Status:</span>
                    <span class="view-value">
                        <span class="badge <?php echo $gallery_image['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo $gallery_image['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </span>
                </div>
                <div class="view-group">
                    <span class="view-label">Order Index:</span>
                    <span class="view-value"><?php echo htmlspecialchars($gallery_image['order_index'] ?? 'N/A'); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Created At:</span>
                    <span class="view-value"><?php echo date('Y-m-d H:i:s', strtotime($gallery_image['created_at'])); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Last Updated:</span>
                    <span class="view-value"><?php echo $gallery_image['updated_at'] ? date('Y-m-d H:i:s', strtotime($gallery_image['updated_at'])) : 'N/A'; ?></span>
                </div>

                <div class="mt-4">
                    <a href="edit.php?id=<?php echo $gallery_image['id']; ?>" class="btn btn-primary btn-action">
                        <i class="fas fa-edit me-2"></i> Edit Image
                    </a>
                    <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');" style="display: inline;">
                        <input type="hidden" name="gallery_id" value="<?php echo $gallery_image['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-action">
                            <i class="fas fa-trash me-2"></i> Delete Image
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 