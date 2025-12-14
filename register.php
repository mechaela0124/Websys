<?php
include 'includes/db.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST['role'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $profile = time()."_".$_FILES['profile']['name'];
    $signature = time()."_".$_FILES['signature']['name'];

    move_uploaded_file($_FILES['profile']['tmp_name'], "uploads/profiles/$profile");
    move_uploaded_file($_FILES['signature']['tmp_name'], "uploads/signatures/$signature");

    $stmt = $conn->prepare("
        INSERT INTO users (role,full_name,email,password,profile_picture,signature)
        VALUES (?,?,?,?,?,?)
    ");
    $stmt->bind_param("ssssss", $role, $name, $email, $password, $profile, $signature);

    if ($stmt->execute()) {
        $msg = "Registration successful! You can now login.";
    } else {
        $msg = "Email already exists.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-5">
<div class="card p-4 shadow">
<h4 class="text-center">User Registration</h4>

<?php if($msg): ?>
<div class="alert alert-info"><?= $msg ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <select name="role" class="form-control mb-2" required>
        <option value="">Select Role</option>
        <option value="student">Student</option>
        <option value="faculty">Faculty</option>
        <option value="admin">Administrator</option>
    </select>

    <input class="form-control mb-2" name="name" placeholder="Full Name" required>
    <input type="email" class="form-control mb-2" name="email" placeholder="Email" required>
    <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>

    <label>Profile Picture</label>
    <input type="file" class="form-control mb-2" name="profile" required>

    <label>Signature</label>
    <input type="file" class="form-control mb-3" name="signature" required>

    <button class="btn btn-primary w-100">Register</button>
</form>

<a href="login.php" class="d-block text-center mt-3">Already have an account?</a>
</div>
</div>
</body>
</html>
