<?php
include '../includes/auth.php';
include '../includes/db.php';

$student_id = $_SESSION['user_id'];
$msg = "";
$min_grade = 3;


if (isset($_GET['enroll'])) {
    $subject_id = intval($_GET['enroll']);


    $sub = $conn->query("SELECT prerequisite_id, subject_name FROM subjects WHERE id=$subject_id")->fetch_assoc();
    $prereq = $sub['prerequisite_id'];

   
    if ($prereq) {
        $check = $conn->query("
            SELECT grades.grade 
            FROM enrollments
            LEFT JOIN grades ON grades.student_id = enrollments.student_id AND grades.subject_id = enrollments.subject_id
            WHERE enrollments.student_id=$student_id 
            AND enrollments.subject_id=$prereq
        ")->fetch_assoc();

        if (!$check || $check['grade'] === NULL || $check['grade'] < $min_grade) {
            $msg = "Cannot enroll in '{$sub['subject_name']}'. Prerequisite not passed or grade below $min_grade.";
        }
    }

    // allowed to enroll
    if ($msg == "") {
        $already = $conn->query("SELECT * FROM enrollments WHERE student_id=$student_id AND subject_id=$subject_id");
        if ($already->num_rows == 0) {
            $conn->query("INSERT INTO enrollments (student_id, subject_id) VALUES ($student_id, $subject_id)");
            $msg = "Successfully enrolled in '{$sub['subject_name']}'!";
        } else {
            $msg = "Already enrolled in '{$sub['subject_name']}'.";
        }
    }
}


$subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");

// enrolled sub
$enrolled_subjects = [];
$enrolled_result = $conn->query("
    SELECT subjects.id, subjects.subject_code, subjects.subject_name, subjects.units, subjects.prerequisite_id, enrollments.student_id, grades.grade, enrollments.status
    FROM enrollments
    JOIN subjects ON enrollments.subject_id = subjects.id
    LEFT JOIN grades ON grades.student_id = enrollments.student_id AND grades.subject_id = enrollments.subject_id
    WHERE enrollments.student_id = $student_id
");

while ($row = $enrolled_result->fetch_assoc()) {
    $enrolled_subjects[$row['id']] = $row;
}


$available_subjects = [];
while ($row = $subjects->fetch_assoc()) {
    $id = $row['id'];

    if (isset($enrolled_subjects[$id])) continue;

    // Check prerequisite
    $prereq_passed = true;
    if ($row['prerequisite_id']) {
        $prereq_id = $row['prerequisite_id'];
        if (!isset($enrolled_subjects[$prereq_id])) {
            $prereq_passed = false;
        } else {
            $grade = $enrolled_subjects[$prereq_id]['grade'];
            if ($grade === NULL || $grade < $min_grade) {
                $prereq_passed = false; 
            }
        }
    }

    if ($prereq_passed) $available_subjects[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enroll Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Enroll in Subjects</h3>
    <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <h5>Available Subjects</h5>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Units</th>
                    <th>Prerequisite</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($available_subjects as $row): ?>
                    <?php
                    $prereq_name = '';
                    if ($row['prerequisite_id']) {
                        $pr = $conn->query("SELECT subject_name FROM subjects WHERE id=" . $row['prerequisite_id'])->fetch_assoc();
                        $prereq_name = $pr['subject_name'];
                    }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['subject_code']) ?></td>
                        <td><?= htmlspecialchars($row['subject_name']) ?></td>
                        <td><?= htmlspecialchars($row['units']) ?></td>
                        <td><?= $prereq_name ?: 'None' ?></td>
                        <td>
                            <a href="?enroll=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Enroll</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h5>My Enrolled Subjects & Grades</h5>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Grade</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($enrolled_subjects as $row):
                    $grade = $row['grade'];

                    if ($grade === NULL) {
                        $status = 'Incomplete';
                        $display_grade = 'INC';
                    } elseif ($grade == 3) {
                        $status = 'Passed';
                        $display_grade = $grade;
                    } elseif ($grade == 4) {
                        $status = 'Failed';
                        $display_grade = $grade;
                    } else {
                        $status = 'Unknown';
                        $display_grade = $grade;
                    }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['subject_name']) ?></td>
                        <td><?= $status ?></td>
                        <td><?= $display_grade ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
