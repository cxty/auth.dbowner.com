<?php
/**
 * OAuth 2.0 服务器端处理
 *
 * @author wbqing405@sina.com
 */

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
	 * 权限验证
	 */
	private function _checkValid ($bArr) {
		//需要进行权限验证，通过后就不需要再次验证
		$_ischeckValid = true;
		if ($_COOKIE['oauth_checkValid']) {
			if ( ComFun::getCookies('oauth_checkValid') == true ) {
				$_ischeckValid = false;
			}
		}
		if ($_ischeckValid == true) {
			$this->redirect($this->config['PLATFORM']['Auth'] . '/oauth/doCheckValid?' . $_SERVER['QUERY_STRING'] . '&' . http_build_query($bArr));
		}
	}
	
	/**
	 * 具体权限验证
	 */
	public function doCheckValid () {
		$UserID    = $_GET['UserID'];
		$client_id = $_GET['client_id'];
		
		$recall = $this->config['PLATFORM']['Auth_https'] . '/oauth/authorize?' . $_SERVER['QUERY_STRING'];
		
		if ( $UserID && $client_id ) {
			$LogArr['UserID']    = $UserID;
			$LogArr['client_id'] = $client_id;
				
			//对应用调用进行鉴权
			$mandOAuth = $this->getClass('MandOAuth');
			$user_id = $mandOAuth->getUserID($LogArr);
				
			$tArr['user_id']     = $user_id;
			$tArr['client_id']   = $client_id;
			$tArr['AppPlugInID'] = $this->config['Expand']['AppPlugIn']['InviteCode'];
				
			$dbApiAuth = $this->_getClass('DBApiAuth');
			$plugInfo = $dbApiAuth->checkValid($tArr);
				
			if($plugInfo['result']){
				$atArr['user_id']   = $user_id;
				$atArr['client_id'] = $client_id;
				//用户是否已经激活过
				$reIc = $dbApiAuth->checkUserHadDone($atArr);
					
				if ( $reIc['state'] && !$reIc['result'] ) {
					$furl = $this->config['PLATFORM']['Plus'].'/inviteCode/check?'.http_build_query($atArr);
					$this->assign ( 'furl', $furl );
					$this->assign ( 'host', $this->config['PLATFORM']['Auth'] );
					$this->assign ( 'recall', $recall );
					$this->assign ( 'pageModel', 'inviteCode' );
					$this->display ('oauth/checkValid.html'); //输出模板
					exit;
				}
			} 
			
			ComFun::SetCookies(array('oauth_checkValid' => true));
			
			$this->redirect($recall);
		} else {
			$this->redirect($this->config['PLATFORM']['Auth'] . '/throwMessage/throwMsg?client_id=' . $client_id . '&msgkey=' );
		}
	}
	
	
	/**
	 *  OAuth 2.0 验证第一步
	 */
	public function authorize(){
		$this->assign ( 'title', Lang::get('OAuth_title') );
		
		DBOError::write(' oauth-authorize-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$MandOAuth = $this->getClass('MandOAuth');
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
		
		//$redirectArr = ComFun::exRedirectUri($appInfo['data']['appset']['asReCall']);
		
		//判断回调地址是否合法
		if ( $appInfo['data']['appset']['asReCall'] != $redirect_uri ) {
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 103)));exit;
		}
		
		if(!$client_id  || !$redirect_uri || !$response_type){
			$callback = urldecode($redirect_uri).'?'.'error=101';
		
			//请求返回信息记录
			$OauthBackArr['Autoid']      = $OauthLogID;
			$OauthBackArr['Back_State']  = '101';
			$OauthBackArr['Back_String'] = $callback;
			$MandOAuthLog->setOauthBackLoginLog($OauthBackArr);
			
			DBOError::write(' oauth-authorize-3 | ' . time() . ' | ' . json_encode($_GET));
			
			//$this->redirect($callback);
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 101)));exit;
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
		
		//验证用户是否有使用应用的权限
		$isForbid = $MandOAuthLog->isValidAppForUser ( array(
				'AppID' => $client_id,
				'UserID' => $UserID
		) ) ;
		
		if ( $isForbid === true ) {	
			//$this->redirect(urldecode($redirect_uri).'?'.'error=315');
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 315)));exit;
		}
		
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
		
		if(!$appInfo['data']){
			$callback = urldecode($redirect_uri).'?'.'error=113';
				
			//请求返回信息记录
			$OauthBackArr['Autoid']      = $OauthLogID;
			$OauthBackArr['Back_State']  = '113';
			$OauthBackArr['Back_String'] = $callback;
			$MandOAuthLog->setOauthBackLoginLog($OauthBackArr);

			DBOError::write(' oauth-authorize-9 | ' . time() . ' | ' . json_encode($_GET));
			
			//$this->redirect($callback);
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 113)));exit;
			exit;
		}
		$this->assign ('theApp', $appInfo['data']['appinfo']['aName']);
		
		//写$_COOKIES
		$newArr = ComFun::pickParams($_GET);
		if ( !$newArr['oa_tpl'] ) {
			$newArr['oa_tpl']            = ($_GET['oa_tpl'] ? $_GET['oa_tpl'] : 'web_wide');
		}
		$newArr['apppermissions'] = $appInfo['data']['apppermissions'];
		
		ComFun::setCookies($newArr);
		
		$urlStr = http_build_query($newArr);
	
		//如果已经登录，直接返回授权值
		if ( $UserID ) {
			$user = new User($this->model);
			$UserOnLineLogID = $user->getUserOnLineID(array('UserID' => $UserID, 'uName' => ComFun::getCookies('uName')));
			
			//处理登录，成功后返回3个授权值
			$bArr['UserID']    = $UserID;
			$bArr['client_id'] = $client_id;
			
			//需要进行权限验证，通过后就不需要再次验证
			$this->_checkValid(array(
					'UserID' => $UserID,
					'client_id' => $client_id
			));
			
			$token = $MandOAuth->getAuthToken($bArr);
			
			//$redirect_uri = $redirect_uri . ( $token ? '?' . http_build_query($token) : '' );
			
			//$this->redirect($redirect_uri);exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], $token));exit;
		}
		
		//无论是否为手机端，直接跳到登录界面
		if ( in_array($client_id, $this->config['oauth']['login_direct']) ) {
			$this->redirect('/iframe/login?ident=oauthlogin&'.$urlStr);exit;
		} else {
			//站内验证，直接用登录页面
			if( ComFun::isMobileClient() || trim(strtolower($_GET['display'])) == 'mobile' ){  //手机端登录
					
			} else {
				if( (!ComFun::isMobileClient() && in_array($client_id, $this->config['oauth']['login'])) ){
					$this->redirect('/index/login?ident=oauthlogin&'.$urlStr);exit;
				}
			}	
		}
		
		$this->tpl->assign('urlStr',$urlStr);
	
		//第三方登录按钮列表
		$apiArr = ComFun::getAPIConfig();
		
		DBOError::write(' oauth-authorize-10 | ' . time() . ' | ' . json_encode($_GET));
		
		$showpro = $_GET['show_pro'] ? $_GET['show_pro'] : '';
		$apiCount = 0;
		foreach($apiArr['providers'] as $key=>$val){
			if($val['enabled']){
				$isShow = false;
				
				if ( $showpro ) {
					if ( in_array(strtolower($key), explode(',', $showpro)) ) {
						$isShow = true;
					}
				} else {
					$isShow = true;
				}
				
				if ( $isShow === true ) {
					$thirdArr[$key] = $val;
					$JsonKeyArr[] = '"'.$key.'":{"txt":"'.$val['txt'].'","icon":"'.$val['icon'].'"}';
					$apiCount++;
				}
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
	
			switch ( strtolower($_GET['oa_tpl']) ) {
				case 'web_narrow':
					
					$sys = true;
					$width = 0;
					if ( $_GET['oa_width'] ) {
						$sys = false;
						$width = $_GET['oa_width'] ? intval($_GET['oa_width']) : 0;
						$width = $width < 300 ? 300 : $width;
					}
					
					//是否显示应用信息
					$wordShow = false;
					if ( $_GET['oa_wordShow'] === 'true' ) {
						$sys = false;
						$wordShow = true;
					}
				
					//是否显示logo
					$showLogo = false;
					if ( $_GET['oa_showLogo'] === 'true' ) {
						$sys = false;
						$showLogo = true;
					}
					
					$this->assign('vdata', array(
							'sys' => $sys,
							'wordShow' => $wordShow,
							'showLogo' => $showLogo,
					));
					$this->assign('sData', json_encode(
							array(
									'width' => $width,
									'showLogo' => $showLogo,
							)
					));
					$this->assign('css_cont', 'web_narrow');
					$this->display ('oauth/login_web_narrow.html'); 
					break;
				default: // web_wide
					$this->assign('sData', '');
					$this->assign('css_cont', 'web_wide');
					$this->display ('oauth/login.html'); //输出模板
					break;
			}
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
		
		//判断回调地址是否合法
		if ( $appInfo['data']['appset']['asReCall'] != $redirect_uri ) {
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 103)));exit;
		}
		
		if($redirect_uri == 'error.php'){
			//$this->redirect($redirect_uri.'?error=132');exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 132)));exit;
		}
		if(empty($UserID)){
			//$this->redirect($redirect_uri.'?error=131');exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 131)));exit;
		}		
		if(empty($client_id)){
			//$this->redirect($redirect_uri.'?error=101');exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 101)));exit;
		}		
		
		$tArr['UserID']         = $UserID;
		$tArr['client_id']      = $client_id;
		$tArr['redirect_uri']   = $redirect_uri;
		$tArr['response_type']  = $response_type;
		$tArr['uanPermissions'] = ComFun::getCookies('apppermissions');
		$tArr['tpl']            = ($_GET['tpl'] ? $_GET['tpl'] : 'web_wide');

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
			$tArr['provider']  = $_COOKIE['provider'] ? ComFun::getCookies('provider') : '';
			
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
			//站内验证，直接用登录页面
			unset($tArr['UserID']);
			
			$tArr['display'] = $_GET ['display'] ? $_GET ['display'] : $_POST ['display'];
			$url = '/oauth/checkOAuth?' . (ComFun::pickParams($_GET) ? http_build_query(ComFun::pickParams($_GET)) : '' ); //http_build_query($tArr);
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
		
		//需要进行权限验证，通过后就不需要再次验证
		$this->_checkValid(array(
				'UserID' => $UserID,
				'client_id' => $client_id
		));
		
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
		
		//判断回调地址是否合法
		if ( $appInfo['data']['appset']['asReCall'] != $redirect_uri ) {
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 103)));exit;
		}
		
		if($redirect_uri == 'error.php'){
			//$this->redirect($redirect_uri.'?error=132');exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 132)));exit;
		}
		if(empty($UserID)){
			//$this->redirect($redirect_uri.'?error=131');exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 131)));exit;
		}		
		if(empty($client_id)){
			//$this->redirect($redirect_uri.'?error=101');exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 101)));exit;
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
			
		
		DBOError::write(' oauth-checkOAuth-3 | ' . time() . ' | ' . json_encode($_GET));
		
		if(!$appInfo){
			$callback = $redirect_uri.'?error=113';
			//$this->redirect($callback);exit;
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 113)));exit;
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
			
			
			switch ( strtolower($_GET['oa_tpl']) ) {
				case 'web_narrow':
					$sys = true;
					$width = 0;
					if ( $_GET['oa_width'] ) {
						$sys = false;
						$width = $_GET['oa_width'] ? intval($_GET['oa_width']) : 0;
						$width = $width < 300 ? 300 : $width;
					}
					
					//是否显示应用信息
					$wordShow = false;
					if ( $_GET['oa_wordShow'] === 'true' ) {
						$sys = false;
						$wordShow = true;
					}
				
					//是否显示logo
					$showLogo = false;
					if ( $_GET['oa_showLogo'] === 'true' ) {
						$sys = false;
						$showLogo = true;
					}
					
					$this->assign('vdata', array(
							'sys' => $sys,
							'wordShow' => $wordShow,
							'showLogo' => $showLogo,
					));
					$this->assign('sData', json_encode(
							array(
									'width' => $width,
									'showLogo' => $showLogo,
							)
					));
					
					$this->assign('css_cont', 'web_narrow');
					$this->display ('oauth/checkAuth_web_narrow.html');
					break;
				default: // web_wide
					$this->assign('css_cont', 'web_wide');
					$this->display ('oauth/checkAuth.html'); //输出模板
					break;
			}
		}
	}
	/**
	 * 保存授权信息
	 */
	public function saveAuth(){
		DBOError::write(' oauth-saveAuth-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$redirect_uri = $_GET['redirect_uri'] ? $_GET['redirect_uri'] : __ROOT__.$this->config['oauth']['Default_Callback'];
	
		$client_id    = $_GET['client_id'];
		
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
		
		//判断回调地址是否合法
		if ( $appInfo['data']['appset']['asReCall'] != $redirect_uri ) {
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 103)));exit;
		}
		
		if($_POST['type'] == 'cancel'){
			//$url = $_GET['redirect_uri'].'?error=126';
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 126)));exit;
		}else{		
			$this->assign ( 'title', Lang::get('OAuth_title') );
			
			$UserID       = ComFun::getCookies('UserID');
			
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
		
		header("Location: $url");exit;
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
		$redirect_uri = $get['redirect_uri'];
		
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
		
		DBOError::write(' oauth-token-1 | ' . time() . ' | ' . json_encode($_GET));
		
		//判断回调地址是否合法
		if ( $appInfo['data']['appset']['asReCall'] != $redirect_uri ) {
			$this->redirect(ComFun::recombinationUrl($appInfo['data']['appset']['asReCall'], array('error' => 103)));exit;
		}
		
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
		$get['grant_type']    = trim($_POST['grant_type']) ? trim($_POST['grant_type']) : trim($_GET['grant_type']);
		$get['client_id']     = trim($_POST['client_id']) ? trim($_POST['client_id']) : trim($_GET['client_id']);
		$get['redirect_uri']  = trim($_POST['redirect_uri']) ? trim($_POST['redirect_uri']) : trim($_GET['redirect_uri']);
		$get['client_secret'] = trim($_POST['client_secret']) ? trim($_POST['client_secret']) : trim($_GET['client_secret']);
		$get['code']          = trim($_POST['code']) ? trim($_POST['code']) : trim($_GET['code']);
		
		$format = 'json';
		
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
		$re = $MandOAuth->checkToken2($get['code'],$get['redirect_uri']);
	
		//信息缓存
		$memKey_result = '|oauth|token2|checkToken2-1|' . $client_id . '-' . json_encode(array('code' => $get['code'],'redirect_uri' => $get['redirect_uri']));
		$menVal = $this->_Cache->get( $memKey_result );
		if ( $menVal ) {
			$url = $menVal;
		} else {
			$re = $MandOAuth->checkToken2($get['code'],$get['redirect_uri']);
			$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		}
		
		DBOError::write(' oauth-token2-2 | ' . time() . ' | ' . json_encode($_GET));
	
		$this->_return($format,$re);
	}
	/**
	 * 返回信息处理
	 */
	public function _return($format,$data=null) {
		echo json_encode($data);exit;
		if(isset($format)){
			switch($format){
				case 'json':
					$re = json_encode($data);
					break;
				default:
					$re = json_encode($data);
					break;
			}
		}else{
			$re = json_encode($data);
		}
		
		echo $re;exit;
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