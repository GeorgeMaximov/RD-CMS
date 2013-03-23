<?
if ($admin || $moder){
	if (isset($_REQUEST['id'])){ $id=$_REQUEST['id']; $edit=true; } else { $edit=false; }
	$n_input='';
	if ($edit){
			$title="Редактирование новости";
			$sql=mysql_query("SELECT * FROM `News` WHERE `Num`=$id LIMIT 1");
			while ($row = mysql_fetch_assoc($sql)) {
				$n_title=$row['Title'];
				$n_title=str_replace('"','&quot;',$n_title);
				$n_title=str_replace("'",'&#39;',$n_title);
				$n_text=$row['Text'];
				$n_cat=$row['Category'];
				$n_preview=htmlspecialchars($row['Preview']);
				$n_tags=$row['Tags'];
				$n_text=str_replace("<br>","\r\n",$n_text);
				$n_text=str_replace("<br/>","\r\n",$n_text);
				$n_text=str_replace("&lt;","{<",$n_text);
				$n_text=str_replace("&gt;",">}",$n_text);
				$n_text=str_replace("<span class='code'>","<code>",$n_text);
				$n_text=str_replace("</span>","</code>",$n_text);
				$text=str_replace("&#39;","'",$text);
				$n_fixed=$row['Fixed']==1 ? " checked":"";
				$d_coms=$row['NoComments']==1 ? " checked":"";
				$d_page=$row['OnlyPage']==1 ? " checked":"";
				$d_replacern=$row['ReplaceRN']==1 ? " checked":"";
			}
			$n_input="<input type='hidden' name='num' value='$id'/>";
	} else {
		$title="Добавление новости";
		$n_fixed='';
		$d_coms='';
		$d_page='';
		$d_replacern=' checked';
	}
	$bbcodes=	"<div id='editor-codes'>".
				"<img src='$static_path/images/bb_codes/b.png' alt='b' data-title='Жирный текст'>".
				"<img src='$static_path/images/bb_codes/i.png' alt='i' data-title='Курсивный текст'>".
				"<img src='$static_path/images/bb_codes/u.png' alt='u' data-title='Подчеркнутый текст'>".
				"<img src='$static_path/images/bb_codes/s.png' alt='s' data-title='Зачеркнутый текст'>".
				"<img src='$static_path/images/bb_codes/hr.png' alt='hr' data-title='Разделитель'>".
				"<img src='$static_path/images/bb_codes/code.png' alt='code' data-title='Код'>".
				"<img src='$static_path/images/bb_codes/a.png' alt='a' data-title='Ссылка'>".
				"<img src='$static_path/images/bb_codes/img.png' alt='img' data-title='Изображение'>".
				"<img src='$static_path/images/bb_codes/spoiler.png' alt='spoiler' data-title='Спойлер'>".
				"</div>";
	if ($edit){
		$n_sbt="<input type='submit' class='btn' value='Отредактировать'/>";
	} else {
		$n_sbt="<input type='submit' class='btn' value='Добавить'/>";
	}
	$content="<form method='post'>
				<script>$(document).ready(function () { /*$('#uploader').load('/modules/img_uploader.php');*/ });</script>
				<div class='well'><h4 class='heading'>Редактор</h4>
					<table class='table table-striped'>
					<tr><td class='lefttd'>Заголовок</td>
						<td><input type='text' name='title' value='$n_title' onChange='get_tags(this.value);' class='input-block-level'/></td>
					</tr>
					<tr><td class='lefttd'>Текст новости</td>
						<td><div id='editor-container'>$bbcodes<textarea id='editor-area' cols='60' rows='15' name='text' class='input-block-level'>$n_text</textarea></div>
						<span class='help-block'>В тексте новости можно использовать HTML-теги.</span>
						</td>
					</tr>
					<tr><td class='lefttd'>Теги</td>
						<td><input type='text' name='tags' class='hundred' value='$n_tags' id='editor-tags' data-title='Теги позволяют объединять новости по определенным темам' class='input-block-level'/>
						<span class='help-block'>Теги вводятся через запятую.</span>
						</td>
					</tr>
					<tr><td class='lefttd'>Картинка</td>
						<td><input type='text' name='preview' class='hundred' value=\"$n_preview\"/>
						<span class='help-block'>Адрес мини-изображения для статьи.</span>
						</td>
					</tr>
					<tr><td class='lefttd'>Категория</td><td>
						<select name='category' class='input-block-level'>";
	foreach($cats as $id=>$ar){
		if ($id!=='*'){
			if ($id==$n_cat){$seld=" selected";} else {$seld="";}
			$content.="<option value='$id'$seld>". $ar['title'] .'</option>';
		}
	}

	$content.="					</select>
					</td></tr>
					<tr><td class='lefttd'>Дополнительно</td>
						<td>
							<label for='fix_news' class='checkbox'><input type='checkbox' name='fixed' id='fix_news'$n_fixed/> Закрепить новость на первой странице</label>
							<label for='dis_comments' class='checkbox'><input type='checkbox' name='nocoms' id='dis_comments'$d_coms/> Запретить комментирование новости</label>
							<label for='dis_page' class='checkbox'><input type='checkbox' name='nopage' id='dis_page'$d_page/> Убрать строки информации</label>";
	if ($admin){$content.="<label for='replace_rn' class='checkbox'><input type='checkbox' name='replacern' id='replace_rn'$d_replacern/> Заменять переводы строк тегом &lt;br&gt;</label>";}
	$content.=	"		</td></tr>
						<tr><td class='lefttd'>$n_input<input type='hidden' value='submit' name='act'></td>
							<td>$n_sbt</td>
						</tr>
						</table>
						</div>";
	$content.="	<div class='well'><h4 class='heading'>Загрузка изображений</h4>
					<div id='uploader'>
						<iframe src='/modules/img_uploader.php' style='border:0; width:100%;height:300px;'></iframe>
					</div>
				</div>";
	$content.="</form>";
}
else
{
	$title="Ошибка!";
	$content="<script>$(document).ready(function () { alert('$errs[0]','error'); });</script>"; 
}
?>
