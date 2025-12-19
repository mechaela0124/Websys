<?php
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    die("Access Denied");
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $abstract = trim($_POST['abstract']);
    $keywords = trim($_POST['keywords']);
    $file = $_FILES['file'];

    // Basic file validation: allow only PDFs, max 10MB
    $allowed_types = ['application/pdf'];
    if (!in_array($file['type'], $allowed_types)) {
        die("Only PDF files are allowed.");
    }
    if ($file['size'] > 10 * 1024 * 1024) {
        die("File size must be less than 10MB.");
    }

    // Generate unique filename to avoid overwriting
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('thesis_', true) . "." . $ext;
    $upload_dir = "../assets/uploads/thesis/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $path = $upload_dir . $new_filename;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        die("Failed to upload file.");
    }

    // Insert into thesis table with prepared statements
    $stmt = $conn->prepare("INSERT INTO thesis (title, abstract, keywords, author_id, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssi", $title, $abstract, $keywords, $user['id']);
    $stmt->execute();

    $thesis_id = $stmt->insert_id;

    // Insert file path
    $stmt2 = $conn->prepare("INSERT INTO files (thesis_id, file_path) VALUES (?, ?)");
    $stmt2->bind_param("is", $thesis_id, $path);
    $stmt2->execute();

    header("Location: ../dashboard/student.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Thesis</title>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h4>Upload Thesis</h4>

<form method="POST" enctype="multipart/form-data" class="mb-3">
    <input name="title" class="form-control mb-2" placeholder="Title" required>
    <textarea name="abstract" class="form-control mb-2" placeholder="Abstract" rows="5" required></textarea>
    <input name="keywords" class="form-control mb-2" placeholder="Keywords" required>
    <input type="file" name="file" class="form-control mb-2" accept=".pdf" required>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="../dashboard/student.php" class="btn btn-secondary ms-2">Back to Dashboard</a>
</form>

</body>
</html>
