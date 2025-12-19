<?php
session_start();
include "../config/db.php";

/* Log activity before destroying session */
if (isset($_SESSION['user']['id'])) {
    $uid = $_SESSION['user']['id'];
    $conn->query("INSERT INTO activity_logs (user_id, action) 
                  VALUES ($uid, 'User Logged Out')");
}

/* Unset all session variables */
$_SESSION = [];

/* Destroy the session */
session_destroy();

/* Prevent back-button access */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* Redirect to login page */
header("Location: login.php");
exit;
