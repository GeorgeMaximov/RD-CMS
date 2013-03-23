<?
define('Only_Settings',1);
require('../include/settings.php');
require('../modules/engine/init.php');
$_rnd=rand(0,999999);
$_domain=$_SERVER['HTTP_HOST'];
$_dt=".$_domain";
if ((isset($_POST['login'])) && (isset($_POST['password']))){
	die ("Use login form at main page!");
	setcookie($_cookie_login,$_POST['login'],0,'/',$_dt);
	setcookie($_cookie_passw,md5($salt_a.$_POST['password'].$salt_b),0,'/',$_dt);
	echo "Login ok!";
	header("Location: /");
} else if (isset($_REQUEST['reset'])){
	setcookie($_cookie_login,'',1,'/',$_dt);
	setcookie($_cookie_passw,'',1,'/',$_dt);
	setcookie("vk_app_$api_id",'',1,'/',$_dt);
	echo "Logout ok!";
} else {
	die ("Use login form at main page!");
	if ((isset($_COOKIE[$_cookie_login])) || (isset($_COOKIE[$_cookie_passw])) || (isset($_COOKIE['vk_app_'.$api_id]))){
		$user_id=$_COOKIE[$_cookie_login];
		$user_pw=$_COOKIE[$_cookie_passw];
		$user_vk=$_COOKIE['vk_app_'.$api_id];
		$found="<pre>Founded login info!<br>ys_lg: $user_id<br>ys_pw: $user_pw<br>vk_ck: $user_vk<br><a href='?reset=1'>Reset cookies</a></pre>";
	}
?>
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<form method='post'>
			<input name='_rnd' value='<? echo $_rnd; ?>' type='hidden'>
			<? echo $found; ?>
			<table>
				<tr><td>Login:</td><td><input type='text' name='login'></td></tr>
				<tr><td>Password:</td><td><input type='text' name='password'></td></tr>
				<tr><td><input type='submit' value='Login'></td><td></td></tr>
			</table>
		</form>
	</body>
</html>
<? } ?>