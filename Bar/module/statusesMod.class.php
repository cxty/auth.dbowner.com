<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 好友信息接口
 *
 */
class statusesMod extends commonMod{
	/**
	 * 返回信息处理
	 */
	private function _return($format,$data=null) {
		if(isset($data['error'])){
			$data['state'] = ComFun::getErrorValue('client',$data['error']);
		}
		
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
	 * 检验用户是否有权限访问接口
	 */
	private function checkUserIsValid($format, $token){
		//不是内部应用
		if ( !in_array($token['client_id'], $this->config['oauth']['login']) ) {
			//判断是否是默认权限
			$dbSoapInterface = $this->_getClass('DB_SoapInterface');
			$authArr = $dbSoapInterface->getAuthListInfo();
			if ( !$authArr ) {
				$this->_return(false, ComFun::getErrorValue('client', '306'), array('error'=>'306'));
			} else {
				$_isExist = false; //接口名是否存在
				$_isDefault = false; //是否是默认接口
				$_isOpen = false; //接口是否已经开放
				$_portName = ''; //授权接口名
				foreach ( $authArr as $_k => $_v ) {
					if ( $_v['contains'] ) {
						foreach ( $_v['contains'] as $_k_2 => $_v_2 ) {
							if ( strtolower($_v_2['oauthName']) == strtolower($token['port']) ) {
								$_isExist = true;
								$_isOpen = $_v_2['isOpen'];
								break;
							}
						}
						if ( $_isExist ) {
							$_isDefault = $_v['isDefault'];
							$_portName = $_v['permName'];
							break;
						}
					}
				}
			}
			
			//接口还未开放
			if ( !$_isOpen ) {
				$this->_return(false, ComFun::getErrorValue('client', '303'), array('error'=>'303'));
			}
			
			//接口名不存在
			if ( !$_isExist ) {
				$this->_return(false, ComFun::getErrorValue('client', '307'), array('error'=>'307'));
			}
			
			//接口不是默认
			if ( !$_isDefault ) {
				$mandOAuthLog = $this->_getClass('MandOAuthLog');
				$permValue = $mandOAuthLog->getPermValue($token);
				//用户还未进行授权
				if ( !$permValue ) {
					$this->_return(false, ComFun::getErrorValue('client', '305'), array('error'=>'305'));
				} else {
					$permArr = explode('|', substr($permValue, 1));
					$_isPerm = false; //是否有访问接口的权限
					foreach ( $permArr as $_k => $_v ) {
						if ( strtolower($_v) == strtolower($_portName) ) {
							$_isPerm = true;
							break;
						}
					}
					
					//没有访问接口的权限
					if ( !$_isPerm ) {
						$this->_return(false, ComFun::getErrorValue('client', '304'), array('error'=>'304'));
					}
				}
			}
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
		//echo $this->_return($format,$token);exit;
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
	/**
	 * 获取用户朋友信息
	 */
	public function friends(){	
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$platform     = $_GET ['platform'] ? $_GET ['platform'] : $_POST ['platform'];
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'friends';
		$this->checkUserIsValid($format, $token);
		
		$UserOAuth = $this->getClass('UserOAuth');
		
		$UserID = $token['UserID'];
		
		if($platform){
			$partner = ucfirst($platform);
				
			$OAuthArr   = $UserOAuth->getOAuthInfo($UserID,$partner);

			if($OAuthArr == -1){
				$re['status']   = 'fail';
				$re['msg']      = 'Please badding,first';
				$re['platform'] = strtolower($partner);
				$re['data']     = '';
			}else{
				$tArr['partner']  = $partner;
				$tArr['OAuthArr'] = $OAuthArr;
		
				$OAuth = $this->getClass('thirdparty',$tArr);

				$friendInfo = $OAuth->getFriendList($OAuthArr['user_id']);
		
				$re['status']   = 'success';
				$re['msg']      = 'ok';
				$re['platform'] = strtolower($partner);
				$re['data']     = $friendInfo;
			}
		}else{
			
			
			$re['status']   = 'success';
			$re['msg']      = 'ok';
			$re['platform'] = 'DBOnwer';
			//$re['data']     = $UserOAuth->getOAuthUserInfo($UserID);
		}
		
		echo $this->_return($format,$re);exit;
	}
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		$root = dirname(dirname(__FILE__));
	
		switch($className){
			case 'UserOAuth':
				include_once($root.'/include/lib/UserOAuth.class.php');
				return new UserOAuth($this->model,$this->config);
				break;
			case 'MandOAuth':
				include_once($root.'/include/lib/MandOAuth.class.php');
				return new MandOAuth($this->model,$this->config);
				break;
			case 'thirdparty':
				$partner = $fieldArr['partner'];
				
				$apiArr = ComFun::getNowApi($partner);
				
				$host       = $apiArr['provider']['urls']['hostURL'];
				$api_key    = $apiArr['provider']['keys']['api_key'];
				$api_sercet = $apiArr['provider']['keys']['api_sercet'];
				
				$url = dirname(dirname(__FILE__)).'/include/ext/partner/Providers/'.$partner.'.php';
				include($url);
					
				$class = 'Providers_'.$partner;
				
				return new $class($host,$api_key,$api_sercet,$fieldArr['OAuthArr']);
				break;
			case 'Login':
				include_once(dirname(dirname(__FILE__)).'/include/lib/Login.class.php');
				return new Login($this->model);
				break;
			case 'User':
				include_once(dirname(dirname(__FILE__)).'/include/lib/User.class.php');
				return new User($this->model);
				break;
			case 'UserMessage':
				include_once($root.'/include/lib/UserMessage.class.php');
				return new UserMessage($this->model);
				break;
			case 'MandOAuthLog':
				include_once($root.'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
			default:
				break;
		}
	}
}