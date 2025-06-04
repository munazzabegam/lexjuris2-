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

$query .= " ORDER BY created_at DESC";

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
    <style>
        .case-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .case-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }

        .case-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(188, 132, 20, 0.1);
        }

        .case-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
            line-height: 1.4;
        }

        .case-meta {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .case-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .case-meta-item i {
            color: var(--primary-color);
        }

        .case-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
            flex-grow: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            min-height: 4.5em; /* 3 lines of text */
        }

        .case-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(188, 132, 20, 0.1);
        }

        .case-status {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-pending {
            background: rgba(188, 132, 20, 0.1);
            color: var(--primary-color);
        }

        .status-closed {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .case-actions {
            display: flex;
            gap: 0.5rem;
        }

        .case-actions .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            white-space: nowrap;
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
    </style>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    <?php include 'components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
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

            <!-- Cases List -->
            <div class="row">
                <?php if (empty($cases)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No cases found matching your criteria.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($cases as $case): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="case-card">
                                <div class="case-header">
                                    <div>
                                        <h5 class="case-title"><?php echo htmlspecialchars($case['title']); ?></h5>
                                        <div class="case-meta">
                                            <span class="case-meta-item">
                                                <i class="fas fa-gavel"></i><?php echo ucfirst(htmlspecialchars($case['category'])); ?>
                                            </span>
                                            <span class="case-meta-item">
                                                <i class="fas fa-hashtag"></i><?php echo htmlspecialchars($case['case_number']); ?>
                                            </span>
                                            <span class="case-meta-item">
                                                <i class="fas fa-calendar"></i><?php echo date('M d, Y', strtotime($case['created_at'])); ?>
                                            </span>
                                            <span class="case-meta-item">
                                                <i class="fas fa-user"></i>
                                                <?php echo htmlspecialchars($case['author_name'] ?? 'N/A'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="case-description">
                                    <?php echo htmlspecialchars($case['description']); ?>
                                </div>
                                <div class="case-footer">
                                    <span class="case-status status-<?php echo strtolower(str_replace(' ', '-', $case['status'])); ?>">
                                        <?php echo htmlspecialchars($case['status']); ?>
                                    </span>
                                    <div class="case-actions">
                                        <a href="case_details.php?id=<?php echo $case['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                        <a href="edit_case.php?id=<?php echo $case['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
    </script>
</body>
</html>