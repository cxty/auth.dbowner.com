<?php /* Smarty version Smarty-3.0.8, created on 2015-05-10 16:50:59
         compiled from "./templates/main/main.html" */ ?>
<?php /*%%SmartyHeaderCode:2055086166554f1bf3653740-72108378%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '73413a695e9d36f58e64ea5ea7925071efb7d1bc' => 
    array (
      0 => './templates/main/main.html',
      1 => 1430127786,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2055086166554f1bf3653740-72108378',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_GET['_action']=='index'&&$_GET[0]=='u_profile'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_u_profile.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php }elseif($_GET['_action']=='index'&&$_GET[0]=='u_safe'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_u_safe.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php }elseif($_GET['_action']=='index'&&$_GET[0]=='u_private'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_u_private.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php }elseif($_GET['_action']=='index'&&$_GET[0]=='u_points'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_u_points.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php }elseif($_GET['_action']=='index'&&$_GET[0]=='u_active'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_u_active.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php }elseif($_GET['_action']=='uploadPortrait'){?>
	<?php $_template = new Smarty_Internal_Template("main/uploadPortrait.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php }elseif($_GET['_action']=='profile_contact'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_contact.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php }elseif($_GET['_action']=='profile_common'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_common.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>	
<?php }elseif($_GET['_action']=='profile_social'){?>
	<?php $_template = new Smarty_Internal_Template("main/profile_social.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>		
<?php }?>