
function Tmsglist(){
	this.JS_LANG = ''; //语言包
};
Tmsglist.prototype.init = function(){	
	$('.msglist_box .msg_b_r').hide();
	$('.msglist_box').hover(
		function(){
			$('.msglist_box .msg_b_r').eq($('.msglist_box').index(this)).show();
		},
		function(){
			$('.msglist_box .msg_b_r').eq($('.msglist_box').index(this)).hide();	
		}
	);
	
	$("#selectAll").click(function(){
		if($(this).attr('checked') == 'checked'){
			$('.msglist_content input[type=checkbox]').attr('checked',true);
		}else{
			$('.msglist_content input[type=checkbox]').attr('checked',false);
		}
	});
	
	$('#sub_but').click(function(){
		Boxy.confirm( msglist.JS_LANG.ConfirmDel ,
				function(){
					var idstr = '';
					$('.msglist_content input[type=checkbox]').each(function(){
						if($(this).val() != 'all' && $(this).attr('checked') == 'checked'){
							idstr += ','+$(this).val();
							$(this).parent().parent().remove();
						}
					});
					
					msglist.subClick(idstr);
					
				},
				{title: msglist.JS_LANG.Remind ,modal:true,unloadOnHide:true}
			);	
	});
};
Tmsglist.prototype.subClick = function(idstr){
	$.get('/main/delMsg',{type:$('#type').val(),idstr:idstr,rnd:Math.random()},function(data){
		//alert(data);
	});
};
Tmsglist.prototype.delClick = function(id){
	Boxy.confirm( msglist.JS_LANG.ConfirmDel ,
			function(){
				msglist.subClick(','+id);
				$('#msglist_box_'+id).remove();		
			},
			{title: msglist.JS_LANG.Remind ,modal:true,unloadOnHide:true}
		);
};
Tmsglist.prototype.createEditor = function(){
	$('#msg_callback').DropInDown();
};
Tmsglist.prototype.removeEditor = function(){
	$('#msg_callback').DropOutDown();
};
Tmsglist.prototype.edit = function(){
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="uContent"]', {
			themeType : 'simple',
			filterMode : true,
			resizeType : 1,
			allowFileManager : true,
			items : [
			//			'source','|','fontname', 'fontsize', '|', 'forecolor', 'bold', 'italic', 'underline',
			//			'removeformat','|','preview','image','fullscreen'
					]
		});
	});	
	prettyPrint();
};