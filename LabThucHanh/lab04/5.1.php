<?php
// Hàm showArray để in mảng theo dạng bảng HTML
function showArray($arr)
{
    echo "<table border='1'>";
    echo "<tr><th>Index</th><th>Value</th></tr>"; 
    
    foreach($arr as $index => $value) {
        echo "<tr><td>$index</td><td>$value</td></tr>"; 
    }

    echo "</table>";
}

// Tạo mảng và gọi hàm showArray
$a = array(1, 3, 5, 7, 9); // Một mảng đơn giản
echo "<h3>Mảng in ra dạng bảng HTML</h3>";
showArray($a);
?>
