<?php
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, name FROM programs ORDER BY name ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Programs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Manage Programs</h3>
    <a href="add_program.php" class="btn btn-primary mb-3">Add New Program</a>
    <a href="../dashboard/admin.php" class="btn btn-secondary mb-3 float-end">Back to Dashboard</a>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
        <tr>
            <th>Program Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($program = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($program['name']) ?></td>
                <td>
                    <a href="edit_program.php?id=<?= $program['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_program.php?id=<?= $program['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete program?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
