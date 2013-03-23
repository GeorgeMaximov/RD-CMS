<?
$redir_to="act=news";
if ($admin || $moder)
{
	if (isset($_POST['text'])){
		$category=$_POST['category'];
		
		$text=$_POST['text'];
		$d_replacern=isset($_REQUEST['replacern']) ? 1 : 0;
		if ($d_replacern && $admin){
			$text=str_replace("\r\n","<br/>",$text);
			$text=str_replace("\r","<br/>",$text);
			$text=str_replace("\n","<br/>",$text);
		}
		
		$text=str_replace("{<","&lt;",$text);
		$text=str_replace(">}","&gt;",$text);
		$text=str_replace("<code>","<span class='code'>",$text);
		$text=str_replace("</code>","</span>",$text);

		if (!$admin){
			$text=str_replace("<iframe","< iframe",$text);
			$text=str_replace("<frame","< frame",$text);
			$text=str_replace("<scri","< scri",$text);
			$text=str_replace("<styl","< styl",$text);
		}
		$text=str_replace("'","&#39;",$text);
		
		$title=strip_tags($_POST['title']);
		
		$preview=$_POST['preview'];
		$preview=strip_tags($preview,"<a><img>");
		$preview=str_replace("onClick","data:click",$preview);
		
		$tags=strip_tags($_POST['tags']);
		$fixed=isset($_REQUEST['fixed']) ? 1 : 0;
		$d_coms=isset($_REQUEST['nocoms']) ? 1 : 0;
		$d_page=isset($_REQUEST['nopage']) ? 1 : 0;
		$action="";
		
		if (isset($_REQUEST['num'])){
			$id=$_REQUEST['num'];
			$sql=mysql_query("UPDATE `News` SET `Title` = '$title', `Text` = '$text', `Category` = '$category', `Tags` = '$tags', `Preview` = '$preview', `Fixed` = '$fixed', `NoComments` = '$d_coms', `OnlyPage` = '$d_page', `ReplaceRN`='$d_replacern' WHERE  `Num` = $id LIMIT 1 ;");
			$success="обновлена";
		} else {
			$sql=mysql_query("INSERT INTO `News`(`Title`,`Text`,`Category`,`Tags`,`Preview`,`Author`,`Fixed`,`NoComments`,`OnlyPage`,`ReplaceRN`) VALUES('$title','$text','$category','$tags','$preview','$u_id','$fixed','$d_coms','$d_page','$d_replacern')");
			$sql1=mysql_query("SELECT `Num` FROM `News` ORDER BY `Num` DESC LIMIT 1");
			$row = mysql_fetch_assoc($sql1);
			$id=$row['Num'];
			$success="добавлена";
			$action="news_posted=1;";
		}
		$title="Добавление новости";
		if ($sql){ 
				$redir_to="act=fulltext&id=$id";
				$alert="alert('Новость успешно $success!','noerror'); $action"; 
			} else { 
				$alert="alert('Ошибка при добавлении новости!','error')"; 
			}
	}
	else
	{
		$alert="alert('Нет требуемых параметров!','error')"; 
	}
}
else
{
	$alert="alert('$errs[0]','error')"; 
}
$content.="<script>$(document).ready(function () { $alert; rd_ajax('$redir_to');});</script>";
?>
