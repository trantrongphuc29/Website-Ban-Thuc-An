<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bài 5.3 - Giải phương trình bậc 2</title>
</head>
<body>
<h2>Giải phương trình bậc 2: ax² + bx + c = 0</h2>

<form method="post">
    Nhập a: <input type="number" step="any" name="a" required><br><br>
    Nhập b: <input type="number" step="any" name="b" required><br><br>
    Nhập c: <input type="number" step="any" name="c" required><br><br>
    <input type="submit" value="Giải phương trình">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = (float)$_POST["a"];
    $b = (float)$_POST["b"];
    $c = (float)$_POST["c"];

    if ($a == 0) {
        if ($b == 0)
            echo ($c == 0) ? "<p>Phương trình vô số nghiệm.</p>" : "<p>Phương trình vô nghiệm.</p>";
        else
            echo "<p>Phương trình có một nghiệm: x = " . (-$c / $b) . "</p>";
    } else {
        $delta = $b * $b - 4 * $a * $c;
        if ($delta < 0)
            echo "<p>Phương trình vô nghiệm.</p>";
        elseif ($delta == 0) {
            $x = -$b / (2 * $a);
            echo "<p>Phương trình có nghiệm kép: x₁ = x₂ = <b>$x</b></p>";
        } else {
            $x1 = (-$b + sqrt($delta)) / (2 * $a);
            $x2 = (-$b - sqrt($delta)) / (2 * $a);
            echo "<p>Phương trình có hai nghiệm phân biệt:</p>";
            echo "<p>x₁ = <b>$x1</b><br>x₂ = <b>$x2</b></p>";
        }
    }
}
?>
</body>
</html>
