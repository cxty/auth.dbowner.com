<?php
/**
 * 调试信息
 * @author 本清
 *
 */
class DBWriteTrace {
	private $filepath = '../../data/trace'; //文件位置
	
	private $file;
	
	private $isTrace = false;
	
	private $cutline = ' ********** '; //分割线
	
	public function __construct ( $config ) {
		$this->config = $config;
		
		//文件存储位置
		if ( $this->config['DBWriteTrace_FilePath'] ) {
			$this->filepath = $this->config['DBWriteTrace_FilePath'];
		} 
		
		//日志文件
		$this->file = $this->filepath . date('Y-m-d') . '.log';
		
		//是否写入调试信息
		if ( $this->config['DBWriteTrace_IsTrace'] ) {
			$this->isTrace = $this->config['DBWriteTrace_IsTrace'];
		}
		
		$this->_init();
	}
	
	/**
	 * 获取IP
	 */
	private function _getIP () {
		if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]){
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}elseif (getenv("HTTP_X_FORWARDED_FOR")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}elseif (getenv("HTTP_CLIENT_IP")){
			$ip = getenv("HTTP_CLIENT_IP");
		}elseif (getenv("REMOTE_ADDR")){
			$ip = getenv("REMOTE_ADDR");
		}else{
			$ip = "Unknown";
		}
	
		return $ip;
	}
	
	/**
	 * 初始化文件
	 */
	private function _init () {
		if ( $this->isTrace === true ) {
			//检查目录是否存在，不存在则创建
			if ( !is_dir($this->filepath) ) {
				if ( @mkdir($this->filepath, 0777) ) {
					if ( is_file($this->filepath) ) {
						$fh = fopen($this->file, 'w'); //打开文件
						fclose($fh); //关闭文件
					}
					
					//文件不可写，则修改权限
					if ( !is_writable($this->file) ) {
						@chmod($this->file, 0777);
					}
				}
			}
		}
	}
	
	/**
	 * 写日志信息
	 */
	public function write ( $value ) {
		if ( $this->isTrace === true ) {
			$value = $this->_getIP() . $this->cutline . date('Y-m-d H:i:s') . $this->cutline . $value . "\r\n";
		
			$stream = fopen($this->file, "a");
			fwrite($stream, $value);
			fclose($stream);
			//file_put_contents($this->file, $value, FILE_APPEND);
		}
	}
}