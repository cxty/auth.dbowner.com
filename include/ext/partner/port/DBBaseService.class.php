<?php
/**
 * 接口调用基类
 *
 * @author wbqing405@sina.com
 */
class DBBaseService {
	
	public function __construct ($partner, $provider, $OAuthArr) {
		$this->partner            = $partner;
	
		$this->api_key            = $provider['keys']['api_key'];
		$this->api_sercet         = $provider['keys']['api_sercet'];
	
		$this->host               = $provider['urls']['hostURL'];
		$this->wrapper            = ucfirst($provider['wrapper']);
		$this->authway            = $provider['authway'];
	
		$this->OAuthArr = $OAuthArr;
	}
	
	/**
	 * 返回值
	 * @param unknown_type $state 操作成功与否
	 * @param unknown_type $msg 操作说明
	 * @param unknown_type $data 返回数据
	 * @return multitype:unknown
	 */
	protected function _return ($state, $msg, $data) {
		return array('state' => $state, 'msg' => $msg, 'data' => $data);
	}
	
	/**
	 * 获取不同服务提供商的用户信息
	 */
	public function getThirdParty () {
		$root = dirname(dirname(__FILE__)) . '/' . $this->wrapper;
	
		if ( !is_file( $root ) ) {
			return $this->_return(false, 'file is not exist', array() );
		}
	
		require_once $root;
	
		switch(strtolower($this->partner)){
			case 'sina':
				return $this->_return(true, 'ok', array('className' => 'getSina') );
				break;
			case 'douban':
				return $this->_return(true, 'ok', array('className' => 'getDouban') );
				break;
			case 'qq':
				return $this->_return(true, 'ok', array('className' => 'getQQ') );
				break;
			case 'renren':
				return $this->_return(true, 'ok', array('className' => 'getRenren') );
				break;
			case 'kaixin':
				return $this->_return(true, 'ok', array('className' => 'getKaixin') );
				break;
			case 'tianya':
				return $this->_return(true, 'ok', array('className' => 'getTianya') );
				break;
			case 'wangyi':
				return $this->_return(true, 'ok', array('className' => 'getWangyi') );
				break;
			case 'sohu':
				return $this->_return(true, 'ok', array('className' => 'getSohu') );
				break;
			case 'facebook':
				return $this->_return(true, 'ok', array('className' => 'getFacebook') );
				break;
			case 'google':
				return $this->_return(true, 'ok', array('className' => 'getGoogle') );
				break;
			case 'live':
				return $this->_return(true, 'ok', array('className' => 'getLive') );
				break;
			case 'baidu':
				return $this->_return(true, 'ok', array('className' => 'getBaidu') );
				break;
			case 'diandian':
				return $this->_return(true, 'ok', array('className' => 'getDiandian') );
				break;
			case 'foursquare':
				return $this->_return(true, 'ok', array('className' => 'getFoursquare') );
				break;
			case 'github':
				return $this->_return(true, 'ok', array('className' => 'getGithub') );
				break;
			case 'linkedin':
				return $this->_return(true, 'ok', array('className' => 'getLinkedin') );
				break;
			case 'tianyi':
				return $this->_return(true, 'ok', array('className' => 'getTianyi') );
				break;
			case 'tumblr':
				return $this->_return(true, 'ok', array('className' => 'getTumblr') );
				break;
			default:
				return $this->_return(false, 'The Provider is not exist!', array() );
				break;
		}
	}
	
	/**
	 * 实例化类-新浪微博
	 */
	protected function getSina () {
		return new Providers_Sina ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr );
	}
	
	/**
	 * 实例化类-豆瓣
	 */
	protected function getDouban () {
		return new Providers_Douban ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-QQ
	 */
	protected function getQQ () {
		return new Providers_Qq ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-人人
	 */
	protected function getRenren () {
		return new Providers_Renren ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-开心
	 */
	protected function getKaixin () {
		return new Providers_Kaixin ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-天涯
	 */
	protected function getTianya () {
		return new Providers_Tianya ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr );
	}
	
	/**
	 * 实例化类-网易
	 */
	protected function getWangyi () {
		return new Providers_Wangyi ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr );
	}
	
	/**
	 * 实例化类-搜狐
	 */
	protected function getSohu () {
		return new Providers_Sohu ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Facebook
	 */
	protected function getFacebook () {
		return new Providers_Facebook ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr );
	}
	
	/**
	 * 实例化类-Google
	 */
	protected function getGoogle () {
		return new Providers_Google ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Live
	 */
	protected function getLive () {
		return new Providers_Live ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Baidu
	 */
	protected function getBaidu () {
		return new Providers_Baidu ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Diandian
	 */
	protected function getDiandian () {
		return new Providers_Diandian ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Foursquare
	 */
	protected function getFoursquare () {
		return new Providers_Foursquare ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Github
	 */
	protected function getGithub () {
		return new Providers_Github ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Linkedin
	 */
	protected function getLinkedin () {
		return new Providers_Linkedin ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Tianyi
	 */
	protected function getTianyi () {
		return new Providers_Tianyi ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
	
	/**
	 * 实例化类-Tumblr
	 */
	protected function getTumblr () {
		return new Providers_Tumblr ( $this->host, $this->api_key, $this->api_sercet, $this->OAuthArr, $this->partner );
	}
}
