<?
$title="Сайт отключен";
if (trim($_disable_reason)==''){ $_disable_reason='Сайт отключен администратором!'; }
$content="<div class='alert alert-error'>$_disable_reason</div>";
?>