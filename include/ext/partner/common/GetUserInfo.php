<?php 
/**
 * 取得服务提供商的用户信息
 * 
 * @author wbqing405@sina.com
 */
class GetUserInfo{
	
	function __construct($partner,$provider,$OAuthArr){
		$this->partner            = $partner;
		
		$this->api_key            = $provider['keys']['api_key'];
		$this->api_sercet         = $provider['keys']['api_sercet'];
		
		$this->host               = $provider['urls']['hostURL'];
		$this->wrapper            = ucfirst($provider['wrapper']);
		$this->authway            = $provider['authway'];

		$this->OAuthArr = $OAuthArr;
	}
	
	/**
	 * 获取不同服务提供商的用户信息
	 */
	function getUserInfo(){
		$root = dirname(dirname(__FILE__));
		
		if(!is_file($root.'/'.$this->wrapper)){
			return -1;
		}
		
		require_once $root.'/'.$this->wrapper;

		switch(strtolower($this->partner)){
			case 'sina':
				return $this->getSina();
				break;
			case 'douban':
				return $this->getDouban();
				break;
			case 'qq':
				return $this->getQQ();
				exit;
				break;
			case 'renren':
				return $this->getRenren();
				break;
			case 'kaixin':
				return $this->getKaixin();
				break;
			case 'tianya':
				return $this->getTianya();
				break;
			case 'wangyi':
				return $this->getWangyi();
				break;
			case 'sohu':
				return $this->getsohu();
				break;
			case 'facebook':				
				return $this->getFacebook();
				break;
			case 'google':
				return $this->getGoogle();
				break;
			case 'live':
				return $this->getLive();
				break;
			case 'baidu':
				return $this->getBaidu();
				break;
			case 'diandian':
				return $this->getDiandian();
				break;	
			case 'foursquare':
				return $this->getFoursquare();
				break;
			case 'github':
				return $this->getGithub();
				break;
			case 'linkedin':
				return $this->getLinkedin();
				break;
			case 'tianyi':
				return $this->getTianyi();
				break;
			case 'tumblr':
				return $this->getTumblr();
				break;
			default:
				throw new Exception('The Provider is not exist!');
				exit;
				break;
		}
	}
	
	/**
	 * 获取新浪微博用户信息
	 */
	private function getSina(){	
		$Sina = new Providers_Sina($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr);
		$uid = $this->OAuthArr['user_id'];
		
		$userInfo = $Sina->getUserInfo($uid);	

		if(isset($userInfo['screen_name'])){
			$backInfo['uDisplay_name']   = $userInfo['screen_name'];
			$backInfo['uImages']         = $userInfo['profile_image_url'];
			$backInfo['uLocalion']       = $userInfo['location'];
			$backInfo['uProfile_url']    = 'http://weibo.com/'.$userInfo['profile_url'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	
	/**
	 * 获取豆瓣用户信息
	 */
	private function getDouban(){		
		$Douban = new Providers_Douban($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$uid = $this->OAuthArr['user_id'];
		
		$userInfo = $Douban->getUserInfo($uid);

		if(isset($userInfo['id'])){
			$backInfo['uDisplay_name']   = $userInfo['name'];
			$backInfo['uImages']         = $userInfo['avatar'];
			$backInfo['uLocalion']       = $userInfo['loc_name'];
			$backInfo['uProfile_url']    = $userInfo['alt'];
			$backInfo['uProvider_uid']   = $userInfo['id'];
		}else{
			$backInfo['error'] = -1;
		}
				
		return $backInfo;
	}
	
	/**
	 * 获取QQ用户信息
	 */
	private function getQQ(){	
		$QQ = new Providers_Qq($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr, $this->partner);

		$userInfo = $QQ->getUserInfo();

		if(isset($userInfo['nickname'])){
			$backInfo['uDisplay_name']    = $userInfo['nickname'];
			$backInfo['uImages']          = $userInfo['figureurl_2'];
			$backInfo['uLocalion']        = '';
			$backInfo['uProfile_url']     = $this->OAuthArr['openid'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取人人用户信息
	 */
	function getRenren(){
		$Renren = new Providers_Renren($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);

		$tArr['userId'] = $this->OAuthArr['user_id'];
		//$uid = '462474248';
		$userInfo = $Renren->getUserInfo($tArr);
		
		if(isset($userInfo['response']['id'])){			
// 			$backInfo['uDisplay_name']    = $userInfo[0]['name'];
// 			$backInfo['uLocalion']        = $userInfo[0]['hometown_location']['province'].' '.$userInfo[0]['hometown_location']['city'];
// 			$backInfo['uImages']          = $userInfo[0]['headurl'];
			$backInfo['uDisplay_name']    = $userInfo['response']['name'];
			$backInfo['uImages']          = $userInfo['response']['avatar'][1]['url'];
			$backInfo['uLocalion']        = $userInfo['response']['basicInformation']['homeTown']['province'] . ' ' . $userInfo['response']['basicInformation']['homeTown']['city'];
			$backInfo['uProfile_url']     = 'http://www.renren.com/' . $userInfo['response']['id'] . '/profile';
		}else{
			$backInfo['error'] = -1;
		}
		
		return $backInfo;
	}
	/**
	 * 获取开心用户信息
	 */
	function getKaixin(){
		$Kaixin = new Providers_Kaixin($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr);
	
		$uid = $this->OAuthArr['user_id'];
		$userInfo = $Kaixin->getUserInfo($uid);

		if(isset($userInfo['uid'])){
			$backInfo['uDisplay_name']    = $userInfo['name'];	
			$backInfo['uImages']          = $userInfo['logo50'];
			$backInfo['uProvider_uid']    = $userInfo['uid'];
		}else{
			$backInfo['error'] = -1;
		}
	
		return $backInfo;
	}
	/**
	 * 获取天涯用户信息
	 */
	function getTianya(){
		$Tianya = new Providers_Tianya($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr);
	
		$userInfo = $Tianya->getUserInfo();

		if(isset($userInfo['user']['user_name'])){
			$backInfo['uDisplay_name']    = $userInfo['user']['user_name'];
			$backInfo['uLocalion']        = $userInfo['user']['province'].' '.$userInfo['user']['location'];
			$backInfo['uImages']          = $userInfo['user']['head'];
			$backInfo['uProvider_uid']    = $userInfo['user']['user_id'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取网易用户信息
	 */
	function getWangyi(){
		$Wangyi = new Providers_Wangyi($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);

		$userInfo = $Wangyi->getUserInfo();

		if(isset($userInfo['name'])){
			$backInfo['uDisplay_name']    = $userInfo['name'];
			$backInfo['uLocalion']        = $userInfo['location'];
			$backInfo['uImages']          = $userInfo['profile_image_url'];
			$backInfo['uProvider_uid']    = $userInfo['id'];
			$backInfo['email']            = $userInfo['email'];
		}else{
			$backInfo['error'] = -1;
		}
		
		return $backInfo;
	}
	/**
	 * 获取搜狐用户信息
	 */
	function getsohu(){
		$sohu = new Providers_Sohu($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);

		$userInfo = $sohu->getUserInfo();

		if(isset($userInfo['screen_name'])){
			$backInfo['uDisplay_name']    = $userInfo['screen_name'];
			$backInfo['uLocalion']        = $userInfo['location'];
			$backInfo['uImages']          = $userInfo['profile_image_url'];
			$backInfo['uProvider_uid']    = $userInfo['id'];
		}else{
			$backInfo['error'] = -1;
		}
	
		return $backInfo;
	}
	/**
	 * 获取Facebook用户信息
	 */
	function getFacebook(){
		$facebook = new Providers_Facebook($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr);
		
		$userInfo = $facebook->getUserInfo();

		if(isset($userInfo['id'])){
			$backInfo['uDisplay_name']    = $userInfo['name'];
			$backInfo['uLocalion']        = $userInfo['location']['name'];
			$backInfo['uImages']          = 'https://graph.facebook.com/'.$userInfo['id'].'/picture?type=large';
			$backInfo['uProvider_uid']    = $userInfo['id'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Google用户信息
	 */
	function getGoogle(){
		$google = new Providers_Google($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $google->getUserInfo();
return $userInfo;
		if(isset($userInfo['id'])){
			$backInfo['uDisplay_name']    = $userInfo['name'];
			$backInfo['uLocalion']        = $userInfo['location']['name'];
			$backInfo['uImages']          = 'https://graph.facebook.com/'.$userInfo['id'].'/picture?type=large';
			$backInfo['uProvider_uid']    = $userInfo['id'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Live用户信息
	 */
	function getLive(){
		$live = new Providers_Live($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $live->getUserInfo();
		
		if(isset($userInfo['id'])){
			$backInfo['uDisplay_name']    = $userInfo['name'];
			$backInfo['uLocalion']        = $userInfo['locale'];
			$backInfo['uImages']          = $this->host.'me/picture?type=small&access_token='.$this->OAuthArr['access_token'];
			$backInfo['uProvider_uid']    = $userInfo['id'];
			$backInfo['homepage']         = $userInfo['link'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Baidu用户信息
	 */
	function getBaidu(){
		$baidu = new Providers_Baidu($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $baidu->getUserInfo();

		if(isset($userInfo['userid'])){
			$backInfo['uDisplay_name']    = $userInfo['username'];
			$backInfo['uLocalion']        = '';
			$backInfo['uImages']          = 'http://tb.himg.baidu.com/sys/portrait/item/'.$userInfo['portrait'];
			$backInfo['uProvider_uid']    = $userInfo['userid'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Diandian用户信息
	 */
	function getDiandian(){
		$diandian = new Providers_Diandian($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
	
		$userInfo = $diandian->getUserInfo();
	
		if(isset($userInfo['response']['name'])){
			$backInfo['uDisplay_name']    = $userInfo['response']['name'];
			$backInfo['uLocalion']        = '';
			$backInfo['uImages']          = $this->host.'v1/blog/'.$userInfo['response']['blogs'][0]['blogCName'].'/avatar/114';
			$backInfo['uProvider_uid']    = $this->OAuthArr['user_id'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Foursquare用户信息
	 */
	function getFoursquare(){
		$foursquare = new Providers_Foursquare($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $foursquare->getUserInfo();

		if(isset($userInfo['response']['user'])){
			$backInfo['uDisplay_name']    = $userInfo['response']['user']['lastName'].$userInfo['response']['user']['firstName'];
			$backInfo['uLocalion']        = '';
			$backInfo['uImages']          = $userInfo['response']['user']['photo'];
			$backInfo['uProvider_uid']    = $userInfo['response']['user']['id'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Github用户信息
	 */
	function getGithub(){
		$github = new Providers_Github($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $github->getUserInfo();

		if(isset($userInfo['id'])){
			$backInfo['uDisplay_name']    = $userInfo['login'];
			$backInfo['uLocalion']        = '';
			$backInfo['uImages']          = $userInfo['avatar_url'];
			$backInfo['uProvider_uid']    = $userInfo['id'];
			$backInfo['homepage']         = $userInfo['html_url'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Linkedin用户信息
	 */
	function getLinkedin(){
		$linkedin = new Providers_Linkedin($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $linkedin->getUserInfo();

		if(isset($userInfo['firstName'])){
			$backInfo['uDisplay_name']    = $userInfo['lastName'].$userInfo['firstName'];
			$backInfo['uLocalion']        = $userInfo['location']['name'];
			$backInfo['uImages']          = $userInfo['pictureUrl'];
			$backInfo['uProvider_uid']    = $userInfo['positions']['values'][0]['id'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Tianyi用户信息
	 */
	function getTianyi(){
		$tianyi = new Providers_Tianyi($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $tianyi->getUserInfo();

		if(isset($userInfo['nickname']['user_nickname'])){
			$backInfo['uDisplay_name']    = $userInfo['nickname']['user_nickname'];
			$backInfo['uLocalion']        = $userInfo['location']['province'];
			$backInfo['uImages']          = $this->host.'upc/vitual_identity/user_avatar?app_id='.$this->api_key.'&access_token='.$this->OAuthArr['access_token'].'&type=json';
			$backInfo['uProvider_uid']    = $this->OAuthArr['user_id'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
	}
	/**
	 * 获取Tumblr用户信息
	 */
	function getTumblr(){
		$tumblr = new Providers_Tumblr($this->host,$this->api_key,$this->api_sercet,$this->OAuthArr,$this->partner);
		
		$userInfo = $tumblr->getUserInfo();

		if(isset($userInfo['response']['user']['name'])){
			$backInfo['uDisplay_name']    = $userInfo['response']['user']['name'];
			$backInfo['uLocalion']        = '';
			$backInfo['uImages']          = 'http://api.tumblr.com/v2/blog/'.$userInfo['response']['user']['name'].'.tumblr.com/avatar/128';
			$backInfo['uProvider_uid']    = $userInfo['response']['user']['name'];
		}else{
			$backInfo['error'] = -1;
		}

		return $backInfo;
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