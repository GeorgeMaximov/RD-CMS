<?
$support=true;
$s=array(
	0=>'Ожидание',
	2=>'Выполнено',
	3=>'Закрыто'
);
$statusclass=array(
	0=>' pull-right',
	2=>' label-success pull-right',
	3=>' label-important pull-right'
);
if (!defined('RD')) die('Hacking attempt!');
############
if ($_REQUEST['mode']=='post'){
	$nick=htmlspecialchars($_REQUEST['nick']);
	$msg=htmlspecialchars($_REQUEST['msg']);
	$skype=htmlspecialchars($_REQUEST['skype']);
	$vk=htmlspecialchars($_REQUEST['vk']);
	$ip=$_SERVER['REMOTE_ADDR'];

	$title="Результаты добавления заявки";
	$error='';
	if ($nick=='') { $error.='<br>Ник пуст!'; }
	if ($msg=='')  { $error.='<br>Пустое сообщение!'; }
	if ($icq=='') 
		{ if ($skype=='')
			{ if ($vk=='')
			  { $error.='<br>Нет контактной информации!'; }
			}
		}
	if ($user!=true){
		if ($admin!=true){ 
			$error.="<br>Вы не вошли в систему!"; 
		}
	}
	if ($error=='') {
		$sql = mysql_query("INSERT INTO TechSupp (`Status`,`Nick`,`Skype`,`VK`,`Auth`,`MSG`,`IP`) VALUES('0','$nick','$skype','$vk','$u_id','$msg','$ip')");
		if ($sql){
			$sql_n = mysql_query("SELECT `ID` FROM `TechSupp` ORDER BY `TechSupp`.`ID` DESC LIMIT 1");
			if ($sql_n){
				while ($line = mysql_fetch_array($sql_n)){
					$content = "<div class='well'><h4 class='heading'>Результат добавления заявки</h4>Номер заявки: $line[0]<br><a class='rdajax btn btn-mini' href='?act=support'>Вернуться</a></div><script>$(document).ready(function () { alert('Заявка добавлена!','noerror'); })</script>"; 
				}
				/* you can notify yourself :) */
			} else {
				$content = "<script>$(document).ready(function () { alert('Ошибка при выполнении запроса!','error'); rd_ajax('act=support'); })</script>";
			}
		} else {
			$content = "<script>$(document).ready(function () { alert('Ошибка при выполнении запроса!','error'); rd_ajax('act=support'); })</script>";
		}
	} else 
	{ 
		$content = "<script>$(document).ready(function () { alert('Форма заполнена неправильно: $error','error'); rd_ajax('act=support'); })</script>";
	}

} else if (($_REQUEST['mode']=='status') and (isset($_REQUEST['num'])) and (isset($_REQUEST['status']))) {
	$num=$_REQUEST['num'];
	$status=htmlspecialchars($_REQUEST['status']);
	$class=$statusclass[$status];
	$sql = mysql_query("UPDATE TechSupp SET `Status`='$status' WHERE `ID`=$num");
	if ($sql){
		$content="<span class='label$class' id='status_box_$num'>$s[$status]</span>";
	} else {
		$content="<span class='label label-warning' id='status_box_$num'>Ошибка</span>";
	}
} else if (($_REQUEST['mode']=='view') and (isset($_REQUEST['num']))) {
	$num=htmlspecialchars($_REQUEST['num']);
	$sql_r = mysql_query("SELECT `Status`,`Auth` FROM `TechSupp` WHERE `ID`=$num");
	$count=0;
	if ($sql_r){
		while ($line = mysql_fetch_array($sql_r)) {
			if ($line['Auth']==$u_id || $admin || $moder){
				$status=$line['Status'];
				$content="<p><div class='alert alert-info'>Статус заявки №$num: $s[$status]</div></p>";
			} else {
				$content="<p><div class='alert alert-error'>У Вас нет прав для доступа к данной заявке!</div></p>"; 
			}
			$count++;
		}
		if ($count==0){
			$content="<p><div class='alert alert-error'>Неверный номер заявки!</div></p>"; 
		}
	} else {
		$content="<p><div class='alert alert-error'>Неверный номер заявки!</div></p>"; 
	}
} else if ($_REQUEST['mode']=='cs') {
	$title="Анкета администратора";
	$content ="<div class='well'>
			<h3 class='heading'>Анкета администратора</h3>
			<div class='well well-small'>
				<h5>Откуда вы?</h5>
					<input class='input-block-level' type='text' id='q1'/>
			</div>
			<div class='well well-small'>
				<h5>Ваш возраст?</h5>
					<input class='input-block-level' type='text' id='q2'/>
			</div>
			<div class='well well-small'>
				<h5>Сколько лет вы играете в Counter-Strike?</h5>
					<input class='input-block-level' type='text' id='q3'/>
			</div>
			<div class='well well-small'>
				<h5>На каком сервере вы чаще всего играете? (кроме RedDragon's Play Servers)</h5>
					<input class='input-block-level' type='text' id='q6'/>
			</div>
			<div class='well well-small'>
				<h5>Пользуетесь ли вы читами?</h5>
				<form id='qq4'>
					<label class='checkbox'><input name='q4' type='radio' id='q4_1' value='да' /> да</input></label>
					<label class='checkbox'><input name='q4' type='radio' id='q4_2' value='раньше пользовался' /> раньше пользовался, сейчас - нет</input></label>
					<label class='checkbox'><input name='q4' type='radio' id='q4_3' value='нет' /> нет</input></label>
				</form>
			</div>
			<div class='well well-small'>
				<h5>Состоите ли вы в клане?</h5>
				<form id='qq5'>
					<label class='checkbox'><input name='q5' type='radio' id='q5_1' value='да' /> да</input></label>
					<label class='checkbox'><input name='q5' type='radio' id='q5_2' value='нет' /> нет</input></label>
				</form>	
			</div>
			<div class='well well-small'>
				<h5>Сколько времени вы играете в CS в день?</h5>
				<form id='qq7'>
					<label class='checkbox'><input name='q7' type='radio' id='q7_1' value='меньше часа' /> меньше часа</input></label>
					<label class='checkbox'><input name='q7' type='radio' id='q7_2' value='около часа' /> около часа</input></label>
					<label class='checkbox'><input name='q7' type='radio' id='q7_3' value='2-3 часа' /> 2-3 часа</input></label>
					<label class='checkbox'><input name='q7' type='radio' id='q7_4' value='более 3 часов' /> более 3 часов</input></label>
				</form>
			</div>
			<input class='btn btn-primary' type='button' value='Далее' onClick=\"
			var str='';
			var a4=0;
			var a5=0;
			var a7=0;
			a4=$('#qq4 input:radio:checked').val();
			a5=$('#qq5 input:radio:checked').val();
			a7=$('#qq7 input:radio:checked').val();
			str='|'+$('#q1').val()+'|'+$('#q2').val()+'|'+$('#q3').val()+'|'+a4+'|'+a5+'|'+$('#q6').val()+'|'+a7+'|';
			
			str=encodeURIComponent(str);
			show_loader();
			rd_ajax('act=support&mode=status&msgcd='+str);
			\" /></div>";
} else if ($_REQUEST['mode']=='admin') { 
	if ($admin)
	{
		if ($_REQUEST['all']=='1'){
		$title = "Все заявки";
		$sql_r = mysql_query("SELECT * FROM `TechSupp` ORDER BY `TechSupp`.`ID` DESC");
		}
		else
		{
		$title= "Просмотр невыполненных заявок";
		$sql_r = mysql_query("SELECT * FROM `TechSupp` WHERE `Status`<>'2' and `Status`<>'3' ORDER BY `TechSupp`.`ID` DESC");
		}
		//$content = $tab;
		$content.="<script>function toggle_adm(id){
					$(\"#form\"+id).slideToggle(\"slow\");
					$(\"#admin\"+id).toggleClass(\"editor_show\");
					}
					</script>
					<div class='well'>";
					
		while ($line = mysql_fetch_array($sql_r)) {
			$t_id=$line[0];
			$t_time=$line[1];
			$t_status=$line[2];
			$t_nick=$line[3];
			$t_skype=$line[4];
			$t_vk=$line[5];
			$t_auth=$line[8];
			$t_message=$line[6];
			$t_s_msg=text_cut($t_message,40);
			$t_ip=$line[7];
			$status="$s[$t_status]";
			$c_status=$statusclass[$t_status];
			$content .= "<div id='admin$t_id' class='editor'><span><span id='status_box_$t_id' class='label$c_status pull-right'>$status</span></span><a href='javascript:' class='btn btn-mini' onClick='toggle_adm($t_id);'>$t_nick</a> $t_s_msg
						<form method='post' id='form$t_id' class='admins hide'>
							<div>
								<input type='hidden' name='num' value='$t_id'>
								<input type='hidden' name='act' value='support' />
								<input type='hidden' name='mode' value='status' />
								<table class='table table-striped'>
									<tr>
										<td>Заявка:</td><td>№$t_id</td>
									</tr>
									<tr>
										<td>Дата и время:</td><td>$t_time</td>
									</tr>
									<tr>
										<td>Статус:</td><td><select class='cm_box' name='status' onChange=\"$.post('index.php',{num:'$t_id',act:'support',mode:'status',ajax:2,status:this.value},function(data){\$('#status_box_$t_id').parent().html(data);});alert('Статус изменен!');\">
												<option value='0'>$s[0]</option>
												<option value='2'>$s[2]</option>
												<option value='3'>$s[3]</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Авторизационный ID:</td><td>$t_auth</td>
									</tr>
									<tr>
										<td>VK:</td><td>$t_vk</td>
									</tr>
									<tr>
										<td>Skype:</td><td>$t_skype</td>
									</tr>
									<tr>
										<td>Текст:</td><td>$t_message</td>
								</table>
							</div>
						</form>
				</div>";
		}
		$content .= "<div class='form-actions'><a class='rdajax btn btn-primary input-block-level' href='index.php?act=support&amp;mode=admin&amp;all=1'>Все заявки</a></div></div>";
	
	}
	else { $title = "Админ - панель"; $content = "<script>$(document).ready(function () { alert('$errs[0]','error'); rd_ajax('act=support'); })</script>"; }
} else {
	$title="Техническая поддержка";
	if (isset($_REQUEST['msgcd'])) { 
		$t_msg_input .= "<input type='hidden' name='msg' value='".$_REQUEST['msgcd']."'>";
		$t_msg_input .= "<input type='text' class='input-block-level' disabled value='Анкета администратора' id='inputMsg'/>";
	} else { 
		$t_msg_input .= "<input class='input-block-level' type='text' name='msg' maxlength='2048' id='inputMsg' placeholder='Описание проблемы'><span class='help-inline'>Введите подробное описание вопроса или проблемы.</span>";
	}
	$show_as=($u_nick=='')?$u_name:$u_nick;
	$show_id=(is_numeric($u_id))?$u_id:'';
	$content = "
	<div class='well'>
		<h4 class='heading'>Добавление заявки</h4>
		<form method='post' id='add_ticket' class='form-vertical'>
			<input type='hidden' name='act' value='support' />
			<input type='hidden' name='mode' value='post'>
			
			<div class='control-group'>
				<label class='control-label' for='inputNick'>Имя или ник</label>
				<div class='controls'>
					<input type='text' class='input-block-level' name='nick' maxlength='32' value='$show_as' placeholder='Имя или ник' id='inputNick'/>
					<span class='help-inline'>Как обращаться к Вам.</span>
				</div>
			</div>
			
			<div class='well well-small'>
				<p>Требуется заполнение хотя бы одного из полей в этом разделе.</p>
				<div class='control-group'>
					<label class='control-label' for='inputSkype'>Skype</label>
					<div class='controls'>
						<input type='text' class='input-block-level' name='skype' maxlength='32' placeholder='Skype-логин' id='inputSkype'/>
					</div>
				</div>
				
				<div class='control-group'>
					<label class='control-label' for='inputVk'>ВКонтакте</label>
					<div class='controls'>
						<input type='text' class='input-block-level' name='vk' maxlength='32' placeholder='ID ВКонтакте' id='inputVk' value='$show_id'/>
					</div>
				</div>
			</div>
			
			<div class='control-group'>
				<label class='control-label' for='inputMsg'>Сообщение</label>
				<div class='controls'>
					$t_msg_input
				</div>
			</div>
			
			<div class='form-actions'>
				<input class='btn btn-primary btn-block' type='submit' value='Отправить заявку' id='send_btn' disabled='disabled'/>
				<span class='help-inline' id='vk_enter'>Для отправки заявки необходимо авторизоваться.</span>
				<script>setInterval(function(){ if ((user==true) || (moder==true) || (admin==true)){ $('#send_btn').removeAttr('disabled'); $('#vk_enter').hide(); } else { $('#send_btn').attr('disabled','disabled'); $('#vk_enter').show();}},1000);</script>
			</div>
		</form>
	</div>

	<div class='well'>
		<h4 class='heading'>Проверка статуса заявки</h4>
		<form method='post' class='form-vertical'>
			<input type='hidden' name='act' value='support' />
			<input type='hidden' name='mode' value='view'>
			<div class='control-group'>
				<label class='control-label' for='ticket_num'>Номер заявки</label>
				<div class='controls'>
					<input type='text' class='input-block-level' id='ticket_num' maxlength='32' name='num' placeholder='Номер заявки'/>
					<span class='help-inline'>Номер заявки присваивается при успешной отправке заявки.</span>
				</div>
			</div>
			
			<div id='server_answer2'></div>
			
			<div class='form-actions'>
				<div class='controls'>
					<input type='button' onClick=\" 
						ticket_id=$('#ticket_num').val();
						$.post('index.php',{act:'support',mode:'view',num:ticket_id,ajax:2},function(data){ 
							$('#server_answer2').html(data); 
						});\" value='Проверить' class='btn btn-primary btn-block'/>
				</div>
			</div>
		</form>
	</div>

	<div class='well'>
	<h4 class='heading'>Анкета администратора Counter-Strike</h4>
	<div style='text-align:center'><a href='?act=support&mode=cs' class='rdajax'>Перейти к анкете</a></div></div>
	";
}
?>
