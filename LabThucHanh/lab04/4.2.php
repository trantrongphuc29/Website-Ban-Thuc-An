<?php
$a = array(1, -3, 5);
$b = array("a" => 2, "b" => 4, "c" => -6);
echo "<h4>Nội dung mảng a (key-value): </h4>";
foreach($a as $key => $value) {
    echo "([$key] - $value) ";
}

echo "<br>";
// a. Đếm số phần tử có giá trị dương trong mảng $a
$positive_count_a = 0;
foreach($a as $value) {
    if ($value > 0) {
        $positive_count_a++;
    }
}
echo "<br>Số phần tử có giá trị dương trong mảng a: $positive_count_a<br>";
// b. Tạo mảng mới chứa các phần tử dương trong mảng $b
echo "<h4>Nội dung mảng b (key-value): </h4>";
foreach($b as $key => $value) {
    echo "([$key] - $value) ";
}
$c = array();
foreach($b as $key => $value) {
    if ($value > 0) {
        $c[$key] = $value;
    }
}

// In ra mảng mới $c
echo "<h4>Nội dung mảng c (chứa các phần tử dương của mảng b): </h4>";
print_r($c);
?>
