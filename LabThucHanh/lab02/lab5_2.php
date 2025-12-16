<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bài 5.2 - Kiểm tra kiểu số</title>
</head>
<body>
<h2>Kiểm tra số nguyên hay số thực</h2>

<form method="post">
    Nhập số a: <input type="text" name="a" required>
    <input type="submit" value="Kiểm tra">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = $_POST["a"];

    if (filter_var($a, FILTER_VALIDATE_INT) !== false)
        echo "<p><b>$a</b> là số nguyên.</p>";
    elseif (filter_var($a, FILTER_VALIDATE_FLOAT) !== false)
        echo "<p><b>$a</b> là số thực.</p>";
    else
        echo "<p><b>$a</b> không phải là số hợp lệ.</p>";
}
?>
</body>
</html>
