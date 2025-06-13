<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$name = '';
$position = '';
$company = '';
$testimonial_content = '';
$is_active = true;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? NULL; // Make optional
    $company = $_POST['company'] ?? NULL; // Make optional
    $testimonial_content = $_POST['testimonial'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $user_id = $_SESSION['user_id']; // From logged-in user

    // Handle photo upload
    $photo_path = NULL;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/testimonials/";
        if (!is_dir(__DIR__ . '/../../' . $target_dir)) {
            mkdir(__DIR__ . '/../../' . $target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid('testimonial_') . '.' . $file_extension;
        $target_file = __DIR__ . '/../../' . $target_dir . $new_file_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = $target_dir . $new_file_name;
        } else {
            $_SESSION['testimonial_error'] = "Failed to upload photo.";
            header("Location: create.php");
            exit();
        }
    }

    // Find the current maximum order_index and add 1
    $stmt_order = $conn->prepare("SELECT MAX(order_index) AS max_order FROM testimonials");
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();
    $row_order = $result_order->fetch_assoc();
    $new_order_index = ($row_order['max_order'] !== null) ? $row_order['max_order'] + 1 : 1;

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO testimonials (user_id, name, position, company, photo, testimonial, order_index, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssi", $user_id, $name, $position, $company, $photo_path, $testimonial_content, $new_order_index, $is_active);

    if ($stmt->execute()) {
        $_SESSION['testimonial_success'] = "Testimonial added successfully!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['testimonial_error'] = "Error: " . $stmt->error;
        header("Location: create.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Testimonial - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Custom styles for the form card */
        .form-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .alert {
            border-radius: 8px;
        }
        .card-header {
            background: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if (isset($_SESSION['testimonial_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['testimonial_success'];
                    unset($_SESSION['testimonial_success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['testimonial_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['testimonial_error'];
                    unset($_SESSION['testimonial_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Add New Testimonial</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Testimonials
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <form action="create.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="position" class="form-label">Position (Optional)</label>
                        <input type="text" class="form-control" id="position" name="position" value="<?php echo htmlspecialchars($position); ?>">
                    </div>
                    <div class="form-group">
                        <label for="company" class="form-label">Company (Optional)</label>
                        <input type="text" class="form-control" id="company" name="company" value="<?php echo htmlspecialchars($company); ?>">
                    </div>
                    <div class="form-group">
                        <label for="photo" class="form-label">Photo (Optional)</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="testimonial" class="form-label">Testimonial <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="testimonial" name="testimonial" rows="6" required><?php echo htmlspecialchars($testimonial_content); ?></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo $is_active ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add Testimonial
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html> 