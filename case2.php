<?php

$name = isset($_GET['name']) ? $_GET['name'] : "Unknown Student";
$score = isset($_GET['score']) ? (int)$_GET['score'] : 0;

$grade = "";
$remarks = "";

if ($score >= 95 && $score <= 100) {
    $grade = "A (Excellent)";
    $remarks = "Outstanding Performance!";
} elseif ($score >= 90 && $score <= 94) {
    $grade = "B (Very Good)";
    $remarks = "Great Job!";
} elseif ($score >= 85 && $score <= 89) {
    $grade = "C (Good)";
    $remarks = "Good effort, keep it up!";
} elseif ($score >= 75 && $score <= 84) {
    $grade = "D (Needs Improvement)";
    $remarks = "Work harder next time.";
} else {
    $grade = "F (Failed)";
    $remarks = "You need to improve.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Grade Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 450px;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }
        .info {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 16px;
        }
        .info:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #111;
        }
        .remarks {
            margin-top: 20px;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
        }
        .A { background: #eafbea; color: #2d7a2d; }
        .B { background: #e8f5e9; color: #1b5e20; }
        .C { background: #fff9c4; color: #9e7700; }
        .D { background: #ffe082; color: #8d6e00; }
        .F { background: #ffcdd2; color: #b71c1c; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Student Grade Result</h2>
        <div class="info"><span class="label">Name:</span> <span class="value"><?= htmlspecialchars($name) ?></span></div>
        <div class="info"><span class="label">Score:</span> <span class="value"><?= $score ?></span></div>
        <div class="info"><span class="label">Grade:</span> <span class="value"><?= $grade ?></span></div>
        <div class="remarks <?= substr($grade, 0, 1) ?>">
            <?= $remarks ?>
        </div>
    </div>
</body>
</html>
