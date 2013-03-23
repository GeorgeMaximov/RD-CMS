<?
$title = 'Переадресовщик';
$srv='helpcast.ru';
if (isset($_REQUEST['url'])){
	$api = $_REQUEST['api'];
	if ($api=='1'){
		$url = urldecode($_REQUEST['url']);
	} else {
		$url = $_REQUEST['url'];
	}
	if ((strpos($url,'.',0) === false) or (strlen($url)<5)){
		if ($api=='1'){
			$content="Неверный формат ссылки!";
		} else {
			$content="<script>$(document).ready(function () { alert('Неверный формат ссылки!','error'); rd_ajax('act=redirect'); })</script>";
		}
	} else {
		if (strpos($url,'http',0) <> 0){ $url="http://".$url; }
		$name = str_replace(array("0","1","2","3","4","5","6","7","8","9"),array("g","h","k","m","n","o","p","q","r","s"),substr(md5($url),5,9));
		$pass = $_REQUEST['pass'];
		$onetime=isset($_REQUEST['onetime']) ? 1 : 0;
		
		$sql=mysql_query("SELECT * FROM `Redirects` WHERE `Name`='$name'");
		$exist=false;
		while ($row = mysql_fetch_assoc($sql)) {
			$r_pass=trim($row['Pass']);
			$r_url=$row['URL'];
			$r_ot=$row['OneTime'];
			if (($r_pass==$pass) && ($r_url==$url) && ($r_ot==$onetime)){
				$exist=true;
			} else {
				$name = str_replace(array("0","1","2","3","4","5","6","7","8","9"),array("g","h","k","m","n","o","p","q","r","s"),substr(md5($url.$pass.uniqid()),5,9));
				break;
			}
		}
		if (!$exist){
			$sql = mysql_query("INSERT INTO `Redirects` (`ID` ,`Name` ,`URL` ,`Pass`, `OneTime`) VALUES (NULL ,  '$name',  '$url',  '$pass', '$onetime');");
		} else {
			$sql=true;
		}
		if (!$sql){ $content="<script>$(document).ready(function () { alert('Ошибка при выполнении запроса!','error'); rd_ajax('act=redirect'); })</script>";}
		else { 
		
				$link= "http://" . $srv . "/r/$name";
				if ($api=='1'){	$content="$link"; } else {
					$passinfo=($pass=='') ? '' : '<b>Пароль: </b>'.htmlspecialchars($pass);
					$otinfo=($onetime==1) ? '<b>Ссылка одноразовая.</b>' : '';
				$content .= "<div class='well'>Ваша ссылка: <br><input type='text' value='$link' class='hundred' readonly><br>$passinfo$otinfo
				<div class='btn-toolbar'>
					<a class='rdajax btn btn-mini' href='index.php?act=redirect'><i class='icon-star'></i> Создать переадресацию</a>
					<a href='$link' class='news_link btn btn-mini'><i class='icon-chevron-right'></i> Перейти по ссылке</a>
				</div>";
				$content.="</div>";
				}
		}
	}
}

else if (($_REQUEST['adm']=='true') and ($admin)){
	$lines=$lines_at_log;
	$gp=$_REQUEST['part'];
	if (isset($_REQUEST['part'])){ $gp=$_REQUEST['part']; } else { $gp=1; }
	$gp-=1;
	$part=$gp*$lines;
	if (isset($part)){ $start=$part; } else { $start=1; }
	$sql=mysql_query("SELECT COUNT(*) from `Redirects`");
	$row = mysql_fetch_assoc($sql);
	$cnt=$row['COUNT(*)'];
	$pages=floor($cnt/$lines);
	$ost=$pages % $lines;
	for ($i=0; $i<=$pages; $i++){
		$np=$lines*$i;
		if ($gp==$i){ $st="rdajax active"; } else { $st="rdajax"; }
		$show=$i+1;
		$ps.= "<a class='$st' href='index.php?act=redirect&adm=true&part=$show'>$show</a>";
	}
	$sql=mysql_query("SELECT * FROM `Redirects` ORDER BY `ID` DESC LIMIT $start,$lines");
	$title="Список переадресаций";
	$content="<div class='well'><table class='table table-striped'>
	<thead><tr><th>Сокращение</th><th>Ссылка</th><th>&nbsp;</th></tr></thead><tbody>";
	while ($row = mysql_fetch_assoc($sql)) {
		$r_id=$row['ID'];
		$r_name=$row['Name'];
		$r_ot=$row['OneTime'];
		$r_views=$row['Visits'];
		$r_url=htmlspecialchars($row['URL']);
		$r_url_parts=explode('/',$r_url,4);
		$r_url_domain=$r_url_parts[2];
		$r_ot_class=($r_ot==1) ? ($r_views==0) ? 'badge-success' : 'badge-important' : '';
		$r_ot_title=($r_ot==1) ? ($r_views==0) ? 'Количество просмотров [одноразовая ссылка]' : 'Количество просмотров [одноразовая ссылка истекла]' : 'Количество просмотров';
		$r_pass=(trim($row['Pass'])=='') ? '' : "<span class='badge badge-inverse' data-title='Пароль: " . htmlspecialchars($row['Pass']) . "'>P</span> ";
		$content .= "<tr><td><a href='/r/$r_name'>$r_name</a></td><td><a href='$r_url'>$r_url_domain</a></td><td><span class='badge $r_ot_class' data-title='$r_ot_title'>$r_views</span> $r_pass<a class='rdajax pull-right btn btn-mini btn-danger' href='/?act=redirect&mode=delete&part=$gp&name=$r_name' data-title='Удалить'><i class='icon-white icon-remove'></i></a></td></tr>"; 
		
	}
	$content.="</tbody></table></div></div><div class='paginator'>$ps</div>";
} else if (isset($_REQUEST['name'])){
	$name=$_REQUEST['name'];
	$gp=$_REQUEST['part']+1;
	if (($_REQUEST['mode']=='delete') and ($admin)){
		$sql = mysql_query("DELETE FROM `Redirects` WHERE `Name`='$name' LIMIT 1;");
		if ($sql){
			$alert = "alert('Ссылка удалена','noerror');";  
		} else {
			$alert = "alert('Ошибка при удалении ссылки!','error');"; 
		}
		$content.="<script>$(document).ready(function () { $alert; rd_ajax('act=redirect&part=$gp&adm=true'); })</script>";
	} else {
		$content="<script>$(document).ready(function () { var tr=setInterval(function(){clearInterval(tr); location.href='/r/$name'}, 1) })</script>";
	}
} else {
	$content="<div class='well'><h4 class='heading'>Создание переадресации</h4>
			<p>Сервис \"Переадресовщик\" позволяет сократить или замаскировать любую ссылку. <br>При желании, Вы можете защитить ссылку-переадресацию паролем.</p>
			<form class='form-horizontal' method='post'>
				<input type='hidden' name='act' value='redirect'>
				<div class='control-group'>
					<label class='control-label' for='inputUrl'>Введите ссылку</label>
					<div class='controls'>
						<input class='input-block-level' type='text' id='inputUrl' placeholder='Ссылка' name='url'>
						<span class='help-inline'>Только http:// или https://</span>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputPassword'>Пароль</label>
					<div class='controls'>
						<input class='input-block-level' type='password' id='inputPassword' placeholder='Пароль' name='pass'>
						<span class='help-inline'>Поле заполнять не обязательно</span>
					</div>
				</div>
				<div class='control-group'>
					
					<div class='controls'>
						<label class='checkbox'>
							<input type='checkbox' id='inputOneTime'  name='onetime'>
							Одноразовая ссылка
						</label>
					</div>
				</div>
				<div class='control-group'>
					<div class='controls'>
						<button type='submit' class='btn btn-primary'>Создать переадресацию</button>
					</div>
				</div>
			</form>";
}
?>


