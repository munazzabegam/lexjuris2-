<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Check if order_index column exists in sub_junior_team_members table, if not add it
$check_column = $conn->query("SHOW COLUMNS FROM sub_junior_team_members LIKE 'order_index'");
if ($check_column->num_rows === 0) {
    $conn->query("ALTER TABLE sub_junior_team_members ADD COLUMN order_index INT DEFAULT 0");
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
        $target_dir = __DIR__ . "/../../uploads/team_photos/"; // Assuming sub-junior photos are in the same directory
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . "." . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = "uploads/team_photos/" . $file_name;
        } else {
            $_SESSION['sub_junior_member_error'] = "Error uploading photo.";
            header("Location: create_sub_junior.php");
            exit();
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert sub_junior team member
        $stmt = $conn->prepare("INSERT INTO sub_junior_team_members (full_name, position, bio, photo, portfolio, is_active, order_index) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $full_name, $position, $bio, $photo_path, $portfolio, $is_active, $order_index);
        $stmt->execute();
        $sub_junior_member_id = $conn->insert_id;

        // Handle social links
        if (isset($_POST['social_links']) && is_array($_POST['social_links'])) {
            $stmt_social = $conn->prepare("INSERT INTO sub_junior_social_links (sub_junior_id, platform, url) VALUES (?, ?, ?)");
            foreach ($_POST['social_links'] as $link) {
                $platform = trim($link['platform']);
                $url = trim($link['url']);
                if (!empty($platform) && !empty($url)) {
                    $stmt_social->bind_param("iss", $sub_junior_member_id, $platform, $url);
                    $stmt_social->execute();
                }
            }
            $stmt_social->close();
        }

        $conn->commit();
        $_SESSION['sub_junior_member_success'] = "Sub Junior Team member added successfully.";
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['sub_junior_member_error'] = "Error adding sub junior team member: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sub Junior Team Member - Admin Panel</title>
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
            <?php if (isset($_SESSION['sub_junior_member_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['sub_junior_member_error'];
                    unset($_SESSION['sub_junior_member_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Add New Sub Junior Team Member</h4>
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
                            <div class="form-text">Upload a profile photo for the sub junior team member.</div>
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

                    <hr class="my-4">
                    <h5>Social Links</h5>
                    <div id="social-links-container">
                        <!-- Social links will be added here by JavaScript -->
                    </div>
                    <button type="button" class="btn btn-info btn-sm mt-2" id="add-social-link">
                        <i class="fas fa-plus-circle me-2"></i>Add Social Link
                    </button>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save me-2"></i>Add Sub Junior Team Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const socialLinksContainer = document.getElementById('social-links-container');
            const addSocialLinkButton = document.getElementById('add-social-link');
            const availablePlatforms = ['LinkedIn', 'Twitter', 'Email', 'Facebook', 'Instagram', 'GitHub', 'Other'];

            // Function to validate email format
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Function to format email URL
            function formatEmailUrl(email) {
                return `mailto:${email}`;
            }

            // Function to get platforms already selected in the form
            function getSelectedPlatforms() {
                const selected = new Set();
                socialLinksContainer.querySelectorAll('.social-link-row select[name^="social_links["]').forEach(select => {
                    if (select.value) {
                        selected.add(select.value);
                    }
                });
                return selected;
            }

            // Function to update platform options in all dropdowns
            function updatePlatformOptions() {
                const currentSelected = getSelectedPlatforms();
                socialLinksContainer.querySelectorAll('.social-link-row select[name^="social_links["]').forEach(select => {
                    const currentVal = select.value;
                    select.innerHTML = '<option value="">Select Platform</option>'; // Clear existing options
                    availablePlatforms.forEach(platform => {
                        const option = document.createElement('option');
                        option.value = platform;
                        option.textContent = platform;
                        if (currentVal === platform) {
                            option.selected = true;
                        } else if (currentSelected.has(platform)) {
                            option.disabled = true; // Disable already selected options in other dropdowns
                        }
                        select.appendChild(option);
                    });
                });

                // Show/hide add button based on available platforms
                const allPlatformsSelected = currentSelected.size === availablePlatforms.length;
                addSocialLinkButton.style.display = allPlatformsSelected ? 'none' : 'inline-block';
            }

            // Function to add a new social link row
            function addSocialLinkRow(platform = '', url = '') {
                const rowId = Date.now(); // Unique ID for the row
                const row = document.createElement('div');
                row.classList.add('row', 'mb-3', 'g-2', 'social-link-row');
                row.setAttribute('data-id', rowId);

                let platformOptionsHtml = '<option value="">Select Platform</option>';
                availablePlatforms.forEach(p => {
                    platformOptionsHtml += `<option value="${p}" ${platform === p ? 'selected' : ''}>${p}</option>`;
                });

                row.innerHTML = `
                    <div class="col-md-4">
                        <select class="form-select" name="social_links[${rowId}][platform]" required>
                            ${platformOptionsHtml}
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control social-link-url" name="social_links[${rowId}][url]" value="${url}" placeholder="Enter URL or value" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-social-link" title="Remove Social Link" style="display: ${url ? 'block' : 'none'};">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

                socialLinksContainer.appendChild(row);
                updatePlatformOptions(); // Update options across all dropdowns

                // Add event listener for platform change to update placeholder and validation
                row.querySelector('select').addEventListener('change', function() {
                    const urlInput = this.closest('.social-link-row').querySelector('.social-link-url');
                    const invalidFeedback = this.closest('.social-link-row').querySelector('.invalid-feedback');
                    const selectedPlatform = this.value;
                    
                    // Clear any previous validation
                    urlInput.classList.remove('is-invalid');
                    invalidFeedback.textContent = '';

                    switch (selectedPlatform) {
                        case 'Email':
                            urlInput.placeholder = 'Enter email address (e.g., john@example.com)';
                            urlInput.type = 'email';
                            break;
                        case 'Phone':
                            urlInput.placeholder = 'Enter phone number (e.g., +1234567890)';
                            urlInput.type = 'tel';
                            break;
                        case 'WhatsApp':
                            urlInput.placeholder = 'Enter WhatsApp number (e.g., +1234567890)';
                            urlInput.type = 'tel';
                            break;
                        case 'Website':
                            urlInput.placeholder = 'Enter website URL (e.g., https://example.com)';
                            urlInput.type = 'url';
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
                            urlInput.placeholder = 'Enter URL or value';
                            urlInput.type = 'text'; // Default to text for generic input
                    }
                    // Re-validate on platform change if there's a value
                    if (urlInput.value) {
                        validateUrlInput(urlInput);
                    }
                });

                // Add event listener for URL input changes (for validation and remove button)
                row.querySelector('.social-link-url').addEventListener('input', function() {
                    validateUrlInput(this);
                });

                // Trigger change to set initial placeholder and validation if platform is pre-selected
                if (platform) {
                    row.querySelector('select').dispatchEvent(new Event('change'));
                }
                // For initial rows, also set remove button visibility based on initial URL value
                if (url) {
                    row.querySelector('.remove-social-link').style.display = 'block';
                }
            }

            // Function to validate URL input
            function validateUrlInput(urlInput) {
                const row = urlInput.closest('.social-link-row');
                const platformSelect = row.querySelector('select[name^="social_links["]');
                const invalidFeedback = row.querySelector('.invalid-feedback');
                const removeButton = row.querySelector('.remove-social-link');
                const platform = platformSelect.value;

                // Show/hide remove button
                removeButton.style.display = urlInput.value ? 'block' : 'none';

                // Validate email format if platform is Email
                if (platform === 'Email') {
                    if (urlInput.value && !isValidEmail(urlInput.value)) {
                        urlInput.classList.add('is-invalid');
                        invalidFeedback.textContent = 'Please enter a valid email address.';
                    } else {
                        urlInput.classList.remove('is-invalid');
                        invalidFeedback.textContent = '';
                    }
                } else {
                    // For other URL types, ensure it's not marked as invalid if empty
                    urlInput.classList.remove('is-invalid');
                    invalidFeedback.textContent = '';
                }
            }

            // Event listener for "Add Social Link" button
            addSocialLinkButton.addEventListener('click', function() {
                const selectedPlatforms = getSelectedPlatforms();
                if (selectedPlatforms.size < availablePlatforms.length) {
                    addSocialLinkRow();
                } else {
                    alert('All available social media platforms have been added.');
                }
            });

            // Event listener for removing social link rows (delegated)
            socialLinksContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-social-link')) {
                    const rowToRemove = e.target.closest('.social-link-row');
                    const urlInput = rowToRemove.querySelector('.social-link-url');
                    const platformSelect = rowToRemove.querySelector('select[name^="social_links["]');
                    
                    // Clear values and remove validation before removal
                    urlInput.value = '';
                    urlInput.classList.remove('is-invalid');
                    rowToRemove.querySelector('.invalid-feedback').textContent = '';
                    platformSelect.value = ''; // Reset selected platform

                    rowToRemove.remove();
                    updatePlatformOptions(); // Re-enable platform option and update add button visibility after removal
                }
            });

            // Handle form submission for final validation and email formatting
            document.querySelector('form').addEventListener('submit', function(e) {
                let isValidForm = true;
                socialLinksContainer.querySelectorAll('.social-link-row').forEach(row => {
                    const platformSelect = row.querySelector('select[name^="social_links["]');
                    const urlInput = row.querySelector('.social-link-url');
                    const invalidFeedback = row.querySelector('.invalid-feedback');

                    // Perform validation for each row before submission
                    if (platformSelect.value === 'Email') {
                        if (urlInput.value && !isValidEmail(urlInput.value)) {
                            urlInput.classList.add('is-invalid');
                            invalidFeedback.textContent = 'Please enter a valid email address.';
                            isValidForm = false;
                        } else {
                            urlInput.classList.remove('is-invalid');
                            invalidFeedback.textContent = '';
                        }
                    }
                    // If any input is invalid and not an email, it should already have .is-invalid from type="url" validation
                    if (urlInput.classList.contains('is-invalid')) {
                        isValidForm = false;
                    }
                });

                if (!isValidForm) {
                    e.preventDefault(); // Prevent form submission if there are validation errors
                    alert('Please correct the errors in the social links before submitting.');
                } else {
                    // Format email URLs before submission if valid
                    socialLinksContainer.querySelectorAll('.social-link-row').forEach(row => {
                        const platformSelect = row.querySelector('select[name^="social_links["]');
                        const urlInput = row.querySelector('.social-link-url');
                        if (platformSelect.value === 'Email' && urlInput.value && !urlInput.value.startsWith('mailto:')) {
                            urlInput.value = formatEmailUrl(urlInput.value);
                        }
                    });
                }
            });

            // Initial call to update options and add button visibility
            updatePlatformOptions();
        });
    </script>
</body>
</html> 