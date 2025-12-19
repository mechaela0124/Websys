<?php
include "../config/db.php";
$id = $_GET['id'];

$conn->query("UPDATE thesis SET status='approved' WHERE id=$id");
header("Location: ../dashboard/admin.php");
