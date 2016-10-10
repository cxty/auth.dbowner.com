<?php
/**
 * 关注用户、与用户成为好友
 *
 * @author wbqing405@sina.com
 */

include(dirname(__FILE__). '/DBBaseService.class.php');

class DBBeFriend extends DBBaseService {
	
	public function __construct ($partner, $provider, $OAuthArr) {
		parent::__construct($partner, $provider, $OAuthArr);
	}
	
	/**
	 * 总接口-关注用户
	 */
	public function beFriend ( $fieldArr ) {
	//调用父类返回值
		$_base = parent::getThirdParty();
		
		if ( $_base['state'] ) {
			$_rb = $this->$_base['data']['className']( $fieldArr );
			if ( $_rb['state'] ) {
				return $this->_return(true, 'ok',  $_rb['data']);
			} else {
				return $this->_return(false, 'Error returns from DBOwner or Third party',  $_rb['data']);
			}
		} else {
			return $this->_return(false, $_base['msg'], $_base['data'] );
		}
	}
	
	/**
	 * 获取新浪微博关注 
	 */
	protected function getSina ( $fieldArr ) {
		$Sina = parent::getSina();
		
		if ( $fieldArr['uid'] ) {
			$tArr['uid'] = $fieldArr['uid'];
		} else {
			$tArr['screen_name'] = $fieldArr['name'];
		}
	
		$_rb = $Sina->follow( $tArr );
		
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
		
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 豆瓣关注
	 */
	protected function getDouban ( $fieldArr ) {
		$Douban = parent::getDouban();
	
		if ( $fieldArr['uid'] ) {
			$tArr['user_id'] = $fieldArr['uid'];
		}
	
		$_rb = $Douban->follow( $tArr );
	
		if ( isset($_rb['dbowner_error']) || !isset($_rb['code']) ) {
			$_state = true;
		}else{
			$_state = false;
		}
		
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * QQ关注
	 */
	protected function getQQ ( $fieldArr ) {
		$qq = parent::getQQ();
	
		if ( $fieldArr['uid'] ) {
			$tArr['fopenids'] = $fieldArr['uid'];
		} else {
			$tArr['name'] = $fieldArr['name'];
		}
	
		$_rb = $qq->follow( $tArr );
	
		if ( isset($_rb['dbowner_error']) || $_rb['ret'] != 0 ) {
			$_state = true;
		}else{
			$_state = false;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 人人网关注
	 */
	protected function getRenren ( $fieldArr ) {
		return array('state' => false, 'data' => array('msg' => 'Renren has not the api'));
		
		$Renren = parent::getRenren();
	
		
	}
	
	/**
	 * 开心
	 */
	protected function getKaixin ( $fieldArr ) {
		return array('state' => false, 'data' => array('msg' => 'The api had not passed'));
		
		$Kaixin = parent::getKaixin();
	
		$tArr['touid'] = $fieldArr['uid'];
	
		$_rb = $Kaixin->follow( $tArr );
	ComFun::pr($_rb);exit;
		if ( isset($_rb['dbowner_error']) || $_rb['ret'] != 0 ) {
			$_state = true;
		}else{
			$_state = false;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 天涯关注
	 */
	protected function getTianya ( $fieldArr ) {
		$Tianya = parent::getTianya();
	
		if ( $fieldArr['uid'] ) {
			$tArr['id'] = $fieldArr['uid'];
		}
	
		$_rb = $Tianya->follow( $tArr );
		
		if ( isset($_rb['dbowner_error']) || $_rb['ret'] != 0 ) {
			$_state = true;
		}else{
			$_state = false;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 搜狐关注
	 */
	protected function getSohu ( $fieldArr ) {
		$Sohu = parent::getSohu();
	
		if ( $fieldArr['uid'] ) {
			$tArr['id'] = $fieldArr['uid'];
		} else {
			$tArr['nick_name'] = $fieldArr['name'];
		}
	
		$_rb = $Sohu->follow( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 网易关注
	 */
	protected function getWangyi ( $fieldArr ) {
		$Wangyi = parent::getWangyi();
	
		if ( $fieldArr['uid'] ) {
			$tArr['user_id'] = $fieldArr['uid'];
		} else {
			$tArr['screen_name'] = $fieldArr['name'];
		}
		
		$_rb = $Wangyi->follow( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['error_code']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 百度关注
	 */
	protected function getBaidu ( $fieldArr ) {
		$Baidu = parent::getBaidu();
	
		if ( $fieldArr['uid'] ) {
			$tArr['user_id'] = $fieldArr['uid'];
		} else {
			$tArr['screen_name'] = $fieldArr['name'];
		}
	
		$_rb = $Baidu->follow( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['error_code']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 点点关注
	 */
	protected function getDiandian ( $fieldArr ) {
		$Diandian = parent::getDiandian();
	
		$tArr['blogIdentity'] = $fieldArr['uid'];
	
		$_rb = $Diandian->follow( $tArr );
	
		if ( isset($_rb['dbowner_error']) || ($_rb['meta']['status'] && intval($_rb['meta']['status']) != 200) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 天翼信息发布
	 */
	protected function getTianyi ( $fieldArr ) {
		return array('state' => false, 'data' => array('msg' => 'The api had not opened'));
	}
	
	/**
	 * Live信息发布
	 */
	protected function getLive ( $fieldArr ) {
		return array('state' => false, 'data' => array('msg' => 'The api had not opened'));
	}
}