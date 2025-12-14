<?php
include 'includes/auth.php';
include 'includes/db.php';

$user = $conn->query("SELECT * FROM users WHERE id=".$_SESSION['user_id'])->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-primary px-3">
<span class="navbar-brand">Enrollment System</span>
<a href="logout.php" class="btn btn-light btn-sm">Logout</a>
</nav>

<div class="container mt-4">

<h4>Welcome, <?= $user['full_name'] ?></h4>
<p>Role: <?= ucfirst($_SESSION['role']) ?></p>

<img src="uploads/profiles/<?= $user['profile_picture'] ?>" width="100">
<img src="uploads/signatures/<?= $user['signature'] ?>" width="200">

<hr>

<?php if ($_SESSION['role']=='student'): ?>
<a href="student/enroll.php" class="btn btn-primary">Enroll Subjects</a>

<?php elseif ($_SESSION['role']=='faculty'): ?>
<a href="faculty/submit_grade.php" class="btn btn-warning">Submit Grades</a>

<?php elseif ($_SESSION['role']=='admin'): ?>
<a href="admin/subjects.php" class="btn btn-dark">Manage Subjects</a>
<?php endif; ?>

</div>
</body>
</html>
