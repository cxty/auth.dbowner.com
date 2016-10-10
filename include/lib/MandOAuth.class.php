<?php
/**
 * 第三方接口类 OAuth 2.0 验证方式
 *
 * @author wbqing405@sina.com
 */
include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('Addslashes.class.php'); //数据过滤类
include_once('DES.class.php'); //引入DES加密解密文件

class MandOAuth{
		
	public function __construct($model=null,$config=null){
		// Use Beijing Timezone
		date_default_timezone_set ('Etc/GMT-8');
		
		$this->model   = $model;
		$this->config  = $GLOBALS["config"];

		$this->Access_EffectTime  = $this->config['EXPIRE_TIME']['Access_EffectTime'];
		$this->Refresh_EffectTime = $this->config['EXPIRE_TIME']['Refresh_EffectTime'];
	
		$this->COFGIGDES = $this->config['DES'];
		
		$this->DES_PWD   = $this->config['oauth']['DES_PWD'];
		$this->DES_IV    = $this->config['oauth']['DES_IV'];
		
		$this->init();
	}
	
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
		$this->DES = new DES($this->DES_PWD,$this->DES_IV);
	}
	/**
	 * 判断是否登录，登录过则返回授权信息
	 */
	public function checkOAuthLogin($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$UserID          = $fieldArr['UserID'];
		$UserOnLineLogID = $fieldArr['UserOnLineLogID'];
		$client_id       = $fieldArr['client_id'];	

		if($UserID && $UserOnLineLogID != -1){	
			$re = $this->getAppInfo($client_id);

			if(intval($re) == -1){
				$backStr['str']   = -1;
				$backStr['msg']   = 'error=102';
				
				$asReCall = $fieldArr['redirect_uri'];
			}else{			
				$asReCall = $re['asReCall'];
				
				$user = $this->getClass('User');
				$UserOnlineLogID = $user->getUserOnlineLogID($UserID);
				
				if($UserOnlineLogID == -1){
					$backStr['str']   = -1;
					$backStr['msg']   = 'error=108';
				}elseif($UserOnlineLogID == -2){
					$backStr['str']   = -2;
					$backStr['msg']   = 'error=130';
				}else{
					$tArr['UserID']    = $UserID;
					$tArr['client_id'] = $client_id;
					
					$re = $this->getAuthToken($tArr);
					
					$backStr['str']   = 1;
					$backStr['msg']   = http_build_query($re);
				}			
			}
		}else{
			$backStr['str']   = -1;
			$backStr['msg']   = 'error=108';
			
			$asReCall = $fieldArr['redirect_uri'];
		}
		
		//$backStr['msg'] = $asReCall.'?'.$backStr['msg'];
		$backStr['msg'] = $this->_backUrl($asReCall, $backStr['msg']);

		return $backStr;
	}
	/**
	 * 授权类
	 * @param unknown_type $fieldArr
	 */
	public function checkAuthorize($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$client_id     = $fieldArr['client_id'];
		$redirect_uri  = $this->urldecode_rfc3986($fieldArr['redirect_uri']);
		$response_type = $fieldArr['response_type'];
		$UserID        = $fieldArr['UserID'];
		
		$re = $this->getAppInfo($client_id);
	
		if(intval($re) == -1){
			$backStr = 'error=105';
			$asReCall = $redirect_uri;
		}else{
			if(trim($client_id) != trim($re['AppID'])){
				$backStr = 'error=102';
			}elseif($this->setUpper($redirect_uri) != $this->setUpper($re['asReCall'])){				
				$backStr = 'error=103';
			}else{
				$tArr['UserID']        = $UserID;
				$tArr['client_id']     = $client_id;
				$tArr['response_type'] = $response_type;
				$tArr['provider']      = $fieldArr['provider'];
				
				$code = $this->getTypeCode($tArr);
				
				if($code){
					$backStr = 'code='.$code;
				}else{
					$backStr = 'error=106';
				}	
			}
			
			$asReCall = $re['asReCall'];
		}
		
		$cookies['code'] = $code;		
		ComFun::SetCookies($cookies);
		
		//$authorizeURL = $asReCall.'?'.$backStr;
		$authorizeURL = $this->_backUrl($asReCall, $backStr);

		if ( $client_id == 'app20' ) {
			ComFun::pr($authorizeURL);exit;
		}
		
		return $authorizeURL;
	}
	
	/**
	 * 授权验证
	 */
	public function checkToken($code,$redirect_uri){
		//$this->code  = $this->Addslashes->get_addslashes($code);

		$decodeStr = $this->releaseCode($code);

		if(!$decodeStr){		
			//return $this->urldecode_rfc3986($redirect_uri).'?error=110';
			$decodeStr = $this->releaseCode($this->urldecode_rfc3986($code));
		}

		$decodeArr = explode('|',$decodeStr);

		$decodeTime = $decodeArr[0];
		$UserID     = $decodeArr[1];
		$client_id  = $decodeArr[2];
		$provider   = $decodeArr[3];
		
		$re = $this->getAppInfo($client_id);
		
		if(intval($re) == -1){			
			$backStr = 'error=105';		
			$asReCall = $redirect_uri; 
		}else{
			$asReCall = $re['asReCall'];
			
			$backTime = $decodeTime+$this->Access_EffectTime;
			
			if(time() > $backTime){
				$backStr = 'error=104';
			}else{
				//$access_token = $this->getAccessToken();
				$tArr['UserID']    = $UserID;
				$tArr['client_id'] = $client_id;
				$tArr['provider']  = $provider;
					
				$re = $this->getAuthToken($tArr);
				
				if($re['access_token'] == -1){
					$backStr = 'error=108';
				}elseif($re['access_token'] == -2){
					$backStr = 'error=130';
				}else{
// 					$backArr['access_token']  = $access_token;
// 					$backArr['refresh_token'] = $this->getOpenID();
// 					$backArr['user_id']       = $this->getUserID();
						
					//$this->updateAuthInfo($tArr, $re);
				
					$backStr = http_build_query($re);
				}	
			}
		}

		//$accessUrl = $asReCall.'?'.$backStr;
		$accessUrl = $this->_backUrl($asReCall, $backStr);

		return $accessUrl;
	}
	
	/**
	 * 授权验证
	 */
	public function checkToken2($code,$redirect_uri){
		//$this->code  = $this->Addslashes->get_addslashes($code);
	
		$decodeStr = $this->releaseCode($code);

		if(!$decodeStr){
			//return 'error=110';
			$decodeStr = $this->releaseCode($this->urldecode_rfc3986($code));
		}

		$decodeArr = explode('|',$decodeStr);
	
		$decodeTime = $decodeArr[0];
		$UserID     = $decodeArr[1];
		$client_id  = $decodeArr[2];
		$provider   = $decodeArr[3];
	
		$re = $this->getAppInfo($client_id);
			
		if(intval($re) == -1){
			$backArr['error'] = '105';
		}else{
			$backTime = $decodeTime+$this->Access_EffectTime;
	
			if(time() > $backTime){
				$backArr['error'] = '104';
			}else{
				//$access_token = $this->getAccessToken();
				$tArr['UserID']    = $UserID;
				$tArr['client_id'] = $client_id;
				$tArr['provider']  = $provider;
					
				$re = $this->getAuthToken($tArr);

				if($re['access_token'] == -1){
					$backArr['error'] = '108';
				}elseif($re['access_token'] == -2){
					$backStr['error'] = '130';
				}else{					
					$backArr = $re;
					
// 					$backArr['access_token']  = $access_token;
// 					$backArr['refresh_token'] = $this->getOpenID();
// 					$backArr['user_id']       = $this->getUserID();
				}
			}
		}
		
		return $backArr;
	}
	/**
	 * 获取应用的相关信息
	 */
	 public function getAppInfo($client_id){ 	
	 	$AppInfo = $this->getAuthAppInfo($client_id);

		if(!$AppInfo['data']){
			return -1;
		}

		$re['AppInfoID']       = $AppInfo['data']['appset']['AppInfoID'];
		$re['AppID']           = $AppInfo['data']['appset']['AppID'];
		$re['asKey']           = $AppInfo['data']['appset']['asKey'];
		$re['asReCall']        = $AppInfo['data']['appset']['asReCall'];
		$re['aName']           = $AppInfo['data']['appinfo']['aName'];
		$re['apppermissions']  = $AppInfo['data']['apppermissions'];
		
		
		return $re;
	}
	/**
	 * 获取应用信息
	 */
	public function getAuthAppInfo($client_id){		
		$tArr['client_id'] = $client_id;
		$soapc = $this->getClass('soapc',$tArr);
		$AppInfo = $soapc->run();
//ComFun::pr($AppInfo);exit;	
		
		return $AppInfo;
	}
	/**
	 * 获取相应验证类型值
	 */
	private function getTypeCode($fieldArr){
		switch(strtoupper($fieldArr['response_type'])){
			case 'CODE':
				return $this->getCode($fieldArr);
				break;
			default:
				return $this->getCode($fieldArr);
				break;
		}
	}
	/**
	 * code验证方式，随机码
	 */
	private function getCode($fieldArr){
		$str = time().'|'.$fieldArr['UserID'].'|'.$fieldArr['client_id']. '|' . $fieldArr['provider'];
		$str = $this->DES->encrypt($str);
		$str = base64_encode($str);
		$str = $this->urlencode_rfc3986($str);	

		return $str;
	}
	
	/**
	 * code验证码，解密
	 */
   private function releaseCode($code){
   		$code = base64_decode($code);
		return $this->DES->decrypt($code);
	}
	
	/**
	 * refresh_token 生成
	 */
	private function getOpenID($fieldArr){	
		//$str = $this->UserID.'|'.$this->client_id.'|'.$this->UserID.'|'.time();
		$str = $fieldArr['TokenID'].'|'.time();
		$str = $this->DES->encrypt($str);
		$str = base64_encode($str);
		$str = $this->urlencode_rfc3986($str);
		
		return $str;
	}
	
	/**
	 * refresh_token 解密
	 */
	private function releaseOpenID($refresh_token){	
		$this->DES = new DES($this->DES_PWD,$this->DES_IV);
		$refresh_token = base64_decode($refresh_token);
		return $this->DES->decrypt($refresh_token);
	}
	
	/**
	 *  access_token 生成
	 */
	private function getAccessToken($fieldArr){
		if(!$fieldArr['UserOnlineLogID']){
			$user = $this->getClass('User');
			$fieldArr['UserOnlineLogID'] = $user->getUserOnlineLogID($fieldArr['UserID']);
		}
	
		if($fieldArr['UserOnlineLogID'] == -1){
			return -1;
		}
	
		//$str = $this->UserOnlineLogID.'|'.$this->client_id.'|'.$this->UserID.'|'.time();
		$str = $fieldArr['UserOnlineLogID'].'|'.$fieldArr['TokenID'].'|'.time();
		$str = $this->DES->encrypt($str);
		$str = base64_encode($str);
		$str = $this->urlencode_rfc3986($str);
		
		return $str;
	}
	
	/**
	 * access_token 解密
	 */
	private function releaseAccessToken($access_token){
		$access_token = base64_decode($access_token);
		return $this->DES->decrypt($access_token);
	}
	
	/**
	 * 在线ID不存在，增加在线ID，并返回access_token
	 */
	public function reGenerateAccessToken ( $fieldArr ) {
		$userQuery = new UserQuery($this->model);
		$tArr['UserOnlineLogID'] = $userQuery->inUserOnLineLogInfo($fieldArr);
		
		$mandOAuthLog = new MandOAuthLog($this->model);
		$tArr['TokenID'] = $mandOAuthLog->getTokenIDByUserIDAndAppID($fieldArr);
	
		return $this->getAccessToken($tArr);
	}
	
	/**
	 * 
	 */
	public function releaseStr($str){
		return $this->DES->decrypt(base64_decode($str));
	}
	/**
	 *  user_id 生成
	 */
	public function getUserID($fieldArr){
		$str = $fieldArr['UserID'].'|'.$fieldArr['client_id'];

		$str = $this->DES->encrypt($str);
		$str = base64_encode($str);
		$str = $this->urlencode_rfc3986($str);

		return $str;
	}
	
	/**
	 * user_id 解密
	 */
	public function releaseUserID($user_id){
		$user_id = base64_decode($user_id);
		return $this->DES->decrypt($user_id);
	}
	/**
	 * 返回OpendID
	 */
	public function _getUserIDArr($value){
		if($value){			
			
			return explode('|', $this->DES->decrypt(base64_decode(urldecode($value))));
		}
	}
	/**
	 * 分解UserID
	 */
	public function _getUserID($value){
		if($value){
			//$strArr = explode('|', $this->DES->decrypt(base64_decode(urldecode($value))));
			
			$strArr = explode('|', $this->releaseUserID($value));

			$UserID = $strArr[0];
				
			return $UserID;
		}
	}
	/**
	 *  加密字符串
	 */
	public function doencrypt($str=''){
		if($str){
			$str = $this->DES->encrypt($str);
			$str = base64_encode($str);
			$str = $this->urlencode_rfc3986($str);
			
			return $str;
		}else{
			return false;
		}	
	}
	/**
	 *  解密字符串
	 */
	public function dodecrypt($str=''){
		if($str){
			return $this->DES->decrypt(base64_decode($str));
		}else{
			return false;
		}		
	}
	/**
	 * urlencode处理
	 */
	private function urlencode_rfc3986($input) {
		if (is_scalar($input)) {
			return str_replace(
					'+',
					' ',
					str_replace('%7E', '~', rawurlencode($input))
			);
		} else {
			return '';
		}
	}
	
	/**
	 * urldecode处理
	 */
   public function urldecode_rfc3986($string) {
		return urldecode($string);
	}
	/**
	 * 授权信息处理
	 */
	public function getAuthToken($fieldArr){		
		$UserID    = $fieldArr['UserID'];
		$client_id = $fieldArr['client_id'];
		
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $client_id;
		
		$MandOAuthLog = $this->getClass('MandOAuthLog');
		$re = $MandOAuthLog->getAuthNumLogInfo($fieldArr);	

		if($re){
			$token_id = $re[0]['TokenID'];
			
			if($token_id){
				$token_time = $re[0]['token_time'];
			
				$backTime = $token_time+$this->Refresh_EffectTime;
				
				$tArr['TokenID']   = $token_id;			
				
				if((time() < $backTime) && $re[0]['refresh_token']){				
					$backArr['access_token']  = $this->getAccessToken($tArr);
					$backArr['refresh_token'] = $re[0]['refresh_token'];
					$backArr['user_id']       = $re[0]['user_id'];

					//成功授权之后记录信息
					$oAuthArr['TokenID']       = $token_id;
					$oAuthArr['client_id']     = $client_id;
					$oAuthArr['access_token']  = $backArr['access_token'];
					$oAuthArr['refresh_token'] = $backArr['refresh_token'];
					$oAuthArr['user_id']       = $backArr['user_id'];
					$MandOAuthLog->setOauthSuccessLog($oAuthArr);
				}else{
					$backArr['access_token']  = $this->getAccessToken($tArr);
					$backArr['refresh_token'] = $this->getOpenID($tArr);
					$backArr['user_id']       = $this->getUserID($tArr);

					$fieldArr['refresh_token'] = $backArr['refresh_token'];
					$fieldArr['user_id']       = $backArr['user_id'];
						
					$MandOAuthLog->updateAuthNumLogInfo($fieldArr);
					
					//成功授权之后记录信息
					$oAuthArr['TokenID']       = $token_id;
					$oAuthArr['client_id']     = $client_id;
					$oAuthArr['access_token']  = $backArr['access_token'];
					$oAuthArr['refresh_token'] = $backArr['refresh_token'];
					$oAuthArr['user_id']       = $backArr['user_id'];
					$MandOAuthLog->setOauthSuccessLog($oAuthArr);
				}
			}else{
				
			}
		}else{					
			$fieldArr['UserID']        = $UserID;
		
			$token_id = $MandOAuthLog->addAuthNumLogInfo($fieldArr);
	
			if($token_id){
				$tArr['TokenID']   = $token_id;
				
				$backArr['access_token']  = $this->getAccessToken($tArr);
				$backArr['refresh_token'] = $this->getOpenID($tArr);
				$backArr['user_id']       = $this->getUserID($tArr);

				$fieldArr['refresh_token'] = $backArr['refresh_token'];
				$fieldArr['user_id']       = $backArr['user_id'];
				
				$MandOAuthLog->updateAuthNumLogInfo($fieldArr);
				
				//成功授权之后记录信息
				$oAuthArr['TokenID']       = $token_id;
				$oAuthArr['client_id']     = $client_id;
				$oAuthArr['access_token']  = $backArr['access_token'];
				$oAuthArr['refresh_token'] = $backArr['refresh_token'];
				$oAuthArr['user_id']       = $backArr['user_id'];
				$MandOAuthLog->setOauthSuccessLog($oAuthArr);
			}else{
				$backArr['access_token']  = -2;
				$backArr['error']         = '130';
			}
		}
		
		//增加返回参数，当前登录第三方绑定过的DBOwner账号数组
		$provider = $_COOKIE['provider'] ? ComFun::getCookies('provider') : ($fieldArr['provider'] ? $fieldArr['provider'] : '');
		$usedArr = array();
		if ( $provider ) {
			$login = $this->getClass('Login');
			$re = $login->getThirdInfoByUserIDAndProvider($UserID, $provider);
		
			$uArr['client_id'] = $client_id;
			
			if ( $re ) {
				foreach ( $re as $v ) {
					if ( $v['OUserID'] ) {
						$OUserID = explode(',', substr($v['OUserID'], 1));
						if ( $OUserID ) {
							foreach ( $OUserID as $v2 ) {
								if ( $v2 ) {
									$uArr['UserID'] = $v2;
	
									$usedArr[] = $this->getUserID($uArr);
								}
							}
						}
					}
				}
			}
		}
		$backArr['used_user_id'] = $usedArr ? implode('|', $usedArr) : '';

		return $backArr;
	}
	/**
	 * 取用户授权信息
	 */
	public function getTokenInfo($tokenID){
		$MandOAuthLog = $this->getClass('MandOAuthLog');
		$re = $MandOAuthLog->getAuthNumLogByID($tokenID);

		if($re){
			$backArr['UserID']    = $re[0]['UserID'];
			$backArr['client_id'] = $re[0]['AppID'];
			
			return $backArr;
		}else{
			return -1;
		}
		
	}
	/**
	 * 取用户授权信息
	 */
	public function getTokenInfoByCurl($tokenID){
		$MandOAuthLog = $this->getClass('MandOAuthLog');
		$re = $MandOAuthLog->getAuthNumLogByID($tokenID);
	
		if($re){
			$backArr['UserID']    = $re[0]['UserID'];
			$backArr['client_id'] = $re[0]['AppID'];
				
			return $backArr;
		}else{
			return false;
		}
	
	}
	
	/**
	 * 返回授权信息
	 */
	/**
	private function getToken($fieldArr){
		$UserID    = $fieldArr['UserID'];
		$client_id = $fieldArr['client_id'];
	
		$backArr['access_token']  = $this->getAccessToken();
		$backArr['refresh_token'] = $this->getOpenID();
		$backArr['user_id']       = $this->getUserID();
	
		return $backArr;
	}
	**/
	/**
	 * 判断是否过期
	 */
	public function checkAuthPastDue($fieldArr){
		$MandOAuthLog = $this->getClass('MandOAuthLog');
		$re = $MandOAuthLog->getAuthNumLogInfo($fieldArr);
		
		if($re){
			$token_time = $re[0]['token_time'];
			$backTime = $token_time+$this->Refresh_EffectTime;
			
			if(time() < $backTime){
				return 1;
			}else{
				return -1;
			}
		}else{
			return -2;
		}
	}
	/**
	 * 更新授权信息表
	 */
	public function updateAuthInfo($condition, $fieldArr){
		ComFun::pr($condition);
		ComFun::pr($fieldArr);
		exit;
	}
	/**
	 * 取现在的时间
	 */
	private function getNowTime(){
		return time();
	}
	/**
	 * 转化为整形
	 */
	private function setIntval($int){
		return intval($int);
	}
	
	/**
	 * 把字符串转化为大写
	 */
	private function setUpper($str){
		return strtoupper($str);
	}
	/**
	 * 获取用户信息
	 */
	public function getUInfo($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$user_id = $this->releaseUserID($this->urldecode_rfc3986($fieldArr['user_id']));
		
		$userArr = explode('|',$user_id);
		
 		$user = $this->getClass('User');
		
 		$oUserID =  $user->getUserOnlineLogID($userArr[0]);	
 		
 		if($oUserID == -1){
 			return array('error'=>"108");
 		}
 				
 		$userInfo = $user->getUserDetInfo($userArr[0]);
 		
 		if($userInfo){
 			$re['name']     = $userInfo[0]['uName'];
 			$re['email']    = $userInfo[0]['uEmail'];
 			$re['comefrom'] = $userInfo[0]['uComeFrom'];
 			$re['image']    = $userInfo[0]['uhURL'];
 				
 			return $re;
 		}else{
 			return '';
 		} 		
	}
	/**
	 * 获取$access_token值
	 */
	public function reAccessToken($token){	
		//$token = $this->urlencode_rfc3986($token);
		
		$tokenStr = $this->urldecode_rfc3986($token);
		
		$tokenStr = $this->releaseOpenID($tokenStr);
		
		if(!$tokenStr){
			$tokenStr = $this->releaseOpenID($token);	
		}		
		
		return explode('|', $tokenStr);
	}
	/**
	 * refresh_token合法性检查
	 */
	public function checkRefreshToken($key){
		$re = $this->getAppInfo($key['client_id']);
		
		if(intval($re) == -1){
			$backStr['error'] = '105';
		}else{
			$backStr['AppInfoID']  = $re['AppInfoID'];
			$backStr['error']      = 'ok';
		}
		
		return $backStr;
	}
	/**
	 * 刷新access_token
	 */
	public function dofresh_token($token){
		$UserID    = $token['UserID'];
		
		$login = $this->getClass('Login');
		$rArr['UserID'] = $UserID;
		$UserOnLineLogID = $login->checkOnLineID($rArr);
		
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $token['client_id'];
		
		return $this->getAuthToken($tArr);
	}
	/**
	 * 刚添加应用，对用户信息进行记录
	 */
	public function _getToken($fieldArr){	
		$re = $this->getAuthToken($fieldArr);
		return $re;
		if($re['access_token'] == -1){
			$backArr['error'] = '108';
		}elseif($re['access_token'] == -2){
			$backArr['error'] = '130';
		}else{
			$backStr = $re;
		}
		
		return $re;
// 		$UserID = $fieldArr['UserID'];
		
// 		$user = $this->getClass('User');
// 		$userInfo = $user->getUserInfo($UserID);

// 		if(!$userInfo){
// 			return array("error" => "201");
// 		}
// 		$condition['UserID'] = $UserID;
// 		$condition['uName']  = $userInfo[0]['uName'];
		
// 		$this->UserOnLineLogID = $user->getUserOnLineID($condition);
		
		
// 		$tArr['UserID']    = $UserID;
// 		$tArr['client_id'] = $fieldArr['client_id'];
		
// 		return $this->getAuthToken($tArr);
	}
	/**
	 * 用户过期鉴权
	 */
	public function checkAccessToken($key){	
		$re = $this->getAppInfo($key['client_id']);
		if(intval($re) == -1){			
			$backStr['error'] = '105';
		}else{
			$user = $this->getClass('User');
			$oUserID =  $user->getUserOnlineLogID($key['UserID']);

			$backStr['AppInfoID']      = $re['AppInfoID'];
			$backStr['apppermissions'] = $re['apppermissions'];
			
			if(intval($oUserID) == -1){
				$backStr['error'] = '108';
			}else{
				$backStr['error'] = 'ok';
			}
		}
		
		return $backStr;
	}
	/**
	 * 取用户user_id
	 */
	public function getUserOAuthID($UserID,$client_id){
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $client_id;
		
		return $this->getUserID($tArr);
	}
	/**
	 * 取用户权限列表
	 */
	public function getPermValue($fieldArr){
		//用户记录表中权限	
		$mandOAuthlog = $this->getClass('MandOAuthLog');
		$userPerm = $mandOAuthlog->getUserPermission($fieldArr);
		
		//配置文件中的权限
		$permArr = ComFun::getPermissionInfo(); 

		if($userPerm){
			if(is_array($permArr)){
				foreach($permArr as $val){
					unset($val['contains']);
					if($val['isAuth']){
						if(strpos($userPerm.'|', '|'.$val['permName'].'|') !== false){
							$val['isDefault'] = true;
							$nPermArr['limit'] .= '|'.$val['permName'];
						}else{
							$val['isDefault'] = false;
						}
						$nPermArr['list'][] = $val;
					}					
				}
			}
		}else{
			if(is_array($permArr)){
				foreach($permArr as $val){
					unset($val['contains']);		
					if($val['isAuth']){
						if($val['isDefault']){
							$nPermArr['limit'] .= '|'.$val['permName'];
						}
						$nPermArr['list'][] = $val;
					}				
				}
			}
		}
		
		return $nPermArr;
	}
	/**
	 * 取用户权限列表 ---旧方法
	 */
	public function getPermission($fieldArr){	
		$apppermissions = $fieldArr['apppermissions'];
		$UserID         = $fieldArr['UserID'];
		$client_id      = $fieldArr['client_id'];
		
		//权限列表范围,应用权限列表存在则用应用的,若不存在则用默认的
		if($apppermissions){
			foreach($apppermissions as $val){
				$newMissionArr[] = $val['apCode'];			
			}
		}else{	 //默认权限
			$newMissionArr = $this->config['oauth']['permission'];
		}
		
		//所有权限列表
		$limitArr = ComFun::getPermissionInfo();
		
		//选出勾选的权限列表
		if($limitArr){
			foreach($limitArr as $key=>$val){
				if(in_array($val['oauth'], $newMissionArr)){
					$missionArr[] = $val;
				}
			}
			if(!$missionArr){
				$newMissionArr = $this->config['oauth']['permission'];
				foreach($limitArr as $key=>$val){
					if(in_array($val['oauth'], $newMissionArr)){
						$missionArr[] = $val;
					}
				}
			}
		}
		
		if($UserID && $client_id){
			//若用户权限列表存在,则启用用户的默认勾选选
			$tArr['UserID']    = $UserID;
			$tArr['client_id'] = $client_id;
			$mandOAuthlog = $this->getClass('MandOAuthLog');
			$userPer = $mandOAuthlog->getUserPermission($tArr);
			
			if($userPer){
				$userPerArr = json_decode($userPer,true);
				if($userPerArr){
					foreach($userPerArr as $val){
						$newPer[] = $val['usercmd'];
					}	
				}
				if($missionArr){
					foreach($missionArr as $key=>$val){
						if(in_array($val['oauth'], $newPer)){
							$missionArr[$key]['default'] = true;
						}else{
							$missionArr[$key]['default'] = false;
						}
					}
				}			
			}
		}
			
		return $missionArr;
	}
	/**
	 * 处理权限列表数组
	 */
	public function mandPermission($permission){
		$perArr = explode('|', substr($permission,1));
	
		if($perArr){
			//所有权限列表
			$limitArr = ComFun::getPermissionInfo();
			$i=0;
			foreach($limitArr as $val){
				if(in_array($val['oauth'], $perArr)){
					$missionArr[$i] = $val;
					$i++;
				}
			}
		
			return $missionArr;
		}else{
			return '';
		}
	}
	/**
	 * 用户权限处理
	 */
	public function doPermission($permission){
		$limitArr = explode('|', substr($permission,1));
		
		if($limitArr){
			foreach($limitArr as $Key=>$val){
				$ulimit[] = $val;
			}
			//所有权限列表
			$limitArr = ComFun::getPermissionInfo();
			$i=0;
			foreach($limitArr as $val){
				if(in_array($val['oauth'], $ulimit)){
					$missionArr[$i]['usercmd'] = $val['oauth'];
					$missionArr[$i]['read']    = $val['read'];
					$missionArr[$i]['write']   = $val['write'];
					$missionArr[$i]['delete']  = $val['delete'];
					$i++;
				}
			}
				
			return json_encode($missionArr);
		}else{
			return '';
		}
	}
	
	/**
	 * 回调地址重组
	 */
	private function _backUrl ( $asReCall, $backStr ) {
		$redirectArr = parse_url($asReCall);
	
		$_query = '';
		if ( $redirectArr['query'] ) {
			$_query = '&';
		} else {
			$_query = '?';
		}
	
		return $asReCall . $_query . $backStr;
	}
	
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'User':
				include_once('User.class.php');
				return new User($this->model);
				break;
			case 'Login':
				include_once('Login.class.php');
				return new Login($this->model);
				break;
			case 'MandOAuthLog':
				include_once('MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;	
			case 'UserQuery':
				include_once('UserQuery.class.php');
				return new UserQuery($this->model);
				break;
			case 'soapc':
				include_once('soapc.class.php');
				return new soapc($this->COFGIGDES,$fieldArr['client_id']);
				break;	
			default:
				break;
		}
	}
}