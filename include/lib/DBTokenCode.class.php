<?php
/**
 * 
 * @author wbqing405@sina.com
 * 
 * 手机随机宝令处理类
 *
 */
class DBTokenCode{
	/**
	 * 截取用户随机码
	 */
	public function subUserCode($uCode){
		return substr($uCode, 0, 10);
	}
	/**
	 * 通过手机随机宝令验证用户信息
	 */
	public function CheckUserByUserCode($user_code, $uCode, $timestamp){
		if($uCode){
			$ToKenCode = $this->getTokenCode($uCode, $timestamp);

			if(strval($user_code) == strval($ToKenCode)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/**
	 * 生成手机宝令随机码第一步
	 */
	public function getTokenCode($code, $time){
		$re = '';
		
		if($code != ''){
			$timestamp =  $time/ 30 % 2000000;
			$_md5      = MD5($code.$timestamp);			
			$_code     = substr($_md5, 0, 6);
			$re        = $this->getalphnum($_code);
		}
		
		return $re;
	}
	/**
	 * 手机宝令随机码处理第二步
	 */
	private function getalphnum($_char){
		$_array = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9");
		
		$_len = strlen($_char);
		$_sum = 0;
		
		for($i=0;$i<$_len;$i++){
			for($j=0;$j<count($_array);$j++){
				if(substr($_char, $i, 1) == $_array[$j]){
					$_sum += ($j + 1) * Pow(36, $_len - $i - 1);
					break;
				}
			}
		}
		
		return $_sum;
	}
}