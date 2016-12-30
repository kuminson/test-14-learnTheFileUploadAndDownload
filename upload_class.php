<?php
class upload{
	protected $filename;
	protected $maxsize;
	protected $allowmime;
	protected $allowext;
	protected $uploadpath;
	protected $imgflag;
	protected $fileinfo;
	protected $error;
	protected $ext;
	protected $destination;
	protected $uniname;

	/*
	 *
	 * @param string $filename
	 * @param string $uploadpath
	 * @param string $imgflag
	 * @param number $maxsize
	 * @param array $allowext
	 * @param array $allowmime
	 */
	public function __construct($filename='myfile',$uploadpath='./file',$imgflag=true,$maxsize=5242880,$allowext=array('jpeg','jpg','png','gif'),$allowmime=array('image/jpeg','image/png','image/gif')){
		$this->filename = $filename;
		$this->maxsize = $maxsize;
		$this->allowmime = $allowmime;
		$this->allowext = $allowext;
		$this->uploadpath = $uploadpath;
		$this->imgflag = $imgflag;
		$this->fileinfo = $_FILES[$this->filename];
	}

	/*
	 * 检测上传文件是否出错
	 * @return boolean
	 */
	protected function checkerror(){
		if(is_null($this->fileinfo)){
			$this->error = '文件上传出错';
			return false;
		}
		if($this->fileinfo['error']>0){
			switch ($this->fileinfo['error']){
				case 1:
					$this->error = '超过了php配置文件中upload_max_filesize选项的值';
					break;
				case 2:
					$this->error = '超过了表单中MAX_FILE_SIZE设置的值';
					break;
				case 3:
					$this->error = '文件部分被上传';
					break;
				case 4:
					$this->error = '没有选择上传文件';
					break;
				case 6:
					$this->error = '没有找到临时目录';
					break;
				case 7:
					$this->error = '文件不可写';
					break;
				case 8:
					$this->error = '由于PHP的扩展程序中断文件上传';
					break;
			}
			return false;
		}else{
			return true;
		}
	}

	/*
	 * 检测上传文件的大小
	 * @return boolean
	 */
	protected function checksize(){
		if($this->fileinfo['size']>$this->maxsize){
			$this->error = '上传文件过大';
			return false;
		}
		return true;
	}

	/*
	 * 检测扩展名
	 * @return boolean
	 */
	protected function checkext(){
		$this->ext = strtolower(pathinfo($this->fileinfo['name'],PATHINFO_EXTENSION));
		if(!in_array($this->ext,$this->allowext)){
			$this->error = '不允许的扩展名';
			return false;
		}
		return true;
	}

	/*
	 * 检测文件类型
	 * @return boolean
	 */
	protected function checkmime(){
		if(!in_array($this->fileinfo['type'],$this->allowmime)){
			$this -> error = '不允许的文件类型';
			return false;
		}
		return true;
	}

	/*
	 * 检测是否是真实图片
	 * return boolean
	 */
	protected function checktrueimg(){
		if($this->imgflag){
			if(!@getimagesize($this->fileinfo['tmp_name'])){
				$this->error = '不是真实图片';
				return false;
			}
			return true;
		}
	}

	/*
	 * 检测文件是否是通过http POST方式上传的
	 * return boolean
	 */
	protected function checkhttppost(){
		if(!is_uploaded_file($this->fileinfo['tmp_name'])){
			$this->error = '文件不是通过HTTP POST方式上传上来的';
			return false;
		}
		return true;
	}

	/*
	 * 显示错误
	 */
	protected function showerror(){
		exit('<span style="color:red">'.$this->error.'</span>');
	}

	/*
	 * 检测目录不存在则创建目录
	 */
	protected function checkuploadpath(){
		if(!file_exists($this->uploadpath)){
			mkdir($this->uploadpath,0777,true);
		}
	}

	/*
	 * 生产唯一字符串
	 * @return string
	 */
	protected function getuniname(){
		return md5(uniqid(microtime(true),true));
	}

	/*
	 * 上传文件
	 * @return string
	 */
	public function uploadfile(){
		if($this->checkerror() && $this->checksize() && $this->checkext() && $this->checkmime() && $this->checktrueimg() && $this->checkhttppost()){
			// 目录是否存在否则创建
			$this->checkuploadpath();
			$this->uniname = $this->getuniname();
			$this->destination = $this->uploadpath.'/'.$this->uniname.'.'.$this->ext;
			// 移动文件
			if(@move_uploaded_file($this->fileinfo['tmp_name'], $this->destination)){
				return $this->destination;
			}else{
				$this -> error = '文件移动失败';
				$this -> showerror();
			}
		}else{
			$this->showerror();
		}
	}
}
?>