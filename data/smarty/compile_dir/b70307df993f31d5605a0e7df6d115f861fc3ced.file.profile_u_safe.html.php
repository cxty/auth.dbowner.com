<?php /* Smarty version Smarty-3.0.8, created on 2015-05-29 17:41:30
         compiled from "./templates/main/profile_u_safe.html" */ ?>
<?php /*%%SmartyHeaderCode:10775989925568344ac0ab46-28650715%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b70307df993f31d5605a0e7df6d115f861fc3ced' => 
    array (
      0 => './templates/main/profile_u_safe.html',
      1 => 1430127788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10775989925568344ac0ab46-28650715',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/profile_u_safe.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/checkPassWord.js"></script>

<div class="user_profile_right_box">
    <div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AccountSafe'];?>
</div>
        <div class="b_content" id="AccountSafe">
        	<ul>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['SafePassWord'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('Lang')->value['PassWordNotice'];?>
</dt><dr><a href="javascript:userSafe.domodify('SafePassWord','<?php echo $_smarty_tpl->getVariable('Lang')->value['Modify'];?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['SafePassWord'];?>
',<?php echo $_smarty_tpl->getVariable('pwd')->value;?>
)"><?php echo $_smarty_tpl->getVariable('Lang')->value['Modify'];?>
</a></dr></li>
            </ul>
        </div>
        
    </div>
    
    <div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AuthAccount'];?>
</div>
        <div class="b_content" id="AccountSafe">
        	<ul>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['SafeRealName'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('safeInfo')->value['uRealName'];?>
</dt><dr><?php if (!$_smarty_tpl->getVariable('safeInfo')->value['uRealName']){?><a id="safeRealName" href="javascript:void(0);"><?php echo $_smarty_tpl->getVariable('Lang')->value['Add'];?>
</a><?php }?></dr></li>
                <li>
                	<dl><?php echo $_smarty_tpl->getVariable('Lang')->value['SafeEmail'];?>
:</dl>
                	<dt><?php echo $_smarty_tpl->getVariable('safeInfo')->value['uSafeEmail'];?>
</dt>
                	<dr>
                		<a id="safeEmail" href="javascript:void(0);">
                			<?php if ($_smarty_tpl->getVariable('safeInfo')->value['uSafeEmail']){?><?php if ($_smarty_tpl->getVariable('safeInfo')->value['uAuthEmail']==1){?><?php echo $_smarty_tpl->getVariable('Lang')->value['NotActivated'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('Lang')->value['Modify'];?>
<?php }?><?php }else{ ?><?php echo $_smarty_tpl->getVariable('Lang')->value['Add'];?>
<?php }?>
                		</a>
                	</dr>
                </li>
                <li style="display:none;"><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['SafePhone'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('safeInfo')->value['uSafePhone'];?>
</dt><dr><a id="safePhone" href="javascript:void(0);"><?php if ($_smarty_tpl->getVariable('safeInfo')->value['uSafePhone']){?><?php echo $_smarty_tpl->getVariable('Lang')->value['Modify'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('Lang')->value['Add'];?>
<?php }?></a></dr></li>
            </ul>
        </div>
        
    </div>
      
    <div class="user_profile_right_box_b" style="display:none;">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['BandAccount'];?>
</div>     
        <div class="b_qr">
        	<img src="<?php echo $_smarty_tpl->getVariable('qrUrl')->value;?>
" />
        	<div class="b_exp"><?php echo $_smarty_tpl->getVariable('Lang')->value['QRExplain'];?>
.&nbsp;&nbsp;<a href="<?php echo $_smarty_tpl->getVariable('clientSDK')->value;?>
" target="_blank"><?php echo $_smarty_tpl->getVariable('Lang')->value['Download'];?>
</a></div>
        </div>
    </div>
    
    <div class="user_profile_right_box_b" style="display:none;">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AppAuth'];?>
</div>
        <div class="b_content" id="appAuth">
        	<ul>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AuthDefaule'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('AuthDefaule')->value;?>
</dt><dr></dr></li>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AuthApp'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('AuthApp')->value;?>
</dt><dr></dr></li>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AuthWay'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('AuthWay')->value;?>
</dt><dr></dr></li>
            </ul>
        </div>
    </div>
    
</div>

<script type="text/javascript">
var userSafe = new TuserSafe();
userSafe.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	userSafe.init();
});
//释放
$(window).unload(function(){
	userSafe = null;
});
</script>
