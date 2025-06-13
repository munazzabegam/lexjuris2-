<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Get all testimonials ordered by order_index
$result = $conn->query("SELECT * FROM testimonials ORDER BY order_index ASC");
$testimonials = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Testimonials - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Additional styles for testimonial table to match faq page */
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
        .testimonial-photo {
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
            <?php if (isset($_SESSION['testimonial_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['testimonial_success'];
                    unset($_SESSION['testimonial_success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['testimonial_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['testimonial_error'];
                    unset($_SESSION['testimonial_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manage Testimonials</h4>
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add New Testimonial
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Testimonials</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Company</th>
                                <th>Photo</th>
                                <th>Status</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="testimonials-list">
                            <?php if (empty($testimonials)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No testimonials found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($testimonials as $testimonial): ?>
                                    <tr data-id="<?php echo $testimonial['id']; ?>">
                                        <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                        <td><?php echo htmlspecialchars($testimonial['order_index']); ?></td>
                                        <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                                        <td><?php echo htmlspecialchars($testimonial['position']); ?></td>
                                        <td><?php echo htmlspecialchars($testimonial['company']); ?></td>
                                        <td>
                                            <?php if ($testimonial['photo']): ?>
                                                <img src="../../<?php echo htmlspecialchars($testimonial['photo']); ?>" alt="Testimonial Photo" class="testimonial-photo">
                                            <?php else: ?>
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($testimonial['name']); ?>&background=bc8414&color=fff" alt="Default Photo" class="testimonial-photo">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $testimonial['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo $testimonial['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($testimonial['date_added'])); ?></td>
                                        <td>
                                            <a href="view.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-outline-info btn-action">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edit.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?');" style="display: inline;">
                                                <input type="hidden" name="testimonial_id" value="<?php echo $testimonial['id']; ?>">
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
            $("#testimonials-list").sortable({
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
                    $("#testimonials-list tr").each(function(index) {
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
                                $("#testimonials-list tr").each(function(index) {
                                    if ($(this).data('id')) {  // Only update rows that have an ID
                                        $(this).find('td:eq(1)').text(index + 1);
                                    }
                                });
                                showAlert('Testimonial order updated successfully.');
                            } else {
                                showAlert('Error updating testimonial order: ' + (response.message || 'Unknown error'), 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            showAlert('Error updating testimonial order. Please try again.', 'danger');
                        }
                    });
                }
            });
        });

        function deleteTestimonial(id) {
            if (confirm('Are you sure you want to delete this testimonial?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = './actions/delete.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'testimonial_id';
                input.value = id;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html> 