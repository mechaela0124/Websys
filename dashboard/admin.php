<?php
include "../config/db.php";

/* Security checks */
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access Denied");
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Admin Dashboard</h4>
        <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Actions -->
    <div class="mb-3">
        <a href="../reports/export.php" class="btn btn-primary">Export Reports</a>
    </div>

    <!-- Management Links -->
    <div class="mb-4">
        <h5>Management</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="../users/admin_users.php" class="btn btn-outline-primary">Manage Users</a>
            <a href="../departments/manage_departments.php" class="btn btn-outline-secondary">Manage Departments</a>
            <a href="../programs/manage_programs.php" class="btn btn-outline-success">Manage Programs</a>
            <a href="../archives/manage_archives.php" class="btn btn-outline-warning">Manage Archives</a>
            <a href="../thesis/admin_approve.php" class="btn btn-outline-info">Approve Theses</a>
            <a href="../logs/activity_logs.php" class="btn btn-outline-dark">View Activity Logs</a>
            <a href="../profile/manage_profile.php" class="btn btn-outline-danger">Manage Profile Pictures/Signatures</a>
            <a href="../backup/backup_restore.php" class="btn btn-outline-secondary">Backup/Restore System</a>
        </div>
    </div>

    <!-- Thesis Table -->
    <div class="card shadow">
        <div class="card-body">

            <h5>All Thesis Submissions</h5>

            <table class="table table-bordered table-hover mt-3">
                <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $stmt = $conn->prepare("
                    SELECT thesis.id, thesis.title, thesis.status, users.name
                    FROM thesis
                    JOIN users ON users.id = thesis.author_id
                    ORDER BY thesis.created_at DESC
                ");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $action_text = '';
                        if ($row['status'] === 'approved') {
                            $action_text = 'Approved';
                        } elseif ($row['status'] === 'rejected') {
                            $action_text = 'Rejected';
                        } else {
                            $action_text = 'Pending';
                        }
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $row['status'] == 'approved' ? 'success' :
                                ($row['status'] == 'rejected' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td><?= $action_text ?></td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No thesis submissions found.
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
