<?php
/**
 * 文件上传读取操作类
 * @author Cxty
 *
 */
class fileMod extends commonMod {
	
	public function __construct() {
		parent::__construct ();
	
	}
	
	public function index() {
		$this->display ();
	}
	/**
	 * 上传文件 socket方式
	 */
	public function Up_socket() {
 		//ComFun::pr($_FILES);//exit;
		
		$SERVER_URL = $this->config ['FILE_SERVER_UP'];

		if (! empty ( $_FILES )) {
			
			$files = array ();
			
			foreach ( $_FILES as $name => $file ) {
				if ($file ["error"] > 0) {
					
					echo json_encode ( array (
							'state' => false,
							'error' => $file ["error"] 
					) );
				
				} else {
					$files = $file; // 表单对象
					
					$file_md5 = md5_file ( $files ['tmp_name'] ); // 文件的md5值
					$filename = base64_encode ( $files ['name'] );
					
					$SERVER_URL = $SERVER_URL . (strrpos ( $SERVER_URL, '?' ) > 0 ? '&' : '?') . 'filename=' . rawurlencode ( $filename ) . '&filemd5=' . $file_md5;
					
					$Server = parse_url ( $SERVER_URL );
					$host = $Server ['host'];
					$port = empty ( $Server ['port'] ) ? 80 : ( int ) $Server ['port'];
					
					if(function_exists('fsockopen')){
						$fp = @fsockopen ( $host, $port, $errno, $errstr, 30 );
					}else{
						$fp = @pfsockopen ( $host, $port, $errno, $errstr, 30 );
					}						
					
					srand ( ( double ) microtime () * 1000000 );
					$boundary = "---------------------------" . substr ( md5 ( rand ( 0, 32000 ) ), 0, 10 );
					$http_header = ""; // http协议信息头
					$http_header .= "POST $SERVER_URL  HTTP/1.0\r\n";
					$http_header .= "Host: $host\r\n";
					$http_header .= "Accept-Language: zh-cn,zh;q=0.5\r\n";
					$http_header .= "Accept-Encoding: deflate\r\n";
					$http_header .= "Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n";
					$http_header .= "Content-type: multipart/form-data,boundary=$boundary\r\n";
					
					$data .= "--$boundary\r\n";
					
					$new_file_name = $files ['name']; // 存储原文件名
					
					$content_file = join ( "", file ( $files ['tmp_name'] ) );
					$data .= "Content-Disposition:form-data;name=\"uploadfile\";filename=\"$new_file_name\" \r\n";
					$data .= "Content-Type: {$files['type']}\r\n\r\n";
					$data .= "$content_file\r\n";
					$data .= "--$boundary--\r\n";
					
					$http_header .= "Content-length: " . strlen ( $data ) . "\n";
					$http_header .= "Connection: close\n\n";
					$http_header .= "$data\n";
	
					fputs ( $fp, $http_header );
					
					stream_set_timeout ( $fp, 2000 );
	
					$resp = '';
					$start = microtime ( true );
					$len = - 1;
					
					while ( ($line = trim ( fgets ( $fp ) )) != "" ) {
						$header .= $line;
						if (strstr ( $line, "Content-Length:" )) {
							list ( $cl, $len ) = explode ( " ", $line );
						}
					}
					if ($len > 0) {
						$body = fread ( $fp, $len );
						if ($body) {
							$response_info = json_decode ( $body, true );
							
							$urlPic = $response_info ['data'] ['filecode'];
							
							include_once (dirname ( dirname ( __FILE__ ) ) . '/include/lib/ModifyProfile.class.php');
							$modifyProfile = new ModifyProfile ( $this->model );
							$modifyProfile->savePortrait ( $urlPic );
						}
					
					}
					
					fclose ( $fp );
					unlink ( $files ['tmp_name'] );
					
					echo $body;
					
					/*
					 * fputs ( $fp, $http_header ); $response_info = ""; while (
					 * ! feof ( $fp ) ) { $response_info .= fgets ( $fp, 32000
					 * ); } fclose ( $fp ); if (strpos ( $response_info, '200' )
					 * !== false) { $response_info =
					 * substr($response_info,stripos($response_info,'{"state":'));
					 * if($response_info){ $response_info =
					 * json_decode($response_info,true); $urlPic =
					 * $response_info['data']['filecode'];
					 * include_once(dirname(dirname(__FILE__)).'/include/lib/ModifyProfile.class.php');
					 * $modifyProfile = new ModifyProfile($this->model);
					 * $modifyProfile->savePortrait($urlPic);
					 * $this->redirect('/main/index'); } //echo
					 * json_encode(array('state'=>true,'msg'=>$response_info));
					 * }
					 */
				}
			}
		} else {
			echo json_encode ( array (
					'state' => false,
					'error' => 'Nothing file data!' 
			) );
		}
	}
	/**
	 * 上传文件 curl方式
	 */
	public function Up(){
		//header("Content-type: text/html; charset=utf-8");
		//ComFun::pr($_FILES);//exit;
		if (! empty ( $_FILES )) {
			
			$files = array ();
				
			foreach ( $_FILES as $name => $file ) {
				if ($file ["error"] > 0) {
						
					echo json_encode ( array (
							'state' => false,
							'error' => $file ["error"]
					) );
		
				} else {						
					$files = $file; // 表单对象		
						
				    $SERVER_URL = $this->config ['FILE_SERVER_UP'];
				    
					$fields['uploadfile'] = '@'.$files ['tmp_name'];
					$fields['filetype']   = $files['type'];
					$fields['filename']   = base64_encode ( $files ['name'] );// 存储原文件名		
					$fields['filemd5']    = md5_file ( $files ['tmp_name'] ); // 文件的md5值

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $SERVER_URL );
					curl_setopt($ch, CURLOPT_POST, 1 );
					curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );
					
					ob_start();
					curl_exec($ch);
					$body = ob_get_contents();
					ob_end_clean();
					curl_close($ch);
					
					if( $_GET[0] ){
						switch( strtolower($_GET[0]) ){
							case 'protrait':
								if( $body ){
									$response_info =  json_decode($body, true);
								
									$urlPic = $response_info ['data'] ['filecode'];
										
									include_once (dirname ( dirname ( __FILE__ ) ) . '/include/lib/ModifyProfile.class.php');
									$modifyProfile = new ModifyProfile ( $this->model );
									$modifyProfile->savePortrait ( $urlPic );
								}
								break;
						}
					}

					echo $body;	
				}
			}
		} else {
			echo json_encode ( array (
					'state' => false,
					'error' => 'Nothing file data!'
			) );
		}		
	}
	/**
	 * 读取
	 */
	public function Get() {
		$filecode = $_GET ['filecode'];
	}
	
	public function b_fsockopen($host, $port, &$errno, &$errstr, $timeout) {
		$ip = gethostbyname ( $host );
		$s = socket_create ( AF_INET, SOCK_STREAM, 0 );
		if (socket_set_nonblock ( $s )) {
			$r = @socket_connect ( $s, $ip, $port );
			if ($r || socket_last_error () == EINPROGRESS) {
				$errno = EINPROGRESS;
				return $s;
			}
		}
		$errno = socket_last_error ( $s );
		$errstr = socket_strerror ( $errno );
		socket_close ( $s );
		return false;
	}
}

?>