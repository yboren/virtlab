<?php
error_reporting(0);
session_start();
if($_POST["op"] == "登录"){
    include("do_login.php");
    exit;
}
include("header.php");
?>
<form  method="post" action="login.php">
  <p>
    <label>输入用户名
    <input name="username" type="text" id="username" />
    </label>
  </p>
  <p>
    <label>输入密码
    <input name="password" type="password" id="password" />
    </label>
  </p>
  <p>
    <label>
    <input type="submit" name="op" value="登录" />
    </label>
  </p>
  <p>&nbsp;</p>
</form>
<?php
include("footer.php");
?>
