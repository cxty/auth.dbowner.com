/*@cxty*/

function TloginForm(){
    this.ns = '';
    this.def_uEmail_txt = '';
    this.def_password_txt = '';
    this.loginType = new Array('pwd','tl','qr');
    this.JS_LANG = ''; //语言包
    this.loginWay = ''; //登录初始化
	this.P_Box_CloseOK = false; //是否显示,并可以关闭第三方登录框
	this.jData = null;
	this.vData = null;
	this.page_type = 1; //1为正常页面，2为内嵌页面
	this.isClick = true; //避免重复点击
};
TloginForm.prototype.init = function(){
	this.def_uEmail_txt = this.JS_LANG.Email;
	this.def_password_txt = this.JS_LANG.LoginPwd;
	
	if ( loginForm.page_type == 2 ) {
		loginForm.changeLoginWayIframe(); //初始化登录界面
	} else {
		loginForm.changeLoginWay(this.loginWay.loginNum, this.loginWay.loginType); //初始化登录界面
	}
	
	//绑定点击事件
	var Obj_lg = $('#lg_ct_list_box li');
	Obj_lg.each(function(){
		Obj_lg.eq($(this).index()).click(function(){
			location= loginForm.vData.host + '/index/loginThirdParty?provider='+$(this).attr("id");
		});
	});
	
	document.onkeydown = function(e){ 
	    var ev = document.all ? window.event : e;
	     
	    if(ev.keyCode == 13 && loginForm.isClick == true ) {
	    //if ( ev.keyCode == 13 ) {
	    	loginForm.isClick = false;
	    	loginForm.btnClick();
	     }
	};
	
    $('#'+this.ns+'submit_btn').click(function(){
    	loginForm.btnClick();
    });
    
    //邮箱上层提示信息
    $('#'+this.ns+'uEmail').val('').before(jQuery('<div class="def_txt">'+this.def_uEmail_txt+'</div>').click(function(){
        $(this).hide();
        $('#'+loginForm.ns+'uEmail').focus();
    }));
    
    /*
	//邮箱上层右侧第三方平台登录按钮
	$('#'+this.ns+'uEmail').before(jQuery('<div class="def_partners" fx="0"></div>').click(function(){
		if($(this).attr('fx') == 0){
			loginForm.showPartnersBox();
		}else{
			loginForm.hidePartnersBox();
		}
	}));
	*/
	
    $('#'+this.ns+'password').val('').before(jQuery('<div class="def_txt">'+this.def_password_txt+'</div>').click(function(){
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
    	
        if($(this).val()==''){
            $(this).prevAll('.def_txt').show();
        }else{
            $(this).prevAll('.def_txt').hide();
        }
    });
    
    /*
	$(document).click(function(e){
		if(loginForm.P_Box_CloseOK){
			loginForm.hidePartnersBox();
		}
	});
	*/
	$(document).ready( function () { 
		$('#'+loginForm.ns+'uEmail').focusout();
		$('#'+loginForm.ns+'password').focusout();
	});
	
	loginForm.checkPhoneLogin(); //检查手机是否已经登录过
};
TloginForm.prototype.btnClick = function(){
	if($.trim($('#'+loginForm.ns+'uEmail').val()) == ''){
        Boxy.alert(loginForm.JS_LANG.RightEmail,function(){
        		$('#'+loginForm.ns+'uEmail').focus();
        	},
        	{title: loginForm.JS_LANG.Remind,modal:true,unloadOnHide:true,afterHide:function(){
        		loginForm.isClick=true;
        	}});        
     }else if($.trim($('#'+loginForm.ns+'password').val()) == ''){
         $('#'+loginForm.ns+'password').focus();
         loginForm.isClick=true;
     }else{   
    	//var url = this.vData.type == 'iframe' ? loginForm.vData.domain + '/iframe/checkLogin' : loginForm.vData.host + '/index/checkLogin';
    	loginForm.isClick=true;
    	
    	$('#login_form').submit();
    	
    	return;
    	
    	$.fancybox.showLoading();
     	$.post(url,{uEmail:$('#'+loginForm.ns+'uEmail').val(),uPWD:$('#'+loginForm.ns+'password').val(),remusrname:$('#'+loginForm.ns+'remusrname').attr('checked'),rnd:Math.random()},
     		function(data){
	     		loginForm.isClick=true;
	     		$.fancybox.hideLoading();
     			if(data > 0){
     				//$('#loginform').submit();
     				location = location;
     			}else{
     				Boxy.alert(loginForm.JS_LANG.LoginWrongRemind ,function(){$('#'+loginForm.ns+'uEmail').val('');$('#'+loginForm.ns+'password').val('');$('#'+loginForm.ns+'uEmail').focus();},{title: loginForm.JS_LANG.Remind ,modal:true,unloadOnHide:true});
     			}
     		}
     	);
     }
};
/*
TloginForm.prototype.showPartnersBox = function(){
	$(".dethis.JS_LANGners").rotate({animateTo:-90});

	$(".def_partners").attr('fx',1);
	
	$(".def_partners").after(jQuery('<div class="def_partners_box"><div class="def_partners_title">'+loginForm.JS_LANG.ThirdAccount+'</div>'+loginForm.buildPartnersBox()+'</div>'));
	
	//生成滚动条
	$('#def_partners_list_box').jScrollPane();

	//绑定点击事件
	$('.def_partners_t').click(function(){
		location= '/index/loginThirdParty?provider='+this.id;
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
*/
TloginForm.prototype.checkPhoneLogin = function(){
	return;
	$.get( loginForm.vData.host + '/login/checkPhoneLogin',{rnd:Math.random()},function(data){
		if(data == 1){
			location = loginForm.vData.host +  '/index/loginCallBack';
		}else{
			setTimeout(loginForm.checkPhoneLogin, 2000);
		}	
	});	
};
TloginForm.prototype.changeLoginWay = function(num, type){
	//登录方式切换
	$('#lg_type li a').eq(0).removeClass(this.loginType[0]+'_cur');
	$('#lg_type li a').eq(1).removeClass(this.loginType[1]+'_cur');
	$('#lg_type li a').eq(2).removeClass(this.loginType[2]+'_cur');
	$('#lg_type li a').eq(num).addClass(type+'_cur');
	for(var i=0;i<this.loginType.length;i++){
		$('#lg_ct_'+i).hide();
	}
	$('#lg_ct_'+num).show();
	
	//左边说明
	switch(type){
		case 'pwd':
			$('#exp_ct').text(this.JS_LANG.Ep_Login_Pwd);
			break;
		case 'tl':
			$('#exp_ct').text(this.JS_LANG.Ep_Login_Third);
			break;
		case 'qr':
			$('#exp_ct').text(this.JS_LANG.Ep_Login_Qr);
			break;
	}
	
	//生成滚动条
	if(type == 'tl'){		
		$('#lg_ct_list_box').jScrollPane();
	}
};
TloginForm.prototype.changeLoginWayIframe = function(){
	var obj = $('#lg_type .lg_top_item');
	var len = $('#lg_type .lg_top_item').length;
	obj.click(function(){
		var ind = obj.index(this);
		for(var i=0;i<len;i++){
			$('#lg_ct_'+i).hide();
			$('#lg_type .lg_top_item').removeClass('lg_top_on');
		}
		$('#lg_ct_'+ind).show();
		$('#lg_type .lg_top_item').eq(ind).addClass('lg_top_on');
		
		//生成滚动条
		if(ind == 1){		
			$('#lg_ct_list_box').jScrollPane();
		}
	});
};