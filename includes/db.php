<?php
$conn = new mysqli("localhost","root","","enrollment_system");
if ($conn->connect_error) {
    die("Database error");
}
?>
