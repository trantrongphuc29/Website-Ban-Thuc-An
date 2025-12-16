<?php
function postIndex($index, $value = "")
{
  if (!isset($_POST[$index]))  return $value;
  return trim($_POST[$index]);
}

function checkUserName($string)
{
  if (preg_match("/^[a-zA-Z0-9._-]*$/", $string))
    return true;
  return false;
}


function checkEmail($string)
{
  echo $string;
  if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $string))
    return true;
  return false;
}

// Kiểm tra mật khẩu: ít nhất 8 ký tự, chứa ít nhất một chữ hoa, một chữ thường và một chữ số
function checkPassword($password)
{
  if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
    return true;
  }
  return false;
}

// Kiểm tra số điện thoại: chỉ chứa số
function checksdt($phone)
{
  if (preg_match("/^[0-9]+$/", $phone)) {
    return true;
  }
  return false;
}

// Kiểm tra ngày sinh theo định dạng dd/mm/yyyy hoặc dd-mm-yyyy
function checkNgaySinh($date)
{
  if (preg_match("/^(0[1-9]|[12][0-9]|3[01])[-\/](0[1-9]|1[0-2])[-\/](19|20)\d\d$/", $date)) {
    return true;
  }
  return false;
}
function formatNgaySinh($date)
{
  if (empty($date)) 
    return false; // Nếu ngày sinh không được nhập
  return true;
}

$sm = postIndex("submit");
$username = postIndex("username");
$password = postIndex("password");
$email = postIndex("email");
$date = postIndex("date");
$date2 = postIndex("date2");
$phone = postIndex("phone");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Lab6_4.2</title>
  <style>
    fieldset {
      width: 60%;
      margin: 50px auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    legend {
      font-size: 40px;
      color: #333;
      font-weight: bold;
    }

    table {
      width: 100%;
    }

    td {
      padding: 10px;
      font-size: 1em;
      color: #555;
    }
  </style>
</head>

<body>
  <fieldset>
    <legend style="margin:0 auto">Đăng ký thông tin </legend>
    <form action="lab06_4.2.php" method="post" enctype="multipart/form-data" id='frm1'>
      <table align="center">
        <tr>
          <td width="88">UserName</td>
          <td width="317"><input type="text" name="username" value="<?php echo $username; ?>" />*</td>
        </tr>
        <tr>
          <td>Mật khẩu</td>
          <td><input type="password" id="password" name="password" />*</td>
        </tr>
        <tr>
          <td></td>
          <td><input type="checkbox" onclick="displayPass()">Hiển thị mật khẩu </td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" />*</td>
        </tr>
        <tr>
          <td>Ngày sinh (text)</td>
          <td><input type="text" name="date" placeholder="dd/mm/yyyy hoặc dd-mm-yyyy" value="<?php echo $date; ?>" />*</td>
        </tr>
        <tr>
          <td>Ngày sinh (date)</td>
          <td><input type="date" name="date2" value="<?php echo $date2; ?>" />*</td>
        </tr>
        <tr>
          <td>Điện thoại</td>
          <td><input type="text" name="phone" /></td>
        </tr>

        <tr>
          <td colspan="2" align="center"><input type="submit" value="submit" name="submit"></td>
        </tr>
      </table>
    </form>
  </fieldset>
  <script>
    // Hàm hiện mật khẩu
    function displayPass() {
      var passwordField = document.getElementById('password');
      var checkbox = document.querySelector('input[type="checkbox"]');
      // Nếu checkbox được đánh dấu, hiển thị mật khẩu
      if (checkbox.checked) {
        passwordField.type = 'text'; // Hiển thị mật khẩu
      } else {
        passwordField.type = 'password'; // Ẩn mật khẩu
      }
    }
  </script>

  <?php

  if ($sm != "") {
  ?>
    <div class="info">
      <?php
      if (checkUserName($username) == false)
        echo "Username: Các ký tự được phép: a-z, A-Z, số 0-9, ký tự ., _ và - <br>";
      elseif (checkPassword($password) == true) {
        echo checkPassword($password);
        // $passCheck = checkPassword($password);
        // echo $passCheck;
      } elseif (checkEmail($email) == false)
        echo "Định dạng email sai!<br>";
      elseif (checksdt($phone) == false)
        echo "Số điện thoại chỉ chứa ký tự số!<br>";
      elseif (checkNgaySinh($date) == false)
        echo "Ngày sinh 'text' phải theo định dạng dd/mm/yyyy hoặc dd-mm-yyyy!<br>";
      elseif (formatNgaySinh($date2) == false)
        echo "Chưa chọn ngày sinh 'date'";
      else {
        echo "✔️ Đăng kí thành công <br />";
      }
      ?>
    <?php
  }
    ?>
</body>


</html>