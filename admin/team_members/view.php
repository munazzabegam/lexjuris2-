<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$team_member = null;
$team_member_id = $_GET['id'] ?? 0;

if ($team_member_id) {
    $stmt = $conn->prepare("SELECT * FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $team_member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $team_member = $result->fetch_assoc();

    if (!$team_member) {
        $_SESSION['team_member_error'] = "Team member not found.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['team_member_error'] = "Team member ID not provided.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Team Member - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        .view-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(188, 132, 20, 0.1);
        }
        .view-card h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 0.75rem;
        }
        .view-group {
            margin-bottom: 1rem;
        }
        .view-label {
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 0.25rem;
        }
        .view-value {
            color: #333;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }
        .btn-action {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .team-member-photo-view {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">View Team Member Details</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Team Members
                        </a>
                    </div>
                </div>
            </div>

            <div class="view-card">
                <div class="view-group">
                    <span class="view-label">Full Name:</span>
                    <span class="view-value"><?php echo htmlspecialchars($team_member['full_name']); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Position:</span>
                    <span class="view-value"><?php echo htmlspecialchars($team_member['position']); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Bio:</span>
                    <p class="view-value"><?php echo nl2br(htmlspecialchars($team_member['bio'])); ?></p>
                </div>
                <div class="view-group">
                    <span class="view-label">Photo:</span>
                    <div class="view-value">
                        <?php if ($team_member['photo']): ?>
                            <img src="../../<?php echo htmlspecialchars($team_member['photo']); ?>" alt="Team Member Photo" class="team-member-photo-view">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                </div>
                <div class="view-group">
                    <span class="view-label">Portfolio Link:</span>
                    <span class="view-value">
                        <?php if ($team_member['portfolio']): ?>
                            <a href="<?php echo htmlspecialchars($team_member['portfolio']); ?>" target="_blank">
                                <?php echo htmlspecialchars($team_member['portfolio']); ?>
                            </a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </span>
                </div>
                <div class="view-group">
                    <span class="view-label">Status:</span>
                    <span class="view-value">
                        <span class="badge <?php echo $team_member['is_active'] ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo $team_member['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </span>
                </div>
                <div class="view-group">
                    <span class="view-label">Order Index:</span>
                    <span class="view-value"><?php echo htmlspecialchars($team_member['order_index']); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Created At:</span>
                    <span class="view-value"><?php echo date('Y-m-d H:i:s', strtotime($team_member['created_at'])); ?></span>
                </div>
                <div class="view-group">
                    <span class="view-label">Last Updated:</span>
                    <span class="view-value"><?php echo $team_member['updated_at'] ? date('Y-m-d H:i:s', strtotime($team_member['updated_at'])) : 'N/A'; ?></span>
                </div>

                <div class="mt-4">
                    <a href="edit.php?id=<?php echo $team_member['id']; ?>" class="btn btn-primary btn-action">
                        <i class="fas fa-edit me-2"></i> Edit Team Member
                    </a>
                    <form action="actions/delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this team member?');" style="display: inline;">
                        <input type="hidden" name="team_member_id" value="<?php echo $team_member['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-action">
                            <i class="fas fa-trash me-2"></i> Delete Team Member
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 