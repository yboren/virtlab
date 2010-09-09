<?php
include("header.php");
switch($_GET["m"]){
case "login_error":
    $msg = '对不起，用户名或密码错误。<br/>请返回重新填写。<br/>';
    $href = '<a href="login.php">返回</a>';
    break;
}
?>

<?php
echo $msg;
echo $href;
?>
