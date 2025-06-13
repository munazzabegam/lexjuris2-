<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create FAQ - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .form-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.6rem 0.8rem;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: rgba(188, 132, 20, 0.3);
            box-shadow: 0 0 0 0.2rem rgba(188, 132, 20, 0.1);
        }
        .btn-action {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 6px;
        }
        .btn-action i {
            margin-right: 0.4em;
        }
    </style>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Create New FAQ</h4>
                <a href="index.php" class="btn btn-secondary btn-action">
                    <i class="fas fa-arrow-left"></i> Back to FAQ
                </a>
            </div>

            <?php if (isset($_SESSION['faq_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['faq_error'];
                    unset($_SESSION['faq_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <form action="actions/create.php" method="POST">
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <input type="text" class="form-control" id="question" name="question" required>
                    </div>

                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer</label>
                        <textarea class="form-control" id="answer" name="answer" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas fa-save"></i> Create FAQ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 