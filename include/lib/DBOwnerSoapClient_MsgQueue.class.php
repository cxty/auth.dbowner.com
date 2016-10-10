<?php
/**
 * SOAP客户端
 * @author Cxty
 *
 */

class DBOwnerSoapClient_MsgQueue {
	public $_ExpandWsdl = '';
	
	public $config; // 全局配置
	public $_user;
	public $_pwd;
	public $_vi;
	public $_host;
	public $_soap_client;
	public $_des;
	public $_Cache;
	
	public function __construct() {
		require_once (dirname ( __FILE__ ) . "/DES.class.php");
	
		global $config;
		
		$this->config = $config; // 配置
	
		$this->_Wsdl = $this->config['DES']['Soap_Client_MsgQueue'];
		$this->_host = $this->config['DES']['Soap_Header_MsgQueue'];
		
		$this->_Wsdl = 'http://messagequeue.dbowner.com/soap.php?wsdl';
		$this->_host = 'http://messagequeue.dbowner.com/soap';
		
		$this->_user = $this->config['DES']['SOAP_USER'];
		$this->_pwd  = $this->config['DES']['SOAP_PWD'];
		$this->_vi   = $this->config['DES']['SOAP_IV'];
	
		$this->_des = new DES ( $this->_pwd, $this->_vi );
	
		$this->_soap_client =$this->SOAP_Client();
	}
	
	/**
	 * 初始化SOAP
	 * @return SoapClient
	 */
	public function SOAP_Client(){
		$client = new SoapClient ( $this->_Wsdl,array('compression'=>true));
		// 增加SoapHeader验证
		$soap_var = new SoapVar ( array (
				'user' => $this->_user
		), SOAP_ENC_OBJECT, 'Auth' );
		$header = new SoapHeader ( $this->_host, 'Auth', $soap_var, true );
		$client->__setSoapHeaders ( $header );
		return $client;
	}
	
	/**
	 * 对unicode码再进行解码
	 */
	private function decodeUnicode($str) { 
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function( '$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");' ), $str); 
	}
	
	/**
	 * 取指定用户在指定App的用户ID
	 */
	public function AddMessageTask ( $Type, $FromAddress, $Parameters, $SendTime ) {
		$_re_data=null;
		$_UserInfo = null;
		try{
			$_val = $this->_des->encrypt ( json_encode ( array(
					'Type'        => $Type,
					'AppID'       => $this->config['PLATFORM']['Auth_client_id'],//'80002001',
					'Tip'         => '',
					'FromAddress' => $FromAddress,
					'Parameters'  => $this->decodeUnicode(json_encode($Parameters)),
					'SendTime'    => $SendTime,
			) ) );
			
			$_re = json_decode ( $this->_soap_client->AddMessageTask ( array (
					'data' => json_encode ( array (
							'data' => $_val
					) )
			) )->return );
			
			if (isset ( $_re->data )) {
				$_re_data = json_decode($this->_des->decrypt ( $_re->data ));
				
				if($_re_data){
					$_UserInfo = $_re_data->data;
				}
			}
		}catch ( Exception $e ) {
			//printf ( "Message = %s", $e->__toString () );
		}
		return $_UserInfo;
	}
}
?>