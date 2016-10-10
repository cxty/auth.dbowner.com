<?php
/**
 * 
 * @author wbqing405@sina.com
 * 
 * 个人中心二维码登录相应处理类
 *
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBQRCodeForPC{
	
	var $tbQRCodeForPCInfo = 'tbQRCodeForPCInfo'; //个人中心二维码处理
	
	public function __construct($model, $config){
		$this->model = $model;
		$this->config = $config;
		
		$this->init();
	}
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	/**
	 * 保存初始化页面生成的session值
	 */
	public function saveSessionValue($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		$condition['cSessionID'] = $fieldArr['cSessionID'];
		
		if($this->model->table($this->tbQRCodeForPCInfo)->field('QRCodeID')->where($condition)->select()){
			$this->_updateSessionValue($fieldArr);
		}else{
			$this->_addSessionValue($fieldArr);
		}
	}
	/**
	 * 增加session记录
	 */
	private function _addSessionValue($fieldArr){
		try{
			$data['cSessionID']  = $fieldArr['cSessionID'];
			$data['cAppendTime'] = time();
			$data['cUpdateTime'] = time();
			
			return $this->model->table($this->tbQRCodeForPCInfo)->data($data)->insert();
		}catch(Exception $e){
			return null;
		}
	}
	/**
	 * 更新session记录
	 */
	private function _updateSessionValue($fieldArr){
		try{			
			$condition['cSessionID'] = $fieldArr['cSessionID'];

			$data['cUpdateTime'] = time();
				
			return $this->model->table($this->tbQRCodeForPCInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return null;
		}
	}
	/**
	 * 删除过期的session值记录
	 */
	public function deleteOverTimeSession(){
		try{
			$re = $this->model->table($this->tbQRCodeForPCInfo)->field('QRCodeID,cUpdateTime')->select();
			
			if($re){
				foreach($re as $key=>$val){
					if($val['cUpdateTime']+$this->config['DB']['QRCode']['OverTime'] < time()){
						$condition['QRCodeID'] = $val['QRCodeID'];
						$this->model->table($this->tbQRCodeForPCInfo)->where($condition)->delete();
					}
				}
			}
		}catch (Exception $e){
			return null;
		}
	}
	/**
	 * 检验sessionid是否过期或存在
	 */
	public function checkSessionValid($cSessionID){
		try{			
			$cSessionID = $this->Addslashes->do_addslashes($cSessionID);
			
			$condition['cSessionID'] = $cSessionID;

			$re = $this->model->table($this->tbQRCodeForPCInfo)->field('QRCodeID,cUpdateTime')->where($condition)->select();
			if($re){
				if($re[0]['cUpdateTime']+$this->config['DB']['QRCode']['OverTime'] > time()){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 检验手机端是否已经登录
	 */
	public function checkPhoneLogin($cSessionID){
		try{
			$cSessionID = $this->Addslashes->do_addslashes($cSessionID);
			
			$condition['cSessionID'] = $cSessionID;
			
			$re = $this->model->table($this->tbQRCodeForPCInfo)->field('cUserID')->where($condition)->select();
			
			if($re){
				if($re[0]['cUserID']){
					return $re[0]['cUserID'];
				}else{
					return null;
				}
			}else{
				return null;
			}
		}catch(Exception $e){
			return null;
		}
	}
	/**
	 * 手机验证更新数据库
	 */
	public function updateCheckResult($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$condition['cSessionID'] = $fieldArr['cSessionID'];
			
			$data['cUserID'] = $fieldArr['cUserID'];
			$data['cUserCode'] = $fieldArr['cUserCode'];
			
			$this->model->table($this->tbQRCodeForPCInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return null;
		}
	}
}