<?php
require("config.inc.php");
set_time_limit(0);
if(!$debug) error_reporting(0);
if($_GET["action"]=="exit") {
		setcookie("admin",false);
		//printLogin();
		echo "<script>location.href='admin.php'</script>";
		exit;
}
ob_start();
print '<html>
<head>
<META http-equiv="Content-Type" content=text/html; charset=gb2312>
  <link rel="stylesheet" type="text/css" media="all" href="calendar-win2k-cold-1.css" title="win2k-cold-1" />

  
  <script type="text/javascript" src="js/calendar.js"></script>

  
  <script type="text/javascript" src="js/calendar-zh.js"></script>

  <script type="text/javascript" src="js/calendar-setup.js"></script>
  <script type="text/javascript" >
	function  sall()  	{  
	     var  a  =  document.getElementsByName("ids");
	     for  (var  i=0;  i<a.length;  i++){  
	         a[i].checked  =  true;
	     }  
	       
	} 
	function  clear_all()  	{  
	     var  a  =  document.getElementsByName("ids");
	     for  (var  i=0;  i<a.length;  i++){  
	         a[i].checked  =  false;
	     }  
	       
	} 
	
	function check_password() {
		if(document.getElementById("p1").value!=document.getElementById("p2").value) {alert("�������벻��ͬ"); return false;}
		return true;
	}
	function  del_s()  	{  
	     var  a  =  document.getElementsByName("ids");
	     w = "";
	     idx=0;
	     for  (var  i=0;  i<a.length;  i++){
	     	   if(idx==0) {
	     	   	if(a[i].checked) w+="+id%3D"+a[i].value;
	     	   }else {
	     	   	if(a[i].checked) w+="+or+id%3D"+a[i].value;
	     	   }
	     	   idx++;
	     }
	     location.href="'.$baseurl.'admin.php?action=delete&w="+w;
	} 
	
	function edit_ad(name,content) {
		n = document.getElementById("adname");
		a = document.getElementById("ad");
		n.value=name;
		a.value = content;
		//a.innerHTML = unescape(a.innerHTML);
	}
  </script>
<style><!--
body { font-size:9pt; }
 // -->
</style>
</head>
	<body>
		<h1>����ҳ</h1>
	';

if(!check_admin() && $_COOKIE["admin"]!="1") {
	printLogin();
	exit;
}


setcookie("admin","1");
ob_end_flush();

echo "[<a href='admin.php'>�ϴ��ļ��б�</a>] [<a href='admin.php?action=path'>�����ļ�Ŀ¼����</a>] [<a href='admin.php?action=ext'>�ļ���չ������</a>] [<a href='admin.php?action=time'>�����ϴ����ʱ��</a>] [<a href='admin.php?action=size'>����ϴ��ļ��ֽ�����</a>] [<a href='admin.php?action=ip'>��IP</a>]  [<a href='admin.php?action=iptime'>��ʱ��IP</a>] [<a href='admin.php?action=delete'>����ɾ���ļ�</a>] [<a href='admin.php?action=ad'>���λ����</a>] [<a href='admin.php?action=pass'>��������</a>] [<a href='admin.php?action=exit'>�˳�</a>]";
switch($_GET["action"]) {
	case "ext"	:
		print_ext();
		break;
	case "ip"	:
		print_ip();
		break;
	case "iptime"	:
		print_iptime();
		break;
	case "size"	:
		print_size();
		break;
	case "delete"	:
		print_delete();
		break;
		break;
	case "pass"	:
		print_password();
		break;
	case "time"	:
		print_time();
		break;
	case "path"	:
		print_path();
		break;
	case "ad"	:
		print_ad();
		break;
	default	:
		print_list();
		break;
}
exit;

function print_ip() {
	if($_POST["do"] == "1") {
		$ex = explode("\n",$_POST["ip_list"]);
		$ex = array_map("trim",$ex);
		$content = implode("\",\"",$ex);
		$content = "<?php \n \$ip_list=array(\"".$content."\");\n ?>";
		$fp = fopen("_allow_ip_list.inc.php","w");
		fwrite($fp,$content);
		fclose($fp);
		$success = "<h4>���óɹ���</h4>";
	}
	include("_allow_ip_list.inc.php");
	print '
		<h2>�����ϴ�IP������</h2>
		<h3>ÿ��дһ��IP</h3>
		<form action="admin.php?action=ip" method="POST">
			������IP�б�:<br /><textarea  name="ip_list" cols="30" rows="10">'.implode("\n",$ip_list).'</textarea><br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" value="����" />
		</form>
		'.$success.'
	</body>
	</html>
	';
}

function print_iptime() {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	if($_POST["do"] == "1") {
		$ip = $_POST["ip"];
		$t = time() + $_POST["t"]*60;
		$sql = "insert into deny_ip(ip,utime) values('{$ip}',{$t})";
		if($debug) echo $sql;
		$res=mysql_query($sql);
		if($res) {
			$success = "<h4>���óɹ���</h4>";
		}else{
			$success = "<h4>����ʧ�ܣ����������IP��ʱ���Ƿ���ȷ��</h4>";
		}
	}
	$t = time();
	$sql ="select * from deny_ip where utime > {$t} order by utime desc";
	if($debug)echo "<br />".$sql;
	$res = mysql_query($sql);
	$iptb = "<table>";
	while($row= mysql_fetch_array($res)) {
		$ltime = date("Y-m-d H:i:s",$row['utime']);
		$iptb .= "<tr><td>{$row['ip']}</td><td>&nbsp;&nbsp;{$ltime}</td></tr>";
	}
	$iptb .= "</table>";
	print '
		<h2>������ʱ��IP</h2>
		<h3>����Ҫ���IP��ʱ��(����)</h3>
		<form action="admin.php?action=iptime" method="POST">
			IP:<br /><input type="text" name="ip" />&nbsp;ʱ��(����)<input type="text" name="t" value="60" /><br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" value="���" />
		</form>
		'.$iptb.'
	</body>
	</html>
	';
}

function ad_replace($c,$flg=false) {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	$res = mysql_query("select * from ad");
	while($row = mysql_fetch_array($res)) {
		if(!$flg) {
			$c = str_replace(base64_decode($row["adname"]),base64_decode($row["ad"]),$c);
		}else {
			$adname = base64_decode($row["adname"]);
			$ad = base64_decode($row["ad"]);
			$c = preg_replace("/<!-- {$adname} -->.*<!-- {$adname} -->/",$adname,$c);
		}
	}
	return $c;
}

function print_ad() {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname,$allow_custom_ad;
	//$debug = true;
	if($debug) print_r($_POST);
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	if($_POST["do"] == "1") {
		$adname = $_POST["adname"];
		$ad = $_POST["ad"];
		if( ini_get("magic_quotes_gpc") ) {
			$ad = stripslashes($ad);
			$adname = stripslashes($adname);
		}
		$ad = base64_encode("<!-- {$adname} -->".$ad."<!-- {$adname} -->");
		$adname = base64_encode($adname);
		$sql = "insert into ad(adname,ad) values('{$adname}','{$ad}')";
		if($debug) echo $sql;
		$res=mysql_query($sql);
		if($res) {
			$success = "<h4>����ɹ���</h4>";
		}else{
			$sql = "update ad set adname='{$adname}' , ad='{$ad}' where adname= '{$adname}'";
			$res = mysql_query($sql);
			if($res) {
				$success = "<h4>����ɹ���</h4>";
			}else {
				$success = "<h4>����ʧ�ܣ�DB����</h4>";
			}
		}
	}
	if($_GET["del"]) {
		$id = $_GET["del"];
		if(!is_numeric($id)) die("�����Ƿ�");
		$sql = "delete from ad where id={$id}";
		if($debug) echo $sql;
		$res=mysql_query($sql);
		if($res) {
			$success = "<h4>���óɹ���</h4>";
		}else{
			$success = "<h4>����ʧ�ܣ�DB����</h4>";
		}
	}
	$sql ="select * from ad order by id ";
	if($debug)echo "<br />".$sql;
	$res = mysql_query($sql);
	$iptb = "<table>";
	if(!$allow_custom_ad) $iptb .= "<tr>";
	while($row= mysql_fetch_array($res)) {
		$name = base64_decode($row['adname']);
		$ad = str_replace("\r\n","\\n",htmlspecialchars(base64_decode($row["ad"])));
		$ad = str_replace("\n","\\n",$ad);
		$pad = str_replace(htmlspecialchars("<!-- {$name} -->"),"",$ad);
		$pad = str_replace("'","\'",$pad);
		if($allow_custom_ad) {
			$iptb .= "<tr><td title='{$ad}'>{$name}</td><td>&nbsp;<a href='admin.php?action=ad&del={$row['id']}'  onclick=\"if(!confirm('��ȷ��Ҫɾ�����¹��ô��\\n\\n-----------------------------\\n\\n{$ad}')) return false;\">ɾ��</a></td><td><a href=\"javascript:edit_ad('{$name}','{$pad}');\">�༭</a></td></tr>";
		}else {
			$iptb .= "<td>&nbsp;&nbsp;<a href=\"javascript:edit_ad('{$name}','{$pad}');\">{$name}</a>&nbsp;&nbsp;</td>";
		}
	}
	if(!$allow_custom_ad) $iptb .= "</tr>";
	$iptb .= "</table>";
	if(!$allow_custom_ad)$readonly = "readonly";
	print '
		<h2>����ģ���ʶ</h2>
		<h3>�������ʶ������</h3>
		<h4>'.$success.'</h4>
		<form action="admin.php?action=ad" method="POST">
			��ʶ:<br /><input type="text" name="adname" id="adname" '.$readonly.' /><br />����:<br /><textarea name="ad" id="ad" cols="50" rows="20" ></textarea><br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" value="����" />
		</form>
		'.$iptb.'
	</body>
	</html>
	';
}

function getTime($s,$end="") {
	global $debug;
	if($debug) echo "arg:$s<br />";
	$s = explode("-",$s);
	if( count($s) !=3 ) {
		die("date format error");
	}
	if($end != "") {
		$time = mktime (23,59,59,$s[1],$s[2],$s[0]);
	}else {
		$time = mktime (0,0,0,$s[1],$s[2],$s[0]);
	}
	if($debug) echo "reutrn:$time<br />";	
	return $time;
}

function print_delete() {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname,$page_size;
	if($_POST["start"] != "") {
		$start_time = getTime($_POST["start"]);
		$end_time = getTime($_POST["end"],"end");
		$_GET["w"] = "utime>={$start_time} and utime<={$end_time}";
	}
	if($_POST["do"] == "1") {
		mysql_connect($dbadd,$dbuser,$dbpass);
		mysql_select_db($dbname);
		$sql = "select * from upload_files where ".$_POST["w"];
		$res = mysql_query($sql);
		while($row=mysql_fetch_array($res)) {
			unlink($row["path"]);
		}
		$sql = "delete from upload_files where ".$_POST["w"];
		if(!mysql_query($sql)) die("db error,please try again.".mysql_error()."<br />".$sql);
		if($debug) echo "sql:".$sql;
		$success = "<h4><font color=blue><a href='".$_POST["url"]."'>ɾ���ɹ�</a></font></h4>";
		echo "<META HTTP-EQUIV=\"refresh\" CONTENT='2; URL=admin.php'>";
		echo $success;
		exit;
	}
	if($_GET["w"] != "") {
		print_delete_confirm($_GET["w"],$_SERVER["HTTP_REFERER"]);
		return;
	}
	print '
		<h2>����ɾ���ļ�</h2>
		<h3>����ʱ�䷶Χ</h3>
<form action="admin.php?action=delete" method="POST">
��ʼʱ��: <input type="text" name="start" size="20" value="2002-11-1" id="begin_date_b"><input type="reset" value="..."
onclick="return '."showCalendar('begin_date_b', 'y-m-d')".';">
<BR>����ʱ��: <input type="text" name="end" size="20" value="2004-11-1" id="end_date_b"><input type="reset" value="..."
onclick="return '."showCalendar('end_date_b', 'y-m-d')".';"><br />
				<input type="submit" value="ɾ��" /></form>
		'.$success.'
	</body>
	</html>
	';
}

function print_delete_confirm($w,$url="") {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname,$page_size;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	$w = preg_replace("/^or/","",trim($w));
	$sql = "select count(*) from upload_files where ".$w;
	if($debug) echo $sql;
	$res = mysql_query($sql);
	$row = mysql_fetch_row($res);
	$count = $row[0];
	print '
		<h2>ȷ��ɾ��ҳ</h2>
		<h3>���������ɾ�� <font color=red>'.$count.'</font> ���ļ�����ȷ����</h3>
		<form action="admin.php?action=delete" method="POST">
			<input type="hidden" name="w" value="'.$w.'" />
			<input type="hidden" name="do" value="1" />
			<input type="hidden" name="url" value="'.$url.'" />
			<input type="submit" value="ȷ����ɾ��" />&nbsp;<input type="button" onclick="history.back();" value="ȡ��" />
		</form>
		'.$success.'
	</body>
	</html>
	';
}

function print_list() {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname,$page_size;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	/*
	$res = mysql_query("select count(*) from upload_files");
	if(!$res) die("Error: db error.");
	$row = mysql_fetch_array($res);
	$page_count = floor($row[0]/$page_size);
	*/
	$this_page = $_GET["p"];
	if(!is_numeric($this_page)) $this_page=0;
	$start = $this_page*$page_size;
	$end = $page_size;
	$res = mysql_query("select * from upload_files order by id desc limit {$start},{$end}");
	$this_count =mysql_num_rows($res);
	if($debug) echo "this_count:".$this_count."<br />";
	if(!$res) die("Error: db error.");
	$page_link = "<hr />";
	if($debug) echo "start:".$start."<br />";
	if($start != 0) {
		$pre_page = $this_page-1;
		$page_link .= "<a href='admin.php?p={$pre_page}'>ǰһҳ</a> ";
	}else {
		$page_link .= "ǰһҳ ";
	}
	if($this_count == $page_size) {
		$next_page = $this_page+1;
		$page_link .= "<a href='admin.php?p={$next_page}'>��һҳ</a> ";
	}else {
		$page_link .= "��һҳ ";
	}
	$page_link.="<hr />";
	echo $page_link;
	echo "<table border='1'>";
	while($row = mysql_fetch_array($res)) {
		$upload_date = date("Y-m-d H:i:s",$row["utime"]);
		$name = base64_decode($row["name"]);
		$fname = $name;
		$name = "<a href='{$baseurl}link.php?ref={$row['fkey']}' target='_blank'>".$name."</a>";
		echo "<tr><td><input type='checkbox' id='ids' name='ids' value='{$row['id']}' /></td><td>&nbsp;{$name}&nbsp;</td><td>&nbsp;{$upload_date}&nbsp;</td><td>&nbsp;{$row['ip']}&nbsp;</td><td>&nbsp;<font color='red'>{$row['dcount']}</font>&nbsp;</td><td>&nbsp;<a href='admin.php?action=delete&w=".rawurlencode("id={$row['id']}")."'>Delete</a>&nbsp;&nbsp;<a href='clear_dcount.php?do=1&c={$row['fkey']}'  onclick=\"if(!confirm('��ȷ��Ҫ�����¹�����ش�������ô��\\n\\n-----------------------------\\n\\n{$fname}')) return false;\">��0</a></td></tr>\n";
	}
	echo "<tr><td colspan='6'><a href='javascript:sall();'>ȫѡ</a> <a href='javascript:clear_all();'>ȫ��</a> <a href='javascript:del_s();'>ɾ��ѡ��</a></td></tr>";
	echo "</table>";
	echo $page_link;
}

function print_ext() {
	if($_POST["do"] == "1") {
		$ex = explode(",",$_POST["ext_list"]);
		$content = implode("\",\"",$ex);
		$content = "<?php \n \$ext_list=array(\"".$content."\");\n ?>";
		$fp = fopen("_allow_ext_list.inc.php","w");
		fwrite($fp,$content);
		fclose($fp);
		$success = "<h4>���óɹ���</h4>";
	}
	include("_allow_ext_list.inc.php");
	print '
		<h2>���ò�������ļ����� </h2>
		<h3>ÿ����չ���ö���(,)�ָ���ע���ǰ�Ƕ���(,)</h3>
		<form action="admin.php?action=ext" method="POST">
			���������չ��:<input type="text" name="ext_list" size="50" value="'.implode(",",$ext_list).'"/><br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" value="����" />
		</form>
		'.$success.'
	</body>
	</html>
	';
}

function print_size() {
	if($_POST["do"] == "1") {
		$size = $_POST["size"];
		if(!is_numeric($size)) {
			$success = "<h4>����:ֵ����������</h4>";
		}else{
			$content = "<?php \n \$allow_size={$size};\n ?>";
			$fp = fopen("_allow_size.inc.php","w");
			fwrite($fp,$content);
			fclose($fp);
			$success = "<h4>���óɹ���</h4>";
		}
	}
	include("_allow_size.inc.php");
	print '
		<h2>�����ϴ��ļ�size���ֵ</h2>
		<h3>����һ���ļ���С����(M)</h3>
		<form action="admin.php?action=size" method="POST">
			����ļ��ߴ� :<input type="text" name="size" value="'.$allow_size.'"/> M<br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" value="����" />
		</form>
		'.$success.'
	</body>
	</html>
	';
}

function print_path() {
	global $baseurl;
	if($_POST["do"] == "1") {
		$path = $_POST["path"];
		if( ini_get("magic_quotes_gpc") ) {
			$path = stripslashes($path);
		}
		$path = str_replace("\\","/",$path);
		if(!file_exists($path)) {
			$success = "<h4>����:û�����·��</h4>";
		}else{
			$content = "<?php \n \$uploaddir=\"{$path}/\";\n ?>";
			$fp = fopen("_upload_dir.inc.php","w");
			fwrite($fp,$content);
			fclose($fp);
			$success = "<h4>���óɹ���</h4>";
			}
	}
	include("_upload_dir.inc.php");
	print '
		<h2>���ñ����ļ���Ŀ¼</h2>
		<h3>����һ���������ϵ�Ŀ¼</h3>
		<form action="admin.php?action=path" method="POST">
			·�� :<input type="text" name="path" size="60" value="'.$uploaddir.'"/> <br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" value="����" />
		</form>
		'.$success.'
	</body>
	</html>
	';
}

function print_time() {
	if($_POST["do"] == "1") {
		$t = $_POST["t"];
		if(!is_numeric($t)) {
			$success = "<h4>����:ֵ����������</h4>";
		}else{
			$content = "<?php \n \$time_limit={$t};\n ?>";
			$fp = fopen("_time_limit.inc.php","w");
			fwrite($fp,$content);
			fclose($fp);
			$success = "<h4>���óɹ���</h4>";
		}
	}
	include("_time_limit.inc.php");
	print '
		<h2>�����ϴ��ļ����ʱ������</h2>
		<h3>����һ��ʱ������</h3>
		<form action="admin.php?action=time" method="POST">
			�������:<input type="text" name="t" value="'.$time_limit.'"/><br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" value="����" />
		</form>
		'.$success.'
	</body>
	</html>
	';
}

function printLogin() {
	print '
		<form action="admin.php" method="POST">
			�û���:<input type="text" name="username" /><br />
			�ܡ���:<input type="password" name="password" /><br /><br />
			<input type="submit" value="�ǡ���½" />
		</form>
	</body>
	</html>
	';
}

function check_admin() {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	$sql = "select * from admin where user='admin' limit 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	//die($row["password"] ."|".md5($_POST["password"]));
	if($row["password"] == md5($_POST["password"])) {
		setcookie("admin","1");
		return true;
	}else {
		return false;
	}
}

function print_password() {
	global $debug,$dbadd,$dbuser,$dbpass,$dbname;
	mysql_connect($dbadd,$dbuser,$dbpass);
	mysql_select_db($dbname);
	if($_POST["do"] == "1") {
		$password = md5($_POST["password"]);
		mysql_query("update admin set password='{$password}' where `user`='admin' ");
		$success="���óɹ�";
	}
	print '
		<h2>���ù���Ա����</h2>
		<form action="admin.php?action=pass" method="POST">
			����:<input type="password" name="password" id="p1" />&nbsp;�ٴ�����:<input type="password" name="password2" id="p2" /><br />
			<input type="hidden" name="do" value="1" />
			<input type="submit" onclick="return check_password();" value="����" />
		</form>
		'.$success.'
	</body>
	</html>
	';
}
?>