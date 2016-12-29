<?php
header("content-type:text/html; charset=utf-8");
echo "<pre>";
print_r($_FILES);

$filename = $_FILES["myfile"]["name"];
$type = $_FILES["myfile"]["type"];
$tmp_name = $_FILES["myfile"]["tmp_name"];
$error = $_FILES["myfile"]["error"];
$size = $_FILES["myfile"]["size"];

// move_uploaded_file($tmp_name,$_SERVER["DOCUMENT_ROOT"]."/file/".$filename);
copy($tmp_name, $_SERVER["DOCUMENT_ROOT"]."/file/".$filename);
?>