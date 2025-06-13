<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$contact = null;
$result = $conn->query("SELECT * FROM contact LIMIT 1");
if ($result->num_rows > 0) {
    $contact = $result->fetch_assoc();
} else {
    $_SESSION['contact_error'] = "No contact entry found. Please add one.";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Assume there's only one contact entry, so we always update id = 1 or the existing one.
    // In a real application, you might manage multiple contact types or ensure only one exists.
    $stmt = $conn->prepare("UPDATE contact SET phone = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("sii", $phone, $is_active, $contact['id']);

    if ($stmt->execute()) {
        $_SESSION['contact_success'] = "Contact information updated successfully.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['contact_error'] = "Error updating contact information: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact Info - Admin Panel</title>
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
            <?php if (isset($_SESSION['contact_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['contact_error'];
                    unset($_SESSION['contact_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Contact Information</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Contact Info
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $contact['is_active'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save me-2"></i>Update Contact Info
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 