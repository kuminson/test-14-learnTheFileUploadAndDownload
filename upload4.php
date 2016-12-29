<?php
header("content-type:text/html; charset=utf-8");
require_once $_SERVER["DOCUMENT_ROOT"]."/upload_func2.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/common_func.php';
$files = getfiles();

foreach ($files as $fileinfo) {
	$res = uploadfile($fileinfo);
	echo $res['mes'].'<br/>';
	if(array_key_exists('dest', $res)){
		$uploadfiles[]=$res['dest'];
	}
}
// echo "<pre>";
if(isset($uploadfiles)){
	$uploadfiles = array_values(array_filter($uploadfiles));
	print_r($uploadfiles);
}
?>