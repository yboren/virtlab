<?php
error_reporting(0);
// topic emoticons
$topic_emoticons=array();
$topic_emoticons[0]='default.gif';
$topic_emoticons[1]='exclamation.gif';
$topic_emoticons[2]='question.gif';
$topic_emoticons[3]='thumbsup.gif';
$topic_emoticons[4]='thumbsdown.gif';

if (count($messages[0]) > 0) {
?>
			<table width="100%" border=0 cellspacing=1 cellpadding=3 class="flat">
				<tr>
					<td class="table_header" align="center" width="51">　</td>
					<td class="table_header" align="center">　</td>
					<td class="table_header" width="40%" align="center"><strong>主题</strong></td>
					<td class="table_header" align="center" width="72"><strong>回复</strong></td>
					<td class="table_header" width="11%" align="center"><strong>作者</strong></td>
					<td class="table_header" width="27%" align="center"><strong>最后发表</strong></td>
				</tr>
				<?php foreach($messages[0] as $leaf) { ?>
				<tr>
					<td align="center" width="51">
					<span class="<?php echo $last_visit<$leaf->time?"new":"notnew"?>"?>new!</span></td>
					<td align="center"><?php echo $leaf->topic_emoticon==0?"<img src=\"tree-blank.gif\" width=15 height=15>":"<img src=\"emoticons/".$topic_emoticons[$leaf->topic_emoticon]."\">";?></td>
					<td><a href="view.php?id=<?php echo $leaf->id.$viewstr;?>"><?php echo stripslashes($leaf->subject);?></a></td>
					<td align="center" width="72"><?php echo (int)$thread_counts[$leaf->id];?></td>
					<td align="center"><?php echo $leaf->email!=""?"<a href=\"mailto:".stripslashes($leaf->email)."\">".stripslashes($leaf->name)."</a>":stripslashes($leaf->name);?></td>
					<td align="center">
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
							<tr>
								<td width="100%"><span class="latest_post"><?php echo date("y年n月d日 G:i:s",$last_reply[$leaf->id]->time);?><br>
								by <?php echo stripslashes($last_reply[$leaf->id]->name);?></span></td>
								<td><a href="view.php?id=<?php echo $leaf->id.$viewstr;?>#<?php echo $last_reply[$leaf->id]->id;?>" class="latest_post_link">&nbsp;&gt;&nbsp;</a>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php } ?>
			</table>
<?php
}
else
{
	echo "<p align=\"center\">论坛尚未有任何帖子！</p>";
}
?>
