<?php
/**
 * Soap 调用Expand平台数据
 * Oauth登录权限控制
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类
include_once('soapu.class.php'); //soap类
include_once('MandOAuth.class.php'); //Oauth处理类

class DBSoapExpandOauthPerm{
	public function __construct($config){
		$this->config   = $config;
		
		$this->init();
	}
	/**
	 * 初始化对象
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
		$this->soapu = new soapu($this->config);
		$this->mandOAuth = new MandOAuth('',$this->config);;
	}
	/**
	 * 选出Oauth登录权限信息
	 */
	public function getOauthPermList(){
		$tArr['condition'] = 'oStatues = \'0\'';
		$rb = $this->soapu->SelectTableInfo('SelectOauthPermInfo', $tArr, 'oAppendTime asc');

		if(isset($rb['data'])){
			return $rb['data'];
		}else{
			return null;
		}
	}
	/**
	 * 保存Oauth用户授权信息
	 */
	public function SaveOauthPerm($fieldArr){
		$data['AppInfoID']   = $fieldArr['client_id'];
		$data['UserID']      = $this->mandOAuth->doencrypt($fieldArr['UserID'].'|'.$fieldArr['client_id']);
		
		if($fieldArr['OauthPerm']){
			$uPermArr = explode('|', substr($fieldArr['OauthPerm'],1));
		}		

		$data['uPermission'] = json_encode(array(array('AppPlugInID' => $this->config['Expand']['AppPlugIn']['OauthLoginCode'], 'Attach' => $uPermArr)));

		return $this->soapu->InsertTableInfo('InsertUserOauthPermInfo', $data);
	}
}