<?php
include "../config/db.php";

/* Security checks */
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['user']['role'] !== 'reviewer') {
    die("Access Denied");
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Reviewer Dashboard</title>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-4">

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Reviewer Dashboard</h4>
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Thesis Table -->
<div class="card shadow">
<div class="card-body">

<h5>Student Thesis Submissions</h5>

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
    <td>
        <a href="../thesis/review.php?id=<?= $row['id'] ?>"
           class="btn btn-sm btn-primary">
           Review
        </a>
    </td>
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
