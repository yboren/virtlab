<html>
<head>
<META http-equiv="Content-Type" content=text/html; charset=utf8>
<title>实验报告提交系统</title>
</head>

<body bgcolor=#FFFFF link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF" text="#000000">
<p align="center">
<br>
<b>
</b><br>
<br>
<br>
<br>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="300" height="60">
<?php require("config.inc.php"); ?>
<h2>匿名上传文件</h2>
<form name="form1" enctype="multipart/form-data" method="post" action="upload.php">
<input type="file" name="file" size="20" >
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $allow_size; ?>"> 
<input type="submit" height=27 width=174 value="上传" border=0 name=submit valign="bottom"> 
</form>
<!--
<h2>输入代码下载文件</h2>
<form name="form2" method="post" action="download.php" target=_blank>
<input type="text" name="ref" size="20" > 
<input type="submit" height=27 width=174 value="下载" border=0 name=submit valign="bottom"> 
</form>
-->
</td>
</tr>
</table>

</body>
</html>
