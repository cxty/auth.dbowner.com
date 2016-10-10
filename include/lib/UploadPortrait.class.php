<?php
/**
  * 头像上传处理类
  */
class UploadPortrait{
	function UploadPortrait($uploaddir='upload',$userfile='userfile'){
		$this->uploaddir = $uploaddir.'/';
		$this->userfile  = $userfile;

		$this->create_folders();
	}
	
	/**
	  * 文件夹是否存在，不存在则创建
	  */
	public function create_folders(){
        if(!is_dir($this->uploaddir)){
            mkdir($this->uploaddir);
        }       
    }
	
	/**
	  * 上传原始图片,成功返回图片名称
	  */
	public function uploadOrigin(){
		$picname = explode('.' , basename($_FILES[$this->userfile]['name'])); 
		$filename = date('YmdHis') . '.' . end($picname);
		$uploadfile = $this->uploaddir . $filename;
		
		if(move_uploaded_file($_FILES[$this->userfile]['tmp_name'], $uploadfile)){
			return $filename;				
		}else{
			return false;
		}
	}
	
	/**
	  * 剪裁图片,并保存为jpg格式
	  * @params $targ_Pic 需要剪裁的图片
	  * @params $targ_arr 需要剪裁的图片四个角
	  * @params $targ_w 图片宽
	  * @params $targ_h 图片高
	  * @params $jpeg_quality 图片质量
	  */
	 public function cutFixedPic($targ_Pic,$targ_arr,$targ_w=150,$targ_h=150,$jpeg_quality=100){
		$this->targ_w       = $targ_w; 
		$this->targ_h       = $targ_h;
		$this->jpeg_quality = $jpeg_quality;
		$this->targ_Pic     = $targ_Pic;

		$this->xlen = $targ_arr['xlen'];
		$this->ylen = $targ_arr['ylen'];
		$this->wlen = $targ_arr['wlen'];
		$this->hlen = $targ_arr['hlen'];
		//echo $this->uploaddir.'fixe'.$this->targ_Pic;
		//echo '<br>';
		//var_dump($targ_arr);exit;
		$img_r  = imagecreatefromjpeg($this->targ_Pic);  
		//$this->targ_w = imagesx($img_r );   
		//$this->targ_h = imagesy($img_r );   
		
		$dst_r = ImageCreateTrueColor( $this->targ_w, $this->targ_h );
		
		imagecopyresampled($dst_r,$img_r,0,0,$this->xlen,$this->ylen,$this->targ_w,$this->targ_h,$this->wlen,$this->hlen);
		
		header('Content-type: image/jpeg');
		//imagejpeg($dst_r,null,$this->jpeg_quality);
		
		//imagejpeg($dst_r,'upload_pic/'."$timestamp.jpg");
		
		$newFileName = 'fixed'.basename($this->targ_Pic);
		imagejpeg($dst_r,$this->uploaddir.$newFileName,$this->jpeg_quality);
		imagedestroy($dst_r);
		
		//return $newFileName;
		
//	$targ_w = $targ_h = 150;
//	$jpeg_quality = 100;
//	
//	$src = $_GET['bigImage'];
//	$img_r = imagecreatefromjpeg($src);
//	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
//	
//	imagecopyresampled($dst_r,$img_r,0,0,$_GET['xlen'],$_GET['ylen'],$targ_w,$targ_h,$_GET['wlen'],$_GET['hlen']);
//	
//	header('Content-type: image/jpeg');
//	imagejpeg($dst_r,null,$jpeg_quality);
//	$timestamp = time();
//	//$target ='upload_pic/';
//	imagejpeg($dst_r,'upload_pic/'."$timestamp.jpg");
//	imagedestroy($dst_r);
	 }
	 
	 /**
	  * 其他处理图片方法待建
	  */
	public  function cutOtherPic(){
		// 	// 剪裁
// 	$source=imagecreatefromjpeg($src_img);
// 	$croped=imagecreatetruecolor($w, $h);
// 	imagecopy($croped,$source,0,0,$x,$y,$src_w,$src_h);
// 	// 缩放
// 	$scale = $dst_w/$w;
// 	$target = imagecreatetruecolor($dst_w, $dst_h);
// 	$final_w = intval($w*$scale);
// 	$final_h = intval($h*$scale);
// 	imagecopyresampled($target,$croped,0,0,0,0,$final_w,$final_h,$w,$h);	 
	 }
}
?>