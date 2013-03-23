<?
$now_time=date('r');
$content="<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version=\"2.0\">
<channel>
<title>$vk_share_title</title>
<link>http://".$_SERVER['HTTP_HOST']."</link>
<language>ru</language>
<pubDate>$now_time</pubDate>
<lastBuildDate>$now_time</lastBuildDate>
<generator>RD CMS</generator>";


$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`,`News`.`NoComments`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `Category` NOT IN (SELECT `ID` FROM `Categories` WHERE `HideMain`=1) $_news_sorting LIMIT $news_at_home";
$sql=mysql_query($sql_getcontents);
while ($row = mysql_fetch_assoc($sql)) {
	$dp=date_parse($row['Time']);
	$news_time=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
	$news_time=date('r',$news_time+$offset_t);
	$news_preview=(trim($row['Preview'])=='') ? '' : (((substr($row['Preview'],0,8))=='[attach]') ? '<img src="'. str_replace('[attach]',$_uploader_path,$row['Preview']) .'" alt="Изображение"/>' : '<img src="'.$row['Preview'].'" alt="Изображение"/>');
	$news_raw_text=$row['Text'];
	$news_raw_text=str_replace("<spoiler>","[ Спойлер ]<br>",$news_raw_text);
	$news_text=preg_replace("#<spoiler=([^>]*)>#","[ Спойлер: $1 ]",$news_text);
	$news_raw_text=str_replace("</spoiler>","[ ====== ]",$news_raw_text);
	$news_raw_text=str_replace("<a","<a class='news_link'",$news_raw_text);
	$news_raw_text=str_replace("class='rd_ajax'",'class="rd_ajax"',$news_raw_text);
	$news_raw_text=str_replace('class="rd_ajax"','',$news_raw_text);
	$news_raw_text=str_replace("&#39;","'",$news_raw_text);
	$news_raw_text=str_replace('[attach]',$_uploader_path,$news_raw_text);
	$news_views=$row['Views'];
	
	$news_title=$row['Title'];
	$news_cat=$row['Category'];
	
	$news_num=$row['Num'];
	
	if (trim($row['A_Nick'])==''){
		$n_author_name=$row['A_Name'];
	} else {
		$n_author_name=$row['A_Nick'];
	}
	$n_author_img=$row['A_Photo'];
	$n_author_id=$row['Author'];
	
	$n_hr_pos=strpos($news_raw_text,'<hr>');
	if ($n_hr_pos === false){
		if ($short_sym>0){
			$news_text=html_cut($news_raw_text,$short_sym);
			$news_text.="<br><a href='http://".$_SERVER['HTTP_HOST']."/a/$news_num'>Читать далее</a>";
		} else {
			$news_text=$news_raw_text;
		}
	} else {
		$news_text=str_replace("<hr>","",$news_raw_text);
		$news_text=substr($news_text,0,$n_hr_pos);
		$news_text.="<br><a href='http://".$_SERVER['HTTP_HOST']."/a/$news_num'>Читать далее</a>";
	}
	
	$news_coms=$row['Coms'];
	if ($news_coms==0){
		$coms_show="Нет комментариев";
	} else {
		$coms_show="Комментарии: <b>$news_coms</b>";
	}
	
	if (trim($news_preview)==''){
		$n_article=$news_text;
	} else {
		$n_article=$news_preview . '<br/>' . $news_text;
	}
	
	if (trim($news_title)==''){
		$art_title="&lt;нет заголовка&gt;";
	} else {
		$art_title="$news_title";
	}
	
	$art_title=str_replace("&","&amp;",$art_title);
	$n_article=str_replace("&","&amp;",$n_article);
	$n_article=str_replace("<br>","<br/>",$n_article);
	$art_title=str_replace("&","&amp;",$art_title);

	$content .= "
	<item>
		<title><![CDATA[$art_title]]></title>
		<link>http://".$_SERVER['HTTP_HOST']."/a_$news_num</link>
		<description><![CDATA[$n_article]]></description>
		<pubDate>$news_time</pubDate>
		<guid isPermaLink='true'>http://".$_SERVER['HTTP_HOST']."/a/$news_num</guid>
	</item>
	";
}
$content.="</channel>
</rss>";
?>