<?php
function showArray($arr)
{
    foreach($arr as $k => $v)
    {
        echo "<br> $k - $v ";    
    }    
}

$a = array(6, 2, 7, 8, 5); 
$b = array("a" => 4, "b" => 2, "c" => 3, "d" => 8);

echo "<h3>Mảng ban đầu</h3>";
echo "Mảng a: ";
showArray($a);
echo "<br>Mảng b: ";
showArray($b);


rsort($a);  

arsort($b);  // Sắp xếp mảng $b theo chiều giảm dần

echo "<h3>Mảng sau khi sắp xếp theo chiều giảm dần</h3>";
echo "Mảng a: ";
showArray($a);
echo "<br>Mảng b (giữ lại khóa): ";
showArray($b);
?>
