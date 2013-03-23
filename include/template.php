<? if (!defined('RD')) die('Hacking attempt!'); ?><!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
	<? echo $scripts; ?>
</head>
<body>
<!-- Fullscreen Alert Message -->
<div id='vk_frame_bg'>
	<div class='popup_body' id='vk_frame_inner'><div class='popup_title'><? echo $site_name; ?><a href='javascript:' onClick="$('#vk_frame_bg').fadeOut('fast'); return false;" class='form_close'></a></div><div class='popup_text'>
		<span id='vk_msg'></span>
	</div></div>
</div>
<!-- / -->

<!-- Ajax Block -->
<div id='ajax_temp' class='hide'></div>
<div id='alerts_container'></div>

<!-- Logo -->
<header class='container-fluid logo-bg'>
	<div class='container'>
		<div id='logo_holder'>
			<a class='rdajax logo_img' href='?act=news' data-title="Главная страница"></a>
		</div>
	</div>
</header>
<!-- / -->	
<div class='container'>
	<div class='row'>	
		<aside class='span3'>
			<nav>
				<? echo $main_menu; ?>
			</nav>
			
			<div class='hide well well-small' id='login_loader'><div class='ajaxloader'></div> Загрузка</div>
			<div id='user_menu' class='hide well well-small'></div>
			<ul id='admin_menu' class='hide nav nav-tabs nav-stacked rd-nav-tabs'></ul>
			<ul id='mod_menu' class='hide nav nav-tabs nav-stacked rd-nav-tabs'></ul>
			<form id='login' onsubmit='login_req($(this));return false;' class='form-vertical well'>
				<input type='hidden' name='act' value='auth'><input type='hidden' name='ajax' value='2'><input type='hidden' name='login' value='1'>
				<div class='control-group'>
					<label for='popup-login' class='control-label'><i class='icon-user'></i> Логин</label>
					<div class='controls'>
						<input class='hundred' type='text' name='rd_login' value='' placeholder='Логин или ID' id='popup-login'/>
					</div>
				</div>
				<div class='control-group'>
					<label for='popup-pass' class='control-label'><i class='icon-arrow-right'></i> Пароль</label>
					<div class='controls'>
						<input class='hundred' type='password' name='rd_pass' value='' placeholder='Пароль' id='popup-pass'/>
					</div>
				</div>
				<div class='control-group'>
					<div class='controls'>
						<div class='btn-toolbar'>
							<div class='btn-group'>
								<a href='?act=reg' class='btn' onClick="login_form(0);rd_ajax('act=reg');return false;">Регистрация</a>
								<input type='submit' value='Вход' class='btn btn-primary'>
							</div>
						</div>
					</div>
				</div>
				<a href='javascript:' onClick='doLogin(); return false;' data-title='Вы можете зарегистрироваться и входить на сайт с помощью учётной записи В Контакте'>Войти через VK</a><br>
				<a href='?act=users&amp;mode=restore' class='rdajax'>Забыли пароль?</a>
			</form>
			
			<? if ((trim($stats_main)!="") and (substr($stats_main,0,1)!="$")){ ?>
			<div class='well'>
				<ul class='nav nav-list'>
					<li class="nav-header">Статистика</li>
				</ul>
				<? echo $stats_main; ?>
			</div>
			<? } ?>
			
			<? if ((trim($ads_main)!="") and (substr($ads_main,0,1)!="$")){ ?>
			<div class='well'>
				<ul class='nav nav-list'>
					<li class="nav-header">Реклама</li>
				</ul>
				<? echo $ads_main; ?>
			</div>
			<? } ?>
		</aside>
		<section class='span6'>
			<ul class="breadcrumb">
				<li><a href="/" class='rdajax'>Главная</a> <span class="divider">/</span></li>
				<li id='loader'>Загрузка...</li>
				<li class="active" id='title'><? echo $title; ?></li>
			</ul>
			<!-- -->
			<div id='content'>
				<? if ($_ajax_enabled=="true"){ ?>
					<script>document.write("<di"+"v class='alert alert-info'>Подождите! Идет загрузка!</d"+"iv>");</script>
				<? } else { 
					echo $content; 
				} ?>
			</div>
		</section>
		<aside class='span3'>
			<div class='well well-small'>
				<form class='form-vertical' method='post' style='margin:0 !important;'>
					<input type='hidden' name='act' value='news'>
					<div class='controls controls-row'>
						<input type='text' name='s' placeholder='Поиск...' class='input-block-level'>
						<input type='submit' value='Найти!' class='btn input-block-level search-button'>
					</div>
				</form>
			</div>
			<? if ((trim($articles_sort)!="") and ($articles_count>0)){ ?>
			<ul class='nav nav-tabs nav-stacked rd-nav-tabs'>
				<? if (trim($articles_title)!=""){ ?><li class="nav-header"><? echo $articles_title; ?></li><? } ?>
				<? echo $articles_inner; ?>
			</ul>
			<? } ?>
			
			<? if (($tags_count>0) and ($tags_params>0)){ ?>
			<ul class='nav nav-tabs nav-stacked rd-nav-tabs'>
				<li class="nav-header">Облако тегов</li>
				<? echo $tag_cloud;	?>
			</ul>
			<? } ?>
			
			<? if ($com_show_cnt>0){ ?>
			<ul class='nav nav-tabs nav-stacked rd-nav-tabs'>
				<li class="nav-header">Комментарии</li>
				<? echo $coms_block; ?>
			</ul>
			<? } ?>
		</aside>
	</div>
</div>
<footer class='footer container'>
	<p>
		<!--[if lte IE 7]><div class='alert alert-info'>Вы пользуетесь устаревшим браузером. Скорее всего, сайт не будет работать в Вашем браузере.</div><![endif]-->
		<? echo $_copyright; ?>
	</p>
</footer>
	
</body></html>
