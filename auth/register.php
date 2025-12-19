<?php
include "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* Upload profile picture */
    $profilePath = null;
    if (!empty($_FILES['profile']['name'])) {
        $profilePath = "../assets/uploads/profiles/" . time() . $_FILES['profile']['name'];
        move_uploaded_file($_FILES['profile']['tmp_name'], $profilePath);
    }

    /* Upload signature */
    $signaturePath = null;
    if (!empty($_FILES['signature']['name'])) {
        $signaturePath = "../assets/uploads/signatures/" . time() . $_FILES['signature']['name'];
        move_uploaded_file($_FILES['signature']['tmp_name'], $signaturePath);
    }

    $stmt = $conn->prepare("
        INSERT INTO users (name,email,password,role,profile_pic,signature)
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssssss",
        $name,
        $email,
        $pass,
        $role,
        $profilePath,
        $signaturePath
    );

    $stmt->execute();

    /* Activity Log */
    $uid = $conn->insert_id;
    $conn->query("INSERT INTO activity_logs (user_id, action)
                  VALUES ($uid, 'User Registered')");

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">
<h3>User Registration</h3>

<form method="POST" enctype="multipart/form-data">

    <input class="form-control mb-2" name="name" required placeholder="Full Name">

    <input class="form-control mb-2" name="email" type="email" required placeholder="Email">

    <input class="form-control mb-2" name="password" type="password" required placeholder="Password">

    <select class="form-control mb-2" name="role" required>
        <option value="student">Student</option>
        <option value="reviewer">Reviewer</option>
        <option value="admin">Admin</option>
    </select>

    <label>Profile Picture</label>
    <input type="file" name="profile" class="form-control mb-2">

    <label>Signature</label>
    <input type="file" name="signature" class="form-control mb-2">

    <button class="btn btn-primary">Register</button>
    <a href="login.php" class="btn btn-secondary">Back to Login</a>

</form>
</body>
</html>
