<?php
include "../config/db.php";
$id = $_GET['id'];

$q = $conn->query("
SELECT files.file_path 
FROM files 
WHERE thesis_id=$id
");

$file = $q->fetch_assoc()['file_path'];

header("Content-Disposition: attachment; filename=".basename($file));
readfile($file);
?>