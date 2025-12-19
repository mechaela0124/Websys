<?php
$conn = new mysqli("localhost", "root", "", "thesis_archive");

if ($conn->connect_error) {
    die("Database Connection Failed");
}
session_start();
?>
