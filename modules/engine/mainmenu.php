<?
if ($admin){
	$title="Управление главным меню";
	$iter=0;
	$header="<script>function toggle_adm(id){
	$(\"#form\"+id).slideToggle(\"slow\");
	$(\"#admin\"+id).toggleClass(\"editor_show\");
	}
	</script>
	<div class='well'><h4 class='heading'>Управление главным меню</h4>";
	$mode="";
	if (isset($_REQUEST['mode'])){ $mode=$_REQUEST['mode']; }
	switch ($mode){
			case "edit":
				$id=htmlspecialchars($_REQUEST['id']);
				$titl=htmlspecialchars($_REQUEST['title']);
				$url=htmlspecialchars($_REQUEST['url']);
				$pos=htmlspecialchars($_REQUEST['pos']);
				if (trim($pos)=='') { $pos='NULL'; } else { $pos="'" . $pos . "'"; }
				$sql=mysql_query("UPDATE `MainMenu` SET `Title` = '$titl',  `URL` =  '$url', `Position` = $pos WHERE  `ID` = '$id' LIMIT 1;");
				$alert=($sql) ? "alert('Пункт меню изменён!','noerror');" : "alert('Ошибка при изменении пункта меню!','error');";
				$content.="<script>$(document).ready(function () { rd_ajax('act=mainmenu');$alert });</script>";
			break;
			case "delete":
				$id=htmlspecialchars($_REQUEST['id']);
				$sql=mysql_query("DELETE FROM `MainMenu` WHERE  `ID` = '$id' LIMIT 1;");
				$alert=($sql) ? "alert('Пункт меню удалён!','noerror');" : "alert('Ошибка при удалении пункта меню!','error');";
				$content.="<script>$(document).ready(function () { rd_ajax('act=mainmenu');$alert });</script>";
			break;
			case "add":
				$titl=htmlspecialchars($_REQUEST['title']);
				$url=htmlspecialchars($_REQUEST['url']);
				$pos=(isset($_REQUEST['pos']) && trim($_REQUEST['pos'])!='') ? htmlspecialchars($_REQUEST['pos']) : false;
				$sqlq=($pos) ? "INSERT INTO `MainMenu` (`Title`, `URL`, `Position`) VALUES('$titl', '$url', '$pos')" : "INSERT INTO `MainMenu` (`Title`, `URL`) VALUES('$titl', '$url')";
				echo $sqlq.' ';
				mysql_query($sqlq);
				$alert=($sql) ? "alert('Пункт меню добавлен!','noerror');" : "alert('Ошибка при добавлении пункта меню!','error');";
				$content.="<script>$(document).ready(function () { rd_ajax('act=mainmenu');$alert });</script>";
			break;
			default:	
			$sql=mysql_query("SELECT * FROM `MainMenu` ORDER BY `Position`,`ID` ASC");
			$content=$header;
			while ($row = mysql_fetch_assoc($sql)) {
				$iter+=1;
				$id=$row['ID'];
				$titl=htmlspecialchars($row['Title']);
				$url=$row['URL'];
				$pos=$row['Position'];
				$content .= "	<div id='admin$iter' class='editor'><a href='javascript:' onClick='toggle_adm($iter);' class='btn btn-link'>$id - $titl</a>
										<form method='post' id='form$iter' class='form-horizontal hide'>
											<input type='hidden' name='act' value='mainmenu'><input type='hidden' name='id' value='$id'><input type='hidden' name='mode' value='edit'>
											<div class='control-group'>
												<label class='control-label' for='inputTitle$iter'>Название</label>
												<div class='controls'>
													<input class='input-block-level' id='inputTitle$iter' placeholder='Название ссылки' name='title' type='text' value='$titl'>
													<span class='help-inline'>Максимум - 255 символов.</span>
												</div>
											</div>
											<div class='control-group'>
												<label class='control-label' for='inputurl$iter'>Ссылка</label>
												<div class='controls'>
													<input class='input-block-level' id='inputurl$iter' placeholder='Адрес' name='url' type='text' value='$url'>
													<span class='help-block'>Максимум - 1024 символов.</span>
												</div>
											</div>
											<div class='control-group'>
												<label class='control-label' for='inputpos$iter'>Позиция</label>
												<div class='controls'>
													<input class='input-block-level' id='inputpos$iter' placeholder='Позиция ссылки' name='pos' type='text' value='$pos'>
													<span class='help-block'>Чем меньше это значение - тем выше будет пункт меню. Указывать позицию не обязательно.</span>
												</div>
											</div>
											<div class='control-group'>
												<div class='controls'>
													<input type='submit' class='btn btn-primary' value='Сохранить изменения'/>
													<a class='rdajax btn btn-danger' href='?act=mainmenu&mode=delete&id=$id'>Удалить</a>
												</div>
											</div>
										</form>
								</div>";
			}
			$content .= "<div id='admin0' class='editor'><a href='javascript:' class='btn btn-small btn-block' onClick='toggle_adm(0);'>Добавить новый пункт</a><form method='post' id='form0' class='form-horizontal hide'><input type='hidden' name='act' value='mainmenu'><input type='hidden' name='mode' value='add'>
				<div class='control-group'>
					<label class='control-label' for='inputTitle0'>Название</label>
					<div class='controls'>
						<input class='input-block-level' id='inputTitle0' placeholder='Название ссылки' name='title' type='text'>
						<span class='help-inline'>Максимум - 255 символов.</span>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputurl0'>Ссылка</label>
					<div class='controls'>
						<input class='input-block-level' id='inputurl0' placeholder='Адрес' name='url' type='text'>
						<span class='help-block'>Максимум - 1024 символов.</span>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='inputpos0'>Позиция</label>
					<div class='controls'>
						<input class='input-block-level' id='inputpos0' placeholder='Позиция ссылки' name='pos' type='text'>
						<span class='help-block'>Чем меньше это значение - тем выше будет пункт меню. Указывать позицию не обязательно.</span>
					</div>
				</div>
				<div class='control-group'>
					<div class='controls'>
						<input type='submit' class='btn btn-primary' value='Сохранить изменения'/>
						<a class='rdajax btn btn-danger' href='?act=mainmenu&mode=delete&id=$id'>Удалить</a>
					</div>
				</div>
				</form>
		</div>";
			break;
	}
} else {
	$title="Ошибка!";
	$content="<script>$(document).ready(function () { alert('Доступ запрещён!','error'); rd_ajax('act=news'); });</script>"; 
}
?>