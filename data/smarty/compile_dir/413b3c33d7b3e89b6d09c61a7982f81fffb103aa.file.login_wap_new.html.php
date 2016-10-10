<?php /* Smarty version Smarty-3.0.8, created on 2015-05-08 16:04:24
         compiled from "./templates/oauth/login_wap_new.html" */ ?>
<?php /*%%SmartyHeaderCode:1048391221554c6e08509f39-64180107%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '413b3c33d7b3e89b6d09c61a7982f81fffb103aa' => 
    array (
      0 => './templates/oauth/login_wap_new.html',
      1 => 1430127788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1048391221554c6e08509f39-64180107',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_wap_new.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB.loginWap.js"></script>

				<div class="login_right"><a href="/index/register?<?php echo $_smarty_tpl->getVariable('redirect')->value;?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Register'];?>
</a></div>
			</div>
		</aside>
	</header>

<div id="login_form">
<section>
	<div class="line"></div>
	<div class="login_content"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['AppWelcome'];?>
<?php echo $_smarty_tpl->getVariable('theApp')->value;?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Application'];?>
</div>
	
	<div class="login_third" id="login_third">
		<ul >
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('thirdArr')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
				<li><a href="javascript:loginWap.dothirdlogin('<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
')"><dt class="<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
"><?php if (!$_smarty_tpl->tpl_vars['item']->value['icon']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['txt'];?>
<?php }?></dt></a></li>			
			<?php }} ?>
		</ul>
	</div>
	
	<div id="login_common">
		<form method="POST" id="loginform" name="loginform" action="/oauth/login?<?php echo $_smarty_tpl->getVariable('urlStr')->value;?>
">
			<input type="hidden" name="UserID" id="UserID" />
			<input type="hidden" name="display" value="mobile" />
			
			<div class="form_input">
				<div><input type="text" size="20" name="uEmail" id="uEmail" /></div>			
				<div><input type="password" size="20" name="uPWD" id="password" /></div>
			</div>
					
			<div class="form_button">
				<input type="button" id="submit_btn" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Login'];?>
" />
				<input type="button" id="button_btn" class="mar_left" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Cancel'];?>
" />
			</div>
		</form>
	</div>	
</div>
</section>
<script type="text/javascript">
var loginWap = new TloginWap();
loginWap.PH_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
loginWap.jdata = <?php echo $_smarty_tpl->getVariable('jdata')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	loginWap.init();
});
//释放
$(window).unload(function(){
	loginWap = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer_wap_new.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>