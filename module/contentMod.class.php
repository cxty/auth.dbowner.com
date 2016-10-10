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
	 * 初始化数据记录类
	 */
	private function _init () {
		$this->dbPlugInShare = $this->_getClass( 'DB_PlugInShare' );
		
		$this->_requestLog();
	}
	
	/**
	 * 请求数据记录
	 */
	private function _requestLog () {
		$tArr['pslModuleName']  = $_GET['_module'];
		$tArr['pslActionName']  = $_GET['_action'];
	
		$_pslRequestData = '';
		unset($_GET['_module']);
		unset($_GET['_action']);
		if ( is_array($_GET) && count($_GET)>0 ) {
			$_pslRequestData = json_encode($_GET);
		} elseif ( is_array($_POST) && count($_POST)>0 ) {
			$_pslRequestData = json_encode($_POST);
		}
	
		$tArr['pslRequestData'] = $_pslRequestData;
	
		$this->PlugInShareLogID = $this->dbPlugInShare->addPlugInShareRequestLog($tArr);
	}
	
	/**
	 * 响应数据记录
	 */
	private function _respondLog ( $fieldArr ) {
		$tArr['PlugInShareLogID'] = $this->PlugInShareLogID;
		$tArr['pslRespondData']   = addslashes($fieldArr['pslRespondData']);
	
		$this->dbPlugInShare->addPlugInShareRespondLog($tArr);
	}
	
	/**
	 * 返回信息处理
	 */
	private function _return($state, $msg='', $data=null, $format='json') {
		$_rb = json_encode(array(
				'state' => $state, 
				'msg' => $msg , 
				'data' => $data 
			));
		
		$this->_respondLog( array( 'pslRespondData' =>  $_rb ) );
	
		echo $_rb;exit;
	}
	
	/**
	 * 检验用户是否有权限访问接口
	 */
	private function checkUserIsValid($format, $token){	
		//不是内部应用
		if ( !in_array($token['client_id'], $this->config['oauth']['login']) ) {
			//判断是否是默认权限
			$dbSoapInterface = $this->_getClass('DB_SoapInterface');
			$GLOBALS['config']['DB_Model']['DB_SoapInterface'] = $dbSoapInterface;
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
		$MandOAuth = $this->getClass('MandOAuth');
		$token = $MandOAuth->reAccessToken($access_token);		
		$tokenInfo = $MandOAuth->getTokenInfo($token[1]);
		
		if($tokenInfo == -1){
			$this->_return(false, ComFun::getErrorValue('client', '129'), array('error'=>'129'));
		}
		
		$re = $MandOAuth->checkAccessToken($tokenInfo);
		
		if($re['error'] != 'ok'){
			$reArr['error'] = $re['error'];
			$this->_return(false, ComFun::getErrorValue('client', $reArr['error']), array( 'error' => $reArr['error'] ) );
		}else{
			$tArr['UserID']    = $tokenInfo['UserID'];
			$tArr['client_id'] = $tokenInfo['client_id'];
			$backArr = $MandOAuth->checkAuthPastDue($tArr);
			
			if($backArr == -1){
				$this->_return(false, ComFun::getErrorValue('client', '127'), array( 'error' => '127' ) );
			}elseif($backArr == -2){
				$this->_return(false, ComFun::getErrorValue('client', '128'), array( 'error' => '128' ) );
			}else{
				return $tokenInfo;
			}				
		}
	}
	
	/**
	 * 发布信息
	 */
	public function send_msg(){	
		$this->_init();
		
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$platform     = $_POST['platform'];
		$accepter     = $_POST['accepter'];
		$theme        = $_POST['theme'];
		$content      = $_POST['content'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		if(!$accepter){
			$this->_return(false, ComFun::getErrorValue('client', '119'), array( 'error' => '119' ) );
		}
		if(!$content){
			$this->_return(false, ComFun::getErrorValue('client', '120'), array( 'error' => '120' ) );
		}
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'send_msg';
		$this->checkUserIsValid($format, $token);
		
		$UserID = $token['UserID'];
		
		$UserOAuth = $this->getClass('UserOAuth');
			
		if($platform){
			
		}else{
			if(!$theme){
				$user = $this->getClass('User');
				
				//用信息缓存
				$memKey_result = '|content|send_msg|getUserInfo-1|' . $access_token . '-';
				$menVal = $this->_Cache->get( $memKey_result );
				if ( $menVal ) {
					$userInfo = $menVal;
				} else {
					$userInfo = $user->getUserInfo($UserID);
					
					$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
				}
				
				if($userInfo){
					$theme = Lang::get('SendMsgTheme').'：'.$userInfo[0]['uName'];
				}else{
					$theme = Lang::get('SendMsgThemeOther');
				}
			}
			
			$tArr['UserID']   = $UserID;
			$tArr['accepter'] = $accepter; 
			$tArr['uTitle']   = $theme;
			$tArr['uContent'] = $content; 

			$UserMessage = $this->getClass('UserMessage');
			$articleid = $UserMessage->doSaveMsg($tArr);

			if($articleid > 0){
				$re['id'] = $articleid;
				
				$this->_return(true, 'ok', $re );
			}else{
				$this->_return(false, ComFun::getErrorValue('client', '311'), array( 'error' => '311' ) );
			}		
		}
	}
	/**
	 * 取用户未读短信息列表
	 */
	public function get_new_msg(){
		//$this->_return(true, 'ok', $_GET );
		$this->_init();
		
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|content|get_new_msg||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
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
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 取用户已读短信息列表
	 */
	public function get_read_msg(){
		$this->_init();
		
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|content|get_read_msg||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
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
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 取用户已发送信息列表
	 */
	public function get_send_msg(){
		$this->_init();
		
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|content|get_send_msg||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
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
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 取用户已删除信息列表
	 */
	public function get_del_msg(){
		$this->_init();
		
		$format       = $_POST['format'];
		$access_token = $_POST['access_token'];
		$pagesize     = $_POST['pagesize'] ? $_POST['pagesize'] : 10;
		$page         = $_POST['page'] ? $_POST['page'] : 1;
	
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|content|get_del_msg||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
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
	
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 删除短信息
	 */
	public function del_msg(){
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$platform     = $_GET ['platform'] ? $_GET ['platform'] : $_POST ['platform'];
		$id           = $_GET ['id'] ? $_GET ['id'] : $_POST ['id'];
		$type         = $_GET ['type'] ? $_GET ['type'] : $_POST ['type'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		if(!$id){
			$reArr['error'] = $re['error'];
			$this->_return(false, ComFun::getErrorValue('client', '114'), array( 'error' => '114' ) );
		}
		if(!$type){
			$reArr['error'] = $re['error'];
			$this->_return(false, ComFun::getErrorValue('client', '115'), array( 'error' => '115' ) );
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
			$this->_return(false, ComFun::getErrorValue('client', '116'), array( 'error' => '116' ) );
		}
		$UserMessage = $this->getClass('UserMessage');
		$re['ident'] = $UserMessage->doDelMsg_w2($tArr);
		
		if ( $re['ident'] == 1 ) {
			$this->_return(true, 'ok', $re );
		} else {
			$this->_return(false, ComFun::getErrorValue('client', '310'), array( 'error' => '310' ) );
		}
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