<?php
require("config.inc.php");
set_time_limit(0);
if(!$debug) error_reporting(0);

$real_name = str_replace(" ","_",$_FILES['file']['name']);
$save_name = uniqid("f");

if($debug) {
	echo "<META http-equiv=\"Content-Type\" content=text/html; charset=UTF-8>";
	echo "realname:".$real_name."<br />";
}

if( !checkTime() ) die("错误:上传时间限制,请稍后上传");
if( !checkip() ) die("错误:IP被禁止.");
if( !checktype($real_name) && count($ext_list) ) die("错误: 文件类型错误");
if($debug) "check type is ok <br />";
if($savedir_type == "TYPE") {
	$uploadfile = $uploaddir . fileExtName($real_name) ."/".$save_name;
}elseif($savedir_type == "DATE") {
	$tdate = date("Ymd");
	if(!file_exists($uploaddir.$tdate)) mkdir($uploaddir.$tdate);
	$uploadfile = $uploaddir . $tdate ."/".$save_name;
}else {
	$uploadfile = $uploaddir . "/".$save_name;
}
if( !checksize($_FILES['file']['tmp_name']) ) die("错误: 文件太大了");

if( !saveit($uploadfile) ) die("错误: 保存文件失败");
if( !save2db($save_name,$real_name,$uploadfile) ) die("错误: 数据库错误");
setcookie("ut",time());
echo "文件上传成功！<br />";
echo "链接地址:".getFileLink($save_name);
exit;

function getFileLink($key) {
	global $baseurl;
	return $baseurl."link.php?ref={$key}";
}

function save2db($key,$name,$path) {
	global $dbadd,$dbuser,$dbpass,$dbname,$debug;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	if($debug && mysql_error() != "") echo "mysql 错误:".mysql_error()."<br />";
	$name = base64_encode($name);
	$path = mysql_real_escape_string($path);
	$ip = getIp();
	$sql = "insert into upload_files(fkey,name,path,utime,ip) values('{$key}','{$name}','{$path}',".time().",'{$ip}')";
	if($debug) echo "sql:".$sql."<br />";
	$res = mysql_query($sql);
	if($debug && !$res) echo "mysql 错误:".mysql_error()."<br />";
	return $res;
}

function checkTime() {
	global $time_limit;
	$ut = $_COOKIE["ut"];
	$t = time();
	if(($t - $ut)>$time_limit) {
		return true;
	}else {
		return false;
	}
}

function saveit($uploadfile) {
	global $debug;
	if($debug) echo "uploadfile:".$uploadfile."<br />";
	if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
	   return false;
	}
	return true;
}

function checksize($filename) {
	$size = getAllowSize();
	if(filesize($filename)>$size) {
		return false;
	}else{
		return true;
	}
}

function getAllowSize() {
	//TODO
	global $allow_size;
	return $allow_size*1000000;
}

function checktype($filename) {
	global $ext_list,$debug,$uploaddir,$allow_ext;
	$ext = fileExtName($filename);
	$allow_list = $ext_list;
	//$allow_list = array_map(upper
	if($debug) {
		echo "filename:".$filename."<br />";
		echo "ext:".$ext."<br />";
		echo "ext_list:".implode("|",$ext_list)."<br />";
	}
	if(in_array($ext,$allow_list)) {
		$r =  true;
	}else {
		$r = false;
	}
	if(!$allow_ext) $r = !$r;
	if($r) {
		if(!file_exists($uploaddir.$ext)) mkdir($uploaddir.$ext);
	}
	return $r;
}

function fileExtName ($fStr) {
	$retval = "";
	$pt = strrpos($fStr, ".");
	if ($pt) $retval = substr($fStr, $pt+1, strlen($fStr) - $pt);
	return ($retval);
}

function getIp() {
	if($_SERVER['HTTP_CLIENT_IP']){
	     $onlineip=$_SERVER['HTTP_CLIENT_IP'];
	}elseif($_SERVER['HTTP_X_FORWARDED_FOR']){
	     $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
	     $onlineip=$_SERVER['REMOTE_ADDR'];
	}
	return $onlineip;
}

function checkip() {
	global $ip_list;
	$ip_list = addTimeIp($ip_list);
	$ip = getIp();
	if(in_array($ip,$ip_list)) {
		return false;
	}else {
		return true;
	}
}

function addTimeIp($ip_list) {
	global $dbadd,$dbuser,$dbpass,$dbname,$debug;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	if($debug && mysql_error() != "") echo "mysql 错误:".mysql_error()."<br />";
	$t = time();
	$sql = "select * from deny_ip where utime>{$t}";
	$res = mysql_query($sql);
	while($row=mysql_fetch_array($res)) {
		$ip_list[] = $row["ip"];
	}
	return $ip_list;
}

?>