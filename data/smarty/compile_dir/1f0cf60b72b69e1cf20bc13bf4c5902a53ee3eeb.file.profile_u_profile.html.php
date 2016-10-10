<?php /* Smarty version Smarty-3.0.8, created on 2015-05-10 16:50:59
         compiled from "./templates/main/profile_u_profile.html" */ ?>
<?php /*%%SmartyHeaderCode:1423530522554f1bf37d9662-48886918%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f0cf60b72b69e1cf20bc13bf4c5902a53ee3eeb' => 
    array (
      0 => './templates/main/profile_u_profile.html',
      1 => 1430127787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1423530522554f1bf37d9662-48886918',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/profile_u_profile.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.jUploader-1.0.js"></script>

<div class="user_profile_right_box">
    <div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['Portrait'];?>
</div>
        <div class="b_content">
        	<div class="user_ico_b"><img class="fancybox" src="<?php echo $_smarty_tpl->getVariable('imagesUrl_1')->value;?>
" /></div>
            <div class="user_ico_m"><img class="fancybox" src="<?php echo $_smarty_tpl->getVariable('imagesUrl_2')->value;?>
" /></div>
            <div class="user_ico_s"><img class="fancybox" src="<?php echo $_smarty_tpl->getVariable('imagesUrl_3')->value;?>
" /></div>
            
        </div>
        <div class="b_tool"><a href="javascript:void(0);" id="user_ico_tool_bar"><?php echo $_smarty_tpl->getVariable('Lang')->value['Modify'];?>
</a></div>
    	<div id="Protrait"><div class="b_content pro-upload"><?php echo $_smarty_tpl->getVariable('Lang')->value['PictrueLoading'];?>
</div></div>
    </div>
    
    <div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['CommonInfo'];?>
</div>
        <div class="b_content" id="CommonInfo">
            <ul>
            	<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactNickname'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('userInfo')->value['uName'];?>
</dt></li>
            	<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['Sex'];?>
:</dl><dt><?php if ($_smarty_tpl->getVariable('userInfo')->value['uSex']==1){?><?php echo $_smarty_tpl->getVariable('Lang')->value['Female'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('Lang')->value['Male'];?>
<?php }?></dt></li>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['Birthday'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('userInfo')->value['uBirthday'];?>
</dt></li>
            </ul>
        </div>
        <div class="b_tool"><a id="common_btn" href="javascript:void(0);"><?php echo $_smarty_tpl->getVariable('Lang')->value['Modify'];?>
</a></div>
    </div>
    
    <div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactInfo'];?>
</div>
        <div class="b_content" id="contactInfo">
            <ul>
            	<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactAddr'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('userInfo')->value['uComeFrom'];?>
</dt></li>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactEmail'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('userInfo')->value['uEmail'];?>
<?php if ($_smarty_tpl->getVariable('userInfo')->value['uEmail']&&($_smarty_tpl->getVariable('userInfo')->value['uEstate']==-1||$_smarty_tpl->getVariable('userInfo')->value['uEstate']==-2)){?>&nbsp;&nbsp;<a id="activateBtn" href="javascript:void(0);" title="<?php echo $_smarty_tpl->getVariable('Lang')->value['ToActivateMessage'];?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['ToActivate'];?>
</a><?php }?></dt></li>
            </ul>
        </div>
        <div class="b_tool"><a id="contact_btn" href="javascript:void(0);"><?php echo $_smarty_tpl->getVariable('Lang')->value['Modify'];?>
</a></div>
    </div> 
    
    <div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['WorkInfo'];?>
</div>
        <div class="b_content">
            <ul>
            	<?php if ($_smarty_tpl->getVariable('workInfo')->value){?>
            		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('workInfo')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
	            		<li class="li_show" id="li_<?php echo $_smarty_tpl->tpl_vars['item']->value['AutoID'];?>
">
	            			<dl></dl>
	            			<dt>
	            				<?php echo $_smarty_tpl->tpl_vars['item']->value['wStartYear'];?>
-<?php echo $_smarty_tpl->tpl_vars['item']->value['wEndYear'];?>

	            				&nbsp;&nbsp;
	            				<?php echo $_smarty_tpl->tpl_vars['item']->value['wCompanyName'];?>

	            				&nbsp;&nbsp;
	            				<?php echo $_smarty_tpl->tpl_vars['item']->value['wDepartment'];?>

	            				&nbsp;&nbsp;
	            				<?php echo $_smarty_tpl->tpl_vars['item']->value['State'];?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['Provice'];?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['City'];?>

	            				&nbsp;&nbsp;
	            				<a class="aedit" href="javascript:UserProfile.workEdit(<?php echo $_smarty_tpl->tpl_vars['item']->value['AutoID'];?>
);"></a>
	            				<a class="adelt" href="javascript:UserProfile.workDele(<?php echo $_smarty_tpl->tpl_vars['item']->value['AutoID'];?>
)"></a>
	            			</dt>
	            		</li>
            		<?php }} ?>
            	<?php }else{ ?>
            		<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['NotFill'];?>
</dl><dt></dt></li>
           		<?php }?>
            </ul>
        </div>
        <div class="b_tool"><a id="work_btn" href="javascript:void(0);"><?php echo $_smarty_tpl->getVariable('Lang')->value['Add'];?>
</a></div>
    </div>
    
    <div class="user_profile_right_box_b" style="display:none;">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['EducationInfo'];?>
</div>
        <div class="b_content">
            <ul>
            	<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['NotFill'];?>
</dl><dt></dt></li>
            </ul>
        </div>
        <div class="b_tool"><a id="education_btn" href="javascript:void(0);"><?php echo $_smarty_tpl->getVariable('Lang')->value['Add'];?>
</a></div>
    </div>
</div>

<script type="text/javascript">
var UserProfile = new TUserProfile();
UserProfile.IMAGES = <?php echo $_smarty_tpl->getVariable('images')->value;?>
;
UserProfile.theEamil = $('#contactInfo input').eq(1).val();
UserProfile.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	UserProfile.init();
});
//释放
$(window).unload(function(){
	UserProfile = null;
});
</script>