<?
require("../include/settings.php");
@session_start();
$upload_dir=$_home_dir."/uploader/";
$f=$_REQUEST['f'];
$f=str_replace('/','_',$f);
$f=str_replace('akibaranger-rus_','akibaranger-rus/',$f);
$f_normal=preg_replace("/(_)[0-9A-Fa-f]{13}(.)(.*)/", '.$3', $f);
$get=$_REQUEST['get'];
$file=$upload_dir.$f;
$sid=substr(md5(session_id()),0,5);
$uid=substr($_REQUEST['sid'],0,5);
if (!file_exists($file)){ header("HTTP/1.1 404 Not Found"); $_REQUEST['error']='404'; include($_home_dir.'/include/error.php');  die(); }

function getExtension($filename) {
	$path_info = pathinfo($filename);
	return $path_info['extension'];
}

$ext=strtolower(getExtension($file));
$type="";
$size=filesize($file);
$sizeKB=(int)($size/1024);
$link='http://'.$_SERVER['HTTP_HOST'].'/d/'.$f;
switch ($ext){
	case "png":	$mime='image/png';$type='image'; break;
	case "jpg":	$mime='image/jpeg';$type='image'; break;
	case "bmp":	$mime='image/bmp';$type='image'; break;
	case "jpeg":$mime='image/jpeg';$type='image'; break;
	case "gif":	$mime='image/gif';$type='image'; break;
	case "mp3":	$mime='audio/mpeg';$type='audio'; break;
	case "ass":	$mime='application/octet-stream';$type='sub'; break;
	case "ssa":	$mime='application/octet-stream';$type='sub'; break;
	case "sub":	$mime='application/octet-stream';$type='sub'; break;
	case "srt":	$mime='application/octet-stream';$type='sub'; break;
	case "txt":	$mime='text/plain'; break;
	case "pdf":	$mime='application/pdf'; break;
	case "php":	$mime='text/plain';$type='local'; break;
	default:	$mime='application/octet-stream'; break;
}

if ($get=="1"){
	if ($uid==$sid){
		header("Content-Transfer-Encoding: binary"); 
		header("Content-Length: $size;"); 
		header("Content-Type: $mime; "); 
		header("Content-Disposition: attachment; filename=\"".$f_normal."\"; ");
		ob_clean();
		flush();
		readfile($file);
	} else {
		header("Location: /index.php?act=download&f=$f");
	}
} else if (($get=="2") or ($get=="admin")){
	if (($uid==$sid) or ($get=="admin")){
		header("Content-Transfer-Encoding: binary"); 
		header("Content-Length: $size;"); 
		header("Content-Type: $mime; "); 
		ob_clean();
		flush();
		readfile($file);
	} else {
		header("Location: /index.php?act=download&f=$f");
	}
} else if ((($type=="image") || ($type=="sub")) and ($get=="3")){
		//header('Content-Type: $mime; charset=ISO-8859-1'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Content-Length: $size;"); 
		header("Content-Type: $mime; ");
		ob_clean();
		flush();
		readfile($file);
} else {
	header("Location: /index.php?act=download&f=$f");
}
?>