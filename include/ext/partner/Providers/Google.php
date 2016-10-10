<?php
/**
 * Google信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Google{
	
	var $format = 'json';
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr,$partner){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;

		$this->refresh_token = $OAuthArr['refresh_token'];
		$this->access_token = $OAuthArr['access_token'];
		$this->uid = $OAuthArr['user_id'];

		$root = dirname(dirname(__FILE__));
		
		include($root.'/common/PartnerOAuth2.php');
		$this->oauth = new PartnerOAuth2($this->api_key,$this->api_sercet,$this->access_token,$this->refresh_token,$this->host,$partner);
		
	}
	/**
	 * get方式获取信息
	 */
	function _get($url, $params){	
		if(is_array($params)){
			$url .= '?'.http_build_query($params);
		}
		
		$html = "<script language=\"javascript\" type=\"text/javascript\">";
		$html .= "var req = new XMLHttpRequest();";
		$html .= "req.open('GET', '" . $url . "', true);";
		$html .= "req.setRequestHeader('Authorization', 'Bearer " . $this->access_token . "');";
		$html .= "req.onreadystatechange = function (e) {";
		$html .= "if (req.readyState == 4) {";
		$html .= "if(req.status == 200){";
		$html .= "   var text = req.responseText;";
		$html .= "   var _text = eval(\"(\"+text+\")\");";
		//$html .= $a.'';
		//$a = "_text.name";
		$html .= "   alert(_text.name);";
		$html .= "   return _text";
		$html .= "}else if(req.status == 400) {";
		$html .= "  alert(1);";
		//$html .= "	 return eval(\"({\"error\":\"false\",\"msg\":\"There was an error processing the token.\"})\");";
		$html .= "}else{";
		$html .= "  alert(2);";
		//$html .= "	 return eval(\"({\"error\":\"false\",\"msg\":\"something else other than 200 was returned.\"})\");";
		$html .= "}}};";
		$html .= "req.send(null);";
		$html .= "</script>";

		
		return $html;
	}
	/**
	 * 当前登录者的信息
	 *
	 * @param unknown_type $uid
	 */
	function getUserInfo(){
		$params['access_token'] = $this->access_token;
		
		$url = $this->host.'oauth2/v2/userinfo';
		
		//return $url;
		
		//return $this->_get($url, $params);
		
		return  $this->oauth->get( $url, $params );
		
		return $this->oauth->get( $this->host.'plus/v1/people/me', $params );
		return $this->oauth->get( $this->host.'oauth2/v2/userinfo' , $params );
	}
}