<?
ob_start("ob_gzhandler");
header('Content-Type: text/html; charset=utf-8'); 
$e=$_REQUEST['error'];
$t=array(
'400'=>'Плохой запрос',
'401'=>'Неавторизован',
'402'=>'Необходима оплата',
'403'=>'Доступ запрещен',
'404'=>'Запрашиваемая страница не найдена',
'405'=>'Метод не поддерживается',
'406'=>'Не приемлимо',
'410'=>'Удален',
'411'=>'Необходима длина',
'414'=>'Запрашиваемый URI слишком длинный',
'500'=>'Внутренняя ошибка сервера',
'503'=>'Сервис временно недоступен',
'777'=>'Хакеры, идите лесом! Тута нету баги',
'999'=>'Произошла ошибка при подключении к базе данных!'
);
if (!isset($t[$e])){
$e='777';
}
$reason=$t[$e];
$__sitename=str_replace("&#39;","'",$site_name);

$title='Ошибка!';
if ($e=='999'){
	$content="<div style='text-align:center;margin:20px 0;'><span style='background:#FFF; border:2px solid #444; border-radius:5px; font-size:50pt; color:#000; margin:10px 0;padding:10px 100px;'>SQL</span></div><div style='text-align:center'>$reason<br><a href='/' class='norm'>Обновить страницу</a></div>";
} else {
	$content="<div style='text-align:center;margin:20px 0;'><span style='background:#FFF; border:2px solid #444; border-radius:5px; font-size:50pt; color:#000; margin:10px 0;padding:10px 100px;'>$e</span></div><div style='text-align:center'>Ошибка HTTP <b>$e</b>: $reason!<br><a href='/' class='norm'>Перейти на главную страницу</a></div>";
}
?>
<!DOCTYPE HTML>
<html>
<head><title><? echo $__sitename; ?></title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<? echo $scripts; ?>
<style>
	body{background:#CCF; margin:5em auto; }
</style>
</head>
<body>
<div id='wrapper'>
	<!-- Logo -->
	<div id='logo_wrapper'>
		<div id='logo_holder'>
				<a class='rdajax logo_img' href='/' title="Главная страница"></a>
		</div>
	</div>
	<!-- / -->

	<div id='main'>
		<div class='news_body'><? echo $content; ?></div>
	</div>
</div>

</body></html>
<?ob_end_flush();?>