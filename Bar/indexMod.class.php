<?php

include_once(DBO_PATH.'lib/Config.class.php'); //引入处理类的编码格式 utf-8

class indexMod extends commonMod {
	public function index() {	
		$this->assign ( 'title', Lang::get('Index_title') );

		if(ComFun::getCookies('UserID')){
			$this->redirect('/index/loginCallBack');
		}else{
			$this->redirect('/index/login');
		}
	}	
	/**
	 * 成功登录后，回调URL处理
	 */
	function loginCallBack(){	
		$client_id = ComFun::getCookies(ComFun::getCookies('ident').'_client_id');
		$UserID    = ComFun::getCookies('UserID');
		ECHO $client_id;EXIT;
		if(!empty($client_id)){
			if(empty($UserID)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam101'));
			}
			
			//用户是否激活过应用			
			$MandOAuth = $this->getClass('MandOAuth');	
			$classFunction = $MandOAuth;
			$appInfo = $MandOAuth->getAuthAppInfo($client_id);

			//权限字符串，判断是否用户登录是否需要验证码
			if(strstr($appInfo['data']['appplus'],'inviteCode')){
				$atArr['UserID'] = $UserID;
						
				$inviteCode = $this->getClass('InviteCode');
				$isActive = $inviteCode->IsUserActive($atArr);
				
				if($isActive == -1){
					$this->assign ( 'pageModel', 'inviteCode' );
					$this->display ('index/index.html'); //输出模板
					exit;
				}
			}
		}

		$url = ComFun::getCallBack($this->model,$this->config,$classFunction);
		//echo $url;exit;
		$this->redirect($url);
	}
	/**
	 * URL激活账户
	 */
	function inviteCode(){
		$url = '/index/login?ident=inviteCode&'.ComFun::makeCallBack($_GET);
		$this->redirect($url);
	}
	/**
	 * 注册处理
	 */
	public function register(){
		$this->assign('title', Lang::get('Index_title'));
		$this->assign('show',$_GET['show'] ? $_GET['show'] : ''); //页眉显示控制

		ComFun::SaveCallBack($_GET); //登录成功后的回调地址
		
		if($_GET['display'] == 'mobile'){
			$this->display ('index/register_wap.html'); //输出模板
		}else{
			$this->display ('index/index.html'); //输出模板
		}			
	}	
	/**
	 * 处理注册信息
	 */
	public function doregister(){
		$login = $this->getClass('Login');
		$login->doRegister($_POST);
		$this->redirect('/index/loginCallBack');
	}	
	/**
	 * 验证邮箱是否已经注册
	 */
	function checkEmail(){
		$user = $this->getClass('User');
		$re['uEmail'] = $user->doCheckEmail($_POST['uEmail']);
		$re['uName']  = $user->docheckUserName($_POST['uName']);
	
		echo json_encode($re);
	}		
	/**
	 * login登录页面信息处理
	 */
	public function login(){
		$this->assign ( 'title', Lang::get('Index_title') );
	
	//用户是否激活过应用
	$MandOAuth = $this->getClass('MandOAuth');
	$classFunction = $MandOAuth;
	$appInfo = $MandOAuth->getAuthAppInfo($_GET['client_id']);
	ComFun::pr($appInfo);
	exit;		
		$this->assign('redirect',ComFun::makeCallBack($_GET));
// ComFun::destoryCookies();
// ComFun::pr(ComFun::getCookies());
		ComFun::SaveCallBack($_GET); //登录成功后的回调地址
		
		//是否已经登录过，登录过直接跳转到回调地址
		if(ComFun::getCookies('UserID')){			
			$this->redirect('/index/loginCallBack');
		}
		
		$apiArr = ComFun::getAPIConfig();
		
		foreach($apiArr['providers'] as $key=>$val){
			if($val['enabled']){
				$JsonKeyArr[] = '"'.$key.'":{"txt":"'.$val['txt'].'","icon":"'.$val['icon'].'"}';
			}
		}
		
		$Partners_json = implode(',',$JsonKeyArr);
		$Partners_json = '{'.$Partners_json.'}';
	
		$this->assign ('Partners_json',$Partners_json);
			
		$this->display ('index/index.html'); //输出模板
	}
	/**
	 * login登录处理
	 */
	public function dologin(){
		$this->redirect('/index/loginCallBack');
	}	
	/**
	 * 登录用户账户检测
	 */
	public function checkLogin(){
		$login = $this->getClass('Login');
		echo $login->doCheckLogin($_GET);
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
	 * 第三方登录
	 */
	public function loginThirdParty(){	
		$provider   = $_GET['provider'];
		$idProvider = $_GET['idProvider'];
		$display    = isset($_GET['display']) ? $_GET['display'] : 'web';
	
		if(empty($provider)){
			ThrowMessage::ThrowMsg(Lang::get('Ex_NotNull304'));
		}		
		
		//$_COOKIES信息记录
		switch($idProvider){
			case 'join':
				$proNum = ComFun::getCookies('proNum') ? (ComFun::getCookies('proNum')+1) : 1;
				$Oprovider = ComFun::getCookies('provider');
				
				if(empty($Oprovider)){
					ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam206'));
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
		$OAuthCommon = $this->getClass('OAuthCommon',ComFun::getNowApi($provider));		
		$request_url = $OAuthCommon->getRequestAuth(1,$tArr);
//echo $request_url;exit;
		if($request_url){
			$this->redirect($request_url);
		}			
	}	
	/**
	 * 第三方登录验证
	 */
	public function checkThirdParty(){	
		$provider = ComFun::getCookies('provider');

		if(empty($provider)){
			ThrowMessage::ThrowMsg(Lang::get('Ex_NotNull304'));
		}
		
		$apiArr   = ComFun::getNowApi($provider);
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

		$OAuthCommon = $this->getClass('OAuthCommon',$apiArr);	
		$backInfo = $OAuthCommon->getRequestAuth(2,$fieldArr);
ComFun::pr($backInfo);exit;
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
			case 'qq':
				$backInfo['user_id'] = $backInfo['name'];
				break;
			//以下通过获取用户信息，取得用户标识信息	
			case 'wangyi':
			case 'kaixin':
			case 'tianya':
			case 'sohu':			
				$tArr['partner']  = $provider;
				$tArr['provider'] = $apiArr['provider'];
				$tArr['OAuthArr'] = $backInfo;
				
				$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',$tArr);
				$backInfo['user_id'] = $thirdInfo['uProvider_uid'];
				break;	
		}

		foreach($backInfo as $key=>$val){
			$cookies[$provider.'_'.$key] = $val;
		}
		ComFun::SetCookies($cookies);
	
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

		$this->redirect($url);	
	}	
	/**
	 * 做为第三方登录：若注册过账号直接返回，若未注册过则插入默认账号跟默认授权用户信息表
	 */
	function mandThirdLogin(){		
		$tArr['client_id']     = ComFun::getCookies('client_id');
		$tArr['redirect_uri']  = ComFun::getCookies('redirect_uri');
		$tArr['response_type'] = ComFun::getCookies('response_type');
	
		$login = $this->getClass('Login');
		$login->doCheckOauthInfo();	
		
		setcookie('idProvider','',time()-3600,'/');
		
		$this->redirect('/oauth/login?'.http_build_query($tArr).'&idProvider=dirauth&display='.ComFun::getCookies('display'));
	}
	/**
	 * 跳过账户关联，直接注册默认账户跟默认授权信息表
	 */
	function joinSkip(){
		$login = $this->getClass('Login');
		$login->doCheckOauthInfo();	
	
		$this->redirect('/index/loginCallBack');
	}
	/**
	 * 个人信息页面，直接绑定第三方信息
	 */
	function mandjoinThird(){
		$login = $this->getClass('Login');
		
		if($login->checkOAuthInfo() == -1){
			ThrowMessage::ThrowMsg(Lang::get('Ex_ErrorValueUsed502'));
		}else{
			$login->dirjoinThird(); //直接增加第三方数据
		}

		$this->redirect('/main/index');
	}
	/**
	 * 正常绑定页面显示处理
	 */
	function beforeJoinfrom(){			
		$this->assign ( 'title', Lang::get('Index_title') );
		
		$provider = ComFun::getCookies('provider');

		if(empty($provider)){
			ThrowMessage::ThrowMsg(Lang::get('Ex_NotNull304'));
		}
		
		//验证是否已经绑定且邮箱是否为空
		$login = $this->getClass('Login');
		$re = $login->checkOAuthInfo(); 

		if($re['UserID'] && $re['uEmail']){		
			$rbArr = $login->IsExistUserInfo($re['UserID']);			
			if($rbArr != -1){				
				$login->updateOauthThirdInfo($provider); //更新当前第三方信息
				$login->doUserInfoByThirdParty($rbArr);  //操作通过第三方验证绑定的第三方信息
				$login->setSuccessLog($rbArr); //登录成功后，更新日志表和写$_COOKIES
				
				$this->redirect('/index/loginCallBack');
			}else{				
				ThrowMessage::ThrowMsg(Lang::get('Ex_ErrorSystem402'));
			}
		}
 		//未绑定的情况		
		$userInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));
		ComFun::pr($userInfo);exit;
		if(isset($userInfo['error'])){
			ThrowMessage::ThrowMsg(Lang::get('Ex_ErrorSystem401'));
		}

		$this->assign('userInfo',$userInfo);
		$this->display ('index/index.html'); //输出模板
	}
	/**
	 * 关联账户时，绑定邮箱情况处理
	 */
	function checkJoinfrom(){
		$uEmail = $_POST['uEmail'];
		
		if(empty($uEmail)){ echo -2;exit; }
		
		$login = $this->getClass('Login');
		$user = $this->getClass('User');
		
		$authInfo = $login->checkOAuthInfo(); //第三方信息表是否存在
		$userInfo = $user->getEmailUserID($uEmail); //用户信息表是否存在		
		$thirdInfo = $login->thirdBandedInfo($uEmail); //邮箱已经绑定过第三方

		if($userInfo['UserID']){
			$accountInfo['UserID'] = $userInfo['UserID'];
		}else{
			$accountInfo['UserID'] = -1;
		}
		if($userInfo['uPWD']){
			$accountInfo['pwd'] = 1;
		}else{
			$accountInfo['pwd'] = -1;
		}

		if($authInfo != -1){
			$OauthID = $authInfo['UserAuthenticationsID'];
		}else{
			$OauthID = -1;
		}

		if(is_array($thirdInfo)){ //第三方信息存在
			$apiArr = ComFun::getNowApi();
			foreach($thirdInfo  as $key=>$val){
				$uProvider = ucfirst($val['uProvider']);
				$JsonKeyArr[] = '"'.$uProvider.'":{"txt":"'.$apiArr['providers'][$uProvider]['txt'].'","icon":"'.$apiArr['providers'][$uProvider]['icon'].'"}';
			}	
			$Partners_json = implode(',',$JsonKeyArr);
			$msg = 1;
		}else{
			$Partners_json = '';
			$msg = -1;
		}

		echo '{"OauthID":"'.$OauthID.'","accountInfo":'.json_encode($accountInfo).',"data":{"msg":"'.$msg.'","data":{'.$Partners_json.'}}}';
	}
	/**
	 * 验证密码是否正确
	 */
	function checkPassword(){
		$tArr['uPWD'] = $_GET['pwd'];
		$tArr['uEmail'] = $_GET['uEmail'];
		
		$user = $this->getClass('User');		
		$gbArr = $user->checkBeforeLogin($tArr);

		if($gbArr != -1){
			echo 1;
		}else{
			echo -1;
		}	
	}
	/**
	 * 关联账户:用邮箱进行绑定
	 */
	function joinfrom(){		
		$uEmail = $_POST['uEmail'];
		
		if(empty($uEmail)){ echo -2;exit; }
		
		$login = $this->getClass('Login');
		$user = $this->getClass('User');
		
		$userInfo = $user->getEmailUserID($uEmail); //用户信息表是否存在	
		$authInfo = $login->checkOAuthInfo(); //第三方信息表是否存在

		if($userInfo != -1){ //用户已经存在
			$login->doUserInfoByEmail($userInfo,$authInfo);
		}else{ //用户不存在
			if($authInfo != -1){ //第三方信息存在
				ThrowMessage::ThrowMsg(Lang::get('Ex_ErrorSystem402'));
			}else{ //第三方信息不存在
				$tArr['uEmail'] = $uEmail;

				$login->addJoinAccountInfo($tArr);
			}
		}

		$this->redirect('/index/loginCallBack');
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
	function test(){
		$emArr['uName']   = '吴本清';
		$emArr['uEmail']  = 'wbqing405@sina.com';
		$emArr['uCode']   = '78aae04a880b058e';
		$emArr['type']    = 'bandaccount';
		ComFun::pr($emArr);
		ComFun::toSendMail($emArr);
	
	}
	/**
	 * 激活处理
	 */
	public function activate(){
		$this->assign ( 'title', Lang::get('Index_title') );
		
		$login = $this->getClass('Login');

		$type = $login->doActivate($_GET);

		if($type == 1){
			$backArr['msg'] = Lang::get('SuccessActivate');
		}elseif($type == 2){
			$backArr['msg'] = Lang::get('SuccessReActivate');
		}elseif($type == 3){
			echo 222;exit;
		}else{
			$backArr['msg'] = Lang::get('FailActivate');
		}
	
		$backArr['url'] = '/index/index';
	
		$this->assign('msg',$backArr['msg']);
		$this->assign('url',$backArr['url']);
	
		$this->display ('index/index.html'); //输出模板
	}
// 	/**
// 	 * 检查UserID是否存在$_COOKIE中
// 	 */
// 	function checkCookieUserID(){
// 		if(ComFun::getCookies('UserID')){
// 			echo ComFun::getCookies('UserID');
// 		}else{
// 			echo -1;
// 		}
// 	}
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
			default:
				break;
		}
	}
}
?>