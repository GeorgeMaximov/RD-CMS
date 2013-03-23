<?
	$__cms=array(
		'name'=>'RD CMS',
		'version'=>'0.51',
		'current'=>'http://static.helpcast.ru/cmsver.html',
		'info'=>'http://helpcast.ru/cms',
		'updater'=>''
	);
	define('RD',md5($time_start));
	header('X-Powered-By: '. $__cms['name'] . ' ' . $__cms['version'] . '. Visit: ' . $__cms['info'] . ' for more info.');
	$__copy_add='Сайт управляется с помощью системы управления сайтом <a href="' . $__cms['info'] . '">' . $__cms['name'] . ' ' . $__cms['version'] . '</a><br>'."\r\n";
?>
