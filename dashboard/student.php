<?php
include "../config/db.php";

/* Security checks */
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['user']['role'] !== 'student') {
    die("Access Denied");
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard</title>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-4">

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Welcome, <?= htmlspecialchars($user['name']) ?></h4>
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Actions -->
<div class="mb-3">
    <a href="../thesis/upload.php" class="btn btn-success">
        Upload Thesis
    </a>
    <a href="../user/profile.php" class="btn btn-secondary">
        Profile
    </a>
</div>

<!-- Thesis Table -->
<div class="card shadow">
<div class="card-body">

<h5>Your Thesis Submissions</h5>

<table class="table table-bordered table-hover mt-3">
<thead class="table-dark">
<tr>
    <th>Title</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php
$stmt = $conn->prepare("SELECT id, title, status FROM thesis WHERE author_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td>
        <span class="badge bg-<?= 
            $row['status'] == 'approved' ? 'success' :
            ($row['status'] == 'rejected' ? 'danger' : 'warning') ?>">
            <?= ucfirst($row['status']) ?>
        </span>
    </td>
    <td>
        <?php if ($row['status'] === 'approved'): ?>
            <a href="../thesis/download.php?id=<?= $row['id'] ?>"
               class="btn btn-sm btn-primary">
               Download
            </a>
        <?php else: ?>
            <span class="text-muted">Not available</span>
        <?php endif; ?>
    </td>
</tr>
<?php
    endwhile;
else:
?>
<tr>
    <td colspan="3" class="text-center text-muted">
        No thesis submissions yet.
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
