<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 头像接口
 *
 */
class provitejsMod extends commonMod {
	public function test(){
		exit;
		$this->display('index/test3.html');
	}
	/**
	 * 用户头像信息
	 */
	public function userbox(){
		header ( "content-type: text/javascript; charset=utf-8" );

		$lang  = $_GET['lang'] ? $_GET['lang'] : 'zh' ;
		$top   = $_GET['top'] ? $_GET['top'] : '10' ;
		$lg    = $_GET['lg'] ? $_GET['lg'] : false ;
		
		$uName = ComFun::getCookies('uName') ? ComFun::getCookies('uName') : '';
		
		$root = $this->config['PLATFORM']['Auth'];
		$public = $this->config['PLATFORM']['Auth'] . '/public';
		
		if($uName){
			$html = 'var js_u_ui={
							root : "'.$root.'",
							css : "'.$public.'/css/js_userInfo.css",
							uName : "'.$uName.'",
							portrait : "'.$this->getPortrait().'",
							unreadNum:"'.$this->getUnreadNum().'",
							JS_LANG : '.json_encode(Lang::getAssign('JS_LANG',$lang)).',
							top : "' . ($top+39) . '",
							lg : "' . $lg . '",
						};';
		}else{
			$html = 'var js_u_ui={
							root : "'.$root.'",
							css : "'.$public.'/css/js_userInfo.css",
							uName : "'.$uName.'",
							redirect : "'.$_SERVER['REQUEST_URI'].'",
							JS_LANG : '.json_encode(Lang::getAssign('JS_LANG',$lang)).',	
							lg : "' . $lg . '",					
						};';
		}
		
		$html .='(function() {';		
 		$html .= 'document.write(\'<div id="ui_userShell" style="margin-top:' . $top . 'px;"></div>\');';
		$html .= 'var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true; ';
		$html .= 'ga.src = "'.$public.'/js/js_userInfo.js"; ';
		$html .= 'var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.appendChild(ga, s); ';
		$html .='})();';

		echo $html;
	}
	/**
	 * 取未读短信息数目
	 */
	public function getunreadcount(){
		$jsonp_callback=$_GET['callback'];//...//如果正确

		echo $jsonp_callback."(".json_encode($this->getUnreadNum()).")"; return;
	}
}