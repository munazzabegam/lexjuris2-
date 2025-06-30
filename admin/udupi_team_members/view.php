<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Get member ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error_message'] = "Invalid member ID.";
    header('Location: index.php');
    exit();
}

// Fetch member data
$query = "SELECT * FROM udupi_team_members WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Member not found.";
    header('Location: index.php');
    exit();
}

$member = $result->fetch_assoc();
$page_title = "View Udupi Team Member";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include '../components/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php include '../components/topnavbar.php'; ?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $page_title; ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="edit.php?id=<?php echo $member['id']; ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- Member Details -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Photo</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="../../<?php echo htmlspecialchars($member['photo']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['full_name']); ?>" 
                                     class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Member Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">Full Name:</th>
                                        <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Education:</th>
                                        <td><?php echo nl2br(htmlspecialchars($member['education'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact:</th>
                                        <td>
                                            <?php if (!empty($member['contact'])): ?>
                                                <?php if (filter_var($member['contact'], FILTER_VALIDATE_EMAIL)): ?>
                                                    <a href="mailto:<?php echo htmlspecialchars($member['contact']); ?>">
                                                        <?php echo htmlspecialchars($member['contact']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="tel:<?php echo htmlspecialchars($member['contact']); ?>">
                                                        <?php echo htmlspecialchars($member['contact']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Not provided</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Portfolio:</th>
                                        <td>
                                            <?php if (!empty($member['portfolio'])): ?>
                                                <a href="<?php echo htmlspecialchars($member['portfolio']); ?>" target="_blank">
                                                    <?php echo htmlspecialchars($member['portfolio']); ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Not provided</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Display Order:</th>
                                        <td><?php echo $member['order_index']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <?php if ($member['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td><?php echo date('F j, Y g:i A', strtotime($member['created_at'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td><?php echo date('F j, Y g:i A', strtotime($member['updated_at'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html> 