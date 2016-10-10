<?php /* Smarty version Smarty-3.0.8, created on 2015-05-10 16:50:59
         compiled from "./templates/main/index.html" */ ?>
<?php /*%%SmartyHeaderCode:341665244554f1bf3443d40-73574353%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '79b5a2ddde563280e757cf554dbaad52a87c0417' => 
    array (
      0 => './templates/main/index.html',
      1 => 1430127786,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '341665244554f1bf3443d40-73574353',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/user_profile.js"></script>

<div class="user_profile">
	<div class="user_profile_left">
    	<div class="user_profile_left_ico" ><img class="fancybox" id="user_ico" src="<?php echo $_smarty_tpl->getVariable('imagesUrl_2')->value;?>
" /></div>
        <div class="user_profile_left_tool">
        	<ul>
            	<li id="u_profile" <?php if ($_GET[0]=='u_profile'){?>class="selected"<?php }?>><div></div><span><?php echo $_smarty_tpl->getVariable('Lang')->value['UserDetail'];?>
</span></li>   
                <li id="u_private" <?php if ($_GET[0]=='u_private'){?>class="selected"<?php }?>><div></div><span><?php echo $_smarty_tpl->getVariable('Lang')->value['LoginManage'];?>
</span></li>
                <li id="u_safe" <?php if ($_GET[0]=='u_safe'){?>class="selected"<?php }?>><div></div><span><?php echo $_smarty_tpl->getVariable('Lang')->value['SetSafe'];?>
</span></li>
                <li id="u_points" <?php if ($_GET[0]=='u_points'){?>class="selected"<?php }?>><div></div><span><?php echo $_smarty_tpl->getVariable('Lang')->value['UserSorce'];?>
</span></li>
                <li id="u_active" <?php if ($_GET[0]=='u_active'){?>class="selected"<?php }?>><div></div><span><?php echo $_smarty_tpl->getVariable('Lang')->value['ActiveAccount'];?>
</span></li>
            </ul>
        </div>
    </div>
    <div class="user_profile_right">
    	<?php $_template = new Smarty_Internal_Template("main/main.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    </div>
    <div class="content_box_c">
		
    </div>
</div>

<script type="text/javascript">
var User_Profile = new TUser_Profile();
User_Profile.ns = '';
User_Profile.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	User_Profile.init();
});
//释放
$(window).unload(function(){
	User_Profile = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>