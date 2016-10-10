<?php 
/**
 * OpenID通信的类
 * 
 * @author wbqing405@sina.com
 *
 */
class CommonOpenID{
	function __construct($callback,$openid=NULL,$openidIdentifier=NULL){
		$this->callback = $callback;
		$this->openid = $openid;
		$this->openidIdentifier = $openidIdentifier;
	}
	
	/**
	 * OpenID登录
	 */
	function getLogin(){
		$provider = $this->getCookies('provider');
		
		switch(strtolower($provider)){
			case 'yahoo':
				$this->getYahoo();
				break;
			case 'google':
				return $this->getGoogle();
				break;
			default:
				throw new Exception('The provider is not exist,please check it');
				break;
		}
	}
	
	/**
	 * 获取Yahoo用户信息
	 */
	private function getYahoo(){
		//echo $this->openidIdentifier;exit;
		$this->connect = $this->getClass('OpenID');
		$this->connect->identity = $this->openidIdentifier;
		$this->connect->required = array(
				'namePerson/friendly',
				'contact/email' ,
				'contact/country/home',
				'namePerson/first',
				'pref/language',
				'namePerson/last'
		);
		if(!$this->connect->mode){
			$url = $this->connect->authUrl();
			$this->redirect($url);
		}
		
		$this->getAccessToken($args);
		return $this->showUser();
		exit;
	}
	
	/**
	 * 获取Google用户信息
	 */
	private function getGoogle(){
		$this->connect = $this->getClass('OpenID');
		$this->connect->identity = $this->openidIdentifier;
		$this->connect->required = array(
				'namePerson/friendly',
				'contact/email' ,
				'contact/country/home',
				'namePerson/first',
				'pref/language',
				'namePerson/last'
		);
		if(!$this->connect->mode){
			$url = $this->connect->authUrl();
			$this->redirect($url);
		}
		
		$this->getAccessToken($args);
		
		$getUserInfo = $this->getClass('Google');
		return $getUserInfo->getUserInfo();
	}
	
	/**
	 * 转向
	 */
	function redirect($url){
		header ( 'location:' . $url, false, 301 );
		exit ();
	}
	
	function getAccessToken($args=array()){
		if($this->connect->mode == 'cancel'){
			throw new Exception('User has canceled.');
		}else{
			if($this->connect->validate()){
				return $this->connect->validate();
			}else{
				throw new Exception('User has not logged in.');
			}
		}
	}

	/**
	 * 取加密的$_COOKIE值
	 */
	public function getCookies($value=false){
		include_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/include/lib/ComFun.class.php');
		return ComFun::getCookies($value);
	}
	
	/**
	 * 取得OAuth处理类
	 */
	private function getClass($className){
		$root = dirname(dirname(__FILE__));
		switch($className){
			case 'OpenID':
				include_once('OpenID.php');
				//return new OpenID($this->openid);
				return new OpenID($this->callback);
				break;
			case 'Google':
				include_once($root.'/providers/Google.php');
				return new Providers_Google($this->connect);
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