<?php
// $fileinfo = $_FILES["myfile"]; // 缓存上传文件信息

function uploadfile($fileinfo,$uploadpath,$allowext= array("jpg","jpeg","png","gif"),$maxsize = 2097152,$flag=true){
	// 判断错误号
	if($fileinfo["error"] > 0){
		// 匹配错误信息
		switch ($fileinfo["error"]) {
			case 1:
				$mes = "上传文件超过了PHP配置文件中upload_max_filesize选项的值";
				break;
			case 2:
				$mes = "超过了表单max_file_size限制大小";
				break;
			case 3:
				$mes = "文件部分被上传";
				break;
			case 4:
				$mes = "没有选择上传文件";
				break;
			case 6:
				$mes = "没有找到临时目录";
				break;
			case 7:
			case 8:
				$mes = "系统错误";
				break;
		}
		exit($mes);
	}

	// 检测上传文件类型
	$ext = pathinfo($fileinfo["name"],PATHINFO_EXTENSION);
	if(!in_array($ext, $allowext)){
		exit("非法文件类型");
	}

	// 检测上传文件大小不超过设定值
	if($fileinfo["size"] > $maxsize){
		exit("上传文件过大");
	}

	// 判定上传图片为真的图片
	if($flag){
		if(!getimagesize($fileinfo["tmp_name"])){
			exit("不是真实的图片类型");
		}
	}

	// 检测上传文件是通过http POST传上来的
	if(!is_uploaded_file($fileinfo["tmp_name"])){
		exit("文件不是通过HTTP POST 方式上传上来的");
	}

	// 判断文件夹位置不存在
	if(!file_exists($uploadpath)){
		// 创建文件夹
		mkdir($uploadpath,0777,true);
		chmod($uploadpath, 0777);
	}
	// 生成唯一名字
	$uniname = md5(uniqid(microtime(true),true)).'.'.$ext;
	$destination = $uploadpath."/".$uniname;
	// 移动上传文件
	if(!@move_uploaded_file($fileinfo["tmp_name"], $destination)){
		exit("文件移动失败");
	}

	// echo "文件上传成功";
	return $destination;
	
}
?>