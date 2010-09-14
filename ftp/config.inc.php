<?php

$dbadd = "localhost";								//你的mysql地址，如是就是本机不用改。
$dbuser = "root";									//mysql用户名 ----需要修改----
$dbpass = "12347890";								//mysql密码 ----需要修改----
$dbname = "download";								//mysql库的名字，提前建一个，或者用已经有的。 ----需要修改----
$basedir = "E:/javafogre/docs/PHP_Mysql_0605/sources/12/";		//网站程序在服务器上的路径 ----需要修改----
$baseurl = "http://localhost/12/";					//网站的url ----需要修改----
$admin = "111";										//后台管理密码 ----需要修改----
$page_size = 10;									//后台文件列表每页的数量 ----可以修改----
$allow_ext = false;
$savedir_type = "TYPE";			// TYPE | DATE
$debug = false;										//调试用的，平时就设置成false就可以了。 ----不用改----
include("_allow_ext_list.inc.php");					//扩展名配置，由后台生成的 ----不能改----
include("_allow_ip_list.inc.php");						//封IP配置，由后台生成 ----不能改----
include("_allow_size.inc.php");						//上传大下限制，就后台生成。 ----不能改----
include("_time_limit.inc.php");
include("_upload_dir.inc.php");
?>