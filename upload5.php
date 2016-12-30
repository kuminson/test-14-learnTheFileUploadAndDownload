<?php
header('content-type:text/html;charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/upload_class.php';
$upload = new upload();
$dest = $upload -> uploadfile();
echo $dest;
?>