<?php
include '../includes/auth.php';
include '../includes/db.php';

$faculty_id = $_SESSION['user_id'];
$msg = "";


if (isset($_POST['submit'])) {
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);
    $grade = strtoupper(trim($_POST['grade']));


    $existing = $conn->query("
        SELECT * FROM grades 
        WHERE student_id=$student_id AND subject_id=$subject_id AND faculty_id=$faculty_id
    ");

    if ($existing->num_rows > 0) {
        $conn->query("
            UPDATE grades SET grade='$grade'
            WHERE student_id=$student_id AND subject_id=$subject_id AND faculty_id=$faculty_id
        ");
        $msg = "Grade updated successfully!";
    } else {
        $conn->query("
            INSERT INTO grades (student_id, subject_id, faculty_id, grade)
            VALUES ($student_id, $subject_id, $faculty_id, '$grade')
        ");
        $msg = "Grade submitted successfully!";
    }


    $conn->query("
        UPDATE enrollments SET status='completed'
        WHERE student_id=$student_id AND subject_id=$subject_id
    ");
}


$subjects = $conn->query("
    SELECT DISTINCT subjects.id, subjects.subject_name
    FROM subjects
    JOIN enrollments ON subjects.id = enrollments.subject_id
    WHERE enrollments.student_id IN (SELECT student_id FROM enrollments) 
    ORDER BY subjects.subject_name
");


$selected_subject_id = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
$students = [];

if ($selected_subject_id) {
    $students = $conn->query("
        SELECT users.id, users.full_name, users.profile_picture, users.signature, enrollments.status, grades.grade
        FROM enrollments
        JOIN users ON enrollments.student_id = users.id
        LEFT JOIN grades ON grades.student_id = users.id AND grades.subject_id = enrollments.subject_id
        WHERE enrollments.subject_id=$selected_subject_id
        ORDER BY users.full_name
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Submit Grades</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<h3>Submit Grades</h3>
<a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

<?php if($msg): ?>
<div class="alert alert-info"><?= $msg ?></div>
<?php endif; ?>

<form method="GET" class="mb-3 row g-2">
    <div class="col-md-4">
        <select name="subject" class="form-control" required onchange="this.form.submit()">
            <option value="">Select Subject</option>
            <?php while($sub = $subjects->fetch_assoc()): ?>
            <option value="<?= $sub['id'] ?>" <?= ($sub['id']==$selected_subject_id)?'selected':'' ?>>
                <?= $sub['subject_name'] ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>
</form>

<?php if($selected_subject_id && $students->num_rows > 0): ?>
<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>Student</th>
    <th>Profile</th>
    <th>Signature</th>
    <th>Status</th>
    <th>Grade</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php while($s = $students->fetch_assoc()): ?>
<tr>
    <td><?= $s['full_name'] ?></td>
    <td><img src="../uploads/profiles/<?= $s['profile_picture'] ?>" width="60"></td>
    <td><img src="../uploads/signatures/<?= $s['signature'] ?>" width="100"></td>
    <td><?= ucfirst($s['status']) ?></td>
    <td><?= $s['grade'] ?: '-' ?></td>
    <td>
        <form method="POST" class="d-flex">
            <input type="hidden" name="student_id" value="<?= $s['id'] ?>">
            <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
            <input type="text" name="grade" class="form-control form-control-sm me-1" placeholder="Grade" required>
            <button class="btn btn-sm btn-success" name="submit">Submit</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php elseif($selected_subject_id): ?>
<div class="alert alert-warning">No students enrolled in this subject yet.</div>
<?php endif; ?>
</div>
</body>
</html>
