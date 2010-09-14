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
    <h2>
<?php echo "欢迎";
echo $_SESSION["username"];
echo ',';
echo '<a href="logout.php">退出</a>';
?>
</h2>
<hr/>
<h2>
查看实验内容
</h2>
<p><a href="viewtask.php">点击这里</a>
<hr/>
<h2>当前可用机器：</h2>
<p>
<ul>
<li><a href="machine.php" target="_blank">CentOS 5.4</a></li>
</ul>
<?php
}
?>
<?php
include("footer.php");
?>
</body>
<html>
