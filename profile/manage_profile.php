<?php
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, name, profile_pic, signature FROM users ORDER BY name ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Profile Pictures & Signatures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        img.profile-pic, img.signature {
            max-height: 50px;
            max-width: 100px;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Manage Profile Pictures & Signatures</h3>
    <a href="../dashboard/admin.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Profile Picture</th>
            <th>Signature</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td>
                    <?php if ($user['profile_pic'] && file_exists("../" . $user['profile_pic'])): ?>
                        <img src="../<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" class="profile-pic">
                    <?php else: ?>
                        <span class="text-muted">No picture</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($user['signature'] && file_exists("../" . $user['signature'])): ?>
                        <img src="../<?= htmlspecialchars($user['signature']) ?>" alt="Signature" class="signature">
                    <?php else: ?>
                        <span class="text-muted">No signature</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_profile.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
