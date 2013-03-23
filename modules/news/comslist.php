<?
$title="Список комментариев";
$lines=$lines_at_log;
$gp=$_REQUEST['part'];
if (isset($_REQUEST['part'])){ $gp=$_REQUEST['part']; } else { $gp=1; }
if (($admin || $moder) and ($_REQUEST['user']!="") and ($_REQUEST['user']!==NULL)){
	$usr=htmlspecialchars($_REQUEST['user']);
	$fltr="WHERE `Author`='$usr'";
	echo "exp1; ";
} else if (($user) || (($_REQUEST['user']=="") and ($_REQUEST['user']!==NULL))){
	$usr=$u_id;
	$fltr="WHERE `Author`='$usr'";
	echo "exp2; ";
	var_dump($user);
	echo "===";
	var_dump($_REQUEST['user']);
} else {
	$fltr="";
	echo "exp3; ";
}
$gp-=1;
$part=$gp*$lines;
if (isset($part)){ $start=$part; } else { $start=1; }
$sql=mysql_query("SELECT COUNT(*) FROM `Comments` $fltr");
$row = mysql_fetch_assoc($sql);
$cnt=$row['COUNT(*)'];
$pages=floor($cnt/$lines);
$ost=$pages % $lines;

$sql=mysql_query("SELECT *,(SELECT `Title` FROM `News` WHERE `News`.`Num`=`Comments`.`News_ID`) AS 'NewsTitle' FROM `Comments` $fltr ORDER BY `ID` DESC LIMIT $start,$lines");
//$content="<div class='news_body'><div class='news_title support'>Все комментарии</div><div class='news_text'></div></div>";
if ($cnt>0){
	while ($row = mysql_fetch_assoc($sql)) {
		$c_id=$row['ID'];
		$c_text=$row['Text'];
		$c_news_title=$row['NewsTitle'];
		$c_news_titl=enc_cut($row['NewsTitle'],40);
		$c_news_id=$row['News_ID'];
		$c_author=$row['Author'];
		$dp=date_parse($row['Time']);
		$c_t=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
		$c_date=date('d.m.Y',$c_t+$offset_t);
		$c_time=date('H:i',$c_t+$offset_t);
		
		$sql1=mysql_query("SELECT * FROM `Users` WHERE `Auth_ID`='$c_author' LIMIT 1");
		$row1 = mysql_fetch_assoc($sql1);
		$n_id=$row1['Auth_ID'];
		$n_name=$row1["Name"];
		$n_nick=$row1["Nick"];
		$n_photo=$row1["Photo"];
		
		if (trim($n_nick)==''){ $c_author=$n_name; } else { $c_author=$n_nick; }
		$retprt=$gp+1;
		
		if ($admin || $moder){
				$mod_panel="<a class='rdajax btn btn-mini btn-warning pull-right' data-cont='ajax_temp' href='index.php?act=comment&amp;del_id=$c_id&amp;$id&amp;ret=comments&amp;retpart=$retprt' data-title='Удалить комментарий'><i class='icon-white icon-remove'></i></a>";
		}
		$content.="
						<div class='media well well-small'>
							<a class='pull-left' href='?act=users&amp;profile=$n_id' class='rdajax'>
								<img class='media-object comment-icon' src='$n_photo'>
							</a>
							<div class='media-body'>
							<h4 class='media-heading'>$c_author</h4>
								$c_text
								<div class='btn-toolbar'>
									<div class='btn btn-mini' data-title='Комментарий отправлен $c_date в $c_time'><i class='icon-calendar'></i> $c_date</div>
									<a href='?act=fulltext&id=$c_news_id' class='rdajax btn btn-mini' title='Перейти к новости $c_news_title'>$c_news_titl</a>
									$mod_panel
								</div>
							</div>
						</div>";
/*
		if ($admin || $moder){
			$mod_panel="<a class='pull-right rdajax btn btn-mini btn-warning' href='index.php?act=comment&del_id=$c_id&$id&ret=comments&retpart=$retprt' data-title='Удалить комментарий'><i class='icon-white icon-remove'></i> Удалить</a>";
		}
		$content.="<div class='well'>$mod_panel$c_text
			<div class='btn-toolbar'>
				<div class='btn-group'>
					<a href='?act=users&profile=$n_id' class='rdajax btn btn-mini' data-title='Комментарий от $c_author'><img class='list-icon' src='$n_photo' align='top'> $c_author</a>
					<div class='btn btn-mini' data-title='Комментарий отправлен $c_date в $c_time'><i class='icon-calendar'></i> $c_date</div>
					<a href='?act=fulltext&id=$c_news_id' class='rdajax btn btn-mini' title='Перейти к новости $c_news_title'>$c_news_titl</a>
				</div>
			</div>
		</div>";*/
	}
} else {
	$content.="<div class='info_box'>К сожалению, ничего не нашлось.</div>";
}

for ($i=0; $i<=$pages; $i++){
	$np=$lines*$i;
	if ($gp==$i){ $st="class='rdajax active'"; } else { $st="class='rdajax'"; }
	$show=$i+1;
	$ps.= "<a $st href='index.php?act=comments&part=$show$pg_add'>$show</a>";
}

$content.="</div><div class='paginator'>$ps</div>";
?>
