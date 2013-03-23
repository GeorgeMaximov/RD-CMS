<?
if (isset($_REQUEST['mode'])){
	$step=$_REQUEST['mode'];
} else {
	$step='step1';
}
/*
	step1 - Reg Form Main
	step2 - Reg Success
*/
$title='Регистрация';
if (!($user || $admin || $moder)){
	if ($step=='step1'){
		$content="<div class='well'>
					<form method='post'><input type='hidden' name='act' value='reg'><input type='hidden' name='mode' value='step2'>
					<table class='table table-striped'>
						<tr><td class='lefttd'>Логин:</td><td><input class='input-xlarge' type='text' name='login'><span class='help-inline'>Минимум - 3 символа.</span></td></tr>
						<tr><td class='lefttd'>E-mail:</td><td><input class='input-xlarge' type='text' name='mail'><span class='help-inline'>Используется <a href='http://gravatar.com'>Gravatar</a>.</span></td></tr>
						<tr><td class='lefttd'>Пароль:</td><td><input class='input-xlarge' type='password' name='pass'><span class='help-inline'>Минимум - 5 символов.</span></td></tr>
						<tr><td class='lefttd'>Пароль:</td><td><input class='input-xlarge' type='password' name='pass2'><span class='help-inline'>Пароли должны совпадать.</span></td></tr>
						<tr><td class='lefttd'></td><td><input class='btn btn-primary' type='submit' value='Зарегистрироваться'></td></tr>
					</table>
					</form>
					</div>";
	}
	if ($step=='step2'){
		$r_login=htmlspecialchars($_REQUEST['login']);
		$r_mail=htmlspecialchars($_REQUEST['mail']);
		$r_pass=$_REQUEST['pass'];
		$r_pass2=$_REQUEST['pass2'];
		$r_pass_md5=md5($salt_a.$r_pass.$salt_b);
		$r_ava='http://www.gravatar.com/avatar/'.md5(strtolower(trim($r_mail))).'?d=identicon&s=150';
		$sql=mysql_query("SELECT COUNT(*) FROM `Users` WHERE `Mail`='$r_mail' OR `Auth_ID`='$r_login';");
		$row = mysql_fetch_assoc($sql);
		$u_mail=$row["COUNT(*)"];
		
		preg_match_all('/[0-9]/',$r_login,$matches);
		$int_count=count($matches[0]);
		
		if ($r_pass<>$r_pass2){
			$content="<script>alert('Пароли не совпадают!','error');rd_ajax('act=reg');";
		} else if (strlen($r_login)==$int_count){
			$content="<script>alert('Имя должно содержать хотя бы одну букву!','error');rd_ajax('act=reg');";
		} else if (strlen($r_pass)<5){
			$content="<script>alert('Пароль меньше 5 символов!','error');rd_ajax('act=reg');";
		} else if (!(filter_var($r_mail, FILTER_VALIDATE_EMAIL))){
			$content="<script>alert('Неверный E-mail адрес!','error');rd_ajax('act=reg');";
		} else if ($u_mail>0){
			$content="<script>alert('Пользователь с таким E-mail адресом или логином уже зарегистрирован!','error');rd_ajax('act=reg');";
		} else if (strlen($r_login)<3){
			$content="<script>alert('Слишком короткий логин! Минимум 3 символа!','error');rd_ajax('act=reg');";
		} else {
			$sql=mysql_query("INSERT INTO `Users` (`Auth_ID`, `Nick`, `Password`, `Mail`, `Photo`) VALUES ('$r_login', '$r_login', '$r_pass_md5', '$r_mail', '$r_ava')");
			if ($sql){
				$content="<script>alert('Вы успешно зарегистрированы!','noerror');rd_ajax('act=news');login_form(1);";
			} else {
				$content="<script>alert('Произошла неизвестная ошибка при регистрации! Повторите запрос позже','error');rd_ajax('act=reg');";
			}
		}
	}
} else {
	$content="<script>rd_ajax('act=news');alert('Вы уже зарегистрированы!','error');</script>";
}
?>
