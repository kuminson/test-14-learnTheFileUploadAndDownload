<?php
header("content-type:text/html; charset=utf-8");
$fileinfo = $_FILES["myfile"]; //上传文件信息
$maxsize = 2097152; //允许的最大值
$allowext = array('jpg','jpeg','gif','png','wbmp'); // 允许上传文件的类型
$flag = true; // 验证图片是否是真的图片
// 判断错误号
if($fileinfo["error"] == 0){
	// 判断文件大小
	if($fileinfo["size"] > $maxsize){
		exit("文件上传过大");
	}
	// $ext = strtolower(end(explode('.', $fileinfo["name"])));
	$ext = pathinfo($fileinfo['name'],PATHINFO_EXTENSION);
	// 判断文件类型
	if(!in_array($ext, $allowext)){
		exit("文件类型非法");
	}
	// 判断文件为http POST上传过来
	if(!is_uploaded_file($fileinfo["tmp_name"])){
		exit("文件不是通过http POST 方式上传上来的");
	}

	// 判定图片是否是真的
	if($flag){
		if(!getimagesize($fileinfo["tmp_name"])){
			exit("上传的文件不是真正的图片");
		}
	}

	$path = $_SERVER["DOCUMENT_ROOT"]."/file";
	// 判定不存在指定目录
	if(!file_exists($path)){
		// 创建目录
		mkdir($path,0777,true);
		chmod($path, 0777);
	}
	$uniname = md5(uniqid(microtime(true),true)).".".$ext;
	$destination = $path."/".$uniname;
	// 移动文件 @消除警告
	if(@move_uploaded_file($fileinfo["tmp_name"], $destination)){
		echo "文件上传成功";
	}else{
		echo "文件上传失败";
	}
}else{
	// 匹配错误信息
	switch ($fileinfo["error"]) {
		case 1:
			echo "上传文件超过了PHP配置文件中upload_max_filesize选项的值";
			break;
		case 2:
			echo "超过了表单max_file_size限制大小";
			break;
		case 3:
			echo "文件部分被上传";
			break;
		case 4:
			echo "没有选择上传文件";
			break;
		case 6:
			echo "没有找到临时目录";
			break;
		case 7:
		case 8:
			echo "系统错误";
			break;
	}
}
?>