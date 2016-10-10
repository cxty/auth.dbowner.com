<?php /* Smarty version Smarty-3.0.8, created on 2015-07-02 14:37:09
         compiled from "./templates/main/msglist.html" */ ?>
<?php /*%%SmartyHeaderCode:6508147505594dc154c3166-24858088%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bc3d05f7651db0aee096bfa4ce6db1a857e0ff6' => 
    array (
      0 => './templates/main/msglist.html',
      1 => 1430127786,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6508147505594dc154c3166-24858088',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_date_format')) include '/www/web/auth_dbowner_com/public_html/include/ext/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_smartTruncate')) include '/www/web/auth_dbowner_com/public_html/include/ext/smarty/plugins/modifier.smartTruncate.php';
?>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/msglist.js"></script>

<div class="msg_title">
	<?php if ($_smarty_tpl->getVariable('type')->value=='unreadMsg'){?>
		<?php echo $_smarty_tpl->getVariable('Lang')->value['UnreadMsg'];?>

	<?php }elseif($_smarty_tpl->getVariable('type')->value=='readMsg'){?>
		<?php echo $_smarty_tpl->getVariable('Lang')->value['ReadedMsg'];?>

	<?php }elseif($_smarty_tpl->getVariable('type')->value=='sendMsg'){?>
		<?php echo $_smarty_tpl->getVariable('Lang')->value['SendMsg'];?>

	<?php }elseif($_smarty_tpl->getVariable('type')->value=='delMsg'){?>
		<?php echo $_smarty_tpl->getVariable('Lang')->value['DelMsg'];?>

	<?php }?>
</div>

<?php if ($_smarty_tpl->getVariable('view')->value=='detail'){?>
	<div class="msg_content">
		<div class="msg_detail_box">
			<div class="msg_d_b_box">
				<?php if ($_smarty_tpl->getVariable('ident')->value=='send'){?>
					<div class="msg_d_b_pic_box">
						<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('detailMsg')->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
							<img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['portrait']['imagesUrl_3'];?>
" />
							<?php echo $_smarty_tpl->tpl_vars['item']->value['uName'];?>

						<?php }} ?>
					</div>
					<div class="msg_d_b_time">
						<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y')==smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],'%Y')){?>
							<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],'%Y-%m-%d')){?>
								<?php echo $_smarty_tpl->getVariable('Lang')->value['Today'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%H:%M");?>

							<?php }elseif(smarty_modifier_date_format($_smarty_tpl->getVariable('yestime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],'%Y-%m-%d')){?>
								<?php echo $_smarty_tpl->getVariable('Lang')->value['Yestoday'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%H:%M");?>

							<?php }else{ ?>
								<?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%m-%d %H:%M");?>

							<?php }?>
						<?php }else{ ?>
							<?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%Y-%m-%d %H:%M");?>

						<?php }?>
					</div>
				<?php }else{ ?>
					<div class="msg_d_b_pic">
						<img src="<?php echo $_smarty_tpl->getVariable('detailMsg')->value['portrait']['imagesUrl_3'];?>
" />
					</div>
					<div class="msg_d_b_right">
						<?php echo $_smarty_tpl->getVariable('detailMsg')->value['people'];?>

						&nbsp;&nbsp;
						<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y')==smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],'%Y')){?>
							<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],'%Y-%m-%d')){?>
								<?php echo $_smarty_tpl->getVariable('Lang')->value['Today'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%H:%M");?>

							<?php }elseif(smarty_modifier_date_format($_smarty_tpl->getVariable('yestime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],'%Y-%m-%d')){?>
								<?php echo $_smarty_tpl->getVariable('Lang')->value['Yestoday'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%H:%M");?>

							<?php }else{ ?>
								<?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%m-%d %H:%M");?>

							<?php }?>
						<?php }else{ ?>
							<?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('detailMsg')->value['uAppendTime'],"%Y-%m-%d %H:%M");?>

						<?php }?>
					</div>
				<?php }?>
			</div>
			<div class="msg_d_b_content <?php if ($_smarty_tpl->getVariable('ident')->value=='send'){?>m_t_30<?php }else{ ?>m_t_10<?php }?>">
				<?php echo $_smarty_tpl->getVariable('detailMsg')->value['content'];?>

			</div>
			<?php if ($_smarty_tpl->getVariable('ident')->value!='send'){?>
				<div class="msg_d_b_edit">
					<form method="POST" action="/main/answerMsg">
						<input type="hidden" name="type" value="<?php echo $_smarty_tpl->getVariable('type')->value;?>
" />
						<input type="hidden" name="uFormID" value="<?php echo $_smarty_tpl->getVariable('detailMsg')->value['UserID'];?>
" />
						<input type="hidden" name="uMsgID" value="<?php echo $_smarty_tpl->getVariable('detailMsg')->value['uMsgID'];?>
" />
						<input type="hidden" name="uAncMsgID" value="<?php echo $_smarty_tpl->getVariable('detailMsg')->value['uAncMsgID'];?>
" />
						<input type="hidden" name="uPreMsgID" value="<?php echo $_smarty_tpl->getVariable('detailMsg')->value['uPreMsgID'];?>
" />
						
						<textarea name="uContent" id="uContent" style="width:700px;height:150px;"></textarea>
						<input type="submit" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['CallBack'];?>
" />		
					</form>	
				</div>
			<?php }?>
		</div>	
	</div>
<?php }else{ ?>
	<div class="msglist_content">		
		<?php if ($_smarty_tpl->getVariable('msgArr')->value){?>
			<div class="msglist_box_space"></div>
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('msgArr')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
				<div class="msglist_box" id="msglist_box_<?php echo $_smarty_tpl->tpl_vars['item']->value['uMsgID'];?>
">
					<?php if ($_smarty_tpl->getVariable('type')->value=='sendMsg'){?>
						<div class="msg_b_l_input">
							<input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['selfid'];?>
" />
						</div>
						<div class="msg_b_l_more_box">
							<div class="msg_b_l_more">
								<?php  $_smarty_tpl->tpl_vars['item2'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['portrait']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item2']->key => $_smarty_tpl->tpl_vars['item2']->value){
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['item2']->key;
?>
									<img src="<?php echo $_smarty_tpl->tpl_vars['item2']->value['imagesUrl_3'];?>
" /><?php echo $_smarty_tpl->tpl_vars['item']->value['nameList'][$_smarty_tpl->getVariable('key2')->value];?>

								<?php }} ?>
							</div>
							<div class="msg_b_m_content">
								<a href="/main/message?type=<?php echo $_smarty_tpl->getVariable('type')->value;?>
&ident=<?php echo $_smarty_tpl->tpl_vars['item']->value['ident'];?>
&view=detail&uMsgID=<?php echo $_smarty_tpl->tpl_vars['item']->value['uMsgID'];?>
"><?php echo smarty_modifier_smartTruncate($_smarty_tpl->tpl_vars['item']->value['uContent'],38,"...");?>
</a>
							</div>
							<div class="msg_b_m_time">
								<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y')==smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],'%Y')){?>
									<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],'%Y-%m-%d')){?>
										<?php echo $_smarty_tpl->getVariable('Lang')->value['Today'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%H:%M");?>

									<?php }elseif(smarty_modifier_date_format($_smarty_tpl->getVariable('yestime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],'%Y-%m-%d')){?>
										<?php echo $_smarty_tpl->getVariable('Lang')->value['Yestoday'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%H:%M");?>

									<?php }else{ ?>
										<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%m-%d %H:%M");?>

									<?php }?>
								<?php }else{ ?>
									<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%Y-%m-%d %H:%M");?>

								<?php }?>
							</div>
						</div>
					<?php }else{ ?>
						<div class="msg_b_l">
							<input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['selfid'];?>
" />
							<img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['portrait']['imagesUrl_2'];?>
" />
						</div>
						<div class="msg_b_m">
							<div class="msg_b_m_title"><?php if ($_smarty_tpl->getVariable('type')->value=='sendMsg'){?><?php echo $_smarty_tpl->tpl_vars['item']->value['userList'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['item']->value['uName'];?>
<?php }?></div>
							<div class="msg_b_m_content">
								<a href="/main/message?type=<?php echo $_smarty_tpl->getVariable('type')->value;?>
&ident=<?php echo $_smarty_tpl->tpl_vars['item']->value['ident'];?>
&view=detail&uMsgID=<?php echo $_smarty_tpl->tpl_vars['item']->value['uMsgID'];?>
"><?php echo smarty_modifier_smartTruncate($_smarty_tpl->tpl_vars['item']->value['uContent'],38,"...");?>
</a>
							</div>
							<div class="msg_b_m_time">
								<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y')==smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],'%Y')){?>
									<?php if (smarty_modifier_date_format($_smarty_tpl->getVariable('nowtime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],'%Y-%m-%d')){?>
										<?php echo $_smarty_tpl->getVariable('Lang')->value['Today'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%H:%M");?>

									<?php }elseif(smarty_modifier_date_format($_smarty_tpl->getVariable('yestime')->value,'%Y-%m-%d')==smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],'%Y-%m-%d')){?>
										<?php echo $_smarty_tpl->getVariable('Lang')->value['Yestoday'];?>
  <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%H:%M");?>

									<?php }else{ ?>
										<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%m-%d %H:%M");?>

									<?php }?>
								<?php }else{ ?>
									<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['uAppendTime'],"%Y-%m-%d %H:%M");?>

								<?php }?>
							</div>
						</div>
					<?php }?>
					
					<div class="msg_b_r">
						<div class="msg_b_r_del">
							<a class="adelt" href="javascript:msglist.delClick(<?php echo $_smarty_tpl->tpl_vars['item']->value['uMsgID'];?>
,this)"></a>
						</div>
						<div class="msg_b_r_check"><a href="/main/message?type=<?php echo $_smarty_tpl->getVariable('type')->value;?>
&ident=<?php echo $_smarty_tpl->tpl_vars['item']->value['ident'];?>
&view=detail&uMsgID=<?php echo $_smarty_tpl->tpl_vars['item']->value['uMsgID'];?>
"><?php echo $_smarty_tpl->getVariable('Lang')->value['CheckDetail'];?>
</a></div>
					</div>
				</div>
			<?php }} ?>
			
			<div class="sub_foot">
				<div class="subBut">
					<?php if ($_smarty_tpl->getVariable('type')->value!='delMsg'){?>
						<input type="checkbox" id="selectAll" value="all" />
						<input type="button" id="sub_but" name="but" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['Delete'];?>
" />
					<?php }?>
				</div>
				<div class="showpage_r"><?php echo $_smarty_tpl->getVariable('showpage')->value;?>
</div>
			</div>
		<?php }else{ ?>
			<div class="notcontent">
				<?php echo $_smarty_tpl->getVariable('Lang')->value['NotNullContent'];?>

			</div>		
		<?php }?>
	</div>
<?php }?>

<script type="text/javascript">
var msglist = new Tmsglist();
msglist.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	msglist.edit();
	msglist.init();
});
//释放
$(window).unload(function(){
	msglist = null;
});
</script>