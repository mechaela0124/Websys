<?php
include '../includes/db.php';

$subject_id = $_GET['subject'];

$result = $conn->query("
    SELECT users.full_name, users.profile_picture, users.signature
    FROM enrollments
    JOIN users ON enrollments.student_id = users.id
    WHERE enrollments.subject_id = $subject_id
");

while ($row = $result->fetch_assoc()) {
    echo "<img src='../uploads/profiles/{$row['profile_picture']}' width='50'>";
    echo "<p>{$row['full_name']}</p>";
    echo "<img src='../uploads/signatures/{$row['signature']}' width='100'><hr>";
}
?>
