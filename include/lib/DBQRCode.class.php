<?php
/**
 * 
 * @author wbqing405@sina.com
 * 
 * 二维码生成处理
 *
 */
class DBQRCode{
	
	/**
	 ************二维码生成***********
	 * 
	 * @param $data ： url地址
	 * @param $errorCorrectionLevel : 错误级别
	 * @param $matrixPointSize ： 尺寸大小
	 */
	public function getQRCode($data, $level='L', $size=4, $savepic=false){
		$PNG_TEMP_DIR = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'QRCode'.DIRECTORY_SEPARATOR;	
		$PNG_WEB_DIR  = strtolower(__ROOT__).'/qrCode/get?code=';

		include dirname(dirname(__File__)).'/ext/phpqrcode/qrlib.php';

		if(!file_exists($PNG_TEMP_DIR)){
			mkdir($PNG_TEMP_DIR);
		}
		
		if($data == ''){
			$data = strtolower(__ROOT__);
		}
		
		$errorCorrectionLevel = 'L';
		if(!empty($level) && in_array($level, array('L','M','Q','H'))){
			$errorCorrectionLevel = $level;
		}
				
		$matrixPointSize = 4;
		if(!empty($size)){
			$matrixPointSize = min(max((int)$size, 1), 10);
		}
		
		if($savepic){
			$basename = md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize);
			
			$filename = $PNG_TEMP_DIR.$basename.'.png';
			
			if(!file_exists($filename)){
				QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);		
			}
			
			return $PNG_WEB_DIR.$basename;	
		}else{
			QRcode::png($data, false, $errorCorrectionLevel, $matrixPointSize, 2, true);
		}	
	}
	/**
	 * 删除二维码
	 */
	public function deleteQRCode($code){
		if($code){
			$img = dirname(dirname(dirname(__FILE__))).'/qrCode/'.$code.'.png';

			if (file_exists($img)) {
				$result=unlink ($img);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}