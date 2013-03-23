<?
if ($admin){
	$title="Панель управления сайтом";
	$__cms_ver=$__cms['version'];
	$__cms_update_info=$__cms['current'];
	$__cms_update_link=$__cms['info'];
	if (isset($_POST['do'])){
			$p_siteactive=htmlspecialchars($_POST['Site_Active']);
			$p_newshome=htmlspecialchars($_POST['News_at_home']);
			$p_loglines=htmlspecialchars($_POST['Lines_at_log']);
			$p_vkid=htmlspecialchars($_POST['VK_API_ID']);
			$p_vksecret=htmlspecialchars($_POST['VK_API_SECRET']);
			$p_toffset=htmlspecialchars($_POST['Time_Offset']);
			$p_shortsym=htmlspecialchars($_POST['Short_Sym']);
			$p_tagscount=htmlspecialchars($_POST['Tag_Cloud_Count']);
			$p_tagparams=htmlspecialchars($_POST['Tag_Cloud_Params']);
			$p_fbadm=htmlspecialchars($_POST['FB_Admins']);
			$p_nsort=htmlspecialchars($_POST['News_Sort']);
			$p_vk_share_title=htmlspecialchars($_POST['VK_Share_Title']);
			$p_vk_share_wall=htmlspecialchars($_POST['VK_Share_Wall']);
			$p_vk_share_attach=htmlspecialchars($_POST['VK_Share_Attach']);
			$p_news_similar=htmlspecialchars($_POST['News_Similar']);
			$p_rss_link=htmlspecialchars($_POST['RSS_Link']);
			$p_google_code=htmlspecialchars($_POST['Google_Code']);
			$p_ya_code=htmlspecialchars($_POST['Ya_Code']);
			$p_site_name=htmlspecialchars($_POST['Site_Name']);
			$p_site_copy=htmlspecialchars($_POST['Site_Copy']);
			$p_goog_an_id=htmlspecialchars($_POST['Google_Analytics']);
			$p_coms_sort=htmlspecialchars($_POST['Coms_Sort']);
			$p_art_sort=htmlspecialchars($_POST['Articles_Sort']);
			$p_art_title=htmlspecialchars($_POST['Articles_Title']);
			$p_art_count=htmlspecialchars($_POST['Articles_Count']);
			$p_enable_ufu=htmlspecialchars($_POST['News_UFU']);
			$p_enable_ajax=htmlspecialchars($_POST['Site_AJAX']);
			$p_com_show_cnt=htmlspecialchars($_POST['ComsList_Count']);
			$p_logger_enabled=htmlspecialchars($_POST['Site_Logger']);
			$p_font_family=htmlspecialchars($_POST['Font_Family']);
			$p_font_size=htmlspecialchars($_POST['Font_Size']);
			$p_site_swatch=htmlspecialchars($_POST['Site_Swatch']);
			$p_welcomemsg=$_POST['Site_Welcome'];
			$p_banned=$_POST['Site_Banned'];
			$p_adsmain=$_POST['Advert_On_Main'];
			$p_statsmain=$_POST['Stats_On_Main'];
			$p_disable_reason=$_POST['Site_Active_Reason'];
			
			$p_vk_share_title=str_replace("'","&#39;",$p_vk_share_title);
			$p_site_name=str_replace("'","&#39;",$p_site_name);
			
			$cnt=0;
			
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_siteactive' WHERE `Setting` = 'Site_Active' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_newshome' WHERE `Setting` = 'News_at_home' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_loglines' WHERE `Setting` = 'Lines_at_log' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_vkid' WHERE `Setting` = 'VK_API_ID' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_vksecret' WHERE `Setting` = 'VK_API_SECRET' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_adsmain' WHERE `Setting` = 'Advert_On_Main' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_statsmain' WHERE `Setting` = 'Stats_On_Main' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_toffset' WHERE `Setting` = 'Time_Offset' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_shortsym' WHERE `Setting` = 'News_Short_Symbols' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_tagscount' WHERE `Setting` = 'Tag_Cloud_Count' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_tagparams' WHERE `Setting` = 'Tag_Cloud_Params' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_welcomemsg' WHERE `Setting` = 'Site_Welcome' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_fbadm' WHERE `Setting` = 'FB_Admins' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_nsort' WHERE `Setting` = 'News_Sort' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_banned' WHERE `Setting` = 'Site_Banned' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_vk_share_title' WHERE `Setting` = 'VK_Share_Title' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_vk_share_wall' WHERE `Setting` = 'VK_Share_Wall' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_vk_share_attach' WHERE `Setting` = 'VK_Share_Attach' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_news_similar' WHERE `Setting` = 'News_Similar' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_rss_link' WHERE `Setting` = 'RSS_Link' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_google_code' WHERE `Setting` = 'Google_Code' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_ya_code' WHERE `Setting` = 'Ya_Code' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_site_name' WHERE `Setting` = 'Site_Name' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_site_copy' WHERE `Setting` = 'Site_Copyright' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_goog_an_id' WHERE `Setting` = 'Google_Analytics' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_coms_sort' WHERE `Setting` = 'Coms_Sort' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_art_sort' WHERE `Setting` = 'Articles_Sort' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_art_title' WHERE `Setting` = 'Articles_Title' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_art_count' WHERE `Setting` = 'Articles_Count' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_enable_ufu' WHERE `Setting` = 'News_UFU' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_enable_ajax' WHERE `Setting` = 'Site_AJAX' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_com_show_cnt' WHERE `Setting` = 'ComsList_Count' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_logger_enabled' WHERE `Setting` = 'Site_Logger' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_font_family' WHERE `Setting` = 'Font_Family' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_font_size' WHERE `Setting` = 'Font_Size' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_site_swatch' WHERE `Setting` = 'Site_Swatch' LIMIT 1;");if ($sql){ $cnt+=1; }
			$sql=mysql_query("UPDATE `Settings` SET `Parameter` = '$p_disable_reason' WHERE `Setting` = 'Site_Active_Reason' LIMIT 1;");if ($sql){ $cnt+=1; }
			
			if ($cnt==$params_count){ 
				$content="<script>$(document).ready(function () { alert('Параметры изменены!','noerror'); rd_ajax('act=admin'); });</script>"; 
			} else {
				$content="<script>$(document).ready(function () { alert('Произошла ошибка при выполнении запроса!','error'); rd_ajax('act=admin'); });</script>"; 
			}
	} else {
	/* DASHBOARD */
		if ($__cms_update_info!=""){
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $__cms_update_info);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			$got_ver = curl_exec($ch);
			curl_close($ch);
			$updater='';
			if (($__cms_ver<$got_ver) && ($got_ver!==FALSE)){
				if ($__cms_update_link!=""){
					$updater="<div class='alert alert-error'>Система управления сайтом устарела!<br>Доступна версия: <b>$got_ver</b>.<br><a href='$__cms_update_link' target='_blank' class='btn btn-mini'>Перейти к обновлению</a></div>";
				} else {
					$updater="<div class='alert alert-error'>Система управления сайтом устарела!<br>Доступна версия: <b>$got_ver</b>.</div>";
				}
			} else {
				if ($got_ver===FALSE){
					if ($__cms_update_link!=""){
						$updater="<div class='alert alert-error'>Произошла ошибка при получении информации о новой версии!<br><a href='$__cms_update_link' target='_blank' class='btn btn-mini'>Перейти на страницу получения информации</a>.</div>";
					} else {
						$updater="<div class='alert alert-error'>Произошла ошибка при получении информации о новой версии!</div>";
					}
				}
			}
		}
		$sql=mysql_query("SELECT COUNT(*) from `News`");
		$row = mysql_fetch_assoc($sql);
		$total_news=$row['COUNT(*)'];

		$sql=mysql_query("SELECT COUNT(*) from `News` WHERE `Category`='1';");
		$row = mysql_fetch_assoc($sql);
		$draft_news=$row['COUNT(*)'];

		$sql=mysql_query("SELECT COUNT(*) from `Comments`");
		$row = mysql_fetch_assoc($sql);
		$total_comments=$row['COUNT(*)'];

		$sql=mysql_query("SELECT COUNT(*) from `Users`");
		$row = mysql_fetch_assoc($sql);
		$total_users=$row['COUNT(*)'];
		/* END */
		$serv_time=$n_time=date('d.m.Y H:i');
		$serv_time_offset=$n_time=date('d.m.Y H:i',date('U')+$offset_t);
		$content="
		<div class='well'>
			<h4 class='heading'>Изменение параметров</h4>
				<form method='post'><input type='hidden' name='act' value='admin'>
				<input type='hidden' name='do' value='edit'>
				<div class='tabbable'>
					<ul class='nav nav-pills' id='rdtab'> 
						<li><a href='#site' data-toggle='tab'>Сайт</a></li>
						<li><a href='#start' data-toggle='tab'>Новости</a></li>
						<li><a href='#lists' data-toggle='tab'>Содержимое</a></li>
						<li><a href='#sbar' data-toggle='tab'>Сайдбар</a></li>
						<li><a href='#socialy' data-toggle='tab'>Соц. сети и поиск</a></li>
					</ul>
					<div class='tab-content'>
						<div class='tab-pane active' id='site'>
							<div class='container-fluid'>
								<div class='row center'>
									<a href='?act=news&cat=*' class='rdajax span2 btn btn-big' data-title='Все новости'><h2>$total_news</h2><h4>новостей</h4></a>
									<a href='?act=news&cat=1' class='rdajax span2 btn btn-big' data-title='Все новости в черновиках'><h2>$draft_news</h2><h4>в черновиках</h4></a>
									<a href='?act=comments' class='rdajax span2 btn btn-big' data-title='Все комментарии'><h2>$total_comments</h2><h4>комментариев</h4></a>
									<a href='?act=users' class='rdajax span2 btn btn-big' data-title='Все пользователи'><h2>$total_users</h2><h4>пользователей</h4></a>
								</div>
							</div>
							<p><div class='alert alert-info'>Установленная версия RD CMS: <b>$__cms_ver</b>.</div></p>
							$updater
							<table class='table table-striped'>
								<tr><td class='lefttd'></td><td><b>Настройки сайта</b></td></tr>
								<tr><td class='lefttd'>Сайт активен</td><td>
								<select name='Site_Active' class='input-block-level'>";
								$content.= (!$_site_active) ? "<option value='0' selected>Неактивен</option><option value='1'>Активен</option>" : "<option value='0'>Неактивен</option><option value='1' selected>Активен</option>";
			$content.="			</select>
								</td></tr>
								<tr><td class='lefttd'>Причина отключения сайта</td><td><textarea class='input-block-level' type='text' name='Site_Active_Reason'>".htmlspecialchars($_disable_reason)."</textarea><div class='help-block'>Для скрытия блока уберите весь текст или поставьте в начало знак <b>\"$\"</b> (без кавычек). В данном поле поддерживаются HTML-теги.</div></td></tr>
								<tr><td class='lefttd'>Забаненые IP-адреса</td><td><input class='input-block-level' type='text' name='Site_Banned' value='$banned'><div class='help-block'>IP адреса перечисляются через запятую, без пробелов.</div></td></tr>
								<tr><td class='lefttd'>Смещение времени (сек)</td><td><input class='input-block-level' type='text' name='Time_Offset' value='$offset_t'><div class='help-block'>Время сервера: <b>$serv_time</b><br>Время с текущим значением смещения: <b>$serv_time_offset</b></div></td></tr>
								<tr><td class='lefttd'>Использовать AJAX</td><td>
								<select name='Site_AJAX' class='input-block-level'>";
								$content.=(!$enable_ajax) ? "<option value='0' selected>Не использовать</option><option value='1'>Использовать</option>" : "<option value='0'>Не использовать</option><option value='1' selected>Использовать</option>";
			$content.="			</select>
								<div class='help-block'>AJAX позволяет обновлять только нужные элементы на странице, не перезагружая страницу полностью.</div></td></tr>
								<tr><td class='lefttd'>Использовать ЧПУ</td><td>
								<select name='News_UFU' class='input-block-level'>";
								if ($enable_ufu==0){
									$content.="<option value='0' selected>Не использовать</option><option value='1'>Использовать</option><option value='2'>Использовать короткие ссылки</option>";
								} else if ($enable_ufu==1){
									$content.="<option value='0'>Не использовать</option><option value='1' selected>Использовать</option><option value='2'>Использовать короткие ссылки</option>";
								} else {
									$content.="<option value='0'>Не использовать</option><option value='1'>Использовать</option><option value='2' selected>Использовать короткие ссылки</option>";
								}
			$content.="			</select><div class='help-block'>ЧПУ - веб-адрес, удобный для восприятия человеком. Для использования ЧПУ, необходим файл \".htaccess\", настроенный для использования таковых ссылок.</div></td></tr>
								<tr><td class='lefttd'>Запись всех переходов</td><td>
								<select name='Site_Logger' class='input-block-level'>";
								$content.=(!$_logger_enabled) ? "<option value='1'>Включить</option><option value='0' selected>Отключить</option>" : "<option value='1' selected>Включить</option><option value='0'>Отключить</option>";
			$content.="			</select>
								</td></tr>
							</table>
						</div> 
						<div class='tab-pane' id='start'>
							<table class='table table-striped'>
								<tr><td class='lefttd'></td><td><b>Настройки категорий новостей</b></td></tr>
								<tr><td class='lefttd'>Управление категориями</td><td><a href='?act=categories' class='btn btn-block rdajax'>Перейти к управлению категориями</a><div class='alert alert-info'>Сохраните настройки, перед переходом в управление категориями.</div></td></tr>
								<tr><td class='lefttd'></td><td><b>Настройки модуля новостей</b></td></tr>
								<tr><td class='lefttd'>Ссылка на RSS</td><td><input class='input-block-level' type='text' name='RSS_Link' value=\"$rss_link\"><div class='help-block'>Стандартное расположение ленты новостей: <b>http://".$_SERVER['HTTP_HOST']."/index.php?act=rss&ajax=2</b></div></td></tr>
								<tr><td class='lefttd'>Параметры сортировки новостей</td><td>
								<select name='News_Sort' class='input-block-level'>";
								for($i=0;$i<=3;$i++){
									if ($i==$news_sort){$seld=" selected";} else {$seld="";}
									$content.="<option value='$i'$seld>$sort_methods[$i]</option>";
								}
			$content.="			</select>
								</td></tr>
								<tr><td class='lefttd'>Количество символов в краткой версии новостей</td><td><input class='input-block-level' type='text' name='Short_Sym' value='$short_sym'><div class='help-block'>Если количество слов в краткой версии отлично от нуля, то текст новости будет обрезан до указанного количества символов (не считая теги, текст обрежется по окончанию слова). Если в тексте пристутствует тег \"&lt;hr&gt;\", то новость обрежется либо до ограничения по символам, либо до данного тега.</div></td></tr>
								<tr><td class='lefttd'>Количество новостей в \"Похожих новостях\"</td><td><select name='News_Similar' class='input-block-level'>";
								for($i=0;$i<=10;$i++){
									if ($i==$news_similar){$seld=" selected";} else {$seld="";}
									if ($i==0){ $ns_title="Отключить блок"; } else { $ns_title=$i;}
									$content.="<option value='$i'$seld>$ns_title</option>";
								}
			$content.="			</select></td></tr>
								<tr><td class='lefttd'>Сортировка комментариев</td><td><select name='Coms_Sort' class='input-block-level'>";
								$content.=($coms_sort==1) ? "<option value='1' selected>От новых к старым</option><option value='2'>От старых к новым</option>" : "<option value='1'>От новых к старым</option><option value='2' selected>От старых к новым</option>";
			$content.="			</select></td></tr>
								<tr><td class='lefttd'></td><td><b>Оформление</b></td></tr>

								<tr><td class='lefttd'>Шрифт</td><td><select name='Font_Family' class='input-block-level'>";
								$content.=($_font_family=='ubuntu') ? "<option value='ubuntu' selected>Ubuntu</option>" : "<option value='ubuntu'>Ubuntu</option>";
								$content.=($_font_family=='ptsans') ? "<option value='ptsans' selected>PT Sans</option>" : "<option value='ptsans'>PT Sans</option>";
								$content.=($_font_family=='opensans') ? "<option value='opensans' selected>Open Sans</option>" : "<option value='opensans'>Open Sans</option>";
								$content.=($_font_family=='cuprum') ? "<option value='cuprum' selected>Cuprum</option>" : "<option value='cuprum'>Cuprum</option>";
								$content.=($_font_family=='istokweb') ? "<option value='istokweb' selected>Istok Web</option>" : "<option value='istokweb'>Istok Web</option>";
								$content.=($_font_family=='scada') ? "<option value='scada' selected>Scada</option>" : "<option value='scada'>Scada</option>";
								$content.=($_font_family=='arial') ? "<option value='arial' selected>Arial</option>" : "<option value='arial'>Arial</option>";
								$content.=($_font_family=='tahoma') ? "<option value='tahoma' selected>Tahoma</option>" : "<option value='tahoma'>Tahoma</option>";
								$content.=($_font_family=='sansserif') ? "<option value='sansserif' selected>Sans Serif</option>" : "<option value='sansserif'>Sans Serif</option>";
			$content.="			</select>
								</td></tr>
								<tr><td class='lefttd'>Размер базового шрифта</td><td><select name='Font_Size' class='input-block-level'>";
								for($i=6;$i<=20;$i++){
									if ($i==$_font_size){$seld=" selected";} else {$seld="";}
									$content.="<option value='$i'$seld>".$i."pt</option>";
								}
			$content.="				</select>
								</td></tr>
								<tr><td class='lefttd'>Тема</td><td><select name='Site_Swatch' class='input-block-level'>";
								$content.=($_site_swatch=='default') ? "<option value='default' selected>Стандартная</option>" : "<option value='default'>Стандартная</option>";
								$content.=($_site_swatch=='amelia') ? "<option value='amelia' selected>Amelia</option>" : "<option value='amelia'>Amelia</option>";
								$content.=($_site_swatch=='cosmo') ? "<option value='cosmo' selected>Cosmo</option>" : "<option value='cosmo'>Cosmo</option>";
								$content.=($_site_swatch=='cyborg') ? "<option value='cyborg' selected>Cyborg</option>" : "<option value='cyborg'>Cyborg</option>";
								$content.=($_site_swatch=='slate') ? "<option value='slate' selected>Slate</option>" : "<option value='slate'>Slate</option>";
								$content.=($_site_swatch=='spacelab') ? "<option value='spacelab' selected>Space Lab</option>" : "<option value='spacelab'>Space Lab</option>";
								$content.=($_site_swatch=='spruce') ? "<option value='spruce' selected>Spruce</option>" : "<option value='spruce'>Spruce</option>";
								$content.=($_site_swatch=='superhero') ? "<option value='superhero' selected>Superhero</option>" : "<option value='superhero'>Superhero</option>";
								$content.=($_site_swatch=='united') ? "<option value='united' selected>United</option>" : "<option value='united'>United</option>";
			$content.="			</select>
								<div class='help-block'>Используются темы от <a href='http://bootswatch.com/'>Bootswatch.com</a>. Не рекомендуется использовать нестандартные темы, т.к. возможно возникновение проблем.</div>
								</td></tr>
							</table>
						</div>
						<div class='tab-pane' id='lists'>
							<table class='table table-striped'>
								<tr><td class='lefttd'></td><td><b>Общие настройки отображения содержимого на сайте</b></td></tr>
								<tr><td class='lefttd'>Кол-во новостей на главной</td><td><input class='input-block-level' type='text' name='News_at_home' value='$news_at_home'></td></tr>
								<tr><td class='lefttd'>Кол-во строк в логе</td><td><input class='input-block-level' type='text' name='Lines_at_log' value='$lines_at_log'></td></tr>
								<tr><td class='lefttd'>Название сайта</td><td><input class='input-block-level' type='text' name='Site_Name' value='$site_name'></td></tr>
								<tr><td class='lefttd'>Строка Copyright</td><td><input class='input-block-level' type='text' name='Site_Copy' value='$site_copy'><div class='help-block'>Для вывода текущего года, напишите <b>\"%year%\"</b> (без кавычек)</div></td></tr>
								<tr><td class='lefttd'>Приветствие</td><td><textarea class='input-block-level' type='text' name='Site_Welcome'>".htmlspecialchars($welcome_msg)."</textarea><div class='help-block'>Для скрытия блока уберите весь текст или поставьте в начало знак <b>\"$\"</b> (без кавычек). В данном поле поддерживаются HTML-теги.</div></td></tr>
							</table>
						</div>
						<div class='tab-pane' id='socialy'>
							<table class='table table-striped'>
								<tr><td class='lefttd'></td><td><b>Общее</b></td></tr>
								<tr><td class='lefttd'>Название сайта</td><td><input class='input-block-level' type='text' name='VK_Share_Title' value='$vk_share_title'><div class='help-block'>Это поле отвечает за заголовок ссылки при постинге в соц. сети, а так же за название RSS потока.</div></td></tr>
								<tr><td class='lefttd'></td><td><b>В Контакте</b></td></tr>
								<tr><td class='lefttd'>ID стены для отправки сообщений</td><td><input class='input-block-level' type='text' name='VK_Share_Wall' value='$vk_share_wall'><div class='help-block'>Для указания стены группы или публичной страницы, напишите перед ID символ <b>\"-\"</b> (без кавычек).</div></td></tr>
								<tr><td class='lefttd'>Вложения</td><td><input class='input-block-level' type='text' name='VK_Share_Attach' value='$vk_share_attach'><div class='help-block'>Перечислите ID вложений через запятую. Максимум - <b>10 записей</b>.</div></td></tr>
								<tr><td class='lefttd'>API ID</td><td><input class='input-block-level' type='text' name='VK_API_ID' value='$api_id'></td></tr>
								<tr><td class='lefttd'>Secret Key</td><td><input class='input-block-level' type='text' name='VK_API_SECRET' value='$secret_key'><div class='help-block'>Для получения API ID и Secret Key, создайте приложение-сайт В Контакте. <a href='http://vk.com/developers.php' target='_blank'>Подробнее</a>.</div></td></tr>
								<tr><td class='lefttd'></td><td><b>Facebook</b></td></tr>
								<tr><td class='lefttd'>Admin ID</td><td><input class='input-block-level' type='text' name='FB_Admins' value='$fb_admins'><div class='help-block'>ID администраторов сайта на Facebook. ID перечисляются через запятую, без пробелов.</div></td></tr>
								<tr><td class='lefttd'></td><td><b>Поисковые системы</b></td></tr>
								<tr><td class='lefttd'>Meta-тег проверки Google</td><td><input class='input-block-level' type='text' name='Google_Code' value='$google_code'><div class='help-block'><a href='https://www.google.com/webmasters/tools/home' target='_blank'>Подробнее</a></div></td></tr>
								<tr><td class='lefttd'>Google Analytics ID</td><td><input class='input-block-level' type='text' name='Google_Analytics' value='$google_an_id'><div class='help-block'>ID отображается на главной странице Google Analytics. Он выглядит так: <b>UA-xxxxxxxx-x</b>. <a href='https://www.google.com/analytics/web/?pli=1&hl=ru' target='_blank'>Подробнее</a></div></td></tr>
								<tr><td class='lefttd'>Meta-тег проверки Яндекса</td><td><input class='input-block-level' type='text' name='Ya_Code' value='$ya_code'><div class='help-block'><a href='http://webmaster.yandex.ru/site/add.xml' target='_blank'>Подробнее</a></div></td></tr>
							</table>
						</div>
						<div class='tab-pane' id='sbar'>
							<table class='table table-striped'>
								<tr><td class='lefttd'></td><td><b>Главное меню</b></td></tr>
								<tr><td class='lefttd'>Пункты меню</td><td><a href='?act=mainmenu' class='btn btn-block rdajax'>Перейти к управлению главным меню</a><div class='alert alert-info'>Сохраните настройки, перед переходом в управление главным меню.</div></td></tr>
								<tr><td class='lefttd'></td><td><b>Список статей</b></td></tr>
								<tr><td class='lefttd'>Сортировка статей</td><td><input class='input-block-level' type='text' name='Articles_Sort' value='$articles_sort'><div class='help-block'>Сортировка в случайном порядке: \"<b>ORDER BY RAND()</b>\"<br>По просмотрам: \"<b>ORDER BY `Views` DESC</b>\"<br>Определенные статьи: \"<b>AND `Num` IN (x,y,z,...)</b>\" (количество статей должно быть не более, чем указано в поле \"Количество статей\".)<br>Во всех примерах не нужны кавычки (\").</div></td></tr>
								<tr><td class='lefttd'>Заголовок блока</td><td><input class='input-block-level' type='text' name='Articles_Title' value='$articles_title'></td></tr>
								<tr><td class='lefttd'>Количество статей</td><td><select name='Articles_Count' class='input-block-level'>";
								for($i=0;$i<=15;$i++){
									if ($i==$articles_count){$seld=" selected";} else {$seld="";}
									if ($i==0){ $bttext="Скрыть блок"; } else { $bttext=$i; }
									$content.="<option value='$i'$seld>$bttext</option>";
								}
			$content.="			</select></td></tr>
								<tr><td class='lefttd'></td><td><b>Облако тегов</b></td></tr>
								<tr><td class='lefttd'>Количество тегов в облаке тегов</td><td><select name='Tag_Cloud_Count' class='input-block-level'>";
								for($i=0;$i<=15;$i++){
									if ($i==$tags_count){$seld=" selected";} else {$seld="";}
									if ($i==0){ $bttext="Скрыть блок"; } else { $bttext=$i; }
									$content.="<option value='$i'$seld>$bttext</option>";
								}
			$content.="			</select></td></tr>
								<tr><td class='lefttd'>Параметры сортировки тегов</td><td><select name='Tag_Cloud_Params' class='input-block-level'>";
									if ($tags_params==0){
										$content.="<option value='0' selected>Отключить облако тегов</option><option value='1'>Выводить последние теги</option><option value='2'>Выводить популярные теги</option>";
									} else if ($tags_params==1){
										$content.="<option value='0'>Отключить облако тегов</option><option value='1' selected>Выводить последние теги</option><option value='2'>Выводить популярные теги</option>";
									} else {
										$content.="<option value='0'>Отключить облако тегов</option><option value='1'>Выводить последние теги</option><option value='2' selected>Выводить популярные теги</option>";
									}
			$content.="			</select></td></tr>
								<tr><td class='lefttd'></td><td><b>Блок комментариев</b></td></tr>
								<tr><td class='lefttd'>Количество комментариев</td><td><select name='ComsList_Count' class='input-block-level'>";
								for($i=0;$i<=10;$i++){
									if ($i==$com_show_cnt){$seld=" selected";} else {$seld="";}
									if ($i==0){ $ns_title="Отключить блок"; } else { $ns_title=$i;}
									$content.="<option value='$i'$seld>$ns_title</option>";
								}
			$content.="			</select></td></tr>
								<tr><td class='lefttd'></td><td><b>Рекламный блок</b></td></tr>
								<tr><td class='lefttd'>Рекламный блок</td><td><textarea class='input-block-level' type='text' name='Advert_On_Main'>".htmlspecialchars($ads_main)."</textarea><div class='help-block'>Для скрытия блока уберите весь текст или поставьте в начало знак <b>\"$\"</b> (без кавычек). В данном поле поддерживаются HTML-теги.</div></td></tr>
								<tr><td class='lefttd'></td><td><b>Блок статистики</b></td></tr>
								<tr><td class='lefttd'>Блок статистики</td><td><textarea class='input-block-level' type='text' name='Stats_On_Main'>".htmlspecialchars($stats_main)."</textarea><div class='help-block'>Для скрытия блока уберите весь текст или поставьте в начало знак <b>\"$\"</b> (без кавычек). В данном поле поддерживаются HTML-теги.</div></td></tr>
							</table>
					</div>
				</div>
			</div>
				

					<div class='form-actions'>
						<div class='help-block'>Не забудьте сохранить настройки!";
						if (trim($_recache_url)!=''){ $content.='<br>После обновления некоторых настроек необходимо перекешировать шаблон.'; }
		$content.="	</div>
					<div class='btn-toolbar'>
						<input type='submit' value='Сохранить' class='btn btn-primary'>";
						if (trim($_recache_url)!=''){ $content.="<a href='$_recache_url' onClick='recache(\"$_recache_url\");return false;' class='btn'>Перекешировать шаблон</a>"; }
		$content.="	
					</div>
					</div>
				</form>
			</div>
		</div>";
	}
} else {
	$title="Ошибка!";
	$content="<script>$(document).ready(function () { alert('Доступ запрещён!','error'); rd_ajax('act=news'); });</script>"; 
}
?>
