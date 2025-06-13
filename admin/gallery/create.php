<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploaded_by = trim($_POST['uploaded_by']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $order_index = (int)$_POST['order_index'];

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../../uploads/gallery/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . "." . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "uploads/gallery/" . $file_name;
        } else {
            $_SESSION['gallery_error'] = "Error uploading image.";
            header("Location: create.php");
            exit();
        }
    }

    $stmt = $conn->prepare("INSERT INTO gallery (image, uploaded_by, is_active, order_index) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $image_path, $uploaded_by, $is_active, $order_index);

    if ($stmt->execute()) {
        $_SESSION['gallery_success'] = "Image added to gallery successfully.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['gallery_error'] = "Error adding image to gallery: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Gallery Image - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .form-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .btn-action {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if (isset($_SESSION['gallery_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['gallery_error'];
                    unset($_SESSION['gallery_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Add New Gallery Image</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Gallery
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        <div class="form-text">Upload an image for the gallery.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="uploaded_by" class="form-label">Uploaded By (Optional)</label>
                            <input type="text" class="form-control" id="uploaded_by" name="uploaded_by">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="order_index" class="form-label">Order Index</label>
                            <input type="number" class="form-control" id="order_index" name="order_index" value="0">
                            <div class="form-text">Lower numbers appear first.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save me-2"></i>Save Gallery Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 