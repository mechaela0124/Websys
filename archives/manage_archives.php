<?php
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT files.id, thesis.title, files.file_path
    FROM files
    JOIN thesis ON thesis.id = files.thesis_id
    ORDER BY thesis.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Archives</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Manage Archives</h3>
    <a href="../dashboard/admin.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
        <tr>
            <th>Thesis Title</th>
            <th>File Path</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($file = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($file['title']) ?></td>
                <td><a href="<?= htmlspecialchars($file['file_path']) ?>" target="_blank">View File</a></td>
                <td>
                    <a href="delete_archive.php?id=<?= $file['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this archive file?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
