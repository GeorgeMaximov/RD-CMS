<?
if ($_enable_debug){
	error_reporting(E_ALL ^ E_NOTICE);
} else {
	error_reporting(0);
}
@session_start();
$time_start = microtime(true);
require($_home_dir.'/include/update.php');
require($_home_dir.'/include/vk.php');
require($_home_dir.'/include/functions.php');
if (!defined('RD'))	err_and_die(500);
$ip_1=($_SERVER['REMOTE_ADDR']==$_SERVER['SERVER_ADDR'])?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['REMOTE_ADDR'];
$ua_1=$_SERVER['HTTP_USER_AGENT'];
$tp_1=$_SERVER['REQUEST_METHOD'];
if ($_POST) {
	$kv = array();
	foreach ($_POST as $key => $value) {
		$kv[] = "$key=$value";
	}
	$query_string = '?'.join("&", $kv);
}
$ur_1='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$query_string;
$rf_1=$_SERVER['HTTP_REFERER'];
if ($ip_1==''){
	$ip_1='localhost';
}
if ($ua_1==''){
	$ua_1='N/A [Probably CRON]';
}
$sd_1=session_id();

if (!mysql_connect($mysql_host,$mysql_user,$mysql_password)) err_and_die(999);
mysql_select_db($mysql_database);
mysql_set_charset('utf8');

$sql=mysql_query("SELECT * FROM `Settings`");
$params_count=37;
$p_cnt=0;
while ($row = mysql_fetch_assoc($sql)){
	if ($row['Setting']=='Site_Active'){ $_site_active=$row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='News_at_home'){ $news_at_home=$row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Lines_at_log'){ $lines_at_log=$row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='VK_API_ID'){ $api_id = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='VK_API_SECRET'){ $secret_key = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Advert_On_Main'){ $ads_main = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Stats_On_Main'){ $stats_main = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Time_Offset'){ $offset_t = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='News_Short_Symbols'){ $short_sym = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Tag_Cloud_Count'){ $tags_count = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Tag_Cloud_Params'){ $tags_params = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_Welcome'){ $welcome_msg = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='FB_Admins'){ $fb_admins = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='News_Sort'){ $news_sort = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_Banned'){ $banned = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='VK_Share_Title'){ $vk_share_title = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='VK_Share_Wall'){ $vk_share_wall = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='VK_Share_Attach'){ $vk_share_attach = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='News_Similar'){ $news_similar = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='RSS_Link'){ $rss_link = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Google_Code'){ $google_code = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Ya_Code'){ $ya_code = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_Name'){ $site_name = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_Copyright'){ $site_copy = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Google_Analytics'){ $google_an_id = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Articles_Sort'){ $articles_sort = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Coms_Sort'){ $coms_sort = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Articles_Title'){ $articles_title = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Articles_Count'){ $articles_count = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='News_UFU'){ $enable_ufu = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_AJAX'){ $enable_ajax = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='ComsList_Count'){ $com_show_cnt = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_Logger'){ $_logger_enabled = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Font_Family'){ $_font_family = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Font_Size'){ $_font_size = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_Active_Reason'){ $_disable_reason = $row['Parameter']; $p_cnt+=1;}
	if ($row['Setting']=='Site_Swatch'){ $_site_swatch = $row['Parameter']; $p_cnt+=1;}
}

if ($p_cnt!=$params_count) err_and_die(999);

$_banned=explode(',',$banned);

$errs=array(0=>"У Вас нет прав доступа к данной команде!");

$_copyright=$__copy_add . str_replace("%year%",date('Y'),$site_copy);

$title="";
$content="";
$_pass_info="";
$_pass_span="";
$articles_inner="";

if ($enable_ajax==1){
	$_ajax_enabled="true";
} else {
	$_ajax_enabled="false";
}

switch($news_sort){
	case "0": $_news_sorting="ORDER BY `Fixed` DESC,`Num` DESC"; break;
	case "1": $_news_sorting="ORDER BY `Fixed` Desc,`Num` ASC"; break;
	case "2": $_news_sorting="ORDER BY `Fixed` Desc,`Time` DESC"; break;
	case "3": $_news_sorting="ORDER BY `Fixed` Desc,`Time` ASC"; break;
	default: $_news_sorting="ORDER BY `Fixed` Desc,`Num` DESC"; break;
}
$sort_methods=array(
	0=>"По ID (от новых к старым)",
	1=>"По ID (от старых к новым)",
	2=>"По времени обновления (от новых к старым)",
	3=>"По времени обновления (от старых к новым)"
);

if (!defined('Only_Settings')){
	$member = authOpenAPIMember($api_id,$secret_key);
	$user_id=$member['id'];

	if ((isset($_COOKIE[$_cookie_login])) && (isset($_COOKIE[$_cookie_passw]))){
		$user_id=$_COOKIE[$_cookie_login];
		$user_pw=$_COOKIE[$_cookie_passw];
		$user_group=null;
		$sql=mysql_query("SELECT COUNT(*) from `Users` WHERE `Auth_ID`= '$user_id'");
		$row = mysql_fetch_assoc($sql);
		$total=$row['COUNT(*)'];
		if ($total==0){
			# wrong login
		} else {
			$sql=mysql_query("SELECT * FROM `Users` WHERE `Auth_ID`='$user_id' LIMIT 1");
			$row = mysql_fetch_assoc($sql);
			$u_pass=$row["Password"];
			if ($u_pass!=$user_pw){
				# wrong pass
			} else {
				$u_id=$row['Auth_ID'];
				$u_name=$row["Name"];
				$u_nick=$row["Nick"];
				$u_photo=$row["Photo"];
				$u_group=$row["Group"];
				$sql=mysql_query("UPDATE `Users` SET `Last`=CURRENT_TIMESTAMP WHERE `Auth_ID`='$u_id' LIMIT 1 ;");
			}
		}
	}
	if ($user_id!==NULL){
		$sql=mysql_query("SELECT COUNT(*) from `Users` WHERE `Auth_ID`= '$user_id'");
		$row = mysql_fetch_assoc($sql);
		$total=$row['COUNT(*)'];
		$u_group=null;
		if ($total==0){
			$VK = new vkapi($api_id, $secret_key);
			$resp = $VK->api('users.get', array('uids'=>"$user_id",'fields'=>"first_name,last_name,nickname,photo,photo_medium"));
			$u_id=$resp["response"][0]["uid"];
			$u_name=$resp["response"][0]["first_name"]." ".$resp["response"][0]["last_name"];
			$u_nick=$resp["response"][0]["nickname"];
			$u_photo=$resp["response"][0]["photo_medium"];
			$u_group='user';
			$u_pass_dec=crc32("vK-oAuTH|id=$u_id");
			$u_pass=md5($salt_a.$u_pass_dec.$salt_b);
			$_pass_info="<div class='alert alert-info'><b>Внимание!</b><br>Ваш логин: <b>$u_id</b><br>Ваш пароль: <b>$u_pass_dec</b>. Сохраните эти данные!</div>";
			$_pass_span="special_notify(\"<div class='alert alert-info'><b>Внимание!</b><br>Ваш логин: <b>$u_id</b><br>Ваш пароль: <b>$u_pass_dec</b>. Сохраните эти данные!</div>\");";
			if (!empty($u_id)){
				$sql=mysql_query("INSERT INTO `Users` (`Auth_ID`, `Name`, `Nick`, `Photo`, `Group`, `Password`) VALUES ('$user_id', '$u_name', '$u_nick', '$u_photo', '$u_group', '$u_pass')");
			}
		} else {
			$sql=mysql_query("UPDATE `Users` SET `Last`=CURRENT_TIMESTAMP WHERE `Auth_ID`='$u_id' LIMIT 1 ;");
			$sql=mysql_query("SELECT * FROM `Users` WHERE `Auth_ID`='$user_id' LIMIT 1");
			$row = mysql_fetch_assoc($sql);
			$u_id=$row['Auth_ID'];
			$u_name=$row["Name"];
			$u_nick=$row["Nick"];
			$u_photo=$row["Photo"];
			$u_group=$row["Group"];
			$u_pass=$row["Password"];
			if (trim($u_pass)==''){
				$u_pass_dec=crc32("vK-oAuTH|id=$u_id");
				$u_pass=md5($salt_a.$u_pass_dec.$salt_b);
				$_pass_info="<div class='alert alert-info'>Внимание!<br>Ваш пароль: <b>$u_pass_dec</b>. Изменить его Вы можете в \"<a href='?act=users&amp;mode=edit&amp;profile=$u_id' class='rdajax'>Профиле</a>\"!</div>";
				$_pass_span="special_notify(\"<div class='alert alert-info'>Внимание!<br>Ваш пароль: <b>$u_pass_dec</b>. Изменить его Вы можете в \"<a href='?act=users&amp;mode=edit&amp;profile=$u_id' class='rdajax'>Профиле</a>\"!</div>\");";
				$sql=mysql_query("UPDATE `Users` SET `Password` = '$u_pass' WHERE `Auth_ID`='$u_id' LIMIT 1 ;");
			}
		}
		setcookie($_cookie_login,$u_id,0,'/','.'.$_SERVER['HTTP_HOST']);
		setcookie($_cookie_passw,md5($salt_a.$u_pass.$salt_b),0,'/','.'.$_SERVER['HTTP_HOST']);
	}

	$admin=false;
	$user=false;
	$moder=false;
	if ($u_group=='admin'){ $admin=true; }
	if ($u_group=='user'){ $user=true; }
	if ($u_group=='moder'){ $moder=true; }

	foreach($_banned as $ip){
		if ($ip_1==$ip){
			if ($_logger_enabled){
				$sql=mysql_query("INSERT INTO `Logger` (`IP`, `VK_ID`, `SID`, `UserAgent`,`Path`, `Referer`, `Type`) VALUES ('[BAN] $ip_1', '$user_id', '$sd_1', '$ua_1', '$ur_1', '$rf_1', '$tp_1')");
			}
			err_and_die(403);
		}
	}
	if ($_logger_enabled){
		$sql=mysql_query("INSERT INTO `Logger` (`IP`, `VK_ID`, `SID`, `UserAgent`,`Path`, `Referer`, `Type`) VALUES ('$ip_1', '$user_id', '$sd_1', '$ua_1', '$ur_1', '$rf_1', '$tp_1')");
	}
	
	if ((trim($articles_sort)!="") and ($articles_count>0)){
		$sql=mysql_query("SELECT * FROM `News` WHERE `Category`<>'1' $articles_sort LIMIT $articles_count");
		$articles_inner='';
		while ($row = mysql_fetch_assoc($sql)) {
			$news_title=$row['Title'];
			$news_num=$row['Num'];
			if (trim($news_title)==''){
				$articles_inner.="<li><a class='rdajax' href='?act=fulltext&amp;id=$news_num'>&lt;нет заголовка&gt;</a></li>";
			} else {
				$articles_inner.="<li><a class='rdajax' href='?act=fulltext&amp;id=$news_num'>$news_title</a></li>";
			}
		}
	}

	if ($com_show_cnt>0){
		$sql=mysql_query("SELECT COUNT(*) FROM `Comments`");
		$row = mysql_fetch_assoc($sql);
		$cnt=$row['COUNT(*)'];
		if ($cnt>0){
			$sql=mysql_query("SELECT *,(SELECT `Title` FROM `News` WHERE `News`.`Num`=`Comments`.`News_ID`) AS 'NewsTitle' FROM `Comments` ORDER BY `ID` DESC LIMIT $com_show_cnt");
			$coms_block='';
			while ($row = mysql_fetch_assoc($sql)) {
				$c_id=$row['ID'];
				$c_text=text_cut(strip_tags($row['Text']),20);
				$c_news_title=$row['NewsTitle'];
				$c_news_id=$row['News_ID'];
				$c_author=$row['Author'];
				$cp=date_parse($row['Time']);
				$c_time=mktime($cp['hour'],$cp['minute'],$cp['second'],$cp['month'],$cp['day'],$cp['year']);
				$c_time=date('d.m.Y H:i',$c_time+$offset_t);
				
				$sql1=mysql_query("SELECT * FROM `Users` WHERE `Auth_ID`='$c_author' LIMIT 1");
				$row1 = mysql_fetch_assoc($sql1);
				$n_id=$row1['Auth_ID'];
				$n_name=$row1["Name"];
				$n_nick=$row1["Nick"];
				$n_photo=$row1["Photo"];
				
				if (trim($n_nick)==''){ $c_author=$n_name; } else { $c_author=$n_nick; }
				$retprt=$gp+1;
				$coms_block.="
					<li data-title='Новость:<br>$c_news_title'>
						<a class='rdajax' href='?act=fulltext&amp;id=$c_news_id'>
							<span class='comsblock_author'>$c_author: </span>
							<span class='comsblock_text'>$c_text</span>
						</a>
					</li>
				";
			}
		} else {
			$coms_block.="<li>Комментариев нет</li>";
		}
	}
	$cats=get_cats(mysql_query("SELECT * FROM `Categories`"));
	$scripts=generate_scripts($_home_dir,$_SERVER['QUERY_STRING'],$site_name,$static_path,$rss_link,$ya_code,$google_code,$__cms['version'],$_font_size,$_font_family,$_ajax_enabled,$api_id,$google_an_id,$_site_swatch);
}
?>