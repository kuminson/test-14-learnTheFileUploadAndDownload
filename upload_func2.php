<?php

/*
 * 构建上传文件信息
 * @return array $files
 */
function getfiles(){
	$i = 0;
	foreach($_FILES as $file){
		if(is_string($file['name'])){
			$files[$i] = $file;
			$i++;
		}elseif(is_array($file['name'])){
			foreach($file['name'] as $key=>$val){
				$files[$i]['name'] = $file['name'][$key];
				$files[$i]['type'] = $file['type'][$key];
				$files[$i]['tmp_name'] = $file['tmp_name'][$key];
				$files[$i]['error'] = $file['error'][$key];
				$files[$i]['size'] = $file['size'][$key];
				$i++;
			}
		}
	}
	return $files;
}

/*
 * 针对单文件、多个单文件、多文件的上传
 * @param array $fileinfo
 * @param boole $flag
 * @param number $maxsize
 * @param array $allowext
 * @return array $res
 */
function uploadfile($fileinfo,$flag=true,$maxsize=1048576,$allowext=array('jpeg','jpg','gif','png')){
	// $allowext = array('jpeg','jpg','gif','png');
	// $flag = true;
	// $maxsize = 1048576;  //1M
	// 判断错误号
	if($fileinfo['error'] === UPLOAD_ERR_OK){
		// 检测上传文件大小
		if($fileinfo['size']>$maxsize){
			$res['mes'] = $fileinfo['name'].'上传文件过大';
		}
		// 检测上传文件类型
		$ext = getext($fileinfo['name']);
		if(!in_array($ext, $allowext)){
			$res['mes'] = $fileinfo['name'].'非法文件类型';
		}
		// 检测是否是真是的图片类型
		if($flag){
			if(!getimagesize($fileinfo['tmp_name'])){
				$res['mes'] = $fileinfo['name'].'不是真实的图片类型';
			}
		}
		// 检测文件是否是通过 http POST 上传过来的
		if(!is_uploaded_file($fileinfo['tmp_name'])){
			$res['mes'] = $fileinfo['name'].'文件不是通过http POST 方式上传上来的';
		}
		if(isset($res)){
			return $res;
		}
		// 移动上传文件到指定位置
		$path = $_SERVER['DOCUMENT_ROOT'].'/file';
		if(!file_exists($path)){
			mkdir($path,0777,true);
			chmod($path, 0777);
		}
		$uniname = getuniname();
		$destination = $path.'/'.$uniname.'.'.$ext;
		if(!move_uploaded_file($fileinfo['tmp_name'], $destination)){
			$res['mes'] = $fileinfo['name'].'文件移动失败';
		}

		$res['mes'] = $fileinfo['name'].'上传成功';
		$res['dest'] = $destination;

	}else{
		// 匹配错误信息
		switch ($fileinfo["error"]) {
			case 1:
				$res['mes'] = "上传文件超过了PHP配置文件中upload_max_filesize选项的值";
				break;
			case 2:
				$res['mes'] = "超过了表单max_file_size限制大小";
				break;
			case 3:
				$res['mes'] = "文件部分被上传";
				break;
			case 4:
				$res['mes'] = "没有选择上传文件";
				break;
			case 6:
				$res['mes'] = "没有找到临时目录";
				break;
			case 7:
			case 8:
				$res['mes'] = "系统错误";
				break;
		}
	}
	return $res;
}
?>