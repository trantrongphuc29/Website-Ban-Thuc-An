<?php

try{
$pdh = new PDO("mysql:host=sql309.infinityfree.com;port=3306;dbname=if0_40293397_bookstore", "if0_40293397", "x3sFqZvDMLzG7C");
$pdh->query("  set names 'utf8'"  );
}
catch(Exception $e){
		echo $e->getMessage(); exit;
}

$cat_id = isset($_GET["cat_id"])?$_GET["cat_id"]:"";
$sql ="delete from category where cat_id = :cat_id ";
$arr = array(":cat_id"=>$cat_id);

$stm = $pdh->prepare($sql);
$stm->execute($arr);
$n = $stm->rowCount();
if ($n>0) $thongbao="Da xoa $n loai sach! ";
else $thongbao="Loi xoa!";
?>
<script language="javascript">
alert("<?php echo $thongbao;?>");
window.location = "lab8_3.php";
</script>