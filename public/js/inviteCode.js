function TinviteCode(){
	this.JS_LANG = '';
};
TinviteCode.prototype.init = function(){
	$('.box_body').hide();
	
	$("#submit_btn").click(function(){
		var code = $('#inviteCode').val();
		
		if(code == ''){
			Boxy.alert(inviteCode.JS_LANG.IsNullInviteCode,
					function(){$('#inviteCode').val('').focus();},
					{title: inviteCode.JS_LANG.Remind ,modal:true,unloadOnHide:true}
					);	
		}else{			
			$.get('/index/useActiveCode',{type:1,client_id:$('#client_id').val(),inviteCode:code,rnd:Math.random()},function(data){
				switch(parseInt(data)){
					case 1:
						Boxy.alert(inviteCode.JS_LANG.SuccessActive,
								function(){$('#loginform').submit()},
								{title: inviteCode.JS_LANG.Remind ,modal:true,unloadOnHide:true}
								);	
						break;
					case -1:
						Boxy.alert(inviteCode.JS_LANG.FailActive_CodeNotExist,
								function(){$('#inviteCode').val('').focus();},
								{title: inviteCode.JS_LANG.Remind ,modal:true,unloadOnHide:true}
								);
						break;
					case -2:
						Boxy.alert(inviteCode.JS_LANG.FailActive_CodeActived,
								function(){$('#inviteCode').val('').focus();},
								{title: inviteCode.JS_LANG.Remind ,modal:true,unloadOnHide:true}
								);
						break;
					default:
						Boxy.alert(inviteCode.JS_LANG.FailActive,
								function(){},
								{title: inviteCode.JS_LANG.Remind ,modal:true,unloadOnHide:true}
								);
						break;
				}
			});
		}
	});
};