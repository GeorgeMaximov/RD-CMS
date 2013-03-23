<?
$title="Новости";
if ((isset($_REQUEST['cat'])) or (isset($_REQUEST['tag'])) or (isset($_REQUEST['user'])) or (isset($_REQUEST['s']))){
	if (isset($_REQUEST['tag'])){
		$tag=htmlspecialchars($_REQUEST['tag']);
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
		$sql_count="SELECT COUNT(*) from `News`$sel_cat";
		$title='Категория: '.$cats[$cat]['title'];
	} else if ((isset($_REQUEST['user'])) and ($admin || $moder || $user)){
		if (($admin || $moder) and ($_REQUEST['user']!="")){
			$usr=htmlspecialchars($_REQUEST['user']);
		} else {
			$usr=$u_id;
		}			
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
	$sql_count="SELECT COUNT(*) FROM `News` WHERE `Category` NOT IN (SELECT `ID` FROM `Categories` WHERE `HideMain`=1);";
	if ($welcome_msg!='' && substr($welcome_msg,0,1)!="$"){
		$slist="<div class='well welcome'>$welcome_msg</div>";
	} else {
		$slist='';
	}
}

$content.=(trim($cats[$cat]['style'])!='') ? '<style>'. str_replace('[static]',$static_path,$cats[$cat]['style']) .'</style>' : '';
$content.=$slist;

if (isset($_REQUEST['part'])){ $part=htmlspecialchars($_REQUEST['part']); } else { $part=1; }

$sql=mysql_query($sql_count);
if ($sql){
	$row = mysql_fetch_assoc($sql);
		if ($row){ $total=$row['COUNT(*)']; } else { $total=0; }
} else { $total=0; }

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
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`,`News`.`NoComments`,`News`.`OnlyPage`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `News`.`Tags` LIKE '%$tag%' $_news_sorting LIMIT $get_news,$news_at_home";
		} else if (isset($_REQUEST['cat'])){
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`,`News`.`NoComments`,`News`.`OnlyPage`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`)$sel2_cat $_news_sorting LIMIT $get_news,$news_at_home";
		} else if (isset($_REQUEST['user'])){
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`,`News`.`NoComments`,`News`.`OnlyPage`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `News`.`Author`='$usr' $_news_sorting LIMIT $get_news,$news_at_home";
		} else {
			$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`,`News`.`NoComments`,`News`.`OnlyPage`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND MATCH (`News`.`Title`,`News`.`Text`,`News`.`Tags`) AGAINST ('$search') $_news_sorting LIMIT $get_news,$news_at_home";
		}
	} else {
		$sql_getcontents="SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`,`News`.`NoComments`,`News`.`OnlyPage`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `Category` NOT IN (SELECT `ID` FROM `Categories` WHERE `HideMain`=1) $_news_sorting LIMIT $get_news,$news_at_home";
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
	while ($row = mysql_fetch_assoc($sql)) {
		$dp=date_parse($row['Time']);
		$news_t=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
		$news_date=date('d.m.Y',$news_t+$offset_t);
		$news_time=date('H:i',$news_t+$offset_t);
		$n_show_date=($news_date==date('d.m.Y')) ? $news_time : $news_date;
		$news_preview=(trim($row['Preview'])=='') ? '' : (((substr($row['Preview'],0,8))=='[attach]') ? '<img src="'. str_replace('[attach]',$_uploader_path,$row['Preview']) .'" class="news-image img-polaroid" alt="Изображение"/>' : '<img src="'.$row['Preview'].'" class="news-image img-polaroid" alt="Изображение"/>');
		$news_raw_text=$row['Text'];
		$news_raw_text=str_replace("<spoiler>","<div class='spoiler nav nav-tabs nav-stacked rd-nav-tabs'><div class='spoiler-title nav-header' data-title='Нажмите для отображения или скрытия содержимого'>Спойлер</div><div class='spoiler-inner'>",$news_raw_text);
		$news_text=preg_replace("#<spoiler=([^>]*)>#","<div class='spoiler nav nav-tabs nav-stacked rd-nav-tabs'><div class='spoiler-title nav-header' data-title='Нажмите для отображения или скрытия содержимого'>$1</div><div class='spoiler-inner'>",$news_text);
		$news_raw_text=str_replace("</spoiler>","</div></div>",$news_raw_text);
		$news_raw_text=str_replace("<a","<a class='news_link'",$news_raw_text);
		$news_raw_text=str_replace("class='rd_ajax'",'class="rd_ajax"',$news_raw_text);
		$news_raw_text=str_replace('class="rd_ajax"','',$news_raw_text);
		$news_raw_text=str_replace("&#39;","'",$news_raw_text);
		$news_raw_text=str_replace('[attach]',$_uploader_path,$news_raw_text);
		$news_views=$row['Views'];
		
		$news_title=$row['Title'];
		$news_title=str_replace('"','&quot;',$news_title);
		$news_title=str_replace("'",'&#39;',$news_title);
		$news_cat=$row['Category'];
		$news_fixed=$row['Fixed']==1 ? " news-fixed":"";
		
		$news_num=$row['Num'];
		$news_nocom=$row['NoComments'];
		$d_page=$row['OnlyPage'];
		
		if ($enable_ufu=="1"){
			$cat_cut=explode(" - ",$cats[$news_cat]['title']);
			$news_translit=prepare_ufu($news_title);
			$news_razd_t=prepare_ufu($cat_cut[0]);
			$news_cat_t=prepare_ufu($cat_cut[1]);
			$news_ufu="data-url='act=fulltext&amp;id=$news_num' href='/$news_razd_t/$news_cat_t/$news_num-$news_translit' ";
		} else if ($enable_ufu=="2"){
			$news_ufu="data-url='act=fulltext&amp;id=$news_num' href='/a/$news_num' ";
		} else {
			$news_ufu="href='?act=fulltext&amp;id=$news_num' ";
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
				$news_text.="<br><a class='rdajax btn btn-mini' $news_ufu data-title='Полный текст новости и комментарии'><i class='icon-arrow-right'></i> Читать далее</a>";
			} else {
				$news_text=$news_raw_text;
			}
		} else {
			$news_text=str_replace("<hr>","",$news_raw_text);
			$news_text=substr($news_text,0,$n_hr_pos);
			$news_text.="<br><a class='rdajax btn btn-mini' $news_ufu data-title='Полный текст новости и комментарии'><i class='icon-arrow-right'></i> Читать далее</a>";
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
			$n_article=$news_preview.$news_text;
		}
		
		if ($admin || $moder){ 
			$mod_panel="<div class='btn-group'><a class='btn btn-mini dropdown-toggle' data-toggle='dropdown' href='#' data-title='Действия'><i class='icon-cog'></i><span class='caret'></span> </a>
				<ul class='dropdown-menu'>
					<li><a class='rdajax' href='?act=add&amp;id=$news_num'><i class='icon-pencil'></i> Редактировать</a></li>
					<li><a class='rdajax' data-cont='ajax_temp' href='?act=del_news&amp;id=$news_num&amp;part=$part&amp;return=$_addit'><i class='icon-remove'></i> Удалить</a></li>
				</ul></div>"; 
		}
		
		if (trim($news_title)==''){
			$news_title='&lt;нет заголовка&gt;';
		}
		
		if ($d_page==1){
			$art_title='<h4 class=\'heading\'><a class="rdajax" '.$news_ufu.' data-title="'.$news_title.'">'.$news_title.'</a><div class=\'pull-right\'>'.$mod_panel.'</div></h4>';
		} else {
			$art_title="<h4 class='heading'><a class='rdajax' $news_ufu data-title=\"$news_title\">$news_title</a></h4>
			<div class='btn-toolbar'>
				<span class='btn btn-mini' data-title='Новость опубликована $news_date в $news_time'><i class='icon-calendar'></i> $n_show_date</span>
				<span class='btn btn-mini' data-title='Просмотров: $news_views'><i class='icon-eye-open'></i> $news_views</span>
				<a class='rdajax btn btn-mini' href='?act=users&amp;profile=$n_author_id' data-title='Автор: $n_author_name'><i class='icon-user'></i> $n_author_name</a>
				<a class='rdajax btn btn-mini' href='?act=news&amp;cat=$news_cat' data-title='Категория: ". $cats[$news_cat]['title'] ."'><i class='icon-th-list'></i> ". $cats[$news_cat]['title'] ."</a>";
			if ($news_nocom!=1){
				$art_title.="<a class='rdajax btn btn-mini' $news_ufu data-title='$coms_show'><i class='icon-comment'></i> $news_coms</a>";
				// $news_coms = 0,1,2...  // $coms_show = Нет комментариев, Комментарии: 1, ...
			}
				$art_title.="$mod_panel</div>";
		}
		$content .= "<article class='well$news_fixed'>$art_title<div class='news_text text'>$n_article</div></article>";
	}

	$prev=$part-1;
	$next=$part+1;

	if ($addit){
		$addit="&amp;".$addit;
	}

	$switch_p=($prev>=1)?"<a class='rdajax' href='index.php?part=$prev$addit'>&lt;&lt;</a>":"<span>&lt;&lt;</span>";
	$switch_n=($next<=$pages)?"<a class='rdajax' href='index.php?part=$next$addit'>&gt;&gt;</a>":"<span>&gt;&gt;</span>";

	$content.="<form id='pager'><input type='hidden' name='act' value='news'><input type='hidden' name='addit' value='$addit' id='addit'><div class='pager well well-small'>
		<div class='pn-switch'>
			$switch_p
		</div>
		<div class='pager-input' data-title='Введите номер страницы и нажмите \"Enter\" для перехода'>
			Страница <input type='text' name='part' value='$part' id='pager-part'> из $pages
		</div>
		<div class='pn-switch'>
			$switch_n
		</div>
	</div>
	</form>";
}
?>
