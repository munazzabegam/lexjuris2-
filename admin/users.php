<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Initialize variables for filters
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build the query
$query = "SELECT * FROM admin_users WHERE 1=1";
$params = [];
$types = "";

if (!empty($search_query)) {
    $query .= " AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
    $search_param = "%$search_query%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if (!empty($status_filter)) {
    $query .= " AND is_active = ?";
    $params[] = $status_filter === 'active' ? 1 : 0;
    $types .= "i";
}

$query .= " ORDER BY created_at DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

// Read and clear session messages
$user_success = $_SESSION['user_success'] ?? null;
unset($_SESSION['user_success']);
$user_error = $_SESSION['user_error'] ?? null;
unset($_SESSION['user_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
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

        /* Column width fixes */
        .table th:nth-child(2), /* Username column */
        .table td:nth-child(2) {
            max-width: 120px;
            width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        .filter-item label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .filter-item select,
        .filter-item input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid rgba(188, 132, 20, 0.2);
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .filter-item select:focus,
        .filter-item input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(188, 132, 20, 0.1);
            outline: none;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
<?php include 'components/sidebar.php'; ?>
<?php include 'components/topnavbar.php'; ?>

<div class="main-content">
    <div class="container-fluid p-3">
        <?php if ($user_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($user_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($user_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($user_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Users Management</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus me-2"></i>Add New User
            </button>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <form method="GET" action="" class="filter-group">
                <div class="filter-item">
                    <label>Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
            </form>
        </div>

        <!-- Users List (Table) -->
        <div class="table-card">
            <div class="card-header">
                <h5>All Users</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No users found matching your criteria.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($user['profile_image'])): ?>
                                            <img src="<?php echo htmlspecialchars('/lexjuris/' . $user['profile_image']); ?>" alt="Profile" class="profile-image">
                                        <?php else: ?>
                                            <div class="profile-image bg-secondary d-flex align-items-center justify-content-center text-white">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary btn-action" onclick="editUser(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-edit"></i>Edit
                                        </button>
                                        <?php if ($user['id'] !== $_SESSION['admin_id']): ?>
                                            <form action="actions/delete_user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-action">
                                                    <i class="fas fa-trash"></i>Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST" action="actions/add_user.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addUserForm" class="btn btn-primary">Add User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST" action="actions/update_user.php" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                        <div id="current_profile_image" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="edit_is_active" class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editUserForm" class="btn btn-primary">Update User</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/common.js"></script>
<script>
function editUser(userId) {
    // Fetch user data and populate the edit form
    $.get('actions/get_user.php', { user_id: userId }, function(response) {
        if (response.success) {
            const user = response.user;
            $('#edit_user_id').val(user.id);
            $('#edit_username').val(user.username);
            $('#edit_email').val(user.email);
            $('#edit_is_active').val(user.is_active);
            
            // Show current profile image if exists
            if (user.profile_image) {
                $('#current_profile_image').html(`
                    <img src="/lexjuris/${user.profile_image}" alt="Current Profile" class="profile-image">
                    <small class="text-muted">Current profile image</small>
                `);
            } else {
                $('#current_profile_image').html(`
                    <div class="profile-image bg-secondary d-flex align-items-center justify-content-center text-white">
                        <i class="fas fa-user"></i>
                    </div>
                    <small class="text-muted">No profile image</small>
                `);
            }
            
            // Show the modal
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        } else {
            showAlert('Error loading user data.', 'danger');
        }
    });
}
</script>
</body>
</html> 