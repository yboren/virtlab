<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>首页</title>
</head>
<body>
<?php
error_reporting(0);
include("header.php");
session_start();
if(!isset($_SESSION["userid"])){
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
}else{
?>
    <p><?php echo $_SESSION["username"];
echo '<a href="logout.php">退出</a>';
echo '<a href="guestbook/index.php">留言簿</a>';
?>
</p>
<a href="machne.php">machine</a>
<?php
}
?>
</body>
<html>
