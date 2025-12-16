<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 2_4</title>
</head>

<body>
<?php
$a=1;
$b=2;
$x="1";
$s1="Xin";
$s2= "chào";
$s=$s1." ".$s2;//ghép chuỗi
echo "a + b = ".($a+$b)."<br/>";
echo "a + x = ".($a+$x)."<br/>";
echo "x + a = ".($x+$a)."<br/>";
echo "Ghép chuỗi: $s"."<br/>";
echo "Phân biệt == và === :";
if($a===$x)
//if($a === (int)$x) //cách 2
//if($a == $x) //cách 1
	echo "a và x giống nhau";
else 
	echo "a và x khác nhau";



echo"<i> <br>Biến a có kiểu số nguyên (int) với giá trị 1.
<br>Biến x có kiểu chuỗi (string) với giá trị 1.
<br>PHP khi thực hiện phép cộng (+) tự động ép kiểu các biến sang kiểu số để thực hiện phép toán, vì vậy a + x và x + a đều cho kết quả 2.
<br>Nhưng trong phép so sánh:
<br>== (so sánh bằng) chỉ so sánh về giá trị, PHP sẽ tự ép kiểu nếu cần để so sánh, nên 1 == 1 trả về true.
<br>=== (so sánh bằng kiểu và giá trị) so sánh cả kiểu dữ liệu và giá trị, nên 1 === 1 trả về false vì a là int, x là string.
<br>dòng 21 hiện tại: sẽ trả về false, dẫn đến in ra a và x khác nhau.
<br>muốn xuất ra a và x giống nhau, có 2 cách:
<br> Cách 1: Dùng so sánh chỉ về giá trị, không xét kiểu (dùng ==) 
<br>if(a == x) Kết quả in ra sẽ là a và x giống nhau vì 1 == 1 đúng.
<br> Cách 2: Ép kiểu biến trước rồi so sánh tuyệt đối
<br>if(a === (int)x) 
</i> "
?>
</body>
</html>