<?
ob_start("ob_gzhandler");
require("settings.php");
require("../modules/engine/init.php");
if (!defined('RD')) die('Hacking attempt!');

$name=$_REQUEST['name'];
$pass=$_REQUEST['pass'];
$sql = mysql_query("SELECT * FROM `Redirects` WHERE `Name`='$name' LIMIT 1;");
if (!$sql){ err_and_die(404); }
while ($row = @mysql_fetch_assoc($sql)) {
	$_pass=$row['Pass'];
	$_url=$row['URL'];
	$_visits=$row['Visits'];
	$_onetime=$row['OneTime'];
	$_visits1=$_visits+1;
	
	$_ot_allow=($_onetime==0) ? 1 : ($_visits==0) ? 1 : 0;
	
	if (((trim($_pass=='')) || ($pass==$_pass)) && $_ot_allow==1){
		# password ok or not set
		$sql=mysql_query("UPDATE `Redirects` SET `Visits`='$_visits1' WHERE `Name`='$name';");
		if ($sql){
			$frame="<iframe id='frame' src='$_url' frameborder='0'></iframe>";
			$link=$_url;
		} else {
			err_and_die(999);
		}
	} else if ($_ot_allow==0){
		$e_desc="<h3>Одноразовая ссылка</h3>";
		$frame="<div id='frame'>Данная ссылка является одноразовой и по ней уже был совершён переход, поэтому доступ запрещён.</div>";
	} else {
		# wrong password
		if (!isset($_REQUEST['pass'])){ $e_desc="Для перехода по данной ссылке, необходимо ввести пароль в поле ниже!"; } else { $e_desc="Вы ввели неверный пароль! Повторите попытку.";}
		$frame="<div id='frame'><form method='post'>$e_desc
						<table width='100%'><tr><td style='width:90%'><input type='password' name='pass' style='width:100%; color:#000'></td><td><input type='hidden' name='name' value='$name'><input type='submit' value='Перейти >'></td></tr></table></form></div>";
	}

}

if (!defined('RD')) die('Hacking attempt!'); ?><!DOCTYPE html>
<html>
<head>
	<title>RD's Blog</title>
	<meta name='yandex-verification' content='4ba97f8e9e676240'>
	<meta name="google-site-verification" content="bqA1g09I1_Uih6Ex-16-gXb-pczX-Y4cpTZGhbb5Lw0" />
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=latin,cyrillic' rel='stylesheet'>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>
	<style>
		*{font-family:'Ubuntu',Verdana,Arial,Tahoma;font-size:11pt;}
		a{text-decoration:none;}
		.takru,.takru *{color:#000; text-decoration:underline;font-size:10pt;}
		.takru b{display:none !important;}
		body{font-size:12pt;color:#000;margin:0;padding:0;background:#ddd url('//static.helpcast.ru/images/body_bg.png') repeat scroll 50% 0;}
		#logo_wrapper_mini{background:#121212; width:100%; height:30px; }

		#frame{width: 100%; height: 600px; border: 0; padding: 0; margin: 0; display:block;}
		
		#wrapper {
			width: 100%;
			min-height: 100%;
			height: auto !important;
			height: 100%;
		}
		#header {
			height: 30px;
			background:#121212;
		}
		#content {
			padding: 0 0 50px;
			min-height:100%;
		}
		#footer {
			margin: -50px auto 0;
			height: 50px;
			position: relative;
			background:#999;
			overflow:hidden;
		}
		
		img{border:0}
	</style>
	<!-- Google Analytics -->
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-9210291-4']);
		_gaq.push(['_setDomainName', 'helpcast.ru']);
		_gaq.push(['_trackPageview']);
		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	<!-- /Google Analytics -->
</head>
<body>

<div id="wrapper">

	<div id="header">
		<a href='/' title="RedDragon's Blog: главная страница"><img src='//static.helpcast.ru/images/menu/logo_30px.png' width='335' height='30' alt="RedDragon's Blog"></a>
		<a href='<? echo $link; ?>' style='float:right; padding-right:5px; padding-top:5px;'><img alt="Убрать рамки" src="//static.helpcast.ru/images/icons/cross-circle.png"></a>
	</div>
	
	<div id="content">
		<? echo "$frame"; ?>
	</div>
</div>

<div id="footer">
	<table style='width:100%'>
		<tr>
			<td style='width:100%'>
				<a href='http://tak.ru/partner.php?id=241640' class='takru'><strong>Реклама Tak.ru: </strong></a><script language="JavaScript" charset="UTF-8" src="http://z930.takru.com/in.php?id=931240"></script>
			</td>
			<td>
				<!-- Yandex.Metrika informer --><a href="http://metrika.yandex.ru/stat/?id=7927513&amp;from=informer" target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/7927513/1_1_555555FF_353535FF_1_pageviews" style="width:80px; height:15px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры)" onclick="try{Ya.Metrika.informer({i:this,id:7927513,type:0,lang:'ru'});return false}catch(e){}"/></a><!-- /Yandex.Metrika informer --><!-- Yandex.Metrika counter --><div style="display:none;"><script type="text/javascript">(function(w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter7927513 = new Ya.Metrika({id:7927513, enableAll: true, trackHash:true}); } catch(e) { } }); })(window, "yandex_metrika_callbacks");</script></div><script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script><noscript><div><img src="//mc.yandex.ru/watch/7927513" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
			</td>
		</tr>
	</table>
</div>
<script>
	function rw(){
		if (self.innerHeight){y = self.innerHeight;}
		else if (document.documentElement && document.documentElement.clientHeight){y = document.documentElement.clientHeight;}
		else if (document.body){y = document.body.clientHeight;}
		document.getElementById('frame').style.height=(y-(50+30))+'px';
	}
	window.onresize = function() {
		rw();
	}
	rw();
</script>
</body></html>
<?
mysql_close();
ob_end_flush();
?>