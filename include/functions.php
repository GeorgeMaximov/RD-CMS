<?
function authOpenAPIMember($api_id,$secret_key) {
  $session = array();
  $member = FALSE;
  $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
  $app_cookie = $_COOKIE['vk_app_'.$api_id];
  if ($app_cookie) {
    $session_data = explode ('&', $app_cookie, 10);
    foreach ($session_data as $pair) {
      list($key, $value) = explode('=', $pair, 2);
      if (empty($key) || empty($value) || !in_array($key, $valid_keys)) {
        continue;
      }
      $session[$key] = $value;
    }
    foreach ($valid_keys as $key) {
      if (!isset($session[$key])) return $member;
    }
    ksort($session);

    $sign = '';
    foreach ($session as $key => $value) {
      if ($key != 'sig') {
        $sign .= ($key.'='.$value);
      }
    }
    $sign .= $secret_key;
    $sign = md5($sign);
    if ($session['sig'] == $sign && $session['expire'] > time()) {
      $member = array(
        'id' => intval($session['mid']),
        'secret' => $session['secret'],
        'sid' => $session['sid']
      );
    }
  }
  return $member;
}

function generate_scripts($_home_dir,$query_string,$site_name,$static_path,$rss_link,$ya_code,$google_code,$__cms_ver,$_font_size,$_font_family,$_ajax_enabled,$api_id,$google_an_id,$_site_swatch){
$__fs='body{font-size: '.$_font_size.'pt;}';
switch($_font_family){
	case "ubuntu":$__fontcss="<link href='http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&amp;subset=latin,cyrillic' rel='stylesheet'>\r\n<style>*{font-family:'Ubuntu',sans-serif !important;}$__fs</style>";break;
	case "ptsans":$__fontcss="<link href='http://fonts.googleapis.com/css?family=PT+Sans:regular,bold&amp;subset=latin,cyrillic' rel='stylesheet'>\r\n<style>*{font-family:'PT Sans',sans-serif !important;}$__fs</style>";break;
	case "opensans":$__fontcss="<link href='http://fonts.googleapis.com/css?family=Open+Sans:regular,bold&amp;subset=latin,cyrillic' rel='stylesheet'>\r\n<style>*{font-family:'Open Sans',sans-serif !important;}$__fs</style>";break;
	case "cuprum":$__fontcss="<link href='http://fonts.googleapis.com/css?family=Cuprum:regular,bold&amp;subset=latin,cyrillic' rel='stylesheet'>\r\n<style>*{font-family:'Cuprum',sans-serif !important;}$__fs</style>";break;
	case "istokweb":$__fontcss="<link href='http://fonts.googleapis.com/css?family=Istok+Web:regular,bold&amp;subset=latin,cyrillic' rel='stylesheet'>\r\n<style>*{font-family:'Istok Web',sans-serif !important;}$__fs</style>";break;
	case "scada":$__fontcss="<link href='http://fonts.googleapis.com/css?family=Scada:regular,bold&amp;subset=latin,cyrillic' rel='stylesheet'>\r\n<style>*{font-family:'Scada',sans-serif !important;}$__fs</style>";break;
	case "arial":$__fontcss="<style>*{font-family:'Arial',sans-serif !important;}$__fs</style>";break;
	case "tahoma":$__fontcss="<style>*{font-family:'Tahoma',sans-serif !important;}$__fs</style>";break;
	case "sansserif":;
	default:$__fontcss="<style>*{font-family:sans-serif !important;}$__fs</style>";break;
}
switch($_site_swatch){
	case "amelia":$__swatch='amelia';break;
	case "cosmo":$__swatch='cosmo';break;
	case "cyborg":$__swatch='cyborg';break;
	case "slate":$__swatch='slate';break;
	case "spacelab":$__swatch='spacelab';break;
	case "spruce":$__swatch='spruce';break;
	case "superhero":$__swatch='superhero';break;
	case "united":$__swatch='united';
	default:$__swatch=false;break;
}

$css=($__swatch) ? file_get_contents($_home_dir.'/static/css/swatch/'.$__swatch.'.css') : file_get_contents($_home_dir.'/static/css/bootstrap.css');
$css.=file_get_contents($_home_dir.'/static/css/bootstrap-responsive.css');
$css.=file_get_contents($_home_dir.'/static/css/rdstrap.css');
$css=str_replace("../images/",$static_path."images/",$css);
$css=str_replace("'/images/","'".$static_path."images/",$css);
$css=str_replace("\"/images/",'"'.$static_path."images/",$css);
$css=str_replace("(/images/",'('.$static_path."images/",$css);
$css=str_replace("../img/",$static_path."images/",$css);
$css=str_replace("\r",'',$css);
$css=str_replace("\n",'',$css);
$css=str_replace("\t",'',$css);

$_an_code='';
if (trim($google_an_id)){
	$_an_code="
	<script type='text/javascript'>
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '$google_an_id']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>";
}
$_req_string='#!/'.$query_string;
$__sitename=str_replace("&#39;","'",$site_name);
$engine_scripts=$static_path.'/scripts/engine.min.js';
$jquery='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
$rss_string=(trim($rss_link)=='') ? '' : "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"$rss_link\" title=\"$site_name\"><link rel=\"search\" type=\"application/opensearchdescription+xml\" href=\"$rss_link\" title=\"$site_name\" />";
$scripts=<<<RD
	<title>$__sitename</title>
	<meta name='yandex-verification' content='$ya_code'>
	<meta name="google-site-verification" content="$google_code" />
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="generator" content="RD CMS Ver. $__cms_ver">
	$rss_string
	<link rel="shortcut icon" href="favicon.ico">
	$__fontcss
	<style>
		$css
	</style>
	<script>
		var admin=false;
		var moder=false;
		var user=false;
		var ajax_enabled=$_ajax_enabled;
		var js_vk_api_id='$api_id';
		var js_site_name="$__sitename";
		var static_path='$static_path';
		var hash='$_req_string';
		var qs=location.href;
		var qsp=qs.indexOf('?');
		if (qsp!=-1){
			var query_string=qs.substr(qsp+1);
		} else {
			var query_string=location.hash;
		}
		var alert_id=0;
		var al_int=new Array();
		var news_posted=0;
		
		var debug=false;
	</script>
	<script src="$jquery"></script>
	<script src="$engine_scripts"></script>
	$_an_code
RD;
return $scripts;
}

function get_cats($sql){
	$arr=array();
	if ($sql){
		$arr['*']['title']='Все новости';
		$arr['*']['style']='';
		while ($row = mysql_fetch_assoc($sql)) {
			$id=$row['ID'];
			$title=$row['Title'];
			$css=$row['Stylesheet'];
			$arr[$id]['title']=$title;
			$arr[$id]['style']=$css;
		}
		return $arr;
	} else {
		return false;
	}
}

function err_and_die($code=500){
	switch($code){
		case 301: header('HTTP/1.0 301 Moved Permanently'); break;
		case 302: header('HTTP/1.0 302 Found'); break;
		case 400: header('HTTP/1.0 400 Bad Request'); break;
		case 401: header('HTTP/1.0 401 Unauthorized'); break;
		case 403: header('HTTP/1.0 403 Forbidden'); break;
		case 404: header('HTTP/1.0 404 Not Found'); break;
		case 405: header('HTTP/1.0 405 Method Not Allowed'); break;
		case 405: header('HTTP/1.0 406 Not Acceptable'); break;
		case 408: header('HTTP/1.0 408 Request Timeout'); break;
		case 409: header('HTTP/1.0 409 Conflict'); break;
		case 429: header('HTTP/1.0 429 Too Many Requests'); break;
		case 500: header('HTTP/1.0 500 Internal Server Error'); break;
		case 501: header('HTTP/1.0 501 Not Implemented'); break;
		case 501: header('HTTP/1.0 501 Not Implemented'); break;
	}
	header('Location: /modules/error.php?error='.$code);
	ob_end_flush();
	die();
}

function strip_words($string,$count){
	$result	  = '';
	$counter_plus  = true;
	$counter = 0;
	$string_len = strlen($string);
	for($i=0; $i<$string_len; ++$i){
		$char = $string[$i];
		if($char == '<') $counter_plus = false;
		if($char == '>' and $string[$i+1] != '<'){
			$counter_plus = true;
			$counter--;
		}
		$result .= $char;
		if($counter_plus) $counter++;
		if($counter >= $count) {
			$pos_space = strpos($string, ' ', $i);
			$pos_tag = strpos($string, '<', $i);
			if ($pos_space == false) {
				$pos = strrpos($result, ' ');
				$result = substr($result, 0, strlen($result)-($i-$pos+1));
			} else {
				$pos = min($pos_space, $pos_tag);
				if ($pos != $i) {
					$dop_str = substr($string, $i+1, $pos-$i-1);
					$result .= $dop_str;
				} else {
					$result = substr($result, 0, strlen($result)-1);
				}
			}
			break;
		}
	}
	return $result;
}

function closetags($html){
  preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU",$html,$result);
  $openedtags=$result[1];
 
  preg_match_all("#</([a-z]+)>#iU",$html,$result);
  $closedtags=$result[1];
  $len_opened = count($openedtags);
 
  if(count($closedtags) == $len_opened){
	 return $html;
  }
  $openedtags = array_reverse($openedtags);
 
  for($i=0;$i<$len_opened;$i++) {
	 if (!in_array($openedtags[$i],$closedtags)){
		$html .= '</'.$openedtags[$i].'>';
	 } else {
		unset($closedtags[array_search($openedtags[$i],$closedtags)]);
	 }
  }
  return $html;
}

function html_cut($html, $size) {
	$html = iconv('UTF-8', 'cp1251', $html);
	$html = str_replace(" ", ' ', $html);
	$symbols = strip_tags($html);
	$symbols_len = strlen($symbols);
	if($symbols_len > $size) {
		$strip_text = strip_words($html,$size);
		$strip_text = $strip_text."...";
		$strip_text = closetags($strip_text);
	} else $strip_text = $html;
	$final_text = iconv('cp1251', 'UTF-8', $strip_text);
	return $final_text;
}

function text_cut($html, $size) {
	$html = iconv('UTF-8', 'cp1251', $html);
	$html = str_replace(" ", ' ', $html);
	$symbols = strip_tags($html);
	$symbols_len = strlen($symbols);
	if($symbols_len > $size) {
		$strip_text = substr($symbols,0,$size);
		$strip_text = $strip_text."...";
	} else $strip_text = $symbols;
	$final_text = iconv('cp1251', 'UTF-8', $strip_text);
	return $final_text;
}

function enc_cut($text,$size){
	$text = iconv('UTF-8', 'cp1251', $text);
	$text = substr($text,0,$size);
	$final_text = iconv('cp1251', 'UTF-8', $text);
	return $final_text;
}

function SpiderDetect($user_agent,$ip){
	global $static_path;
	$engines = array( 
		array('Aport',	'<img src="http://www.aport.ru/favicon.ico" height="16" title="Aport">'), 
		array('Mail.RU',	'<img src="http://img.mail.ru/r/favicon.ico" height="16" title="Mail.ru">'), 
		array('Google',	'<img src="http://www.google.ru/favicon.ico" height="16" title="Google">'), 
		array('msnbot',	'<img src="http://www.msn.com/favicon.ico" height="16" title="MSN">'), 
		array('Rambler',	'<img src="http://www.rambler.ru/favicon.ico" height="16" title="Rambler">'), 
		array('Yahoo',	'<img src="http://www.yahoo.com/favicon.ico" height="16" title="Yahoo!">'), 
		array('AbachoBOT', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="AbachoBOT">'), 
		array('accoona', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Accoona">'), 
		array('AcoiRobot', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="AcoiRobot">'), 
		array('naver.com', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Yeti / Naverbot">'), 
		array('ASPSeek', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="ASPSeek">'), 
		array('CrocCrawler', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="CrocCrawler">'), 
		array('Dumbot', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Dumbot">'), 
		array('FAST-WebCrawler', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="FAST-WebCrawler">'), 
		array('GeonaBot', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="GeonaBot">'), 
		array('Gigabot', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Gigabot">'), 
		array('Lycos', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Lycos spider">'), 
		array('MSRBOT', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="MSRBOT">'), 
		array('Scooter', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Altavista robot">'), 
		array('AltaVista', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Altavista robot">'), 
		array('WebAlta', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="WebAlta">'), 
		array('IDBot', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="ID-Search Bot">'), 
		array('eStyle', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="eStyle Bot">'), 
		array('MJ12bot', '<img src="http://www.majestic12.co.uk/favicon.ico" height="16" title="MJ12bot - Majestic-12 DSearch">'), 
		array('bingbot', '<img src="http://bing.com/favicon.ico" height="16" title="Bing Bot">'), 
		array('Scrubby', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Scrubby robot">'), 
		array('EC2LinkFinder', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="EC2LinkFinder">'), 
		array('Twitterbot', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Twitterbot">'),
		array('Synapse', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Synapse">'),
		array('Ezooms', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="Ezooms">'),
		array('WBSearchBot', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="WareBay">'),
		array('DigExt', '<img src="'.$static_path.'images/icons/bot.png" height="16" title="IE\'s \'Make available offline\' crawler, probably IE5.0">'), 
		array('Yandex', '<img src="http://www.yandex.ru/favicon.ico" height="16" title="Яндекс">'), 
		array('YaDirectBot', '<img src="http://direct.yandex.ru/favicon.ico" height="16" title="Яндекс.Директ">') ,
		array('W3C_Validator', '<img src="http://www.w3.org/2008/site/images/favicon.ico" height="16" title="W3C Validator">'), 
		array('Slurp', '<img src="http://www.yahoo.com/favicon.ico" height="16" title="Yahoo! Slurp">'),
		array('Baiduspider', '<img src="http://www.baidu.com/favicon.ico" height="16" title="Baidu">'),
		array('crawler@nigma.ru', '<img src="http://nigma.ru/favicon.ico" height="16" title="Nigma">'),
		array('stat.cctld.ru', '<img src="http://xn--80aaghfpaqulid2acmbc.xn--p1ai/favicon.ico" height="16" title="Статистика доменов">'),
		array('CRON', '<img src="http://php.net/favicon.ico" height="16" title="cURL">'),
		array('AhrefsBot', '<img src="http://ahrefs.com/favicon.ico" height="16" title="AhrefsBot">'),
		array('Monitorus', '<img src="http://monitorus.ru/favicon.ico" height="16" title="Monitorus">'),
		array('fastbot', '<img src="http://www.fastbot.de/favicon.ico" height="16" title="fastbot>>">'),
		array('facebookexternalhit', '<img src="http://facebook.com/favicon.ico" height="16" title="Facebook (facebookexternalhit)">'),
		array('Netvibes', '<img src="http://www.netvibes.com/favicon.ico" height="16" title="Netvibes RSS Fetcher">'),
		array('ia_archiver', '<img src="http://www.alexa.com/favicon.ico" height="16" title="Alexa - ia_archiever">'),
		array('Butterfly', '<img src="http://topsylabs.com/favicon.ico" height="16" title="Topsy Labs - Butterfly">'),
		array('UnwindFetchor', '<img src="http://gnip.com/favicon.ico" height="16" title="UnwindFetchor">'),
		array('TweetmemeBot', '<img src="http://tweetmeme.com/favicon.ico" height="16" title="Tweetmeme">'),
		array('StumbleUpon', '<img src="http://www.stumbleupon.com/favicon.ico" height="16" title="StumbleUpon">'),
		array('NetcraftSurveyAgent', '<img src="http://news.netcraft.com/favicon.ico" height="16" title="NetcraftSurveyAgent">'),
		array('Site-Shot', '<img src="http://www.site-shot.com/favicon.ico" height="16" title="Site-Shot.com Screenshot Bot">'),
		array('cURL', '<img src="http://php.net/favicon.ico" height="16" title="cURL">')
	); 

	foreach ($engines as $engine) {
		if (strstr(strtolower($user_agent), strtolower($engine[0]))) {
			return($engine[1]); 
		}
	}
	return '<img src="'.$static_path.'images/icons/user.png" height="16">';
}

function unparse_url($parsed_url) {
	$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
	$pass     = ($user || $pass) ? "$pass@" : '';
	$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
	return "$scheme$user$pass$host$port$path$query$fragment";
}

function is_link_outside($url){
	$pto=parse_url($url);
	$host=$pto['host'];
	$path=$pto['path'];
	$quer=$pto['query'];
	$frag=$pto['fragment'];
	if ((strpos($host,$_SERVER['HTTP_HOST'])!==FALSE) && (strpos($host,$_SERVER['HTTP_HOST'])==0) && ($path=="/" || $path=="/index.php") || ($host===NULL)){
		if (trim($quer)!=""){
			$to=str_replace("index.php","",$quer);
			$to=str_replace("?","",$to);
		} else {
			$to=str_replace("!/","",$frag);
		}
		return 0;
	} else {
		return 1;
	}
}

function generate_mainmenu(){
	$menu='<ul class="nav nav-tabs nav-stacked rd-nav-tabs">';
	$sql=mysql_query("SELECT `Title`,`URL` from `MainMenu` ORDER BY `Position`,`ID` ASC;");
	while ($row = mysql_fetch_assoc($sql)) {
		$titl=$row['Title'];
		$url=$row['URL'];
		$eurl=urlencode($url);
		$menu.=(is_link_outside($url)) ? "<li><a class='rdajax' href='?act=redir&to=$eurl'>$titl</a></li>" : "<li><a class='rdajax' href='$url'>$titl</a></li>";
	}
	$menu.='</ul>';
	return $menu;
}

function generate_tags($tags_count,$tags_params){
	$tag_cloud='';
	if (($tags_count>0) and ($tags_params>0)){
		$sql=mysql_query("SELECT `Tags` from `News` ORDER BY `Num` DESC;");
		while ($row = mysql_fetch_assoc($sql)) {
			$tag=$row['Tags'];
			if ($tag!==NULL){
				$tags.=", ".strtolower($row['Tags']);
			}
		}
		$tags = preg_replace('/\s[\s]+/','',$tags);
		$tags = str_replace(", ",",",$tags);
		$tags = preg_replace('/,[,]+/','',$tags);
		$tags = explode(",",$tags);
		unset($tags[0]);

		$cloud=array_count_values($tags);
		if ($tags_params==2){
			arsort($cloud);
		}
		$count_of_tags=0;

		foreach ($cloud as $name=>$count) {
			$count_of_tags++;
			$cloud_n[$name]=$count;
			if ($count_of_tags>=$tags_count){ break; }
		}
		
		foreach ($cloud_n as $name=>$count) {
			$size=95+$count*5;
			if ($size>=200){
				$size='200%';
			} else {
				$size=$size.'%';
			}
			$tag_cloud.="<li><a href='?act=news&amp;tag=$name' class='rdajax' style='font-size:$size'>$name</a></li>";
		}
	}
	return $tag_cloud;
}

function parse_tags($tags){
		$tags = preg_replace('/\s[\s]+/','',$tags);
		$tags = str_replace(", ",",",$tags);
		$tags = explode(",",$tags);
		$count = count($tags);
		$i=0;
		foreach($tags as $tag){
			$tag_cloud.="<a href='?act=news&amp;tag=$tag' class='rdajax'>$tag</a>";
			$i++;
			if ($i<$count){ $tag_cloud.=", "; }
		}
		return $tag_cloud;
}

function translit($txt)
{
	$txt = preg_replace('/Ц(Е|е|Ё|ё|И|и|Й|й|Ы|ы|Э|э|Ю|ю|Я|я)/', 'C$1', $txt);
	$txt = preg_replace('/ц(е|Е|ё|Ё|и|И|й|Й|ы|Ы|э|Э|ю|Ю|я|Я)/', 'c$1', $txt);
	$cyrillic = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я",
					  "а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я");
	$translit = array("A","B","V","G","D","E","YO","ZH","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","X","CZ","CH","SH","SHH","``","Y`","`","E`","YU","YA",
					  "a","b","v","g","d","e","yo","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","x","cz","ch","sh","shh","``","y`","`","e`","yu","ya");
    return $txt = str_replace($cyrillic, $translit, $txt);
}


function prepare_ufu($name) 
{
    $name = trim($name);
    $name = mb_strtolower($name,'UTF-8');
    $name = translit($name);
	$name = htmlspecialchars_decode($name, ENT_QUOTES);
    $name = preg_replace('~[`’!;$\.,":*^%#@\[\]&{}\(\)«»„“”]+~s','',$name);
	$name = preg_replace('~(—|&ndash;|&mdash;)~s', '', $name);
    $name = preg_replace('~(\\+|\/)+~s','-',$name);
	$name = preg_replace('~[^a-z_0-9]~','-',$name);
    $name = preg_replace('~(\s+|-)+~s','-',$name);
	return $name;
}

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
?>