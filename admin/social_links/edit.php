<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$social_link_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$social_link_id) {
    $_SESSION['social_link_error'] = "Invalid social link ID";
    header("Location: index.php");
    exit();
}

// Fetch social link details
$stmt = $conn->prepare("SELECT * FROM social_links WHERE id = ?");
$stmt->bind_param("i", $social_link_id);
$stmt->execute();
$result = $stmt->get_result();
$social_link = $result->fetch_assoc();

if (!$social_link) {
    $_SESSION['social_link_error'] = "Social link not found";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = $_POST['platform'];
    $url = trim($_POST['url']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $order_index = (int)$_POST['order_index'];

    // Validate URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $_SESSION['social_link_error'] = "Please enter a valid URL.";
    } else {
        $stmt = $conn->prepare("UPDATE social_links SET platform = ?, url = ?, is_active = ?, order_index = ? WHERE id = ?");
        $stmt->bind_param("ssiii", $platform, $url, $is_active, $order_index, $social_link_id);

        if ($stmt->execute()) {
            $_SESSION['social_link_success'] = "Social link updated successfully.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['social_link_error'] = "Error updating social link: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Social Link - Admin Panel</title>
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
            <?php if (isset($_SESSION['social_link_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['social_link_error'];
                    unset($_SESSION['social_link_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Social Link</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Social Links
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="platform" class="form-label">Platform</label>
                            <select class="form-select" id="platform" name="platform" required>
                                <option value="">Select Platform</option>
                                <option value="Facebook" <?php echo $social_link['platform'] === 'Facebook' ? 'selected' : ''; ?>>Facebook</option>
                                <option value="Twitter" <?php echo $social_link['platform'] === 'Twitter' ? 'selected' : ''; ?>>Twitter</option>
                                <option value="Instagram" <?php echo $social_link['platform'] === 'Instagram' ? 'selected' : ''; ?>>Instagram</option>
                                <option value="LinkedIn" <?php echo $social_link['platform'] === 'LinkedIn' ? 'selected' : ''; ?>>LinkedIn</option>
                                <option value="YouTube" <?php echo $social_link['platform'] === 'YouTube' ? 'selected' : ''; ?>>YouTube</option>
                                <option value="GitHub" <?php echo $social_link['platform'] === 'GitHub' ? 'selected' : ''; ?>>GitHub</option>
                                <option value="Other" <?php echo $social_link['platform'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="url" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($social_link['url']); ?>" required>
                            <div class="form-text">Enter the full URL including https://</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_index" class="form-label">Order Index</label>
                            <input type="number" class="form-control" id="order_index" name="order_index" value="<?php echo htmlspecialchars($social_link['order_index']); ?>">
                            <div class="form-text">Lower numbers appear first.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $social_link['is_active'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save me-2"></i>Update Social Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 