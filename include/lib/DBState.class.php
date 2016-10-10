<?php
/**
 * 国家、省份、市区选择
 *
 * @author wbqing405@sina.com
 */

class DBState{
	/**
	 * 取国家json文件
	 */
	public static function getStateJson(){
		return file_get_contents(dirname(dirname(dirname(__FILE__))).'/conf/state.json');
	}
	
	/**
	 * 取城市json文件
	 */
	public static function getCityJson(){
		return file_get_contents(dirname(dirname(dirname(__FILE__))).'/conf/city_zh.json');
	}
	/**
	 * 通过代号取国家名
	 */
	public static function getStateName($code){
		$json = json_decode(self::getStateJson(), true);
		$_rb = '';
		if(is_array($json)){
			foreach($json as $key=>$val){
				if(intval($code) == $val['code']){
					$_rb = $val['name'];
					break;
				}
			}
		}
		return $_rb;
	}
	/**
	 * 通过代号取省份名
	 */
	public static function getProviceName($code){
		$json = json_decode(self::getCityJson(), true);
		$_rb = '';
		if(is_array($json)){
			foreach($json as $key=>$val){
				if(intval($code) == $val['code']){
					$_rb = $val['name'];
					break;
				}
			}
		}
		return $_rb;
	}
	/**
	 * 通过代号取省份名
	 */
	public static function getCityName($code){
		$json = json_decode(self::getCityJson(), true);
		$_rb = '';
		if(is_array($json)){
			foreach($json as $key=>$val){
				foreach($val['city'] as $ke=>$va){
					if(intval($code) == $va['code']){
						$_rb = $va['name'];
						break;
					}
				}
			}
		}
		return $_rb;
	}
	/**
	 * 取国家选择框
	 */
	public static function getState($selectName='state', $code=0){
		$json = json_decode(self::getStateJson(), true);
	
		if(is_array($json)){
			$html = '<select name="'.$selectName.'" id="'.$selectName.'">';
			foreach($json as $key=>$val){
				if(intval($code) == intval($val['code'])){
					$html .= '<option value="'.$val['code'].'" selected="selected">'.$val['name'].'</option>';
				}else{
					$html .= '<option value="'.$val['code'].'">'.$val['name'].'</option>';
				}
	
			}
			$html .= '</select>';
		}
	
		return $html;
	}
	/**
	 * 取省份选择框
	 */
	public static function getProvice($selectName='provice', $pcode=0){
		$json = json_decode(self::getCityJson(), true);
	
		if(is_array($json)){
			$html = '<select name="'.$selectName.'" id="'.$selectName.'">';
			$html .= '<option value="0">'.Lang::get('PleaseSelect').'</option>';
			foreach($json as $key=>$val){
				if(intval($pcode) == intval($val['code'])){
					$html .= '<option value="'.$val['code'].'" selected="selected">'.$val['name'].'</option>';
				}else{
					$html .= '<option value="'.$val['code'].'">'.$val['name'].'</option>';
				}
	
			}
			$html .= '</select>';
		}
	
		return $html;
	}
	/**
	 * 取市区选择框
	 */
	public static function getCity($selectName='city', $pcode=0,$ccode=0){
		$json = json_decode(self::getCityJson(), true);
	
		if(is_array($json)){
			$html = '<select name="'.$selectName.'" id="'.$selectName.'">';
			foreach($json as $key=>$val){
				if(intval($pcode) == $val['code']){
					if(is_array($val['city'])){
						foreach($val['city'] as $ke=>$va){
							if(intval($ccode) == intval($va['code'])){
								$html .= '<option value="'.$va['code'].'" selected="selected">'.$va['name'].'</option>';
							}else{
								$html .= '<option value="'.$va['code'].'">'.$va['name'].'</option>';
							}
						}
					}
				}
			}
			$html .= '</select>';
		}
	
		return $html;
	}
}