<?php
function BCC($n=6, $colorHead="green", $color1="blue", $color2="gray")
{
?>
    <table>
        <tr>
            <td bgcolor="<?php echo $colorHead; ?>" colspan="3">Bảng cửu chương <?php echo $n; ?></td>
        </tr>
        <?php
        for ($i = 1; $i <= 10; $i++) {
        ?>
            <tr bgcolor="<?php if ($i % 2 != 0) echo $color1;
                            else echo $color2; ?>">
                <td><?php echo $n; ?></td>
                <td><?php echo $i; ?></td>
                <td><?php echo $n * $i; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}

function BanCo($size=8)
{
?>
    <div id="banco">
        <?php
        for ($i = 1; $i <= $size; $i++) {
            for ($j = 1; $j <= $size; $j++) {
                $classCss = (($i + $j) % 2) == 0 ? "cellWhite" : "cellBlack";
                echo "<div class='$classCss'> $i - $j</div>";
            }
            echo "<div class='clear' />";
        }
        ?>
    </div>
<?php
}

function aggregateFunctions()
{
    $functions = [
        'BCC',
        'BanCo'
    ];
    $result = "";
    foreach ($functions as $function) {
        if (function_exists($function)) {
            $result .= $function() . " ";
        }
    }
    return trim($result);
}

echo aggregateFunctions();
?>