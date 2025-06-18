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

// Fetch existing social links
$social_links = [];
$social_stmt = $conn->prepare("SELECT * FROM team_social_links WHERE team_id = ? AND is_active = 1");
$social_stmt->bind_param("i", $team_member_id);
$social_stmt->execute();
$social_result = $social_stmt->get_result();
while ($link = $social_result->fetch_assoc()) {
    $social_links[] = $link;
}

if (!$team_member) {
    $_SESSION['team_member_error'] = "Team member not found.";
    header("Location: index.php");
    exit();
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $education = trim($_POST['education']);
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
        $stmt = $conn->prepare("UPDATE team_members SET full_name = ?, education = ?, photo = ?, portfolio = ?, is_active = ?, order_index = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("ssssiii", $full_name, $education, $photo_path, $portfolio, $is_active, $order_index, $team_member_id);
        $stmt->execute();

        // Handle social links
        // First, deactivate all existing social links
        $deactivate_stmt = $conn->prepare("UPDATE team_social_links SET is_active = 0 WHERE team_id = ?");
        $deactivate_stmt->bind_param("i", $team_member_id);
        $deactivate_stmt->execute();

        // Then, add new social links
        if (isset($_POST['social_platforms']) && is_array($_POST['social_platforms'])) {
            $platforms = $_POST['social_platforms'];
            $urls = $_POST['social_urls'];
            
            for ($i = 0; $i < count($platforms); $i++) {
                if (!empty($platforms[$i]) && !empty($urls[$i])) {
                    // Check if this platform already exists for this team member
                    $check_stmt = $conn->prepare("SELECT id FROM team_social_links WHERE team_id = ? AND platform = ? AND is_active = 0");
                    $check_stmt->bind_param("is", $team_member_id, $platforms[$i]);
                    $check_stmt->execute();
                    $existing = $check_stmt->get_result()->fetch_assoc();

                    if ($existing) {
                        // Update existing record
                        $update_stmt = $conn->prepare("UPDATE team_social_links SET url = ?, is_active = 1 WHERE id = ?");
                        $update_stmt->bind_param("si", $urls[$i], $existing['id']);
                        $update_stmt->execute();
                    } else {
                        // Insert new record
                        $insert_stmt = $conn->prepare("INSERT INTO team_social_links (team_id, platform, url) VALUES (?, ?, ?)");
                        $insert_stmt->bind_param("iss", $team_member_id, $platforms[$i], $urls[$i]);
                        $insert_stmt->execute();
                    }
                }
            }
        }

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
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <div class="form-text">Upload a new profile photo to replace the current one.</div>
                            <?php if ($team_member['photo']): ?>
                                <p class="mt-2">Current Photo:</p>
                                <img src="../../<?php echo htmlspecialchars($team_member['photo']); ?>" alt="Current Photo" class="current-photo">
                            <?php endif; ?>
                        </div>
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
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $team_member['is_active'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links Section -->
                    <div class="mb-4">
                        <h5 class="mb-3">Social Links</h5>
                        <div id="social-links-container">
                            <?php
                            $platforms = ['LinkedIn', 'Twitter', 'Email', 'Facebook', 'Instagram', 'Other'];
                            $existing_links = [];
                            foreach ($social_links as $link) {
                                $existing_links[$link['platform']] = $link['url'];
                            }
                            
                            foreach ($platforms as $platform) {
                                $url = isset($existing_links[$platform]) ? $existing_links[$platform] : '';
                                ?>
                                <div class="row mb-2 social-link-row">
                                    <div class="col-md-4">
                                        <select class="form-select" name="social_platforms[]">
                                            <option value="">Select Platform</option>
                                            <?php foreach ($platforms as $p): ?>
                                                <option value="<?php echo $p; ?>" <?php echo $platform === $p ? 'selected' : ''; ?>>
                                                    <?php echo $p; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="social_urls[]" 
                                               placeholder="Enter URL" value="<?php echo htmlspecialchars($url); ?>">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-social-link" <?php echo empty($url) ? 'style="display:none;"' : ''; ?>>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-social-link">
                            <i class="fas fa-plus me-2"></i>Add Another Social Link
                        </button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('social-links-container');
            const addButton = document.getElementById('add-social-link');
            const platforms = <?php echo json_encode($platforms); ?>;

            // Function to create a new social link row
            function createSocialLinkRow() {
                const row = document.createElement('div');
                row.className = 'row mb-2 social-link-row';
                row.innerHTML = `
                    <div class="col-md-4">
                        <select class="form-select" name="social_platforms[]">
                            <option value="">Select Platform</option>
                            ${platforms.map(p => `<option value="${p}">${p}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="social_urls[]" placeholder="Enter URL">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-social-link">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                return row;
            }

            // Add new social link row
            addButton.addEventListener('click', function() {
                container.appendChild(createSocialLinkRow());
            });

            // Remove social link row
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-social-link')) {
                    const row = e.target.closest('.social-link-row');
                    const urlInput = row.querySelector('input[name="social_urls[]"]');
                    const platformSelect = row.querySelector('select[name="social_platforms[]"]');
                    
                    // Clear the values instead of removing the row
                    urlInput.value = '';
                    platformSelect.value = '';
                    e.target.closest('.remove-social-link').style.display = 'none';
                }
            });

            // Show/hide remove button based on input
            container.addEventListener('input', function(e) {
                if (e.target.matches('input[name="social_urls[]"]')) {
                    const row = e.target.closest('.social-link-row');
                    const removeButton = row.querySelector('.remove-social-link');
                    removeButton.style.display = e.target.value ? 'block' : 'none';
                }
            });
        });
    </script>
</body>
</html> 