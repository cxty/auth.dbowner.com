<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 用户详情接口
 *
 */
class usersMod extends commonMod{
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
		//方法改造后返回参数进行变动（为了兼容改造前接口规范，特此处理）
		if ( isset($data['error']) ) {
			$_rb['data']  = $data;
			$_rb['error'] = $data['error'];
		} else {
			$_rb = $data;
		}
		
		$_rb['state'] = $state;
		$_rb['msg']   = $msg;
		
		$_rb = json_encode($_rb);
		
		$this->_respondLog( array( 'pslRespondData' =>  $_rb ) );
	
		echo $_rb;exit;
	}
	
	/**
	 * 返回信息处理
	 */
	private function _returnNew($state, $msg='', $data=null, $format='json') {
		//方法改造后返回参数进行变动（为了兼容改造前接口规范，特此处理）
		if ( isset($data['error']) ) {
			$_rb['data']  = $data;
			$_rb['error'] = $data['error'];
		} else {
			$_rb['data'] = $data;
		}
	
		$_rb['state'] = $state;
		$_rb['msg']   = $msg;
	
		$_rb = json_encode($_rb);
	
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
		
		$memKey_result = '|oauth|checkAccess||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$re = $menVal;
		} else {
			$re = $MandOAuth->checkAccessToken($tokenInfo);
			$this->_Cache->set( $memKey_result, $re );
		}
		
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
	 * refresh_token鉴权
	 */
	private function checkrefresh($format,$refresh_token){
		$MandOAuth = $this->getClass('MandOAuth');
		$token = $MandOAuth->reAccessToken($refresh_token);
		//echo $this->_return($format,$token);exit;
		$tokenInfo = $MandOAuth->getTokenInfo($token[0]);

		if($tokenInfo == -1){
			$this->_return(false, ComFun::getErrorValue('client', '129'), array( 'error' => '129' ) );
		}
			
		$re = $MandOAuth->checkRefreshToken($tokenInfo);
	
		if($re['error'] != 'ok'){
			$reArr['error'] = $re['error'];
			echo $this->_return($format,$reArr);exit;
		}else{
			$tArr['UserID']    = $tokenInfo['UserID'];
			$tArr['client_id'] = $tokenInfo['client_id'];
			$re = $MandOAuth->checkAuthPastDue($tArr);
			
			if($re == -1){
				$this->_return(false, ComFun::getErrorValue('client', '127'), array( 'error' => '127' ) );
			}elseif($re == -2){
				$this->_return(false, ComFun::getErrorValue('client', '128'), array( 'error' => '128' ) );
			}else{
				return $tokenInfo;
			}
		}
	}
	/**
	 * 取得用户相关信息
	 */	
	public function show(){	
		$this->DBWriteTrace->write('show1');
		
		$this->_init();
		
		$this->DBWriteTrace->write('show2');
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$platform     = $_GET ['platform'] ? $_GET ['platform'] : $_POST ['platform'];
		$providers    = $_GET ['providers'] ? $_GET ['providers'] : $_POST ['providers'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		$this->DBWriteTrace->write('show3');
		
		//用信息缓存
		$memKey_result = '|users|show||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		$this->DBWriteTrace->write('show4');
		
		$token = $this->checkAccess($format,$access_token);
		
		$this->DBWriteTrace->write('show5');
		
		//$this->_return(true, 'ok', $token );
		//权限检验
		$token['port'] = 'show';
		$this->checkUserIsValid($format, $token);
		
		$this->DBWriteTrace->write('show6');
		
		$UserOAuth = $this->getClass('UserOAuth');
		
		$UserID = $token['UserID'];
		
		$this->DBWriteTrace->write('show7');
		
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
	
				$userInfo = $OAuth->getUserInfo($OAuthArr['user_id']);
	
				$re['status']   = 'success';
				$re['msg']      = 'ok';
				$re['platform'] = strtolower($partner);
				$re['data']     = $userInfo;
			}
		}else{
			$userInfo = $UserOAuth->getOAuthUserInfo($UserID,$this->modifyProfile);
	
			if($userInfo){
				$MandOAuth = $this->getClass('MandOAuth');
				
				$re['status']   = 'success';
				$re['msg']      = 'ok';
				$re['platform'] = 'DBOnwer';
					
				$re['data']['account']  = $this->config['PLATFORM']['Auth'] . '/main/index';
				$re['data']['signout']  = $this->config['PLATFORM']['Auth'] . '/index/loginOut';
				$re['data']['name']     = $userInfo['name'];
				$re['data']['email']    = $userInfo['email'];
				$re['data']['id']       = $MandOAuth->getUserOAuthID($token['UserID'],$token['client_id']);
				$re['data']['group']    = $userInfo['group'];
				$re['data']['ico']      = $userInfo['ico'];
				$re['data']['location'] = $userInfo['location'];
				$re['data']['sex']      = $userInfo['sex'];
			}else{
				$re['status']   = 'fail';
				$re['msg']      = 'The account is not exist';
				$re['platform'] = 'DBOnwer';
			}
		}
		
		$this->DBWriteTrace->write('show8');
		
		//已经绑定的第三方信息
		if ( $providers == 'true' ) {
			$re['data']['providers'] = $UserOAuth->getBindingThirdPartyInfoByUserID( $UserID );
		}
		
		$this->DBWriteTrace->write('show9');
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->DBWriteTrace->write('show10');
		
		$this->_return(true, 'ok', $re );
	}
	
	/**
	 * 取得用户相关信息==特殊接口，待写
	 */
	public function showinfo () {
		$this->_init();
	
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$platform     = $_GET ['platform'] ? $_GET ['platform'] : $_POST ['platform'];
		$providers    = $_GET ['providers'] ? $_GET ['providers'] : $_POST ['providers'];
	
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
	
		//用信息缓存
		$memKey_result = '|users|show||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
	
		$token = $this->checkAccess($format,$access_token);
	
		//权限检验
		$token['port'] = 'show';
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
	
				$userInfo = $OAuth->getUserInfo($OAuthArr['user_id']);
	
				$re['status']   = 'success';
				$re['msg']      = 'ok';
				$re['platform'] = strtolower($partner);
				$re['data']     = $userInfo;
			}
		}else{
			$userInfo = $UserOAuth->getOAuthUserInfo($UserID,$this->modifyProfile);
	
			if($userInfo){
				$MandOAuth = $this->getClass('MandOAuth');
	
				$re['status']   = 'success';
				$re['msg']      = 'ok';
				$re['platform'] = 'DBOnwer';
					
				$re['data']['account']  = $this->config['PLATFORM']['Auth'] . '/main/index';
				$re['data']['signout']  = $this->config['PLATFORM']['Auth'] . '/index/loginOut';
				$re['data']['name']     = $userInfo['name'];
				$re['data']['email']    = $userInfo['email'];
				$re['data']['id']       = $MandOAuth->getUserOAuthID($token['UserID'],$token['client_id']);
				$re['data']['group']    = $userInfo['group'];
				$re['data']['ico']      = $userInfo['ico'];
				$re['data']['location'] = $userInfo['location'];
				$re['data']['sex']      = $userInfo['sex'];
			}else{
				$re['status']   = 'fail';
				$re['msg']      = 'The account is not exist';
				$re['platform'] = 'DBOnwer';
			}
		}
	
		//已经绑定的第三方信息
		if ( $providers == 'true' ) {
			$re['data']['providers'] = $UserOAuth->getBindingThirdPartyInfoByUserID( $UserID );
		}
	
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
	
		$this->_return(true, 'ok', $re );
	}
	
	/**
	 * 退出此用户登录状态
	 */
	public function signout(){
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|users|signout||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		$token = $this->checkAccess($format,$access_token);	

		//权限检验
		$token['port'] = 'signout';
		$this->checkUserIsValid($format, $token);
		
		$login = $this->getClass('Login');
		$login->delOnLineID($token['UserID']);

		$re['status']   = 'success';
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 判断是否过期
	 */
	public function istimeout(){
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|users|istimeout||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		$MandOAuth = $this->getClass('MandOAuth');
		//$token = $MandOAuth->reAccessToken($access_token);
		$token = $this->checkAccess($format,$access_token);

		//权限检验
		$token['port'] = 'istimeout';
		$this->checkUserIsValid($format, $token);
		
		$user = $this->getClass('User');

		$OnlineLogID = $user->getUserOnlineLogID($token['UserID']);

		$re['status']   = 'success';
		$re['id'] = $OnlineLogID;
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );

		$this->_return(true, 'ok', $re );
	}
	/**
	 * 返回用户所有应用及其权限代码
	 */
	public function getapplist(){
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['c'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|users|getapplist||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'getapplist';
		$this->checkUserIsValid($format, $token);
		
		$UserOAuth = $this->getClass('UserOAuth');
		$re['list'] = $UserOAuth->dogetAppList($token['UserID']);
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 返回API对应的相关信息
	 */
	public function getApiInfow(){
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['c'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|users|getApiInfow||' . $access_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'getApiInfow';
		$this->checkUserIsValid($format, $token);
		
		$UserOAuth = $this->getClass('UserOAuth');
		$re = $UserOAuth->getAuthAppInfo($token['client_id']);
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 用refresh_token刷新access_token
	 */
	public function fresh_token(){	
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$refresh_token = $_GET ['refresh_token'] ? $_GET ['refresh_token'] : $_POST ['refresh_token'];
		
		if(!$refresh_token){
			$this->_return(false, ComFun::getErrorValue('client', '112'), array( 'error' => '112' ) );
		}
		
		//用信息缓存
		$memKey_result = '|users|fresh_token||' . $refresh_token . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		
		$token = $this->checkrefresh($format,$refresh_token);
		
		//权限检验
		$token['port'] = 'fresh_token';
		$this->checkUserIsValid($format, $token);
		
		$MandOAuth = $this->getClass('MandOAuth');
		$re = $MandOAuth->dofresh_token($token);
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 查询指定用户名的用户信息
	 */
	public function show_by_name(){
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$userName     = $_GET ['name'] ? $_GET ['name'] : $_POST ['name'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|users|show_by_name||' . $access_token . '-';
		
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		if(!$userName){
			$this->_return(false, ComFun::getErrorValue('client', '117'), array( 'error' => '117' ) );
		}
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'show_by_name';
		$this->checkUserIsValid($format, $token);
		
		$token['uName'] = $userName;
		
		$UserOAuth = $this->getClass('UserOAuth');
		
		$userInfo = $UserOAuth->doshow_by_name($token,$this->modifyProfile);
		
		if($userInfo){
			$MandOAuth = $this->getClass('MandOAuth');
			
			$re['status']   = 'success';
			$re['msg']      = 'ok';
			$re['platform'] = 'DBOnwer';
				
			$re['data']['account']  = $this->config['PLATFORM']['Auth'] . '/main/index';
			$re['data']['signout']  = $this->config['PLATFORM']['Auth'] . '/index/loginOut';
			$re['data']['name']     = $userInfo['name'];
			$re['data']['email']    = $userInfo['email'];
			$re['data']['id']       = $MandOAuth->getUserOAuthID($userInfo['UserID'],$token['client_id']);
			$re['data']['group']    = $userInfo['group'];
			$re['data']['ico']      = $userInfo['ico'];
			$re['data']['location'] = $userInfo['location'];
			$re['data']['sex']      = $userInfo['sex'];
		}else{
			$re['status']   = 'fail';
			$re['msg']      = 'The account is not exist';
			$re['platform'] = 'DBOnwer';
		}
		
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 查询指定用户user_id的用户信息
	 */
	public function show_by_userid(){
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$user_id      = $_GET ['user_id'] ? $_GET ['user_id'] : $_POST ['user_id'];
		
		if(!$user_id){
			$this->_return(false, ComFun::getErrorValue('client', '118'), array( 'error' => '118' ) );
		}
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|users|show_by_userid||' . $access_token . '-';
		
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		$token = $this->checkAccess($format,$access_token);
		
		//权限检验
		$token['port'] = 'show_by_userid';
		$this->checkUserIsValid($format, $token);
		
		$token['user_id'] = $user_id;
		
		$UserOAuth = $this->getClass('UserOAuth');
		$userInfo = $UserOAuth->doshow_by_userid($token,$this->modifyProfile);
		
		if($userInfo){
			$MandOAuth = $this->getClass('MandOAuth');
				
			$re['status']   = 'success';
			$re['msg']      = 'ok';
			$re['platform'] = 'DBOnwer';
	
			$re['data']['account']  = $this->config['PLATFORM']['Auth'] . '/main/index';
			$re['data']['signout']  = $this->config['PLATFORM']['Auth'] . '/index/loginOut';
			$re['data']['name']     = $userInfo['name'];
			$re['data']['email']    = $userInfo['email'];
			$re['data']['id']       = $MandOAuth->getUserOAuthID($userInfo['UserID'],$token['client_id']);
			$re['data']['group']    = $userInfo['group'];
			$re['data']['ico']      = $userInfo['ico'];
			$re['data']['location'] = $userInfo['location'];
			$re['data']['sex']      = $userInfo['sex'];
		}else{
			$re['status']   = 'fail';
			$re['msg']      = 'The account is not exist';
			$re['platform'] = 'DBOnwer';
		}
	
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re );
	}
	/**
	 * 判断第三方账号是否过期
	 */
	public function istimeoutofpartner () {
		$this->_init();
	
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$partner      = $_GET ['partner'] ? $_GET ['partner'] : $_POST ['partner'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		
		if ( !$partner ) {
			$this->_return(false, ComFun::getErrorValue('client', '312'), array( 'error' => '312' ) );
		}
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|users|istimeoutofpartner||' . $access_token . '-';
		
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		//判断第三方标识码是否合法
		$apiArr = ComFun::getAPIConfig();
		$_isvalid = false; //合作方不合法
		$_parnterInfo = '';
		if ( $apiArr['providers'] ) {
			foreach ( $apiArr['providers'] as $_key => $_val ) {
				if ( strtolower($_key) == strtolower($partner) ) {
					$_isvalid     = true;
					$partner      = $_key;
					$_parnterInfo = $_val;
					break;
				}
			}
		}
		if ( !$_isvalid ) {
			$this->_return(false, ComFun::getErrorValue('client', '313'), array( 'error' => '313' ) );
		}
		
		//授权值合法性判断
		if(!$access_token){
			$this->_return(false, ComFun::getErrorValue('client', '109'), array( 'error' => '109' ) );
		}
		$MandOAuth = $this->getClass('MandOAuth');
		$token = $this->checkAccess($format,$access_token);
	
		//接口权限检验
		$token['port'] = 'istimeoutofpartner';
		$this->checkUserIsValid($format, $token);
		
		$tArr['UserID'] = $token['UserID'];
		$tArr['uProvider'] = $partner;
		$login = $this->_getClass('Login');
		$_rb = $login->getPartnerAuthInfoByID( $tArr );
		
		$rqArr['partner']  = $partner;
		$rqArr['provider'] = $_parnterInfo;
		$rqArr['OAuthArr'] = $_rb;	
	
		$dbGetUserInfo = $this->_getClass('DBGetUserInfo', $rqArr);
		$_uRb = $dbGetUserInfo->getUserInfo();
	
		if ( $_uRb['state'] ) {
			$_rcb['state']            = $_uRb['state'];
			$_rcb['msg']              = $_uRb['msg'];
			$_rcb['data']['name']     = $_uRb['data']['uDisplay_name'];
			$_rcb['data']['id']       = $_uRb['data']['uProvider_uid'];
			$_rcb['data']['head']     = $_uRb['data']['uImages'];
			$_rcb['data']['location'] = $_uRb['data']['location'];
			$_rcb['data']['profile']  = $_uRb['data']['uri'];
			
			$this->_Cache->set( $memKey_result, $_rcb, $this->config['MEM_EXPIRE'] );
			
			$this->_return(true, 'ok', $_rcb );
		} else {
			$this->_return(false, ComFun::getErrorValue('client', '314'), array( 'error' => '314' ) );
		}
	}
	
	/**
	 * 获取指定应用的user_id
	 */
	public function get_userid(){
		$this->_init();
	
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$user_id      = $_GET ['user_id'] ? $_GET ['user_id'] : $_POST ['user_id'];
		$AppID        = $_GET ['AppID'] ? $_GET ['AppID'] : $_POST ['AppID'];
	
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		if(!$user_id){
			$this->_return(false, ComFun::getErrorValue('client', '118'), array( 'error' => '118' ) );
		}
		
		if(!$AppID){
			$this->_return(false, ComFun::getErrorValue('client', '316'), array( 'error' => '316' ) );
		}
		
		$mandOAuth = $this->getClass('MandOAuth');
		$token = $this->checkAccess($format,$access_token);
		
		$IDStr = $mandOAuth->dodecrypt($user_id);
			
		$IDArr = explode('|', $IDStr);
		
		if(is_array($IDArr)){
			$mandOAuthLog = $this->getClass('MandOAuthLog');
			
			$re = $mandOAuthLog->getAuthNumLogInfo(array(
					'UserID' => $IDArr[0],
					'client_id' => $AppID
			));
			
			if ( $re ) {
				$this->_return(true, 'ok', array('user_id' => $mandOAuth->doencrypt($IDArr[0].'|'.$AppID)) );
			} else {
				$this->_return(false, ComFun::getErrorValue('client', '317'), array( 'error' => '317' ) );
			}
		}else{
			return $this->_return ( false, 'Data Error', null);
		}
	}
	
	/**
	 * 增加用户
	 */
	public function add () {
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$AppID        = $_GET ['AppID'] ? $_GET ['AppID'] : $_POST ['AppID'];
		$email        = $_GET ['email'] ? $_GET ['email'] : $_POST ['email'];
		$username     = $_GET ['username'] ? $_GET ['username'] : $_POST ['username'];
		$password     = $_GET ['password'] ? $_GET ['password'] : $_POST ['password'];
		$isEmail      = $_GET ['isemail'] ? $_GET ['isemail'] : $_POST ['isemail'];
	
		if ( !$access_token ) {
			$this->_returnNew(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		if ( !$AppID ){
			$this->_returnNew(false, ComFun::getErrorValue('client', '316'), array( 'error' => '316' ) );
		}
		
		if ( !$email ) {
			$this->_returnNew(false, ComFun::getErrorValue('client', '318'), array( 'error' => '318' ) );
		}
		
		//检验access_token合法性
		$token = $this->checkAccess($format, $access_token);
		
		$mandOAuth = $this->getClass('MandOAuth');
		$User = new User($this->model);
		$reArr = $User->getEmailUserIDNew($email);
		
		if ( $reArr ) {
			$this->_returnNew(true, 'OK', array(
					'user_id'  => $mandOAuth->getUserID(array(
							'UserID'    => $reArr['UserID'],
							'client_id' => $AppID//$token['client_id']
					)),
					'email'    => $reArr['uEmail'],
					'username' => $reArr['uName'],
					'isnew'    => false,
			));
		} else {
			$emailArr = ComFun::getEmail($email);
			
			if ( $emailArr['state'] ) {
				$Login = new Login($this->model);
		
				$uName = $Login->checkRepeatName($username ? $username : $emailArr['str']);
		
				$re = $Login->addNewUserDir(array(
						'uEmail' => $email,
						'uName' => $uName,
						'uPWD' => $password ? $password : $this->config['Default_Pwd'],
						'isEmail' => $isEmail ? true : false
				));
		
				$this->_returnNew(true, 'OK', array(
						'user_id'   => $mandOAuth->getUserID(array(
								'UserID' => $re,
								'client_id' => $AppID//$token['client_id']
						)),
						'email'     => $email,
						'username'  => $uName,
						'isnew'     => true,
				));
			} else {
				$this->_returnNew(false, ComFun::getErrorValue('client', '319'), array( 'error' => '319' ) );
			}
		}
	}
	
	/**
	 * 重置密码
	 */
	public function reset_pwd () {
		$this->_init();
		
		$format       = $_GET ['format'] ? $_GET ['format'] : $_POST ['format'];
		$access_token = $_GET ['access_token'] ? $_GET ['access_token'] : $_POST ['access_token'];
		$email        = $_GET ['email'] ? $_GET ['email'] : $_POST ['email'];
		$password     = $_GET ['password'] ? $_GET ['password'] : $_POST ['password'];
		
		if ( !$access_token ) {
			$this->_returnNew(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		if ( !$email ) {
			$this->_returnNew(false, ComFun::getErrorValue('client', '318'), array( 'error' => '318' ) );
		}
		
		//检验access_token合法性
		$token = $this->checkAccess($format, $access_token);

		$User = new User($this->model);
		$reArr = $User->getEmailUserIDNew($email);
		
		if ( $reArr ) {
			$password = $password ? $password : ComFun::getRandomCode(6);
			
			$re = $User->modifyPassword(array(
					'UserID' => $reArr['UserID'],
					'newPWD' => $password,
			));
			
			$this->_returnNew(true, 'ok', array('password' => $password) );
			
			//以下验证应用是否有修改密码的权限
			/*
			$UserOAuth = new UserOAuth($this->model);
			if ( $UserOAuth->isUserBelongAppByUserID(array(
					'UserID' => $reArr['UserID'],
					'AppID'  => $token['client_id'],
			)) ) {
				$re = $User->modifyPassword(array(
						'UserID' => $reArr['UserID'],
						'uPWD'   => $password,
				));
				
				$this->_returnNew(true, 'ok', $password );
			} else {
				$this->_returnNew(false, ComFun::getErrorValue('client', '321'), array('error'=>'321'));
			}
			*/
		} else {
			$this->_returnNew(false, ComFun::getErrorValue('client', '320'), array('error'=>'320'));
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
			case 'MandOAuthLog':
				include_once($root.'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
			default:
				break;
		}
	}
}
?>