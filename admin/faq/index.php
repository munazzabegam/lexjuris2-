<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get all FAQ ordered by order_index
$result = $conn->query("SELECT * FROM faq ORDER BY order_index ASC");
$faqs = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQ - Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../assets/images/favicon.png">
    <link rel="apple-touch-icon" href="../../assets/images/favicon.png">
    <link rel="manifest" href="../../assets/images/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Additional styles for FAQ table to match articles page */
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
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if (isset($_SESSION['faq_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['faq_success'];
                    unset($_SESSION['faq_success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['faq_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['faq_error'];
                    unset($_SESSION['faq_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manage FAQ</h4>
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add New FAQ
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All FAQ</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Order</th>
                                <th>Question</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="faq-list">
                            <?php if (empty($faqs)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No FAQ found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($faqs as $faq): ?>
                                    <tr data-id="<?php echo $faq['id']; ?>">
                                        <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                        <td><?php echo htmlspecialchars($faq['order_index']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($faq['question'], 0, 100)) . (strlen($faq['question']) > 100 ? '...' : ''); ?></td>
                                        <td><?php echo htmlspecialchars($faq['author_name'] ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge <?php echo $faq['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo $faq['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($faq['created_at'])); ?></td>
                                        <td><?php echo $faq['updated_at'] ? date('Y-m-d H:i', strtotime($faq['updated_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <a href="view.php?id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-outline-info btn-action">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edit.php?id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?');" style="display: inline;">
                                                <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>">
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
            $("#faq-list").sortable({
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
                    $("#faq-list tr").each(function(index) {
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
                                $("#faq-list tr").each(function(index) {
                                    if ($(this).data('id')) {  // Only update rows that have an ID
                                        $(this).find('td:eq(1)').text(index + 1);
                                    }
                                });
                                showAlert('FAQ order updated successfully.');
                            } else {
                                showAlert('Error updating FAQ order: ' + (response.message || 'Unknown error'), 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            showAlert('Error updating FAQ order. Please try again.', 'danger');
                        }
                    });
                }
            });
        });

        function deleteFAQ(id) {
            if (confirm('Are you sure you want to delete this FAQ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = './actions/delete.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'faq_id';
                input.value = id;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html> 