<?
if ($admin){
	$title="Управление категориями";
	$iter=0;
	$header="<script>function toggle_adm(id){
	$(\"#form\"+id).slideToggle(\"slow\");
	$(\"#admin\"+id).toggleClass(\"editor_show\");
	}
	</script>
	<div class='well'><h4 class='heading'>Управление категориями</h4>";
	$mode="";
	if (isset($_REQUEST['mode'])){ $mode=$_REQUEST['mode']; }
	switch ($mode){
			case "edit":
				$id=$_REQUEST['id'];
				$titl=htmlspecialchars($_REQUEST['title']);
				$css=$_REQUEST['css'];
				$hide=isset($_REQUEST['hide']) ? 1 : 0;
				$sql=mysql_query("UPDATE `Categories` SET `Title` = '$titl',  `Stylesheet` =  '$css', `HideMain`=$hide WHERE  `ID` = '$id' LIMIT 1;");
				$alert=($sql) ? "alert('Категория изменена!','noerror');" : "alert('Ошибка при изменении категории!','error');";
				$content.="<script>$(document).ready(function () { rd_ajax('act=categories');$alert });</script>";
			break;
			case "delete":
				$id=$_REQUEST['id'];
				if (($id=='0') || ($id=='1')){
					$alert="alert('Невозможно удалить стандартную категорию!','error');";
				} else {
					$sql=mysql_query("DELETE FROM `Categories` WHERE  `ID` = '$id' LIMIT 1;");
					$alert=($sql) ? "alert('Категория удалена!','noerror');" : "alert('Ошибка при удалении категории!','error');";
				}
				$content.="<script>$(document).ready(function () { rd_ajax('act=categories');$alert });</script>";
			break;
			case "add":
				$titl=htmlspecialchars($_REQUEST['title']);
				$css=$_REQUEST['css'];
				$hide=isset($_REQUEST['hide']) ? 1 : 0;
				$sql=mysql_query("INSERT INTO `Categories` (`Title`, `Stylesheet`, `HideMain`) VALUES('$titl', '$css', $hide)");
				$alert=($sql) ? "alert('Категория добавлена!','noerror');" : "alert('Ошибка при добавлении категории!','error');";
				$content.="<script>$(document).ready(function () { rd_ajax('act=categories');$alert });</script>";
			break;
			default:	
			$sql=mysql_query("SELECT * FROM `Categories` ORDER BY `ID` ASC");
			$content=$header;
			while ($row = mysql_fetch_assoc($sql)) {
				$iter+=1;
				$id=$row['ID'];
				$titl=htmlspecialchars($row['Title']);
				$css=$row['Stylesheet'];
				$hide=($row['HideMain']==1) ? " checked='checked'" : '';
				$content .= "	<div id='admin$iter' class='editor'><a href='javascript:' onClick='toggle_adm($iter);' class='btn btn-link'>$id - $titl</a>
										<form method='post' id='form$iter' class='form-horizontal hide'>
											<input type='hidden' name='act' value='categories'><input type='hidden' name='id' value='$id'><input type='hidden' name='mode' value='edit'>
											<div class='control-group'>
												<label class='control-label' for='inputTitle$iter'>Название</label>
												<div class='controls'>
													<input class='input-block-level' id='inputTitle$iter' placeholder='Название категории' name='title' type='text' value='$titl'>
													<span class='help-inline'>Максимум - 255 символов.</span>
												</div>
											</div>
											<div class='control-group'>
												<label class='control-label' for='inputCss$iter'>Таблица стилей</label>
												<div class='controls'>
													<textarea class='input-block-level' name='css' class='hundred' rows='5' cols='50' id='inputCss$iter'>$css</textarea>
													<span class='help-block'>Максимум - 2048 символов.<br>Можно использовать внешние CSS-файлы.<br>Для подключения файла из директории статических файлов используйте переменную <code>[static]</code>.</span>
												</div>
											</div>
											<div class='control-group'>
												<label class='control-label' for='inputCss$iter'>Дополнительно</label>
												<div class='controls'>
													<label class='checkbox'><input type='checkbox' name='hide'$hide> Скрыть с главной страницы</label>
												</div>
											</div>
											<div class='control-group'>
												<label class='control-label'>Ссылка</label>
												<div class='controls'>
													<span class='help-block'>Ссылка на категорию: <code>?act=news&cat=$id</code><br>Вы можете использовать эту ссылку, например, в шаблоне сайта.</span>
												</div>
											</div>
											<div class='control-group'>
												<div class='controls'>
													<input type='submit' class='btn btn-primary' value='Сохранить изменения'/>
													<a class='rdajax btn btn-danger' href='?act=categories&mode=delete&id=$id'>Удалить</a>
												</div>
											</div>
										</form>
								</div>";
			}
			$content .= "<div id='admin0' class='editor'><a href='javascript:' class='btn btn-small btn-block' onClick='toggle_adm(0);'>Добавить категорию</a><form method='post' id='form0' class='form-horizontal hide'><input type='hidden' name='act' value='categories'><input type='hidden' name='mode' value='add'>
					<div class='control-group'>
						<label class='control-label' for='inputTitle0'>Название</label>
						<div class='controls'>
							<input class='input-block-level' id='inputTitle0' placeholder='Название категории' name='title' type='text'>
							<span class='help-inline'>Максимум - 255 символов.</span>
						</div>
					</div>
					<div class='control-group'>
						<label class='control-label' for='inputCss0'>Таблица стилей</label>
						<div class='controls'>
							<textarea class='input-block-level' name='css' class='hundred' rows='5' cols='50' id='inputCss0'></textarea>
							<span class='help-block'>Максимум - 2048 символов.<br>Можно использовать внешние CSS-файлы.<br>Для подключения файла из директории статических файлов используйте переменную <code>[static]</code>.</span>
						</div>
					</div>
					<div class='control-group'>
						<label class='control-label' for='inputCss$iter'>Дополнительно</label>
						<div class='controls'>
							<label class='checkbox'><input type='checkbox' name='hide'> Скрыть с главной страницы</label>
						</div>
					</div>
					<div class='control-group'>
						<div class='controls'>
							<input type='submit' class='btn btn-primary' value='Добавить'/>
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