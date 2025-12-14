<?php
include '../includes/auth.php';
include '../includes/db.php';

$msg = "";

// Add subject
if (isset($_POST['add'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $units = intval($_POST['units']);
    $prereq = !empty($_POST['prerequisite']) ? intval($_POST['prerequisite']) : NULL;

    $stmt = $conn->prepare("
        INSERT INTO subjects (subject_code, subject_name, units, prerequisite_id)
        VALUES (?,?,?,?)
    ");
    $stmt->bind_param("ssii", $code, $name, $units, $prereq);
    if ($stmt->execute()) {
        $msg = "Subject added successfully!";
    } else {
        $msg = "Error adding subject: " . $stmt->error;
    }
}

// Delete subject
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);


    $check = $conn->query("SELECT * FROM enrollments WHERE subject_id=$id");
    if ($check->num_rows > 0) {
        $msg = "Cannot delete subject. It is assigned to some students.";
    } else {
        if ($conn->query("DELETE FROM subjects WHERE id=$id")) {
            $msg = "Subject deleted successfully.";
        } else {
            $msg = "Error deleting subject: " . $conn->error;
        }
    }
}

// Fetch all subjects
$subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");

$prerequisites = $conn->query("SELECT id, subject_name FROM subjects ORDER BY subject_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Subjects</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<h3>Manage Subjects</h3>
<a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

<?php if($msg): ?>
<div class="alert alert-info"><?= $msg ?></div>
<?php endif; ?>

<!-- add sub -->
<div class="card mb-4 p-3">
<h5>Add New Subject</h5>
<form method="POST" class="row g-2">
    <div class="col-md-2">
        <input type="text" class="form-control" name="code" placeholder="Code" required>
    </div>
    <div class="col-md-3">
        <input type="text" class="form-control" name="name" placeholder="Subject Name" required>
    </div>
    <div class="col-md-2">
        <input type="number" class="form-control" name="units" placeholder="Units" required>
    </div>
    <div class="col-md-3">
        <select name="prerequisite" class="form-control">
            <option value="">No Prerequisite</option>
            <?php 
            $prerequisites->data_seek(0); 
            while($pre = $prerequisites->fetch_assoc()): ?>
                <option value="<?= $pre['id'] ?>"><?= $pre['subject_name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100" name="add">Add</button>
    </div>
</form>
</div>


<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>Code</th>
    <th>Name</th>
    <th>Units</th>
    <th>Prerequisite</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($sub = $subjects->fetch_assoc()): ?>
    <?php
        $prereq_name = "";
        if($sub['prerequisite_id']) {
            $pr = $conn->query("SELECT subject_name FROM subjects WHERE id=".$sub['prerequisite_id'])->fetch_assoc();
            $prereq_name = $pr['subject_name'];
        }
    ?>
<tr>
    <td><?= $sub['subject_code'] ?></td>
    <td><?= $sub['subject_name'] ?></td>
    <td><?= $sub['units'] ?></td>
    <td><?= $prereq_name ?: 'None' ?></td>
    <td>
        <a href="subjects.php?delete=<?= $sub['id'] ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('Are you sure you want to delete this subject?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</body>
</html>
