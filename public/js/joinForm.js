
function TjoinForm(){
    this.ns = '';
    this.def_uEmail_txt = '';
    this.JS_LANG = '';
    
    this.checkArray = '';
};

var join_uEmail = '';
TjoinForm.prototype.init = function(){
	this.def_uEmail_txt = joinForm.JS_LANG.Email;

	$('#'+joinForm.ns+'uEmail').blur(function(){
		if(join_uEmail != '' && join_uEmail != $('#'+joinForm.ns+'uEmail').val()){
			$('#submit_btn span').text(joinForm.JS_LANG.JoinUser);
			$('#join_uEmail').remove();			
		}
		join_uEmail = $('#'+joinForm.ns+'uEmail').val();
	});
	
    $('#'+this.ns+'submit_btn').click(function(){
    	
        if($.trim($('#'+joinForm.ns+'uEmail').val()) == ''){
			Boxy.alert( joinForm.JS_LANG.RightEmail ,function(){$('#'+joinForm.ns+'uEmail').focus();},{title: joinForm.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        }else if($.trim($('#'+joinForm.ns+'uName').val()) == ''){
			Boxy.alert(joinForm.JS_LANG.RightNickname ,function(){$('#'+joinForm.ns+'uName').focus();},{title: joinForm.JS_LANG.Remind,modal:true,unloadOnHide:true});            
        }else if(!$('#'+joinForm.ns+'agree').attr('checked')){
        	joinForm.btnClick();
			$('#'+joinForm.ns+'agree').attr('checked','checked');	
			$('.input_box_b label').addClass('checked');
        }else{
        	if(typeof $('#password').val() == 'undefined'){
	        	var uEmail = $('#'+joinForm.ns+'uEmail').val();
	        	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
	            	
	        	if(reg.test(uEmail)){
	        		joinForm.checkJoinFrom(uEmail); 		
	        	}else{
	        		Boxy.alert(joinForm.JS_LANG.EmailForm ,function(){$('#'+joinForm.ns+'uEmail').val('').focus();},{title: joinForm.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
	        	}
        	}else{
        		$.get('/index/checkPassword',{pwd:$('#password').val(),uEmail:$('#'+joinForm.ns+'uEmail').val(),rnd:Math.random()},
        			function(data){
        				if(data > 0){ 
        					$('#loginform').submit();
        				}else{
        					Boxy.alert( joinForm.JS_LANG.WrongPwd,function(){$('#password').val('').focus();},{title: joinForm.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
        				}
        		});
        	}
		}
    });
    
    
    $('#'+this.ns+'submit_btn_Skip').click(function(){
    	$('#loginform').attr('action','/index/joinSkip');
    	$('#loginform').submit();
    });
    
    //邮箱上层提示信息
    $('#'+this.ns+'uEmail').before(jQuery('<div class="def_txt">'+this.def_uEmail_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+joinForm.ns+'uEmail').focus();
    }));
    
    $('#'+this.ns+'uEmail').focusin(function(){
            $(this).prevAll('.def_txt').hide();
    });
    $('#'+this.ns+'uEmail').focusout(function(){
        if($(this).val()=='')
        {
            $(this).prevAll('.def_txt').show();
        }else{
            $(this).prevAll('.def_txt').hide();
        }
    });
    $('#to_m').click(function(){
    	joinForm.btnClick();		
	});

	$(document).ready( function () { 
		$('#'+joinForm.ns+'uEmail').focusout();
	});	
};
TjoinForm.prototype.btnClick = function(){
	var _box = new Boxy($('#box_body_msg').html(),{title: joinForm.JS_LANG.UseRule ,center:true,modal:true,closeText: joinForm.JS_LANG.TurnOff ,unloadOnHide:true},function(val){});

    _box.tween($(document).width()-400,$(document).height()-400);
    
    return false;
};
TjoinForm.prototype.checkJoinFrom = function(uEmail){
	$.fancybox.showLoading();
	$.post('/index/checkJoinfrom',{uEmail:uEmail,rnd:Math.random()},
			function(data){ 
				$.fancybox.hideLoading();
		
				var conMsg = joinForm.JS_LANG.ConfirmBinding;
				if ( data.repeat ) {
					conMsg = joinForm.JS_LANG.ConfirmBinding + '<br />' + joinForm.JS_LANG.And + joinForm.JS_LANG.ConfirmBinded;
				}
		
				$('#submit_btn span').text(joinForm.JS_LANG.Login);
				if(parseInt(data.OauthID) == -2){
					Boxy.alert( 
							joinForm.JS_LANG.RightEmail ,
							function(){
								$('#'+joinForm.ns+'uEmail').focus();
							},
							{title: joinForm.JS_LANG.Remind ,modal:true,unloadOnHide:true}
						);
				} else if ( parseInt(data.OauthID) > 0 && data.accountInfo.UserID != -1 ) {
					joinForm.checkArray = data;
					joinForm.makeConfirm();
					return;
					/*
					Boxy.confirm( conMsg , 
								  function() { 
										joinForm.loginMethod(data.accountInfo,data.data);
								  }, 
								  {title: joinForm.JS_LANG.Remind });*/
				} else {
					if ( data.repeat ) {
						Boxy.confirm( joinForm.JS_LANG.ConfirmBinded , 
								  function() { 
										joinForm.loginMethod(data.accountInfo,data.data);
								  }, 
								  {title: joinForm.JS_LANG.Remind });
					} else {
						joinForm.loginMethod(data.accountInfo,data.data);
					}
				}       					
			}
		,'json'
    );
};
TjoinForm.prototype.makeConfirm = function () {
	$.fancybox({
	    type: 'iframe',
	    href: '/index/repeatComfirm',
	    scrolling: 'no',
	    width: 1000,
	    height: 500,
	    modal : true,
	    onClosed: function(){
	    	//location = location;
	    } 
	});
};
TjoinForm.prototype.makeConfirmSubBtn = function () {
	joinForm.loginMethod(joinForm.checkArray.accountInfo,joinForm.checkArray.data);
};
TjoinForm.prototype.loginMethod = function(accountInfo,jsondata){
	var UserID = parseInt(accountInfo.UserID);
	var msg = parseInt(jsondata.msg);
	var pwd = parseInt(accountInfo.pwd);

	if(UserID == -1 && msg == -1){
		$('#loginform').submit();return;		
	}
	
	if(pwd == -1 && msg == -1){
		Boxy.alert(joinForm.JS_LANG.ErrorAccount ,
				function(){
					$('#submit_btn span').text(joinForm.JS_LANG.JoinUser);
					$('#'+joinForm.ns+'uEmail').val('');
				},
				{title: joinForm.JS_LANG.Remind,modal:true,unloadOnHide:true});return;
	}

	var PwdHTML = '';
	if(pwd != -1){		
		PwdHTML += '<div id="join_uEmail"><div>' + joinForm.JS_LANG.RelePwd + '</div>';			
		PwdHTML += '<div class="input_box"><div class="input_big l_input_box"><input type="password" id="password" autocomplete="off" /></div><div id="uEmail_box" class="r_input_box"> ' + joinForm.JS_LANG.AccountPwd + '</div></div>';			
	}
	
	if(pwd != -1 && msg != -1){
		PwdHTML += '<div>' + joinForm.JS_LANG.Or + '</div><br />';
	}else if(pwd == -1 && msg != -1){
		PwdHTML += '<div>' + joinForm.JS_LANG.ThirdAccountLogin + '</div><br />';
	}

	var OauthHTML = '';
	if(msg != -1){
		$.each(jsondata.data, function(idx,item){
			OauthHTML += '<li id="'+idx+'" class="def_partners_t"><dt class="'+item.icon+'">'+(item.icon?'':item.txt)+'</dt></li>';
		});

		OauthHTML = '<div class="def_partners_box_join"><ul id="def_partners_list_box">'+OauthHTML+'</ul></div></div>';
	}

	HTML = PwdHTML + OauthHTML;
	
	$("#thirdParty").html(HTML);
	PwdHTML = null;
	OauthHTML = null;
	HTML = null;
	
	//绑定点击事件
	$('.def_partners_t').click(function(){
		var thisid = this.id;
		$.get('/index/checkCookieUserID',{},function(data){
			location= '/index/loginThirdParty?provider='+thisid+'&idProvider=join';
		});  						
	});		
	
	//生成滚动条
	$('.def_partners_box_join').jScrollPane();        					
};