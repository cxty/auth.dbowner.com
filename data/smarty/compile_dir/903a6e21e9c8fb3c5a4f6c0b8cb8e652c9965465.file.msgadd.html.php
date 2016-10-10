<?php /* Smarty version Smarty-3.0.8, created on 2015-08-14 14:46:31
         compiled from "./templates/main/msgadd.html" */ ?>
<?php /*%%SmartyHeaderCode:58556335855cd8ec70c51e3-77118348%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '903a6e21e9c8fb3c5a4f6c0b8cb8e652c9965465' => 
    array (
      0 => './templates/main/msgadd.html',
      1 => 1430127786,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '58556335855cd8ec70c51e3-77118348',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/ckeditor.js"></script>

<div class="msg_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['WriteMsg'];?>
</div>
<div class="msg_content">
	<form id="msgForm" name="msgForm" method="POST" action="/main/saveMsg">
	
		<ul>
			<?php if ($_smarty_tpl->getVariable('msg')->value){?><li><dl>&nbsp;</dl><dt style="color:red"><?php echo $_smarty_tpl->getVariable('msg')->value;?>
</dt></li><?php }?>
			<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['Accepter'];?>
:</dl><dt><input type="text" name="accepter" value="<?php echo $_POST['accepter'];?>
" /></dt></li>
			<li ><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['Text'];?>
:</dl><dt><textarea name="uContent" style="width:545px;height:250px;"><?php echo $_POST['uContent'];?>
</textarea></dt></li>
			<li><dl>&nbsp;</dl>
				<dr>
					<input type="submit" name="but" id="sub" class="mar_rigth_55" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['Submint'];?>
" />					
				</dr>
			</li>		
		</ul>	
	</form>	
</div>


<script language="javascript" type="text/javascript">
var ckeditor = new Tckeditor();
ckeditor.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	ckeditor.init();
	ckeditor.edit();
});
//释放
$(window).unload(function(){
	ckeditor = null;
});
</script>