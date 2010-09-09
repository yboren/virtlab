<?php
include("dbconnect.php");
$user = 'renyunbo';
$sql = "select * from user where username = '{$user}' limit 1";
$res = mysql_query($sql);
$row = mysql_fetch_assoc($res);
echo $row["username"];
echo "<br/>";
echo $row["password"];
?>
