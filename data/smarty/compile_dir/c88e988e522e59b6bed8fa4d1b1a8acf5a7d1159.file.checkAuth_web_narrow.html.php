<?php /* Smarty version Smarty-3.0.8, created on 2015-11-23 15:28:58
         compiled from "./templates/oauth/checkAuth_web_narrow.html" */ ?>
<?php /*%%SmartyHeaderCode:1773317475652c03a0e3009-39421958%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c88e988e522e59b6bed8fa4d1b1a8acf5a7d1159' => 
    array (
      0 => './templates/oauth/checkAuth_web_narrow.html',
      1 => 1430127788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1773317475652c03a0e3009-39421958',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_auth.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script src=" http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=3168919025" type="text/javascript" charset="utf-8"></script>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/checkAuth.js"></script>

	<?php if (!$_smarty_tpl->getVariable('vdata')->value['showLogo']){?>
		</div>
	<?php }?>
	</div>	
	
<form method="POST" id="loginform" name="loginform" action="/oauth/saveAuth?<?php echo $_smarty_tpl->getVariable('urlStr')->value;?>
">
	<input type="hidden" name="limit" id="limit" value="" />
	<input type="hidden" name="type" id="type" value="" />
	
	<div class="auth_left" style="display:none;">
		<div class="auth_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['OAuthMsg1'];?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['OAuthMsg2'];?>
：</div>
		
		<ul id="checkbox">
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('permArr')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
				<li <?php if ($_smarty_tpl->tpl_vars['key']->value>3){?>style="display:none;"<?php }?>><div>⊙ <?php echo $_smarty_tpl->tpl_vars['item']->value['permInfo'];?>
</div><span><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['permName'];?>
" <?php if ($_smarty_tpl->tpl_vars['item']->value['isDefault']){?>checked="checked"<?php }?> <?php if (!$_smarty_tpl->tpl_vars['item']->value['isDisable']){?>disabled="true"<?php }?> /></span></li>
			<?php }} ?>
		</ul>
		
		<div class="auth_check_more" style="display:none;"><a id="clickMore" href="javascript:void(0);"><?php echo $_smarty_tpl->getVariable('Lang')->value['MoreLimit'];?>
<span class="hand_show"></span></a></div>
		
	</div>
	
	<div class="auth_narrow_right">
	
		<div class="narrow_top" style="display:none;">
			<img src="<?php echo $_smarty_tpl->getVariable('pic')->value;?>
" />
			<div class="narrow_exp">
				<p><?php echo $_smarty_tpl->getVariable('theApp')->value;?>
</p>
				<p style="display:none;"><?php echo $_smarty_tpl->getVariable('userDev')->value;?>
</p>
				<p><?php echo $_smarty_tpl->getVariable('userDev')->value;?>
</p>
				<p><?php echo $_smarty_tpl->getVariable('Lang')->value['AppTotal'];?>
<?php echo $_smarty_tpl->getVariable('count')->value;?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['AppMsg'];?>
</p>
			</div>
		</div>
		
		<div class="narrow_content">
			<div class="narrow_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['OAuthMsg1'];?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['OAuthMsg2'];?>
：</div>
			<ul id="checkbox" class="narrow_auth_box">
				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('permArr')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
					<li <?php if ($_smarty_tpl->tpl_vars['key']->value>3){?>style="display:none;"<?php }?>><div class="narrow_word">⊙ <?php echo $_smarty_tpl->tpl_vars['item']->value['permInfo'];?>
</div><div class="narrow_check"><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['permName'];?>
" <?php if ($_smarty_tpl->tpl_vars['item']->value['isDefault']){?>checked="checked"<?php }?> <?php if (!$_smarty_tpl->tpl_vars['item']->value['isDisable']){?>disabled="true"<?php }?> /></div></li>
				<?php }} ?>
			</ul>
		</div>
		
		<div class="line"></div>
		
		<div class="auth_info" style="display:none;"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['AppInfo'];?>
</div>
		
		<div class="form_button">
			<input type="button" id="submit_btn" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['OAuthBtn'];?>
" />
			<input type="button" id="button_btn" class="mar_left" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Cancel'];?>
" />
		</div>		
	</div>
</form>

<script type="text/javascript">
var checkAuth = new TcheckAuth();
checkAuth.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
checkAuth.sData = <?php echo $_smarty_tpl->getVariable('sData')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	checkAuth.init();
});
//释放
$(window).unload(function(){
	checkAuth = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer_auth.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>