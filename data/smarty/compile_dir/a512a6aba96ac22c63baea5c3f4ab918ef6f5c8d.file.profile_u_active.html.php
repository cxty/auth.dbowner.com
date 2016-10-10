<?php /* Smarty version Smarty-3.0.8, created on 2015-05-29 17:41:35
         compiled from "./templates/main/profile_u_active.html" */ ?>
<?php /*%%SmartyHeaderCode:12740396365568344f69b9e3-31687452%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a512a6aba96ac22c63baea5c3f4ab918ef6f5c8d' => 
    array (
      0 => './templates/main/profile_u_active.html',
      1 => 1430127788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12740396365568344f69b9e3-31687452',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/profile_u_active.js"></script>

<div class="user_applist">
	<div class="user_applist_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AppList'];?>
</div>
	<?php if ($_smarty_tpl->getVariable('appList')->value){?>
		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('appList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
			<div class="user_applist_content" id="now_<?php echo $_smarty_tpl->tpl_vars['item']->value['AppID'];?>
">
				<div class="user_applist_info">
					<div class="info_img"><img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['img'];?>
" /></div>
					<div class="info_title">
						<dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AppName'];?>
:</dl><dt><?php echo $_smarty_tpl->tpl_vars['item']->value['aName'];?>
</dt>
						<dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AppInfo'];?>
:</dl><dt><?php echo $_smarty_tpl->tpl_vars['item']->value['aInfo'];?>
</dt>
					</div>
					<div class="info_del"><a href="javascript:active.cancelApp('<?php echo $_smarty_tpl->tpl_vars['item']->value['AppID'];?>
')"><img id="del_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="del_img" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/fancy_close.png" /></a></div>
				</div>
				<div class="user_applist_plusinfo" id="plusinfo_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
					<?php if ($_smarty_tpl->tpl_vars['item']->value['inviteCode']){?>
						<div class="plus_img">
							<a class="tiptip_plus" href="javascript:active.getInviteCode('<?php echo $_smarty_tpl->tpl_vars['item']->value['AppID'];?>
','<?php echo $_smarty_tpl->tpl_vars['item']->value['inviteCode'];?>
','<?php echo $_smarty_tpl->tpl_vars['item']->value['user_id'];?>
')" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['CreateInviteCode'];?>
">
								<img alt="<?php echo $_smarty_tpl->getVariable('Lang')->value['CreateInviteCode'];?>
" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/addinvite.png" />
							</a>
						</div>
					<?php }?>
				</div>
			</div>
		<?php }} ?>
		
		<div class="showpage"><?php echo $_smarty_tpl->getVariable('showpage')->value;?>
</div>
	<?php }?>
	
	<div class="user_applist_bottom"></div>
</div>

<div id="boxy" style="display: none"> 
 	<Iframe src="#"  name="boxy_content" id="boxy_content" marginheight="0" marginwidth="0" frameborder="0" width="100%" height="100%" scrolling="auto" ></iframe> 
</div>

<script language="javascript"> 
active = new Tactive();
active.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
active.furl = '<?php echo $_smarty_tpl->getVariable('furl')->value;?>
';
//页面完全再入后初始化
$(document).ready(function(){
	active.init();
});
//释放
$(window).unload(function(){
	active = null;
});
</script>