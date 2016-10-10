<?php
/**
 * Auth接口请求响应数据记录
 *
 * @author wbqing405@sina.com
 */
class DB_AuthInterfaceLog {
	
	var $tbAuthInterfaceLogInfo = 'tbAuthInterfaceLogInfo'; //请求响应数据记录
	
	
	public function __construct ( $model ) {
		$this->model = $model;
	}
	
	/**
	 * 请求数据记录
	 */
	public function addAuthInterfaceRequestLog ( $fieldArr ) {
		try {
			$_data['ailModuleName']  = $fieldArr['ailModuleName'];
			$_data['ailActionName']  = $fieldArr['ailActionName'];
			$_data['ailRequestData'] = $fieldArr['ailRequestData'];
			$_data['ailRequestTime'] = time();
			
			return $this->model->table($this->tbAuthInterfaceLogInfo)->data($_data)->insert();
		} catch ( Exception $e ) {
			return false;			
		}	
	}
	
	/**
	 * 响应数据记录
	 */
	public function addAuthInterfaceRespondLog ( $fieldArr ) {
		try {
			$_cond['PlugInShareLogID'] = $fieldArr['PlugInShareLogID'];
			
			$_data['pslRespondData'] = $fieldArr['pslRespondData'];
			$_data['pslRespondTime'] = time();
			 
			$this->model->table($this->tbAuthInterfaceLogInfo)->data($_data)->where($_cond)->update();
			
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
}