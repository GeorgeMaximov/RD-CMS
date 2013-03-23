<?
$id=$_REQUEST['id'];
if ($admin || $moder)
{
	if (isset($_POST['text'])){
		$text=$_POST['text'];
		$text=str_replace("\r\n","<br>",$text);
		$text=str_replace("\r","<br>",$text);
		$text=str_replace("\n","<br>",$text);
		$text=str_replace("onClick","data-click",$text);
		$text=strip_tags($text,"<b><i><u><s><br>");
		if (strlen($text)<5){
			$alert="alert('Слишком короткий текст комментария!','error')";
		} else {
			$sql=mysql_query("INSERT INTO `Comments`(`Text`,`Author`,`News_ID`) VALUES('$text','$user_id','$id')");
			$title="Добавление комментария";
			if ($sql){ 
					$alert="alert('Комментарий добавлен!','noerror')"; 
				} else { 
					$alert="alert('Ошибка при отправке комментария!','error')"; 
			}
		}
	}
	else if (isset($_REQUEST['del_id'])){
		$del_id=$_REQUEST['del_id'];
		$sql=mysql_query("DELETE FROM `Comments` WHERE `ID` = $del_id LIMIT 1");
		$title="Удаление комментария";
		if ($sql){ 
				$alert="alert('Комментарий удален!','noerror')"; 
			} else { 
				$alert="alert('Ошибка при отправке комментария!','error')"; 
			}
	}
	else
	{
		$alert="alert('$errs[7]','noerror')"; 
	}
}
else if ($user)
{
	if (isset($_POST['text'])){
		$text=$_POST['text'];
		$text=str_replace("\r\n","<br>",$text);
		$text=str_replace("\r","<br>",$text);
		$text=str_replace("\n","<br>",$text);
		$text=strip_tags($text,"<b><i><u>");
		if (strlen($text)<5){
			$alert="alert('Слишком короткий текст комментария!','error')";
		} else {
			$sql=mysql_query("INSERT INTO `Comments`(`Text`,`Author`,`News_ID`) VALUES('$text','$user_id','$id')");
			$title="Добавление комментария";
			if ($sql){ 
					$alert="alert('Комментарий добавлен!','noerror')"; 
				} else { 
					$alert="alert('Ошибка при отправке комментария!','error')"; 
			}
		}
	}
	else if (isset($_REQUEST['del_id'])){
		$alert="alert('$errs[0]','error')"; 
	}
}
else
{
	$alert="alert('$errs[0]','error')"; 
}
if ((isset($_REQUEST['ret'])) && isset($_REQUEST['retpart'])){
	$ret=htmlspecialchars($_REQUEST['ret']);
	$retp=htmlspecialchars($_REQUEST['retpart']);
	$redir="act=$ret&part=$retp";
} else {
	$redir="act=fulltext&id=$id";
}
$content.="<script>$(document).ready(function () { $alert; rd_ajax('$redir'); });</script>";
?>