<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bài 5.1 - Phần nguyên và phần dư</title>
</head>
<body>
<h2>Tính phần nguyên và phần dư của a / b</h2>

<form method="post">
    Nhập a: <input type="number" name="a" required><br><br>
    Nhập b: <input type="number" name="b" required><br><br>
    <input type="submit" value="Tính toán">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = (int)$_POST["a"];
    $b = (int)$_POST["b"];

    if ($b == 0) {
        echo "<p><b>Lỗi:</b> Không thể chia cho 0!</p>";
    } else {
        $thuong = intdiv($a, $b); // phần nguyên
        $du = $a % $b;            // phần dư
        echo "<p>Phần nguyên của $a / $b là: <b>$thuong</b></p>";
        echo "<p>Phần dư của $a / $b là: <b>$du</b></p>";
    }
}
?>
</body>
</html>
