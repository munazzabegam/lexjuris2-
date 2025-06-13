<?php
require_once __DIR__ . '/../../config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get user ID from URL
$user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$user_id) {
    header("Location: index.php");
    exit();
}

// Fetch user data with profile image from admin_users table
$stmt = $conn->prepare("SELECT id, username, email, profile_image, is_active, created_at, last_login FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .profile-image {
            max-width: 150px; /* Smaller image */
            max-height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
        }
        .info-label {
            font-weight: 600;
            color: #555;
            font-size: 0.95rem;
        }
        .info-value {
            font-size: 0.95rem;
            color: #333;
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
                <h4 class="mb-0">User Details</h4>
                <div>
                    <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-action">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary btn-action">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            <?php if ($user['profile_image'] && file_exists(__DIR__ . '/../../' . $user['profile_image'])): ?>
                                <img src="../../<?php echo htmlspecialchars($user['profile_image']); ?>" 
                                     alt="Profile" class="profile-image">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>&background=bc8414&color=fff" 
                                     alt="Default Profile" class="profile-image">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-sm-4 info-label">Username:</div>
                                <div class="col-sm-8 info-value"><?php echo htmlspecialchars($user['username']); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 info-label">Email:</div>
                                <div class="col-sm-8 info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 info-label">Status:</div>
                                <div class="col-sm-8 info-value">
                                    <span class="badge <?php echo $user['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 info-label">Created At:</div>
                                <div class="col-sm-8 info-value">
                                    <?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 info-label">Last Login:</div>
                                <div class="col-sm-8 info-value">
                                    <?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'N/A'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 