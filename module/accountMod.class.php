<?php
class accountMod extends commonMod{
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
	 * 鉴权
	 */
	private function _checkAccess($format,$access_token){
		if(!$access_token){
			//echo $this->_return('json',array('error'=>'109'));
			$this->assign('msg',$this->_return($format,$reArr));
			$this->display ('oauth/message.html'); //输出模板
			exit;
		}

		$MandOAuth = $this->getClass('MandOAuth');
		$token = $MandOAuth->reAccessToken($access_token);

		$tokenInfo = $MandOAuth->getTokenInfo($token[1]);
		
		$re = $MandOAuth->checkAccessToken($tokenInfo);

		if($re['error'] != 'ok'){
			$reArr['error'] = $re['error'];
			//echo $this->_return($format,$reArr);
			$this->assign('msg',$this->_return($format,$reArr));
			$this->display ('oauth/message.html'); //输出模板
			exit;
		}else{
			$tArr['UserID']    = $tokenInfo['UserID'];
			$tArr['client_id'] = $tokenInfo['client_id'];
			
			$backArr = $MandOAuth->checkAuthPastDue($tArr);
				
			if($backArr == -1){
				echo $this->_return($format, array('error'=>'127'));exit;
			}elseif($backArr == -2){
				echo $this->_return($format, array('error'=>'128'));exit;
			}else{
				$tokenInfo['AppInfoID']      = $reback['AppInfoID'];
				$tokenInfo['apppermissions'] = $reback['apppermissions'];
				return $tokenInfo;
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
	//以下方法是以页面的形式呈现
	/**
	 * 修改密码
	 */
	public function reset_pwd(){
		if($this->getCookies('UserID')){
			$UserID = $this->getCookies('UserID');
		}else{
			$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
			$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
			$token = $this->_checkAccess($format,$access_token);
			$UserID = $token['UserID'];
		}
			
		$user = $this->getClass('User');
		$userInfo = $user->getUserInfo($UserID);
		
		if($userInfo[0]['uPWD']){
			$uPWD = 1;
		}else{
			$uPWD = -1;
		}
		$this->assign('pwd',$uPWD);
		$this->assign('UserID',$userInfo[0]['UserID']);
		
		$this->display ('oauth/resetpassword.html'); //输出模板
	}
	/**
	 * 密码检查
	 */
	public function checkPwd(){
		$user = $this->getClass('User');
		$userInfo = $user->getUserInfo($_GET['UserID']);
		
		if($userInfo[0]['uPWD'] ==  md5(trim($_GET['uPWD']))){
			echo 1;
		}else{
			echo -1;
		}
	}
	/**
	 * 修改密码
	 */
	public function doreset_pwd(){
		$user = $this->getClass('User');
		$userInfo = $user->modifyPassword($_POST);
		$lang = Lang::get('PH_LANG');
		$this->assign('msg',$lang['ModifyPwdSuccess']);
		$this->display ('oauth/message.html'); //输出模板
		exit;
	}
	/**
	 * 授权页面
	*/
	public function set_oauth(){
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$token = $this->_checkAccess($format,$access_token);

		$UserID    = $token['UserID'];
		$client_id = $token['client_id'];
		
		$tArr['UserID']         = $UserID;
		$tArr['client_id']      = $client_id;
		//$tArr['apppermissions'] = $token['apppermissions']; //应用权限列表

		$MandOAuth = $this->getClass('MandOAuth');
		$permArr = $MandOAuth->getPermValue($tArr);

		$this->assign('UserID',$UserID);
		$this->assign('client_id',$client_id);
		$this->assign('permArr',$permArr['list']);
		$this->display ('oauth/setoauth.html'); //输出模板
		exit;
	}
	/**
	 * 处理授权页面
	 */
	public function doset_oauth(){	
		/*	====旧的方法	
		$MandOAuth = $this->getClass('MandOAuth');
		$permission = $MandOAuth->doPermission($_POST ['ulimit']);
		*/

		$tArr['UserID']    = $_POST['UserID'];
		$tArr['client_id'] = $_POST['client_id'];
		$tArr['ulimit']    = $_POST['ulimit'];
		$mandOAuthlog = $this->getClass('MandOAuthLog');
		$mandOAuthlog->updatePermValue($tArr);
		
		$lang = Lang::get('JS_LANG');
		$this->assign('msg',$lang['ModifyOAuthSuccess']);
		$this->display ('oauth/message.html'); //输出模板
		exit;
	}
	//以下的方法是以curl形式呈现
	/**
	 * 开发者第一次登录时，插入授权应用信息和授权用户信息
	 */
	public function register_user(){
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$user_id      = $_GET ['user_id'] ? $_GET ['user_id'] : $_POST ['user_id'];
		$client_id    = $_GET ['client_id'] ? $_GET ['client_id'] : $_POST ['client_id'];
		
		$token = $this->checkAccess($format,$access_token);
		if($token['client_id'] != '80022010'){
			//echo $this->_return($format,array("error" => "202"));exit;
		}
		
		if(!$user_id){
			echo $this->_return($format,array("error" => "203"));exit;
		}
		
		if(!$client_id){
			echo $this->_return($format,array("error" => "204"));exit;
		}
		
		$MandOAuth = $this->getClass('MandOAuth');
		$OpenID = $MandOAuth->_getUserIDArr($user_id);
		
		$tArr['UserID']    = $OpenID[0];
		$tArr['client_id'] = $client_id;
		//$tArr['AppInfoID'] = $OpenID[1];
		
		$re = $MandOAuth->_getToken($tArr);
		//echo $this->_return($format,$re);exit;
		if(!$re['error']){
			$mandOAuthlog = $this->getClass('MandOAuthLog');
			$mandOAuthlog->setAuthosizeAppLog($client_id);
			
// 			$aArr['UserID']        = $OpenID[0];
// 			$aArr['client_id']     = $client_id;
			
// 			if(!$mandOAuthlog->getAuthNumLogInfo($aArr)){
// 				$aArr['access_token']  = $re['access_token'];
// 				$aArr['refresh_token'] = $re['refresh_token'];
// 				$aArr['user_id']       = $re['user_id'];
				
// 				$mandOAuthlog->addAuthNumLogInfo($aArr);
// 			}
			
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
			case 'MandOAuthLog':
				include_once($root.'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
			default:
				break;
		}
	}
}