<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Get member ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error_message'] = "Invalid member ID.";
    header('Location: index.php');
    exit();
}

// Fetch member data
$query = "SELECT * FROM udupi_team_members WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Member not found.";
    header('Location: index.php');
    exit();
}

$member = $result->fetch_assoc();
$page_title = "Edit Udupi Team Member";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include '../components/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php include '../components/topnavbar.php'; ?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $page_title; ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                        echo $_SESSION['success_message']; 
                        unset($_SESSION['success_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                        echo $_SESSION['error_message']; 
                        unset($_SESSION['error_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Edit Member Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="actions/update.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="<?php echo htmlspecialchars($member['full_name']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="education" class="form-label">Education</label>
                                        <textarea class="form-control" id="education" name="education" rows="3"><?php echo htmlspecialchars($member['education']); ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="contact" class="form-label">Contact (Email or Phone)</label>
                                        <input type="text" class="form-control" id="contact" name="contact" 
                                               value="<?php echo htmlspecialchars($member['contact']); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="portfolio" class="form-label">Portfolio URL</label>
                                        <input type="url" class="form-control" id="portfolio" name="portfolio" 
                                               value="<?php echo htmlspecialchars($member['portfolio']); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Photo</label>
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                        <div class="form-text">Leave empty to keep current photo. Recommended size: 400x500 pixels. Max file size: 2MB.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="order_index" class="form-label">Display Order</label>
                                        <input type="number" class="form-control" id="order_index" name="order_index" 
                                               value="<?php echo $member['order_index']; ?>" min="1">
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                                   <?php echo $member['is_active'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Update Member</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Current Photo</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img src="../../<?php echo htmlspecialchars($member['photo']); ?>" 
                                         alt="<?php echo htmlspecialchars($member['full_name']); ?>" 
                                         style="max-width: 100%; max-height: 300px; object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Preview</h5>
                            </div>
                            <div class="card-body">
                                <div id="photo-preview" class="text-center" style="display: none;">
                                    <img id="preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 300px; object-fit: cover;">
                                </div>
                                <div id="no-photo" class="text-center text-muted">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <p>No new photo selected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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