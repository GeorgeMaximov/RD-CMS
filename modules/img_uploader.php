<?
require('../include/settings.php');
require('../modules/engine/init.php');
if (!($admin || $moder)) die('Access denied!');
$width=$_preview_image[0];
$height=$_preview_image[1];
$s_width=$_preview_image[0];
$s_height=$_preview_image[1];
header('Content-type: text/html; charset=utf-8');
if (isset($_FILES['file'])){
	$uploadDirectory=$_home_dir.'/uploader/';
	if (($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/pjpeg")
	|| ($_FILES["file"]["type"] == "image/png"))
	  {
	if ($_FILES["file"]["error"] > 0){
		echo "Ошибка: " . $_FILES["file"]["error"] . "<br />";
	} else {
		echo "Имя: " . $_FILES["file"]["name"] . "<br />";
		echo "Размер: " . ceil($_FILES["file"]["size"] / 1024) . " КБ<br />";

			$pathinfo = pathinfo($_FILES["file"]["name"]);
			$fn = substr(htmlspecialchars($pathinfo['filename']),0,5) . '_'. substr(md5(time()),0,5);
			preg_match_all('/[A-Za-z0-9\_\-]/s', $fn, $cleaned);
			foreach($cleaned[0] as $k=>$v) {
				$ready .= $v;
			}
			$filename=$ready;

			$ext = $pathinfo['extension'];
		        while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(100, 999);
            }
			$full_img=$uploadDirectory . $filename . '.' . $ext;
			//move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDirectory . $filename . '.' . $ext);
			
			### creating thumbnail
			$type = strtolower($ext);
			if($type == 'jpeg') $type = 'jpg';
			switch($type){
				case 'gif': $im = imagecreatefromgif($_FILES["file"]["tmp_name"]); break;
				case 'jpg': $im = imagecreatefromjpeg($_FILES["file"]["tmp_name"]); break;
				case 'png': $im = imagecreatefrompng($_FILES["file"]["tmp_name"]); break;
				default : $err_i="Тип изображения не поддерживается!";
			}
			
			if(!list($w, $h) = getimagesize($_FILES["file"]["tmp_name"])) $err_i="Тип изображения не поддерживается!";
			
			if($w < $width and $h < $height) $err_l=1;
			$ratio = min($width/$w, $height/$h);
			$width = $w * $ratio;
			$height = $h * $ratio;
			$x = 0;

			$new=imagecreatetruecolor($width,$height);
			
			if($type == "gif" or $type == "png"){
				imagealphablending($new, false);
				imagesavealpha($new,true);
				imagecolortransparent($new, imagecolorallocatealpha($new, 255, 255, 255, 127));
				imagefilledrectangle($new, 0, 0, $w, $h, imagecolorallocatealpha($new, 255, 255, 255, 127));
			}
						
			imagecopyresampled($new,$im,0,0,0,0,$width,$height,imagesx($im),imagesy($im));
			
			if (isset($_REQUEST['watermark'])){
				####### Text on image
				$white = imagecolorallocate($im, 255, 255, 255);
				$grey = imagecolorallocate($im, 128, 128, 128);
				$black = imagecolorallocate($im, 0, 0, 0);
				
				$text1=$_watermark_text[0];
				$text2=$_watermark_text[1];
				$font='../static/media/visitor.ttf';
				
				$pos1=imagettfbbox(12,0,$font,$text1);
				$pos2=imagettfbbox(12,0,$font,$text2);
				
				imagettftext($im, 12, 0, $w-$pos1[2]/2-100, $h-$pos1[3]-30, $black, $font, $text1);
				imagettftext($im, 12, 0, $w-$pos1[2]/2-101, $h-$pos1[3]-31, $white, $font, $text1);
				
				imagettftext($im, 12, 0, $w-$pos2[2]/2-100, $h-$pos2[3]-10, $black, $font, $text2);
				imagettftext($im, 12, 0, $w-$pos2[2]/2-101, $h-$pos2[3]-11, $white, $font, $text2);
				######
			}
			
			if (!isset($err_i)){
				imagejpeg($im,$uploadDirectory . $filename . '.jpg',80);
				//$f_l="http://".$_SERVER['HTTP_HOST'].'/uploader/'.$filename . '.' . $ext;
				$f_l='[attach]'.$filename . '.jpg';
				if (isset($_REQUEST['preview'])){
					if (isset($err_l)){
						echo "Невозможно создать превью, т.к. изображение слишком маленькое!<br>";
					} else {
						imagejpeg($new,$uploadDirectory . $filename . '_s.jpg',50);
						//$p_l="http://".$_SERVER['HTTP_HOST'].'/uploader/'.$filename . '_s.' . $ext;
						$p_l='[attach]'.$filename . '_s.jpg';
						echo "<br><b>Превью</b><br>Ссылка: <input onClick='this.focus(); this.select();' type='text' value=\"$p_l\">";
						echo "<br>HTML (увеличение): <input onClick='this.focus(); this.select();' type='text' value=\"&lt;a href='$f_l'&gt;&lt;img src='$p_l'&gt;&lt;/a&gt;\">";
					}
				}
				echo "<br><b>Полное изображение</b><br>Ссылка: <input onClick='this.focus(); this.select();' type='text' value=\"$f_l\">";
				echo "<br>Код: <input onClick='this.focus(); this.select();' type='text' value=\"&lt;img src='$f_l'&gt;\"><hr>";
			} else {
				echo "<br><b>Ошибка: $err_i</b><hr>";
			}
			imagedestroy($im);
			imagedestroy($new);
		}
	  }
	else
	  {
	  echo "Файл не поддерживается!";
	  }
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<style>
		html,body{background:transparent; color:#000;font-family:sans-serif}
	</style>
	<title>Images Uploader</title>
</head>
<body>

<form method="post" enctype="multipart/form-data">
<label for="file">Имя:</label>
<input type="file" name="file" id="file" /><br />
<label for="watermark">Выводить адрес сайта</label>
<input type="checkbox" name="watermark" id="watermark" checked/><br>
<label for="preview">Создать превью</label>
<input type="checkbox" name="preview" id="preview" checked/>
<br />
<input type="submit" name="submit" value="Загрузить" />
<br />
Поддерживаются следующие типы: gif, jpg, jpeg, png.<br />
Максимальные размеры превью: <? echo "$s_width x $s_height"; ?>
</form>

</body>
</html>