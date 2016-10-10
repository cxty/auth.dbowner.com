
function TLoginAuth(){
	this.PH_LANG = '';
	this.sData = '';
	
	this.def_partners_margin_left = '';
	this.def_partners_box_width = '';
	this.def_partners_list_box_width = '';
	this.def_partners_t = '';
	this.jspPane_width = '';
};
TLoginAuth.prototype.init = function(){
	//初始化界面
	if ( this.sData.width ) {
		$('.container_box').css({'width': this.sData.width + 'px'});
		this.def_partners_margin_left = 'margin-left:' + (this.sData.width - 70) + 'px;';
		this.def_partners_box_width = 'width:' + (this.sData.width - 50) + 'px;';
		this.def_partners_list_box_width = 'width:' + (this.sData.width - 60) + 'px';
		this.jspPane_width = 'width:' + (this.sData.width - 60) + 'px';
		this.def_partners_t = 'margin:6px 0;';
	}
	if ( this.sData.showLogo === true ) {
		$('.narrow_top_self').css({'padding-top':'10px'});
	}
	
	var width = '';
	if ( this.sData.width ) {
		
	}
	
	$('#uEmail').change(function(){
		var uEmail = $('#uEmail').val();
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
    	if(!reg.test(uEmail)){
    		Boxy.alert( LoginAuth.PH_LANG.EmailForm ,function(){$('#uEmail').val('').focus();},{title: LoginAuth.PH_LANG.Remind ,modal:true,unloadOnHide:true});	
    	}
    });
	
	$('#auth_pro').click(function(){
		var _box = new Boxy($('#auth_box_msg').html(),{title: LoginAuth.PH_LANG.UseRule ,center:true,modal:true,closeText: LoginAuth.PH_LANG.TurnOff ,unloadOnHide:true},function(val){});

		if ( $('.container_box').attr('vtype') == 'web_narrow' ) {
			 _box.tween(400,400);
		} else {
			 _box.tween($(document).width()-400,$(document).height()-400);
		}
	    
	    $('#auth_pro_check').attr('checked',true);
	    
	    return false;
	});
	
	$('#submit_btn').click(function(){
        if($.trim($('#uEmail').val()) == ''){
           Boxy.alert( LoginAuth.PH_LANG.RightEmail ,function(){$('#uEmail').focus();},{title: LoginAuth.PH_LANG.Remind ,modal:true,unloadOnHide:true});            
        }else if($.trim($('#password').val()) == ''){
        	 Boxy.alert( LoginAuth.PH_LANG.WrongPwd ,function(){$('#password').focus();},{title: LoginAuth.PH_LANG.Remind ,modal:true,unloadOnHide:true});
        }else if($('#auth_pro_check').attr('checked') != 'checked'){
        	var _box = new Boxy($('#auth_box_msg').html(),{title: LoginAuth.PH_LANG.UseRule ,center:true,modal:true,closeText: LoginAuth.PH_LANG.TurnOff ,unloadOnHide:true},function(val){});

    	    _box.tween($(document).width()-400,$(document).height()-400);
    	    
        	$('#auth_pro_check').attr('checked',true);
        	
        	return false;
        }else{
        	$.post('/index/checkLogin',{uEmail:$('#uEmail').val(),uPWD:$('#password').val(),rnd:Math.random()},
        			function(data){  
        				if(data > 0){
    						$('#UserID').val(data);

    						$('#loginform').submit();    						
    					}else if(data == -1){   
    						Boxy.alert( LoginAuth.PH_LANG.LoginWrongRemind ,function(){$('#uEmail').val('').focus();$('#password').val('');},{title: LoginAuth.PH_LANG.Remind ,modal:true,unloadOnHide:true});      					
    						$('#password').prevAll('.def_txt').show();	
    					}	
        			}
        	);     
        }
    });
	
	$('#button_btn').click(function(){
		$('#uEmail').val('');
		$('#password').val('');
		$('#uEmail').prevAll('.def_txt').show();
		$('#password').prevAll('.def_txt').show();		
	});
	
	//邮箱上层提示信息
    $('#uEmail').val('').before(jQuery('<div class="def_txt">'+LoginAuth.PH_LANG.LoginEmail+'</div>').click(function(){
        $(this).hide();
        $('#uEmail').focus();
    }));
	//邮箱上层右侧第三方平台登录按钮
	$('#uEmail').before(jQuery('<div class="def_partners" fx="0" style="' + this.def_partners_margin_left + '"></div>').click(function(){
		if($(this).attr('fx') == 0){
			LoginAuth.showPartnersBox();
		}else{
			LoginAuth.hidePartnersBox();
		}
	}));
	
    $('#password').val('').before(jQuery('<div class="def_txt">'+LoginAuth.PH_LANG.LoginPwd+'</div>').click(function(){
        $(this).hide();
        $('#password').focus();
    }));
    
    $('#uEmail').focusin(function(){
            $(this).prevAll('.def_txt').hide();
    });
    $('#uEmail').focusout(function(){
        if($(this).val()==''){
            $(this).prevAll('.def_txt').show();
        }else{
            $(this).prevAll('.def_txt').hide();
        }
    });
    
    $('#password').focusin(function(){
            $(this).prevAll('.def_txt').hide();
    });
    $('#password').focusout(function(){  	
        if($(this).val()==''){
            $(this).prevAll('.def_txt').show();
        }else{
            $(this).prevAll('.def_txt').hide();
        }
    }); 
};
TLoginAuth.prototype.showPartnersBox = function(){	
	$(".def_partners").rotate({animateTo:-90});

	$(".def_partners").attr('fx',1);
	
	$(".def_partners").after(jQuery('<div class="def_partners_box" style="' + this.def_partners_box_width + '"><div class="def_partners_title">' + LoginAuth.PH_LANG.ThirdAccount + '</div>'+LoginAuth.buildPartnersBox()+'</div>'));
	
	//生成滚动条
	$('#def_partners_list_box').jScrollPane();
	
	if ( this.jspPane_left ) {
		$('.jspPane').css({'left':'4px'});
		//jspPane_width
	}
	
	//绑定点击事件
	$('.def_partners_t').click(function(){

		location= '/index/loginThirdParty?provider='+this.id+'&idProvider=dirauth&display=web';
	});
	
	$('.def_partners_box').hover(function(e){LoginAuth.P_Box_CloseOK = false;},function(e){LoginAuth.P_Box_CloseOK = true;});
	
};
TLoginAuth.prototype.buildPartnersBox = function(){
	var tHTML = '';
	if(this.Partners_json){
		$.each(this.Partners_json,function(idx,item){
			if (idx != 0) {
				tHTML += '<li id="'+idx+'" class="def_partners_t" style="' + LoginAuth.def_partners_t + '"><dt class="'+item.icon+'">'+(item.icon?'':item.txt)+'</dt></li>';
			}
		});
		
		tHTML = tHTML?'<ul id="def_partners_list_box" style="' + this.def_partners_list_box_width + '">'+tHTML+'</ul>':'';
	}
	return tHTML;
};
TLoginAuth.prototype.hidePartnersBox = function(){
	$(".def_partners").rotate({animateTo:0});

	$(".def_partners").attr('fx',0);
	
	$('.def_partners_box').remove();
	LoginAuth.P_Box_CloseOK = false;
};
TLoginAuth.prototype.showProBox = function(){
	var _box = new Boxy($('#box_body_msg').html(),{title: LoginAuth.PH_LANG.UseRule ,center:true,modal:true,closeText: LoginAuth.PH_LANG.TurnOff ,unloadOnHide:true},function(val){});

    _box.tween($(document).width()-400,$(document).height()-400);
    
    return false;
};