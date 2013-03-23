<?php
error_reporting(0);
ob_start();
phpinfo(INFO_MODULES);
$info = ob_get_contents();
ob_end_clean();
$info = stristr($info, 'Client API version');
preg_match('/[1-9].[0-9].[1-9][0-9]/', $info, $match);
$mysql_ver = $match[0];

ob_start("ob_gzhandler");

function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'){
    $chars_length = (strlen($chars) - 1);
    $string = $chars{rand(0, $chars_length)};
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        $r = $chars{rand(0, $chars_length)};
        if ($r != $string{$i - 1}) $string .=  $r;
    }
    return $string;
}

header('Content-Type: text/html; charset=utf-8');
$step=$_REQUEST['step'];
$content='';
$errors=0;
if ($step==1){
	$content.='<h3>Добро пожаловать!</h3><p>Вас приветствует мастер установки и первоначальной настройки системы управления сайтов RD CMS.</p>';
	$content.='<h3>Для начала...</h3><p>Для начала, проверим соответствие Вашей системы системным требованиям CMS: <table class="table table-bordered"><thead><tr><th>Параметр</th><th>Требуемое значение</th><th>Текущее значение</th></tr></thead><tbody>';
	if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
		$content.='<tr class="success"><td>Версия PHP</th><td>5.0.0</th><td>'. PHP_VERSION .'</td></tr>';
	} else {
		$content.='<tr class="error"><td>Версия PHP</th><td>5.0.0</th><td>'. PHP_VERSION .'</td></tr>';
		$errors++;
	}
	
	if (version_compare($mysql_ver, '5.0', '>=')) {
		$content.='<tr class="success"><td>Версия MySQL</th><td>5.0</th><td>'. $mysql_ver .'</td></tr>';
	} else {
		$content.='<tr class="error"><td>Версия MySQL</th><td>5.0</th><td>'. $mysql_ver .'</td></tr>';
		$errors++;
	}
	
	if (function_exists('curl_init')) {
		$content.='<tr class="success"><td>Поддержка cURL</th><td>Да</th><td>Да</td></tr>';
	} else {
		$content.='<tr class="error"><td>Поддержка cURL</th><td>Да</th><td>Нет</td></tr>';
		$errors++;
	}
	
	if (ini_get('short_open_tag')=='1'){
		$content.='<tr class="success"><td>Значение short_open_tag</th><td>1</th><td>1</td></tr>';
	} else {
		$content.='<tr class="error"><td>Значение short_open_tag</th><td>1</th><td>0</td></tr>';
		$errors++;
	}
	
	if ((ini_get('register_globals')=='0') || (ini_get('register_globals')=='')){
		$content.='<tr class="success"><td>Значение register_globals</th><td>0</th><td>0</td></tr>';
	} else {
		$content.='<tr class="warning"><td>Значение register_globals</th><td>0</th><td>1</td></tr>';
	}
	
	if (!file_exists(realpath(dirname(__FILE__)).'/include/install.lock')){
		$content.='<tr class="success"><td>Наличие настроек</th><td>0</th><td>0</td></tr>';
	} else {
		$content.='<tr class="warning"><td>Наличие настроек</th><td>0</th><td>1</td></tr>';
	}
	
	if (!file_exists(realpath(dirname(__FILE__)).'/include/install.lock')){
		$content.='<tr class="success"><td>Блокировка установки</th><td>0</th><td>0</td></tr>';
	} else {
		$content.='<tr class="error"><td>Блокировка установки</th><td>0</th><td>1</td></tr>';
		$errors++;
	}
	
	$content.='</tbody></table>';
	$content.='<p>Параметры, подсвеченные <span class="label label-important">красным</span> необходимо поменять на требуемые для продолжения установки.<br>Параметры, подсвеченные <span class="label label-warning">жёлтым</span> рекомендуется изменить на требуемые, но необязательно для продолжения установки.<br>Блокировка установки означает, что CMS уже была настроена и создан файл <code>/include/install.lock</code>.</p>';
	if ($errors==0){
		$content.='<div class="form-actions"><a class="btn btn-primary" href="?step=2">Далее <i class="icon-white icon-arrow-right"></i></a></div>';
	} else {
		$content.='<p>Если Вам требуется качественный облачный хостинг, можете попробовать хостинг от <a href="http://www.dualspace.ru/?ref=06092322092012">Dualspace</a>.</p>';
	}
} else if ($step==2){
	$content.='<h3>Подключение к базе данных</h3>';
	$content.="<form method='post' class='form-horizontal'><input type='hidden' name='step' value='3'/>
				<div class='control-group'>
					<label class='control-label' for='inputHost'>Название</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputHost' placeholder='localhost' name='mysql_host' type='text' required>
						<span class='help-inline'>Обычно - localhost</span>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputUser'>Пользователь</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputUser' name='mysql_user' type='text' required>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputPass'>Пароль</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputPass' name='mysql_password' type='password'>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputBase'>База</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputBase' name='mysql_database' type='text' required>
					</div>
				</div>
				<div class='form-actions'>
					<a href='?step=1' class='btn'>Отмена</a>
					<input type='submit' value='Проверить подключение' class='btn btn-primary'>
				</div>
			</form>";
} else if ($step==3){
	if ((isset($_REQUEST['mysql_host'])) && (isset($_REQUEST['mysql_user'])) && (isset($_REQUEST['mysql_database'])) &&
		(trim($_REQUEST['mysql_host'])!='') && (trim($_REQUEST['mysql_user'])!='') && (trim($_REQUEST['mysql_database'])!=''))
	{
		$mysql_host=$_REQUEST['mysql_host'];
		$mysql_user=$_REQUEST['mysql_user'];
		$mysql_password=$_REQUEST['mysql_password'];
		$mysql_database=$_REQUEST['mysql_database'];
		$sql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
		if ($sql) {
			$db=mysql_select_db($mysql_database);
			if ($db){
				$content.='<div class="alert alert-success">Подключение прошло успешно!</div>';
				$httphost=$_SERVER['HTTP_HOST'];
				$docroot=realpath(dirname(__FILE__));
				$randa=rand_str();
				$randb=rand_str();
				$unqid=md5(time().$httphost);
$settings_file=<<<RDSETTINGS
<?
\$mysql_host = '$mysql_host';
\$mysql_database = '$mysql_database';
\$mysql_user = '$mysql_user';
\$mysql_password = '$mysql_password';

\$_cookie_login='rd_lg';
\$_cookie_passw='rd_pw';

\$static_path='http://$httphost/static';

\$_home_dir='$docroot';

\$_preview_image=array(350,200);
\$_watermark_text=array('$httphost');
\$_uploader_path='http://$httphost/uploader/';

\$_recache_url='';

\$_enable_debug=false;

\$modules = array(
					'news' => 'modules/engine/news.php',
					'home' => 'modules/engine/news.php',
					'categories' => 'modules/engine/categories.php',
					'add' => 'modules/news/add.php',
					'submit' => 'modules/news/submit.php',
					'del_news' => 'modules/news/delete.php',
					'log' => 'modules/engine/log.php',
					'redir' => 'modules/engine/redir.php',
					'fulltext' => 'modules/news/fulltext.php',
					'comment' => 'modules/news/comment.php',
					'comments' => 'modules/news/comslist.php',
					'auth' => 'modules/engine/auth.php',
					'users' => 'modules/engine/users.php',
					'admin' => 'modules/engine/admin.php',
					'reg' => 'modules/engine/reg.php',
					'rss' => 'modules/engine/rss.php',
					'support' => 'modules/engine/techsupport.php',
					'ip' => 'modules/engine/ip.php',
					'redirect' => 'modules/engine/redirector.php',
					'complete' => 'modules/news/autocomplete.php'
			);
				

\$salt_a='$randa';
\$salt_b='$randb';
			
\$gr_loc=array(
	'admin'=>'Администратор',
	'moder'=>'Модератор',
	'user'=>'Пользователь'
);

define('UNIQID','$unqid');
?>
RDSETTINGS;
				$settfile=$docroot.'/include/settings.php';
				if (file_put_contents($settfile,$settings_file)){
					$content.='<div class="alert alert-success">Файл настроек успешно сохранён!<br>Путь к файлу: <code>'. $settfile .'</code></div>';
				} else {
					$content.='<div class="alert">Файл настроек не может быть сохранён!<br>Скопируйте текст и запишите его в файл: <code>'. $settfile .'</code></div>';
					$content.='<h4>Сгенерированный файл settings.php</h4><pre>'. htmlspecialchars($settings_file) .'</pre>';
				}
				$content.='<div class="form-actions"><a class="btn btn-primary" href="?step=4&uniqid='. $unqid .'">Далее <i class="icon-white icon-arrow-right"></i></a></div>';
			} else {
				$content.='<div class="alert alert-error">Произошла ошибка при выборе базы данных! Проверьте, существует ли выбранная база "'. $mysql_database .'".</div><div class="form-actions"><a class="btn btn-primary" href="?step=2"><i class="icon-white icon-arrow-left"></i>Назад</a></div>';
			}
			mysql_close();
		} else {
			$content.='<div class="alert alert-error">Произошла ошибка при подключении к базе данных!</div><div class="form-actions"><a class="btn btn-primary" href="?step=2"><i class="icon-white icon-arrow-left"></i>Назад</a></div>';
		}
	} else {
		$content.='<div class="alert alert-error">Обязательные поля не заполнены!</div><div class="form-actions"><a class="btn btn-primary" href="?step=2"><i class="icon-white icon-arrow-left"></i>Назад</a></div>';
	}
} else if ($step==4){
	if ((@include realpath(dirname(__FILE__)).'/include/settings.php') == 1){
		$uniqid=(isset($_REQUEST['uniqid'])) ? htmlspecialchars($_REQUEST['uniqid']) : '';
		if (UNIQID == $uniqid){
			$httphost=$_SERVER['HTTP_HOST'];
			$content.="<h3>Расширенные настройки</h3>
			<form method='post' class='form-horizontal'><input type='hidden' name='step' value='5'/>
				<p>Настройки приложения В Контакте.<br>Для создания приложения, перейдите по ссылке: <a href='https://vk.com/editapp?act=create' class='btn btn-small' target='_blank'>Создать приложение</a><br>Укажите следующие параметры при создании:
					<ul>
						<li><b>Название: </b>любое</li>
						<li><b>Тип: </b>Веб-сайт</li>
						<li><b>Адрес сайта: </b>http://$httphost</li>
						<li><b>Базовый домен: </b>$httphost</li>
					</ul>
					Затем, в разделе \"Настройки\", получите \"ID приложения\" и \"Секретный ключ\" и запииште их в поля ниже.
				</p>
				<div class='control-group'>
					<label class='control-label' for='inputVkid'>ID приложения</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputVkid' name='vk_api_id' type='text' required>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputSecret'>Секретный ключ</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputSecret' name='vk_api_secret' type='text' required>
					</div>
				</div>
				<p>Введите данные главного администратора</p>
				<div class='control-group'>
					<label class='control-label' for='inputLogin'>Логин</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputLogin' name='admin_login' type='text' required>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputPass'>Пароль</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputPass' name='admin_password' type='password' required>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputMail'>E-mail</label>
					<div class='controls'>
						<input class='input-xlarge' id='inputMail' name='admin_mail' type='text' required>
					</div>
				</div>
				<div class='form-actions'>
					<input type='submit' value='Далее' class='btn btn-primary'>
				</div>
			</form>";
		} else {
			$uniqida=(isset($_REQUEST['uniqid'])) ? htmlspecialchars($_REQUEST['uniqid']) : '';
			$content.='<div class="alert alert-error">Файл <code>settings.php</code>, содержит неверные данные! Проверьте, сохранили ли Вы новый файл.</div><div class="form-actions"><a class="btn" href="?step=2"><i class="icon-arrow-left"></i>Назад</a><a class="btn btn-primary" href="?step=4&uniqid='. $uniqida .'"><i class="icon-white icon-refresh"></i>Обновить</a></div>';
		}
	} else {
		$content.='<div class="alert alert-error">Файл <code>settings.php</code>, сгенерированный на предыдущем шаге, не найден!</div><div class="form-actions"><a class="btn btn-primary" href="?step=2"><i class="icon-white icon-arrow-left"></i>Назад</a></div>';
	}
} else if ($step==5){
@include realpath(dirname(__FILE__)).'/include/settings.php';
$vk_api_id=htmlspecialchars($_REQUEST['vk_api_id']);
$vk_api_secret=htmlspecialchars($_REQUEST['vk_api_secret']);
$admin_mail=htmlspecialchars($_REQUEST['admin_mail']);
$adm_md_mail=md5($admin_mail);
$admin_login=htmlspecialchars($_REQUEST['admin_login']);
$adm_md_pass=md5($salt_a . htmlspecialchars($_REQUEST['admin_password']) . $salt_b);

$sql_dump=<<<RDSQL
-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--

--
-- RD CMS 0.50
--
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rdcms`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE IF NOT EXISTS `Categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `Stylesheet` varchar(2048) NOT NULL,
  `HideMain` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`ID`, `Title`, `Stylesheet`, `HideMain`) VALUES
(0, 'Новости на главной', '', 0),
(1, 'Черновики', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE IF NOT EXISTS `Comments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Text` varchar(1024) NOT NULL,
  `Author` varchar(128) NOT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Category` varchar(32) NOT NULL,
  `News_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- RELATIONS FOR TABLE `Comments`:
--   `Author`
--       `Users` -> `Auth_ID`
--   `News_ID`
--       `News` -> `Num`
--

-- --------------------------------------------------------

--
-- Table structure for table `Logger`
--

CREATE TABLE IF NOT EXISTS `Logger` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IP` varchar(19) NOT NULL,
  `VK_ID` varchar(128) NOT NULL,
  `SID` varchar(32) NOT NULL,
  `UserAgent` varchar(255) NOT NULL,
  `Path` varchar(255) NOT NULL,
  `Referer` varchar(1000) NOT NULL,
  `Type` varchar(4) NOT NULL DEFAULT 'GET',
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- RELATIONS FOR TABLE `Logger`:
--   `VK_ID`
--       `Users` -> `Auth_ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `MainMenu`
--

CREATE TABLE IF NOT EXISTS `MainMenu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `URL` varchar(1024) NOT NULL,
  `Position` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `MainMenu`
--

INSERT INTO `MainMenu` (`ID`, `Title`, `URL`, `Position`) VALUES
(1, 'Главная', '?act=news', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `News`
--

CREATE TABLE IF NOT EXISTS `News` (
  `Num` int(11) NOT NULL AUTO_INCREMENT,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Title` varchar(255) DEFAULT NULL,
  `Text` text NOT NULL,
  `Category` int(11) NOT NULL DEFAULT '0',
  `Tags` varchar(1000) DEFAULT NULL,
  `Author` varchar(255) NOT NULL DEFAULT '0',
  `Preview` varchar(1000) NOT NULL,
  `Fixed` tinyint(1) NOT NULL DEFAULT '0',
  `Views` int(11) NOT NULL DEFAULT '0',
  `NoComments` tinyint(1) NOT NULL DEFAULT '0',
  `OnlyPage` tinyint(1) NOT NULL DEFAULT '0',
  `ReplaceRN` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Num`),
  FULLTEXT KEY `Search` (`Title`,`Text`,`Tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- RELATIONS FOR TABLE `News`:
--   `Author`
--       `Users` -> `Auth_ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `Redirects`
--

CREATE TABLE IF NOT EXISTS `Redirects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(1024) DEFAULT NULL,
  `URL` varchar(1024) DEFAULT NULL,
  `Pass` varchar(15) DEFAULT NULL,
  `Visits` int(11) NOT NULL DEFAULT '0',
  `OneTime` tinyint(1) NOT NULL DEFAULT '0',
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `Settings`
--

CREATE TABLE IF NOT EXISTS `Settings` (
  `Setting` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Parameter` longtext NOT NULL,
  UNIQUE KEY `Setting` (`Setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Settings`
--

INSERT INTO `Settings` (`Setting`, `Parameter`) VALUES
('Site_Active', '1'),
('News_at_home', '8'),
('Lines_at_log', '50'),
('VK_API_ID', '$vk_api_id'),
('VK_API_SECRET', '$vk_api_secret'),
('Advert_On_Main', '$'),
('Time_Offset', '0'),
('News_Short_Symbols', '250'),
('Tag_Cloud_Count', '5'),
('Tag_Cloud_Params', '1'),
('Site_Welcome', '<h1>Добро пожаловать!</h1>\r\n<p>Благодарим Вас за выбор RD CMS! Изменить этот текст можно в "Настройках".</p>'),
('FB_Admins', ''),
('News_Sort', '0'),
('Site_Banned', ''),
('VK_Share_Title', 'New Blog'),
('VK_Share_Wall', ''),
('VK_Share_Attach', ''),
('News_Similar', '0'),
('RSS_Link', ''),
('Google_Code', ''),
('Ya_Code', ''),
('Site_Name', 'New Blog'),
('Site_Copyright', 'Copyright © %year%'),
('Google_Analytics', ''),
('Articles_Sort', 'ORDER BY `Views` DESC'),
('Articles_Title', 'Популярное'),
('Articles_Count', '10'),
('News_UFU', '2'),
('Site_AJAX', '1'),
('Stats_On_Main', '$'),
('Coms_Sort', '2'),
('ComsList_Count', '5'),
('Site_Logger', '1'),
('Font_Family', 'ptsans'),
('Font_Size', '10'),
('Site_Active_Reason', 'К сожалению, сайт временно отключен администратором сайта.<br>Идет обновление дизайна и функционала.<br>Заходите позже!\r\n<style>#sidebar{display:none !important;}\r\n#mainwrapper{border-left:0 !important;}</style>'),
('Site_Swatch', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `TechSupp`
--

CREATE TABLE IF NOT EXISTS `TechSupp` (
  `ID` int(9) NOT NULL AUTO_INCREMENT,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Status` int(3) NOT NULL,
  `Nick` varchar(32) NOT NULL,
  `Skype` varchar(32) NOT NULL,
  `VK` varchar(32) NOT NULL,
  `MSG` varchar(512) NOT NULL,
  `IP` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `Auth` varchar(128) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- RELATIONS FOR TABLE `TechSupp`:
--   `Auth`
--       `Users` -> `Auth_ID`
--

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Auth_ID` varchar(128) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Nick` varchar(128) NOT NULL,
  `Photo` varchar(1024) NOT NULL DEFAULT '/static/images/noimage.png',
  `Group` varchar(32) NOT NULL DEFAULT 'user',
  `Password` varchar(32) NOT NULL,
  `Mail` varchar(255) NOT NULL,
  `Last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `Auth_ID` (`Auth_ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`ID`, `Auth_ID`, `Name`, `Nick`, `Photo`, `Group`, `Password`, `Mail`) VALUES
(1, '$admin_login', 'Администратор', '$admin_login', 'http://www.gravatar.com/avatar/$adm_md_mail?d=identicon&s=150', 'admin', '$adm_md_pass', '$admin_mail');

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

RDSQL;

$sql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if ($sql) {
	$db=mysql_select_db($mysql_database);
	if ($db){
		$sql=mysql_query($sql_dump);
		if ($sql){
			$content.='<div class="alert alert-success">Процесс установки и настройки успешно завершён.</div>';
			if (!file_put_contents(realpath(dirname(__FILE__)).'/include/install.lock',md5(time()))){
				$content.='<div class="alert">Переименуйте или удалите файл установщика <code>install.php</code></div>';
			}
			$content.='<div class="form-actions"><a class="btn btn-primary" href="index.php"><i class="icon-white icon-arrow-left"></i>Перейти к сайту</a></div>';
		} else {
			$content.='<div class="alert">Запрос не может быть выполнен!<br>Скопируйте текст и выполните импорт через интерфейс базы данных.</div>';
			$content.='<h4>Сгенерированный запрос</h4><pre>'. htmlspecialchars($sql_dump) .'</pre>';
		}
	} else {
		$content.='<div class="alert">Ошибка при выборе базы данных!</div>';
	}
} else {
	$content.='<div class="alert">Ошибка при выборе подключении к базе данных!</div>';
}


} else {
	header('Location: ?step=1');
}
?>
<html>
	<head>
		<title>RD CMS - Установка</title>
		<meta charset='utf-8'/>
		<link href='static/css/bootstrap.min.css' rel='stylesheet'/>
		<link href='static/css/bootstrap-responsive.min.css' rel='stylesheet'/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&amp;subset=latin,cyrillic' rel='stylesheet'>
		<style>
			*{font-family:'Ubuntu',sans-serif !important;}
			body{font-size:11pt;}
			.main-container{margin-top:50px}
		</style>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class='container'>
					<a class="brand" href="/">RD CMS</a>
				</div>
			</div>
		</div>
		<div class='container main-container'>
			<div class='row'>
				<div class='span3'>
					<div class='well well-small'>
						<ul class='nav nav-pills nav-stacked'>
							<li class='nav-header'>Этапы</li>
							<li<?php echo ($step==1) ? ' class="active"' : ''; ?>><a href='?step=1'>Подготовка</a></li>
							<li<?php echo (($step==2) || ($step==3)) ? ' class="active"' : ''; ?>><a href='#'>Основные настройки</a></li>
							<li<?php echo ($step==4) ? ' class="active"' : ''; ?>><a href='#'>Расширенные настройки</a></li>
							<li<?php echo ($step==5) ? ' class="active"' : ''; ?>><a href='#'>Завершение</a></li>
						</ul>
					</div>
				</div>
				<div class='span9'>
					<div class='well'>
						<?php echo $content; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
ob_end_flush();
?>