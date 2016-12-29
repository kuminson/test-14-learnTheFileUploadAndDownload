<?php
	header("content-type:text/html; charset=utf-8");
	include_once $_SERVER["DOCUMENT_ROOT"].'/upload_func.php';
	$fileinfo = $_FILES["myfile"];
	$uploadpath = $_SERVER["DOCUMENT_ROOT"].'/file';
	$newname = uploadfile($fileinfo,$uploadpath);
	echo $newname;
?>