<?
ob_start("ob_gzhandler");
if (substr($_SERVER['SERVER_NAME'], 0, 4) === 'www.')
{
    header('Location: http://' . substr($_SERVER['SERVER_NAME'], 4)); exit();
}

$ajax=isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : false;

if (($_SERVER['HTTP_USER_AGENT']!='') && $ajax==false && !isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_recache_url!=''){
	err_and_die(403);
}

require_once('include/settings.php');
require("modules/engine/init.php");
if (!defined('RD')) err_and_die(403);

$r_act = $_REQUEST['act'];
if ((!$_site_active) && (!$admin) && ($r_act!='auth')){
	$r_module = "modules/engine/site_off.php";
} else {
	$r_module = "modules/engine/news.php";
	foreach ($modules as $name => $path){
		if ($r_act == $name) { $r_module = $path; } 
	}
}
include($r_module);

if ((!$_site_active) && ($admin)){
	$__="#title#$title#/title##content#<div class='alert alert-error'>Сайт отключен! Все страницы видны только администраторам! <a href='?act=admin' class='rdajax btn'>Настройки сайта</a></div>$_pass_info$content#/content#";
} else {
	$__="#title#$title#/title##content#$_pass_info$content#/content#";
}



if ($ajax==2){
	echo $_pass_span.$content;
} else {
	if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) || ($ajax==1)){
		echo $__;
	} else {
		$tag_cloud=generate_tags($tags_count,$tags_params);
		$main_menu=generate_mainmenu();
		require("include/template.php");
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		echo "\r\n<!-- Execution time: $time sec. -->\r\n<!-- ".$__cms['version']." $__cms_ver - Info: $__cms_update_link -->";
	}
}
mysql_close();
ob_end_flush();
?>
