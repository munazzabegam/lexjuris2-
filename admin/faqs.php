<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Simple query to fetch all FAQs
$query = "SELECT * FROM faq ORDER BY order_index ASC, created_at DESC";
$result = $conn->query($query);

$faqs = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
    $result->free();
}

// Read and clear session messages
$faq_success = $_SESSION['faq_success'] ?? null;
unset($_SESSION['faq_success']);
$faq_error = $_SESSION['faq_error'] ?? null;
unset($_SESSION['faq_error']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
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
        .table th:nth-child(3), /* Question column */
        .table td:nth-child(3) {
            max-width: 200px;
            width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Styling for active/inactive badge */
        .badge-active {
            background-color: #28a745; /* Bootstrap success green */
            color: white;
        }
        .badge-inactive {
            background-color: #dc3545; /* Bootstrap danger red */
            color: white;
        }

        /* Drag handle styling */
        .drag-handle {
            cursor: move;
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
<?php include 'components/sidebar.php'; ?>
<?php include 'components/topnavbar.php'; ?>
<div class="main-content">
    <div class="container-fluid p-3">
        <?php if ($faq_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($faq_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($faq_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($faq_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">FAQs Management</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                <i class="fas fa-plus me-2"></i>Add New FAQ
            </button>
        </div>

        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>All FAQs</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;"></th>
                            <th>Question</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th>Author</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-faqs">
                        <?php if (empty($faqs)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No FAQs found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($faqs as $faq): ?>
                                <tr data-faq-id="<?php echo $faq['id']; ?>">
                                    <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                    <td><?php echo htmlspecialchars(mb_strimwidth($faq['question'], 0, 100, '...')); ?></td>
                                    <td><?php echo htmlspecialchars($faq['order_index']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $faq['is_active'] ? 'badge-active' : 'badge-inactive'; ?>">
                                            <?php echo $faq['is_active'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($faq['author_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($faq['created_at'])); ?></td>
                                    <td>
                                        <?php 
                                        if ($faq['updated_at'] && $faq['updated_at'] !== $faq['created_at']) {
                                            echo date('Y-m-d H:i', strtotime($faq['updated_at']));
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="view_faq.php?id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-eye"></i>View</a>
                                        <a href="edit_faq.php?id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-outline-secondary btn-action"><i class="fas fa-edit"></i>Edit</a>
                                        <form action="actions/delete_faq.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?');" style="display: inline;">
                                            <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action"><i class="fas fa-trash"></i>Delete</button>
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

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="addFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFaqModalLabel">Add New FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addFaqForm" method="POST" action="actions/add_faq.php">
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer</label>
                        <textarea class="form-control" id="answer" name="answer" rows="5" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_index" class="form-label">Order Index</label>
                            <input type="number" class="form-control" id="order_index" name="order_index" value="<?php echo htmlspecialchars($old_faq_data['order_index'] ?? '0'); ?>">
                            <div class="form-text">Lower numbers appear first.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1" <?php echo (isset($old_faq_data['is_active']) && $old_faq_data['is_active'] === '1') ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo (isset($old_faq_data['is_active']) && $old_faq_data['is_active'] === '0') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="author_id" value="<?php echo $_SESSION['admin_id'] ?? ''; ?>">
                    <input type="hidden" name="author_name" value="<?php echo $_SESSION['admin_username'] ?? ''; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addFaqForm" class="btn btn-primary">Add FAQ</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="assets/js/common.js"></script>
<script>
    $(document).ready(function() {
        $("#sortable-faqs").sortable({
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
                $("#sortable-faqs tr").each(function(index) {
                    newOrder.push({
                        id: $(this).data('faq-id'),
                        order: index
                    });
                });

                // Send the new order to the server
                $.ajax({
                    url: 'actions/update_faq_order.php',
                    method: 'POST',
                    data: {
                        order: newOrder
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the displayed order_index values
                            $("#sortable-faqs tr").each(function(index) {
                                $(this).find('td:eq(2)').text(index);
                            });
                            showAlert('FAQ order updated successfully.');
                        } else {
                            showAlert('Error updating FAQ order.', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('Error updating FAQ order.', 'danger');
                    }
                });
            }
        });
    });
</script>
</body>
</html> 