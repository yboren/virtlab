<?php
require("config.inc.php");
set_time_limit(0);
error_reporting(0);
if($debug) error_reporting(E_ALL);

$key = $_POST["ref"];

if($key != "") {
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	$res = mysql_query("select * from upload_files where fkey='{$key}' ");
	if(!$res) die("错误: 下载代码错误或文件已被删除");
	$row = mysql_fetch_array($res);
	$path = $row["path"];
	$name = base64_decode($row["name"]);
	if($debug) echo "path:".$path."<br />";
	if(file_exists($path)) {
		//echo $name;
		$res = mysql_query("update upload_files set dcount=dcount+1 where fkey='{$key}'");
		downloadit($path,$name);
	}else {
		die("错误: 下载代码错误或文件已被删除");
	}
}else {
	die("错误: 下载代码错误或文件已被删除");
}

function downloadit($read_file_path,$filename) {
	global $debug;
	$filesize = filesize($read_file_path);
	if($debug) echo "filesize:".$filesize."<br />";
	$fp = fopen($read_file_path,'rb');
	header("Content-type: application/octet-stream;charset=utf-8");
	header("Accept-Ranges: bytes");
	header("Accept-Length: ".$filesize);
	header("Content-Disposition: attachment; filename=" . $filename);
	fpassthru($fp);
}

?>