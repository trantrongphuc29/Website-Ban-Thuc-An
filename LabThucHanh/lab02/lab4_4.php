<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>lab 2_5</title>
</head>

<body>
<?php
	//include("lab2_5a.php");
	

	if(isset($x))
		echo "Giá trị của x là: $x";
	else
		echo "Biến x không tồn tại";

echo"<i><br>kết quả :Biến x không tồn tại  
<br> giải thích:
<br>include(lab2_5a.php); có nhiệm vụ chèn nội dung từ file lab2_5a.php vào vị trí đó, giả sử trong lab2_5a.php có khai báo biến x.
<br>Nếu bạn comment hoặc xóa dòng include, biến x không được khai báo.
<br>Khi gọi isset(x), PHP trả về false vì biến không tồn tại trong phạm vi hiện tại.
<br>Vì vậy, đoạn else được thực thi và thông báo Biến x không tồn tại được in ra.</i>"
?>
</body>
</html>