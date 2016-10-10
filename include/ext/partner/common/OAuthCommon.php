<?php
/**
 * 通用接口类
 * 
 * @author wbqing405@sina.com
 */

class OAuthCommon{
	
	function __construct($callback=NULL,$ApiInfo=NULL,$partner=NULL){
		// Use Beijing Timezone
		date_default_timezone_set ('Etc/GMT-8');
		
		$this->callback         = strtolower($callback['normal']);
		$this->openid           = strtolower($callback['openid']);
	
		$this->openidIdentifier = $ApiInfo['urls']['openidIdentifier'];
		$this->ApiInfo          = $ApiInfo;

		$this->authway          = $ApiInfo['authway'];
		
		$this->partner          = $partner;
	}
	
	/**
	 * 请求授权
	 * 
	 * @param $type 请求类型：1为请求临时令牌；2为请求用户信息
	 * @param $fieldArr 数组包括需求参数
	 */
	function getRequestAuth($type,$fieldArr=NULL){	
		$this->type     = $type;
		$this->fieldArr = $fieldArr;
		$this->partner  = $fieldArr['partner'];

		return $this->getOAuthComm();
	}
	/**
	 * 判断是用哪种授权方法
	 * 
	 */
	private function getOAuthComm(){
		switch($this->authway){
			case 'auth1':		
				$commonOAuth1 = $this->getClass('CommonOAuth1');

				if($this->type == 1){
					if(strtolower($this->partner) == 'kaixin'){
						$tArr['scope'] = 'basic create_records';
					}else{
						$tArr = false;
					}
	
					return $commonOAuth1->getRequestOAuth($tArr);
				}elseif($this->type == 2){
					return $commonOAuth1->getAccessOAuth();
				}
				
				break;
			case 'auth2':
				$commonOAuth2 = $this->getClass('CommonOAuth2');
				$response_type = 'code';
				if($this->type == 1){	
					switch(strtolower($this->partner)){
						case 'renren':
							$tArr['scope'] = 'read_user_album read_user_feed publish_feed publish_blog status_update';
							break;
						case 'kaixin':
							$tArr['scope'] = 'basic create_records';
							break;
						case 'douban':
							$tArr['scope'] = 'douban_basic_common,shuo_basic_r,shuo_basic_w';
							break;
						case 'facebook':
							//$tArr['scope'] = 'publish_stream,offline_access,user_status,read_stream';
							$tArr = false;
							break;
						case 'google':
							$tArr['scope'] = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me';
							//$tArr['scope'] = 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.me';
							//$tArr['state'] = 'profile';
							$tArr['access_type'] = 'offline';
							$tArr['approval_prompt'] = 'force';
							$tArr['state'] = 'profile';

							//$response_type = 'token';
							break;
						case 'live':
							$tArr['scope'] = 'wl.basic wl.emails wl.contacts_photos wl.offline_access';
							break;
						case 'linkedin':
							$tArr['scope'] = 'r_basicprofile r_emailaddress w_messages';
							$tArr['state'] = 'linkedin';
							break;
						case 'paypal':
							$tArr['scope'] = 'openid address profile email https://uri.paypal.com/services/paypalattributes https://uri.paypal.com/services/paypalattributes';
							break;
						case 'qq':
							$tArr['scope'] = 'get_user_info,add_t,add_idol,get_idollist';
							break;
						case 'sohu':
							$tArr['scope'] = 'basic';
							break;
						case 'sina':
							$tArr['scope'] = 'all';
							break;
						default:							
							$tArr = false;
							break;
							
					}			

					if(ComFun::checkBrowse() == 1 || trim($this->fieldArr['display']) == 'mobile'){  //手机端登录
						$tArr['display'] = 'mobile';
					}
 					return $commonOAuth2->getAuthorizeOAuth($tArr, $response_type);
				}elseif($this->type == 2){
					return $commonOAuth2->getAccessOAuth();
				}
				break;
			case 'openid':
				$commonOpenID = $this->getClass('CommonOpenID');
				$commonOpenID->getLogin();
				
// 			    $url = $this->ApiInfo['urls']['openidIdentifier'];
// 				$a = $commonOpenID->discover($url);
// 				$this->pr($a);
				break;
			case 'QQWeibo':
				$OAuthQQ = $this->getClass('OAuthQQ');
				
				if($this->type == 1){
					return $OAuthQQ->getRequestOAuth();
				}elseif($this->type == 2){
					return $OAuthQQ->getAccessOAuth();
				}
				break;
			case 'twitter':
				$twitter = $this->getClass('twitter');
				
				if($this->type == 1){
					return $twitter->getRequestToken($this->callback);
				}elseif($this->type == 2){
					return $twitter->getAccessOAuth();
				}
				break;
				break;
			default:				
				return false;
				break;
		}
	}
	
	/**
	 * 取得OAuth处理类
	 */
	private function getClass($className){
		switch($className){
			case 'CommonOAuth1':
				include_once('CommonOAuth1.php');
				return new CommonOAuth1($this->callback,$this->ApiInfo,$this->fieldArr,$this->partner);
				break;
			case 'CommonOAuth2':
				include_once('CommonOAuth2.php');
				return new CommonOAuth2($this->callback,$this->ApiInfo,$this->fieldArr,$this->partner);
				break;
			case 'CommonOpenID':
				include_once('CommonOpenID.php');				
				return new CommonOpenID($this->callback,$this->openid,$this->openidIdentifier);
				break;
			case 'OAuthQQ':
				$root = dirname(dirname(__FILE__));

				include_once($root.'/thirdparty/QQ/OAuthQQ.php');
				return new OAuthQQ($this->callback,$this->ApiInfo,$this->fieldArr);
				break;
			case 'twitter':
				include('specialOAuth/twitteroauth.php');
				return new TwitterOAuth($this->ApiInfo['keys']['api_key'],$this->ApiInfo['keys']['api_sercet']);
				break;
			default:
				break;
		}
	}
	
	/**
	 * 打印类
	 * @param unknown_type $arr
	 */
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}