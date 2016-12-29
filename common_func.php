<?php
/*
 * 得到文件扩展名
 * @param string $filename
 * @return string
 */
function getext($filename){
	return strtolower(pathinfo($filename,PATHINFO_EXTENSION));
}

/*
 * 产生唯一字符串
 * @return string
 */
function getuniname(){
	return md5(uniqid(microtime(true),true));
}
?>