<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $tong=0;
        for($i=2; $i<=100; $i++ ) {
            if($i%2==0)
                $tong += $i;
        }
        echo "Tong cac so chan tu 2 den 100 la: ". $tong
    ?>
</body>
</html>