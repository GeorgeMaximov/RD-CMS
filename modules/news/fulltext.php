<?
if (isset($_REQUEST['id'])){
	$id=htmlspecialchars($_REQUEST['id']);
} else if (isset($_REQUEST['amp;id'])){
	$id=htmlspecialchars($_REQUEST['amp;id']);
} else {
	$id=-1;
}

$sql=mysql_query("SELECT `News`.`Num`,`News`.`Time`,`News`.`Title`,`News`.`Text`,`News`.`Category`,`News`.`Tags`,`News`.`Author`,`News`.`Preview`,`News`.`Fixed`,`News`.`Views`,`News`.`NoComments`, `News`.`OnlyPage`, (SELECT COUNT(*) FROM `Comments` WHERE `Comments`.`News_ID`=`News`.`Num`) AS 'Coms', `Name` AS  'A_Name', `Nick` AS 'A_Nick', `Photo` AS 'A_Photo' FROM `News`,`Users` WHERE (`Users`.`Auth_ID`=`News`.`Author`) AND `Num`='$id'");
$row = ($sql) ? mysql_fetch_assoc($sql) : FALSE;
if ($row!==FALSE){
	$n_title=$row['Title'];
	$dp=date_parse($row['Time']);
	$n_time=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
	$n_date=date('d.m.Y',$n_time+$offset_t);
	$n_time=date('H:i',$n_time+$offset_t);
	$n_show_date=($n_date==date('d.m.Y')) ? $n_time : $n_date;
	$n_text=$row['Text'];
	$n_text=str_replace("<hr>","",$n_text);
	$n_text=str_replace("<spoiler>","<div class='spoiler nav nav-tabs nav-stacked rd-nav-tabs'><div class='spoiler-title nav-header' data-title='Нажмите для отображения или скрытия содержимого'>Спойлер</div><div class='spoiler-inner'>",$n_text);
	$n_text=preg_replace("#<spoiler=([^>]*)>#","<div class='spoiler nav nav-tabs nav-stacked rd-nav-tabs'><div class='spoiler-title nav-header' data-title='Нажмите для отображения или скрытия содержимого'>$1</div><div class='spoiler-inner'>",$n_text);
	$n_text=str_replace("</spoiler>","</div></div>",$n_text);
	$n_text=str_replace("<a","<a class='news_link'",$n_text);
	$n_text=str_replace("class='rd_ajax'",'class="rd_ajax"',$n_text);
	$n_text=str_replace('class="rd_ajax"','',$n_text);
	$n_text=str_replace("&#39;","'",$n_text);
	$n_text=str_replace('[attach]',$_uploader_path,$n_text);
	$n_coms=$row['Coms'];
	$n_views=$row['Views'];
	$n_cat=$row['Category'];
	$n_tags=$row["Tags"];
	$n_img=(trim($row['Preview'])=='') ? '' : (((substr($row['Preview'],0,8))=='[attach]') ? '<img src="'. str_replace('[attach]',$_uploader_path,$row['Preview']) .'" class="news-image img-polaroid"/>' : '<img src="'.$row['Preview'].'" class="news-image img-polaroid"/>');
	$timg=(trim($row['Preview'])=='')? '' : $row['Preview'];
	$n_views1=$n_views+1;
	$n_fixed=$row['Fixed']==1 ? " news-fixed":"";
	$news_nocom=$row['NoComments'];
	$d_page=$row['OnlyPage'];
	
	$sql=mysql_query("UPDATE `News` SET `Views` = '$n_views1' WHERE `Num`='$id' LIMIT 1 ;");
	
	if (trim($row['A_Nick'])==''){
		$n_author_name=$row['A_Name'];
	} else {
		$n_author_name=$row['A_Nick'];
	}
	$n_author_img=$row['A_Photo'];
	$n_author_id=$row['Author'];
	
	if ($admin || $moder){
		$vk_b_share=(trim($vk_share_wall)=='') ? '' : "<li><a href='javascript:' id='to_vk' data-title='Разместить ссылку на странице В Контакте'><i class='icon-share'></i> Отправить в VK</a></li>";
		$mod_panel_news="<div class='btn-group'><a class='btn btn-mini dropdown-toggle' data-toggle='dropdown' href='#' data-title='Действия'><i class='icon-cog'></i><span class='caret'></span> </a>
		<ul class='dropdown-menu'>
			<li><a class='rdajax' href='?act=add&amp;id=$id'><i class='icon-pencil'></i> Редактировать</a></li>
			<li><a class='rdajax' data-cont='ajax_temp' href='?act=del_news&amp;id=$id&amp;part=0'><i class='icon-remove'></i> Удалить</a></li>
			$vk_b_share
		</ul></div>
		<div id='cap_frm' style='display:none'><img src='' id='cap_img'><input type='text' id='cap_key'><input type='button' value='>' id='cap_sbm'></div>
		"; 
	}

	$coms_show=($n_coms==0) ? 'Нет комментариев':'Комментарии: <b>'.$n_coms.'</b>';
	if (trim($n_title)==''){
		$n_title='&lt;нет заголовка&gt;';
	}
	if ($d_page==1){
		$art_title='<h4 class=\'heading\'>'.$n_title.'<div class=\'pull-right\'>'.$mod_panel_news.'</div></h4>';
	} else {
		$art_title="<h4 class='heading'>$n_title</h4>
		<div class='btn-toolbar'>
			<span class='btn btn-mini' data-title='Новость опубликована $n_date в $n_time'><i class='icon-calendar'></i> $n_show_date</span>
			<span class='btn btn-mini' data-title='Просмотров: $n_views1'><i class='icon-eye-open'></i> $n_views1</span>
			<a class='rdajax btn btn-mini' href='?act=users&amp;profile=$n_author_id' data-title='Автор: $n_author_name'><i class='icon-user'></i> $n_author_name</a>
			<a class='rdajax btn btn-mini' href='?act=news&amp;cat=$n_cat' data-title='Категория: ". $cats[$n_cat]['title'] ."'><i class='icon-th-list'></i> ". $cats[$n_cat]['title'] ."</a>";
		if ($news_nocom!=1){
			$art_title.="<span class='btn btn-mini' data-title='$coms_show'><i class='icon-comment'></i> $n_coms</span>";
		}
		$art_title.=$mod_panel_news.'</div>';
	}
	
	if ($news_similar>0 && $d_page==0){
		$sql=mysql_query("SELECT COUNT(*) FROM `News` WHERE `Category`='$n_cat' AND `Num`<>'$id'");
		$row = mysql_fetch_assoc($sql);
		$total=$row['COUNT(*)'];
		if ($total>=$news_similar){
			$sim_width=floor(100/$news_similar);
			$similar="<div class='well'><h4>Вам будет интересно</h4>";
			$similar.="<style>.similar-link{width:$sim_width%;}</style>";
			$sql=mysql_query("SELECT * FROM `News` WHERE `Category`='$n_cat' AND `Num`<>'$id' ORDER BY RAND() LIMIT 0,$news_similar");
			while ($row = mysql_fetch_assoc($sql)) {
				$sim_id=$row['Num'];
				$sim_t=$row['Title'];
				
				$sim_p=(trim($row['Preview'])=='') ? '' : (((substr($row['Preview'],0,8))=='[attach]') ? '<img src="'. str_replace('[attach]',$_uploader_path,$row['Preview']) .'" class="img-polaroid" alt="Изображение"/>' : '<img src="'.$row['Preview'].'" class="img-polaroid" alt="Изображение"/>');
				$similar.="<a class='rdajax similar-link' href='?act=fulltext&amp;id=$sim_id' title='$sim_t'><div class='similar-block'><div class='similar-title'>$sim_t</div><div class='similar_preview'>$sim_p</div></div></a>";
			}
			$similar.="</div>";
		} else {
			$similar="";
		}
	}
	if ($news_nocom!=1){
		if ($coms_sort=="1"){ $_com_sort="ORDER BY `ID` DESC"; } else { $_com_sort="ORDER BY `ID` ASC"; }
		$sql=mysql_query("SELECT * FROM `Comments` WHERE `News_ID`=$id $_com_sort");
		$com_shown=0;
		while ($row = mysql_fetch_assoc($sql)) {
			$com_shown+=1;
			$c_id=$row['ID'];
			$c_text=$row['Text'];
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
			
			if ($admin || $moder){
				$mod_panel="<a class='rdajax btn btn-mini btn-warning pull-right' data-cont='ajax_temp' href='index.php?act=comment&amp;del_id=$c_id&amp;$id&amp;ret=comments&amp;retpart=$retprt' data-title='Удалить комментарий'><i class='icon-white icon-remove'></i></a>";
			}
			
			if ($com_shown==1){
				$comments_head="<ul class='breadcrumb'><li class='active'>Комментарии</li></ul>";
			} else {
				$comments_head='';
			}
		
			$comments.="$comments_head
						<div class='media well well-small'>
							<a class='pull-left' href='?act=users&amp;profile=$n_id' class='rdajax'>
								<img class='media-object comment-icon' src='$n_photo'>
							</a>
							<div class='media-body'>
							<h4 class='media-heading'>$c_author</h4>
								$c_text
								<div class='btn-toolbar'>
									<a href='javascript:insert_reply(\"$c_author\");' class='btn btn-mini' data-title='Ответить пользователю $c_author'><i class='icon-share-alt'></i> Ответ</a>
									<div class='btn btn-mini' data-title='Комментарий отправлен $c_date в $c_time'><i class='icon-calendar'></i> $c_date</div>
									$mod_panel
								</div>
							</div>
						</div>";
		}
		if ($user || $admin || $moder){
			$show_as=(trim($u_nick)=='')?$u_name:$u_nick;
			$addform="<div class='well well-small'><ul class='breadcrumb'><li class='active'>Добавить комментарий</li></ul><form method='post' class='row-fluid'>
						<textarea id='coms_editor' class='span12' name='text' rows='2' cols='50'></textarea>
						<input type='hidden' name='id' value='$id'/>
						<input type='hidden' value='comment' name='act'>
				<div class='btn-toolbar'>
					<div class='btn-group'>
						<div class='rdajax btn' data-title='Комментарий от $show_as'><img class='list-icon' src='$u_photo' align='top'> $show_as</div>
						<input type='submit' class='btn btn-primary' data-title='Отправить комментарий' value='Отправить'/>
					</div>
				</div></form>
			</div>";
		} else {
			$addform="<div class='well' onClick='login_form(1)'><h4 class='heading'>Добавить комментарий</h4>
						<div class='row-fluid'>
						<textarea id='coms_editor' class='span12' rows='6' cols='50'></textarea>
						</div></div>";
		}
	} else {
		$comments='';
		$addform='';
	}
	$title=$n_title;
	
	$vk_title="$n_title";
	$vk_title=str_replace('"',"'",$vk_title);
	$vk_title_enc=urlencode($vk_title);
	$vk_desc=substr(strip_tags($n_text),0,200);
	$vk_link="http://".$_SERVER['SERVER_NAME']."/?act=fulltext&amp;id=$id";
	$vk_link_enc=urlencode($vk_link);
	$vk_short="http://".$_SERVER['SERVER_NAME']."/a/$id";
	$vk_link_md5=md5($vk_link);
	

	if (strlen($n_tags)>0 && $d_page==0){
		$s_n_tags="<div class='tags'>Теги: ".parse_tags($n_tags)."</div>";
	} else {
		$s_n_tags='';
	}
	
	$likebox=($d_page==0) ? "<div class='hide'><div class='like_box lb1' id='like-box'>
			<div class='like'><div id='vk_like'></div></div>
			<div class='like'><iframe src='//www.facebook.com/plugins/like.php?href=$vk_link_enc&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=true&amp;action=like&amp;colorscheme=dark&amp;font=arial&amp;height=21' scrolling='no' frameborder='0' style='border:none; overflow:hidden; height:21px; width:150px; ' allowTransparency='true'></iframe></div>
			<div class='like'><iframe allowtransparency='true' frameborder='0' scrolling='no' src='//platform.twitter.com/widgets/tweet_button.html?url=$vk_short&amp;text=$vk_title&amp;lang=ru' style='width:130px; height:20px;'></iframe></div>
			<div class='like'><a target='_blank' class='mrc__plugin_uber_like_button' href='http://connect.mail.ru/share' data-mrc-config='{\'cm\' : \'1\', \'ck\' : \'1\', \'sz\' : \'20\', \'st\' : \'2\', \'tp\' : \'mm\'}'>Нравится</a></div>
			<script src='http://cdn.connect.mail.ru/js/loader.js' type='text/javascript' charset='UTF-8'></script>
		</div></div>
		<button class='btn' id='like-box-button'><i class='icon-share'></i> Оценить</button>
		" : '';
	
	$content.=(trim($cats[$n_cat]['style'])!='') ? '<style>'. str_replace('[static]',$static_path,$cats[$n_cat]['style']) .'</style>' : '';
	
	$content.="
	<meta property='vk:app_id' content='$api_id' />
	<meta property='fb:admins' content='$fb_admins' />
	<meta property='og:title' content='$vk_share_title' />
	<meta property='og:description' content='$vk_title' />
	<meta property='og:url' content='$vk_short' />
	<meta property='og:image' content='$timg' />
	<meta name='keywords' content='$n_tags' />
	<meta name='description' content='$vk_title' />
	
	$slist
	
	<article class='well news_text text$n_fixed'>
		$art_title
		$n_img$n_text$s_n_tags$likebox
	</article>
	$similar
	<a name='coms'></a>
	$comments
	$addform
	<script>
		function vk_post(){
			VK.api('wall.post',{owner_id: $vk_share_wall, message:\"$vk_title\\n$vk_short\", attachments:'$vk_share_attach'},function(data) {
				if (data.response) {
					alert('Сообщение отправлено!');
				} else {
					alert('Ошибка! ' + data.error.error_code + ' ' + data.error.error_msg);
					if (data.error.error_code==14){
						cap_key=$('#cap_key').val();
						cap_sid=data.error.captcha_sid;
						$('#cap_img').attr('src',data.error.captcha_img);
						$('#cap_frm').show('fast');
						$('#cap_sbm').click(function(){
							$('#cap_frm').hide();
							VK.api('wall.post',{owner_id: $vk_share_wall, message:\"$vk_title\\n$vk_short\", attachments:'$vk_share_attach', captcha_sid: cap_sid, captcha_key: cap_key},function(data) {
							if (data.response) {
								alert('Сообщение отправлено!');
							} else {
								alert('Ошибка! ' + data.error.error_code + ' ' + data.error.error_msg);
							}
							});
						});
					}
				}
			});
		};
		$('#to_vk').click(function(){vk_post();});
		if (news_posted!=0){
			news_posted=0;
			vk_post();
		}
		//$('#like-box').hide();
		$('#like-box-button').popover({title:'Оценка новости',content:$('#like-box')});
		//$('#like-box').remove();
		VK.Widgets.Like('vk_like', {type: 'button', pageUrl: '$vk_short', text:\"$vk_title\", pageTitle: '$vk_share_title'});
	</script>";
} else {
	$content="<script>$(document).ready(function () { alert('Новость с указанным ID не найдена или удалена!','error'); rd_ajax('act=home');});</script>"; 
}
?>
