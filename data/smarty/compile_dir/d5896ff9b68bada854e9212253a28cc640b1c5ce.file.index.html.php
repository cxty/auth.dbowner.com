<?php /* Smarty version Smarty-3.0.8, created on 2015-09-03 09:49:40
         compiled from "./templates/index/index.html" */ ?>
<?php /*%%SmartyHeaderCode:185953818855e7a7341fb792-84635049%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5896ff9b68bada854e9212253a28cc640b1c5ce' => 
    array (
      0 => './templates/index/index.html',
      1 => 1441184128,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185953818855e7a7341fb792-84635049',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<div class="content_box">
    <div class="content_box_c">
           <div class="content">
           		<?php if ($_smarty_tpl->getVariable('pageModel')->value=='inviteCode'){?>
           			<?php $_template = new Smarty_Internal_Template('index/inviteCode.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
           		<?php }else{ ?>
           			<?php if ($_GET['_action']=='register'){?>
	           			<?php $_template = new Smarty_Internal_Template('index/register.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
					<?php }elseif($_GET['_action']=='login'){?>
						<?php $_template = new Smarty_Internal_Template('index/login.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
					<?php }elseif($_GET['_action']=='beforeJoinfrom'){?>
						<?php $_template = new Smarty_Internal_Template('index/loginThirdParty.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
					<?php }elseif($_GET['_action']=='resetpwd'){?>
						<?php $_template = new Smarty_Internal_Template('index/resetpwd.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
					<?php }else{ ?>
						<?php $_template = new Smarty_Internal_Template('index/message.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
					<?php }?>
           		<?php }?>
           		
           </div>
    </div>
</div>

<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>