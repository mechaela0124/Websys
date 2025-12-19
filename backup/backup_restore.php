<?php
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['backup'])) {
        // Placeholder: implement DB backup here
        $message = "Backup feature is not implemented yet.";
    } elseif (isset($_POST['restore'])) {
        // Placeholder: implement DB restore here
        $message = "Restore feature is not implemented yet.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Backup & Restore System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Backup & Restore System</h3>
    <a href="../dashboard/admin.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" name="backup" class="btn btn-success me-2">Backup Database</button>
        <button type="submit" name="restore" class="btn btn-danger">Restore Database</button>
    </form>

    <p class="mt-3 text-muted">* Backup and restore functionality must be implemented with proper file handling and security.</p>
</div>
</body>
</html>
