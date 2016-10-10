<?php
/**
 * Soap 调用Expand平台数据
 * 激活码处理
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类
include_once('soapu.class.php'); //soap类
include_once('MandOAuth.class.php'); //Oauth处理类

class DBSoapExpandInviteCode{
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
		$this->mandOAuth = new MandOAuth('',$this->config);
	}
	/**
	 * 取应用插件信息
	 */
	public function getAppPlugInInfo($appID){
		$tArr['condition'] = 'AppInfoID=\''.$appID.'\'';
		$rb = $this->soapu->SelectTableInfo('SelectAppOauthPlugInInfo', $tArr, '' ,'Expand');
		if(isset($rb['data'])){
			return $rb['data'];
		}else{
			return null;
		}
	}
	/**
	 * 取激活页面提示信息
	 */
	public function getInviteCodeMessage(){
		$rb = $this->soapu->SelectTableInfo('GetInviteCodeMessage', '', '' ,'Plus');
		if(isset($rb['data'])){
			return $rb['data'];
		}else{
			return null;
		}
	}
	/**
	 * 取用户是否激活过
	 */
	public function isActiveInviteCode($fieldArr){
		$tArr['condition'] = 'TUID = \''.$this->mandOAuth->doencrypt($fieldArr['UserID'].'|'.$fieldArr['client_id']).'\' and AppInfoID=\''.$fieldArr['client_id'].'\'';
		
		if($this->soapu->SelectTableInfo('SelectInviteCodeInfo', $tArr, '' ,'Plus')){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 激活
	 */
	public function activeInviteCode($fieldArr){
		$tArr['condition'] = 'inviteCode=\''.trim($fieldArr['inviteCode']).'\'';
		$tArr['TUID']      = $this->mandOAuth->doencrypt($fieldArr['UserID'].'|'.$fieldArr['client_id']);

		$re = $this->soapu->getTableInfo('GetActiveInviteCode', $tArr ,'' , '' ,'Plus');

		if(isset($re['data'])){
			return $re['data'];
		}else{
			return false;
		}
	}
	/**
	 * 取用户所有邀请码
	 */
	public function getUserInviteCodeList($fieldArr){
		$tArr['condition'] = 'AppInfoID=\''.$fieldArr['client_id'].'\' and FUID = \''.$this->mandOAuth->doencrypt($fieldArr['UserID'].'|'.$fieldArr['client_id']).'\'';

		$rb = $this->soapu->SelectTableInfo('SelectInviteCodeInfo', $tArr,'' ,'Plus');

		if(isset($rb['data'])){
			return $rb['data'];
		}else{
			return null;
		}	
	}
	/**
	 * 生成邀请码
	 */
	public function addInviteCode($fieldArr){
		$tArr['AppInfoID'] = $fieldArr['client_id'];
		$tArr['FUID']      = $this->mandOAuth->doencrypt($fieldArr['UserID'].'|'.$fieldArr['client_id']);
		$rb = $this->soapu->InsertTableInfo('InsertInviteCodeInfo', $tArr ,'Plus');

		if(isset($rb['data'])){
			return $rb['data']['InviteCode'];
		}else{
			return null;
		}
	}
	/**
	 * 邀请码鉴权
	 */
	public function checkInviteCodeValid($fieldArr){
		return $fieldArr;
	}
}