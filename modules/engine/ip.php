<?
function getBrowser($u_agent){
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {}

    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}
$xml = simplexml_load_file('http://api.ipinfodb.com/v2/ip_query.php?key=0&ip=' . $ip_1 . '&timezone=false');
/* get your own key at ipinfodb.com */
$title="Ваш IP";
$ip_ccode=strtolower($xml->CountryCode);
$ip_country=$xml->CountryName;
$ip_city=$xml->City;
$ip_lat=$xml->Latitude;
$ip_lon=$xml->Longitude;
if (trim($ip_country)==''){ $ip_country='Не определена'; }
if (trim($ip_city)==''){ $ip_city='Не определен'; }

$host=(intval($ip_1)>0) ? gethostbyaddr($ip_1) : $ip_1;
$ua_info=getBrowser($ua_1);
$browser=$ua_info['name'] . ' ' . $ua_info['version'];
$content="
	<link rel='stylesheet' href='//static.helpcast.ru/css/flags.css'>
	<div class='well well-small'>
		<dl class='dl-horizontal'>
			<dt>IP</dt>
			<dd><img src='//static.helpcast.ru/images/blank.gif' class='flag flag-$ip_ccode' alt='$ip_country' /> $ip_1</dd>
			<dt>Хост</dt>
			<dd>$host</dd>
			<dt>Страна</dt>
			<dd>$ip_country</dd>
			<dt>Город</dt>
			<dd>$ip_city</dd>
			<dt>Браузер</dt>
			<dd>$browser</dd>
		</dl>
	</div>
	
	<div class='well well-small'>
			<iframe src='http://www.informationfreeway.org/?lat=$ip_lat&lon=$ip_lon&zoom=11&layers=00F000B0' width='100%' height='500px' style='border:0'></iframe>
	</div>
	";
?>
