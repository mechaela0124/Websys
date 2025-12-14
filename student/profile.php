<?php
session_start();
include '../includes/db.php';

$id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
?>

<img src="../uploads/profiles/<?= $user['profile_picture'] ?>" width="150">
<h3><?= $user['full_name'] ?></h3>
<img src="../uploads/signatures/<?= $user['signature'] ?>" width="200">
