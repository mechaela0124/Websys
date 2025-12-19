<?php
include "../config/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $error = "Please enter email and password.";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {

            if (password_verify($pass, $user['password'])) {

                // Regenerate session for security
                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ];

                // Activity log
                $uid = $user['id'];
                $conn->query("INSERT INTO activity_logs (user_id, action)
                              VALUES ($uid, 'User Logged In')");

                // Redirect by role
                header("Location: ../dashboard/" . $user['role'] . ".php");
                exit;

            } else {
                $error = "Invalid password.";
            }

        } else {
            $error = "Account not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login | Thesis Archive</title>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-body">

<h4 class="text-center mb-3">Thesis Archive Login</h4>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" novalidate>

    <div class="mb-3">
        <label>Email</label>
        <input class="form-control" type="email" name="email" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input class="form-control" type="password" name="password" required>
    </div>

    <button class="btn btn-primary w-100">Login</button>

</form>

<hr>

<p class="text-center">
    No account?
    <a href="register.php">Register here</a>
</p>

</div>
</div>

</div>
</div>
</div>

</body>
</html>
