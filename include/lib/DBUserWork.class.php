<?php
/**
 * 用户工作信息
 *
 * @author wbqing405@sina.com
 */


class DBUserWork{
	
	var $tbUserWorkInfo = 'tbUserWorkInfo'; //用户工作信息表
	
	public function __construct($model){
		$this->model = $model;
	}
	/**
	 * 增加用户工作记录
	 */
	public function addUserWorkInfo($fieldArr){
		try{
			$condition['UserID']       = $fieldArr['UserID'];
			$condition['wCompanyName'] = $fieldArr['wCompanyName'];
			$condition['wDepartment']  = $fieldArr['wDepartment'];
			
			if( !$this->model->table($this->tbUserWorkInfo)->field('AutoID')->where($condition)->select() ){
				if( $fieldArr['AutoID'] ){
					unset($condition);
					
					$condition['AutoID'] = $fieldArr['AutoID'];
					
					$data['wCompanyName'] = $fieldArr['wCompanyName'];
					$data['wDepartment']  = $fieldArr['wDepartment'];
					$data['wStartYear']   = $fieldArr['wStartYear'];
					$data['wEndYear']     = $fieldArr['wEndYear'];
					$data['wState']       = $fieldArr['wState'];
					$data['wProvice']     = $fieldArr['wProvice'];
					$data['wCity']        = $fieldArr['wCity'];
					$data['UpdateTime']   = time();
					
					$this->model->table($this->tbUserWorkInfo)->data($data)->where($condition)->update();
				}else{
					$data['UserID']       = $fieldArr['UserID'];
					$data['wCompanyName'] = $fieldArr['wCompanyName'];
					$data['wDepartment']  = $fieldArr['wDepartment'];
					$data['wStartYear']   = $fieldArr['wStartYear'];
					$data['wEndYear']     = $fieldArr['wEndYear'];
					$data['wState']       = $fieldArr['wState'];
					$data['wProvice']     = $fieldArr['wProvice'];
					$data['wCity']        = $fieldArr['wCity'];
					$data['AppendTime']   = time();
					$data['UpdateTime']   = time();
						
					$this->model->table($this->tbUserWorkInfo)->data($data)->insert();
				}
			
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 更新用户工作信息
	 */
	public function updateUserWorkInfo($fieldArr){
		try{
			$where = 'UserID = \''.$fieldArr['UserID'].'\'';
			$where .= ' and wCompanyName = \''.$fieldArr['wCompanyName'].'\'';
			$where .= ' and wDepartment = \''.$fieldArr['wDepartment'].'\'';
			$where .= ' and AutoID != \''.$fieldArr['AutoID'].'\'';
			
			if( !$this->model->table($this->tbUserWorkInfo)->field('AutoID')->where($where)->select() ){
				$condition['AutoID'] = $fieldArr['AutoID'];
				
				$data['wCompanyName'] = $fieldArr['wCompanyName'];
				$data['wDepartment']  = $fieldArr['wDepartment'];
				$data['wStartYear']   = $fieldArr['wStartYear'];
				$data['wEndYear']     = $fieldArr['wEndYear'];
				$data['wState']       = $fieldArr['wState'];
				$data['wProvice']     = $fieldArr['wProvice'];
				$data['wCity']        = $fieldArr['wCity'];
				$data['UpdateTime']   = time();
				
				$this->model->table($this->tbUserWorkInfo)->data($data)->where($condition)->update();
				
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 删除信息
	 */
	public function deleteUserWorkInfo($fieldArr){
		try{
			$condition['AutoID'] = $fieldArr['AutoID'];
			
			$this->model->table($this->tbUserWorkInfo)->where($condition)->delete();
			
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 选出用户 
	 */
	public function selectUserWorkInfo($fieldArr, $order='UpdateTime desc'){
		try{
			$condition['UserID'] =  $fieldArr['UserID'];
			
			$field = 'AutoID,UserID,wCompanyName,wDepartment,wStartYear,wEndYear,wState,wProvice,wCity';
			
			$_re = $this->model->table($this->tbUserWorkInfo)->field($field)->where($condition)->order($order)->select();
			
			if( $_re ){
				foreach($_re as $key=>$val){
					$_re[$key]['State']   = DBState::getStateName($val['wState']);
					$_re[$key]['Provice'] = DBState::getProviceName($val['wProvice']);
					$_re[$key]['City']    = DBState::getCityName($val['wCity']);
				}
				return $_re;
			}else{
				return '';
			}
		}catch(Exception $e){
			return '';
		}
	}
	/**
	 * 选出指定ID的记录
	 */
	public function getUserWorkByID($AutoID){
		try{
			$condition['AutoID'] = $AutoID;
			
			$field = 'AutoID,UserID,wCompanyName,wDepartment,wStartYear,wEndYear,wState,wProvice,wCity';
			
			$_re = $this->model->table($this->tbUserWorkInfo)->field($field)->where($condition)->select();
			
			if($_re){
				$_re[0]['State']   = DBState::getStateName($val['wState']);
				$_re[0]['Provice'] = DBState::getProviceName($val['wProvice']);
				$_re[0]['City']    = DBState::getCityName($val['wCity']);
					
				return $_re[0];
			}else{
				return '';	
			}
		}catch(Exception $e){
			return '';
		}
	}
}