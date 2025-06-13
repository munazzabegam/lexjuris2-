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
    // If no contact entry exists, redirect to create one (or handle differently)
    // For simplicity, we'll create a dummy entry if none exists
    $conn->query("INSERT INTO contact (phone) VALUES ('N/A')");
    $result = $conn->query("SELECT * FROM contact LIMIT 1");
    $contact = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contact - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .view-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .view-card h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 0.75rem;
        }
        .view-group {
            margin-bottom: 1rem;
        }
        .view-label {
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 0.25rem;
        }
        .view-value {
            color: #333;
            font-size: 1rem;
            margin-bottom: 0.75rem;
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
            <?php if (isset($_SESSION['contact_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['contact_success'];
                    unset($_SESSION['contact_success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

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
                        <h4 class="mb-0">Manage Contact Information</h4>
                    </div>
                </div>
            </div>

            <div class="view-card">
                <div class="view-group">
                    <span class="view-label">Phone Number:</span>
                    <span class="view-value"><?php echo htmlspecialchars($contact['phone']); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Status:</span>
                    <span class="view-value">
                        <span class="badge <?php echo $contact['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo $contact['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </span>
                </div>
                <div class="view-group">
                    <span class="view-label">Created At:</span>
                    <span class="view-value"><?php echo date('Y-m-d H:i:s', strtotime($contact['created_at'])); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Last Updated:</span>
                    <span class="view-value"><?php echo $contact['updated_at'] ? date('Y-m-d H:i:s', strtotime($contact['updated_at'])) : 'N/A'; ?></span>
                </div>

                <div class="mt-4">
                    <a href="edit.php" class="btn btn-primary btn-action">
                        <i class="fas fa-edit me-2"></i> Edit Contact Info
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 