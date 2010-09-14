<?php
error_reporting(0);
ob_start();

require_once 'config.php';

$start = $sql->getmicrotime();

if ($header!="") include($header);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title><?php echo $board_title;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	<link rel="stylesheet" href="forum.css" type="text/css">
	
	<script language="javascript">
	function emo($e)
	{
		document.postform.message.value=document.postform.message.value+$e;
	}
	</script>
</head>

<body>

	<table border=0 cellspacing=0 cellpadding=0 width="100%" align="center">
		<tr>
			<td align="center">
				<p><a href="index.php"><?php echo $board_title;?>首页</a> | <a href="faq.php">帮助（FAQ）</a></p>
				<p></p>
			</td>
		</tr>
		<tr>
			<td>
			
			<?php
			$action = $_POST["action"];
			$parentid = $_POST["parentid"];
			$name = $_POST["name"];
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			$email = $_POST["email"];
			$topic_emoticon = $_POST["topic_emoticon"];
			if ($action=="post")
			{
			?>
			<table border=0 cellspacing=1 cellpadding=3 width="70%" align="center" class="flat"><tr><td>
				<?php
				$parent=(int)$parentid;
				if (empty($name))
				{
					echo "您忘记填写您的名字了。 请点击浏览器的后退标签填写完整并重试。";
				}
				else if (empty($subject))
				{
					echo "您忘记填写您的留言标题了。 请点击浏览器的后退标签填写完整并重试。";
				}
				else if (empty($message))
				{
					echo "您忘记填写您的留言内容了。 请点击浏览器的后退标签填写完整并重试。";
				}
				else
				{
					if ($parent == 0)
						$thread = $parent = 0;
						
					$sql->query("SELECT id,thread,parent FROM $message_table WHERE id='$parent'");
					if ($sql->num_rows() == 0)
					{
						// bad parent, create a new post
						$parent = 0;
						$thread = 0;
					}
					else
					{
						$m = $sql->fetch_object();
						$thread = $m->parent==0?$m->id:$m->thread;
					}
					
					$name=trim(addslashes($name));
					$subject=trim(addslashes($subject));
					$message=trim(addslashes($message));
					$email=trim(addslashes($email));
					$topic_emoticon=(int)$topic_emoticon;
					$topic_emoticon=$topic_emoticon>4?0:$topic_emoticon;
					
					$q1=$sql->query("INSERT INTO $message_table (parent,thread,name,email,subject,time,ip,topic_emoticon) VALUES('$parent','$thread','$name','$email','$subject','".time()."','".$_SERVER["REMOTE_ADDR"]."','$topic_emoticon')");
					if ($sql->query[$q1])
					{
						$pid=mysql_insert_id();
						$sql->query("INSERT INTO $message_body_table (mesid,message) VALUES('$pid','$message')");
						echo "您的内容已经成功提交。如果您不想等待或者浏览器没有反应，请点 <a href=\"view.php?id=$pid#$pid\">这里</a> 查看您的内容 。";
						?>
						<script language="javascript">
							setTimeout("location='view.php?id=<?php echo $pid;?>#<?php echo $pid;?>'",3500);
						</script>
						<?php
					}
					else
					{
						echo "SQL发生错误。 请联系管理员。";
					}	
				}
				?>
			</td></tr></table>
			<?php
			}
			else
			{
				$parentid=0;
				$replyto=(int)$_GET["replyto"];
				if ($replyto > 0)
				{
					$sql->query("SELECT $message_table.*,$message_body_table.message FROM $message_table,$message_body_table WHERE id='$replyto' AND mesid='$replyto'");
					if ($sql->num_rows() > 0)
					{
						$message = $sql->fetch_object();
						$quote  = "[b]".stripslashes(stripslashes($message->name))." 写道：[/b]\n";
						$quote .= "[quote]".stripslashes(stripslashes($message->message))."[/quote]";
						$resubject = strtolower(substr($message->subject,0,3))=="re:"?stripslashes($message->subject):"Re: ".stripslashes($message->subject);
						$resubject = stripslashes($resubject);
						$parentid = $message->id;
					}
				}
				?>
					<table width="100%" border=0 cellspacing=1 cellpadding=3 class="flat">
					<form action="post.php" method="POST" name="postform">
					<input type="hidden" name="parentid" value="<?php echo $parentid;?>">
					<input type="hidden" name="action" value="post">
					<tr>
						<td colspan=2 class="table_header"><strong>发新帖</strong></td>
					</tr>
					<tr>
						<td width="150"><strong>名字</strong>:</td><td><input type="text" name="name" class="textbox" size=40 value=""></td>
					</tr>
					<tr>
						<td width="150"><strong>email</strong>:</td><td><input type="text" name="email" class="textbox" size=40 value=""></td>
					</tr>
					<tr>
						<td width="150"><strong>标题</strong>:</td><td><input type="text" name="subject" class="textbox" size=75 value="<?php echo $resubject;?>"></td>
					</tr>
					<tr>
						<td width="150"><strong>发帖标记</strong>:</td>
						<td>
						<input type="radio" name="topic_emoticon" value="0" checked selected> 无
						&nbsp;<input type="radio" name="topic_emoticon" value="1"><img src="emoticons/exclamation.gif" width="15" height="15" alt="" border="0">
						&nbsp;<input type="radio" name="topic_emoticon" value="2"><img src="emoticons/question.gif" width="15" height="15" alt="" border="0">
						&nbsp;<input type="radio" name="topic_emoticon" value="3"><img src="emoticons/thumbsup.gif" width="15" height="15" alt="" border="0">
						&nbsp;<input type="radio" name="topic_emoticon" value="4"><img src="emoticons/thumbsdown.gif" width="15" height="15" alt="" border="0">
						</td>
					</tr>
					<tr>
					<td valign=top width="150"><strong>内容</strong>:</td><td><textarea wrap="virtual" name="message" cols=70 rows=10 class="textbox"><?php echo $quote;?></textarea></td>
					</tr>
					<tr>
						<td width="150"><strong>发帖心情</strong>: </td>
						<td>
							<table border=0 cellspacing=3 cellpadding=0>
								<tr>
									<td onClick="javascript:emo(':D');" style="cursor:pointer;"><img src="emoticons/bigsmile-smiley.gif" width="15" height="15" alt="" border="0"></td>
									<td onClick="javascript:emo(':(');" style="cursor:pointer;"><img src="emoticons/blue-smiley.gif" width="15" height="15" alt="" border="0"></td>
									<td onClick="javascript:emo(':)');" style="cursor:pointer;"><img src="emoticons/happy-smiley.gif" width="15" height="15" alt="" border="0"></td>
									<td onClick="javascript:emo(':laugh:');" style="cursor:pointer;"><img src="emoticons/laughing-smiley.gif" width="18" height="19" alt="" border="0"></td>
									<td onClick="javascript:emo(':sad:');" style="cursor:pointer;"><img src="emoticons/sad-smiley.gif" width="15" height="15" alt="" border="0"></td>
									<td onClick="javascript:emo(';)');" style="cursor:pointer;"><img src="emoticons/wink-smiley.gif" width="15" height="15" alt="" border="0"></td>
								</tr>
							</table>
							<table border="0" cellspacing="3" cellpadding="0" width="222" height="24">
								<tr>
									<td onClick="javascript:emo(':a');" style="cursor:pointer;"><img src="emoticons/a.gif" alt="" border="0"></td>
									<td onClick="javascript:emo(':d');" style="cursor:pointer;"><img src="emoticons/d.gif" alt="" border="0"></td>
									<td onClick="javascript:emo(':c');" style="cursor:pointer;"><img src="emoticons/c.gif" alt="" border="0"></td>
									<td onClick="javascript:emo(':b');" style="cursor:pointer;"><img src="emoticons/b.gif" alt="" border="0"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=2  class="table_header"><input type="submit" class="button" value="提  交"></td>
					</tr>
					</form>
					</table>
			</td>
		</tr>
		<tr>
			<td>
			<br>
			<p>
			<a href="faq.php#boardcode">bbcode</a><strong>可用</strong><br>
			html<strong>禁用</strong>
			</p>
			<?php
			}
			?>
			</td>
		</tr>
	</table>

</body>
</html>
<?php if ($footer!="") include($footer); ?>
