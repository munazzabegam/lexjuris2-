<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

$page_title = "Udupi Team Members";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../assets/images/favicon.png">
    <link rel="apple-touch-icon" href="../../assets/images/favicon.png">
    <link rel="manifest" href="../../assets/images/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include '../components/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php include '../components/topnavbar.php'; ?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $page_title; ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Member
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                        echo $_SESSION['success_message']; 
                        unset($_SESSION['success_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                        echo $_SESSION['error_message']; 
                        unset($_SESSION['error_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Team Members Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Education</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-tbody">
                            <?php
                            $query = "SELECT * FROM udupi_team_members ORDER BY order_index ASC";
                            $result = $conn->query($query);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr data-id="' . $row['id'] . '">
                                        <td>
                                            <i class="fas fa-grip-vertical handle" style="cursor: move; color: #6c757d;"></i>
                                            <span class="order-number">' . $row['order_index'] . '</span>
                                        </td>
                                        <td>
                                            <img src="../../' . htmlspecialchars($row['photo']) . '" alt="' . htmlspecialchars($row['full_name']) . '" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        </td>
                                        <td>' . htmlspecialchars($row['full_name']) . '</td>
                                        <td>' . htmlspecialchars($row['education']) . '</td>
                                        <td>' . htmlspecialchars($row['contact']) . '</td>
                                        <td>';
                                    
                                    if ($row['is_active']) {
                                        echo '<span class="badge bg-success">Active</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">Inactive</span>';
                                    }
                                    
                                    echo '</td>
                                        <td>
                                            <a href="view.php?id=' . $row['id'] . '" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete" 
                                                    onclick="deleteMember(' . $row['id'] . ', \'' . htmlspecialchars($row['full_name']) . '\')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">No Udupi team members found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="../assets/js/common.js"></script>

    <script>
        // Sortable functionality
        $(document).ready(function() {
            $("#sortable-tbody").sortable({
                handle: ".handle",
                update: function(event, ui) {
                    const order = [];
                    $("#sortable-tbody tr").each(function(index) {
                        order.push({
                            id: $(this).data('id'),
                            order: index + 1
                        });
                    });

                    $.ajax({
                        url: 'actions/update_order.php',
                        type: 'POST',
                        data: { order: order },
                        success: function(response) {
                            if (response.success) {
                                // Update order numbers
                                $("#sortable-tbody tr").each(function(index) {
                                    $(this).find('.order-number').text(index + 1);
                                });
                            }
                        }
                    });
                }
            });
        });

        function deleteMember(id, name) {
            if (confirm('Are you sure you want to delete "' + name + '"?')) {
                window.location.href = 'actions/delete.php?id=' + id;
            }
        }
    </script>
</body>
</html> 