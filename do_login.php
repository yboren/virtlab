<?php
session_start();
include("dbconnect.php");
include("functions.php");
$username = $_POST["username"];
$password = $_POST["password"];
$sql = "select * from user where username='{$username}' limit 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
if($password != $row["password"]){
    header("Location:msg.php?m=login_error");
    exit;
}
$_SESSION["userid"] = $row["id"];
$_SESSION["username"] = $username;
header("Location:index.php");
?>
