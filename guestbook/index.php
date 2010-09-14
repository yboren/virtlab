<?php
error_reporting(0);
ob_start();

require_once 'config.php';

$start = $sql->getmicrotime();
$view=$_GET["view"];
$view=$view==""?$settings[current_view]:$view;
setcookie("settings[current_view]",$view);

$last_visit = $_COOKIE["last_visit_time"];
setcookie("last_visit_time",time(),time()+7776000);

/*//////////////// Start selecting messages, prepare them for threading, etc... /////////////////*/
$page = (int)$_GET["page"];
$page = $page<1?1:$page;
$offset = ($page-1)*$threads_per_page;

$sql->query("SELECT * FROM $message_table WHERE parent='0' ORDER BY id DESC");
$total=$sql->num_rows();
$done=0;
for ($i=0; $i<$total; $i++)
{
	$message=$sql->fetch_object();
	if ($i >= $offset) {
		$threadids[]=$message->id;
		$messages[$message->parent][]=$message;
		$last_reply[$message->id] = $message;
		$done++;
	}
	if ($done==$threads_per_page) break;
}

if (count($threadids) > 0) {
	$idstr = @join("','",$threadids);
	$sql->query("SELECT id,parent,thread,subject,name,time,topic_emoticon FROM $message_table WHERE thread IN ('$idstr') AND id NOT IN ('$idstr')");
	while ($message = $sql->fetch_object())
	{
		$messages[$message->parent][]=$message;
		$thread_counts[$message->thread]++;
		$last_reply[$message->thread]=$last_reply[$message->thread]->time<$message->time?$message:$last_reply[$message->thread];
	}
}

//@rsort($messages[0]);

if ($header!="") include($header);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title><?php echo $board_title;?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	<link rel="stylesheet" href="forum.css" type="text/css">
	
</head>

<body>

<table border=0 cellspacing=0 cellpadding=0 width="100%" align="center">
	<tr>
		<td align="center">
			<p><a href="post.php">发新帖</a> | <?php echo $view=="flat"?"<a href=\"?view=threaded&page=$page\">树型查看</a>": "<a href=\"?view=flat&page=$page\">平板查看</a>";?> | <a href="faq.php">帮助（FAQ）</a></p>
			
			<p align="right"><?php
			if (count($messages[0]) > 0) {
				echo "Page: ";
				if (($page-2) > 1) 
				{
					echo("<a href=\"?page=1\">1</a>");
					echo(" ...");
				}
				for ($i=($page-2)<=0?1:($page-2); $i<= $page+2 && $i<= ceil($total/$threads_per_page); $i++)
				{
					if ($page == $i)
					{
						echo(" <strong>$i</strong>");
					}
					else
					{
						echo(" <a href=\"?page=$i\">$i</a>");
					}
				}
				if ($page+2 < ceil($total/$threads_per_page))
				{
					echo("<td>...</td>");
					echo("<a href=\"?page=".ceil($total/$threads_per_page)."\">".ceil($total/$threads_per_page)."</a>");
				}
			}
			?></p>
		</td>
	</tr>
	<tr>
		<td>
			<?php
			if (count($messages) > 0)
			{
				if ($view=="flat")
					include_once('flat.php'); 
				else
					include_once('thread.php');
			}
			else
			{
				echo("<p align=\"center\">");
				echo("留言簿尚未有任何留言。");
				echo("</p>");
			}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<br>
			<p align="center"><span class="new">new!</span> 新帖&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="notnew">new!</span>无新帖</p>
		</td>
	</tr>
	<tr>
		<td>
			<br>
			<?php
			$end = $sql->getmicrotime();
			?>
			<p align="center">
			<?php echo round($sql->query_time,4);?>秒内执行 <?php echo $sql->query_count;?> 个请求 
			<?php echo "页面生成时间： ".round($end-$start,4)."秒";?>
			<br>
			</p>
		</td>
	</tr>
</table>

</body>
</html>
<?php if ($footer!="") include($footer); ?>
