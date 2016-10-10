<?php
/**
 * 验证码
 *
 * @author wbqing405@sina.com
 */
class DBCodeInfo {
	
	private $model = ''; //数据库类
	private $config = ''; //配置文件
	
	private $table = 'tbCodeInfo'; //验证码表
	
	public function __construct($model=null,$config=null){
		// Use Beijing Timezone
		date_default_timezone_set ('Etc/GMT-8');
	
		$this->model   = $model;
		$this->config  = $GLOBALS["config"];
	}
	
	/**
	 * 添加记录
	 */
	public function add ( $params ) {
		$_da = 0;
		try {
			$idata['Code']			= $params['Code'];
			$idata['cIsUser']   	= 0;
			$idata['cAppendTime'] 	= time();
			
			return $this->model->table($this->table)->data($idata)->insert();
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 更新记录
	 */
	public function update ( $cond, $data ) {
		$_da = false;
		try {
			$this->model->table($this->table)->data($data)->where($cond)->update();
			
			return true;
		} catch ( Exception $e ) {
			return $_da;	
		}
	}
	
	/**
	 * 更新记录
	 */
	public function get ( $cond ) {
		$_da = array();
		try {
			return $this->model->table($this->table)->where($cond)->find();
		} catch ( Exception $e ) {
			return $_da;
		}
	}
}
?>