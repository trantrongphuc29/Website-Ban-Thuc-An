<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab5_4_2</title>
</head>

<body>
    <fieldset>
        <legend>Form 4.2</legend>
        <form action="Lab5_4_2.php" method="get">
            Nhập tên sản phẩm cần tìm:
            <input type="name" name="ten" require> <br>
            Cách tìm:
            <input type="radio" name="ct" value="gan_dung">Gần đúng
            <input type="radio" name="ct" value="chinh_xac">Chính xác <br>
            Loại sản phẩm: <br>
            <input type="checkbox" name="sp[]" value="Loại 1">Loại 1
            <input type="checkbox" name="sp[]" value="Loại 2">Loại 2
            <input type="checkbox" name="sp[]" value="Loại 3">Loại 3
            <input type="checkbox" name="sp[]" value="Tất cả">Tất cả
            <input type="submit" onclick="">
        </form>
    </fieldset>
    <?php
    if (isset($_GET['ten'])) {
        echo "Tên sản phẩm vừa nhập: " . htmlspecialchars($_GET['ten']);
        echo "<br>";
    }
    if (isset($_GET['ct'])) {
        echo "Cách tìm: " . htmlspecialchars($_GET['ct']);
        echo "<br>";
    }
    if (isset($_GET['sp'])) {
        echo "Loại sản phẩm: ";
        if (is_array($_GET['sp'])) {
            echo implode(", ", $_GET['sp']);
            //implode: Nối các giá trị trong mảng loai[] thành một chuỗi, ngăn cách nhau bằng dấu phẩy.
        }
    } else {
        echo "Chưa chọn loại. ";
    }
    echo "<hr>";
    print_r($_GET);
    ?>
</body>

</html>