<?
if (isset($_REQUEST['to'])){
	$title="Переадресация";
	$to=urldecode($_REQUEST['to']);
	$pto=parse_url($to);
	$quer=$pto['query'];
	$reds=is_link_outside($to) ? "location.href='$to';" : "rd_ajax('$quer');";
	$content.="<div class='alert'>Выполняется переадресация на адрес:<br>$to</div><script>alert('Выполняется переадресация на адрес:<br>$to');$reds</script>";
} else {
	$title="Ошибка";
	$content="<script>alert('Ссылка не найдена!','error');rd_ajax('act=news');</script>";
}
?>