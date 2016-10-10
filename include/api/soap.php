<?php 
//require_once dirname(dirname(dirname(__FILE__))) . '/conf/config.php';
//include_once(dirname(dirname(dirname(__FILE__) . '/conf/config.php')));
include_once('ManageUser.php');
//$server=new SoapServer('http://'.$_SERVER["HTTP_HOST"].'interface/ManageUser.wsdl',array('uri' => "abcd"));
$server=new SoapServer('http://user.dbowner.com/interface/ManageUser.wsdl',array('uri' => "abcd"));
//$server->setClass(new ManageUser($config,$conn));
$server->setClass('ManageUser');
$server->handle();

?>