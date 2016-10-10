<?php
/**
 * 用户基本信息
 *
 * @author wbqing405@sina.com
 */

include(dirname(__FILE__). '/DBBaseService.class.php');

class DBGetUserInfo extends DBBaseService {
	
	public function __construct ($partner, $provider, $OAuthArr) {
		parent::__construct($partner, $provider, $OAuthArr);
	}
	
	/**
	 * 总接口-获取用户信息
	 */
	public function getUserInfo ( $fieldArr=array() ) {
		//调用父类返回值
		$_base = parent::getThirdParty();
		
		if ( $_base['state'] ) {
			$_rb = $this->$_base['data']['className']( $fieldArr );
			if ( $_rb['state'] ) {
				return $this->_return(true, 'ok',  $_rb['data']);
			} else {
				return $this->_return(false, 'Error returns from DBOwner or Third party',  $_rb['data']);
			}
		} else {
			return $this->_return(false, $_base['msg'], $_base['data'] );
		}
	}
	
	/**
	 * 获取新浪微博用户信息
	 */
	protected function getSina ( $fieldArr ) {
		$Sina = parent::getSina();
		
		$_rb = $Sina->getUserInfo( $this->OAuthArr['user_id'] );
		
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
			
			$_rcb   = $_rb;
		}else{
			$_state = true;
			
			$_rcb['uDisplay_name']   = $_rb['screen_name'];
			$_rcb['uProvider_uid']   = $this->OAuthArr['user_id'];
			$_rcb['uImages']         = $_rb['profile_image_url'];
			$_rcb['uLocalion']       = $_rb['location'];
			$_rcb['uProfile_url']    = 'http://weibo.com/' . $_rb['profile_url'];
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取豆瓣用户信息
	 */
	protected function getDouban ( $fieldArr ) {
		$Douban = parent::getDouban();
		
		$uid = $this->OAuthArr['user_id'];
	
		$_rb = $Douban->getUserInfo($uid);
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
			
			$_rcb = $_rb;
		}else{
			$_state = true;
			
			$_rcb['uDisplay_name']   = $_rb['name'];
			$_rcb['uProvider_uid']   = $_rb['id'];
			$_rcb['uImages']         = $_rb['avatar'];
			$_rcb['uLocalion']       = $_rb['loc_name'];
			$_rcb['uProfile_url']    = $_rb['alt'];
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取QQ用户信息
	 */
	protected function getQQ ( $fieldArr ) {
		$qq = parent::getQQ();
		
		$_rb = $qq->getUserInfo();
	
		if ( isset($_rb['dbowner_error']) || $_rb['ret'] != 0 ) {
			$_state = false;
				
			$_rcb = $_rb;
		}else{
			$_state = true;
			
			$_rcb['uDisplay_name']    = $_rb['nickname'];
			$_rcb['uProvider_uid']    = $this->OAuthArr['openid'];
			$_rcb['uImages']          = $_rb['figureurl_2'];
			$_rcb['uLocalion']        = '';
			$_rcb['uProfile_url']     = $this->OAuthArr['openid'];
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取人人用户信息
	 */
	protected function getRenren ( $fieldArr ) {
		$Renren = parent::getRenren();
	
		$tArr['userId'] = $fieldArr['uid'] ? $fieldArr['uid'].'@' : $this->OAuthArr['user_id'];
		
		$_rb = $Renren->getUserInfo( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
				
			$_rcb['error'] = $_rb;
		}else{
			$_state = true;
			
			$_rcb['uDisplay_name']    = $_rb['response']['name'];
			$_rcb['uProvider_uid']    = $_rb['response']['id'];
			$_rcb['uImages']          = $_rb['response']['avatar'][1]['url'];
			$_rcb['uLocalion']        = $_rb['response']['basicInformation']['homeTown']['province'] . ' ' . $_rb['response']['basicInformation']['homeTown']['city'];
			$_rcb['uProfile_url']     = 'http://www.renren.com/' . $_rb['response']['id'] . '/profile';
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取开心用户信息
	 */
	protected function getKaixin () {
		$Kaixin = parent::getKaixin();
	
		$_rb = $Kaixin->getUserInfo();
		
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
		
			$_rcb['error'] = $_rb;
		}else{
			$_state = true;
				
			$_rcb['uDisplay_name']    = $_rb['name'];
			$_rcb['uProvider_uid']    = $_rb['uid'];
			$_rcb['uImages']          = $_rb['logo120'];
			$_rcb['uLocalion']        = $_rb['hometown'] . ' ' . $_rb['city'];
			$_rcb['uProfile_url']     = 'http://www.kaixin001.com/home/?_profileuid=' . $_rb['uid'];
		}
		
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取天涯用户信息
	 */
	protected function getTianya () {
		$Tianya = parent::getTianya();
	
		$_rb = $Tianya->getUserInfo();
		
		if ( isset($_rb['dbowner_error']) || isset($_rb['error_code']) ) {
			$_state = false;
		
			$_rcb['error'] = $_rb;
		}else{
			$_state = true;
		
			$_rcb['uDisplay_name']    = $_rb['user']['user_name'];
			$_rcb['uProvider_uid']    = $_rb['user']['user_id'];
			$_rcb['uImages']          = $_rb['user']['head'];
			$_rcb['uLocalion']        = $_rb['user']['province'] . ' ' . $_rb['user']['location'];
			$_rcb['uProfile_url']     = 'http://www.tianya.cn/' . $_rb['user']['user_id'];
		}
		
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取搜狐用户信息
	 */
	protected function getSohu () {
		$sohu = parent::getSohu();
	
		$_rb = $sohu->getUserInfo();
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
			
			$_rcb['error'] = $_rb;
		}else{
			$_state = true;
		
			$_rcb['uDisplay_name']    = $_rb['screen_name'];
			$_rcb['uProvider_uid']    = $_rb['id'];
			$_rcb['uImages']          = $_rb['profile_image_url'];
			$_rcb['uLocalion']        = $_rb['location'];
			$_rcb['uProfile_url']     = 'http://t.sohu.com/u/' . $_rb['id'];
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}

	/**
	 * 获取网易用户信息
	 */
	protected function getWangyi () {
		$Wangyi = parent::getWangyi();
	
		$_rb = $Wangyi->getUserInfo();

		if ( isset($_rb['dbowner_error']) || isset($_rb['error_code']) ) {
			$_state = false;
				
			$_rcb['error'] = $_rb;
		}else{
			$_state = true;
		
			$_rcb['uDisplay_name']    = $_rb['name'];
			$_rcb['uProvider_uid']    = $_rb['id'];
			$_rcb['uImages']          = $_rb['profile_image_url'];
			$_rcb['uLocalion']        = $_rb['location'];
			$_rcb['uProfile_url']     = 'http://t.163.com/' . $_rb['screen_name'];
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取Baidu用户信息
	 */
	protected function getBaidu () {
		$baidu = parent::getBaidu();
	
		$_rb = $baidu->getUserInfo();
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['error_code']) ) {
			$_state = false;
		
			$_rcb['error'] = $_rb;
		}else{
			$_state = true;
		
			$_rcb['uDisplay_name']    = $_rb['username'];
			$_rcb['uProvider_uid']    = $_rb['userid'];
			$_rcb['uImages']          = 'http://tb.himg.baidu.com/sys/portrait/item/' . $_rb['portrait'];
			$_rcb['uLocalion']        = $_rb['location'];
			$_rcb['uProfile_url']     = 'http://www.baidu.com/p/' . $_rb['username'];
		}
		
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取Diandian用户信息
	 */
	protected function getDiandian () {
		$diandian = parent::getDiandian();
	
		$_rb = $diandian->getUserInfo();
	
		if ( isset($_rb['dbowner_error']) || ($_rb['meta']['status'] && intval($_rb['meta']['status']) != 200) ) {
			$_state = false;
		
			$_rcb['error'] = $_rb;
		}else{
			$_state = true;
		
			$_rcb['uDisplay_name']    = $_rb['response']['name'];
			$_rcb['uProvider_uid']    = $this->OAuthArr['user_id'];
			$_rcb['uImages']          = $this->host . 'v1/blog/' . $_rb['response']['blogs'][0]['blogCName'] . '/avatar/114';
			$_rcb['uLocalion']        = $_rb['location'];
			$_rcb['uProfile_url']     = $_rb['response']['blogs'][0]['blogCName'];
		}
		
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取Tianyi用户信息
	 */
	protected function getTianyi () {
		$tianyi = parent::getTianyi();
	
		$_rb = $tianyi->getUserInfo();
	
		if ( isset($_rb['nickname']['user_nickname']) ) {
			$_state = true;
			
			$_rcb['uDisplay_name']    = $_rb['nickname']['user_nickname'];
			$_rcb['uProvider_uid']    = $this->OAuthArr['user_id'];
			$_rcb['uImages']          = $this->host . 'upc/vitual_identity/user_avatar?app_id=' . $this->api_key . '&access_token=' . $this->OAuthArr['access_token'] . '&type=json';
			$_rcb['uLocalion']        = $_rb['location']['province'];
			$_rcb['uProfile_url']     = '';
		}else{
			$_state = false;
			
			$_rcb['error'] = -1;
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取Live用户信息
	 */
	protected function getLive () {
		$live = parent::getLive();
	
		$_rb = $live->getUserInfo();
		
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
			
			$_rcb = $_rb;
		}else{
			$_state = true;
			
			$_rcb['uDisplay_name']    = $_rb['name'];
			$_rcb['uProvider_uid']    = $_rb['id'];
			$_rcb['uImages']          = $this->host . 'me/picture?type=small&access_token=' . $this->OAuthArr['access_token'];
			$_rcb['uLocalion']        = $_rb['locale'];
			$_rcb['uProfile_url']     = $_rb['link'];
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取Linkedin用户信息
	 */
	protected function getLinkedin () {
		$linkedin = parent::getLinkedin();
	
		$_rb = $linkedin->getUserInfo();
	
		if( isset($_rb['dbowner_error']) || isset($_rb['errorCode']) ) {
			$_state = false;
			
			$_rcb = $_rb;
		}else{
			$_state = true;
			
			$_rcb['uDisplay_name']    = $_rb['lastName'] . $_rb['firstName'];
			$_rcb['uProvider_uid']    = $_rb['positions']['values'][0]['id'];
			$_rcb['uImages']          = $_rb['pictureUrl'];
			$_rcb['uLocalion']        = $_rb['location']['name'];
			$_rcb['uProfile_url']     = '';
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取Foursquare用户信息
	 */
	protected function getFoursquare () {
		$foursquare = parent::getFoursquare();
	
		$_rb = $foursquare->getUserInfo();
	ComFun::pr($_rb);exit;
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
	protected function getGithub () {
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
	 * 获取Tumblr用户信息
	 */
	protected function getTumblr () {
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
	 * 获取Facebook用户信息
	 */
	protected function getFacebook () {
		return array('state' => false, 'data' => array('msg' => 'The third party had not tested'));
	
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
	protected function getGoogle () {
		return array('state' => false, 'data' => array('msg' => 'The third party had not tested'));
	
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
}
