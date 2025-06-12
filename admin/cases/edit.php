<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get case ID from URL
$case_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($case_id <= 0) {
    $_SESSION['case_error'] = "Invalid case ID.";
    header("Location: index.php");
    exit();
}

// Get case details
$stmt = $conn->prepare("
    SELECT c.*, a.username as author_name 
    FROM cases c 
    LEFT JOIN admin_users a ON c.author_id = a.id 
    WHERE c.id = ?
");
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();
$case = $result->fetch_assoc();

if (!$case) {
    $_SESSION['case_error'] = "Case not found.";
    header("Location: index.php");
    exit();
}

$case_success = $_SESSION['case_success'] ?? null;
unset($_SESSION['case_success']);
$case_error = $_SESSION['case_error'] ?? null;
unset($_SESSION['case_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Case - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .edit-case-form {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem 0.75rem;
            color: #333;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #bc841c;
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.15);
        }

        /* Description field specific styles */
        textarea.form-control {
            min-height: 200px;
            line-height: 1.6;
            font-size: 0.95rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            resize: vertical;
        }

        textarea.form-control:focus {
            background-color: #fff;
            border-color: #bc841c;
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.15);
        }

        .description-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }

        .description-wrapper .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .description-wrapper .form-text {
            position: absolute;
            bottom: -1.5rem;
            right: 0;
            font-size: 0.8rem;
            color: #6c757d;
        }

        .form-select {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem 0.75rem;
            color: #333;
            transition: border-color 0.15s ease-in-out;
        }

        .form-select:focus {
            border-color: #bc841c;
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.15);
        }

        .form-text {
            color: #666;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 4px;
        }

        .btn-action i {
            margin-right: 0.5rem;
        }

        .btn-primary {
            background-color: #bc841c;
            border-color: #bc841c;
        }

        .btn-primary:hover {
            background-color: #a67316;
            border-color: #a67316;
        }

        .btn-outline-primary {
            color: #bc841c;
            border-color: #bc841c;
        }

        .btn-outline-primary:hover {
            background-color: #bc841c;
            border-color: #bc841c;
        }
    </style>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
            <?php if ($case_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($case_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($case_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($case_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Case</h4>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Cases
                        </a>
                    </div>
                </div>
            </div>

            <div class="edit-case-form">
                <div class="form-header">
                    <h1 class="form-title">Edit Case</h1>
                    <p class="form-subtitle">Update the case information below</p>
                </div>

                <form action="actions/update.php" method="POST">
                    <input type="hidden" name="case_id" value="<?php echo $case_id; ?>">
                    
                    <div class="form-section">
                        <h2 class="form-section-title">Basic Information</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Case Number</label>
                                <input type="text" name="case_number" class="form-control" value="<?php echo htmlspecialchars($case['case_number']); ?>" required>
                                <div class="form-text">Unique identifier for the case</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($case['title']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="form-section-title">Case Details</h2>
                        <div class="description-wrapper">
                            <label class="form-label">
                                Description
                                <span class="text-muted" style="font-size: 0.85rem; font-weight: normal;">
                                    Required field
                                </span>
                            </label>
                            <textarea name="description" class="form-control" rows="8" required 
                                    placeholder="Enter detailed case description here..."><?php echo htmlspecialchars($case['description']); ?></textarea>
                            <div class="form-text">Provide a comprehensive description of the case</div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="form-section-title">Classification</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="criminal" <?php echo $case['category'] === 'criminal' ? 'selected' : ''; ?>>Criminal</option>
                                    <option value="family" <?php echo $case['category'] === 'family' ? 'selected' : ''; ?>>Family</option>
                                    <option value="cheque" <?php echo $case['category'] === 'cheque' ? 'selected' : ''; ?>>Cheque</option>
                                    <option value="consumer" <?php echo $case['category'] === 'consumer' ? 'selected' : ''; ?>>Consumer</option>
                                    <option value="labour" <?php echo $case['category'] === 'labour' ? 'selected' : ''; ?>>Labour</option>
                                    <option value="high court" <?php echo $case['category'] === 'high court' ? 'selected' : ''; ?>>High Court</option>
                                    <option value="supreme court" <?php echo $case['category'] === 'supreme court' ? 'selected' : ''; ?>>Supreme Court</option>
                                    <option value="other" <?php echo $case['category'] === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Open" <?php echo $case['status'] === 'Open' ? 'selected' : ''; ?>>Open</option>
                                    <option value="In Progress" <?php echo $case['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="Closed" <?php echo $case['status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="form-section-title">Additional Information</h2>
                        <div class="mb-3">
                            <label class="form-label">External Link</label>
                            <input type="url" name="link" class="form-control" value="<?php echo htmlspecialchars($case['link'] ?? ''); ?>">
                            <div class="form-text">Optional link to external resources</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control" value="<?php echo htmlspecialchars($case['tags'] ?? ''); ?>">
                            <div class="form-text">Comma-separated tags for better organization</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-outline-secondary btn-action">
                            <i class="fas fa-times"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html> 