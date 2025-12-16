<?php
// Hàm showArray để in mảng 2 chiều dưới dạng bảng HTML
function showArray($arr)
{
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr><th>STT</th><th>Mã sản phẩm</th><th>Tên sản phẩm</th></tr>"; // Tiêu đề bảng
    
    $stt = 1;  
    foreach($arr as $product) {
        echo "<tr>";
        echo "<td>$stt</td>"; 
        echo "<td>{$product['id']}</td>"; 
        echo "<td>{$product['name']}</td>"; 
        echo "</tr>";
        $stt++; 
    }

    echo "</table>"; 
}

$arr = array();

$r = array("id" => "sp1", "name" => "Sản phẩm 1");
$arr[] = $r;
$r = array("id" => "sp2", "name" => "Sản phẩm 2");
$arr[] = $r;
$r = array("id" => "sp3", "name" => "Sản phẩm 3");
$arr[] = $r;

echo "<h3>Danh sách sản phẩm</h3>";
showArray($arr);  
?>
