<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get all social links ordered by order_index
$result = $conn->query("SELECT * FROM social_links ORDER BY order_index ASC");
$social_links = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Social Links - Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../assets/images/favicon.png">
    <link rel="apple-touch-icon" href="../../assets/images/favicon.png">
    <link rel="manifest" href="../../assets/images/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
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
        .platform-icon {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if (isset($_SESSION['social_link_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['social_link_success'];
                    unset($_SESSION['social_link_success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['social_link_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['social_link_error'];
                    unset($_SESSION['social_link_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manage Social Links</h4>
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add New Social Link
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Social Links</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Order</th>
                                <th>Platform</th>
                                <th>URL</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="social-links-list">
                            <?php if (empty($social_links)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No social links found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($social_links as $link): ?>
                                    <tr data-id="<?php echo $link['id']; ?>">
                                        <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                        <td><?php echo htmlspecialchars($link['order_index']); ?></td>
                                        <td>
                                            <i class="fab fa-<?php echo strtolower($link['platform']); ?> platform-icon"></i>
                                            <?php echo htmlspecialchars($link['platform']); ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 200px;">
                                                <?php echo htmlspecialchars($link['url']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $link['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo $link['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($link['created_at'])); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($link['updated_at'])); ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $link['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this social link?');" style="display: inline;">
                                                <input type="hidden" name="social_link_id" value="<?php echo $link['id']; ?>">
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
            $("#social-links-list").sortable({
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
                    $("#social-links-list tr").each(function(index) {
                        if ($(this).data('id')) {  // Only include rows that have an ID
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
                                $("#social-links-list tr").each(function(index) {
                                    if ($(this).data('id')) {  // Only update rows that have an ID
                                        $(this).find('td:eq(1)').text(index + 1);
                                    }
                                });
                                showAlert('Social link order updated successfully.');
                            } else {
                                showAlert('Error updating social link order: ' + (response.message || 'Unknown error'), 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            showAlert('Error updating social link order. Please try again.', 'danger');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html> 