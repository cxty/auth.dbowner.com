<?php /* Smarty version Smarty-3.0.8, created on 2015-05-29 17:41:33
         compiled from "./templates/main/profile_u_points.html" */ ?>
<?php /*%%SmartyHeaderCode:12440231495568344dccc1f1-56177238%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bdb0cad13ab4724a3c1ae1d9fedd81db61c4eccc' => 
    array (
      0 => './templates/main/profile_u_points.html',
      1 => 1430127787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12440231495568344dccc1f1-56177238',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/profile_u_points.js"></script>

<div class="user_profile_right_box">
    <div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['Points'];?>
</div>
        <div class="b_content">
        	<ul>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['PointSurplus'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('inter')->value;?>
</dt><dr><a href="<?php echo $_smarty_tpl->getVariable('platform_pay')->value;?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['PointRecharge'];?>
</a></dr></li>
				<li>
					<dl><?php echo $_smarty_tpl->getVariable('Lang')->value['PointRecord'];?>
:</dl>
					<dt><?php echo $_smarty_tpl->getVariable('Lang')->value['PointState'];?>
</dt>
					<dr><a href="<?php echo $_smarty_tpl->getVariable('platform_pay')->value;?>
/trade/db"><?php echo $_smarty_tpl->getVariable('Lang')->value['PointCheck'];?>
</a></dr>
					<dr style="display:none;"><a href="javascript:checkInter('PointRecord','<?php echo $_smarty_tpl->getVariable('Lang')->value['PointRecord'];?>
')"><?php echo $_smarty_tpl->getVariable('Lang')->value['PointCheck'];?>
</a></dr>
				</li>
            </ul>
        </div>
        
    </div>
    
    <span id="pointRecord" style="display:none"><?php $_template = new Smarty_Internal_Template('main/pointRecord.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></span>
    
    <div class="user_profile_right_box_b" style="display:none">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['PointChange'];?>
</div>
        <div class="b_content">
        	<ul>
                <li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AddSpace'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('PointContent')->value[1][1];?>
</dt><dr><a href="javascript:checkInter('PointChange','<?php echo $_smarty_tpl->getVariable('Lang')->value['PointRecord'];?>
')"><?php echo $_smarty_tpl->getVariable('Lang')->value['PointChange'];?>
</a></dr></li>
				<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AddApp'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('PointContent')->value[1][2];?>
</dt><dr><a href="javascript:checkInter('PointChange','<?php echo $_smarty_tpl->getVariable('Lang')->value['PointRecord'];?>
')"><?php echo $_smarty_tpl->getVariable('Lang')->value['PointChange'];?>
</a></dr></li>
				<li><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AddAppAnnounce'];?>
:</dl><dt><?php echo $_smarty_tpl->getVariable('PointContent')->value[1][3];?>
</dt><dr><a href="javascript:checkInter('PointChange','<?php echo $_smarty_tpl->getVariable('Lang')->value['PointRecord'];?>
')"><?php echo $_smarty_tpl->getVariable('Lang')->value['PointChange'];?>
</a></dr></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
Upoints = new TUpoints();
Upoints.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	Upoints.init();
});
//释放
$(window).unload(function(){
	Upoints = null;
});
</script>