<?
$__ip=htmlspecialchars($_REQUEST['ip']);
$__sid=htmlspecialchars($_REQUEST['sid']);
$__ua=htmlspecialchars($_REQUEST['ua']);
$__ur=htmlspecialchars($_REQUEST['ur']);
$filter="<div class='well'><h4 class='heading'>Фильтрация</h4>
			<form method='post' class='form-vertical'>
				<input type='hidden' name='act' value='log'>
				
				<div class='control-group'>
					<label class='control-label' for='inputIP'>IP</label>
					<div class='controls'>
						<input type='text' id='inputIP' name='ip' class='input-block-level' value='$__ip'/>
					</div>
				</div>
				
				<div class='control-group'>
					<label class='control-label' for='inputSID'>Session ID</label>
					<div class='controls'>
						<input type='text' id='inputSID' name='sid' class='input-block-level' value='$__sid'/>
					</div>
				</div>
				
				<div class='control-group'>
					<label class='control-label' for='inputUA'>User Agent</label>
					<div class='controls'>
						<input type='text' id='inputUA' name='ua' class='input-block-level' value='$__ua'/>
						<span class='help-inline'>Допускается частичный ввод.</span>
					</div>
				</div>
				
				<div class='control-group'>
					<label class='control-label' for='inputURL'>URL</label>
					<div class='controls'>
						<input type='text' id='inputURL' name='ur' class='input-block-level' value='$__ur'/>
						<span class='help-inline'>Допускается частичный ввод.</span>
					</div>
				</div>
				
				<div class='control-group'>
					<div class='controls'>
						<input class='btn btn-primary btn-block' type='submit' value='Отфильтровать'/>
					</div>
				</div>
			</form>
		</div>";

if ($admin && $_logger_enabled){
	$title="Лог переходов по сайту";
	if (($_REQUEST['ip'] != "") or ($_REQUEST['sid'] != "") or ($_REQUEST['ua'] != "") or ($_REQUEST['ur'] != "")){
		$sql_filter="WHERE ";
		if ($_REQUEST['ip'] != ""){
			$sql_filter.= (($_REQUEST['sid'] != "") || ($_REQUEST['ua'] != "") || ($_REQUEST['ur'] != "")) ? "`IP`='$__ip' AND " : "`IP`='$__ip'";
		}
		if ($_REQUEST['sid'] != ""){
			$sql_filter.= (($_REQUEST['ua'] != "") || ($_REQUEST['ur'] != "")) ? "`SID`='$__sid' AND " : $sql_filter.="`SID`='$__sid'";
		}
		if ($_REQUEST['ua'] != ""){
			$sql_filter.= ($_REQUEST['ur'] != "") ? "`UserAgent` LIKE '%$__ua%' AND" : "`UserAgent` LIKE '%$__ua%'";
		}
		if ($_REQUEST['ur'] != ""){
			$sql_filter.= "`Path` LIKE '%$__ur%'";
		}
	}
		
	$lines=$lines_at_log;
	$gp=$_REQUEST['part'];
	if (isset($_REQUEST['part'])){ $gp=$_REQUEST['part']; } else { $gp=1; }
	$gp-=1;
	$part=$gp*$lines;
	if (isset($part)){ $start=$part; } else { $start=1; }
	$sql=mysql_query("SELECT COUNT(*) from `Logger` $sql_filter");
	$row = mysql_fetch_assoc($sql);
	$cnt=$row['COUNT(*)'];
	$pages=floor($cnt/$lines);
	$ost=$pages % $lines;
	$sql=mysql_query("SELECT * FROM `Logger` $sql_filter ORDER BY `ID` DESC LIMIT $start,$lines");
	$content="<script>function toggle_adm(id){ $(\"#form\"+id).slideToggle(\"fast\"); $(\"#log\"+id).toggleClass(\"editor_show\"); }</script>$filter
	<div class='well'><h4 class='heading'>Лог</h4>";
	if (isset($sql_filter)){
		$content.="<div class='alert alert-info'>Результаты поиска</div>";
		$pg_add="&ip=$__ip&sid=$__sid&ua=$__ua&ur=$__ur";
	}
	if ($cnt>0){
		while ($row = mysql_fetch_assoc($sql)) {
			$l_id=$row['ID'];
			$dp=date_parse($row['TIME']);
			$l_time=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
			$l_time=date('d.m.Y H:i',$l_time+$offset_t);
			$l_ip=htmlspecialchars($row['IP']);
			$l_vk=htmlspecialchars($row['VK_ID']);
			$l_sd=htmlspecialchars($row['SID']);
			$l_ua=htmlspecialchars($row['UserAgent']);
			$l_rf=htmlspecialchars($row['Referer']);
			$l_adr=htmlspecialchars($row['Path']);
			$l_typ=(htmlspecialchars($row['Type'])=='')?'N/A':htmlspecialchars($row['Type']);
			$l_s_adr=text_cut($l_adr,30);
			$spider = SpiderDetect($l_ua,$l_ip);
			$content.="<div id='log$l_id' class='editor'><span class='label pull-right'>$l_time</span><a href='javascript:' onClick='toggle_adm($l_id);' class='btn btn-mini'>$spider $l_ip [$l_typ]</a> $l_s_adr<div id='form$l_id' class='admins hide'>
			<table class='table table-striped'>
				<tr><td class='lefttd'>Событие №</td><td><b>$l_id</b></td></tr>
				<tr><td class='lefttd'>Тип запроса:</td><td><b>$l_typ</b></td></tr>
				<tr><td class='lefttd'>Путь: <a class='btn btn-mini pull-right' href='$l_adr' data-title='Перейти' target='_blank'><i class='icon-arrow-right'></i></a></td><td><input type='text' readonly class='input-block-level' value='$l_adr'/></td></tr>
				<tr><td class='lefttd'>Дата/время:</td><td>$l_time</td></tr>
				<tr><td class='lefttd'>Useragent:</td><td><a href='index.php?act=log&ua=$l_ua' class='rdajax'>$l_ua</a></td></tr>
				<tr><td class='lefttd'>Referer:</td><td><a href='$l_rf'>$l_rf</a></td></tr>
				<tr><td class='lefttd'>Авторизационный ID:</td><td><a href='?act=users&profile=$l_vk' class='rdajax'>$l_vk</a></td></tr>
				<tr><td class='lefttd'>ID сессии:</td><td><a href='index.php?act=log&sid=$l_sd' class='rdajax'>$l_sd</a></td></tr>
				<tr><td class='lefttd'>IP адрес:</td><td><a href='index.php?act=log&ip=$l_ip' class='rdajax'>$l_ip</a> [<a href='http://whois.domaintools.com/$l_ip' target='_blank'>WHOIS</a>]</td></tr>
			</table>
			</div></div>";
		}
	} else {
		$content.="<div class='alert alert-error'>К сожалению, ничего не нашлось.</div>";
	}

	for ($i=0; $i<=$pages; $i++){
		if (($i==0) || ($gp-2==$i) || ($gp-1==$i) || ($gp==$i) || ($gp+1==$i) || ($gp+2==$i) || ($i==$pages)){
			$np=$lines*$i;
			if ($gp==$i){ $st=" class='active'"; } else { $st=""; }
			$show=$i+1;
			$ps.= "<li$st><a href='index.php?act=log&part=$show$pg_add' class='rdajax'>$show</a></li>";
		}
	}
	
	$content.="</div><div class='pagination pagination-centered'><ul>$ps</ul></div>";
} else {
	if (!$admin){
		$content="<script>$(document).ready(function () { alert('$errs[0]','error'); rd_ajax('act=news'); })</script>";
	} else {
		$content="<script>$(document).ready(function () { alert('Запись событий отключена!','error'); rd_ajax('act=news'); })</script>";
	}
}
?>
