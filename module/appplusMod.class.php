<?php
class appplusMod extends commonMod{
	/**
	 * 返回信息处理
	 */
	private function _return($format,$data=null) {
		if(isset($format)){
			switch($format){
				case 'json':
					return json_encode($data);
					break;
				default:
					return json_encode($data);
					break;
			}
		}else{
			return json_encode($data);
		}
	}
	/**
	 * access_token鉴权
	 */
	private function checkAccess($format,$access_token){
		if(!$access_token){
			echo $this->_return('json',array('error'=>'109'));exit;
		}
	
		$MandOAuth = $this->getClass('MandOAuth');
		$token = $MandOAuth->reAccessToken($access_token);
	
		$tokenInfo = $MandOAuth->getTokenInfo($token[1]);
	
		if($tokenInfo == -1){
			echo $this->_return($format, array('error'=>'129'));exit;
		}
	
		$re = $MandOAuth->checkAccessToken($tokenInfo);
	
		if($re['error'] != 'ok'){
			$reArr['error'] = $re['error'];
			echo $this->_return($format,$reArr);exit;
		}else{
			$tArr['UserID']    = $tokenInfo['UserID'];
			$tArr['client_id'] = $tokenInfo['client_id'];
			$backArr = $MandOAuth->checkAuthPastDue($tArr);
				
			if($backArr == -1){
				echo $this->_return($format, array('error'=>'127'));exit;
			}elseif($backArr == -2){
				echo $this->_return($format, array('error'=>'128'));exit;
			}else{
	
				return $tokenInfo;
			}
		}
	}
	public function invitecode(){
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$platform     = $_GET ['platform'] ? $_GET ['platform'] : $_POST ['platform'];
		$client_id    = $_GET ['client_id'] ? $_GET ['client_id'] : $_POST ['client_id'];
		$count        = $_GET ['count'] ? $_GET ['count'] : $_POST ['count'];
		
		$count = $count ? $count : 1;
		
		$token = $this->checkAccess($format,$access_token);
		
		if(!in_array($token['client_id'], $this->config['oauth']['invitecode'])){
			echo $this->_return($format,array('error'=>'302'));exit;
		}
		
		$tArr['UserID']    = $token['UserID'];
		$tArr['client_id'] = $client_id;

		$InviteCode = $this->getClass('InviteCode');
		
		for($i=0;$i<$count;$i++){
			$re[$i] = $InviteCode->getInviteCode($tArr);
		}
		
		echo $this->_return($format,$re);exit;
	}
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		$root = dirname(dirname(__FILE__));
	
		switch($className){
			case 'MandOAuth':
				include_once($root.'/include/lib/MandOAuth.class.php');
				return new MandOAuth($this->model,$this->config);
				break;
			case 'InviteCode':
				include_once($root.'/include/lib/InviteCode.class.php');
				return new InviteCode($this->model);
				break;
			default:
				break;
		}
	}
}