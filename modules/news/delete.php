<?
if ($admin || $moder)
{
	if (isset($_REQUEST['id'])){
		$title="Удаление новости";
		$id=$_REQUEST['id'];
		$sql=mysql_query("DELETE FROM `News` WHERE `Num` = $id LIMIT 1");
		if ($sql){
			$sql=mysql_query("DELETE FROM `Comments` WHERE `News_ID` = $id");
			$alert=$sql ? "alert('Новость и комментарии к ней удалены!','noerror');" : "alert('Новость удалена, ошибка при удалении комментариев!','error');";
		} else {
			$alert="alert('Новость не удалена!','error');";
		}
	}
	else
	{
		$alert="alert('Недостаточно параметров для удаления новости!','error');"; 
	}
}
else
{
	$alert="alert('$errs[0]','error');"; 
}
$part=htmlspecialchars($_REQUEST['part']);
if ($_REQUEST['return']!=""){
	$addit="&".str_replace("*","=",htmlspecialchars($_REQUEST['return']));
}
$content.="<script>$(document).ready(function () { $alert; rd_ajax('act=news&part=$part$addit'); });</script>";
?>