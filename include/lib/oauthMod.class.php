<?php
/**
 * OAuth 2.0 服务器端处理
 *
 * @author wbqing405@sina.com
 */

//ini_set('display_errors', true);
//error_reporting(E_ALL);

class oauthMod extends commonMod {

	public function index() {
		$MandOAuth = $this->getClass('MandOAuth');
		exit;
		$callback = urldecode($this->redirect_uri).'?'.'error=100';
		$this->redirect($callback);
		exit;
	}
	/**
	 * 如果返回地址为空，则自动转到这个页面
	 */
	public function callback () {
		if ( $_GET['error'] ) {
			echo ComFun::getErrorValue('client', $_GET['error']);
		}
	}
	/**
	 *  OAuth 2.0 验证第一步
	 */
	public function authorize(){
		$this->assign ( 'title', Lang::get('OAuth_title') );
		
		DBOError::write(' oauth-authorize-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$MandOAuthLog = $this->getClass('MandOAuthLog');

		//请求信息记录
		$oAuthArr['Request_Type']    = 'authorize';
		$oAuthArr['Request_Client']  = $_SERVER['HTTP_REFERER'];
		$oAuthArr['Request_String']  = $_SERVER['QUERY_STRING'];

		$OauthLogID = $MandOAuthLog->setOauthLoginLog($oAuthArr);
		
		DBOError::write(' oauth-authorize-2 | ' . time() . ' | ' . json_encode($_GET));
	
		$client_id     = trim($_GET['client_id']);
		$redirect_uri  = $_GET['redirect_uri'] ? trim($_GET['redirect_uri']) : __ROOT__.$this->config['oauth']['Default_Callback'];
		$response_type = $_GET['response_type'] ? trim($_GET['response_type']) : 'code';
	
		if(!$client_id  || !$redirect_uri || !$response_type){
			$callback = urldecode($redirect_uri).'?'.'error=101';
		
			//请求返回信息记录
			$OauthBackArr['Autoid']      = $OauthLogID;
			$OauthBackArr['Back_State']  = '101';
			$OauthBackArr['Back_String'] = $callback;
			$MandOAuthLog->setOauthBackLoginLog($OauthBackArr);
			
			DBOError::write(' oauth-authorize-3 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->redirect($callback);
			exit;
		}
		
		//记录应用访问记录表		
		$MandOAuthLog->setAuthosizeAppLog($client_id);
		
		DBOError::write(' oauth-authorize-4 | ' . time() . ' | ' . json_encode($_GET));
		
		//判断是否已经登录验证过
		$UserID = $_COOKIE['UserID'] ? ComFun::getCookies('UserID') : '';

		$tcArr['UserID']          = $UserID;
		$tcArr['UserOnLineLogID'] = $this->checkOnLineID();
		$tcArr['client_id']       = $client_id;
		$tcArr['redirect_uri']    = $redirect_uri;

		$MandOAuth = $this->getClass('MandOAuth');
		
		
		//用信息缓存
		$memKey_result = '|oauth|authorize|checkOAuthLogin-1|' . $client_id . '-' . json_encode($tcArr);
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$loginInfo = $menVal;
		} else {
			$loginInfo = $MandOAuth->checkOAuthLogin($tcArr);
			
			$this->_Cache->set( $memKey_result, $loginInfo, $this->config['MEM_EXPIRE'] );
		}
		
		DBOError::write(' oauth-authorize-5 | ' . time() . ' | ' . json_encode($_GET));
		
		if($loginInfo['str'] == 1 && $_COOKIE['code']){	
			//ComFun::getCookies('code')
			//用户访问指定应用记录表
// 			$k=fopen("1.txt","w+");
// 			fwrite($k,$loginInfo['msg']);
// 			fclose($k);

			$LogArr['UserID']    = $UserID;
			$LogArr['client_id'] = $client_id;
			$MandOAuthLog->setAuthosizeUserLog($LogArr);
			
			DBOError::write(' oauth-authorize-6 | ' . time() . ' | ' . json_encode($_GET));
			
			//请求返回信息记录
			$OauthBackArr['Autoid']      = $OauthLogID;
			$OauthBackArr['Back_State']  = 'success';
			$OauthBackArr['Back_String'] = $loginInfo['msg'];
			$MandOAuthLog->setOauthBackLoginLog($OauthBackArr);
			
			DBOError::write(' oauth-authorize-7 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->redirect($loginInfo['msg']);exit;
		}

		if(intval($_GET['dirauth']) == 1){
			$this->assign('msg',Lang::get('FailLoginThird'));
		}

		DBOError::write(' oauth-authorize-8-pre | ' . time() . ' | ' . json_encode($_GET));
		
		//获取应用信息
		//信息缓存
		$memKey_result = '|oauth|authorize|getAuthAppInfo-1|' . $client_id . '-';
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$appInfo = $menVal;
		} else {
			$appInfo = $MandOAuth->getAuthAppInfo($client_id);
				
			$this->_Cache->set( $memKey_result, $appInfo, $this->config['MEM_EXPIRE'] );
		}
		
		DBOError::write(' oauth-authorize-8 | ' . time() . ' | ' . json_encode($_GET));
		
		if(!$appInfo){
			$callback = urldecode($redirect_uri).'?'.'error=113';
				
			//请求返回信息记录
			$OauthBackArr['Autoid']      = $OauthLogID;
			$OauthBackArr['Back_State']  = '113';
			$OauthBackArr['Back_String'] = $callback;
			$MandOAuthLog->setOauthBackLoginLog($OauthBackArr);

			DBOError::write(' oauth-authorize-9 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->redirect($callback);
			exit;
		}
		$this->assign ('theApp', $appInfo['data']['appinfo']['aName']);
		
		//写$_COOKIES
		$newArr['client_id']      = $client_id;
		$newArr['redirect_uri']   = $redirect_uri;
		$newArr['response_type']  = $response_type;
		$newArr['scope']          = $_GET['scope'];
		$newArr['apppermissions'] = $appInfo['data']['apppermissions'];
		
		ComFun::setCookies($newArr);
		
		$urlStr = http_build_query($newArr);
		//站内验证，直接用登录页面
		if( !ComFun::isMobileClient() && in_array($client_id, $this->config['oauth']['login'])){	
			$this->redirect('/index/login?ident=oauthlogin&'.$urlStr);exit;
		}
		$this->tpl->assign('urlStr',$urlStr);
	
		//第三方登录按钮列表
		$apiArr = ComFun::getAPIConfig();
		
		DBOError::write(' oauth-authorize-10 | ' . time() . ' | ' . json_encode($_GET));
		
		$apiCount = 0;	
		foreach($apiArr['providers'] as $key=>$val){
			if($val['enabled']){
				$thirdArr[$key] = $val;
				$JsonKeyArr[] = '"'.$key.'":{"txt":"'.$val['txt'].'","icon":"'.$val['icon'].'"}';
				$apiCount++;
			}
		}
		$jdata['apiCount'] = $apiCount;
		$this->assign ('jdata', json_encode($jdata));
		
		DBOError::write(' oauth-authorize-11 | ' . time() . ' | ' . json_encode($_GET));
		
		$Partners_json = implode(',',$JsonKeyArr);
		$Partners_json = '{'.$Partners_json.'}';

		$redirect = $urlStr.'&ident=mobileLogin&redirect='.urlencode(__ROOT__.'/oauth/login').'&limit='.$limit.'&show=auth';
	
		if( ComFun::isMobileClient() || trim(strtolower($_GET['display'])) == 'mobile' ){  //手机端登录	
			$redirect = $redirect.'&display=mobile';

			DBOError::write(' oauth-authorize-11 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->assign('def_type', ComFun::getPhoneType());
			$this->assign('redirect',$redirect);
			$this->assign ('thirdArr',$thirdArr);
			$this->display ('oauth/login_wap_new.html');exit; //输出模板
			$this->display ('oauth/login_wap.html'); //输出模板
		}else{    //web端登录
			//取应用图像
			$filecode = $appInfo['data']['appinfo']['aIcoCode'];		
			$fileArr  = explode(',',$filecode);
			$codeArr  = explode('|',$fileArr[0]);
			$pic = $this->config['FILE_SERVER_GET'].'&filecode='.$codeArr[0].'&w=150';
			
			//应用开发者
			$soapc = $this->getClass('soapc');
			$tArr['UserKeyID'] = $appInfo['data']['appinfo']['UserKeyID'];
			//信息缓存
			$memKey_result = '|oauth|authorize|getMethod-1|' . $client_id . '-' . json_encode($tArr);
			$menVal = $this->_Cache->get( $memKey_result );
			if ( $menVal ) {
				$soapArr = $menVal;
			} else {
				$soapArr = $soapc->getMethod('UserKey',$tArr);
				$this->_Cache->set( $memKey_result, $soapArr, $this->config['MEM_EXPIRE'] );
			}
			
			DBOError::write(' oauth-authorize-12 | ' . time() . ' | ' . json_encode($_GET));
			
			$user_id = $soapArr['data']['UserID'];
			
			$UserID = $MandOAuth->_getUserID($user_id);
			
			DBOError::write(' oauth-authorize-13 | ' . time() . ' | ' . json_encode($_GET));
			
			//ComFun::pr($UserID);exit;
			$user = $this->getClass('User');
			//信息缓存
			$memKey_result = '|oauth|authorize|getMethod-1|' . $client_id . '-' . $UserID;
			$menVal = $this->_Cache->get( $memKey_result );
			if ( $menVal ) {
				$userDevInfo = $menVal;
			} else {
				$userDevInfo = $user->getUserInfo($UserID);
				$this->_Cache->set( $memKey_result, $userDevInfo, $this->config['MEM_EXPIRE'] );
			}
		
			DBOError::write(' oauth-authorize-14 | ' . time() . ' | ' . json_encode($_GET));
			
			if($userDevInfo){
				$userDev = $userDevInfo[0]['uName'];
			}
			
			//使用应用人数
			$appid = $appInfo['data']['appset']['AppID'];
			
			//信息缓存
			$memKey_result = '|oauth|authorize|CountAppUser-1|' . $client_id . '-' . $appid;
			$menVal = $this->_Cache->get( $memKey_result );
			$count['count'] = $MandOAuthLog->CountAppUser($appid);
			$this->_Cache->set( $memKey_result, $userDevInfo, $this->config['MEM_EXPIRE'] );
	
			if ( $menVal ) {
				$count = $menVal['count'];
			} else {
				$count = $MandOAuthLog->CountAppUser($appid);
				$this->_Cache->set( $memKey_result, array('count' => $count) , $this->config['MEM_EXPIRE'] );
			}
			
			DBOError::write(' oauth-authorize-15 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->assign ('pic',$pic);
			$this->assign ('userDev',$userDev);
			$this->assign ('count',$count);
			$this->assign ('Partners_json',$Partners_json);
			$this->assign('redirect',$redirect);
			
			$this->display ('oauth/login.html'); //输出模板
		}
		
		//请求返回信息记录
		$OauthBackArr['Autoid']      = $OauthLogID;
		$OauthBackArr['Back_State']  = 'success';
		$OauthBackArr['Back_String'] = $callback;

		DBOError::write(' oauth-authorize-16 | ' . time() . ' | ' . json_encode($_GET));
		
		$MandOAuthLog->setOauthBackLoginLog($OauthBackArr);
	}
	/**
	 * 授权登录
	 */
	public function login(){
		DBOError::write(' oauth-login-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->assign ( 'title', Lang::get('OAuth_title') );
		
		$UserID        = ComFun::getCookies('UserID');
		$client_id     = trim($_GET['client_id']);
		$redirect_uri  = $_GET['redirect_uri'] ? trim($_GET['redirect_uri']) : 'error.php';
		$response_type = $_GET['response_type'] ? trim($_GET['response_type']) : 'code';		

		if($redirect_uri == 'error.php'){
			$this->redirect($redirect_uri.'?error=132');exit;
		}
		if(empty($UserID)){
			$this->redirect($redirect_uri.'?error=131');exit;
		}		
		if(empty($client_id)){
			$this->redirect($redirect_uri.'?error=101');exit;
		}		
		
		$tArr['UserID']         = $UserID;
		$tArr['client_id']      = $client_id;
		$tArr['redirect_uri']   = $redirect_uri;
		$tArr['response_type']  = $response_type;
		$tArr['uanPermissions'] = ComFun::getCookies('apppermissions');

		//用户不是第一次使用应用
		$MandOAuthLog = $this->getClass('MandOAuthLog');
		//信息缓存
		$memKey_result = '|oauth|login|isNeedAuthPermLog-1|' . $client_id . '-' . json_encode($tArr);
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$re = $menVal;
		} else {
			$re = $MandOAuthLog->isNeedAuthPermLog($tArr);
			$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		}
		

		DBOError::write(' oauth-login-2 | ' . time() . ' | ' . json_encode($_GET));
		
		if($re){	
			$MandOAuthLog->setAuthosizeUserLog($tArr);  //用户访问指定应用记录表
			
			DBOError::write(' oauth-login-3 | ' . time() . ' | ' . json_encode($_GET));
			
			//获取code值
			$MandOAuth = $this->getClass('MandOAuth');
			//信息缓存
			$memKey_result = '|oauth|login|checkAuthorize-1|' . $client_id . '-' . json_encode($tArr);
			$menVal = $this->_Cache->get( $memKey_result );
			if ( $menVal ) {
				$url = $menVal;
			} else {
				$url = $MandOAuth->checkAuthorize($tArr);	
				$this->_Cache->set( $memKey_result, $url, $this->config['MEM_EXPIRE'] );
			}

			DBOError::write(' oauth-login-4 | ' . time() . ' | ' . json_encode($_GET));
		}else{
			unset($tArr['UserID']);
			$tArr['display'] = $_GET ['display'] ? $_GET ['display'] : $_POST ['display'];
			$url = '/oauth/checkOAuth?'.http_build_query($tArr);
		}
		
		DBOError::write(' oauth-login-5 | ' . time() . ' | ' . json_encode($_GET));

		$this->redirect($url);	
	}
	/**
	 * 确认授权
	 */
	public function checkOAuth(){
		DBOError::write(' oauth-checkOAuth-1 | ' . time() . ' | ' . json_encode($_GET));
		
		//ComFun::destoryCookies();
		$this->assign ( 'title', Lang::get('OAuth_title') );
		
		$UserID       = ComFun::getCookies('UserID');
		$client_id    = $_GET['client_id'];
		$redirect_uri = $_GET['redirect_uri'] ? trim($_GET['redirect_uri']) : 'error.php';
		
		if($redirect_uri == 'error.php'){
			$this->redirect($redirect_uri.'?error=132');exit;
		}
		if(empty($UserID)){
			$this->redirect($redirect_uri.'?error=131');exit;
		}		
		if(empty($client_id)){
			$this->redirect($redirect_uri.'?error=101');exit;
		}
		
		$uArr['client_id']     = $client_id;
		$uArr['redirect_uri']  = $redirect_uri;
		$uArr['response_type'] = $_GET['response_type'];
		$this->assign ('urlStr', http_build_query($uArr));
		
		//使用者称呼
		$user = $this->getClass('User');
		//信息缓存
		$memKey_result = '|oauth|checkOAuth|getUserInfo-1|' . $client_id . '-' . $UserID;
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$userInfo = $menVal;
		} else {
			$userInfo = $user->getUserInfo($UserID);
			$this->_Cache->set( $memKey_result, $userInfo, $this->config['MEM_EXPIRE'] );
		}
		
		DBOError::write(' oauth-checkOAuth-2 | ' . time() . ' | ' . json_encode($_GET));
		
		if($userInfo){
			$userName = $userInfo[0]['uName'];
		}
		$this->assign ('userName', $userName);
			
		$MandOAuthLog = $this->getClass('MandOAuthLog');
		$MandOAuth = $this->getClass('MandOAuth');
		
		//信息缓存
		$memKey_result = '|oauth|checkOAuth|getAuthAppInfo-1|' . $client_id . '-' . $client_id;
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$appInfo = $menVal;
		} else {
			$appInfo = $MandOAuth->getAuthAppInfo($client_id);
			$this->_Cache->set( $memKey_result, $appInfo, $this->config['MEM_EXPIRE'] );
		}
		
		DBOError::write(' oauth-checkOAuth-3 | ' . time() . ' | ' . json_encode($_GET));
		
		if(!$appInfo){
			$callback = $redirect_uri.'?error=113';
			$this->redirect($callback);
			exit;
		}
		
		//开发者权限请求判断
		$rePermArr = array();
		if ( isset($appInfo['data']['apppermissions']) && $_COOKIE['scope'] ) {
			$memKey_result = '|oauth|checkOAuth|apppermissions*scope-1|' . $client_id . '-';
			$menVal = $this->_Cache->get( $memKey_result );
			if ( $menVal ) {
				$rePermArr = $menVal;
			} else {
				$apppermissions = json_decode($appInfo['data']['apppermissions'], true);
				$scope = explode(',', ComFun::getCookies('scope'));
				if ( $apppermissions && $scope ) {
					foreach ( $apppermissions as $_k => $_v ) {
						foreach ( $scope as $_k_2 => $_v_2 ) {
							if ( strtolower($_v_2) == strtolower($_v) ) {
								$rePermArr[] = $_v_2;
							}
						}
					}
				}
				$this->_Cache->set( $memKey_result, $rePermArr, $this->config['MEM_EXPIRE'] );
			}
		}
	
		DBOError::write(' oauth-checkOAuth-4 | ' . time() . ' | ' . json_encode($_GET));
		
		//权限筛选
		$permArr = ComFun::getPermissionInfo();
		
		DBOError::write(' oauth-checkOAuth-5 | ' . time() . ' | ' . json_encode($_GET));
		
		if ( is_array($permArr) ) {
			$_isExcuName = '';
			foreach ( $permArr as $_k => $_v ) {
				unset($_v['contains']);
				if ( $rePermArr ) {
					if ( in_array($_v['permName'], $rePermArr) ) {
						$_v['isDefault'] = true;
						$nPermArr[] = $_v;
						if ( $_v['isDisable'] ) {
							$limit .= '|' . $_v['permName'];
						}
						
						$_isExcuName = $_v['permName'];
					}
				} 
				if ( $_v['isAuth'] && $_v['isDefault'] && $_v['permName'] != $_isExcuName ) {
					$_v['isDefault'] = true;
					$nPermArr[] = $_v;
					if ( $_v['isDisable'] ) {
						$limit .= '|' . $_v['permName'];
					}
					$_isExcuName = '';
				}
			}
		}
		$nPermArr = ComFun::array_sort($nPermArr, 'isDisable', 'asc', true);
		
		$this->assign ('theApp',$appInfo['data']['appinfo']['aName']);
		$this->assign ('permArr',$nPermArr);
		
		if( ComFun::isMobileClient() || trim(strtolower($_GET['display'])) == 'mobile' ){  //手机端登录
			DBOError::write(' oauth-checkOAuth-6 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->display ('oauth/checkAuth_wap.html');
		}else{    //web端登录	
			//取应用图像
			$filecode = $appInfo['data']['appinfo']['aIcoCode'];
			$fileArr  = explode(',',$filecode);
			$codeArr  = explode('|',$fileArr[0]);
			$pic = $this->config['FILE_SERVER_GET'].'&filecode='.$codeArr[0].'&w=150';
				
			//应用开发者
			$soapc = $this->getClass('soapc');
			$tArr['UserKeyID'] = $appInfo['data']['appinfo']['UserKeyID'];
			$soapArr = $soapc->getMethod('UserKey',$tArr);
			
			DBOError::write(' oauth-checkOAuth-7 | ' . time() . ' | ' . json_encode($_GET));
			
			$user_id = $soapArr['data']['UserID'];
			
			$UserID = $MandOAuth->_getUserID($user_id);
			//信息缓存
			$memKey_result = '|oauth|checkOAuth|getUserInfo-1|' . $client_id . '-' . $UserID;
			$menVal = $this->_Cache->get( $memKey_result );
			if ( $menVal ) {
				$userDevInfo = $menVal;
			} else {
				$userDevInfo = $user->getUserInfo($UserID);
				$this->_Cache->set( $memKey_result, $userDevInfo, $this->config['MEM_EXPIRE'] );
			}
			
			DBOError::write(' oauth-checkOAuth-8 | ' . time() . ' | ' . json_encode($_GET));
			
			if($userDevInfo){
				$userDev = $userDevInfo[0]['uName'];
			}
				
			//使用应用人数
			$appid = $appInfo['data']['appset']['AppID'];
			//信息缓存
			$memKey_result = '|oauth|checkOAuth|CountAppUser-1|' . $client_id . '-' . $appid;
			$menVal = $this->_Cache->get( $memKey_result );
			if ( $menVal ) {
				$count = $menVal;
			} else {
				$count = $MandOAuthLog->CountAppUser($appid);
				$this->_Cache->set( $memKey_result, $count, $this->config['MEM_EXPIRE'] );
			}
			
			DBOError::write(' oauth-checkOAuth-9 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->assign ('pic',$pic);
			$this->assign ('userDev',$userDev);
			$this->assign ('count',$count);
			
			$this->display ('oauth/checkAuth.html'); //输出模板
		}
	}
	/**
	 * 保存授权信息
	 */
	public function saveAuth(){
		DBOError::write(' oauth-saveAuth-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$redirect_uri = $_GET['redirect_uri'] ? $_GET['redirect_uri'] : __ROOT__.$this->config['oauth']['Default_Callback'];
		
		if($_POST['type'] == 'cancel'){
			
			$url = $_GET['redirect_uri'].'?error=126';
		}else{		
			$this->assign ( 'title', Lang::get('OAuth_title') );
			
			$UserID       = ComFun::getCookies('UserID');
			$client_id    = $_GET['client_id'];	
			
// 			if($redirect_uri == 'error.php'){
// 				$this->redirect($redirect_uri.'?error=132');exit;
// 			}
			if(empty($UserID)){
				$this->redirect($redirect_uri.'?error=131');exit;
			}		
			if(empty($client_id)){
				$this->redirect($redirect_uri.'?error=101');exit;
			}
			
			//获取code值
			$tArr['client_id']     = $client_id;
			$tArr['redirect_uri']  = $redirect_uri;
			$tArr['response_type'] = $_GET['response_type'];
			$tArr['UserID']        = $UserID;
			
			$MandOAuth = $this->getClass('MandOAuth');
			//信息缓存
			$memKey_result = '|oauth|checkOAuth|checkAuthorize-1|' . $client_id . '-' . json_encode($tArr);
			$menVal = $this->_Cache->get( $memKey_result );
			if ( $menVal ) {
				$url = $menVal;
			} else {
				$url = $MandOAuth->checkAuthorize($tArr);
				$this->_Cache->set( $memKey_result, $url, $this->config['MEM_EXPIRE'] );
			}
			
			DBOError::write(' oauth-saveAuth-2 | ' . time() . ' | ' . json_encode($_GET));
			
			//用户访问指定应用记录表
			$MandOAuthLog = $this->getClass('MandOAuthLog');			
			$LogArr['UserID']          = $UserID;
			$LogArr['client_id']       = $client_id;
			$LogArr['uLimit']          = $_POST['limit'];
			$LogArr['uanScope']        = ComFun::getCookies('scope');
			$LogArr['uanPermissions']  = ComFun::getCookies('apppermissions');
			
			$MandOAuthLog->setAuthosizeUserLog($LogArr);
			
			DBOError::write(' oauth-saveAuth-3 | ' . time() . ' | ' . json_encode($_GET));
		}
		
		$this->redirect($url);
	}
	/**
	 * OAuth 2.0 验证第二步
	 */
	public function token(){		
		$get['grant_type']    = trim($_GET['grant_type']);
		$get['client_id']     = trim($_GET['client_id']);
		$get['redirect_uri']  = trim($_GET['redirect_uri']);
		$get['client_secret'] = trim($_GET['client_secret']);
		$get['code']          = trim($_GET['code']);
		
		$client_id = $get['client_id'];
		
		DBOError::write(' oauth-token-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$MandOAuth = $this->getClass('MandOAuth');
		//信息缓存
		$memKey_result = '|oauth|token|checkToken-1|' . $client_id . '-' . json_encode(array('code' => $get['code'],'redirect_uri' => $get['redirect_uri']));
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$url = $menVal;
		} else {
			$url = $MandOAuth->checkToken($get['code'],$get['redirect_uri']);
			$this->_Cache->set( $memKey_result, $url, $this->config['MEM_EXPIRE'] );
		}
		
		DBOError::write(' oauth-token-2 | ' . time() . ' | ' . json_encode($_GET));
		
		//$url = urldecode($get['redirect_uri']).'?'.$reStr;
// 		$k=fopen("1.txt","w+");
// 		fwrite($k,$url);
// 		fclose($k);
		$this->redirect($url);
	}
	/**
	 * OAuth 2.0 验证第二步
	 */
	public function token2(){
		ECHO 11;EXIT;
		$this->_return($format,$_POST);
		$get['grant_type']    = trim($_POST['grant_type']) ? trim($_POST['grant_type']) : trim($_GET['grant_type']);
		$get['client_id']     = trim($_POST['client_id']) ? trim($_POST['client_id']) : trim($_GET['client_id']);
		$get['redirect_uri']  = trim($_POST['redirect_uri']) ? trim($_POST['redirect_uri']) : trim($_GET['redirect_uri']);
		$get['client_secret'] = trim($_POST['client_secret']) ? trim($_POST['client_secret']) : trim($_GET['client_secret']);
		$get['code']          = trim($_POST['code']) ? trim($_POST['code']) : trim($_GET['code']);
		
		$client_id = $get['client_id'];
		
		DBOError::write(' oauth-token2-1 | ' . time() . ' | ' . json_encode($_GET));
		/*
		if(!$get['grant_type']){
			$this->_return($format,array('error'=>121));
		}
		if(!$get['client_id']){
			$this->_return($format,array('error'=>122));
		}
		if(!$get['redirect_uri']){
			$this->_return($format,array('error'=>123));
		}
		if(!$get['client_secret']){
			$this->_return($format,array('error'=>124));
		}
		*/
		if(!$get['code']){
			$this->_return($format,array('error'=>125));
		}

		$MandOAuth = $this->getClass('MandOAuth');
		
		//信息缓存
		$memKey_result = '|oauth|token2|checkToken2-1|' . $client_id . '-' . json_encode(array('code' => $get['code'],'redirect_uri' => $get['redirect_uri']));
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$url = $menVal;
		} else {
			$re = $MandOAuth->checkToken2($get['code'],$get['redirect_uri']);
			$this->_Cache->set( $memKey_result, $url, $this->config['MEM_EXPIRE'] );
		}
		
		DBOError::write(' oauth-token2-2 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->_return($format,$re);
	}
	/**
	 * 返回信息处理
	 */
	public function _return($format,$data=null) {
		if(isset($format)){
			switch($format){
				case 'json':
				    $rb = json_encode($data);
					break;
				default:
					$rb = json_encode($data);
					break;
			}
		}else{
			$rb = json_encode($data);
		}
		
		echo $rb;exit;
	}
	/**
	 * 取得用户相关信息
	 */
	public function user(){
		$MandOAuth = $this->getClass('MandOAuth');
		$re = $MandOAuth->getUInfo($_GET);
		
		echo json_encode($re);
	}
	/**
	 * 取得用户相关信息
	 */
	public function oauth2(){
// 		echo 44;
// 		$this->pr($_GET);
		$this->display ('oauth/test.html'); //输出模板
	}
	public function test(){
		ComFun::pr($_GET);
	}
	/**
	 * 取得类
	 */
	private function getClass($className){
		$root = dirname(dirname(__FILE__));

		switch($className){
			case 'User':
				include_once($root.'/include/lib/User.class.php');
				return new User($this->model);
				break;
			case 'Login':
				include_once($root.'/include/lib/Login.class.php');
				return new Login($this->model);
				break;
			case 'MandOAuth':
				include_once($root.'/include/lib/MandOAuth.class.php');
				return new MandOAuth($this->model,$this->config);
				break;
			case 'MandOAuthLog':
				include_once($root.'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
			case 'soapc':
				include_once($root.'/include/lib/soapc.class.php');
				return new soapc($this->config['DES']);
				break;
			case 'InviteCode':
				include_once(dirname(dirname(__FILE__)).'/include/lib/InviteCode.class.php');
				return new InviteCode($this->model);
				break;
			case 'DBSoapExpandOauthPerm':
				$this->config['DES']['type']  = 'Expand';
				$this->config['DES']['ident'] = 'private';
				include_once(dirname(dirname(__FILE__)).'/include/lib/DBSoapExpandOauthPerm.class.php');
				return new DBSoapExpandOauthPerm($this->config);
				break;
			default:
				break;
		}
	}
}