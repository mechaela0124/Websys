<?php
include "../config/db.php";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=thesis_report.csv");

$output = fopen("php://output", "w");
fputcsv($output, ["Title","Status","Year"]);

$q = $conn->query("SELECT title,status,year FROM thesis");
while ($row = $q->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
