<?php
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Approve or Reject actions could be handled here or via separate files (better)

$stmt = $conn->prepare("
    SELECT thesis.id, thesis.title, users.name, thesis.status
    FROM thesis
    JOIN users ON thesis.author_id = users.id
    WHERE thesis.status = 'pending'
    ORDER BY thesis.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Approve Thesis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Approve Pending Theses</h3>
    <a href="../dashboard/admin.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Student</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>
                        <a href="approve.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success"
                           onclick="return confirm('Approve this thesis?')">Approve</a>
                        <a href="reject.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Reject this thesis?')">Reject</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending thesis submissions.</p>
    <?php endif; ?>
</div>
</body>
</html>
