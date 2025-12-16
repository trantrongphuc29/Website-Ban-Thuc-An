<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab2_2</title>
</head>

<body>
<?php
$a = 1;
$kq = 0;
function f()
{
    $GLOBALS['b'] = 2;  // Thay đổi giá trị của b toàn cục
    // Không có global $kq, nên $kq trong phạm vi hàm f() là một biến cục bộ và không ảnh hưởng đến toàn cục
    $kq = $GLOBALS['a'] + $GLOBALS['b'];  // Biến này chỉ có giá trị trong hàm f()
}
f();
echo "a = $a<br/>";
echo "b = $b<br/>";
echo "kq = $kq<br/>";
echo "<i>giá trị của $kq sẽ không được thay đổi khi xóa bỏ từ khóa global vì biến $kq không còn được tham chiếu đến toàn cục trong hàm f()</i>"
?>
</body>
</html>