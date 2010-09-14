<?php
error_reporting(0);
ob_start();

require_once 'config.php';

$start = $sql->getmicrotime();
$view = $_GET["view"];
$view=$view==""?$settings[current_view]:$view;
setcookie("settings[current_view]",$view);

	$message_emoticons=array(
		":D" => '<img src="emoticons/bigsmile-smiley.gif" width="15" height="15" alt="" border="0">',
		":(" => '<img src="emoticons/blue-smiley.gif" width="15" height="15" alt="" border="0">',
		":)" => '<img src="emoticons/happy-smiley.gif" width="15" height="15" alt="" border="0">',
		":laugh:" => '<img src="emoticons/laughing-smiley.gif" width="18" height="19" alt="" border="0">',
		":sad:" => '<img src="emoticons/sad-smiley.gif" width="15" height="15" alt="" border="0">',
		";)" => '<img src="emoticons/wink-smiley.gif" width="15" height="15" alt="" border="0">',
		":a" => '<img src="emoticons/a.gif" border="0">',
		":b" => '<img src="emoticons/b.gif" border="0">',
		":c" => '<img src="emoticons/c.gif" border="0">',
		":d" => '<img src="emoticons/d.gif" border="0">');
		
$last_visit = $_COOKIE["last_visit_time"];

if ($header!="") include($header);
?>

<html>
<head>
	<title><?php echo $board_title;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	<link rel="stylesheet" href="forum.css" type="text/css">
	
</head>

<body>

<?php
$id=(int)$_GET["id"];
$sql->query("SELECT * FROM $message_table LEFT JOIN $message_body_table ON $message_table.id=$message_body_table.mesid WHERE $message_table.id='$id'");
if ($sql->num_rows() == 0)
{
	echo("<p align=\"center\">出现错误！！！！！！</p>\n");
}
else
{
	$this_message = $sql->fetch_object();
	$thread = $this_message->parent==0?$this_message->id:$this_message->thread;
	$sql->query("SELECT * FROM $message_table LEFT JOIN $message_body_table ON $message_table.id=$message_body_table.mesid WHERE thread='$thread' OR id='$thread' ORDER BY time ASC");
	if ($view!="flat") $flat_messages[]=$this_message;
	while ($message = $sql->fetch_object())
	{
		if ($view=="flat")
			$flat_messages[]=$message;
		else
			$messages[$message->parent][]=$message;
	}
	?>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" align="center">
		<tr>
			<td align="center">
				<p><a href="index.php"><?php echo $board_title;?>首页</a> | <a href="post.php?replyto=<?php echo $thread;?>">回复</a> | <?php echo $view=="flat"?"<a href=\"?&view=threaded&id=$id\">树状查看</a>": "<a href=\"?id=$id&view=flat\">平板显示</a>";?> | <a href="faq.php">帮助（FAQ）</a></p>
				<p></p>
			</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=1 cellpadding=3 width="100%" class="flat">
					<tr>
						<td class="table_header" width="200" align="center"><strong>作者</strong></td>
						<td class="table_header" align="center"><strong>内容</strong></td>
					</tr>
				</table>
				<table border=0 cellspacing=1 cellpadding=3 width="100%" class="message , flat">
					<?php 
					foreach($flat_messages as $fmessage) {
						$fmessage->name = htmlspecialchars($fmessage->name);
						$fmessage->email = htmlspecialchars($fmessage->email);
						$fmessage->subject = htmlspecialchars($fmessage->subject);
						$fmessage->message = htmlspecialchars($fmessage->message);
						
						$fmessage->message = str_replace("[quote]","<div class=\"quote\">",$fmessage->message);
						$fmessage->message = str_replace("[/quote]","</div>",$fmessage->message);
						
						// urls
						$fmessage->message = preg_replace("/(\[url\])(.*?)(\[\/url\])/s","<a href=\"\\2\">\\2</a>",$fmessage->message);
						$fmessage->message = preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/s","<a href=\"\\1\">\\2</a>",$fmessage->message);
						
						// bold
						$fmessage->message = preg_replace("/(\[b\])(.*?)(\[\/b\])/s","<strong>\\2</strong>",$fmessage->message);
						
						// underline
						$fmessage->message = preg_replace("/(\[u\])(.*?)(\[\/u\])/s","<u>\\2</u>",$fmessage->message);
						
						// italic
						$fmessage->message = preg_replace("/(\[i\])(.*?)(\[\/i\])/s","<em>\\2</em>",$fmessage->message);
						
						reset($message_emoticons);
						while (list($emo_txt,$emo_src)=each($message_emoticons))
						{
							$fmessage->message=str_replace($emo_txt,$emo_src,$fmessage->message);
						}
						?>
					<a name="<?php echo $fmessage->id;?>"></a>
					<tr>
						<td width="200" valign="top">
							<strong>发帖人：</strong><br>
							<?php echo $fmessage->email!=""?"<a href=\"mailto:".stripslashes($fmessage->email)."\">".stripslashes($fmessage->name)."</a>":stripslashes($fmessage->name);?>
						</td>
						<td valign="top">
							<strong><?php echo stripslashes($fmessage->subject);?></strong>
							<hr size=1>
							<?php echo stripslashes(nl2br($fmessage->message));?>
						</td>
					</tr>
					<tr>
						<td class="message">
							<?php echo date("Y年m月d日 G:i:s",$fmessage->time);?>	
						</td>
						<td align="right" class="message">
							<a href="post.php?replyto=<?php echo $fmessage->id;?>">回复</a> | <a href="admin.php?action=delete&id=<?php echo $fmessage->id;?>">删除</a> | <a href="admin.php?action=edit&id=<?php echo $fmessage->id;?>">编辑</a>
						</td>
					</tr>
				<?php } ?>
				</table>
			</td>
		</tr>
		<?php if ($view!="flat") { ?>
		<tr>
			<td>
			<br>
			<?php include 'thread.php'; ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td align="center">
				<P><br></P>
				<?php $end = $sql->getmicrotime();?>
				<?php echo round($sql->query_time,4);?>秒内执行<?php echo $sql->query_count;?>个请求<br>
				<?php echo "页面执行时间 ".round($end-$start,4)."秒";?><br>
			</td>
		</tr>
	</table>
<?php
}
?>

</body>
</html>
<?php if ($footer!="") include($footer); ?>
