<?php
include "../config/db.php";
$user = $_SESSION['user'];

if ($_POST) {
    if ($_FILES['profile']['name']) {
        $p = "../assets/uploads/profiles/".$_FILES['profile']['name'];
        move_uploaded_file($_FILES['profile']['tmp_name'], $p);
        $conn->query("UPDATE users SET profile_pic='$p' WHERE id={$user['id']}");
    }

    if ($_FILES['signature']['name']) {
        $s = "../assets/uploads/signatures/".$_FILES['signature']['name'];
        move_uploaded_file($_FILES['signature']['tmp_name'], $s);
        $conn->query("UPDATE users SET signature='$s' WHERE id={$user['id']}");
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    Profile Picture: <input type="file" name="profile"><br><br>
    Signature: <input type="file" name="signature"><br><br>
    <button>Save</button>
</form>
