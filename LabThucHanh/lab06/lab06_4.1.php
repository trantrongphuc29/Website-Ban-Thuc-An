<?php
function postIndex($index, $value = "")
{
	if (!isset($_POST[$index]))	return $value;
	return trim($_POST[$index]);
}

$username 	= postIndex("username");
$password1	= postIndex("password1");
$password2	= postIndex("password2");
$name		= postIndex("name");
$thong_tin = postIndex("thong_tin");
$sm 		= postIndex("submit");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Lab6_4.1</title>
	<style>
		fieldset {
			width: 50%;
			margin: 100px auto;
		}

		.info {
			width: 600px;
			color: #006;
			background: #6FC;
			margin: 0 auto
		}
	</style>
</head>

<body>
	<fieldset>
		<legend style="margin:0 auto">Thông tin đăng ký</legend>
		<form action="lab06_4.1.php" method="post" enctype="multipart/form-data">
			<table align="center">
				<tr>
					<td>Tên đăng nhập:</td>
					<td><input type="text" name="username" placeholder="ít nhất 6 kỹ tự" value="<?php echo $username; ?>"></td>
				</tr>
				<tr>
					<td>Mật khẩu:</td>
					<td><input type="password" name="password1" placeholder="ít nhất 8 ký tự"/></td>
				</tr>
				<tr>
					<td>Nhập lại mật khẩu:</td>
					<td><input type="password" name="password2" /></td>
				</tr>
				<tr>
					<td>Họ Tên:</td>
					<td><input type="text" name="name" placeholder="ít nhất 2 từ" value="<?php echo $name; ?>" /></td>
				</tr>
				<tr>
					<td>Thông tin: </td>
					<td><textarea name="thong_tin" cols="21" rows="3"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="submit" name="submit"></td>
				</tr>
			</table>
		</form>
	</fieldset>
	<?php

	if ($sm != "") {
		$err = "";
		if (strlen($username) < 6) 		$err .= " Username ít nhất phải 6 ký tự!<br>";
		if ($password1 != $password2) 	$err .= "Mật khẩu và mật khẩu nhập lại không khớp. <br>";
		if (strlen($password1) < 8) 		$err .= "Mật khẩu phải ít nhất 8 ký tự.<br>";
		if (str_word_count($name) < 2) 	$err .= "Họ tên phải chứa ít nhất 2 từ ";
		// 1. Loại bỏ các thẻ HTML trong thong_tin
		$thong_tin = strip_tags($thong_tin);
		// 2. Thay thế các ký tự xuống dòng (\n) bằng <br>
		$thong_tin = nl2br($thong_tin);
		// 3. Thêm dấu \ trước các ký tự nháy đơn ('), dùng addslashes
		$thong_tin_escaped = addslashes($thong_tin);
		// 4. Loại bỏ các ký tự \ trước các ký tự đặc biệt
		$thong_tin_escaped = stripslashes($thong_tin_escaped);
	?>
		<div class="info">
			<?php
			if ($err != "") echo $err;
			else {
				echo "Username: $username <br>";
				$password_md5 = md5($password1);  // Mã hóa bằng MD5 (128bit) 32 ký tự
				$password_sha1 = sha1($password_md5); // Mã hóa kết quả MD5 bằng SHA1 (160bit) 40 ký tự
				$password_sha256 = hash('sha256', $password1); //sha256 64 ký tự HEX
				$password_sha3_256 = hash('sha3-256', $password1);

				echo "Mật khẩu đã mã hóa MD5: " . $password_md5 . "<br>";
				echo "Mật khẩu đã mã hóa SHA1: " . $password_sha1 . "<br>";
				echo "<hr>";
				echo "Mật khẩu đã mã hóa sha256: " . $password_sha256 . "<br>";
				echo "Mật khẩu đã mã hóa sha3-256: " . $password_sha3_256 . "<br>";
				echo "<hr>";
				echo "Họ tên: " . ucwords($name) . "<br>";
				echo "Thông tin đã xử lý: $thong_tin_escaped";
				// echo "Mã hóa MD5 file " .$file . ' :' . md5_file($file);
				// echo "<hr>";
				// echo "Mã hóa SHA1 file " .$file . ' :' . sha1_file($file);
				// echo "<hr>";
				// echo "Mã hóa văn bản với RIPEMD: " .hash('ripemd160',$password1) ;
				// echo "<hr>";
				// echo "Mã hóa nhị phân với RIPEMD: " .hash('ripemd160',$password1,TRUE) ;
				// echo "<hr>";
			}
			?>
		</div>
	<?php

	}
	?>
</body>

</html>