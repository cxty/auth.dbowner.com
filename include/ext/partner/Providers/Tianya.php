<?php
/**
 * 天涯社区信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Tianya{
	
	var $format = 'json';
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;
		
		$this->oauth_token   = $OAuthArr['oauth_token'];
		$this->oauth_token_secret    = $OAuthArr['oauth_token_secret'];
	}
	function do_get($url,$param=null){
		$param['timestamp'] = time();
		$param['appkey'] = $this->api_key;
		$param['tempkey'] = strtoupper(md5($param['timestamp'].$this->api_key.$this->oauth_token.$this->api_sercet.$this->api_sercet));
		$param['oauth_token'] = $this->oauth_token;
		$param['oauth_token_secret'] = $this->api_sercet;
		$addstr = http_build_query($param);
		$url.='?'.$addstr;
		return request($url,null,'get');
	}
	function do_post($url,$param=null){
		$param['timestamp'] = time();
		$param['appkey'] = $this->api_key;
		$param['tempkey'] = strtoupper(md5($param['timestamp'].$this->api_key.$this->oauth_token.$this->oauth_token_secret.$this->api_sercet ));
		$param['oauth_token'] = $this->oauth_token;
		if($param['media'] && realpath($param['media'])) $param['media'] = '@'.realpath($param['media']);
		$param['oauth_token_secret'] = $this->oauth_token_secret;
		return json_decode($this->request($url,$param),true);
	
	}
	function request($url,$param=null,$method='post'){
		if($method=='get'){
			$send_data.= http_build_query($param);
			
			if(eregi('\?',$url)){
				$url.= '&'.$send_data;
			}else{
				$url.= '?'.$send_data;
			}
				
		}
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		if($method=='post'){
			$send_data = $param;
			curl_setopt($ch, CURLOPT_POST, 1);
			//添加变量			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $send_data);
		}
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$MySources = curl_exec ($ch);
		curl_close($ch);

		return $MySources;
	}
	
	/**
	 * 获取用户信息
	 */
	function getUserInfo($uid=''){		

		return $this->do_post( $this->host . 'user/info.php' );
	}
	
	/**
	 * 广播一条信息
	 */
	function addInformation( $fieldArr ) {		
		if ( !$fieldArr['content'] ) {
			return array('dbowner_error' => -1, 'msg' => 'content is empty');
		}
		
		$params = array();
		$params['word'] = $fieldArr['content'];
		
		return $this->do_post( $this->host.'weibo/add.php', $params ? $params : array() );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		if ( !$fieldArr['id'] ) {
			return array('dbowner_error' => -1, 'msg' => 'uid is empty');
		}
		
		$params = array();
		$params['id'] = $fieldArr['id'];
		
		return $this->do_post( $this->host.'socialgraph/addfollow.php', $params ? $params : array() );
	}
	
	/**
	 * 返回粉丝信息列表
	 */
	function getFriendList( $fieldArr ) {
		$url = $this->host.'socialgraph/getfans.php'; //发微博接口地址
		$params = array();
		$params['userId']   = $fieldArr['userId']; //69537366
		$params['page']     = $fieldArr['page'];
		$params['pagesize'] = $fieldArr['pagesize'];
		
		return $this->do_post( $this->host.'socialgraph/getfollow.php', $params ? $params : array() );
	}
}