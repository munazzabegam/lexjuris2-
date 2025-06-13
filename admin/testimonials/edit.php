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
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ?");
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

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? NULL;
    $company = $_POST['company'] ?? NULL;
    $testimonial_content = $_POST['testimonial'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $current_photo_path = $_POST['current_photo'] ?? NULL;
    
    $new_photo_path = $current_photo_path; // Default to current photo

    // Handle new photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/testimonials/";
        if (!is_dir(__DIR__ . '/../../' . $target_dir)) {
            mkdir(__DIR__ . '/../../' . $target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid('testimonial_') . '.' . $file_extension;
        $upload_file = __DIR__ . '/../../' . $target_dir . $new_file_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_file)) {
            $new_photo_path = $target_dir . $new_file_name;
            // Delete old photo if a new one is uploaded and old one exists
            if ($current_photo_path && file_exists(__DIR__ . '/../../' . $current_photo_path)) {
                unlink(__DIR__ . '/../../' . $current_photo_path);
            }
        } else {
            $_SESSION['testimonial_error'] = "Failed to upload new photo.";
            header("Location: edit.php?id=" . $testimonial_id);
            exit();
        }
    } else if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
        // Remove photo if checkbox is checked and there was a current photo
        if ($current_photo_path && file_exists(__DIR__ . '/../../' . $current_photo_path)) {
            unlink(__DIR__ . '/../../' . $current_photo_path);
        }
        $new_photo_path = NULL;
    }

    // Prepare and execute the update statement
    $stmt = $conn->prepare("UPDATE testimonials SET name = ?, position = ?, company = ?, photo = ?, testimonial = ?, is_active = ? WHERE id = ?");
    $stmt->bind_param("sssssii", $name, $position, $company, $new_photo_path, $testimonial_content, $is_active, $testimonial_id);

    if ($stmt->execute()) {
        $_SESSION['testimonial_success'] = "Testimonial updated successfully!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['testimonial_error'] = "Error: " . $stmt->error;
        header("Location: edit.php?id=" . $testimonial_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Testimonial - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Custom styles for the form card */
        .form-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .alert {
            border-radius: 8px;
        }
        .card-header {
            background: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .current-photo {
            max-width: 150px;
            height: auto;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if (isset($_SESSION['testimonial_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['testimonial_success'];
                    unset($_SESSION['testimonial_success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['testimonial_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['testimonial_error'];
                    unset($_SESSION['testimonial_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Testimonial</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Testimonials
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <form action="edit.php?id=<?php echo $testimonial_id; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($testimonial['photo'] ?? ''); ?>">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($testimonial['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="position" class="form-label">Position (Optional)</label>
                        <input type="text" class="form-control" id="position" name="position" value="<?php echo htmlspecialchars($testimonial['position'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="company" class="form-label">Company (Optional)</label>
                        <input type="text" class="form-control" id="company" name="company" value="<?php echo htmlspecialchars($testimonial['company'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="photo" class="form-label">Photo (Optional)</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        <?php if ($testimonial['photo']): ?>
                            <div class="mt-2">
                                <img src="../../<?php echo htmlspecialchars($testimonial['photo']); ?>" alt="Current Photo" class="current-photo">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="remove_photo" name="remove_photo" value="1">
                                    <label class="form-check-label" for="remove_photo">Remove current photo</label>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="testimonial_content" class="form-label">Testimonial <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="testimonial_content" name="testimonial" rows="6" required><?php echo htmlspecialchars($testimonial['testimonial']); ?></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo $testimonial['is_active'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Testimonial
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html> 