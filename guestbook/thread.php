<?php
error_reporting(0);
$join = "<img src=\"tree-join.gif\" width=\"12\" height=\"15\">";
$end = "<img src=\"tree-end.gif\" width=\"12\" height=\"15\">";
$blank = "<img src=\"tree-blank.gif\" width=\"12\" height=\"15\">";
$vert = "<img src=\"tree-vert.gif\" width=\"12\" height=\"15\">";

// 发帖标记
$topic_emoticons=array();
$topic_emoticons[0]='default.gif';
$topic_emoticons[1]='exclamation.gif';
$topic_emoticons[2]='question.gif';
$topic_emoticons[3]='thumbsup.gif';
$topic_emoticons[4]='thumbsdown.gif';

$thread_start=$sql->getmicrotime();

$c=0;
function thread_flat(&$tree,&$leaves,$branchid=0,$level=0)
{
	global $c;
	foreach($leaves[$branchid] as $leaf)
	{
		$leaf->level=$level;
		$tree[]=$leaf;
		$c++;
		if (is_array($leaves[$leaf->id]))
			thread_flat($tree,$leaves,$leaf->id,$level+1);
	}
	return $tree;
}

$tree=thread_flat($tree,$messages);
?>

			<table width="100%" border=0 cellspacing=1 cellpadding=3 class="flat">
				<tr>
					<td class="table_header" align="center">&nbsp;</td>
					<td class="table_header" align="center">&nbsp;</td>
					<td class="table_header" width="60%" align="center"><strong>主题</strong></td>
					<td class="table_header" width="15%" align="center"><strong>作者</strong></td>
					<td class="table_header" width="25%" align="center"><strong>日期</strong></td>
				</tr>
				<?php foreach($tree as $leaf) { 
				$leaf->name=stripslashes($leaf->name);
				$leaf->subject=stripslashes($leaf->subject);
				$leaf->email=htmlentities($leaf->email);
				?>
				
				<tr>
					<td align="center"<?php echo $leaf->id==$id?" class=\"highlight\"":"";?>><span class="<?php echo $last_visit<$leaf->time?"new":"notnew"?>">new!</span></td>
					<td align="center"<?php echo $leaf->id==$id?" class=\"highlight\"":"";?>><?php echo $leaf->topic_emoticon==0?"<img src=\"tree-blank.gif\" width=15 height=15>":"<img src=\"emoticons/".$topic_emoticons[$leaf->topic_emoticon]."\">";?></td>
					<td<?php echo $leaf->id==$id?" class=\"highlight\"":"";?>>
						<table border=0 cellspacing=0 cellpadding=0><tr>
							<td<?php echo $leaf->id==$id?" class=\"highlight\"":"";?>><?php
							    $array[$leaf->level + 1] = count($messages[$leaf->id]);
							    $array[$leaf->level]--;
							    for ($i = 0; $i < $leaf->level; $i++) {
							        if ($array[$i] > 0) echo($vert);
							        elseif ($array[$i] == 0) echo($blank);
							    }
							    if ($array[$leaf->level] > 0) echo($join);
							    elseif ($array[$leaf->level] == 0 && $leaf->parent != 0) echo($end);
							    //else echo($blank);
							?></td>
							<td<?php echo $leaf->id==$id?" class=\"highlight\"":"";?>><a href="view.php?id=<?php echo $leaf->id.$viewstr;?>"><?php echo stripslashes($leaf->subject);?></a></td>
						</tr></table>
					</td>
					<td align="center"<?php echo $leaf->id==$id?" class=\"highlight\"":"";?>><?php echo $leaf->email!=""?"<a href=\"mailto:".stripslashes($leaf->email)."\">".stripslashes($leaf->name)."</a>":stripslashes($leaf->name);?></td>
					<td align="center"<?php echo $leaf->id==$id?" class=\"highlight\"":"";?>><span class="latest_post"><?php echo date("Y年n月d日 G:i:s",$leaf->time);?></span></td>
				</tr>
				<?php } ?>
			</table>
