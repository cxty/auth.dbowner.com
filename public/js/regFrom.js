
function TregFrom(){
    this.ns = '';
    this.def_uEmail_txt = '';
    this.def_password_txt = '';
    this.def_uName_txt = '';
    this.JS_LANG = '';
};
TregFrom.prototype.init = function(){
	this.def_uEmail_txt = this.JS_LANG.Email;
	this.def_password_txt = this.JS_LANG.LoginPwd;
	this.def_uName_txt = this.JS_LANG.Nickname;
	
    $('.box_body').click(function(e){
        var _box = new Boxy($('#box_body_msg').html(),{title: regFrom.JS_LANG.UseRule ,center:true,modal:true,closeText: regFrom.JS_LANG.TurnOff ,unloadOnHide:true},function(val){
            
        });
        
        _box.tween($(document).width()-400,$(document).height()-400);
        
//    	$.fancybox({
//    		width: 760,
//            height: 480,
//    		content: $('#box_body_msg').html()
//    	});
        
        return false;
    });
    
    $('#'+this.ns+'submit_btn').click(function(){
        if($.trim($('#'+regFrom.ns+'uEmail').val()) == ''){
            Boxy.alert( regFrom.JS_LANG.RightEmail ,function(){$('#'+regFrom.ns+'uEmail').focus();},{title: regFrom.JS_LANG.Remind ,modal:true,unloadOnHide:true});
            
        }else if($.trim($('#'+regFrom.ns+'password').val()) == ''){
            Boxy.alert( regFrom.JS_LANG.WritePwd ,function(){$('#'+regFrom.ns+'password').focus();},{title: regFrom.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        }else if($.trim($('#'+regFrom.ns+'uName').val()) == ''){
            Boxy.alert( regFrom.JS_LANG.RightNickname ,function(){$('#'+regFrom.ns+'uName').focus();},{title: regFrom.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        }else{
        	var uEmail = $('#'+regFrom.ns+'uEmail').val();
        	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
        	var uName = $.trim($('#'+regFrom.ns+'uName').val());
        	
        	if(reg.test(uEmail)){
        		$.post('/index/checkEmail.html',{uEmail:uEmail,uName:uName,rnd:Math.random()},
            			function(data){  
        					if(data.uEmail != -1){
        						Boxy.alert( uEmail + '  ' + regFrom.JS_LANG.Registered ,function(){$('#'+regFrom.ns+'uEmail').val('').focus();},{title: regFrom.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        					}else if(data.uName != -1){
        						Boxy.alert( uName + '  ' + regFrom.JS_LANG.Registered ,function(){$('#'+regFrom.ns+'uName').val('').focus();},{title: regFrom.JS_LANG.Remind ,modal:true,unloadOnHide:true});
        					}else{
        						$('#regform').submit();
        					}
            			},'json'
            	);
        	}else{
        		Boxy.alert( regFrom.JS_LANG.EmailForm ,function(){$('#'+regFrom.ns+'uEmail').val('').focus();},{title: regFrom.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
        	}
        }
    });
    
    $('#'+this.ns+'uEmail').before(jQuery('<div class="def_txt">'+this.def_uEmail_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+regFrom.ns+'uEmail').focus();
    }));
    $('#'+this.ns+'password').before(jQuery('<div class="def_txt">'+this.def_password_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+regFrom.ns+'password').focus();
    }));
    $('#'+this.ns+'uName').before(jQuery('<div class="def_txt">'+this.def_uName_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+regFrom.ns+'uName').focus();
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

    $('#'+regFrom.ns+'uEmail').focusout();
	$('#'+regFrom.ns+'uName').focusout();
	$('#'+regFrom.ns+'password').focusout(); 
};