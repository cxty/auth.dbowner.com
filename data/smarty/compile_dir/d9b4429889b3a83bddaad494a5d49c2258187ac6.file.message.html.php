<?php /* Smarty version Smarty-3.0.8, created on 2015-07-02 14:37:09
         compiled from "./templates/main/message.html" */ ?>
<?php /*%%SmartyHeaderCode:3502934275594dc152f7964-55780275%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd9b4429889b3a83bddaad494a5d49c2258187ac6' => 
    array (
      0 => './templates/main/message.html',
      1 => 1430127787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3502934275594dc152f7964-55780275',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<?php $_template = new Smarty_Internal_Template('kindsoft.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/message.js"></script>

<div class="user_profile">
	<div class="user_profile_left">
        <div class="user_profile_left_tool" id="msg_num">
        	<ul>
            	<li id="writeMsg" <?php if ($_smarty_tpl->getVariable('type')->value=='writeMsg'){?>class="selected"<?php }?>><div></div><label><?php echo $_smarty_tpl->getVariable('Lang')->value['WriteMsg'];?>
</label><span></span></li>
                <li id="unreadMsg" <?php if ($_smarty_tpl->getVariable('type')->value=='unreadMsg'){?>class="selected"<?php }?>><div></div><label><?php echo $_smarty_tpl->getVariable('Lang')->value['UnreadMsg'];?>
</label><span>【<?php echo $_smarty_tpl->getVariable('unread')->value;?>
】</span></li>
                <li id="readMsg" <?php if ($_smarty_tpl->getVariable('type')->value=='readMsg'){?>class="selected"<?php }?>><div></div><label><?php echo $_smarty_tpl->getVariable('Lang')->value['ReadedMsg'];?>
</label><span>【<?php echo $_smarty_tpl->getVariable('msgNum')->value['readMsg'];?>
】</span></li>
                <li id="sendMsg" <?php if ($_smarty_tpl->getVariable('type')->value=='sendMsg'){?>class="selected"<?php }?>><div></div><label><?php echo $_smarty_tpl->getVariable('Lang')->value['SendMsg'];?>
</label><span>【<?php echo $_smarty_tpl->getVariable('msgNum')->value['sendMsg'];?>
】</span></li>
                <li style="display:none;" id="delMsg" <?php if ($_smarty_tpl->getVariable('type')->value=='delMsg'){?>class="selected"<?php }?>><div></div><label><?php echo $_smarty_tpl->getVariable('Lang')->value['DelMsg'];?>
</label><span>【<?php echo $_smarty_tpl->getVariable('msgNum')->value['delMsg'];?>
】</span></li>
            </ul>
        </div>
    </div>
    <div class="user_profile_right">
    	<div class="msg_content_box">
		    	<?php if ($_smarty_tpl->getVariable('type')->value=='writeMsg'){?>
		    		<?php $_template = new Smarty_Internal_Template('main/msgadd.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
		    	<?php }else{ ?>
		    		<?php $_template = new Smarty_Internal_Template('main/msglist.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
		    	<?php }?>
    	</div>
    </div>
    <div class="content_box_c">
		
    </div>
</div>

<script type="text/javascript">
var msg = new Tmsg();
msg.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
msg.init();
//页面完全再入后初始化
$(document).ready(function(){
	msg.init();
});
//释放
$(window).unload(function(){
	msg = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>