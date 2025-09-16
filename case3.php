<!DOCTYPE html>
<html>
<head>
    <title>Multiplication Table</title>
</head>
<body>

<form method="post">
    Rows: <input type="number" name="rows" required>
    Columns: <input type="number" name="cols" required>
    <input type="submit" value="Generate"> <br> <br>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rows = $_POST["rows"];
    $cols = $_POST["cols"];

    echo "<table border='1' cellpadding='5' cellspacing='0'>";

    
    echo "<tr><td>X</td>";
    for ($c = 1; $c <= $cols; $c++) {
        echo "<td>$c</td>";
    }
    echo "</tr>";


    for ($r = 1; $r <= $rows; $r++) {
        echo "<tr>";
        echo "<td>$r</td>"; 
        for ($c = 1; $c <= $cols; $c++) {
            $val = $r * $c;
            if ($val % 2 != 0) {
                echo "<td style='background:yellow; font-weight:bold;'>$val</td>";
            } else {
                echo "<td>$val</td>";
            }
        }
        echo "</tr>";
    }

    echo "</table>";
}
?>

</body>
</html>
