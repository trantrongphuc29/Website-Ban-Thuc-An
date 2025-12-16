<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $tong = 997;
        $n=3;
        do {
            $n++;
            $tong-=$n;
        } while($tong >= 0);
        echo "So n nho nhat de 1 + 2 + ... n > 1000 la: ". $n;
        
    ?>
</body>
</html>