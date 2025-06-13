<?php
require_once __DIR__ . '/../../config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Handle user deletion
if (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("DELETE FROM admin_users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting user.";
    }
    header("Location: index.php");
    exit();
}

// Fetch all users
$query = "SELECT id, username, email, profile_image, is_active, created_at, last_login FROM admin_users ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="../../assets/css/admin.css"> -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Styles to match FAQ panel */
        .table-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .table-card .card-header {
            background: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0 0 0.75rem 0;
            margin-bottom: 0.75rem;
        }
        .table th {
            font-weight: 600;
            color: #666;
            border-top: none;
            padding: 0.75rem;
            font-size: 0.85rem;
        }
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            font-size: 0.95rem;
        }
        .btn-action {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 6px;
        }
        .btn-action i {
            margin-right: 0.4em;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .status-badge {
            width: 80px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>
            
    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Manage Users</h4>
                <a href="create.php" class="btn btn-primary btn-action">
                    <i class="fas fa-plus me-2"></i> Add New User
                </a>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Users</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($user = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <?php if ($user['profile_image']): ?>
                                                <img src="../../<?php echo htmlspecialchars($user['profile_image']); ?>" 
                                                     alt="Profile" class="user-avatar">
                                            <?php else: ?>
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>&background=bc8414&color=fff" 
                                                     alt="Default Profile" class="user-avatar">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $user['is_active'] ? 'bg-success' : 'bg-warning'; ?> status-badge">
                                                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'N/A'; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="view.php?id=<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-outline-info btn-action" title="View">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="edit.php?id=<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary btn-action" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger btn-action" 
                                                        title="Delete"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?php echo $user['id']; ?>">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal<?php echo $user['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirm Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete user "<?php echo htmlspecialchars($user['username']); ?>" ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST">
                                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 