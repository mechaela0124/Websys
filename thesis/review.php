<?php
include "../config/db.php";

/* Security checks */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'reviewer') {
    die("Access Denied");
}

$user = $_SESSION['user'];

/* Get thesis ID safely */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Thesis ID");
}

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] === 'approved' ? 'approved' : 'rejected';
    $remarks = $conn->real_escape_string($_POST['remarks']);

    // Update thesis status
    $stmt = $conn->prepare("UPDATE thesis SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    // Log approval/rejection
    $stmt2 = $conn->prepare("
        INSERT INTO approvals (thesis_id, reviewer_id, status, remarks)
        VALUES (?, ?, ?, ?)
    ");
    $stmt2->bind_param("iiss", $id, $user['id'], $status, $remarks);
    $stmt2->execute();

    // Log activity
    $conn->query("INSERT INTO activity_logs (user_id, action) 
                  VALUES ({$user['id']}, 'Reviewed thesis ID $id')");

    header("Location: ../dashboard/reviewer.php");
    exit;
}

/* Fetch thesis details */
$stmt = $conn->prepare("
    SELECT thesis.*, users.name AS author_name 
    FROM thesis 
    JOIN users ON users.id = thesis.author_id
    WHERE thesis.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$thesis = $result->fetch_assoc();

if (!$thesis) {
    die("Thesis not found");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Review Thesis</title>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h4>Review Thesis: <?= htmlspecialchars($thesis['title']) ?></h4>
<p><strong>Student:</strong> <?= htmlspecialchars($thesis['author_name']) ?></p>
<p><strong>Status:</strong> <?= ucfirst($thesis['status']) ?></p>

<form method="POST" class="mb-3">
    <div class="mb-2">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="approved">Approve</option>
            <option value="rejected">Reject</option>
        </select>
    </div>
    <div class="mb-2">
        <label>Comments / Remarks</label>
        <textarea name="remarks" class="form-control" placeholder="Enter comments here"></textarea>
    </div>
    <button class="btn btn-success">Submit</button>
    <a href="../dashboard/reviewer.php" class="btn btn-secondary">Back to Dashboard</a>
</form>

</body>
</html>
