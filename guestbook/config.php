<?php

/*////////////////////////////////////////////////////////
	必填变量
////////////////////////////////////////////////////////*/

$db['user'] = "root";//数据库用户名
$db['pass'] = "mysql";//数据库密码
$db['host'] = "localhost";//数据库主机名
$db['db'] = "xen";//数据库名

$message_table = "messages";//最好不要改
$message_body_table = "messages_text";//改动的话请自己相应改动simpleboard.sql的表名

$username = "admin";//管理员帐号
$password = "admin";//管理员密码

$board_title = "PHP留言簿系统";//标题

/*////////////////////////////////////////////////////////
	其他可选变量
////////////////////////////////////////////////////////*/

$default_view = "threaded";//选择默认查看方式，"flat"或者"threaded";
$threads_per_page = 25;//每页帖子数

$header = "";//头部信息，可不写
$footer = "";//脚部信息，可不写

/*/////////////////////////////////////////////////////////////////////////////////////////////
	以下请不要修改，除非你非常了解本系统
/////////////////////////////////////////////////////////////////////////////////////////////*/

$other_view=$default_view=="threaded"?"flat":"threaded";

$settings = $_COOKIE['settings'];

require 'db.php';
$sql = new sql($db);

if (!$sql->connected) die("连接失败！");
?>
