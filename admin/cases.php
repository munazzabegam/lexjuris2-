<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once __DIR__ . '/../config/database.php';

// Initialize variables for filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Build the query
$query = "SELECT c.*, a.username as author_name, a.id as author_id FROM cases c LEFT JOIN admin_users a ON c.author_id = a.id WHERE 1=1";
$params = [];

if (!empty($status_filter)) {
    $query .= " AND status = ?";
    $params[] = $status_filter;
}

if (!empty($category_filter)) {
    $query .= " AND category = ?";
    $params[] = $category_filter;
}

if (!empty($search_query)) {
    $query .= " AND (title LIKE ? OR description LIKE ? OR case_number LIKE ?)";
    $search_param = "%$search_query%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

if (!empty($date_filter)) {
    $query .= " AND DATE(created_at) = ?";
    $params[] = $date_filter;
}

$query .= " ORDER BY order_index ASC, created_at DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$cases = $result->fetch_all(MYSQLI_ASSOC);

// Get categories for filter dropdown
$categories_query = "SELECT DISTINCT category FROM cases WHERE category IS NOT NULL";
$categories_result = $conn->query($categories_query);
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

$case_success = $_SESSION['case_success'] ?? null; // Read case success
unset($_SESSION['case_success']); // Clear case success
$case_error = $_SESSION['case_error'] ?? null; // Read case error
unset($_SESSION['case_error']); // Clear case error

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cases Management - Admin Dashboard</title>
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
        .table th:nth-child(3), /* Title column */
        .table td:nth-child(3) {
            max-width: 120px;
            width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Keep filters styling for now */
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

        /* Remove old case card styles */
        .case-card, .case-header, .case-meta, .case-description, .case-footer, .case-status, .status-active, .status-pending, .status-closed, .case-actions {
             /* display: none; */ /* Or other removal/override */
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
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Cases Management</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCaseModal">
                            <i class="fas fa-plus me-2"></i>Add New Case
                        </button>
                    </div>
                </div>
            </div>

            <?php if ($case_success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($case_success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($case_error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($case_error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Filters Section -->
            <div class="filters-section">
                <form method="GET" action="" class="filter-group">
                    <div class="filter-item">
                        <label>Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="Open" <?php echo $status_filter === 'Open' ? 'selected' : ''; ?>>Open</option>
                            <option value="In Progress" <?php echo $status_filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Closed" <?php echo $status_filter === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Case Type</label>
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="criminal" <?php echo $category_filter === 'criminal' ? 'selected' : ''; ?>>Criminal</option>
                            <option value="family" <?php echo $category_filter === 'family' ? 'selected' : ''; ?>>Family</option>
                            <option value="cheque" <?php echo $category_filter === 'cheque' ? 'selected' : ''; ?>>Cheque</option>
                            <option value="consumer" <?php echo $category_filter === 'consumer' ? 'selected' : ''; ?>>Consumer</option>
                            <option value="labour" <?php echo $category_filter === 'labour' ? 'selected' : ''; ?>>Labour</option>
                            <option value="high court" <?php echo $category_filter === 'high court' ? 'selected' : ''; ?>>High Court</option>
                            <option value="supreme court" <?php echo $category_filter === 'supreme court' ? 'selected' : ''; ?>>Supreme Court</option>
                            <option value="other" <?php echo $category_filter === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="<?php echo $date_filter; ?>" onchange="this.form.submit()">
                    </div>
                    <div class="filter-item">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search cases..." value="<?php echo htmlspecialchars($search_query); ?>">
                    </div>
                </form>
            </div>

            <!-- Cases List (Table) -->
            <div class="table-card">
                <div class="card-header">
                    <h5>All Cases</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Order</th>
                                <th>Case Number</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-cases">
                            <?php if (empty($cases)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No cases found matching your criteria.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cases as $case): ?>
                                    <tr data-case-id="<?php echo $case['id']; ?>">
                                        <td><i class="fas fa-grip-vertical drag-handle"></i></td>
                                        <td><?php echo htmlspecialchars($case['order_index'] ?? 0); ?></td>
                                        <td><?php echo htmlspecialchars($case['case_number']); ?></td>
                                        <td><?php echo htmlspecialchars($case['title']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($case['category'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                if ($case['status'] === 'Open') echo 'success'; 
                                                else if ($case['status'] === 'In Progress') echo 'warning';
                                                else if ($case['status'] === 'Closed') echo 'secondary';
                                            ?>">
                                                <?php echo htmlspecialchars($case['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($case['author_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($case['created_at'])); ?></td>
                                        <td>
                                            <a href="case_details.php?id=<?php echo $case['id']; ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-eye"></i>View</a>
                                            <a href="edit_case.php?id=<?php echo $case['id']; ?>" class="btn btn-sm btn-outline-secondary btn-action"><i class="fas fa-edit"></i>Edit</a>
                                            <form action="actions/delete_case.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this case?');" style="display: inline;">
                                                <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
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

    <!-- Add Case Modal -->
    <div class="modal fade" id="addCaseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Case</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($case_success): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($case_success); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($case_error): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($case_error); ?>
                        </div>
                    <?php endif; ?>
                    <form id="addCaseForm" method="POST" action="actions/add_case.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Case Number</label>
                                <input type="text" name="case_number" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="criminal">Criminal</option>
                                    <option value="family">Family</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="consumer">Consumer</option>
                                    <option value="labour">Labour</option>
                                    <option value="high court">High Court</option>
                                    <option value="supreme court">Supreme Court</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link</label>
                            <input type="url" name="link" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control" placeholder="Separate tags with commas">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addCaseForm" class="btn btn-primary">Add Case</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="assets/js/common.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });

        // View case details
        function viewCase(id) {
            window.location.href = `case_details.php?id=${id}`;
        }

        // Edit case
        function editCase(id) {
            window.location.href = `edit_case.php?id=${id}`;
        }

        $(document).ready(function() {
            $("#sortable-cases").sortable({
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
                    $("#sortable-cases tr").each(function(index) {
                        newOrder.push({
                            id: $(this).data('case-id'),
                            order: index
                        });
                    });

                    // Send the new order to the server
                    $.ajax({
                        url: 'actions/update_case_order.php',
                        method: 'POST',
                        data: {
                            order: newOrder
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update the displayed order_index values
                                $("#sortable-cases tr").each(function(index) {
                                    $(this).find('td:eq(1)').text(index);
                                });
                                showAlert('Case order updated successfully.');
                            } else {
                                showAlert('Error updating case order.', 'danger');
                            }
                        },
                        error: function() {
                            showAlert('Error updating case order.', 'danger');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>