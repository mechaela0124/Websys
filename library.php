<?php
session_start();
include "../config/db.php";

/* Security check */
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Fetch filter parameters */
$title = $_GET['title'] ?? '';
$author = $_GET['author'] ?? '';
$year = $_GET['year'] ?? '';
$adviser = $_GET['adviser'] ?? '';
$keywords = $_GET['keywords'] ?? '';

/* Build SQL query with filters */
$sql = "SELECT thesis.id, thesis.title, thesis.keywords, thesis.created_at, thesis.status, thesis.adviser,
        users.name AS author_name
        FROM thesis
        JOIN users ON users.id = thesis.author_id
        WHERE thesis.status='approved'";

$params = [];
$types = '';

if ($title !== '') {
    $sql .= " AND thesis.title LIKE ?";
    $params[] = "%$title%";
    $types .= 's';
}
if ($author !== '') {
    $sql .= " AND users.name LIKE ?";
    $params[] = "%$author%";
    $types .= 's';
}
if ($year !== '') {
    $sql .= " AND YEAR(thesis.created_at) = ?";
    $params[] = $year;
    $types .= 'i';
}
if ($adviser !== '') {
    $sql .= " AND thesis.adviser LIKE ?";
    $params[] = "%$adviser%";
    $types .= 's';
}
if ($keywords !== '') {
    $sql .= " AND thesis.keywords LIKE ?";
    $params[] = "%$keywords%";
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thesis Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h3>Thesis Library</h3>

<!-- Search Filters -->
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <input type="text" name="title" class="form-control" placeholder="Title" value="<?= htmlspecialchars($title) ?>">
    </div>
    <div class="col-md-2">
        <input type="text" name="author" class="form-control" placeholder="Author" value="<?= htmlspecialchars($author) ?>">
    </div>
    <div class="col-md-2">
        <input type="text" name="year" class="form-control" placeholder="Year" value="<?= htmlspecialchars($year) ?>">
    </div>
    <div class="col-md-2">
        <input type="text" name="adviser" class="form-control" placeholder="Adviser" value="<?= htmlspecialchars($adviser) ?>">
    </div>
    <div class="col-md-2">
        <input type="text" name="keywords" class="form-control" placeholder="Keywords" value="<?= htmlspecialchars($keywords) ?>">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Search</button>
    </div>
</form>

<!-- Library Table -->
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Adviser</th>
            <th>Year</th>
            <th>Keywords</th>
            <th>Download</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                // Fetch the thesis file
                $file_query = $conn->prepare("SELECT file_path FROM files WHERE thesis_id=? LIMIT 1");
                $file_query->bind_param("i", $row['id']);
                $file_query->execute();
                $file_res = $file_query->get_result()->fetch_assoc();
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author_name']) ?></td>
                    <td><?= htmlspecialchars($row['adviser']) ?></td>
                    <td><?= date('Y', strtotime($row['created_at'])) ?></td>
                    <td><?= htmlspecialchars($row['keywords']) ?></td>
                    <td>
                        <?php if ($file_res): ?>
                            <a href="<?= htmlspecialchars($file_res['file_path']) ?>" class="btn btn-sm btn-success" download>Download</a>
                        <?php else: ?>
                            <span class="text-muted">No file</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No thesis found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
