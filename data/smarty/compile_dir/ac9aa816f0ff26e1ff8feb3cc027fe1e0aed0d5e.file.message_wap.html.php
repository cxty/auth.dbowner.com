<?php /* Smarty version Smarty-3.0.8, created on 2015-07-02 18:16:27
         compiled from "./templates/throwMessage/message_wap.html" */ ?>
<?php /*%%SmartyHeaderCode:52969503855950f7b1a86d4-78265517%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ac9aa816f0ff26e1ff8feb3cc027fe1e0aed0d5e' => 
    array (
      0 => './templates/throwMessage/message_wap.html',
      1 => 1430127789,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '52969503855950f7b1a86d4-78265517',
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
	<div class="login_remind"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Remind'];?>
:</div>
	
	<div id="login_common">
		<div class="msg_box">
			<?php echo $_smarty_tpl->getVariable('backArr')->value['msg'];?>

		</div>			
		<div class="remind_box">
			<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Remind1'];?>
<br />
			<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Remind2'];?>
<a class="c_red" href="/index/register?<?php echo $_smarty_tpl->getVariable('redirect')->value;?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Register'];?>
</a>。
		</div>
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