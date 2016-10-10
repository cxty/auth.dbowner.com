<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 登录处理
 *
 */


include_once(DBO_PATH.'lib/Config.class.php'); //引入处理类的编码格式 utf-8

class indexMod extends commonMod {
	
	private $redirectParentType; //登录方式
	
	public function index() {	
		$this->assign ( 'title', Lang::get('Index_title') );

		if(ComFun::getCookies('UserID')){
			$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack');
		}else{
			$this->redirect('/index/login');
		}
	}	
	public function test(){
		$this->display('index/test.html');
	}
	/**
	 * 跨域刷新父框架
	 */
	public function refreshInviCode(){	
// 		$html = '<script>';
// 		//$html .= 'parent.parent.location.reload();';
// 		$html .= 'window.parent.parent.location.reload';
// 		$html .= '</script>';
	
// 		echo $html;
	}
	/**
	 * plus平台是否已经输入了邀请码
	 */
	public function checkInviCode () {
		$mandOAuth = new MandOAuth();
		$user_id = $mandOAuth->doencrypt(ComFun::getCookies('UserID') . '|' . ComFun::getCookies('client_id'));
		$_where = 'AppInfoID = \'' . ComFun::getCookies(ComFun::getCookies('ident').'_client_id') . '\' and TUID = \'' . $user_id . '\'';
		
		$dbInnerSoap = new DBInnerSoap();
		if ( $dbInnerSoap->getInviteCode($_where) === true) {
			$back = 1;
			$cookies['oauth_checkValid'] = true;
		} else {
			$back = 0;
			$cookies['oauth_checkValid'] = false;
		}
		ComFun::SetCookies($cookies);
		
		echo $back;
	}
	public function testss () {
		$dbInnerSoap = new DBInnerSoap();
		$a = $dbInnerSoap->getInviteCode();
	
		ComFun::pr($a);exit;
	
		if ( isset($_COOKIE['plus_inviteCode']) ) {
			echo ComFun::getCookies('plus_inviteCode');
		} else {
			echo false;
		}
		ComFun::pr(ComFun::getCookies());
	}
	
	/**
	 * 是否启用插件，在登录成功时检测调用
	 * user_id：必需，加密后的用户user_id
	 * client_id：必需，请求的应用AppID
	 */
	private function _checkPlugin ( $params ) {
		$user_id   = $params['user_id'];
		$client_id = $params['client_id'];
		
		$dbApiAuth = $this->_getClass('DBApiAuth');
		$plugInfo = $dbApiAuth->checkValid(array(
				'user_id' => $user_id,
				'client_id' => $client_id,
				'AppPlugInID' => $this->config['Expand']['AppPlugIn']['InviteCode']
		));
			
		$this->DBWriteTrace->write('loginCallBack4' . '%%%' . json_encode($plugInfo));
		
		if($plugInfo['result']){
			$atArr['user_id']   = $user_id;
			$atArr['client_id'] = $client_id;
			//用户是否已经激活过
			$reIc = $dbApiAuth->checkUserHadDone($atArr);
		
			$this->DBWriteTrace->write('loginCallBack5');
				
			if ( $reIc['state'] && !$reIc['result'] ) {
				$furl = $this->config['PLATFORM']['Plus'].'/inviteCode/check?' . http_build_query($atArr);
				$this->assign ( 'furl', $furl );
				$this->assign ( 'host', $this->config['PLATFORM']['Auth'] );
				$this->assign ( 'recall', '/' );
				$this->assign ( 'pageModel', 'inviteCode' );
				$this->display ('index/index.html'); //输出模板
				exit;
			}
		}
	}
	
	/**
	 * 清空数据
	 */
	private function _clearInfo () {
		//清空作为第三方登录授权cookies信息
		$identArr = $this->config['Login']['Cookies'];
		$unsetArr = array();
		if ( $identArr && $_COOKIE ) {
			foreach ( $identArr as $k => $v ) {
				if ( $this->config['Login']['Ident'] ) {
					foreach ( $this->config['Login']['Ident'] as $k2 => $v2 ) {
						$unsetArr[$v2] = ComFun::getCookies($v2);
					}
				}
				foreach ( $_COOKIE as $k2 => $v2 ) {
					if ( strstr($k2, $v) ) {
						$unsetArr[$k2] = ComFun::getCookies($k2);
					}
				}
			}
		
			if ( $unsetArr ) {
				ComFun::destoryCookies($unsetArr);
			}
		}
		
		if ( ComFun::getCookies('UserID') == 3 || ComFun::getCookies('me') == true){
			//ComFun::pr($unsetArr);//exit;
		}
	}
	
	/**
	 * 登录之前操作
	 */
	public function _deLoginBefore () {
		//嵌入登录框
		if ( strtolower($_GET['ident']) == 'userbox' ) {
			$this->_clearInfo();
			
			if ( isset($_GET['redirect']) ) {
				$cookies['ident'] = $_GET['ident'];
				$cookies[$cookies['ident'] . '_redirect'] = $_GET['redirect'];
				
				//ComFun::pr($cookies);exit;
				ComFun::SetCookies($cookies);
			}
		}
	}
	
	/**
	 * 登录成功后操作
	 */
	private function _deLoginAfter () {
		$this->_clearInfo();
	}
	
	/**
	 * 成功登录后，回调URL处理
	 */
	public function loginCallBack(){	
		$this->DBWriteTrace->write('loginCallBack1');
		
		if ( isset($_GET['plus_inviteCode']) ) {
			//ComFun::pr(array('aa' => true));exit;
		}
		
		$ident = $_COOKIE['ident'] ? ComFun::getCookies('ident') : '';
		
		$mandOAuth = $this->getClass('MandOAuth');
		//ComFun::pr(ComFun::getCookies());
		
		if(!empty($_COOKIE[$ident . '_client_id']) && !$_COOKIE['inviteCodeUsed']){
			//ComFun::getCookies('inviteCodeUsed')
			
			if(empty($_COOKIE['UserID'])){
				ComFun::throwMsg('Ex_LostParam101');
			}
			
			$client_id = ComFun::getCookies($ident . '_client_id');
			$UserID    = ComFun::getCookies('UserID');
	
			//用户连接应用信息记录
			$MandOAuthLog = $this->getClass('MandOAuthLog');
			$LogArr['UserID']    = $UserID;
			$LogArr['client_id'] = $client_id;
			$MandOAuthLog->setAuthosizeUserLog($LogArr);
			
			$this->DBWriteTrace->write('loginCallBack2');
			
			//对应用调用进行鉴权
			$user_id = $mandOAuth->getUserID($LogArr);
			
			$this->DBWriteTrace->write('loginCallBack3');
	
			$this->_checkPlugin(array(
					'user_id' => $user_id,
					'client_id' => $client_id
			));
			
			$this->DBWriteTrace->write('loginCallBack6');
		}	
		
		$url = ComFun::getCallBack($this->model, $this->config, $mandOAuth);
		setcookie('inviteCodeUsed','',time()-3600,'/');

		//当嵌入框架时，需要刷新父框架
		if ( isset($_COOKIE['redirectParentType']) ) {
			if ( ComFun::getCookies('redirectParentType') ==  true ) {
				ComFun::destoryCookies(array('redirectParentType' => false));
			}
		}
		
if(ComFun::getCookies('UserID') == 3){
	//echo $url;
	//ComFun::pr(ComFun::getCookies());
	//echo $url;exit;
}
		
		$this->_deLoginAfter();
		
		$this->redirect($url);
	}
	/**
	 * 注册处理
	 */
	public function register(){
		$this->DBWriteTrace->write('register1');
		
		$this->assign('title', Lang::get('Index_title'));
		$this->assign('show',$_GET['show'] ? $_GET['show'] : ''); //页眉显示控制
	
		if ( !isset($_COOKIE['ident']) ) {
			ComFun::SetCookies(ComFun::pickCallBack($_GET)); //登录成功后的回调地址
		}
	
		//当嵌入框架时，需要刷新父框架
		if ( in_array($_GET['client_id'], $this->config['oauth']['login_direct']) ) {
			//ComFun::SetCookies(ComFun::pickCallBack($_GET)); //登录成功后的回调地址
			ComFun::SetCookies(array('redirectParentType' => true, 'ident' => 'iframe', 'iframe_client_id' => $_GET['client_id']));
			$this->display ('index/register_nature.html');exit;
		}
		
		$this->DBWriteTrace->write('register2');
		
		if( ComFun::isMobileClient() || trim(strtolower($_GET['display'])) == 'mobile' ){
			$this->display ('index/register_wap.html'); //输出模板
		}else{
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
					$this->display ('index/register_web_narrow.html');
					break;
				default:
					$this->assign('css_cont', 'web_wide');
					$this->display ('index/index.html'); //输出模板
					break;
			}
		}			
	}	
	/**
	 * 处理注册信息
	 */
	public function doregister(){
		if(!is_email($_POST['uEmail'])){
			ComFun::throwMsg('Ex_ErrorValueUsed507');
		}
		
		$login = $this->getClass('Login');
		$login->doRegister($_POST);
		$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack');
	}	
	/**
	 * 验证邮箱是否已经注册
	 */
	public function checkEmail(){
		$user = $this->getClass('User');
		$re['uEmail'] = $user->doCheckEmail($_POST['uEmail']);
		$re['uName']  = $user->docheckUserName($_POST['uName']);
	
		echo json_encode($re);
	}		
	/**
	 * login登录页面信息处理
	 */
	public function login(){
		$this->DBWriteTrace->write('login1');
		
		$this->assign ( 'title', Lang::get('Index_title') );
		
		$this->_deLoginBefore();
	
		if ( strtolower($_GET['ident']) == 'oauthlogin' && $_COOKIE['UserID']) {
			$user = new User($this->model);
			if ( $user->getUserOnlineLogID(ComFun::getCookies('UserID')) == -1 ) {
				ComFun::destoryCookies();
				//$this->redirect('?' . ComFun::makeCallBack($_GET));	
				$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack?'.ComFun::makeCallBack($_GET));
			}
		}

		DBOError::write(' index-login-1 | ' . time() . ' | ' . json_encode($_GET));
		
		//二维码登录图
		$this->assign('qrCode', '/qrCode/dirGetQRCode');

		//登录显示初始化
		$this->assign('loginWay', json_encode(array(
				'loginNum' => $this->config['DB']['Login']['loginNum'],//isset($_COOKIE['loginNum']) ? (intval(ComFun::getCookies('loginNum'))-1) : $this->config['DB']['Login']['loginNum'],
				'loginType' => $this->config['DB']['Login']['loginType'],//isset($_COOKIE['loginType']) ? ComFun::getCookies('loginType') : $this->config['DB']['Login']['loginType'],
				'Auth_Platform' => $this->config['db_oauth']['host']
		)));	
		
		$this->DBWriteTrace->write('login2');
		
		$redirect = ComFun::makeCallBack($_GET);
		
		$this->DBWriteTrace->write('login3');
		
		$this->assign('redirect',$redirect);
		$this->assign('clientSDK', $this->config['DB']['QRCode']['clientSDK']);
		
		if ( !isset($_COOKIE['ident']) ) {
			ComFun::SetCookies(ComFun::pickCallBack($_GET)); //登录成功后的回调地址
		}
		
		$this->DBWriteTrace->write('login4');
		
		//是否已经登录过，登录过直接跳转到回调地址
		if(ComFun::getCookies('UserID')){
			$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack?'.$redirect);
		}
		
		$this->DBWriteTrace->write('login5');
		
		$apiArr = ComFun::getAPIConfig();
		
		$this->DBWriteTrace->write('login6');
		
		DBOError::write(' index-login-2 | ' . time() . ' | ' . json_encode($_GET));
		
		$showpro = $_GET['show_pro'] ? $_GET['show_pro'] : '';
		$i = 0;
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
					$JsonKeyArr[] = '"'.$key.'":{"txt":"'.$val['txt'].'","icon":"'.$val['icon'].'"}';
					$thirdLogin[$i]['partner'] = $key;
					$thirdLogin[$i]['txt']     = $val['txt'];
					$thirdLogin[$i]['icon']    = $val['icon'];
					$i++;
				}
			}
		}
		
		$this->DBWriteTrace->write('login7');

		DBOError::write(' index-login-3 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->assign('thirdLogin', $thirdLogin);
		
		$Partners_json = implode(',',$JsonKeyArr);
		$Partners_json = '{'.$Partners_json.'}';
	
		$this->assign ('Partners_json',$Partners_json);
		
		DBOError::write(' index-login-4 | ' . time() . ' | ' . json_encode($_GET));
	
		$this->assign('vData', json_encode(array(
				'host' => $this->config['PLATFORM']['Auth_https']
		)));
		
		//错误信息，从checkLogin传过来的
		$this->assign('msgkey', $_GET['msgkey'] ? $this->_Lang['JS_LANG'][$_GET['msgkey']] : '');
		
		//登录验证地址
		$this->assign('checkLoginUrl', $this->config['PLATFORM']['Auth_https'] . '/index/checkLoginForm?' . $redirect);
		
		if ( in_array($_GET['client_id'], $this->config['oauth']['login_direct']) ) {
			ComFun::SetCookies(array('redirectParentType' => true, 'ident' => 'iframe', 'iframe_client_id' => $_GET['client_id']));
			
			$this->assign('bg_trans', $_GET['bg_trans'] ? $_GET['bg_trans'] : 0);
			
			$this->display('index/login_nature.html');
		} else {
			$this->display('index/login_new.html');
		}
		exit;
		$this->display ('index/index.html'); //输出模板
	}
	/**
	 * login登录处理
	 */
	public function dologin(){
		$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack');
	}

	/**
	 * 登录用户账户检测
	 */
	public function checkLoginForm(){
		$msgkey = 'Ex_Illegality';
	
		if ( $_POST ) {
			//$login = $this->getClass('Login');
			$login = new Login($this->model);
	
			$UserID = $login->doCheckLogin($_POST);
	
			if ( $UserID > 0 ) {
				//echo '<script>history.back();</script>';exit;
				$msgkey = '';
			} else {
				$msgkey = 'LoginWrongRemind';
			}
	
			//设置初始化登录方式
			$cookies['loginNum'] = $this->config['DB']['Login']['loginNum'];
			$cookies['loginType'] = $this->config['DB']['Login']['loginType'];
			ComFun::SetCookies($cookies);
	
		}
	
		$url = '/index/login?' . ComFun::makeCallBack($_GET) . '&msgkey=' . $msgkey;
	
		$this->redirect($url);
	
		exit;
	}
	
	/**
	 * 登录用户账户检测
	 */
	public function checkLogin(){
		$login = $this->getClass('Login');
		echo $login->doCheckLogin($_POST);
		
		//设置初始化登录方式
		$cookies['loginNum'] = $this->config['DB']['Login']['loginNum'];
		$cookies['loginType'] = $this->config['DB']['Login']['loginType'];
		ComFun::SetCookies($cookies);
	}	
	/**
	 * 登出处理
	 */
   public function loginOut(){
		$login = $this->getClass('Login');		
		$login->doLoginOut();
	
		$this->redirect('/index/login');
	}	
	/**
	 * 脚本登录
	 */
	public function loginOutByjs(){
		$jsonp_callback=$_GET['callback'];//...//如果正确
		
		$login = $this->getClass('Login');
		$login->doLoginOut();
		
		$result = 1;
		
		echo $jsonp_callback."(".json_encode($result).")"; return;
	}
	/**
	 * 第三方登录
	 */
	public function loginThirdParty(){	
		$this->DBWriteTrace->write('loginThirdParty1');
		
		DBOError::write(' index-loginThirdParty-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$provider   = $_GET['provider'];
		$idProvider = $_GET['idProvider'];
		$display    = isset($_GET['display']) ? $_GET['display'] : 'web';
	
		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}		
		
		//$_COOKIES信息记录
		switch($idProvider){
			case 'join':
				$proNum = ComFun::getCookies('proNum') ? (ComFun::getCookies('proNum')+1) : 1;
				$Oprovider = ComFun::getCookies('provider');
				
				if(empty($Oprovider)){
					ComFun::throwMsg('Ex_LostParam206');
				}

				$ckArr['proNum']            = $proNum;
				$ckArr['provider_'.$proNum] = $Oprovider;			
				break;
			case 'dirJoin':
				break;
			case 'dirauth':							
				break;
		}
		$ckArr['display']    = $display;
		$ckArr['idProvider'] = $idProvider;
		$ckArr['provider']   = $provider;	
		ComFun::SetCookies($ckArr);
	
		//请求第三方后，回调处理
		$tArr['display'] = $display;
		$tArr['partner'] = $provider;

		$this->DBWriteTrace->write('loginThirdParty2');
		
		$OAuthCommon = $this->getClass('OAuthCommon',ComFun::getNowApi($provider));		
		$request_url = $OAuthCommon->getRequestAuth(1,$tArr);
		
		$this->DBWriteTrace->write('loginThirdParty3');

		DBOError::write(' index-loginThirdParty-2 | ' . time() . ' | ' . json_encode($_GET));
		
		//设置初始化登录方式
		$ckArr['loginNum'] = $this->config['DB']['Login']['loginNum'];
		$ckArr['loginType'] = $this->config['DB']['Login']['loginType'];
		ComFun::SetCookies($ckArr);

		//echo $request_url;exit;
		
		DBOError::write(' index-loginThirdParty-3 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->DBWriteTrace->write('loginThirdParty4');
		
		if( $request_url ){
			$this->redirect($request_url);
		}			
	}	
	/**
	 * 第三方登录验证
	 */
	public function checkThirdParty(){	
		$this->DBWriteTrace->write('checkThirdParty1');
		
		DBOError::write(' index-checkThirdParty-1 | ' . time() . ' | ' . json_encode($_GET));
	
		$provider = ComFun::getCookies('provider');
		
		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		
		$this->DBWriteTrace->write('checkThirdParty2');
		
		$apiArr   = ComFun::getNowApi($provider);
		
		$this->DBWriteTrace->write('checkThirdParty3' . '%%%' . $provider);
		
		DBOError::write(' index-checkThirdParty-2 | ' . time() . ' | ' . json_encode($_GET));
		
		$authway  = $apiArr['provider']['authway'];
		
		switch($authway){
			case 'auth1':
				$fieldArr['oauth_token']          = $_GET['oauth_token'];
				$fieldArr['oauth_verifier']       = $_GET['oauth_verifier'];
				$fieldArr['oauth_token_secret']   = ComFun::getCookies('oauth_token_secret');
				break;
			case 'auth2':
				break;
			case 'openid':
				$this->pr($_GET);exit;
				break;
		}
		
		$this->DBWriteTrace->write('checkThirdParty4');

   		$OAuthCommon = $this->getClass('OAuthCommon',$apiArr);	
   		$backInfo = $OAuthCommon->getRequestAuth(2,$fieldArr);

   		
   		$this->DBWriteTrace->write('checkThirdParty5' . '%%%' . json_encode($backInfo));
   		
   		DBOError::write(' index-checkThirdParty-3 | ' . time() . ' | ' . json_encode($_GET));
   	
//ComFun::pr($backInfo);exit;
//$provider = 'Baidu';
//$backInfo['access_token'] = '21.49803d58f88f66ba81b8444b4f8b65a3.2592000.1417327845.572901107-579156';
//$backInfo['access_token'] = '21.77ba62ddaa7b5ef979c21dc9318b4235.2592000.1417327799.572901107-585386';
//$backInfo['refresh_token'] = '22.ddadd70f35f5c9b733d4131e4a1e9114.315360000.1730092576.572901107-579156';
		switch(strtolower($provider)){
			case 'renren':
				$backInfo['user_id'] = $backInfo['user']['id'];
				break;
			case 'sina':
				$backInfo['user_id'] = $backInfo['uid'];
				break;
			case 'douban':
				$backInfo['user_id'] = $backInfo['douban_user_id'];
				break;
			//case 'qq': //Oauth 1.0验证
			//	$backInfo['user_id'] = $backInfo['name'];
			//	break;
			//以下通过获取用户信息，取得用户标识信息	
			case 'diandian':
				$backInfo['user_id'] = $backInfo['uid'];
				break;
			case 'tianyi':
				$backInfo['user_id'] = $backInfo['p_user_id'];
				$backInfo['user_id'] = $backInfo['open_id'];
				break;
			case 'wangyi':
				$backInfo['user_id'] = $backInfo['uid'];
				break;
			case 'qq':
				$backInfo['user_id'] = $backInfo['openid'];
				break;
			default:			
				$tArr['partner']  = $provider;
				$tArr['provider'] = $apiArr['provider'];
				$tArr['OAuthArr'] = $backInfo;
				/*
				$host = 'https://auth.dbowner.com';
				$url = $host . '/db/getUserInfo';
				echo $url;
				ComFun::pr($tArr);
				$re = DBCurl::dbGet($url,'get',$tArr);
				ComFun::pr($re);exit;
				*/
				$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',$tArr);
				
				if(strtolower($provider) == 'qq'){
					$backInfo['user_id'] = $backInfo['openid'];
				}else{
					$backInfo['user_id'] = $thirdInfo['uProvider_uid'];
				}
				
				break;	
		}
		
		$this->DBWriteTrace->write('checkThirdParty6' . '%%%' . json_encode($backInfo));
		
	//ComFun::pr($backInfo);exit;	
		DBOError::write(' index-checkThirdParty-4 | ' . time() . ' | ' . json_encode($_GET));
		
		foreach($backInfo as $key=>$val){
			$cookies[$provider.'_'.$key] = $val;
		}

		switch(ComFun::getCookies('idProvider')){
			case 'dirauth': //作为第三方登录验证
				$url = '/index/mandThirdLogin';
				break;
			case 'dirJoin': //在个人信息页面直接进行绑定
				$url = '/index/mandjoinThird';
				break;
			default: //正常登录接口登录验证
				$url = '/index/beforeJoinfrom';
				break;
		}
		
		if($this->config['oauth']['googleLogin'] && strtolower($provider) == 'google'){
			$url = '/index/mandGoogleLogin?callback='.$url;
			$cookies['googleLogin'] = true;
		}
		
		ComFun::SetCookies($cookies);

		DBOError::write(' index-checkThirdParty-5 | ' . time() . ' | ' . json_encode($_GET));
	
		$this->DBWriteTrace->write('checkThirdParty7');
		
		$this->redirect($url);	
	}	
	/**
	 * google登陆单独处理
	 */
	public function mandGoogleLogin(){	
		if($_GET['act'] == 'sub'){	
			$partner = 'Google';
			
			$cookies[$partner.'_user_id']          = $_GET['id'];
			$cookies[$partner.'_uDisplay_name']    = $_GET['name'];
			$cookies[$partner.'_location']         = $_GET['locale'];
			$cookies[$partner.'_uImages']          = $_GET['picture'] ? $_GET['picture'] : 'https://graph.facebook.com/'.$_GET['id'].'/picture?type=large';
			$cookies[$partner.'_uProvider_uid']    = $_GET['id'];
			
			ComFun::SetCookies($cookies);
			
			$this->redirect($_GET['callback']);
		}else{
			$apiInfo = ComFun::getTConditionByCurl(ComFun::getCookies('provider'));
			
			$this->assign('callback', $_GET['callback']);
			$this->assign('hostURL', $apiInfo['provider']['urls']['hostURL']);
			$this->assign('access_token', $apiInfo['OAuthArr']['access_token']);
			$this->display('index/getGoogleInfo.html');
		}
	}
	/**
	 * 做为第三方登录：若注册过账号直接返回，若未注册过则插入默认账号跟默认授权用户信息表
	 */
	public function mandThirdLogin(){	
		$this->DBWriteTrace->write('mandThirdLogin1');
		
		DBOError::write(' index-mandThirdLogin-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$tArr['client_id']     = ComFun::getCookies('client_id');
		$tArr['redirect_uri']  = ComFun::getCookies('redirect_uri');
		$tArr['response_type'] = ComFun::getCookies('response_type');
		$tArr['oa_tpl']        = ComFun::getCookies('oa_tpl');
		if ( $_COOKIE['oa_width'] ) {
			$tArr['oa_width']      = ComFun::getCookies('oa_width');
		}
		if ( $_COOKIE['oa_wordShow'] ) {
			$tArr['oa_wordShow']      = ComFun::getCookies('oa_wordShow');
		}
		if ( $_COOKIE['oa_showLogo'] ) {
			$tArr['oa_showLogo']      = ComFun::getCookies('oa_showLogo');
		}
	
		$this->DBWriteTrace->write('mandThirdLogin2');
		
		$login = $this->getClass('Login');
		$login->doCheckOauthInfo();	
		
		$this->DBWriteTrace->write('mandThirdLogin3');
		
		DBOError::write(' index-mandThirdLogin-2 | ' . time() . ' | ' . json_encode($_GET));
		
		setcookie('idProvider','',time()-3600,'/');

		$this->redirect('/oauth/login?'.http_build_query($tArr).'&idProvider=dirauth&display='.ComFun::getCookies('display'));
	}
	/**
	 * 跳过账户关联，直接注册默认账户跟默认授权信息表
	 */
	public function joinSkip(){
		$this->DBWriteTrace->write('joinSkip1');
		
		DBOError::write(' index-joinSkip-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$login = $this->getClass('Login');
		$login->doCheckOauthInfo();	
		
		$this->DBWriteTrace->write('joinSkip2');
		
		DBOError::write(' index-joinSkip-2 | ' . time() . ' | ' . json_encode($_GET));

		$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack');
	}
	/**
	 * 个人信息页面，直接绑定第三方信息
	 */
	public function mandjoinThird(){
		$this->DBWriteTrace->write('mandjoinThird1');
		
		DBOError::write(' index-mandjoinThird-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$login = $this->getClass('Login');

		if($login->checkOAuthInfo() == -1){
			$login->dirjoinThird(); //直接增加第三方数据	
		}else{
			ComFun::throwMsg('Ex_ErrorValueUsed502');
		}

		DBOError::write(' index-mandjoinThird-2 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->DBWriteTrace->write('mandjoinThird2');
		
		$this->redirect('/main/index');
	}
	/**
	 * 个人信息页面，已有账号进行重绑定
	 */
	public function reBandThirdParty(){
		$this->DBWriteTrace->write('reBandThirdParty1');
		
		DBOError::write(' index-reBandThirdParty-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$login = $this->getClass('Login');
		$user = $this->getClass('User');
		
		$userInfo = $user->getUserInfoByUserID(ComFun::getCookies('UserID')); //用户信息表是否存在
		$authInfo = $login->checkOAuthInfo(); //第三方信息表是否存在
		
		$this->DBWriteTrace->write('reBandThirdParty2');
		
		DBOError::write(' index-reBandThirdParty-2 | ' . time() . ' | ' . json_encode($_GET));
		
		if($userInfo == -1){ 
			ComFun::throwMsg('Ex_ErrorSystem409');
		}else{
			$login->doUserInfoByEmail($userInfo,$authInfo);

			DBOError::write(' index-reBandThirdParty-3 | ' . time() . ' | ' . json_encode($_GET));
			
			$this->redirect('/main/index');
		}
	}
	/**
	 * 正常绑定页面显示处理
	 */
	public function beforeJoinfrom(){	
		$this->DBWriteTrace->write('beforeJoinfrom1');
		
		DBOError::write(' index-beforeJoinfrom-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->assign ( 'title', Lang::get('Index_title') );
		
		$provider = ComFun::getCookies('provider');

		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		
		//验证是否已经绑定且邮箱是否为空
		$login = $this->getClass('Login');
		$re = $login->checkOAuthInfo(); 
		
		$this->DBWriteTrace->write('beforeJoinfrom2');
		
		DBOError::write(' index-beforeJoinfrom-2 | ' . time() . ' | ' . json_encode($_GET));
		
		//已经绑定
		if ( $re['UserID'] && $re['uEmail'] ) {	
			//操作记录
			$rbArr = $login->IsExistUserInfo($re['UserID']);		

			$this->DBWriteTrace->write('beforeJoinfrom3');
			
			DBOError::write(' index-beforeJoinfrom-3 | ' . time() . ' | ' . json_encode($_GET));
			
			if($rbArr != -1){				
				//note：修改为只更新第三方授权值，为了加快速度，其他值先不做更新
				//$login->updateOauthThirdInfo($provider); //更新当前第三方信息 
				$login->updateOauthThirdAccessInfo($provider); //只更新当前第三方授权值信息
				
				DBOError::write(' index-beforeJoinfrom-4 | ' . time() . ' | ' . json_encode($_GET));
				
				$login->doUserInfoByThirdParty($rbArr);  //操作通过第三方验证绑定的第三方信息
				
				$this->DBWriteTrace->write('beforeJoinfrom4');
				
				DBOError::write(' index-beforeJoinfrom-5 | ' . time() . ' | ' . json_encode($_GET));
				
				$login->setSuccessLog($rbArr); //登录成功后，更新日志表和写$_COOKIES
				
				DBOError::write(' index-beforeJoinfrom-6 | ' . time() . ' | ' . json_encode($_GET));

				//如果是通过第三方登录的
				if ( $_COOKIE['provider'] ) {
					//若已经绑定过同一第三方账号，则废除之前账号，启用现在第三方账号
					$tArr['uProvider']     = ComFun::getCookies('provider');
					$tArr['uProvider_uid'] = ComFun::getCookies(ComFun::getCookies('provider') . '_user_id');
					$tArr['UserID']        = $re['UserID'];
					$login->updateOldThirdPartyAccount($tArr);
					
					DBOError::write(' index-beforeJoinfrom-7 | ' . time() . ' | ' . json_encode($_GET));
				}
				
				$this->DBWriteTrace->write('beforeJoinfrom5');
				
				//如果是通过第三方登录的，且同一第三方绑定过两个账户的
				if ( $_COOKIE['repeat_provider'] ) {
					//若已经绑定过同一第三方账号，则废除之前账号，启用现在第三方账号
					$tArr['uProvider']     = ComFun::getCookies('repeat_provider');
					$tArr['uProvider_uid'] = ComFun::getCookies(ComFun::getCookies('repeat_provider') . '_user_id');
					$tArr['UserID']        = $re['UserID'];
					$login->updateOldThirdPartyAccount($tArr);
					
					DBOError::write(' index-beforeJoinfrom-8 | ' . time() . ' | ' . json_encode($_GET));
					
					ComFun::destoryCookies(array('repeat_provider' => ''));
				}
				
				$this->DBWriteTrace->write('beforeJoinfrom6');
				
				$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack');
			}else{				
				ComFun::throwMsg('Ex_ErrorSystem402');
			}
		}

		if($this->config['oauth']['googleLogin'] && strtolower($provider) == 'google'){
			$userInfo = ComFun::getUserInfoCookies();
		}else{
			//未绑定的情况
			$userInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));
		}	
// if(ComFun::getCookies('provider') == 'Tianyi'){
// 	echo '<img src="http://api.189.cn/upc/vitual_identity/user_avatar?app_id=252914040000030815&access_token=67f1a59813f83d476f05bae84bf534501363236672125&type=json" />';
// 	ComFun::pr(ComFun::getCookies());
//	ComFun::pr($userInfo);exit;
// }

		$this->DBWriteTrace->write('beforeJoinfrom7');
		
		DBOError::write(' index-beforeJoinfrom-9 | ' . time() . ' | ' . json_encode($_GET));
		
		if(isset($userInfo['error'])){
			ComFun::throwMsg('Ex_ErrorSystem401');
		}

		$this->assign('userInfo', $userInfo);
		$this->display ('index/index.html'); //输出模板
	}
	/**
	 * 关联账户时，绑定邮箱情况处理
	 */
	public function checkJoinfrom(){
		$this->DBWriteTrace->write('checkJoinfrom1');
		
		DBOError::write(' index-checkJoinfrom-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$uEmail = $_POST['uEmail'];
		
		if ( empty($uEmail) ) { 
			echo '{"OauthID":"-2"}';exit; 
		}
		
		$login = $this->getClass('Login');
		$user = $this->getClass('User');
		
		$authInfo = $login->checkOAuthInfo(); //第三方信息表是否存在所用的第三方
		
		$this->DBWriteTrace->write('checkJoinfrom2');
		
		DBOError::write(' index-checkJoinfrom-2 | ' . time() . ' | ' . json_encode($_GET));
		
		$userInfo = $user->getEmailUserID($uEmail); //用户信息表是否存在	

		$this->DBWriteTrace->write('checkJoinfrom3');
		
		DBOError::write(' index-checkJoinfrom-3 | ' . time() . ' | ' . json_encode($_GET));
		
		$thirdInfo = $login->thirdBandedInfo($uEmail); //邮箱已经绑定过第三方
		
		$this->DBWriteTrace->write('checkJoinfrom4');
		
		DBOError::write(' index-checkJoinfrom-4 | ' . time() . ' | ' . json_encode($_GET));

		//邮箱是否已经被注册了
		if ( $userInfo['UserID'] ) {
			$accountInfo['UserID'] = $userInfo['UserID'];
		} else {
			$accountInfo['UserID'] = -1;
		}
		//邮箱账号是否已经有密码
		if ( $userInfo['uPWD'] ) {
			$accountInfo['pwd'] = 1;
		} else {
			$accountInfo['pwd'] = -1;
		}

		//登录的账号是否已经绑定过了
		if ($authInfo != -1) {
			$OauthID = $authInfo['UserAuthenticationsID'];
		} else {
			$OauthID = -1;
		}
		
		$_repeat = false; //此前登录的第三方，是否已经有相同第三方账号绑定过了，默认否
		if ( is_array($thirdInfo) ) { //第三方信息存在
			$apiArr = ComFun::getNowApi();
			foreach ( $thirdInfo  as $key=>$val ) {
				if ( $val['uProvider'] ==  ComFun::getCookies('provider')  ) {
					$_repeat = true;
				} else {
					$uProvider = ucfirst($val['uProvider']);
					$JsonKeyArr[] = '"'.$uProvider.'":{"txt":"'.$apiArr['providers'][$uProvider]['txt'].'","icon":"'.$apiArr['providers'][$uProvider]['icon'].'"}';
				}
			}	
			$Partners_json = implode(',',$JsonKeyArr);
			$msg = 1;
		}else{
			$Partners_json = '';
			$msg = -1;
		}
		
		if ( $_repeat ) {
			$cookies['repeat_provider'] = ComFun::getCookies('provider');
			ComFun::SetCookies($cookies);
		}

		$this->DBWriteTrace->write('checkJoinfrom5');
		
		DBOError::write(' index-checkJoinfrom-5 | ' . time() . ' | ' . json_encode($_GET));
		
		$MandOAuth = new MandOAuth($this->model);
		$DBOwnerSoapClient_Dev = new DBOwnerSoapClient_Dev($this->config);
		$DBOwnerSoapClient_Pay = new DBOwnerSoapClient_Pay($this->config);
		$DBOwnerSoapClient_Push = new DBOwnerSoapClient_Push($this->config);
		$DBOwnerSoapClient_Ads = new DBOwnerSoapClient_Ads($this->config);
		$DBOwnerSoapClient_Expand = new DBOwnerSoapClient_Expand($this->config);
		
		$authList = array();
		$userList = array();
		if ( $authInfo ) {
			//D币总额
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['pay']);
			$authDBTotal = $DBOwnerSoapClient_Pay->GetDBTotal($user_id);
			$authList['db_total'] = array(
					'title' => Lang::get('CheckDBTitle'),
					'total' => '<font style="color:red;">' . ($authDBTotal['state'] ? $authDBTotal['data'] : 0) . '</font> ' . Lang::get('DB')	
			);
			
			$this->DBWriteTrace->write('checkJoinfrom6');
				
			//app列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['dev']);
			$authAppList = $DBOwnerSoapClient_Dev->GetAppAllInfoListByUserID($user_id);
			$authList['list_app'] = array(
					'title' => Lang::get('CheckAppList'),
					'list' => $authAppList['count'] > 0 ? $authAppList['list'] : array()
			);
		
			$this->DBWriteTrace->write('checkJoinfrom7');
			
			//push列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['push']);
			$authPushList = $DBOwnerSoapClient_Push->GetUserAppList($user_id);
			$authList['list_push'] = array(
					'title' => Lang::get('CheckPushList'),
					'list' => $authPushList['state'] ? $authPushList['data'] : array()
			);
			
			$this->DBWriteTrace->write('checkJoinfrom8');
				
			//广告列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['ads']);
			$authADList = $DBOwnerSoapClient_Ads->GetUserAppList($user_id);
			$authList['list_ad'] = array(
					'title' => Lang::get('CheckAdList'),
					'list' => $authADList['state'] ? $authADList['data'] : array()
			);
			
			$this->DBWriteTrace->write('checkJoinfrom9');
				
			//扩展列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['expand']);
			$authExpandList = $DBOwnerSoapClient_Expand->GetUserExpandList($user_id);
			$authList['list_expand'] = array(
					'title' => Lang::get('CheckExpandList'),
					'list' => $authExpandList['state'] ? $authExpandList['data'] : array()
			);
			
			$this->DBWriteTrace->write('checkJoinfrom10');
		}
		
		$userInfo = $user->getEmailUserID($uEmail); //用户信息表是否存在
		
		$this->DBWriteTrace->write('checkJoinfrom11');
		
		if ( $userInfo ) {
			//D币总额
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['pay']);
			$userDBTotal = $DBOwnerSoapClient_Pay->GetDBTotal($user_id);
			$userList['db_total'] = array(
					'title' => Lang::get('CheckDBTitle'),
					'total' => '<font style="color:red;">' . ($userDBTotal['state'] ? $userDBTotal['data'] : 0) . '</font> ' . Lang::get('DB')
			);
			
			$this->DBWriteTrace->write('checkJoinfrom12');
				
			//app列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['dev']);
			$userAppList = $DBOwnerSoapClient_Dev->GetAppAllInfoListByUserID($user_id);
			$userList['list_app'] = array(
					'title' => Lang::get('CheckAppList'),
					'list' => $userAppList['count'] > 0 ? $userAppList['list'] : array()
			);
			
			$this->DBWriteTrace->write('checkJoinfrom13');
				
			//push列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['push']);
			$userPushList = $DBOwnerSoapClient_Push->GetUserAppList($user_id);
			$userList['list_push'] = array(
					'title' => Lang::get('CheckPushList'),
					'list' => $userPushList['state'] ? $userPushList['data'] : array()
			);
			
			$this->DBWriteTrace->write('checkJoinfrom14');
				
			//广告列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['ads']);
			$userADList = $DBOwnerSoapClient_Ads->GetUserAppList($user_id);
			$userList['list_ad'] = array(
					'title' => Lang::get('CheckAdList'),
					'list' => $userADList['state'] ? $userADList['data'] : array()
			);
			
			$this->DBWriteTrace->write('checkJoinfrom15');
				
			//扩展列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['expand']);
			$userExpandList = $DBOwnerSoapClient_Expand->GetUserExpandList($user_id);
			$userList['list_expand'] = array(
					'title' => Lang::get('CheckExpandList'),
					'list' => $userExpandList['state'] ? $userExpandList['data'] : array()
			);
			
			$this->DBWriteTrace->write('checkJoinfrom16');
		}
		
		echo '{
				"OauthID":"' . $OauthID . '",
				"repeat":"' . $_repeat . '",
				"accountInfo":' . json_encode($accountInfo) . ',
				"data":{
					"msg":"' . $msg . '",
					"data":{' . $Partners_json . '}
				},
				"info":{
					"auth":' . ($authInfo ? json_encode($authInfo) : '') . ',
					"user":' . ($userInfo ? json_encode($userInfo) : '') . '
				},
				"list":{
					"auth":' . ($authList ? json_encode($authList) : '') . ',
					"user":' . ($userList ? json_encode($userList) : '') . '
				}
			}';
	}
	
	/**
	 * 确认重新绑定
	 */
	public function repeatComfirm () {
		
		$this->assign('lData', json_encode(array(
				'FilePath' => $this->config['FILE_SERVER_GET'],
				'NullData' => Lang::get('NullData'),
				'ConfirmBinding' => Lang::get('ConfirmBinding'),
				'ConfirmBinded' => Lang::get('ConfirmBinded'),
		)));
		
		$this->display();
	}
	
	/**
	 * 验证密码是否正确
	 */
	public function checkPassword(){
		$this->DBWriteTrace->write('checkPassword1');
		
		DBOError::write(' index-checkPassword-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$tArr['uPWD']   = $_GET['pwd'];
		$tArr['uEmail'] = $_GET['uEmail'];

		$user = $this->getClass('User');		
		$gbArr = $user->checkBeforeLogin($tArr);

		$this->DBWriteTrace->write('checkPassword2');
		
		DBOError::write(' index-checkPassword-2 | ' . time() . ' | ' . json_encode($_GET));
		
		if($gbArr != -1){
			echo 1;
		}else{
			echo -1;
		}	
	}
	/**
	 * 关联账户:用邮箱进行绑定
	 */
	public function joinfrom(){	
		$this->DBWriteTrace->write('joinfrom1');
		
		DBOError::write(' index-joinfrom-1 | ' . time() . ' | ' . json_encode($_GET));
		
		$uEmail = $_POST['uEmail'];

		if(empty($uEmail)){
			ComFun::throwMsg('Ex_NotEmpty601');exit; 
		}
		
		$login = $this->getClass('Login');
		$user = $this->getClass('User');
		
		$userInfo = $user->getEmailUserID($uEmail); //用户信息表是否存在	
		
		$this->DBWriteTrace->write('joinfrom2');
		
		DBOError::write(' index-joinfrom-2 | ' . time() . ' | ' . json_encode($_GET));
		
		$authInfo = $login->checkOAuthInfo(); //第三方信息表是否存在
		
		DBOError::write(' index-joinfrom-3 | ' . time() . ' | ' . json_encode($_GET));
	
		if($userInfo != -1){ //用户已经存在
			$UserID = $userInfo['UserID'];
			$login->doUserInfoByEmail($userInfo, $authInfo);
			
			$this->DBWriteTrace->write('joinfrom3');
			
			DBOError::write(' index-joinfrom-4 | ' . time() . ' | ' . json_encode($_GET));
		}else{ //用户不存在
			$UserID = $authInfo['UserID'];
			$tArr['uEmail']   = $uEmail;
			
			if($authInfo != -1){ //第三方信息存在
				$tArr['provider']   = ComFun::getCookies('provider');
				$tArr['UserID']     = $authInfo['UserID'];
				$tArr['uName']      = $_POST['uDisplay_name'];

				//ComFun::throwMsg('Ex_ErrorSystem402');
				$login->addAccountEmail($tArr);
				
				$this->DBWriteTrace->write('joinfrom4');
				
				DBOError::write(' index-joinfrom-5 | ' . time() . ' | ' . json_encode($_GET));
			}else{ //第三方信息不存在
				$login->addJoinAccountInfo($tArr);
				
				$this->DBWriteTrace->write('joinfrom6');
				
				DBOError::write(' index-joinfrom-6 | ' . time() . ' | ' . json_encode($_GET));
			}
		}
		
		//如果是通过第三方登录的
		if ( $_COOKIE['repeat_provider'] ) {
			//若已经绑定过同一第三方账号，则废除之前账号，启用现在第三方账号
			$tArr['uProvider']     = ComFun::getCookies('repeat_provider');
			$tArr['uProvider_uid'] = ComFun::getCookies(ComFun::getCookies('repeat_provider') . '_user_id');
			$tArr['UserID']        = $UserID;
			$login->updateOldThirdPartyAccount($tArr);
			
			$this->DBWriteTrace->write('joinfrom7');
			
			DBOError::write(' index-joinfrom-7 | ' . time() . ' | ' . json_encode($_GET));
			
			ComFun::destoryCookies(array('repeat_provider' => ''));
		}

		$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack');
	}	
	/**
	 * 解除绑定
	 */
	public function unBinding(){
		$login = $this->getClass('Login');
		$login->doUnbinding($_GET);
	}
	/**
	 * 检查UserID是否存在$_COOKIE中
	 */
	function checkCookieUserID(){
		if ( $_COOKIE['UserID'] ) {
			echo ComFun::getCookies('UserID');
		}else{
			echo -1;
		}
	}
	/**
	 * 注册后未激活处理
	 */
    public function befordActivate(){
		$this->assign ( 'title', Lang::get('Index_title') );
		
		$backArr['msg'] = Lang::get('BeforeActivate');
		$backArr['url'] = __ROOT__.'/main/index.html';
	
		$this->assign('msg',$backArr['msg']);
		$this->assign('url',$backArr['url']);
	
		$this->display ('index/index.html'); //输出模板
	}
	/**
	 * 测试方法
	 */
	public function email(){
		exit;
		$a = json_decode('{"methods": {"get": {"id": "plus.people.get", "path": "people/{userId}", "httpMethod": "GET", "parameters": {"userId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Person"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}, "list": {"id": "plus.people.list", "path": "people/{userId}/people/{collection}", "httpMethod": "GET", "parameters": {"collection": {"type": "string", "required": true, "enum": ["visible"], "location": "path"}, "maxResults": {"type": "integer", "default": "100", "format": "uint32", "minimum": "1", "maximum": "100", "location": "query"}, "orderBy": {"type": "string", "enum": ["alphabetical", "best"], "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "userId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "PeopleFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login"]}, "listByActivity": {"id": "plus.people.listByActivity", "path": "activities/{activityId}/people/{collection}", "httpMethod": "GET", "parameters": {"activityId": {"type": "string", "required": true, "location": "path"}, "collection": {"type": "string", "required": true, "enum": ["plusoners", "resharers"], "location": "path"}, "maxResults": {"type": "integer", "default": "20", "format": "uint32", "minimum": "1", "maximum": "100", "location": "query"}, "pageToken": {"type": "string", "location": "query"}}, "response": {"$ref": "PeopleFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}, "search": {"id": "plus.people.search", "path": "people", "httpMethod": "GET", "parameters": {"language": {"type": "string", "default": "en-US", "location": "query"}, "maxResults": {"type": "integer", "default": "10", "format": "uint32", "minimum": "1", "maximum": "20", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "query": {"type": "string", "required": true, "location": "query"}}, "response": {"$ref": "PeopleFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}}}', true);
		ComFun::pr($a);exit;
		$emArr['uName']   = '吴本清';
		$emArr['uEmail']  = '379182261@qq.com';
		$emArr['uCode']   = '0cdb5f893bb9b81eab2198c7a';
		$emArr['type']    = 'invitecode';
		ComFun::pr($emArr);
//exit;
 		ComFun::toSendMail($emArr);
		exit;
	}
	/**
	 * 激活处理
	 */
	public function activate(){
		$this->assign ( 'title', Lang::get('Index_title') );
		
		$login = $this->getClass('Login');

		$rbArr = $login->doActivate($_GET);
	
		switch($rbArr['type']){
			case 'invitecode':
				$this->redirect( __ROOT__.'/index/inviteCode?inviteCode='.$rbArr['inviteCode']);
				break;
			case 'retakepwd':
				$DBCodeInfo = new DBCodeInfo($this->model, $this->config);
				$re = $DBCodeInfo->get(array('Code' => $rbArr['uCode']));
				
				if ( $re ) {
					if ( $re['cIsUser'] == 1 ) {
						$backArr['msg'] = Lang::get('CodeActivateUsed');
					} else {
						$DBCodeInfo->update(array('Code' => $rbArr['uCode']), array('cIsUser' => 1));
							
						$backArr['type']   = 'backpwd';
						$backArr['view']   = 'byEmail';
						$backArr['uEmail'] = $rbArr['uEmail'];
							
						$_GET['_action'] = 'resetpwd';
							
						$this->assign('backArr',$backArr);
						$this->display ('index/index.html'); //输出模板
						exit;
					}
				} else {
					$backArr['msg'] = Lang::get('CodeActivateValid');
				}
				break;
			case 'turnToMain':
				$this->redirect('/main/index');
				exit;
				break;
			case 'authaccount':
				if($rbArr['status']){
					$backArr['msg'] = Lang::get('SuccessActivate');
				}else{
					$backArr['msg'] = Lang::get('FailActivate');
				}
				break;
			case 1:
				$backArr['msg'] = Lang::get('SuccessActivate');
				break;
			case 2:
				$backArr['msg'] = Lang::get('SuccessReActivate');
				break;
			default:
				$backArr['msg'] = Lang::get('FailActivate');
				break;
		}
		
		$backArr['appshow'] = false;
		$backArr['urlTurn'] = '/main/index';
		$backArr['url']     = '/main/index';
		$backArr['retry']   = $_SERVER['PHP_SELF'];
		
		$this->assign('backArr',$backArr);
		$this->display('throwMessage/message.html');
	}
	/**
	 * URL激活账户
	 */
	public function inviteCode(){
		$InviteCode = $_GET['ic_InviteCode'] ? $_GET['ic_InviteCode'] : ($_GET[0] ? $_GET[0] : $_GET['inviteCode']);

		if(!$InviteCode){
			ComFun::throwMsg('Ex_LostParam208');exit;
		}
		
		$UserID = ComFun::getCookies('UserID');		
		if($UserID){
			$dbArr['InviteCode'] = $InviteCode;
			$dbApiAuth = $this->_getClass('DBApiAuth');
		
			$re = $dbApiAuth->getClientIDByInviteCode($dbArr);
			if($re['state'] === false){
				ComFun::throwMsg('Ex_LostParam207');exit;
			}
			$client_id = $re['result'];
	
			$LogArr['UserID']    = $UserID;
			$LogArr['client_id'] = $client_id;
			
			$mandOAuth = $this->getClass('MandOAuth');
			//对应用调用进行鉴权
			$user_id = $mandOAuth->getUserID($LogArr);
			
			$atArr['user_id']   = $user_id;
			$atArr['client_id'] = $client_id;
			//用户是否已经激活过
			
			$reIc = $dbApiAuth->checkUserHadDone($atArr);
	
			if(!$reIc['result']){
				$atArr['InviteCode'] = $InviteCode;
				$furl = $this->config['PLATFORM']['Plus'].'/inviteCode/check?'.http_build_query($atArr);
				$this->assign ( 'furl', $furl );
				$this->assign ( 'pageModel', 'inviteCode' );
				$this->display ('index/index.html'); //输出模板
				exit;
			}else{
				$aArr['UserID']       = $UserID;
				$aArr['client_id']    = $client_id;	
				if($client_id == $this->config['DB']['Platform_Plus']['app_url'][0]['client_id']){
					$aArr['redirect_uri'] = $this->config['DB']['Platform_Plus']['app_url'][0]['url'];
					$msg = Lang::get('Ex_ErrorSystem414');
				}else{
					$aArr['redirect_uri'] = '/main/index';		
					$msg = Lang::get('Ex_ErrorSystem413');
				}
				$url = ComFun::getAuthValue($this->model, $this->config, $aArr, $mandOAuth);
				
				$url = '/throwMessage/throwMsg?msg='.ComFun::__encrypt($msg).'&urlTurn='.ComFun::__encrypt($url);			
				
				$this->redirect($url);
			}
		}else{
			$uArr['ic_InviteCode'] = $InviteCode;
			$this->redirect('/index/login?ident=inviteCode&'.ComFun::makeCallBack($uArr));
		}	

		exit;
		$UserID = ComFun::getCookies('UserID');
		if($UserID){
			$tArr['inviteCode'] = $_GET['inviteCode'];
			$tArr['UserID']     = $UserID;
			
			$inviteCode = $this->getClass('InviteCode');
			$rbArr = $inviteCode->getUserActive($tArr);

			if($rbArr == -1){
				if($inviteCode->IsInviteCodeValid($tArr) == -1){
					ComFun::throwMsg('Ex_ErrorValueUsed504');
				}elseif($inviteCode->IsUseInviteCode($tArr) == -1){
					ComFun::throwMsg('Ex_ErrorValueUsed503');
				}else{
					$inviteCode->UseInviteCode($tArr);
					$url = $this->config['db_oauth']['host'] . '/index/loginCallBack';
				}
			}else{
				$t2Arr['client_id'] = $rbArr['client_id'];
				ComFun::throwMsg('Ex_ErrorValueUsed505',$t2Arr);
			}							
		}else{
			$url = '/index/login?ident=inviteCode&'.ComFun::makeCallBack($_GET);
		}	
		$this->redirect($url);
	}
	/**
	 * 激活码是否已经使用
	 */
	public function useActiveCode(){
		$tArr['inviteCode'] = $_GET['inviteCode'];
		$tArr['client_id']  = $_GET['client_id'];
		$tArr['UserID']     = ComFun::getCookies('UserID');

		$dbSoapExpandInviteCode = $this->getClass('DBSoapExpandInviteCode');
		echo $dbSoapExpandInviteCode->activeInviteCode($tArr);
	}
	/**
	 * 重置密码：进行密码修改
	 */
	public function resetPwd(){
		$login = $this->getClass('Login');

		if($_GET['type'] == 'byEmail'){
			echo $login->doResetPwdByEmail($_GET);
		}elseif($_GET['type'] == 'byCenter'){
			$UserID = ComFun::getCookies('UserID');
			if(!$UserID){
				echo -2;exit;
			}

			$tArr['UserID'] = $UserID;
			$tArr['uPWD']   = $_GET['opwd'];
			
			if($login->checkOrigPwd($tArr) == -1){
				echo -3;exit;
			}
			
			$tArr['uPWD']   = $_GET['pwd'];
			
			echo $login->doResetPwdByCenter($tArr);
		}else{
			echo -1;
		}
	}
	/**
	 * 忘记密码
	 */
	public function forgetPass(){
		//ComFun::pr($_GET);
		
		//当嵌入框架时，需要刷新父框架
		if ( in_array($_GET['client_id'], $this->config['oauth']['login_direct']) ) {
			//ComFun::SetCookies(ComFun::pickCallBack($_GET)); //登录成功后的回调地址
			ComFun::SetCookies(array('redirectParentType' => true, 'ident' => 'iframe', 'iframe_client_id' => $_GET['client_id']));
			$this->display ('index/forgetPass_nature.html');exit;
		}
		
		$this->display('index/forgetPass.html');
	}
	/**
	 * 忘记密码处理
	 */
	public function sendforgetPassMail(){
		$uEmail = $_GET['uEmail'];
		if(!$uEmail){
			echo -1;
		}elseif(!ComFun::checkEmail($uEmail)){
			echo -2;
		}else{	
			$user = $this->getClass('User');
			$userInfo = $user->getEmailUserID($uEmail);

			if($userInfo != -1){
				$uCode = ComFun::getRandom();
				
				//验证码存储
				$DBCodeInfo = new DBCodeInfo($this->model, $this->config);
				$DBCodeInfo->add(array('Code' => $uCode));
				
				$emArr['uName']   = $userInfo['uName'];
				$emArr['uEmail']  = $uEmail;
				$emArr['uCode']   = $uCode;
				$emArr['type']    = 'retakepwd';
		
				ComFun::toSendMail($emArr);
				
				echo 1;
			}else{
				echo -3;
			}	
		}
	}
	
	/**
	 * 嵌入修改密码
	 */
	public function changePass () {
		$params = array(
				'user_id'   => $this->GetStringAddslashes('user_id'),
				'msgkey'    => $this->GetStringAddslashes('msgkey'),
		);
		
		if ( !$_COOKIE['frame_user_id'] ) {
			ComFun::SetCookies(array(
					'frame_user_id' => $params['user_id'], //此值只做为修改密码使用
			));
		}
	
		$this->assign('vdata', array(
				'actUrl' => '/index/saveChangePass',
				'error'  => $params['msgkey'] ? $this->_Lang['JS_LANG'][$params['msgkey']] : '',
		));
		
		$this->display();
	} 
	
	/**
	 * 嵌入密码修改保存
	 */
	public function saveChangePass () {
		$params = array(
				'oPwd'      => $this->GetStringAddslashes('oPwd'),
				'nPwd'      => $this->GetStringAddslashes('nPwd'),
				'aPwd'      => $this->GetStringAddslashes('aPwd'),
				'user_id'   => ComFun::getCookies('frame_user_id'),
		);
		
		if ( $params['user_id'] ) {
			if ( !$params['oPwd'] ) {
				$this->redirect('/index/changePass?msgkey=Error_Pwd_Empty');
			}
			if ( !$params['nPwd'] ) {
				$this->redirect('/index/changePass?msgkey=Error_Pwd_Empty');
			}
			if ( !$params['aPwd'] ) {
				$this->redirect('/index/changePass?msgkey=Error_Pwd_Empty');
			}
			if ( $params['nPwd'] != $params['aPwd'] ) {
				$this->redirect('/index/changePass?msgkey=Error_Pwd_Difference');
			}
			
			$User = $this->getClass('User');
			$MandOAuth = $this->getClass('MandOAuth');
			$decodeArr = $MandOAuth->dodecrypt($params['user_id']);
			
			if ( $decodeArr ) {
				$validArr = explode('|', $decodeArr);
				
				if ( is_array($validArr) ) {
					$userInfo = $User->getUserInfoNew($validArr[0]);
					
					if ( $userInfo ) {
						if ( $userInfo['uPWD'] == md5($params['oPwd']) ) {
							$User->modifyPassword(array(
									'UserID' => $validArr[0],
									'newPWD' => $params['nPwd'],
							));
							
							ComFun::destoryCookies(array('frame_user_id' => ''));
							echo $this->_Lang['JS_LANG']['Success_Pwd'];
							echo '<script>parent.$.fancybox.close();</script>';exit;
						} else {
							$this->redirect('/index/changePass?msgkey=Error_Error_oPwd');
						}
					} else {
						$this->redirect('/index/changePass?msgkey=Error_User_NotExist');
					}
				} else {
					$this->redirect('/index/changePass?msgkey=Error_System_UserID');
				}
			} else {
				$this->redirect('/index/changePass?msgkey=Error_System_UserID');
			}
		} else {
			$this->redirect('/index/changePass?msgkey=Error_System_Error');
		}
		
		
		ComFun::pr($params);
		exit;
		
		
		
		ComFun::pr($decodeArr);exit;
	}
	
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'Login':
				include_once(dirname(dirname(__FILE__)).'/include/lib/Login.class.php');
				return new Login($this->model);
				break;
			case 'User':
				include_once(dirname(dirname(__FILE__)).'/include/lib/User.class.php');
				return new User($this->model);
				break;
			case 'ApiInterface':
				include_once(dirname(dirname(__FILE__)).'/include/lib/ApiInterface.class.php');
				return new ApiInterface();
				break;
			case 'HttpClient':
				include_once(dirname(dirname(__FILE__)).'/include/lib/HttpClient.class.php');
				$post = __ROOT__;
				return new HttpClient($post);
				break;
			case 'OAuthCommon':
				include_once(dirname(dirname(__FILE__)).'/include/ext/partner/common/OAuthCommon.php');
				return new OAuthCommon($fieldArr['callback'],$fieldArr['provider'],$fieldArr['partner']);				
				break;
			case 'GetUserInfo':
				include_once(dirname(dirname(__FILE__)).'/include/ext/partner/common/GetUserInfo.php');
				//return new GetUserInfo($this->api_key,$this->api_sercet,$this->partner,$this->hostURL,$this->wrapper,$this->authway,$this->OAuthArr);
				return new GetUserInfo($fieldArr['partner'],$fieldArr['provider'], $fieldArr['OAuthArr']);
				break;
			case 'InviteCode':
				include_once(dirname(dirname(__FILE__)).'/include/lib/InviteCode.class.php');
				return new InviteCode($this->model);
				break;
			case 'MandOAuth':
				include_once(dirname(dirname(__FILE__)).'/include/lib/MandOAuth.class.php');
				return new MandOAuth($this->model,$this->config);
				break;
			case 'MandOAuthLog':
				include_once(dirname(dirname(__FILE__)).'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
			case 'DBSoapExpandInviteCode':
				$this->config['DES']['ident'] = 'private';
				include_once(dirname(dirname(__FILE__)).'/include/lib/DBSoapExpandInviteCode.class.php');
				return new DBSoapExpandInviteCode($this->config);
				break;
			default:
				break;
		}
	}
}
?>