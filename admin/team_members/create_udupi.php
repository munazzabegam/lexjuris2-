<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $education = trim($_POST['education']);
    $contact = trim($_POST['contact']);
    $portfolio = trim($_POST['portfolio']);
    $order_index = isset($_POST['order_index']) ? (int)$_POST['order_index'] : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validation
    if (empty($full_name)) {
        $_SESSION['team_member_error'] = "Full name is required.";
        header("Location: create_udupi.php");
        exit();
    }

    // Handle file upload
    $photo_path = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/team_photos/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['photo']['name']);
        $file_extension = strtolower($file_info['extension']);
        
        // Check file type
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['team_member_error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: create_udupi.php");
            exit();
        }

        // Check file size (2MB limit)
        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            $_SESSION['team_member_error'] = "File size must be less than 2MB.";
            header("Location: create_udupi.php");
            exit();
        }

        // Generate unique filename
        $filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
            $photo_path = 'uploads/team_photos/' . $filename;
        } else {
            $_SESSION['team_member_error'] = "Failed to upload photo.";
            header("Location: create_udupi.php");
            exit();
        }
    } else {
        $_SESSION['team_member_error'] = "Photo is required.";
        header("Location: create_udupi.php");
        exit();
    }

    try {
        // Insert into database
        $query = "INSERT INTO udupi_team_members (full_name, education, photo, contact, portfolio, order_index, is_active) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssii", $full_name, $education, $photo_path, $contact, $portfolio, $order_index, $is_active);
        
        if ($stmt->execute()) {
            $_SESSION['team_member_success'] = "Udupi team member added successfully.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['team_member_error'] = "Failed to add Udupi team member.";
            header("Location: create_udupi.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['team_member_error'] = "Database error: " . $e->getMessage();
        header("Location: create_udupi.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Udupi Team Member - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Add New Udupi Team Member</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['team_member_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['team_member_error'];
                    unset($_SESSION['team_member_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="create_udupi.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="education" class="form-label">Education</label>
                                    <textarea class="form-control" id="education" name="education" rows="3"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact (Email or Phone)</label>
                                    <input type="text" class="form-control" id="contact" name="contact">
                                </div>

                                <div class="mb-3">
                                    <label for="portfolio" class="form-label">Portfolio URL</label>
                                    <input type="url" class="form-control" id="portfolio" name="portfolio">
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo *</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                                    <div class="form-text">Recommended size: 400x500 pixels. Max file size: 2MB.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="order_index" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="order_index" name="order_index" value="1" min="1">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Add Udupi Team Member</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Preview</h5>
                        </div>
                        <div class="card-body">
                            <div id="photo-preview" class="text-center" style="display: none;">
                                <img id="preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 300px; object-fit: cover;">
                            </div>
                            <div id="no-photo" class="text-center text-muted">
                                <i class="fas fa-image fa-3x mb-3"></i>
                                <p>No photo selected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>

    <script>
        // Photo preview functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('photo-preview');
            const previewImg = document.getElementById('preview-img');
            const noPhoto = document.getElementById('no-photo');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                    noPhoto.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                noPhoto.style.display = 'block';
            }
        });
    </script>
</body>
</html> 