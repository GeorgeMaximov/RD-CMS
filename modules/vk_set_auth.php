<?
require('../include/settings.php');
require('engine/init.php');
if (!defined('RD')) die('Hacking attempt!'); ?><!DOCTYPE HTML>
<html>
<head><title>RedDragon's Blog</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link rel='stylesheet' href='<? echo $static_path; ?>/css/bootstrap.css'/>
<script type="text/javascript" src="//vk.com/js/api/openapi.js" charset="windows-1251"></script>
</head>
<body onload='start();'>
	<div class='container'>
		<div class='row'>
			<div class='span6'>
				<div class='well'>
					<h3>Авторизация Open Api</h3>
					<a href='/'>Главная</a> &bull; <a href='javascript:doLogin()'>Войти</a> &bull; <a href='javascript:doLogout()'>Выйти</a>
				</div>
				<div class='well'>
					<h3>Профиль пользователя</h3>
					<span id='user'></span>
				</div>
			</div>
			<div class='span6'>
				<div class='well'>
					<h3>Внутренняя авторизация</h3>
					<form id='login' class='form-vertical' method='post' action='/index.php'>
						<input type='hidden' name='act' value='auth'>
						<input type='hidden' name='login' value='1'>
						<div class='control-group'>
							<label for='popup-login' class='control-label'><i class='icon-user'></i> Логин</label>
							<div class='controls'>
								<input class='hundred' type='text' name='rd_login' value='' placeholder='Логин или ID' id='popup-login'/>
							</div>
						</div>
						<div class='control-group'>
							<label for='popup-pass' class='control-label'><i class='icon-arrow-right'></i> Пароль</label>
							<div class='controls'>
								<input class='hundred' type='password' name='rd_pass' value='' placeholder='Пароль' id='popup-pass'/>
							</div>
						</div>
						<div class='control-group'>
							<div class='controls'>
								<div class='btn-toolbar'>
									<input type='submit' value='Вход' class='btn btn-primary'>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script>
		function readcookie(name) {
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
			}
			return null;
		}
		function is_numeric( mixed_var ) {
			return ( mixed_var == '' ) ? false : !isNaN( mixed_var );
		}
		function doLogin() {
			console.log('Login request');
			VK.Auth.login(function(resp){
				request_user_info(resp.session.mid);
			}, null);
		}
		function doLogout(){
			VK.Auth.logout(status('Logged out!'));
			console.log('Logout request');
		}
		function status(text){
			document.getElementById('user').innerHTML=text;
		}
		function request_user_info(id){
			console.log('User Info request');
			VK.Api.call('users.get', {'uids':id,'fields':'first_name,last_name,nickname,photo,photo_medium'}, function(r) {
				console.log('User Info Response');
				console.log(r);
				if(r.response) {
					status("<img class='img-polaroid' src='"+r.response[0].photo_medium+"'/><br>VK ID: <b>"+r.response[0].uid+"</b><br>Имя: <b>"+r.response[0].first_name+' '+r.response[0].last_name+"</b><br>Ник: <b>"+r.response[0].nickname+"</b>");
				} else {
					status("Can't get profile!");
				}
			});
		}
		function start(){
			VK.init({
				apiId: '1931984',
				nameTransportPath: "/include/xd_reciever.html"
			});
			var usid=readcookie('rd_lg');
			if (is_numeric(usid)){
				request_user_info(usid);
			}
		}
	</script>
</body></html>