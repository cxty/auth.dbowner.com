<?php
/**
 * 取好友列表
 *
 * @author wbqing405@sina.com
 */

include(dirname(__FILE__). '/DBBaseService.class.php');

class DBGetFriendList extends DBBaseService {
	
	public function __construct ($partner, $provider, $OAuthArr) {
		parent::__construct($partner, $provider, $OAuthArr);
	}
	
	/**
	 * 总接口-发布信息
	 */
	public function getFriendList ( $fieldArr ) {
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
	 * 获取新浪微博好友列表
	 */
	protected function getSina ( $fieldArr ) {
		$Sina = parent::getSina();
		
		$tArr['uid'] = $fieldArr['uid'] ? $fieldArr['uid'] : $this->OAuthArr['user_id'];
		
		//单页返回的记录条数，默认为20。
		$tArr['count'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
		
		//返回结果的页码，默认为1。
		$tArr['page'] = $fieldArr['page'] ? $fieldArr['page'] : 1;
		
		//排序类型，0：按关注时间最近排序，默认为0。
		if ( $fieldArr['sort'] ) {
			$tArr['sort'] = $fieldArr['sort'];
		}
		
		$_rb = $Sina->getFriendList( $tArr );
		
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
			if ( $_rb['users'] ) {
				foreach ( $_rb['users'] as $_k => $_v ) {
					$_rcb[$_k]['id']          = $_v['id'];
					$_rcb[$_k]['name']        = $_v['screen_name'];
					$_rcb[$_k]['img']         = $_v['avatar_large'];
					$_rcb[$_k]['location']    = $_v['location'];
					$_rcb[$_k]['uri']         = 'http://weibo.com/' . $_v['profile_url'];
					$_rcb[$_k]['description'] = $_v['description'];
				}
			}
		}
		
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取豆瓣好友列表
	 */
	protected function getDouban ( $fieldArr=array() ) {
		$Douban = parent::getDouban();
		
		$tArr['user_id'] = $fieldArr['uid'] ? $fieldArr['uid'] : $this->OAuthArr['user_id'];
	
		$_rb = $Douban->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
			if ( $_rb ) {
				foreach ( $_rb as $_k => $_v ) {
					$_rcb[$_k]['id']          = $_v['id'];
					$_rcb[$_k]['name']        = $_v['screen_name'];
					$_rcb[$_k]['img']         = $_v['large_avatar'];
					$_rcb[$_k]['location']    = $_v['city'];
					$_rcb[$_k]['uri']         = $_v['url'];
					$_rcb[$_k]['description'] = $_v['description'];
				}
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 获取QQ微博好友列表
	 */
	protected function getQQ ( $fieldArr=array() ) {
		$qq = parent::getQQ();
	
		//单页返回的记录条数，默认为20。
		$tArr['reqnum'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 10;
		
		//返回结果的页码，默认为0。
		$tArr['startindex'] = $fieldArr['page'] ? $fieldArr['page'] : 0;
	
		$_rb = $qq->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || $_rb['ret'] != 0 ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
			if ( $_rb['data']['info'] ) {
				foreach ( $_rb['data']['info'] as $_k => $_v ) {
					$_rcb[$_k]['id']          = $_v['openid'];
					$_rcb[$_k]['name']        = $_v['name'];
					$_rcb[$_k]['img']         = $_v['head'] . '/';
					$_rcb[$_k]['location']    = $_v['location'];
					$_rcb[$_k]['uri']         = 'http://t.qq.com/' . $_v['name'];
					$_rcb[$_k]['description'] = '';
				}
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 人人网好友列表
	 */
	protected function getRenren ( $fieldArr ) {
		$Renren = parent::getRenren();
	
		$tArr['userId'] = $fieldArr['uid'] ? $fieldArr['uid'] : $this->OAuthArr['user_id'];
	
		//单页返回的记录条数，默认为20。
		$tArr['pageSize'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
	
		//返回结果的页码，默认为1。
		$tArr['pageNumber'] = $fieldArr['page'] ? $fieldArr['page'] : 1;
	
		//排序类型，0：按关注时间最近排序，默认为0。
		if ( $fieldArr['sort'] ) {
			$tArr['sort'] = $fieldArr['sort'];
		}
	
		$_rb = $Renren->getFriendList( $tArr );

		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
		
			foreach ( $_rb['response'] as $_k => $_v ) {
				$_rcb[$_k]['id']          = $_v['id'];
				$_rcb[$_k]['name']        = $_v['name'];
				$_rcb[$_k]['img']         = $_v['avatar'][2]['url'];
				$_rcb[$_k]['location']    = '';
				$_rcb[$_k]['uri']         = 'http://www.renren.com/' . $_v['id'] . '/profile';;
				$_rcb[$_k]['description'] = $_v['basicInformation'];
			}
		}
		
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 开心好友列表
	 */
	protected function getKaixin ( $fieldArr ) {
		$Kaixin = parent::getKaixin();
	
		$tArr['userId'] = $fieldArr['uid'] ? $fieldArr['uid'] : $this->OAuthArr['user_id'];
	
		//单页返回的记录条数，默认为20。
		$tArr['num'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
	
		//返回结果的页码，默认为0。
		$tArr['start'] = $fieldArr['page'] ? $fieldArr['page'] : 1;
	
		//排序类型，0：按关注时间最近排序，默认为0。
		if ( $fieldArr['sort'] ) {
			$tArr['sort'] = $fieldArr['sort'];
		}
	
		$_rb = $Kaixin->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['error']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
	
			foreach ( $_rb['users'] as $_k => $_v ) {
				$_rcb[$_k]['id']          = $_v['uid'];
				$_rcb[$_k]['name']        = $_v['name'];
				$_rcb[$_k]['img']         = $_v['logo120'];
				$_rcb[$_k]['location']    = $_v['hometown'] . ' ' . $_v['city'];
				$_rcb[$_k]['uri']         = 'http://www.kaixin001.com/home/?_profileuid=' . $_v['uid'];
				$_rcb[$_k]['description'] = $_v['motto'];
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 天涯
	 */
	protected function getTianya ( $fieldArr ) {
		$Tianya = parent::getTianya();
	
		$tArr['userId'] = $fieldArr['uid'] ? $fieldArr['uid'] : $this->OAuthArr['user_id'];
	
		//单页返回的记录条数，默认为20。
		$tArr['pagesize'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
	
		//返回结果的页码，默认为0。
		$tArr['page'] = $fieldArr['page'] ? $fieldArr['page'] : 1;
	
		//排序类型，0：按关注时间最近排序，默认为0。
		if ( $fieldArr['sort'] ) {
			$tArr['sort'] = $fieldArr['sort'];
		}
	
		$_rb = $Tianya->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || $_rb['result'] == 0 ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
	
			foreach ( $_rb['data']['user'] as $_k => $_v ) {
				$_rcb[$_k]['id']          = $_v['id'];
				$_rcb[$_k]['name']        = $_v['name'];
				$_rcb[$_k]['img']         = $_v['headimg'];
				$_rcb[$_k]['location']    = $_v['province'] . ' ' . $_v['city'];
				$_rcb[$_k]['uri']         = 'http://www.tianya.cn/' . $_v['id'];
				$_rcb[$_k]['description'] = $_v['aboutMe'];
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 搜狐
	 */
	protected function getSohu ( $fieldArr ) {
		$Sohu = parent::getSohu();
	
		$tArr['id'] = $fieldArr['uid'] ? $fieldArr['uid'] : $this->OAuthArr['user_id'];
	
		//单页返回的记录条数，默认为20。
		$tArr['count'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
	
		//返回结果的页码，默认为0。
		$tArr['page'] = $fieldArr['page'] ? $fieldArr['page'] : 1;
	
		//排序类型，0：按关注时间最近排序，默认为0。
		if ( $fieldArr['sort'] ) {
			$tArr['sort'] = $fieldArr['sort'];
		}
	
		$_rb = $Sohu->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
	
			foreach ( $_rb as $_k => $_v ) {
				$_rcb[$_k]['id']          = $_v['id'];
				$_rcb[$_k]['name']        = $_v['screen_name'];
				$_rcb[$_k]['img']         = $_v['profile_image_url'];
				$_rcb[$_k]['location']    = $_v['location'];
				$_rcb[$_k]['uri']         = 'http://t.sohu.com/people?uid=' . $_v['id'];
				$_rcb[$_k]['description'] = $_v['description'];
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 网易
	 */
	protected function getWangyi ( $fieldArr ) {
		$Wangyi = parent::getWangyi();
	
		
		if ( $fieldArr['screen_name'] ) {
			$tArr['screen_name'] = $fieldArr['name'];
		} else {
			$tArr['user_id'] = $fieldArr['uid'] ? $fieldArr['uid'] : $this->OAuthArr['user_id'];
		}
	
		//单页返回的记录条数，默认为30。
		$pagesize = ( $fieldArr['pagesize'] && $fieldArr['pagesize'] <= 30 ) ? $pagesize : 30;
		
		//返回结果的页码，默认为0。
		$page = $fieldArr['page'] ? $fieldArr['page'] : 0;
		
		//分页参数，单页只能包含30个关注列表，请求示例，返回60-90。
		$tArr['cursor'] = ($pagesize && $page) ? $pagesize*$page : 0;
	
		$_rb = $Wangyi->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
	
			foreach ( $_rb['users'] as $_k => $_v ) {
				$_rcb[$_k]['id']          = $_v['id'];
				$_rcb[$_k]['name']        = $_v['name'];
				$_rcb[$_k]['img']         = $_v['profile_image_url'];
				$_rcb[$_k]['location']    = $_v['location'];
				$_rcb[$_k]['uri']         = 'http://t.163.com/' . $_v['screen_name'];
				$_rcb[$_k]['description'] = $_v['description'];
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 百度
	 */
	protected function getBaidu ( $fieldArr ) {
		$Baidu = parent::getBaidu();
	
		//单页返回的记录条数，默认为20。
		$tArr['page_size'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
	
		//返回结果的页码，默认为0。
		$tArr['page_no'] = $fieldArr['page'] ? $fieldArr['page'] : 0;
	
		//按照添加时间排序，1：登陆时间排序，默认为0
		if ( $fieldArr['sort'] ) {
			$tArr['sort_type'] = $fieldArr['sort'];
		}
		
		$_rb = $Baidu->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || isset($_rb['code']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
	
			foreach ( $_rb as $_k => $_v ) {
				$_rcb[$_k]['id']          = $_v['uid'];
				$_rcb[$_k]['name']        = $_v['uname'];
				$_rcb[$_k]['img']         = 'http://tb.himg.baidu.com/sys/portrait/item/' . $_v['profile_image_url'];
				$_rcb[$_k]['location']    = $_v['location'];
				$_rcb[$_k]['uri']         = 'http://www.baidu.com/p/' . $_v['uname'];
				$_rcb[$_k]['description'] = '';
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 点点
	 */
	protected function getDiandian ( $fieldArr ) {
		$Diandian = parent::getDiandian();
	
		//单页返回的记录条数，默认为20。
		$tArr['limit'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
	
		//返回结果的页码，默认为0。
		$tArr['offset'] = $fieldArr['page'] ? $fieldArr['page'] : 0;
	
		$_rb = $Diandian->getFriendList( $tArr );
	
		if ( isset($_rb['dbowner_error']) || ($_rb['meta']['status'] && intval($_rb['meta']['status']) != 200) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
	
			foreach ( $_rb['response']['blogs'] as $_k => $_v ) {
				$_rcb[$_k]['id']          = '';
				$_rcb[$_k]['name']        = $_v['name'];
				$_rcb[$_k]['img']         = '';
				$_rcb[$_k]['location']    = $_v['location'];
				$_rcb[$_k]['uri']         = $_v['blogCName'];
				$_rcb[$_k]['description'] = '';
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
	
	/**
	 * 天翼
	 */
	protected function getTianyi ( $fieldArr ) {
		return array('state' => false, 'data' => array('msg' => 'The api had not passed'));
	}
	
	/**
	 * Live
	 */
	protected function getLive ( $fieldArr ) {
		$Live = parent::getLive();
	
		//单页返回的记录条数，默认为20。
		$tArr['limit'] = $fieldArr['pagesize'] ? $fieldArr['pagesize'] : 20;
	
		//返回结果的页码，默认为0。
		$tArr['offset'] = $fieldArr['page'] ? $fieldArr['page'] : 0;
	
		$_rb = $Live->getFriendList( $tArr );
		
		if ( isset($_rb['dbowner_error']) ||  isset($_rb['error']) ) {
			$_state = false;
			$_rcb = $_rb;
		}else{
			$_state = true;
	
			if ( $_rb['data'] ) {
				foreach ( $_rb['data'] as $_k => $_v ) {
					$_rcb[$_k]['id']          = $_v['id'];
					$_rcb[$_k]['name']        = $_v['name'];
					$_rcb[$_k]['img']         = '';
					$_rcb[$_k]['location']    = $_v['location'];
					$_rcb[$_k]['uri']         = $_v['blogCName'];
					$_rcb[$_k]['description'] = '';
				}
			} else {
				$_rcb = array();
			}
		}
	
		return array('state' => $_state, 'data' => $_rcb);
	}
}