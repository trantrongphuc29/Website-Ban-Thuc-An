<?php
$a = array();
$b = array(1, 3, 5); 
$c = array("a"=>2, "1"=>4, "c"=>6);
echo "<h3>Mảng ban đầu</h3>";
echo "Mảng a: ";
print_r($a);
echo "<br>Mảng b: ";
print_r($b);
echo "<br>Mảng c: ";
print_r($c);
echo "<br>-----------------------";
echo "<h3>Mảng sau khi xóa phần tử [1]</h3>";
unset($b["1"]);
unset($c["1"]); 
echo "Mảng a sau khi thay đổi:";
print_r($a);
echo "<br>Mảng b sau khi thay đổi:";
print_r($b);
echo "<br>Mảng c sau khi thay đổi:";
print_r($c);
?>