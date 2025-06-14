<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Check if order_index column exists in team_members table, if not add it
$check_column = $conn->query("SHOW COLUMNS FROM team_members LIKE 'order_index'");
if ($check_column->num_rows === 0) {
    $conn->query("ALTER TABLE team_members ADD COLUMN order_index INT DEFAULT 0");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $position = trim($_POST['position']);
    $bio = trim($_POST['bio']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $order_index = (int)$_POST['order_index'];
    $portfolio = trim($_POST['portfolio']);
    $photo_path = null;

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
        } else {
            $_SESSION['team_member_error'] = "Error uploading photo.";
            header("Location: create.php");
            exit();
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert team member
        $stmt = $conn->prepare("INSERT INTO team_members (full_name, position, bio, photo, portfolio, is_active, order_index) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $full_name, $position, $bio, $photo_path, $portfolio, $is_active, $order_index);
        $stmt->execute();
        $team_member_id = $conn->insert_id;

        // Handle social links
        if (isset($_POST['social_platforms']) && is_array($_POST['social_platforms'])) {
            $platforms = $_POST['social_platforms'];
            $urls = $_POST['social_urls'];
            
            for ($i = 0; $i < count($platforms); $i++) {
                if (!empty($platforms[$i]) && !empty($urls[$i])) {
                    $insert_stmt = $conn->prepare("INSERT INTO team_social_links (team_id, platform, url) VALUES (?, ?, ?)");
                    $insert_stmt->bind_param("iss", $team_member_id, $platforms[$i], $urls[$i]);
                    $insert_stmt->execute();
                }
            }
        }

        $conn->commit();
        $_SESSION['team_member_success'] = "Team member added successfully.";
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['team_member_error'] = "Error adding team member: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Team Member - Admin Panel</title>
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
                        <h4 class="mb-0">Add New Team Member</h4>
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
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="5"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                            <div class="form-text">Upload a profile photo for the team member.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="portfolio" class="form-label">Portfolio Link (Optional)</label>
                            <input type="url" class="form-control" id="portfolio" name="portfolio">
                            <div class="form-text">Enter the full URL including https://</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_index" class="form-label">Order Index</label>
                            <input type="number" class="form-control" id="order_index" name="order_index" value="0">
                            <div class="form-text">Lower numbers appear first.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links Section -->
                    <div class="mb-4">
                        <h5 class="mb-3">Social Links</h5>
                        <div id="social-links-container">
                            <div class="row mb-2 social-link-row">
                                <div class="col-md-4">
                                    <select class="form-select" name="social_platforms[]">
                                        <option value="">Select Platform</option>
                                        <option value="LinkedIn">LinkedIn</option>
                                        <option value="Twitter">Twitter</option>
                                        <option value="Email">Email</option>
                                        <option value="Facebook">Facebook</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="GitHub">GitHub</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="social_urls[]" placeholder="Enter URL">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-social-link" style="display:none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-social-link">
                            <i class="fas fa-plus me-2"></i>Add Social Link
                        </button>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save me-2"></i>Add Team Member
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
            const platforms = ['LinkedIn', 'Twitter', 'Email', 'Facebook', 'Instagram', 'GitHub', 'Other'];

            // Function to validate email format
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Function to format email URL
            function formatEmailUrl(email) {
                return `mailto:${email}`;
            }

            // Function to get selected platforms
            function getSelectedPlatforms() {
                const selects = container.querySelectorAll('select[name="social_platforms[]"]');
                return Array.from(selects).map(select => select.value).filter(value => value !== '');
            }

            // Function to update available platforms
            function updateAvailablePlatforms() {
                const selectedPlatforms = getSelectedPlatforms();
                const selects = container.querySelectorAll('select[name="social_platforms[]"]');
                
                selects.forEach(select => {
                    const currentValue = select.value;
                    Array.from(select.options).forEach(option => {
                        if (option.value === '') return; // Skip the default option
                        if (option.value === currentValue) return; // Skip the currently selected option
                        
                        // Disable if platform is selected elsewhere
                        option.disabled = selectedPlatforms.includes(option.value);
                    });
                });

                // Show/hide add button based on available platforms
                const allPlatformsSelected = selectedPlatforms.length === platforms.length;
                addButton.style.display = allPlatformsSelected ? 'none' : 'inline-block';
            }

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
                        <div class="invalid-feedback">Please enter a valid email address.</div>
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
                const selectedPlatforms = getSelectedPlatforms();
                if (selectedPlatforms.length < platforms.length) {
                    container.appendChild(createSocialLinkRow());
                    updateAvailablePlatforms();
                }
            });

            // Handle platform selection changes
            container.addEventListener('change', function(e) {
                if (e.target.matches('select[name="social_platforms[]"]')) {
                    const row = e.target.closest('.social-link-row');
                    const urlInput = row.querySelector('input[name="social_urls[]"]');
                    const platform = e.target.value;
                    
                    // Clear any previous validation
                    urlInput.classList.remove('is-invalid');
                    
                    // Update placeholder and input type based on platform
                    switch (platform) {
                        case 'Email':
                            urlInput.placeholder = 'Enter email address (e.g., john@example.com)';
                            urlInput.type = 'email';
                            break;
                        case 'LinkedIn':
                            urlInput.placeholder = 'Enter LinkedIn profile URL';
                            urlInput.type = 'url';
                            break;
                        case 'Twitter':
                            urlInput.placeholder = 'Enter Twitter profile URL';
                            urlInput.type = 'url';
                            break;
                        case 'Facebook':
                            urlInput.placeholder = 'Enter Facebook profile URL';
                            urlInput.type = 'url';
                            break;
                        case 'Instagram':
                            urlInput.placeholder = 'Enter Instagram profile URL';
                            urlInput.type = 'url';
                            break;
                        case 'GitHub':
                            urlInput.placeholder = 'Enter GitHub profile URL';
                            urlInput.type = 'url';
                            break;
                        case 'Other':
                            urlInput.placeholder = 'Enter URL';
                            urlInput.type = 'url';
                            break;
                        default:
                            urlInput.placeholder = 'Enter URL';
                            urlInput.type = 'text';
                    }

                    updateAvailablePlatforms();
                }
            });

            // Handle URL input changes
            container.addEventListener('input', function(e) {
                if (e.target.matches('input[name="social_urls[]"]')) {
                    const row = e.target.closest('.social-link-row');
                    const platformSelect = row.querySelector('select[name="social_platforms[]"]');
                    const removeButton = row.querySelector('.remove-social-link');
                    const platform = platformSelect.value;

                    // Show/hide remove button
                    removeButton.style.display = e.target.value ? 'block' : 'none';

                    // Validate email format
                    if (platform === 'Email') {
                        if (e.target.value && !isValidEmail(e.target.value)) {
                            e.target.classList.add('is-invalid');
                        } else {
                            e.target.classList.remove('is-invalid');
                        }
                    }
                }
            });

            // Handle form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const emailInputs = container.querySelectorAll('select[name="social_platforms[]"]');
                let isValid = true;

                emailInputs.forEach((select, index) => {
                    if (select.value === 'Email') {
                        const urlInput = select.closest('.social-link-row').querySelector('input[name="social_urls[]"]');
                        if (urlInput.value && !isValidEmail(urlInput.value)) {
                            urlInput.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please enter valid email addresses for all email fields.');
                } else {
                    // Format email URLs before submission
                    emailInputs.forEach((select) => {
                        if (select.value === 'Email') {
                            const urlInput = select.closest('.social-link-row').querySelector('input[name="social_urls[]"]');
                            if (urlInput.value) {
                                urlInput.value = formatEmailUrl(urlInput.value);
                            }
                        }
                    });
                }
            });

            // Remove social link row
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-social-link')) {
                    const row = e.target.closest('.social-link-row');
                    const urlInput = row.querySelector('input[name="social_urls[]"]');
                    const platformSelect = row.querySelector('select[name="social_platforms[]"]');
                    
                    // Clear the values
                    urlInput.value = '';
                    platformSelect.value = '';
                    
                    // Remove the row if it's not the last one
                    const rows = container.querySelectorAll('.social-link-row');
                    if (rows.length > 1) {
                        row.remove();
                    }
                    
                    updateAvailablePlatforms();
                }
            });

            // Initialize available platforms
            updateAvailablePlatforms();
        });
    </script>
</body>
</html> 