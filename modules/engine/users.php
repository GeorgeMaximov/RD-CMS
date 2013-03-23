<?
if (isset($_REQUEST['profile'])){
	if ($admin || $moder || $_REQUEST['profile']==$u_id){
		$prof_id=htmlspecialchars($_REQUEST['profile']);
		$sql=mysql_query("SELECT * FROM `Users` WHERE `Auth_ID`='$prof_id' LIMIT 1;");
		$title="Профиль пользователя";
		if ($sql!==FALSE){
			$row = mysql_fetch_assoc($sql);
			$l_id=$row['ID'];
			$l_auth=$row['Auth_ID'];
			$l_name=$row['Name'];
			$l_nick=$row['Nick'];
			if (trim($l_nick)==''){
				$l_nick="";
			} else {
				$l_nick="<dt>Ник</dt><dd>$l_nick</dd>";
			}
			$l_photo=$row['Photo'];
			$l_group=$row['Group'];
			$dp=date_parse($row['Last']);
			$l_last=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
			$l_last=date('d.m.Y H:i',$l_last+$offset_t);
			if (trim($l_name)!=""){
				$show_name="<h4 class='heading'>$l_name</h4>";
			} else {
				$show_name="";
			}
			$edit_link="";
			if (!$moder){
				$edit_link="<a href='?act=users&mode=edit&id=$l_auth' class='rdajax btn btn-mini'>Изменить профиль</a>";
			}
			if ($admin || $moder){
				$comnews_link="<a href='?act=news&user=$l_auth' class='rdajax'>Новости</a> &bull; <a href='?act=comments&user=$l_auth' class='rdajax'>Комментарии</a>";
			} else {
				$comnews_link="<a href='?act=comments&user=$l_auth' class='rdajax'>Комментарии</a>";
			}
			$content="<div class='well'>$show_name$edit_link
				<table><tr><td><img class='img-polaroid userpic' src='$l_photo'></td><td>
				    <dl class='dl-horizontal'>
						$l_nick
						<dt>Группа</dt>
						<dd>$gr_loc[$l_group]</dd>
						<dt>Последний вход</dt>
						<dd>$l_last</dd>
						<dt>Активность</dt>
						<dd>$comnews_link</dd>
					</dl>
				</td></tr></table>
				</div>";
		} else {
			$content="<script>$(document).ready(function () { alert('Пользователь с указанным ID не существует!','error'); rd_ajax('act=news'); })</script>";
		}
	} else {
		$content="<script>$(document).ready(function () { alert('$errs[0]','error'); rd_ajax('act=news'); })</script>";
	}
} else if (isset($_REQUEST['mode'])){
	$mode=htmlspecialchars($_REQUEST['mode']);
	$sec_k=htmlspecialchars($_REQUEST['key']);
	
	if (isset($_REQUEST['id'])){
		$req_id=htmlspecialchars($_REQUEST['id']);
		$sql=mysql_query("SELECT * FROM `Users` WHERE `Auth_ID`='$req_id' LIMIT 1;");
		$row = mysql_fetch_assoc($sql);
		$l_nick=$row['Nick'];
		$l_photo=$row['Photo'];
		$l_group=$row['Group'];
		$l_pass=$row['Password'];
		$l_mail=$row['Mail'];
		$l_skey=substr($l_pass,2,7);
	}
	
	if ($mode=="edit" && isset($req_id)){
		if ($u_id==$req_id || $sec_k==$l_skey || $admin){
			$title="Редактирование профиля";
			$content="<div class='well'>
						<form method='post'><input type='hidden' name='mode' value='save'><input type='hidden' name='act' value='users'><input type='hidden' name='id' value='$req_id'>";
			if (trim($sec_k)!=""){
				$content.="<input type='hidden' name='key' value='$sec_k'>";
			}
			$content.="	<table class='table table-striped'>
							<tr><td>Логин:</td><td><input class='input-xlarge' type='text' name='login' value='$l_nick'></td></tr>
							<tr><td>Аватар:</td><td><input class='input-xlarge' type='text' name='ava' value='$l_photo'></td></tr>
							<tr><td>Почта:</td><td><input class='input-xlarge' type='text' name='mail' value='$l_mail'></td></tr>";
			if ($admin){
				$content.="	<tr><td>Группа:</td><td><select class='input-xlarge' name='group'>";
				if ($l_group=='user'){
					$content.="<option value='user' selected>Пользователь</option>";
				} else {
					$content.="<option value='user'>Пользователь</option>";
				}
				if ($l_group=='moder'){
					$content.="<option value='moder' selected>Модератор</option>";
				} else {
					$content.="<option value='moder'>Модератор</option>";
				}
				if ($l_group=='admin'){
					$content.="<option value='admin' selected>Администратор</option>";
				} else {
					$content.="<option value='admin'>Администратор</option>";
				}
				
				$content.="</td></tr>";
			}
			$content.="		<tr><td>Пароль:</td><td><input class='input-xlarge' type='password' name='pass'><div class='alert alert-info'>Если Вы не хотите менять пароль - не заполняйте эти поля. Минимальная длина пароля - 5 символов.</div></td></tr>
							<tr><td>Пароль:</td><td><input class='input-xlarge' type='password' name='pass2'></td></tr>
							<tr><td></td><td><input class='btn btn-primary' type='submit' value='Сохранить'></td></tr>
						</table>
						</form>
					</div></div>";
		} else {
			$content="<script>$(document).ready(function () { alert('$errs[0]','error'); rd_ajax('act=news'); })</script>";
		}
	}
	
	if ($mode=="save" && isset($req_id)){
		if ($u_id==$req_id || $sec_k==$l_skey || $admin){
			$g_login=$_REQUEST['login'];
			$g_ava=$_REQUEST['ava'];
			$g_pass1=$_REQUEST['pass'];
			$g_pass2=$_REQUEST['pass2'];
			$g_group=$_REQUEST['group'];
			$g_mail=$_REQUEST['mail'];
			$g_pass_md5=md5($salt_a.$g_pass1.$salt_b);
			$g_add_pass='';
			$g_add_group='';
			$g_add_mail='';
			$g_error=0;
			
			if ($admin){
				$g_add_group=",`Group`='$g_group'";
			}
			
			if (trim($g_pass1)!=""){
				if ($g_pass1<>$g_pass2){
					$content="<script>alert('Пароли не совпадают!','error');rd_ajax('act=users&mode=edit&id=$req_id&key=$sec_k');";
					$g_error=1;
				} else if (strlen($g_pass1)<5){
					$content="<script>alert('Пароль меньше 5 символов!','error');rd_ajax('act=users&mode=edit&id=$req_id&key=$sec_k');";
					$g_error=1;
				} else {
					$g_pass1=md5($salt_a.$g_pass1.$salt_b);
					$g_add_pass=",`Password`='$g_pass1'";
				}
			}
			
			if (trim($g_login)!=''){
				$sql=mysql_query("SELECT COUNT(*) FROM `Users` WHERE `Nick`='$g_login' AND `Auth_ID`<>'$req_id';");
				$row = mysql_fetch_assoc($sql);
				$u_mail=$row["COUNT(*)"];
				
				if (!(filter_var($g_mail, FILTER_VALIDATE_EMAIL))){
					$content="<script>alert('Неверный E-mail адрес!','error');rd_ajax('act=users&mode=edit&id=$req_id&key=$sek_k');";
					$g_error=1;
				} else if ($u_mail>0){
					$content="<script>alert('Пользователь с таким E-mail адресом уже зарегистрирован!','error');rd_ajax('act=users&mode=edit&id=$req_id&key=$sek_k');";
					$g_error=1;
				} else {
					$g_add_mail=",`Mail`='$g_mail'";
				}
			}
			
			if (trim($g_ava)==""){
				//$g_ava=$_default_avatar;
				$g_ava='http://www.gravatar.com/avatar/'.md5(strtolower(trim($g_mail))).'?d=identicon&s=150';
			}
			
			if (trim($g_mail)!=''){
				$sql=mysql_query("SELECT COUNT(*) FROM `Users` WHERE `Mail`='$g_mail' AND `Auth_ID`<>'$req_id';");
				$row = mysql_fetch_assoc($sql);
				$u_mail=$row["COUNT(*)"];
				
				if (!(filter_var($g_mail, FILTER_VALIDATE_EMAIL))){
					$content="<script>alert('Неверный E-mail адрес!','error');rd_ajax('act=users&mode=edit&id=$req_id&key=$sek_k');";
					$g_error=1;
				} else if ($u_mail>0){
					$content="<script>alert('Пользователь с таким E-mail адресом уже зарегистрирован!','error');rd_ajax('act=users&mode=edit&id=$req_id&key=$sek_k');";
					$g_error=1;
				} else {
					$g_add_mail=",`Mail`='$g_mail'";
				}
			}
			
			if ($g_error==0){
				$sql=mysql_query("UPDATE `Users` SET `Nick` = '$g_login',`Photo` = '$g_ava'$g_add_pass$g_add_group$g_add_mail,`Last`=CURRENT_TIMESTAMP WHERE `Auth_ID`='$req_id' LIMIT 1 ;");
				if ($sql){
					$content="<script>alert('Профиль успешно изменен!','noerror');rd_ajax('act=users&mode=edit&id=$req_id&key=$sec_k');";
				} else {
					$content="<script>alert('Произошла неизвестная ошибка при изменении профиля! Повторите запрос позже','error');rd_ajax('act=users&mode=edit&id=$req_id&key=$sec_k');";
				}
			} else {}
			$title="Редактирование профиля";
		} else {
			$content="<script>$(document).ready(function () { alert('$errs[0]','error'); rd_ajax('act=users&mode=edit&id=$req_id&key=$sec_k'); })</script>";
		}
	}
	
	else if ($mode=="restore"){
		$title="Восстановление пароля";
		$content="<div class='well'>
					<form method='post'><input type='hidden' name='act' value='users'><input type='hidden' name='mode' value='request_restore'>
					<table class='table'>
						<tr><td>Логин:</td><td><input class='input-xlarge' type='text' name='id'><div class='alert alert-info'>Введите логин, указанный при регистрации. На почту, привязанную к этому логину придет письмо со ссылкой на восстановление.</div></td></tr>
						<tr><td></td><td><input class='btn btn-primary' type='submit' value='Восстановить'></td></tr>
					</table>
					</form>
				</div></div>";
	}
	
	else if ($mode=="request_restore"){
		if (trim($l_mail)!=""){
			$title="Восстановление пароля";
			$subject = 'Восстановление пароля на '.$_SERVER['HTTP_HOST'];
			$message = 'Для восстановления пароля перейдите по ссылке: http://'.$_SERVER['HTTP_HOST'].'/?act=users&mode=edit&id='.$req_id.'&key='.$l_skey;
			$headers = 'From: robot@'.$_SERVER['HTTP_HOST']. "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			if (mail($l_mail, $subject, $message, $headers)){
				$content="<div class='alert alert-info'>Письмо со ссылкой на восстановление пароля отправлено на ящик: $l_mail.<br>Не забудьте проверить папку со спамом!</div>";
			} else {
				$content="<script>$(document).ready(function () { alert('Ошибка при отправке письма!','error'); rd_ajax('act=news'); })</script>";
			}
		} else {
			$content="<script>$(document).ready(function () { alert('Для данного аккаунта не указан адрес электронной почты!','error'); rd_ajax('act=news'); })</script>";
		}
	}

} else {
	if ($admin){
		$lines=$lines_at_log;
		$gp=$_REQUEST['part'];
		if (isset($_REQUEST['part'])){ $gp=$_REQUEST['part']; } else { $gp=1; }
		$gp-=1;
		$part=$gp*$lines;
		if (isset($part)){ $start=$part; } else { $start=1; }
		$sql=mysql_query("SELECT COUNT(*) from `Users`");
		$row = mysql_fetch_assoc($sql);
		$cnt=$row['COUNT(*)'];
		$pages=floor($cnt/$lines);
		$ost=$pages % $lines;
		for ($i=0; $i<=$pages; $i++){
			$np=$lines*$i;
			if ($gp==$i){ $st="class='rdajax active'"; } else { $st="class='rdajax'"; }
			$show=$i+1;
			$ps.= "<a $st href='index.php?act=users&part=$show'>$show</a>";
		}
		$sql=mysql_query("SELECT * FROM `Users` ORDER BY `ID` DESC LIMIT $start,$lines");
		$title="Список пользователей";
		$content="<script>function toggle_adm(id){ $(\"#form\"+id).slideToggle(\"slow\"); $(\"#log\"+id).toggleClass(\"editor_show\"); }</script><div class='well'>";
		while ($row = mysql_fetch_assoc($sql)) {
			$l_id=$row['ID'];
			$l_auth=$row['Auth_ID'];
			$l_name=$row['Name'];
			$l_nick=$row['Nick'];
			$l_photo=$row['Photo'];
			$l_group=$row['Group'];
			$l_mail=$row['Mail'];
			$l_pw=$row['Password'];
			$dp=date_parse($row['Last']);
			$l_last=mktime($dp['hour'],$dp['minute'],$dp['second'],$dp['month'],$dp['day'],$dp['year']);
			$l_last=date('d.m.Y H:i',$l_last+$offset_t);
			$content.="<div id='log$l_id' class='editor'><img src='$l_photo' alt='Аватар' class='list-icon'> <a href='javascript:' onClick='toggle_adm($l_id);'>$l_auth</a> - $l_name
				<span class='pull-right btn-group'>
					<a class='rdajax btn btn-mini' title='Профиль' href='?act=users&profile=$l_auth'><i class='icon-user'></i> Профиль</a>
					<a class='rdajax btn btn-mini' title='Редактировать' href='?act=users&mode=edit&id=$l_auth'><i class='icon-pencil'></i> Изменить</a>
				</span>
			<div id='form$l_id' class='admins hide'>
			<table class='table'>
				<tr><td>ID:</td><td><b>$l_id</b></td></tr>
				<tr><td>Авторизационный ID:</td><td><b>$l_auth</b></td></tr>
				<tr><td>Фамилия/Имя:</td><td><b>$l_name</b></td></tr>
				<tr><td>Ник:</td><td><b>$l_nick</b></td></tr>
				<tr><td>Группа:</td><td><b>$gr_loc[$l_group]</b></td></tr>
				<tr><td>Последний вход:</td><td><b>$l_last</b></td></tr>
				<tr><td>Почта:</td><td><b>$l_mail</b></td></tr>
				<tr><td>md5(пароль):</td><td><b>$l_pw</b></td></tr>
			</table>
			</div></div>";
		}
		$content.="<div class='news_time'><div class='paginator'>$ps</div></div></div>";
	} else { 
		$content="<script>$(document).ready(function () { alert('$errs[0]','error'); rd_ajax('act=news'); })</script>";
	}
}
?>
