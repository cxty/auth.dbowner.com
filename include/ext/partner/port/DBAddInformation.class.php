<?php
/**
 * 用户基本信息
 *
 * @author wbqing405@sina.com
 */

include(dirname(__FILE__). '/DBBaseService.class.php');

class DBAddInformation extends DBBaseService {
	
	public function __construct ($partner, $provider, $OAuthArr) {
		parent::__construct($partner, $provider, $OAuthArr);
	}
	
	/**
	 * 总接口-发布信息
	 */
	public function addInformation ( $fieldArr ) {
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
	 * 新浪微博信息发布
	 */
	protected function getSina ( $fieldArr ) {
		$Sina = parent::getSina();
		
		$tArr['status'] = $fieldArr['content'];
	
		$_rb = $Sina->addInformation( $tArr );
		
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
		
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 豆瓣用户信息发布
	 */
	protected function getDouban ( $fieldArr ) {
		$Douban = parent::getDouban();
	
		$tArr['text'] = $fieldArr['content'];
	
		$_rb = $Douban->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * QQ信息发布
	 */
	protected function getQQ ( $fieldArr ) {
		$qq = parent::getQQ();
	
		$tArr['content'] = $fieldArr['content'];
	
		$_rb = $qq->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || $_rb['ret'] != 0 ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 人人网信息发布
	 */
	protected function getRenren ( $fieldArr ) {
		$Renren = parent::getRenren();
	
		$tArr['content'] = $fieldArr['content'];
	
		$_rb = $Renren->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 开心网信息发布
	 */
	protected function getKaixin ( $fieldArr ) {
		$Kaixin = parent::getKaixin();
	
		$tArr['content'] = $fieldArr['content'];
	
		$_rb = $Kaixin->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 天涯信息发布
	 */
	protected function getTianya ( $fieldArr ) {
		$Tianya = parent::getTianya();
	
		$tArr['content'] = $fieldArr['content'];
	
		$_rb = $Tianya->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || $_rb['result'] == 0 ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 搜狐信息发布
	 */
	protected function getSohu ( $fieldArr ) {
		$Sohu = parent::getSohu();
	
		$tArr['status'] = $fieldArr['content'];
	
		$_rb = $Sohu->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 网易信息发布
	 */
	protected function getWangyi ( $fieldArr ) {
		$Wangyi = parent::getWangyi();
	
		$tArr['status'] = $fieldArr['content'];
	
		$_rb = $Wangyi->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 百度信息发布
	 */
	protected function getBaidu ( $fieldArr ) {
		$Baidu = parent::getBaidu();
	
		$tArr['status'] = $fieldArr['content'];
	
		$_rb = $Baidu->addInformation( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
	
	/**
	 * 点点信息发布
	 */
	protected function getDiandian ( $fieldArr ) {
		$Diandian = parent::getDiandian();
	
		$tArr['body'] = $fieldArr['content'];
	
		$_rb = $Diandian->addInformation( $tArr );
	
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
	
	/**
	 * Linkedin信息发布
	 */
	protected function getLinkedin ( $fieldArr ) {
		$Linkedin = parent::getLinkedin();
	
		$tArr['content'] = $fieldArr['content'];
	
		$_rb = $Linkedin->addInformation( $tArr );
	ComFun::pr($_rb);
		if ( isset($_rb['dbowner_error']) || isset($_rb['errorCode']) ) {
			$_state = false;
		}else{
			$_state = true;
		}
	
		return array('state' => $_state, 'data' => $_rb);
	}
}