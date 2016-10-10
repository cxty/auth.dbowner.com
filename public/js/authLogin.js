

function TloginForm(){
    this.ns = '';
    this.def_uEmail_txt = '';
    this.def_password_txt = '';
    this.JS_LANG = '';
};
TloginForm.prototype.ini = function(){
	this.def_uEmail_txt = this.JS_LANG.Email;
	this.def_password_txt = this.JS_LANG.LoginPwd;
	
	$('#'+loginForm.ns+'uEmail').change(function(){
		var uEmail = $('#'+loginForm.ns+'uEmail').val();
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
    	if(!reg.test(uEmail)){
    		Boxy.alert( this.JS_LANG.EmailForm ,function(){$('#'+loginForm.ns+'uEmail').val('').focus();},{title: this.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
    	}
    });
	
    $('#'+this.ns+'submit_btn').click(function(){
        if($.trim($('#'+loginForm.ns+'uEmail').val()) == ''){
           Boxy.alert( this.JS_LANG.RightEmail ,function(){$('#'+loginForm.ns+'uEmail').focus();},{title: this.JS_LANG.Remind ,modal:true,unloadOnHide:true});
            
        }else if($.trim($('#'+loginForm.ns+'password').val()) == ''){
        	 Boxy.alert( this.JS_LANG.WrongPwd ,function(){$('#'+loginForm.ns+'password').focus();},{title: this.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        }else{
        	var uEmail = $('#'+loginForm.ns+'uEmail').val();
        	var password = $.trim($('#'+loginForm.ns+'password').val());     	
        	$.get('/oauth/checkEmail',{uEmail:uEmail,password:password,rnd:Math.random()},
        			function(data){ 
    					if(data == -1){
    						Boxy.alert( loginForm.JS_LANG.LoginWrongRemind ,function(){$('#'+loginForm.ns+'uEmail').val('').focus();$('#'+loginForm.ns+'password').val('');},{title: loginForm.JS_LANG.Remind ,modal:true,unloadOnHide:true});      					
    					}else{
    						$('#'+loginForm.ns+'UserID').val(data);
    						$('#loginform').submit();
    				    }	
        			}
        	);
            
        }
    });
    //邮箱上层提示信息
    $('#'+this.ns+'uEmail').before(jQuery('<div class="def_txt">'+this.def_uEmail_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+loginForm.ns+'uEmail').focus();
    }));
	//邮箱上层右侧第三方平台登录按钮
	$('#'+this.ns+'uEmail').before(jQuery('<div class="def_partners" fx="0"></div>').click(function(){
		if($(this).attr('fx') == 0)
		{
			loginForm.showPartnersBox();
		}else{
			loginForm.hidePartnersBox();
		}
	}));
	
    $('#'+this.ns+'password').before(jQuery('<div class="def_txt">'+this.def_password_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+loginForm.ns+'password').focus();
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
    
    $('#'+this.ns+'password').focusin(function(){
            $(this).prevAll('.def_txt').hide();
    });
    $('#'+this.ns+'password').focusout(function(){
    	
        if($(this).val()=='')
        {
            $(this).prevAll('.def_txt').show();
        }else{
            $(this).prevAll('.def_txt').hide();
        }
    });
    

	$(document).click(function(e){
		if(loginForm.P_Box_CloseOK)
		{
			loginForm.hidePartnersBox();
		}
	});
	$(document).ready( function () { 
		$('#'+loginForm.ns+'uEmail').focusout();
		$('#'+loginForm.ns+'password').focusout();
	});
};
TloginForm.prototype.showPartnersBox = function(){
	$(".def_partners").rotate({animateTo:-90});

	$(".def_partners").attr('fx',1);
	
	$(".def_partners").after(jQuery('<div class="def_partners_box"><div class="def_partners_title">' + this.JS_LANG.ThirdAccount + '</div>'+loginForm.buildPartnersBox()+'</div>'));
	
	//生成滚动条
	$('#def_partners_list_box').jScrollPane();
	
	var turnUrl = this.turnUrl; //定义一个变量，存放地址

	//绑定点击事件
	$('.def_partners_t').click(function(){
		location= turnUrl+'/index/loginThirdParty-'+this.id+'-dirauth.html';
	});
	
	$('.def_partners_box').hover(function(e){loginForm.P_Box_CloseOK = false;},function(e){loginForm.P_Box_CloseOK = true;});
	
};
TloginForm.prototype.buildPartnersBox = function(){
	var tHTML = '';
	if(this.Partners_json){
		$.each(this.Partners_json,function(idx,item){
			if (idx != 0) {
				tHTML += '<li id="'+idx+'" class="def_partners_t"><dt class="'+item.icon+'">'+(item.icon?'':item.txt)+'</dt></li>';
			}
		});
		
		tHTML = tHTML?'<ul id="def_partners_list_box">'+tHTML+'</ul>':'';
	}
	return tHTML;
};
TloginForm.prototype.hidePartnersBox = function(){
	$(".def_partners").rotate({animateTo:0});

	$(".def_partners").attr('fx',0);
	
	$('.def_partners_box').remove();
	loginForm.P_Box_CloseOK = false;
};