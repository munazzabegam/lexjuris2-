<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get all team members ordered by order_index (if exists, otherwise by id)
$check_column = $conn->query("SHOW COLUMNS FROM team_members LIKE 'order_index'");
$order_by = $check_column->num_rows > 0 ? "order_index ASC" : "id DESC";

$result = $conn->query("SELECT * FROM team_members ORDER BY $order_by");
$team_members = $result->fetch_all(MYSQLI_ASSOC);

// Get all sub_junior team members ordered by order_index (if exists, otherwise by id)
$check_column_sub = $conn->query("SHOW COLUMNS FROM sub_junior_team_members LIKE 'order_index'");
$order_by_sub = $check_column_sub->num_rows > 0 ? "order_index ASC" : "id DESC";

$result_sub = $conn->query("SELECT * FROM sub_junior_team_members ORDER BY $order_by_sub");
$sub_junior_team_members = $result_sub->fetch_all(MYSQLI_ASSOC);

// Get all udupi team members ordered by order_index (if exists, otherwise by id)
$check_column_udupi = $conn->query("SHOW COLUMNS FROM udupi_team_members LIKE 'order_index'");
$order_by_udupi = $check_column_udupi->num_rows > 0 ? "order_index ASC" : "id DESC";

$result_udupi = $conn->query("SELECT * FROM udupi_team_members ORDER BY $order_by_udupi");
$udupi_team_members = $result_udupi->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Team Members - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Additional styles for team table to match other pages */
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
        .drag-handle {
            cursor: grab;
            color: #999;
            padding: 0 10px;
        }
        .drag-handle:hover {
            color: #666;
        }
        .ui-sortable-helper {
            display: table;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .ui-sortable-placeholder {
            visibility: visible !important;
            background: #f8f9fa;
            height: 60px;
        }
        .table tbody tr {
            cursor: move;
        }
        .team-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if (isset($_SESSION['team_member_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['team_member_success'];
                    unset($_SESSION['team_member_success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['team_member_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['team_member_error'];
                    unset($_SESSION['team_member_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manage Main Team Members</h4>
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add New Main Team Member
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Main Team Members</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Order</th>
                                <th>Photo</th>
                                <th>Full Name</th>
                                <th>Education</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="team-members-list">
                            <?php if (empty($team_members)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No main team members found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($team_members as $member): ?>
                                    <tr data-id="<?php echo $member['id']; ?>">
                                        <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                        <td><?php echo htmlspecialchars($member['order_index'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if ($member['photo']): ?>
                                                <img src="../../<?php echo htmlspecialchars($member['photo']); ?>" alt="Team Photo" class="team-photo">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/50" alt="Default Photo" class="team-photo">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['education']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $member['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($member['created_at'])); ?></td>
                                        <td><?php echo $member['updated_at'] ? date('Y-m-d H:i', strtotime($member['updated_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this team member?');" style="display: inline;">
                                                <input type="hidden" name="team_member_id" value="<?php echo $member['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-action">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sub Junior Team Members Section -->
            <div class="row mb-4 mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manage Sub Junior Team Members</h4>
                        <a href="create_sub_junior.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add New Sub Junior Team Member
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Sub Junior Team Members</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Order</th>
                                <th>Photo</th>
                                <th>Full Name</th>
                                <th>Education</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sub-junior-members-list">
                            <?php if (empty($sub_junior_team_members)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No sub junior team members found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sub_junior_team_members as $member): ?>
                                    <tr data-id="<?php echo $member['id']; ?>">
                                        <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                        <td><?php echo htmlspecialchars($member['order_index'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if ($member['photo']): ?>
                                                <img src="../../<?php echo htmlspecialchars($member['photo']); ?>" alt="Team Photo" class="team-photo">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/50" alt="Default Photo" class="team-photo">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['education']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $member['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($member['created_at'])); ?></td>
                                        <td><?php echo $member['updated_at'] ? date('Y-m-d H:i', strtotime($member['updated_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <a href="edit_sub_junior.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="actions/delete_sub_junior.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this sub junior team member?');" style="display: inline;">
                                                <input type="hidden" name="sub_junior_member_id" value="<?php echo $member['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-action">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Udupi Team Members Section -->
            <div class="row mb-4 mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manage Udupi Team Members</h4>
                        <a href="create_udupi.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add New Udupi Team Member
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Udupi Team Members</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Order</th>
                                <th>Photo</th>
                                <th>Full Name</th>
                                <th>Education</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="udupi-members-list">
                            <?php if (empty($udupi_team_members)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No Udupi team members found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($udupi_team_members as $member): ?>
                                    <tr data-id="<?php echo $member['id']; ?>">
                                        <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                        <td><?php echo htmlspecialchars($member['order_index'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if ($member['photo']): ?>
                                                <img src="../../<?php echo htmlspecialchars($member['photo']); ?>" alt="Team Photo" class="team-photo">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/50" alt="Default Photo" class="team-photo">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['education']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $member['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($member['created_at'])); ?></td>
                                        <td><?php echo $member['updated_at'] ? date('Y-m-d H:i', strtotime($member['updated_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <a href="edit_udupi.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="actions/delete_udupi.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this Udupi team member?');" style="display: inline;">
                                                <input type="hidden" name="udupi_member_id" value="<?php echo $member['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-action">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="../assets/js/common.js"></script>
    <script>
        $(document).ready(function() {
            // Main Team Members Sortable
            $("#team-members-list").sortable({
                handle: ".drag-handle",
                placeholder: "ui-sortable-placeholder",
                helper: function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function(event, ui) {
                    var newOrder = [];
                    $("#team-members-list tr").each(function(index) {
                        if ($(this).data('id')) {  // Only include rows that have an an ID
                            newOrder.push({
                                id: $(this).data('id'),
                                order: index
                            });
                        }
                    });

                    // Send the new order to the server
                    $.ajax({
                        url: 'actions/update_order.php',
                        method: 'POST',
                        data: {
                            order: newOrder
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update the displayed order_index values
                                $("#team-members-list tr").each(function(index) {
                                    if ($(this).data('id')) {  // Only update rows that have an ID
                                        $(this).find('td:eq(1)').text(index + 1);
                                    }
                                });
                                showAlert('Main team member order updated successfully.');
                            } else {
                                showAlert('Error updating main team member order: ' + (response.message || 'Unknown error'), 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            showAlert('Error updating main team member order. Please try again.', 'danger');
                        }
                    });
                }
            });

            // Sub Junior Team Members Sortable
            $("#sub-junior-members-list").sortable({
                handle: ".drag-handle",
                placeholder: "ui-sortable-placeholder",
                helper: function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function(event, ui) {
                    var newOrder = [];
                    $("#sub-junior-members-list tr").each(function(index) {
                        if ($(this).data('id')) {
                            newOrder.push({
                                id: $(this).data('id'),
                                order: index
                            });
                        }
                    });

                    // Send the new order to the server for sub junior members
                    $.ajax({
                        url: 'actions/update_sub_junior_order.php',
                        method: 'POST',
                        data: {
                            order: newOrder
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#sub-junior-members-list tr").each(function(index) {
                                    if ($(this).data('id')) {
                                        $(this).find('td:eq(1)').text(index + 1);
                                    }
                                });
                                showAlert('Sub junior team member order updated successfully.');
                            } else {
                                showAlert('Error updating sub junior team member order: ' + (response.message || 'Unknown error'), 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            showAlert('Error updating sub junior team member order. Please try again.', 'danger');
                        }
                    });
                }
            });

            // Udupi Team Members Sortable
            $("#udupi-members-list").sortable({
                handle: ".drag-handle",
                placeholder: "ui-sortable-placeholder",
                helper: function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function(event, ui) {
                    var newOrder = [];
                    $("#udupi-members-list tr").each(function(index) {
                        if ($(this).data('id')) {
                            newOrder.push({
                                id: $(this).data('id'),
                                order: index
                            });
                        }
                    });

                    // Send the new order to the server for Udupi members
                    $.ajax({
                        url: 'actions/update_udupi_order.php',
                        method: 'POST',
                        data: {
                            order: newOrder
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#udupi-members-list tr").each(function(index) {
                                    if ($(this).data('id')) {
                                        $(this).find('td:eq(1)').text(index + 1);
                                    }
                                });
                                showAlert('Udupi team member order updated successfully.');
                            } else {
                                showAlert('Error updating Udupi team member order: ' + (response.message || 'Unknown error'), 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            showAlert('Error updating Udupi team member order. Please try again.', 'danger');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html> 