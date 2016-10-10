<?php
/**
 * 时间处理
 *
 * @author wbqing405@sina.com
 */
class Timeconfig{
	
	public function __construct(){
		
	}
	/**
	 * 获取年份
	 */
	public function getYearConfig($selectName='year',$fieldArr=''){
		$str = '';
		$str .= '<select name=\''.$selectName.'\' id="'.$selectName.'">';
		$str .= '<option value=\'0\'>'.Lang::get('PleaseSelect').'</option>';
		
		for($i=date('Y');$i>=(date('Y')-150);$i--){
			if(intval($fieldArr['default']) == $i){
				$str .= '<option value=\''.$i.'\' selected>'.$i.'</option>';
			}else{
				$str .= '<option value=\''.$i.'\'>'.$i.'</option>';
			}		
		}
		
		$str .= '</select>';

		return $str;
	}
	/**
	 * 获取月份
	 */
	public function getMonthConfig($selectName='month',$fieldArr=''){
		$str = '';
		
		if(!$fieldArr['head']){
			$str .= '<select name=\''.$selectName.'\' id="'.$selectName.'">';
			$str .= '<option value=\'0\'></option>';
		}
		
		
		for($i=1;$i<=12;$i++){
			if($i<10){
				$i = '0'.$i;
			}
			if($fieldArr['default'] == $i){
				$str .= '<option value=\''.$i.'\' selected>'.$i.'</option>';
			}else{
				$str .= '<option value=\''.$i.'\'>'.$i.'</option>';
			}		
		}
		
		if(!$fieldArr['head']){
			$str .= '</select>';
		}
		
		
		return $str;
	}
	/**
	 * 获取日起
	 */
	public function getDayConfig($selectName='day',$fieldArr=''){
		$year = $fieldArr['year'] ? intval($fieldArr['year']) : 1;

		if($year%400 == 0){
			$year = 1;
		}elseif($year%2 == 0 && substr($year,2) != '00'){
			$year = 1;
		}else{
			$year = 0;
		}
		
		$month = $fieldArr['month'] ? intval($fieldArr['month']) : 1;
		if(in_array($month, array(1,3,5,7,8,10,12))){
			$day = 31;
		}elseif(in_array($month, array(4,6,9,11))){
			$day = 30;	
		}elseif($month == 2 && $year == 1){
			$day = 29;
		}else{
			$day = 28;
		}

		$str = '';
		if(!$fieldArr['head']){
			$str .= '<select name=\''.$selectName.'\' id="'.$selectName.'">';
			$str .= '<option value=\'0\'></option>';
		}
	
		for($i=1;$i<=$day;$i++){
			if($i<10){
				$i = '0'.$i;
			}
			if(intval($fieldArr['default']) == $i){
				$str .= '<option value=\''.$i.'\' selected>'.$i.'</option>';
			}else{
				$str .= '<option value=\''.$i.'\'>'.$i.'</option>';
			}		
		}
	
		if(!$fieldArr['head']){
			$str .= '</select>';
		}
	
		return $str;
	}
	/**
	 * 默认为空
	 */
	public function getDefaultConfig($selectName=''){
		$str = '';
		$str .= '<select name=\''.$selectName.'\'>';
		$str .= '<option value=\'0\'></option>';
		
		$str .= '</select>';
		
		return $str;
	}
}