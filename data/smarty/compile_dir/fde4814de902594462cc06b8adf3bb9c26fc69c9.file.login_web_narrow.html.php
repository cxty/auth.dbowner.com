<?php /* Smarty version Smarty-3.0.8, created on 2015-05-08 14:32:45
         compiled from "./templates/oauth/login_web_narrow.html" */ ?>
<?php /*%%SmartyHeaderCode:906253648554c588d8a6862-26123469%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fde4814de902594462cc06b8adf3bb9c26fc69c9' => 
    array (
      0 => './templates/oauth/login_web_narrow.html',
      1 => 1430127789,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '906253648554c588d8a6862-26123469',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_auth.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/loginform_auth.js"></script>

	<?php if (!$_smarty_tpl->getVariable('vdata')->value['showLogo']){?>
			<div class="login_right"><a href="/index/register?<?php echo $_smarty_tpl->getVariable('redirect')->value;?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['Register'];?>
</a></div>
		</div>
	<?php }?>
	</div>	

<div class="auth_narrow_right">

	<div class="auth_space" style="display:none;"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['AppWelcome'];?>
<?php echo $_smarty_tpl->getVariable('theApp')->value;?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Application'];?>
</div>
	
	<?php if ($_smarty_tpl->getVariable('vdata')->value['sys']==true){?>
		<div class="narrow_top">
			<img src="<?php echo $_smarty_tpl->getVariable('pic')->value;?>
" />
			<div class="narrow_exp">
				<p><?php echo $_smarty_tpl->getVariable('theApp')->value;?>
</p>
				<p><?php echo $_smarty_tpl->getVariable('userDev')->value;?>
</p>
				<p><?php echo $_smarty_tpl->getVariable('Lang')->value['AppTotal'];?>
<?php echo $_smarty_tpl->getVariable('count')->value;?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['AppMsg'];?>
</p>
			</div>
		</div>
	<?php }else{ ?>
		<div class="narrow_top_self">
			<?php if ($_smarty_tpl->getVariable('vdata')->value['wordShow']){?>
				<div class="narrow_top_self_exp"><img src="<?php echo $_smarty_tpl->getVariable('pic')->value;?>
" /><?php echo $_smarty_tpl->getVariable('theApp')->value;?>
</div>
			<?php }?>
		</div>
	<?php }?>
	
	<div class="auth_login">
		<form method="POST" id="loginform" name="loginform" action="/oauth/login?<?php echo $_smarty_tpl->getVariable('urlStr')->value;?>
">
			<input type="hidden" name="UserID" id="UserID" />
			<input type="hidden" name="display" value="web" />
			
			<div class="form_input">
				<div class="form_input_wrap"><input type="text" name="uEmail" id="uEmail" autocomplete="off" /></div>			
				<div class="form_input_wrap"><input type="password" name="uPWD" id="password" autocomplete="off" /></div>
			</div>
					
			<div class="form_check"><input type="checkbox" id="auth_pro_check" value="yes" checked="checked" /><label><?php echo $_smarty_tpl->getVariable('Lang')->value['RemindRead'];?>
 <a href="javascript:void(0);" id="auth_pro"><< <?php echo $_smarty_tpl->getVariable('Lang')->value['RemindPro'];?>
 >></a></label></div>		
			
			<div class="form_button">
				<input type="button" id="submit_btn" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Login'];?>
" />
				<input type="button" id="button_btn" class="mar_left" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Cancel'];?>
" />
			</div>
		</form>
		
		<div class="auth_msg"><?php if ($_smarty_tpl->getVariable('msg')->value){?><?php echo $_smarty_tpl->getVariable('msg')->value;?>
<?php }?></div>
		
		<div id="auth_box_msg" style="display:none"><?php $_template = new Smarty_Internal_Template('index/regFrom.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></div>
		
		<?php if ($_smarty_tpl->getVariable('vdata')->value['showLogo']){?>
			<div class="login_btn"><a href="/index/register?<?php echo $_smarty_tpl->getVariable('redirect')->value;?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['RegisterAccount'];?>
</a></div>
		<?php }?>
	</div>
	
</div>

<script language="javascript" type="text/javascript">
var LoginAuth = new TLoginAuth();
LoginAuth.Partners_json = <?php echo $_smarty_tpl->getVariable('Partners_json')->value;?>
;
LoginAuth.PH_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
LoginAuth.sData = <?php echo $_smarty_tpl->getVariable('sData')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	LoginAuth.init();
});
//释放
$(window).unload(function(){
	LoginAuth = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer_auth.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>