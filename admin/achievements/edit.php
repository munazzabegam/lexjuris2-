<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$achievement_id = $_GET['id'] ?? null;

if (!$achievement_id) {
    $_SESSION['achievement_error'] = "Achievement ID not provided.";
    header("Location: index.php");
    exit();
}

$query = "SELECT * FROM achievements WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $achievement_id);
$stmt->execute();
$result = $stmt->get_result();
$achievement = $result->fetch_assoc();

if (!$achievement) {
    $_SESSION['achievement_error'] = "Achievement not found.";
    header("Location: index.php");
    exit();
}

$achievement_error = $_SESSION['achievement_error'] ?? null;
unset($_SESSION['achievement_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Achievement - Lex Juris Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php include '../components/topnavbar.php'; ?>

    <div class="main-content">
        <div class="container-fluid p-3">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Achievement</h4>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Achievements
                        </a>
                    </div>
                </div>
            </div>

            <?php if ($achievement_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($achievement_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="actions/update.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($achievement['id']); ?>">
                        <div class="mb-3">
                            <label for="number_value" class="form-label">Number Value</label>
                            <input type="number" class="form-control" id="number_value" name="number_value" value="<?php echo htmlspecialchars($achievement['number_value']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="label" class="form-label">Label (e.g., Happy Clients)</label>
                            <input type="text" class="form-control" id="label" name="label" value="<?php echo htmlspecialchars($achievement['label']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Achievement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 