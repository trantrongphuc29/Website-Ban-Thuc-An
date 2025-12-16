<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 2_3</title>
</head>

<body>
<?php 
//const PI=3.14;
define("PI",3.14);
$r=10;
echo "Diện tích hình tròn có bán kính $r là: ".($r*$r*PI);
echo "<i><br>có thể thay thế hàm const PI = 3.14 bằng hàm define() vì: </i>";
echo "<i><br>define() là một hàm, có thể được gọi ở bất kỳ đâu trong mã, kể cả trong hàm.</i>";
echo "<i><br>const là từ khóa, chỉ có thể khai báo hằng số ở cấp toàn cục hoặc trong lớp.</i>";

?>
</body>
</html>