<?php
/**
 * SOAP调用例子
 *
 * @author wbqing405@sina.com
 */
class soapu {

	public function __construct($config){
		$this->config      = $config;
		$this->SOAP_USER   = $config['DES']['SOAP_USER'];
		$this->DES_PWD     = $config['DES']['SOAP_PWD'];
		$this->DES_IV      = $config['DES']['SOAP_IV'];
		
		if(isset($config['DES']['ident'])){
			if($config['DES']['ident'] != 'private'){
				require(dirname(__FILE__)."/DES.class.php");
			}
		}else{
			require(dirname(__FILE__)."/DES.class.php");
		}
	}
	/**
	 * 初始化Soap
	 */
	protected function _Soap($type){
		switch($type){
			case 'Expand':
				$this->Soap_Client = $this->config['DES']['Soap_Client_Expand'];
				$this->Soap_Header = $this->config['DES']['Soap_Header_Expand'];
				break;
			case 'Plus':
				$this->Soap_Client = $this->config['DES']['Soap_Client_Plus'];
				$this->Soap_Header = $this->config['DES']['Soap_Header_Plus'];
				break;
			case 'User':
				$this->Soap_Client = $this->config['DES']['Soap_Client_User'];
				$this->Soap_Header = $this->config['DES']['Soap_Header_User'];
				break;
			default:
				$this->Soap_Client = $this->config['DES']['Soap_Client_Auth'];
				$this->Soap_Header = $this->config['DES']['Soap_Header_Auth'];
				break;
		}
	}
	/**
	 * 处理SoapClient方法
	 * @param  $className 接口方法名
	 * @param  $conArr 数据数组
	 */
	private function manSoap($className,$conArr){
//  	echo $this->Soap_Client;
// 		echo '<br>';
// 		echo $className;
// 		echo '<br>';
// 		exit;
		try{	
			$des = new DES($this->DES_PWD,$this->DES_IV);
		
			$client = new SoapClient($this->Soap_Client,array("trace"=>true));
			
			$soap_var = new SoapVar(array('user'=>$this->SOAP_USER), SOAP_ENC_OBJECT,'Auth');
			$header = new SoapHeader($this->Soap_Header, 'Auth', $soap_var, true);
			$client->__setSoapHeaders($header);
			
			//$this->pr($conArr);//exit;
			$val = $des->encrypt(json_encode($conArr));

// 			$tVal = array('data'=>json_encode(array('data'=>$val)));					
// 			$re = $client->$className($tVal)->return;		
// 			$_re =  json_decode($re);
			
			//$this->pr(array('data'=>json_encode(array('data'=>$val))));//exit;
			
			$_re =  json_decode($client->$className(array('data'=>json_encode(array('data'=>$val))))->return);

			if(isset($_re->data)){
				if($_re->data){
					return json_decode($des->decrypt($_re->data),true);
				}else{
					return false;
				}
			}else{
				return false;
			}
		}catch (Exception $e) {
			
			//echo $client->__getLastRequest();
			//echo $client->__getLastResponse();
			echo $e->getMessage();
			
			exit;
				
			
			printf("Message = %s",$e->__toString());
		}
	}
	
	/**
	 * 取表信息
	 */
	public function getTableInfo($tableName,$condition=null,$order=null, $AppInfoID=null, $type='Auth'){
		$this->_Soap($type);
		
		$conArr = $condition;
	
		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}
		if($AppInfoID){
			$conArr['AppInfoID'] = $AppInfoID;
		}

		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 取表信息
	 */
	public function SelectTableInfo($tableName, $condition=null, $order=null, $type='Auth'){
		$this->_Soap($type);
	
		$conArr = $condition;
		
		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}

		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 更新表消息
	 */
	public function UpdateTableInfo($tableName, $udata, $type='Auth'){
		$this->_Soap($type);
		
		$conArr = $udata;

		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 删除用户信息
	 */
	public function DeleteTableInfo($tableName, $condition=null, $type='Auth'){
		$this->_Soap($type);
		
		$conArr = $condition;
		
		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 取用户列表
	 */
	public function GetTableList($tableName, $page=1, $rowNum=10, $condition=null, $order=null, $type='Auth'){
		$this->_Soap($type);
		
		$conArr['page']      = $page;
		$conArr['pagesize']  = $rowNum;
		
		if($condition){
			$conArr['condition'] = $condition;
		}else{
			$conArr['condition'] = '';
		}
		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}

		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 增加用户基础信息
	 */
	public function InsertTableInfo($tableName, $data, $type='Auth'){
		$this->_Soap($type);
		
		$conArr = $data;

		return $this->manSoap($tableName,$conArr);
	}
	private function pr($arr=null){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
?>