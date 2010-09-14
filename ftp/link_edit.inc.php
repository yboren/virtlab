<div align="center">
<?php
	require("config.inc.php");
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	$res = mysql_query("select * from ad where adname !=''  order by id");
	$idx=0;
	while($row = mysql_fetch_array($res)) {
		$idx++;
		if($idx==2) {		//这里修改下载的位置
			print "<br />";
			include("link.inc.php");
			print "<br />";
		}
		print base64_decode($row["ad"]);
		print "<br />";
	}
?>
</div>