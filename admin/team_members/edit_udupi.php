<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get member ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['team_member_error'] = "Invalid member ID.";
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
    $_SESSION['team_member_error'] = "Member not found.";
    header('Location: index.php');
    exit();
}

$member = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $education = trim($_POST['education']);
    $contact = trim($_POST['contact']);
    $portfolio = trim($_POST['portfolio']);
    $order_index = (int)$_POST['order_index'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validation
    if (empty($full_name)) {
        $_SESSION['team_member_error'] = "Full name is required.";
        header('Location: edit_udupi.php?id=' . $id);
        exit();
    }

    // Get current member data
    $current_photo = $member['photo'];
    $photo_path = $current_photo;

    // Handle file upload if new photo is provided
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
            header('Location: edit_udupi.php?id=' . $id);
            exit();
        }

        // Check file size (2MB limit)
        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            $_SESSION['team_member_error'] = "File size must be less than 2MB.";
            header('Location: edit_udupi.php?id=' . $id);
            exit();
        }

        // Generate unique filename
        $filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
            // Delete old photo if it exists
            if (!empty($current_photo)) {
                $old_photo_path = '../../' . $current_photo;
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
            $photo_path = 'uploads/team_photos/' . $filename;
        } else {
            $_SESSION['team_member_error'] = "Failed to upload photo.";
            header('Location: edit_udupi.php?id=' . $id);
            exit();
        }
    }

    try {
        // Update database
        $query = "UPDATE udupi_team_members SET full_name = ?, education = ?, photo = ?, contact = ?, portfolio = ?, order_index = ?, is_active = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssiii", $full_name, $education, $photo_path, $contact, $portfolio, $order_index, $is_active, $id);
        
        if ($stmt->execute()) {
            $_SESSION['team_member_success'] = "Udupi team member updated successfully.";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['team_member_error'] = "Failed to update Udupi team member.";
            header('Location: edit_udupi.php?id=' . $id);
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['team_member_error'] = "Database error: " . $e->getMessage();
        header('Location: edit_udupi.php?id=' . $id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Udupi Team Member - Admin Panel</title>
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
                        <h4 class="mb-0">Edit Udupi Team Member</h4>
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
                            <form action="edit_udupi.php?id=<?php echo $member['id']; ?>" method="POST" enctype="multipart/form-data">
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
                                    <button type="submit" class="btn btn-primary">Update Udupi Team Member</button>
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
                                     class="img-fluid rounded" style="max-height: 300px; object-fit: cover;">
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