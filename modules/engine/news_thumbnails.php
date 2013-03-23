<?
$title="Новости";
$wmsg='';
if ((isset($_REQUEST['cat'])) or (isset($_REQUEST['tag'])) or (isset($_REQUEST['user'])) or (isset($_REQUEST['s']))){
	if (isset($_REQUEST['tag'])){
		$tag=htmlspecialchars($_REQUEST['tag']);
		#$slist="<div class='alert alert-info'>Выводятся новости с тегом \"$tag\"</div>";
		$sql_count="SELECT COUNT(*) from `News` WHERE `Tags` LIKE '%$tag%';";
		$title="Новости с тегом \"$tag\"";
	} else if (isset($_REQUEST['cat'])){
		$cat=htmlspecialchars($_REQUEST['cat']);
		if ($cat!="*"){
			$sel_cat=" WHERE `Category`='$cat'";
			$sel2_cat=" AND `News`.`Category`='$cat'";
		} else {
			$sel_cat='';
			$sel2_cat='';
		}
		#$slist="<div class='alert alert-info'>Выводятся новости из категории \"$cats[$cat]\"</div>";
		$sql_count="SELECT COUNT(*) from `News`$sel_cat";
		$title='Категория: '.$cats[$cat];
	} else if ((isset($_REQUEST['user'])) and ($admin || $moder || $user)){
		if (($admin || $moder) and ($_REQUEST['user']!="")){
			$usr=htmlspecialchars($_REQUEST['user']);
		} else {
			$usr=$u_id;
		}			
		#$slist="<div class='alert alert-info'>Выводятся новости от пользователя \"$usr\"</div>";
		$sql_count="SELECT COUNT(*) from `News` WHERE `Author`='$usr';";
		$title="Новости от пользователя $usr";
	} else {
		$search=htmlspecialchars($_REQUEST['s']);
		$slist="<div class='alert alert-info'>Выводятся новости, содержащие слова: \"$search\"</div>";
		$sql_count="SELECT COUNT(*) FROM `News` WHERE MATCH (`Title`,`Text`,`Tags`) AGAINST ('$search');";
		$title="Поиск новостей";
	}
} else {
	$cat=0;
	$sql_count="SELECT COUNT(*) FROM `News` WHERE `Category`<>'1' AND `Category`<>'6';";
	if ($welcome_msg!='' && substr($welcome_msg,0,1)!="$"){
		$wmsg="<div class='well welcome'>$welcome_msg</div>";
		$slist='';
	} else {
		$slist='';
	}
}
# SITE-SPECIFIC CSS
if ($cat=='10'){$slist.="<script>var new_style='cs.css';</script>";}

# если хочешь сделать специальный стиль для определенной категории, дублируешь строку 46 и меняешь 10 на номер нужной категории и cs.css на название файла стилей в /static/css
#
$content.=$slist;

if (isset($_REQUEST['part'])){ $part=htmlspecialchars($_REQUEST['part']); } else { $part=1; }

$sql=mysql_query($sql_count);
$row = mysql_fetch_assoc($sql);
$total=$row['COUNT(*)'];

if ($total==0){
	$content=(isset($_REQUEST['s'])) ? $slist. '<div class="alert alert-error"><strong>К сожалению, новостей не найдено!</strong><p>Возможно, поисковый запрос слишком короткий. Минимальная длина слова для поиска - 4 символа.</p></div>' : $slist. '<div class="alert alert-error">К сожалению, новостей не найдено!</div>';
} else {
	$pages=floor($total/$news_at_home);
	$ost=$total % $news_at_home;
	if ($ost!=0){ $pages+=1; }
	if (($part<1) or ($part>$pages)){ $part=1; }
	$get_news=($part-1)*$news_at_home;

	if ((isset($_REQUEST['cat'])) or (isset($_REQUEST['tag'])) or (isset($_REQUEST['user'])) or (isset($_REQUEST['s']))){
		if (isset($_REQUEST['tag'])){
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `News`.`Tags` LIKE '%$tag%' $_news_sorting LIMIT $get_news,$news_at_home";
		} else if (isset($_REQUEST['cat'])){
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`)$sel2_cat $_news_sorting LIMIT $get_news,$news_at_home";
		} else if (isset($_REQUEST['user'])){
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `News`.`Author`='$usr' $_news_sorting LIMIT $get_news,$news_at_home";
		} else {
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND MATCH (`News`.`Title`,`News`.`Text`,`News`.`Tags`) AGAINST ('$search') $_news_sorting LIMIT $get_news,$news_at_home";
		}
	} else {
		$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `Category`<>'1' AND `Category`<>'6' $_news_sorting LIMIT $get_news,$news_at_home";
	}
	if ((isset($_REQUEST['cat'])) or (isset($_REQUEST['tag'])) or (isset($_REQUEST['user'])) or (isset($_REQUEST['s']))){
		if (isset($_REQUEST['cat'])){
			$addit="cat=$cat";
		} else if (isset($_REQUEST['tag'])){
			$addit="tag=$tag";
		} else if (isset($_REQUEST['user'])){
			$addit="user=$usr";
		} else {
			$addit="s=$search";
		}
	}
	$_addit=str_replace("=","*",$addit);

	$sql=mysql_query($sql_getcontents);
	$content.="<div class='row-fluid'><ul class='thumbnails'>";
	$ccnt=0;
	while ($row = mysql_fetch_assoc($sql)) {
		$dp=date_parse($row['Time']);
		$news_t=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
		$news_date=date('d.m.Y',$news_t+$offset_t);
		$news_time=date('H:i',$news_t+$offset_t);
		$news_preview=(trim($row['Preview'])=='')? '' : '<img src="'.$row['Preview'].'" class="news-image"/>';
		$news_raw_text=$row['Text'];
		$news_raw_text=str_replace("<a","<a class='news_link'",$news_raw_text);
		$news_raw_text=str_replace("class='rd_ajax'",'class="rd_ajax"',$news_raw_text);
		$news_raw_text=str_replace('class="rd_ajax"','',$news_raw_text);
		$news_raw_text=str_replace("&#39;","'",$news_raw_text);
		$news_views=$row['Views'];
		
		$news_title=$row['Title'];
		$news_title=str_replace('"','&quot;',$news_title);
		$news_title=str_replace("'",'&#39;',$news_title);
		$news_cat=$row['Category'];
		$news_fixed=$row['Fixed']==1 ? " news-fixed":"";
		
		$news_num=$row['Num'];
		
		if ($enable_ufu=="1"){
			$cat_cut=explode(" - ",$cats[$news_cat]);
			$news_translit=prepare_ufu($news_title);
			$news_razd_t=prepare_ufu($cat_cut[0]);
			$news_cat_t=prepare_ufu($cat_cut[1]);
			$news_ufu="data-url='act=fulltext&id=$news_num' href='/$news_razd_t/$news_cat_t/$news_num-$news_translit' ";
		} else {
			$news_ufu="href='?act=fulltext&id=$news_num' ";
		}
		
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
				$news_text.="<br><a class='rdajax btn btn-mini' $news_ufu title='Читать далее'><i class='icon-arrow-right'></i> Читать далее</a>";
			} else {
				$news_text=$news_raw_text;
			}
		} else {
			$news_text=str_replace("<hr>","",$news_raw_text);
			$news_text=substr($news_text,0,$n_hr_pos);
			$news_text.="<br><a class='rdajax btn btn-mini' $news_ufu title='Читать далее'><i class='icon-arrow-right'></i> Читать далее</a>";
		}
		
		$news_coms=$row['Coms'];
		if ($news_coms==0){
			$coms_show="Нет комментариев";
		} else {
			$coms_show="Комментарии: <b>$news_coms</b>";
		}
		
		if (trim($row['Preview'])==''){
			$n_article=$news_text;
		} else {
			//$n_article="<table><tr><td class='news_image'>$news_preview</td><td style='vertical-align:top' class='news_text'>$news_text</td></tr></table>";
			$n_article=$news_preview.$news_text;
		}
		
		if ($admin || $moder){ 
			$mod_panel="<div class='btn-group'><a class='btn btn-mini dropdown-toggle' data-toggle='dropdown' href='#'>Действия<span class='caret'></span> </a>
				<ul class='dropdown-menu'>
					<li><a class='rdajax' title='Удалить' data-cont='ajax_temp' href='?act=del_news&id=$news_num&part=$part&return=$_addit'><i class='icon-remove'></i> Удалить</a></li>
					<li><a class='rdajax' title='Редактировать' href='?act=add&id=$news_num'><i class='icon-pencil'></i> Редактировать</a></li>
				</ul></div>"; 
		}
		
		if (trim($news_title)==''){
			$news_title='&lt;нет заголовка&gt;';
		}
		$art_title="<h4 class='heading'><a class='rdajax' $news_ufu data-title=\"$news_title\">$news_title</a></h4>
		<div class='btn-toolbar'>
			<span class='btn btn-mini' data-title='Новость опубликована $news_date в $news_time'><i class='icon-calendar'></i> $news_date</span>
			<span class='btn btn-mini' data-title='Просмотров: $news_views'><i class='icon-eye-open'></i> $news_views</span>
			<a class='rdajax btn btn-mini' href='?act=users&profile=$n_author_id' data-title='Автор: $n_author_name'><i class='icon-user'></i> $n_author_name</a>
			<a class='rdajax btn btn-mini' href='?act=news&cat=$news_cat' data-title='Каегория: $cats[$news_cat]'><i class='icon-th-list'></i> $cats[$news_cat]</a>
			<a class='rdajax btn btn-mini' $news_ufu data-title='$coms_show'><i class='icon-comment'></i> $news_coms</a>
			$mod_panel
		</div>";// $news_coms = 0,1,2...  // $coms_show = Нет комментариев, Комментарии: 1, ...

		$content .= "<li class='span4$news_fixed'><div class='thumbnail'>$news_preview<p><a class='rdajax' $news_ufu data-title=\"$news_title\">$news_title</a></p></div></li>";
		if ($ccnt % 3 == 2){ $content.="</ul><ul class='thumbnails'>"; }
		$ccnt++;
	}

	$prev=$part-1;
	$next=$part+1;

	if ($addit){
		$addit="&".$addit;
	}

	$switch_p=($prev>=1)?"<a class='rdajax' href='index.php?part=$prev$addit'><<</a>":"<span><<</span>";
	$switch_n=($next<=$pages)?"<a class='rdajax' href='index.php?part=$next$addit'>>></a>":"<span>>></span>";

	$content.="</ul></div>$wmsg<form id='pager'><input type='hidden' name='act' value='news'><input type='hidden' name='addit' value='$addit' id='addit'><div class='pager'>
		<div class='pager-input' data-title='Введите номер страницы и нажмите \"Enter\" для перехода'>
			Страница <input type='text' name='part' value='$part' id='pager-part'> из $pages
		</div>
		<div class='pn-switch'>
			$switch_p
			$switch_n
		</div>
	</div>";
}
?>
