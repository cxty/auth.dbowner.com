<?php

include(dirname(__FILE__). '/Fun.class.php');
include(dirname(dirname(__FILE__)) . '/lib/DES.class.php');
include(dirname(dirname(__FILE__)) . '/lib/ComFun.class.php');
class Server{
	public $Unauthorized_User; // 非法用户访问
	public $Unauthorized_IP; // 非法IP访问
	public $DataFormatError; // 数据格式错误
	
	public function __construct() {

	}
	
	/**
	 * 非法用户访问
	 */
	public function Unauthorized_User(){
		return $this->_return ( false, 'Unauthorized User', null );
	}
	/**
	 * 非法IP访问
	 */
	public function Unauthorized_IP(){
		return $this->_return ( false, 'Unauthorized IP', null );
	}
	/**
	 * 数据格式错误
	 */
	public function DataFormatError(){
		return $this->_return ( false, 'Data Format Error', null );
	}
	/**
	 * 连接数据库
	 */
	public function getConnect(){
		include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
		include(dirname(dirname(__FILE__)).'/core/DBOModel.class.php');
		include(dirname(dirname(__FILE__)).'/core/db/mysql.class.php');
		return new DBOModel($config);
	}
	/**
	 * 选择类
	 * @param string $ClassName 类名
	 */
	public function RequireClass($model,$className) {
		if (file_exists ( dirname(__FILE__). '/DBO/' . $className . '.class.php' )){			
			$this->model = $this->getConnect();

			include(dirname(__FILE__). '/DBO/' . $className . '.class.php');
			return new $className ( $this->model);
		}
	}
	
	/**
	 * 选择lib下面的类
	 * @param string $ClassName 类名
	 */
	public function getlib( $className ) {
		include dirname(dirname(__FILE__)) . '/lib/' . $className . '.class.php';
		$this->model = $this->getConnect();
		return new $className ( $this->model);
	}
	
	/**
	 * 获取lib下面的类
	 */
	public function getClass ( $model, $className, $params=array() ) {
		include dirname(dirname(__FILE__)) . '/lib/' . $className . '.class.php';
	
		if ( empty($params) ) {
			return new $className ( $model );
		} else {
			return new $className ( $model, $params);
		}
	}
	
	/**
	 * 获取lib下面的soap类
	 */
	public function getSoap ( $className, $config ) {
		include dirname(dirname(__FILE__)) . '/lib/' . $className . '.class.php';
	
		return new $className ( $config );
	}
	
	/**
	 * 整理返回值
	 *
	 * @param bool $state
	 * @param string $msg
	 * @param string $data
	 */
	public function _return($state, $msg, $data) {
		return array (
				'return' => json_encode ( array (
						'state' => $state,
						'msg' => $msg,
						'data' => $data
				) )
		);
	}

	/**
	 * 加密
	 *
	 * @param string $encrypt
	 * @param string $key
	 * @param string $iv
	 */
	public function _encrypt($encrypt, $key = "", $iv = "") {
		$des = new DES ( $key, $iv );
		return $des->encrypt ( $encrypt );
	}
	/**
	 * 解密
	 *
	 * @param string $decrypt
	 * @param string $key
	 * @param string $iv
	 */
	public function _decrypt($decrypt, $key = "", $iv = "") {
		$des = new DES ( $key, $iv );
		return $des->decrypt ( $decrypt );
	}
	/**
	 * addslashes() 别名函数,加强对数组类型(array)的数据处理
	 * 该函数并添加了对MSSQL 的转义字符异常的支持,但前提是SQL 的分界符为’ 即单引号
	 *
	 * @param
	 *        	string | array $string
	 * @param boolean $force
	 *        	是否强制转换转义字符
	 * @return string | array
	 */
	public function _addslashes($string, $force = 0) {
		$fun = new Fun(Null);
		return $fun->_addslashes ( $string, $force );
	}
	/**
	 * 取功能函数
	 */
	public function fun(){
		return new Fun ( null );
	}
	/**
	 * 随机明文 md5 16位
	 */
	public function _getRandom(){
		return ComFun::getRandom();
	}
	
	/**
	 * 列表默认页码
	 */
	function getListPage($page){
		if($page){
			return $this->_addslashes($page);
		}else{
			return 1;
		}
	}
	
	/**
	 * 列表默认行数
	 */
	function getListPageSize($pageSize){
		if($pageSize){
			return $this->_addslashes($pageSize);
		}else{
			return 10;
		}
	}
}
?>