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
		}
		return true;
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

	public function uploadfile(){
		if($this->checkerror()&&$this->checksize()&&){

		}else{
			$this->showerror();
		}
	}
}
?>