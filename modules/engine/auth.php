<?
	if (isset($_REQUEST['login']) && (isset($_REQUEST['rd_login'])) && (isset($_REQUEST['rd_pass']))){
		$user_id=htmlspecialchars($_REQUEST['rd_login']);
		$user_pw=htmlspecialchars($_REQUEST['rd_pass']);
		$sql=mysql_query("SELECT COUNT(*) from `Users` WHERE `Auth_ID`= '$user_id'");
		$row = mysql_fetch_assoc($sql);
		$total=$row['COUNT(*)'];
		if ($total==0){
			$content="alert('Неверный логин или пароль!','error'); login_form(1);";
		} else {
			$sql=mysql_query("SELECT * FROM `Users` WHERE `Auth_ID`='$user_id' LIMIT 1");
			$row = mysql_fetch_assoc($sql);
			$u_pass=$row["Password"];
			if ($u_pass!=md5($salt_a.$user_pw.$salt_b)){
				$content.="alert('Неверный логин или пароль!','error'); login_form(1);";
			} else {
				setcookie($_cookie_login,$user_id,0,'/','.'.$_SERVER['HTTP_HOST']);
				setcookie($_cookie_passw,md5($salt_a.$user_pw.$salt_b),0,'/','.'.$_SERVER['HTTP_HOST']);
				$sql=mysql_query("UPDATE `Users` SET `Last`=CURRENT_TIMESTAMP WHERE `Auth_ID`='$user_id' LIMIT 1 ;");
				$content.="auth_state(true);";
			}
		}
	} else {
		if ($admin){ $content.="var admin=true;"; } else { $content.="var admin=false; "; }
		if ($moder){ $content.="var moder=true;"; } else { $content.="var moder=false; "; }
		if ($user){ $content.="var user=true;"; } else { $content.="var user=false; "; }
		$content.="var userid='$u_id'; var username='$u_name'; var usernick='$u_nick'; var userphoto='$u_photo';";
		if ($admin){
			$content.="$('#admin_menu').html(\"<li class='nav-header'>Админ меню</li><li><a class='rdajax' href='index.php?act=add'>Добавить новость</a></li><li><a class='rdajax' href='index.php?act=news&cat=1'>Черновики</a></li><li><a class='rdajax' href='index.php?act=admin'>Настройки</a></li><li><a class='rdajax' href='index.php?act=users'>Пользователи</a></li><li><a class='rdajax' href='index.php?act=redirect&adm=true'>Переадресации</a></li><li><a class='rdajax' href='index.php?act=support&mode=admin'>Заявки</a></li><li><a class='rdajax' href='index.php?act=sms'>SMS</a></li><li><a class='rdajax' href='index.php?act=comments'>Комментарии</a></li><li><a class='rdajax' href='index.php?act=log'>Лог</a></li><li><a href='javascript:' onClick='rd_ajax(query_string,1);'>Перегрузить данные</a></li>\");$('#admin_menu').slideDown('fast');";
		} 
		if ($moder){
			$content.="$('#mod_menu').html(\"<li class='nav-header>Меню модератора</li><li><a class='rdajax' href='index.php?act=add'>Добавить новость</a></li><li><a class='rdajax' href='index.php?act=news&cat=1'>Черновики</a></li><li><a class='rdajax' href='index.php?act=comments'>Комментарии</a></li>\");$('#mod_menu').slideDown('fast');";
		}
	}
?>
