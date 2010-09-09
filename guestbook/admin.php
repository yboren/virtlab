<?php
error_reporting(0);
ob_start();

require_once 'config.php';

$start = $sql->getmicrotime();

$refresh=false;

if (addslashes($_SERVER['PHP_AUTH_USER']) != $username || addslashes($_SERVER['PHP_AUTH_PW']) != $password ) {
	header('WWW-Authenticate: Basic realm="版主管理"');
    header('HTTP/1.0 401 Unauthorized');
	echo "对不起，您无权进入！";
	die();
}
else {

if ($header!="") include($header);
?>

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

<p align="center">
<a href="index.php"><?php echo $board_title;?>首页</a> | <a href="faq.php">帮助（FAQ）</a> | <a href="#" onclick="javascript:alert('关闭浏览器就是登出了！');">注销</a>
</p>
<P></P>
<table border=0 cellspacing=1 cellpadding=3 align="center" width="100%" class="flat">
	<tr>
		<td class="post_message">
		<?php
			$action = $_REQUEST["action"];
			$id = $_REQUEST["id"];
			if ($action == "delete")
			{
				$id=(int)$id;
				$sql->query("SELECT * FROM $message_table WHERE id='$id'");
				if ($sql->num_rows() == 0)
				{
					echo "无效帖子ID";
				}
				else
				{
					$message = $sql->fetch_object();
					?>
					<form action="admin.php" method="post" name="myform">
					<input type="hidden" name="action" value="deletepostnow">
					<input type="hidden" name="id" value="<?php echo $message->id;?>">
					您将要删除 <strong><?php echo stripslashes(stripslashes($message->subject));?></strong>.<br>
					<input type="radio" name="removechildren" value="0" checked> 不删除该主题回复<br>
					<input type="radio" name="removechildren" value="1"> 删除所有与该主题相关的所有回复<br>
					<br>
					<a href="#" onClick="document.myform.submit();">继  续</a> | <a href="admin.php">取  消</a>
					</form>
					<?php
				}
			}
			else if ($action == "edit")
			{
				$id=(int)$id;
				$sql->query("SELECT * FROM $message_table LEFT JOIN $message_body_table ON $message_table.id=$message_body_table.mesid WHERE $message_table.id='$id'");
				if ($sql->num_rows() > 0)
				{
					$message = $sql->fetch_object();
					?>
					<table width="100%" border=0 cellspacing=1 cellpadding=3 class="flat">
					<form action="admin.php" method="POST" name="postform">
					<input type="hidden" name="id" value="<?php echo $message->id;?>">
					<input type="hidden" name="action" value="editpostnow">
					<tr>
						<td colspan=2 class="table_header"><strong>编  辑</strong></td>
					</tr>
					<tr>
						<td width="150"><strong>名字</strong>:</td><td><input type="text" name="name" class="textbox" size=40 value="<?php echo stripslashes(stripslashes($message->name));?>"></td>
					</tr>
					<tr>
						<td width="150"><strong>email</strong>:</td><td><input type="text" name="email" class="textbox" size=40 value="<?php echo stripslashes(stripslashes($message->email));?>"></td>
					</tr>
					<tr>
						<td width="150"><strong>标题</strong>:</td><td><input type="text" name="subject" class="textbox" size=75 value="<?php echo stripslashes(stripslashes($message->subject));?>"></td>
					</tr>
					<tr>
						<td width="150"><strong>发帖标记</strong>:</td>
						<td>
						<input type="radio" name="topic_emoticon" value="0"<?php echo $message->topic_emoticon==0?" checked selected":"";?>> 无
						&nbsp;<input type="radio" name="topic_emoticon" value="1"<?php echo $message->topic_emoticon==1?" checked selected":"";?>><img src="emoticons/exclamation.gif" width="15" height="15" alt="" border="0">
						&nbsp;<input type="radio" name="topic_emoticon" value="2"<?php echo $message->topic_emoticon==2?" checked selected":"";?>><img src="emoticons/question.gif" width="15" height="15" alt="" border="0">
						&nbsp;<input type="radio" name="topic_emoticon" value="3"<?php echo $message->topic_emoticon==3?" checked selected":"";?>><img src="emoticons/thumbsup.gif" width="15" height="15" alt="" border="0">
						&nbsp;<input type="radio" name="topic_emoticon" value="4"<?php echo $message->topic_emoticon==4?" checked selected":"";?>><img src="emoticons/thumbsdown.gif" width="15" height="15" alt="" border="0">
						</td>
					</tr>
					<tr>
					<td valign=top width="150"><strong>内容</strong>:</td><td><textarea wrap="virtual" name="message" cols=70 rows=10 class="textbox"><?php echo stripslashes(stripslashes($message->message));?></textarea></td>
					</tr>
					<tr>
						<td width="150"><strong>表情</strong>: </td>
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
						<td colspan=2 class="table_header"><input type="submit" class="button" value="提 交"> <input type="button" class="button" value="取 消" onclick="location='admin.php';"></td>
					</tr>
					</form>
					</table>
					<?php
				}
				else
				{
					echo "无效帖子ID";
				}
			}
			else if ($action == "editpostnow")
			{
				$id=(int)$id;
				$sql->query("SELECT id FROM $message_table WHERE id='$id'");
				if ($sql->num_rows() > 0)
				{
					$name = $_POST["name"];
					$subject = $_POST["subject"];
					$message = $_POST["message"];
					$email = $_POST["email"];
					$topic_emoticon = $_POST["topic_emoticon"];
					$sql->query("UPDATE $message_table SET name='".addslashes($name)."', email='".addslashes($email)."', subject='".addslashes($subject)."', topic_emoticon='".((int)$topic_emoticon)."' WHERE id='$id'");
					$sql->query("UPDATE $message_body_table SET message='".addslashes($message)."' WHERE mesid='$id'");
					if ($sql->query[$sql->query_count] && $sql->query[$sql->query_count-1])
						echo "帖子内容已经更新。 点击 <a href=\"view.php?id=$id\">这里</a> 查看。";
					else
						echo "文章更新时发生错误，请重试。 如果不行请联系管理员。";
				}
				else
				{
					echo "无效帖子ID";
				}
			}
			else if ($action == "deletepostnow")
			{
				$id=(int)$id;
				$sql->query("SELECT parent,thread,subject FROM $message_table WHERE id='$id'");
				if ($sql->num_rows() == 0)
				{
					echo "无效帖子ID";
				}
				else
				{
					$message = $sql->fetch_object();
					$removechildren=(int)$_REQUEST["removechildren"];
					if ($removechildren==1)
					{
						$thread=$message->thread==0?$id:$message->thread;
						$sql->query("SELECT id,parent,thread FROM $message_table WHERE thread='$thread'");
						
						while($child=$sql->fetch_object())
						{
							$children[$child->parent]=$child;
						}
						
						function delete_children($id)
						{
							global $children;
							if (count($children[$id]) > 0)
							{
								foreach($children[$id] as $child)
								{
									$sql->query("DELETE FROM $message_table WHERE id='$child->id'");
									$sql->query("DELETE FROM $message_body_table WHERE mesid='child->$id'");
									delete_children($child->id);
								}
							}
						}
						
						delete_children($id);
					}
					else
					{
						$sql->query("UPDATE $message_table SET parent='$message->parent' WHERE parent='$message->id'");
					}
					$sql->query("DELETE FROM $message_table WHERE id='$id'");
					$sql->query("DELETE FROM $message_body_table WHERE mesid='$id'");
					echo "帖子内容已经成功删除";
				}
				
			}
			else
			{
				echo "欢迎使用PHP留言簿系统管理页";
			}
		?>
		</td>
	</tr>
</table>
<br>
<p align="center">
			<?php echo round($sql->query_time,4);?>秒内执行 <?php echo $sql->query_count;?> 个请求 
			<?php echo "页面生成时间： ".round($end-$start,4)."秒";?>
			<br>
			</p>

</body>
</html>

<?php
if ($footer!="") include($footer);
}
?>
