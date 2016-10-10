<?php
/**
 * 字典信息处理类
 *
 * @author wbqing405@sina.com
 */

class GetDictionary{
	public function __construct(){
		
	}
	
	/**
	 * 获取字典信息
	 */
	public function getDt($dName){
		$fileUrl = dirname(dirname(dirname(__FILE__))).'/conf/Dictionary/'.$dName.'.php';	
		if(file_exists($fileUrl)){
			$reArr = include($fileUrl);
			if(is_array($reArr)){
				return include($fileUrl);
			}			
		}	
	}
	
	/**
	 * 获取指定键值的字典信息
	 */
	public function getDtValue($dName,$value=NULL){
		$fileUrl = dirname(dirname(dirname(__FILE__))).'/conf/Dictionary/'.$dName.'.php';	
		if(file_exists($fileUrl)){
			$reArr = include($fileUrl);
			if(is_array($reArr)){
				foreach($reArr as $key=>$val){
					if(intval($key) == intval($value)){
						return $val;
					}
				}
			}			
		}	
	}
	
	/**
	 * 获取字典的选择
	 */
	public function getDtSelect($dName, $sValue=false, $option=false){
		$fileUrl = dirname(dirname(dirname(__FILE__))).'/conf/Dictionary/'.$dName.'.php';
		if(file_exists($fileUrl)){
			$reArr = include($fileUrl);
			$bStr = '<select name=\''.$dName.'\'>';
			if($option){
				$bStr = '<option value=\'0\'>='.Lang::get('PleaseSelect').'=</option>';
			}
			if(is_array($reArr)){
				foreach($reArr as $key=>$val){
					if(intval($sValue) == $key){
						$bStr .= '<option value=\''.$key.'\' selected>'.$val.'</option>';
					}else{
						$bStr .= '<option value=\''.$key.'\'>'.$val.'</option>';
					}
				}
			}
			
			$bStr .= '</select>';
			
			return $bStr;
		}	
	}
}