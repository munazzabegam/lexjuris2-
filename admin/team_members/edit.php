<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$team_member_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$team_member_id) {
    $_SESSION['team_member_error'] = "Invalid team member ID.";
    header("Location: index.php");
    exit();
}

// Fetch team member details
$stmt = $conn->prepare("SELECT * FROM team_members WHERE id = ?");
$stmt->bind_param("i", $team_member_id);
$stmt->execute();
$result = $stmt->get_result();
$team_member = $result->fetch_assoc();

if (!$team_member) {
    $_SESSION['team_member_error'] = "Team member not found.";
    header("Location: index.php");
    exit();
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $education = trim($_POST['education']);
    $contact = trim($_POST['contact']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $order_index = (int)$_POST['order_index'];
    $portfolio = trim($_POST['portfolio']);

    $photo_path = $team_member['photo']; // Keep existing photo by default

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../../uploads/team_photos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . "." . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = "uploads/team_photos/" . $file_name;
            // Delete old photo if it exists and is different from the new one
            if ($team_member['photo'] && file_exists(__DIR__ . '/../../' . $team_member['photo'])) {
                unlink(__DIR__ . '/../../' . $team_member['photo']);
            }
        } else {
            $_SESSION['team_member_error'] = "Error uploading new photo.";
            header("Location: edit.php?id=" . $team_member_id);
            exit();
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update team member
        $stmt = $conn->prepare("UPDATE team_members SET full_name = ?, education = ?, contact = ?, photo = ?, portfolio = ?, is_active = ?, order_index = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("sssssiii", $full_name, $education, $contact, $photo_path, $portfolio, $is_active, $order_index, $team_member_id);
        $stmt->execute();

        $conn->commit();
        $_SESSION['team_member_success'] = "Team member updated successfully.";
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['team_member_error'] = "Error updating team member: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team Member - Admin Panel</title>
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
        .current-photo {
            max-width: 100px;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
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
            <?php if (isset($_SESSION['team_member_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['team_member_error'];
                    unset($_SESSION['team_member_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Team Member</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Team Members
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($team_member['full_name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="education" class="form-label">Education</label>
                            <input type="text" class="form-control" id="education" name="education" value="<?php echo htmlspecialchars($team_member['education']); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($team_member['contact'] ?? ''); ?>" placeholder="Phone number or email">
                            <div class="form-text">Enter phone number or email address.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <div class="form-text">Upload a new profile photo to replace the current one.</div>
                            <?php if ($team_member['photo']): ?>
                                <p class="mt-2">Current Photo:</p>
                                <img src="../../<?php echo htmlspecialchars($team_member['photo']); ?>" alt="Current Photo" class="current-photo">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="portfolio" class="form-label">Portfolio Link (Optional)</label>
                            <input type="url" class="form-control" id="portfolio" name="portfolio" value="<?php echo htmlspecialchars($team_member['portfolio']); ?>">
                            <div class="form-text">Enter the full URL including https://</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_index" class="form-label">Order Index</label>
                            <input type="number" class="form-control" id="order_index" name="order_index" value="<?php echo htmlspecialchars($team_member['order_index']); ?>">
                            <div class="form-text">Lower numbers appear first.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $team_member['is_active'] ? 'checked' : ''; ?> >
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save me-2"></i>Update Team Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 