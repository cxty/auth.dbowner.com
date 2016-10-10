<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 短信息接口
 *
 */
class contentMod extends commonMod{
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
		
		$tokenInfo = $MandOAuth->getTokenInfo($token[1]);
		//echo $this->_return($format,$tokenInfo);exit;
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
	 * 发布信息
	 */
	public function send_msg(){	
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$platform     = $_POST['platform'];
		$accepter     = $_POST['accepter'];
		$theme        = $_POST['theme'];
		$content      = $_POST['content'];
		
		if(!$accepter){
			echo $this->_return($format,array('error'=>119));exit;
		}
		
		if(!$content){
			echo $this->_return($format,array('error'=>120));exit;
		}
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'send_msg';
		$this->checkUserIsValid($format, $token);
		
		$UserID = $token['UserID'];
		
		$UserOAuth = $this->getClass('UserOAuth');
			
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
				
				$info = $OAuth->addInformation($OAuthArr['user_id'],$_POST['content']);
		
				$re['status']   = 'success';
				$re['msg']      = 'ok';
				$re['platform'] = strtolower($partner);
				$re['data']     = $info;
			}
		}else{
			if(!$theme){
				$user = $this->getClass('User');
				$userInfo = $user->getUserInfo($UserID);
				if($userInfo){
					$theme = Lang::get('SendMsgTheme').'：'.$userInfo[0]['uName'];
				}else{
					$theme = Lang::get('SendMsgThemeOther');
				}
			}
			
			$tArr['UserID']   = $UserID;
			$tArr['accepter'] = $accepter; 
			$tArr['theme']    = $theme;
			$tArr['uContent'] = $content; 

			$UserMessage = $this->getClass('UserMessage');
			$articleid = $UserMessage->doSaveMsg($tArr);

			if($articleid > 0){
				$re['id'] = $articleid;
			}else{
				$re['id'] = -1;
			}		
		}
		
		echo $this->_return($format,$re);exit;
	}
	/**
	 * 取用户未读短信息列表
	 */
	public function get_new_msg(){
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
		//echo $this->_return($format,$_POST);exit;		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'get_new_msg';
		$this->checkUserIsValid($format, $token);
		
		$UserMessage = $this->getClass('UserMessage');
		$user = $this->getClass('User');
		
		$tArr['UserID'] = $token['UserID'];
		$tArr['type'] = 'unreadMsg';
		$msgArr = $UserMessage->getMsgRecord($tArr,$pagesize,$page);
		//echo $this->_return($format,$msgArr);exit;		
		$re['count'] = $msgArr['count'];
		if($msgArr['record']){
			foreach($msgArr['record'] as $key=>$val){
				$re['record'][$key]['id']         = $val['selfid'];
				$re['record'][$key]['name']       = $user->getUserNameByID($val['UserID']);
				$re['record'][$key]['theme']      = $val['uTitle'];
				$re['record'][$key]['content']    = $val['uContent'];
				$re['record'][$key]['appendtime'] = $val['uAppendTime'];
			}
		}
		
		echo $this->_return($format,$re);exit;
	}
	/**
	 * 取用户已读短信息列表
	 */
	public function get_read_msg(){
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'get_read_msg';
		$this->checkUserIsValid($format, $token);
		
		$UserMessage = $this->getClass('UserMessage');
		$user = $this->getClass('User');
		
		$tArr['UserID'] = $token['UserID'];
		$tArr['type'] = 'readMsg';
		$msgArr = $UserMessage->getMsgRecord($tArr,$pagesize,$page);
		
		$re['count'] = $msgArr['count'];
		if($msgArr['record']){
			foreach($msgArr['record'] as $key=>$val){
				$re['record'][$key]['id']         = $val['selfid'];
				$re['record'][$key]['name']       = $user->getUserNameByID($val['UserID']);
				$re['record'][$key]['theme']      = $val['uTitle'];
				$re['record'][$key]['content']    = $val['uContent'];
				$re['record'][$key]['appendtime'] = $val['uAppendTime'];
			}
		}
		
		echo $this->_return($format,$re);exit;
	}
	/**
	 * 取用户已发送信息列表
	 */
	public function get_send_msg(){
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'get_send_msg';
		$this->checkUserIsValid($format, $token);
		
		$UserMessage = $this->getClass('UserMessage');
		$user = $this->getClass('User');
		
		$tArr['UserID'] = $token['UserID'];
		$tArr['type'] = 'sendMsg';
		$msgArr = $UserMessage->getMsgRecord($tArr,$pagesize,$page);
		
		$re['count'] = $msgArr['count'];
		if($msgArr['record']){
			foreach($msgArr['record'] as $key=>$val){
				$re['record'][$key]['id']         = $val['selfid'];
				$re['record'][$key]['name']       = $user->getUserNameByID($val['UserID']);
				$re['record'][$key]['theme']      = $val['uTitle'];
				$re['record'][$key]['content']    = $val['uContent'];
				$re['record'][$key]['appendtime'] = $val['uAppendTime'];
			}
		}
		
		echo $this->_return($format,$re);exit;
	}
	/**
	 * 取用户已删除信息列表
	 */
	public function get_del_msg(){
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
	
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'get_del_msg';
		$this->checkUserIsValid($format, $token);
	
		$UserMessage = $this->getClass('UserMessage');
		$user = $this->getClass('User');
	
		$tArr['UserID'] = $token['UserID'];
		$tArr['type'] = 'delMsg';
		$msgArr = $UserMessage->getMsgRecord($tArr,$pagesize,$page);
	
		$re['count'] = $msgArr['count'];
		if($msgArr['record']){
			foreach($msgArr['record'] as $key=>$val){
				$re['record'][$key]['id']         = $val['selfid'];
				$re['record'][$key]['name']       = $user->getUserNameByID($val['UserID']);
				$re['record'][$key]['theme']      = $val['uTitle'];
				$re['record'][$key]['content']    = $val['uContent'];
				$re['record'][$key]['appendtime'] = $val['uAppendTime'];
				$re['record'][$key]['type']       = ($val['ident'] == 'receive') ? 1 : 2;
			}
		}
	
		echo $this->_return($format,$re);exit;
	}
	/**
	 * 删除短信息
	 */
	public function del_msg(){
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$platform     = $_GET ['platform'] ? $_GET ['platform'] : $_POST ['platform'];
		$id           = $_GET ['id'] ? $_GET ['id'] : $_POST ['id'];
		$type         = $_GET ['type'] ? $_GET ['type'] : $_POST ['type'];
		
		if(!$id){
			$reArr['error'] = $re['error'];
			echo $this->_return($format,array('error'=>114));exit;
		}
		if(!$type){
			$reArr['error'] = $re['error'];
			echo $this->_return($format,array('error'=>115));exit;
		}
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'del_msg';
		$this->checkUserIsValid($format, $token);
		
		$UserID = $token['UserID'];
		
		$tArr['UserID']  = $UserID;	
		$tArr['id']      = $id;
		
		if(intval($type) === 1){
			$tArr['type']  = 'otherType';
		}elseif(intval($type) === 2){
			$tArr['type']  = 'sendMsg';					
		}else{
			echo $this->_return($format,array('error'=>116));exit;
		}
		$UserMessage = $this->getClass('UserMessage');
		$re['ident'] = $UserMessage->doDelMsg_w2($tArr);
		
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