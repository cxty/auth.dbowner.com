<?php /* Smarty version Smarty-3.0.8, created on 2015-10-16 15:23:48
         compiled from "./templates/index/inviteCode.html" */ ?>
<?php /*%%SmartyHeaderCode:2275982345620a604239c02-36758798%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e382224a5a7effbe194649f71d282ec32ab47406' => 
    array (
      0 => './templates/index/inviteCode.html',
      1 => 1441184128,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2275982345620a604239c02-36758798',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB_checkInCodeBox.js"></script>

<div id="login_box">
    
</div>

<script language="javascript">
//document.domain = "www.dbowner.com";
checkInCodeBox = new TcheckInCodeBox();
checkInCodeBox.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
checkInCodeBox.furl = '<?php echo $_smarty_tpl->getVariable('furl')->value;?>
';
checkInCodeBox.host = '<?php echo $_smarty_tpl->getVariable('host')->value;?>
';
checkInCodeBox.recall = '<?php echo $_smarty_tpl->getVariable('recall')->value;?>
';
//页面完全再入后初始化
$(document).ready(function(){
	checkInCodeBox.init();
});
//释放
$(window).unload(function(){
	checkInCodeBox = null;
});
</script>