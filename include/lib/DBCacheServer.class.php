<?php
/**
 * 缓存服务
 */
class DBCacheServer {
	
	private $host; //缓存服务器地址
	private $time; //缓存时间
	private $iswork; //缓存时间
	
	public function __construct ( $config ) {
		$this->config = $config;
	
		$this->host   = $this->config['MEM_SERVER_HOST'];
		$this->time   = $this->config['MEM_SERVER_EXPIRE'];
		$this->iswork = $this->config['MEM_SERVER_WORK'];
		if ( !$this->iswork ) {
			$this->iswork = false;
		}
	}
	
	/**
	 * 返回数据处理
	 */
	private function _return ( $state=true, $msg='OK', $data=array() ) {
		return array(
				'state' => $state,
				'msg'   => $msg,
				'data'  => $data
		);
	}
	
	/**
	 * 设置缓存；不管键值存在不存在，都会设置缓存
	 * key：键值，value：值，time：时间
	 */
	public function set ( $key, $value, $time='' ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'set', 'POST', array(
					'key' => $key,
					'value' => is_array($value) ? json_encode($value) : $value,
					'time' => $time ? $time : (time() + $this->time)
			));
		} else {
			return $this->_return(false, 'Cache is close', $value);
		}
	}
	
	/**
	 * 设置缓存；只有键值不存在时才会设置缓存
	 * key：键值，value：值，time：时间
	 */
	public function add ( $key, $value, $time='' ) {
		if ( $this->iswork === true ) { 
			return DBCurl::dbGet($this->host . 'add', 'POST', array(
					'key' => $key,
					'value' => is_array($value) ? json_encode($value) : $value,
					'time' => $time ? $time : (time() + $this->time)
			));
		} else {
			return $this->_return(false, 'Cache is close', $value);
		}
	}
	
	/**
	 * 设置缓存；用来替换现有键的值
	 * key：键值，value：值，time：时间
	 */
	public function replace ( $key, $value, $time='' ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'replace', 'POST', array(
					'key' => $key,
					'value' => is_array($value) ? json_encode($value) : $value,
					'time' => $time ? $time : (time() + $this->time)
			));
		} else {
			return $this->_return(false, 'Cache is close', $value);
		}
	}
	
	/**
	 * 设置缓存；在现有的缓存数据后添加缓存数据
	 * key：键值，value：值
	 */
	public function append ( $key, $value ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'append', 'POST', array(
					'key' => $key,
					'value' => is_array($value) ? json_encode($value) : $value
			));
		} else {
			return $this->_return(false, 'Cache is close', $value);
		}
	}
	
	/**
	 * 设置缓存；在现有的缓存数据前添加缓存数据
	 * key：键值，value：值
	 */
	public function preprend ( $key, $value, $time='' ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'preprend', 'POST', array(
					'key' => $key,
					'value' => is_array($value) ? json_encode($value) : $value
			));
		} else {
			return $this->_return(false, 'Cache is close', $value);
		}
	}
	
	/**
	 * 设置缓存；只有当最后一个参数和gets所获取的参数匹配时才能存储
	 * key：键值，value：值，time：时间
	 */
	public function cas ( $key, $value, $time='' ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'cas', 'POST', array(
					'key' => $key,
					'value' => is_array($value) ? json_encode($value) : $value,
					'time' => $time ? $time : (time() + $this->time)
			));
		} else {
			return $this->_return(false, 'Cache is close', $value);
		}
	}
	
	/**
	 * 设置缓存；设置一个新的缓存时间
	 * key：键值，time：时间
	 */
	public function touch ( $key, $time='' ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'touch', 'POST', array(
					'key' => $key,
					'time' => $time ? $time : (time() + $this->time)
			));
		} else {
			return $this->_return(false, 'Cache is close', '');
		}
	}
	
	/**
	 * 读取缓存
	 * key：键值
	 */
	public function get ( $key ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'get', 'POST', array(
					'key' => $key
			));
		} else {
			return $this->_return(false, 'Cache is close', '');
		}
	}
	
	/**
	 * 读取缓存；gets命令比普通的get命令多返回了一个数字，当key对应的数据改变时，这个多返回的数字也会改变
	 * key：键值
	 */
	public function gets ( $key ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'gets', 'POST', array(
					'key' => $key
			));
		} else {
			return $this->_return(false, 'Cache is close', '');
		}
	}
	
	/**
	 * 读取缓存；返回多个键对应的值
	 * key：键值（为json数组）
	 */
	public function getMulti ( $key ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'getmulti', 'POST', array(
					'key' => $key
			));
		} else {
			return $this->_return(false, 'Cache is close', '');
		}
	}
	
	/**
	 * 删除缓存
	 * key：键值
	 */
	public function del ( $key ) {
		if ( $this->iswork === true ) {
			return DBCurl::dbGet($this->host . 'del', 'POST', array(
					'key' => $key
			));
		} else {
			return $this->_return(false, 'Cache is close', '');
		}
	}
}