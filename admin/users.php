<?php
include '../includes/auth.php';
include '../includes/db.php';

$msg = "";

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $password_raw = $_POST['password'];

    if (empty($username) || empty($email) || empty($role) || empty($password_raw)) {
        $msg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $msg = "Username or email already exists.";
        } else {
           
            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $username, $email, $password, $role);
            if ($insert->execute()) {
                $msg = "User added successfully!";
            } else {
                $msg = "Error adding user.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
 
    if ($id == $_SESSION['user_id']) {
        $msg = "You cannot delete your own account.";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $msg = "User deleted.";
        } else {
            $msg = "Error deleting user.";
        }
        $stmt->close();
    }
}

$users = $conn->query("SELECT id, username, email, role FROM users ORDER BY username ASC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Manage Users</h3>
    <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>


    <div class="card mb-4 p-3">
        <h5>Add New User</h5>
        <form method="POST" class="row g-3" autocomplete="off">
            <div class="col-md-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="col-md-4">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="col-md-3">
                <select class="form-control" name="role" required>
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="col-md-12">
                <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </div>
        </form>
    </div>


    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <a href="?delete=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
                    <?php else: ?>
                    <span class="text-muted">Cannot delete self</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
