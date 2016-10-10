<?php
/**
 * 应用评论处理类
 *
 * @author wbqing405@sina.com
 */
include_once('Addslashes.class.php'); //数据过滤类
class AppScore{	
	
	var $tbUserInfo = 'tbUserInfo'; //用户信息表
	var $tbUserHeadInfo = 'tbUserHeadInfo'; //用户头像信息
	var $tbAppScoreInfo = 'tbAppScoreInfo'; //应用评分信息
	
	public function __construct($model){
		$this->model = $model;
		
		$this->init();
	}
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	/**
	 * 添加评论
	 */
	public function addAppScore($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$data['AppInfoID']   = $fieldArr['client_id'];
		$data['UserKeyID']   = $fieldArr['UserID'];
		$data['aStar']       = $fieldArr['score'] == 0 ? 1 : $fieldArr['score'];
		$data['aComment']    = $fieldArr['comment'];
		$data['aAppendTime'] = time();
		$data['aState']      = 2;

		try{
			return $this->model->table($this->tbAppScoreInfo)->data($data)->insert();
		}catch(Exception $e){
			return -1;
		}
	}
	/**
	 * 取评论列表
	 */
	public function getAppScoreList($fieldArr, $pagesize=10, $page=0, $order=''){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$pagesize = $pagesize ? $pagesize : 10;
		$page = $page ? $page : 0;
		$limit_start = $page * $pagesize;
		$limit = $limit_start . ',' . $pagesize;

		$where = ' where a.AppInfoID = \''.$fieldArr['client_id'].'\'';
		$condition = 'AppInfoID = \''.$fieldArr['client_id'].'\'';
		if($fieldArr['UserID']){
			$where .= ' and (a.aState = 0 or a.UserKeyID = \''.$fieldArr['UserID'].'\')';
			$condition .= ' and (UserKeyID = \''.$fieldArr['UserID'].'\' or aState = 0)';		
		}else{
			$where .= ' and a.aState = 0';
			$condition .= ' and  aState = 0';					
		}		
		
		if($order){
			$order = ' order by '.$order;
		}else{
			$order = '';
		}

		$count = $this->model->table ( $this->tbAppScoreInfo, false )->field ( 'AppScoreID' )->where ( $condition )->count ();
		
		$field = 'b.uName,c.uhURL,a.aComment,a.aStar,a.aAppendTime,a.aState';
		$sql = 'select '.$field.' from '.$this->tbAppScoreInfo.' as a left join '.$this->tbUserInfo.' as b on a.UserKeyID = b.UserID left join '.$this->tbUserHeadInfo.' c on a.UserKeyID = c.UserID '.$where.$order.' limit '.$limit;

		try {
			$list = $this->model->query($sql);
			
			return array (
					'count' => $count,
					'list' => $list
			);
		} catch ( Exception $e ) {
			return array (
					'count' => 0,
					'list' => null
			);
		}
	}
	/**
	 * 取列表星星情况
	 */
	public function GetStarList($fieldArr){
		$re = array (
				'AllStars'=> array(0,0),
				'FiveStars' => array(0,0),
				'FourStars' => array(0,0),
				'ThreeStars' => array(0,0),
				'TwoStars' => array(0,0),
				'OneStars' => array(0,0),
		);
		
		try{		
			$where = ' where AppInfoID= \''.$fieldArr['client_id'].'\'';
			if($fieldArr['UserID']){
				$where .= ' and (UserKeyID = \''.$fieldArr['UserID'].'\' or aState = 0)';
			}else{
				$where .= ' and aState = 0';
			}
			$Stars = $this->model->query('select
					(select IFNULL(sum(aStar),0) from '.$this->tbAppScoreInfo.$where.' ) as AllStars,
					(select count(0) from '.$this->tbAppScoreInfo.$where.'  and aStar=5) as FiveStars,
					(select count(0) from '.$this->tbAppScoreInfo.$where.'  and aStar=4) as FourStars,
					(select count(0) from '.$this->tbAppScoreInfo.$where.'  and aStar=3) as ThreeStars,
					(select count(0) from '.$this->tbAppScoreInfo.$where.'  and aStar=2) as TwoStars,
					(select count(0) from '.$this->tbAppScoreInfo.$where.'  and aStar=1) as OneStars');
			if($Stars){
				if((int)$Stars[0]['AllStars']!=0){
					return array (
							'AllStars'=>array($Stars[0]['FiveStars']+$Stars[0]['FourStars']+$Stars[0]['ThreeStars']+$Stars[0]['TwoStars']+$Stars[0]['OneStars'],$Stars[0]['AllStars']),
							'FiveStars' => array($Stars[0]['FiveStars'],(int)((int)$Stars[0]['FiveStars']*5/(int)$Stars[0]['AllStars']*100)),
							'FourStars' => array($Stars[0]['FourStars'],(int)((int)$Stars[0]['FourStars']*4/(int)$Stars[0]['AllStars']*100)),
							'ThreeStars' => array($Stars[0]['ThreeStars'],(int)((int)$Stars[0]['ThreeStars']*3/(int)$Stars[0]['AllStars']*100)),
							'TwoStars' => array($Stars[0]['TwoStars'],(int)((int)$Stars[0]['TwoStars']*2/(int)$Stars[0]['AllStars']*100)),
							'OneStars' => array($Stars[0]['OneStars'],(int)((int)$Stars[0]['OneStars']*1/(int)$Stars[0]['AllStars']*100)),
					);
				}else{
					return $re;
				}
			}else{
				return $re;
			}
				
		} catch ( Exception $e ) {
			return $re;
		}
	}
	/**
	 * 取总的星星情况
	 */
	public function getAllStarAnal($fieldArr){
		$re = array(
				'StarSum' => 0,
				'StarCount' => 0
		);
		try{
			$where = ' where AppInfoID= \''.$fieldArr['client_id'].'\'';
			if($fieldArr['UserID']){
				$where .= ' and (UserKeyID = \''.$fieldArr['UserID'].'\' or aState = 0)';
			}else{
				$where .= ' and aState = 0';
			}
			$Stars = $this->model->query('select
					(select IFNULL(sum(aStar),0) from '.$this->tbAppScoreInfo.$where.' ) as StarSum,
					(select count(0) from '.$this->tbAppScoreInfo.$where.') as StarCount');

			if($Stars){
				if((int)$Stars[0]['StarSum']!=0){
					return array (
							'StarSum'=> $Stars[0]['StarSum'],
							'StarCount' => $Stars[0]['StarCount']
					);
				}else{
					return $re;
				}
			}else{
				return $re;
			}
		}catch(Exception $e){
			return $re;
		}
	}
	/**
	 * 用户是否已经评论过
	 */
	public function IsExistRecord($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$condition['AppInfoID'] = $fieldArr['client_id'];
		$condition['UserKeyID'] = $fieldArr['UserID'];
		
		try{
			return $this->model->table($this->tbAppScoreInfo)->where($condition)->select();
		}catch(Exception $e){
			return null;
		}
	}
}