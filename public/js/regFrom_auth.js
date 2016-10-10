
function TregFromAuth(){
    this.ns = '';
    this.def_uEmail_txt = '';
    this.def_password_txt = '';
    this.def_uName_txt = '';
    this.JS_LANG = '';
    
    this.sData = '';
    this.rule_width = 400;
};
TregFromAuth.prototype.init = function(){
	//初始化界面
	if ( this.sData.width ) {
		$('.container_box').css({'width': this.sData.width + 'px'});
		this.rule_width = this.sData.width - 60;
	}
	if ( this.sData.showLogo === true ) {
		$('.narrow_sp_top').css({'padding-top':'10px'});
	}
	
	
	this.def_uEmail_txt = this.JS_LANG.Email;
	this.def_password_txt = this.JS_LANG.LoginPwd;
	this.def_uName_txt = this.JS_LANG.Nickname;

    $('.check_btn').click(function(e){
        var _box = new Boxy($('#use_rule').html(),{title: regFromAuth.JS_LANG.UseRule ,center:true,modal:true,closeText: regFromAuth.JS_LANG.TurnOff ,unloadOnHide:true},function(val){
            
        });

        _box.tween(regFromAuth.rule_width, 300);
        
        return false;
    });
    
    $('#'+this.ns+'submit_btn').click(function(){
        if($.trim($('#'+regFromAuth.ns+'uEmail').val()) == ''){
            Boxy.alert( regFromAuth.JS_LANG.RightEmail ,function(){$('#'+regFromAuth.ns+'uEmail').focus();},{title: regFromAuth.JS_LANG.Remind ,modal:true,unloadOnHide:true});
            
        }else if($.trim($('#'+regFromAuth.ns+'password').val()) == ''){
            Boxy.alert( regFromAuth.JS_LANG.WritePwd ,function(){$('#'+regFromAuth.ns+'password').focus();},{title: regFromAuth.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        }else if($.trim($('#'+regFromAuth.ns+'uName').val()) == ''){
            Boxy.alert( regFromAuth.JS_LANG.RightNickname ,function(){$('#'+regFromAuth.ns+'uName').focus();},{title: regFromAuth.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        }else{
        	var uEmail = $('#'+regFromAuth.ns+'uEmail').val();
        	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
        	var uName = $.trim($('#'+regFromAuth.ns+'uName').val());
        	
        	if(reg.test(uEmail)){
        		$.post('/index/checkEmail.html',{uEmail:uEmail,uName:uName,rnd:Math.random()},
            			function(data){  
        					if(data.uEmail != -1){
        						Boxy.alert( uEmail + '  ' + regFromAuth.JS_LANG.Registered ,function(){$('#'+regFromAuth.ns+'uEmail').val('').focus();},{title: regFromAuth.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        					}else if(data.uName != -1){
        						Boxy.alert( uName + '  ' + regFromAuth.JS_LANG.Registered ,function(){$('#'+regFromAuth.ns+'uName').val('').focus();},{title: regFromAuth.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        					}else{
        						$('#regform').submit();
        					}
            			},'json'
            	);
        	}else{
        		Boxy.alert( regFromAuth.JS_LANG.EmailForm ,function(){$('#'+regFromAuth.ns+'uEmail').val('').focus();},{title: regFromAuth.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
        	}
        }
    });
    $('#'+this.ns+'button_btn').click(function(){
    	$('#'+regFromAuth.ns+'uEmail').val('').prevAll().show();
    	$('#'+regFromAuth.ns+'password').val('').prevAll().show();
    	$('#'+regFromAuth.ns+'uName').val('').prevAll().show();
    });
    
    $('#'+this.ns+'uEmail').before(jQuery('<div class="def_txt">'+this.def_uEmail_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+regFromAuth.ns+'uEmail').focus();
    }));
    $('#'+this.ns+'password').before(jQuery('<div class="def_txt">'+this.def_password_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+regFromAuth.ns+'password').focus();
    }));
    $('#'+this.ns+'uName').before(jQuery('<div class="def_txt">'+this.def_uName_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+regFromAuth.ns+'uName').focus();
    }));
    
    $('#'+this.ns+'uEmail').focusin(function(){
            $(this).prevAll().hide();
    });
    $('#'+this.ns+'uEmail').focusout(function(){
        if($(this).val()==''){
            $(this).prevAll().show();
        }else{
            $(this).prevAll().hide();
        }
    });
    
    $('#'+this.ns+'password').focusin(function(){
            $(this).prevAll().hide();
    });
    $('#'+this.ns+'password').focusout(function(){
        if($(this).val()==''){
            $(this).prevAll().show();
        }else{
            $(this).prevAll().hide();
        }
    });
    
    $('#'+this.ns+'uName').focusin(function(){
            $(this).prevAll().hide();
    });
    $('#'+this.ns+'uName').focusout(function(){
        if($(this).val()=='')
        {
            $(this).prevAll().show();
        }else{
            $(this).prevAll().hide();
        }
    });

    $('#'+regFromAuth.ns+'uEmail').focusout();
	$('#'+regFromAuth.ns+'uName').focusout();
	$('#'+regFromAuth.ns+'password').focusout();
};