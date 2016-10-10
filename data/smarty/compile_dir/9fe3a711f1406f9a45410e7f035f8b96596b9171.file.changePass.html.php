<?php /* Smarty version Smarty-3.0.8, created on 2015-10-22 16:57:21
         compiled from "./templates/index/changePass.html" */ ?>
<?php /*%%SmartyHeaderCode:3094984395628a4f11cfac7-24588668%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9fe3a711f1406f9a45410e7f035f8b96596b9171' => 
    array (
      0 => './templates/index/changePass.html',
      1 => 1445503602,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3094984395628a4f11cfac7-24588668',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/changePass.js"></script>

<div class="lg_ct">
	<form method="post" id="login_form" action="<?php echo $_smarty_tpl->getVariable('vdata')->value['actUrl'];?>
">
		<div class="lg_cpwd_box">
			<div class="lo_input_box">
				<input type="password" name="oPwd" id="oPwd" />
				<input type="password" name="nPwd" id="nPwd" />
				<input type="password" name="aPwd" id="aPwd" />
			</div>
			<div class="lo_btn_msg"><?php echo $_smarty_tpl->getVariable('vdata')->value['error'];?>
</div>
			<div class="lo_btn_box">
				<div class="lo_btn_sure" id="sub_btn"></div>
			</div>
		</div>
	</form>
</div>

<script>
var jdata = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
</script>

<?php $_template = new Smarty_Internal_Template('footer_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>