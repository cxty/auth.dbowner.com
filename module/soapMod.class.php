<?php 
/**
 * soap调用接口
 * @author wbqing405@sina.com
 *
 */
class soapMod {
	/**
	 * 用户调用接口
	 */
	public function userInfoSoap(){
		ini_set("soap.wsdl_cache_enabled", "0");
		include_once(dirname(dirname(__FILE__)).'/include/api/ManageUser.php');
		$server=new SoapServer(dirname(dirname(__FILE__)).'/Interface/ManageUser.wsdl', array('soap_version' => SOAP_1_2));
		$server->setClass(ManageUser);
		$server->handle();
	}
}
?>