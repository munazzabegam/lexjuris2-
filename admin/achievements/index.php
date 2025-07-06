<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Fetch all achievements
$query = "SELECT * FROM achievements ORDER BY order_index ASC, created_at DESC";
$result = $conn->query($query);
$achievements = $result->fetch_all(MYSQLI_ASSOC);

$achievement_success = $_SESSION['achievement_success'] ?? null;
unset($_SESSION['achievement_success']);
$achievement_error = $_SESSION['achievement_error'] ?? null;
unset($_SESSION['achievement_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievements Management - Lex Juris Admin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../assets/images/favicon.png">
    <link rel="apple-touch-icon" href="../../assets/images/favicon.png">
    <link rel="manifest" href="../../assets/images/site.webmanifest">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .table-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .sortable-list .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            margin-bottom: 5px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .sortable-list .list-group-item i.fa-grip-vertical {
            cursor: grab;
            margin-right: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <?php if ($achievement_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($achievement_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($achievement_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($achievement_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Achievements Management</h4>
                        <?php /*
                        <a href="./add.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Achievement
                        </a>
                        */ ?>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Achievements</h5>
                </div>
                <div class="table-responsive">
                    <ul class="list-group sortable-list" id="achievementsList">
                        <?php if (!empty($achievements)): ?>
                            <?php foreach ($achievements as $achievement): ?>
                                <li class="list-group-item" data-id="<?php echo $achievement['id']; ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-grip-vertical me-2"></i>
                                        <span class="fw-bold me-2"><?php echo htmlspecialchars($achievement['number_value']); ?></span>
                                        <span><?php echo htmlspecialchars($achievement['label']); ?></span>
                                    </div>
                                    <div>
                                        <a href="edit.php?id=<?php echo $achievement['id']; ?>" class="btn btn-sm btn-info me-2"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="actions/delete.php?id=<?php echo $achievement['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this achievement?');"><i class="fas fa-trash"></i> Delete</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center">No achievements found.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('achievementsList');
            if (el) {
                var sortable = new Sortable(el, {
                    animation: 150,
                    ghostClass: 'blue-background-class',
                    onEnd: function (evt) {
                        var order = sortable.toArray();
                        fetch('actions/update_order.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ order: order }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Order updated successfully!');
                            } else {
                                console.error('Failed to update order:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    },
                });
            }
        });
    </script>
</body>
</html> 