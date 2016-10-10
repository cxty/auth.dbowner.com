
function TJS_UserInfo(){
	this.u = null;
};
TJS_UserInfo.prototype.init = function(){
	var html = '';
	html += '<link href="' + this.u.css + '" rel="stylesheet" type="text/css" />';
	html += '<div id="ui_user">';
	if(this.u.uName){
		html += JS_UserInfo.showUserInfoBox();
	}else{
		html += JS_UserInfo.showLoginBox();
	}
	html += '</div>';
	$('#ui_userShell').html(html);


	$('#ui_user').hover(
		function(){		
			$('#ui_name').addClass('ui_userinfo');
			$('#ui_name').css({'border-top':'1px #49ACCF solid',
								'border-left':'1px #49ACCF solid',
								'border-right':'1px #49ACCF solid',
								'border-radius' : '5px 5px 0 0',
								'color' : '#446D96',
								'position' : 'relative',
								'z-index' : '11111',
							});
			$('#ui_content').show();
		},
		function(){
			$('#ui_name').removeClass('ui_userinfo');
			$('#ui_name').css({'border':'',
								'border-radius' : '',
								'-webkit-box-shadow' : '',
								'-moz-box-shadow' : '',
								'box-shadow' : '',
								'color' : '#fff',
							});
			$('#ui_content').hide();
		}
	);
	$('#ui_content').hover(
		function(){
			$('#ui_name').css({'background':'#fff'
			});
		},
		function(){
			$('#ui_name').css({'background':''
			});
		}
	);	

	JS_UserInfo.doloop();	
};
TJS_UserInfo.prototype.doloop = function(){
	if ( this.u.uName ) {
		$.ajax({
	          url: JS_UserInfo.u.root + "/provitejs/getunreadcount",
	          data:null,
	          type:"get",
	          async:false,
	          dataType:"jsonp",  // 此处必须要为jsonp,jsonp解决跨域使用的
	          jsonp: 'callback',
	          success:function(data){
	        	  $('#ui_unread').text('('+data+')');
	        	  
	        	  setTimeout("JS_UserInfo.doloop()", 10000 );
	           }
	       });	
	}    
};
TJS_UserInfo.prototype.showUserInfoBox = function(){
	var html = '';		
	html += '<div id="ui_name">' + this.u.uName + '</div>';
	html += '<div id="ui_content" style="display:none;';
	if(this.u.top){
		html += 'top:' + this.u.top + 'px;';
	}	
	html += '">';
	html += '<div class="ui_left">';
	html += '<img id="ui_header_img" src="' + this.u.portrait + '" />';
	html += '</div>';
	html += '<div class="ui_right">';
	html += '<div class="ui_li"><a href="' + this.u.root + '/main/index" target="_blank">' + this.u.JS_LANG.PensonalPage + '</a></div>';
	html += '<div class="ui_li"><a href="' + this.u.root + '/main/message" target="_blank">' + this.u.JS_LANG.PensonalMsg + '<span id="ui_unread">(' + this.u.unreadNum + ')</span></a></div>';
	html += '<div class="ui_li"><a id="loginout" class="ui_header_lo" href="javascript:void(0);" onclick="javascript:JS_UserInfo.loginout();" >' + this.u.JS_LANG.LoginOut + '</a></div>';
	html += '</div>';
	html += '</div>';
	
	if(this.u.top){
		$('#ui_content').css("top","150px");
	}
	
	return html;
};
TJS_UserInfo.prototype.showLoginBox = function(){
	var html = '';
	html +=  '<div id="ui_register">' + JS_UserInfo.u.JS_LANG.Register + '</div>';
	html +=  '<div id="ui_login">' + JS_UserInfo.u.JS_LANG.Login + '</div>';
		
	$(function(){
		if ( JS_UserInfo.u.lg ) {
			var tmpTag = 'https:' == document.location.protocol ? false : true;
			
			if ( tmpTag == true ) {
				redirect = 'http://' + location.hostname + '/login/login';
			} else {
				redirect = 'https://' + location.hostname + '/login/login';
			}
		} else {
			redirect = document.URL;
		}
		
		$('#ui_login').click(function(){
			location = JS_UserInfo.u.root + '/index/login?ident=userbox&redirect=' + encodeURIComponent(redirect);
		});
		$('#ui_register').click(function(){
			location = JS_UserInfo.u.root + '/index/register?ident=userbox&redirect=' + encodeURIComponent(redirect);
		});
	});
	return html;
};
TJS_UserInfo.prototype.loginout = function(){
	$.ajax({
        url: JS_UserInfo.u.root + '/index/loginOutByjs',
        data:null,
        type:"get",
        async:false,
        dataType:"jsonp",  // 此处必须要为jsonp,jsonp解决跨域使用的
        jsonp: 'callback',
        success:function(data){
        	window.location = location;
         }
     });
};

var JS_UserInfo = new TJS_UserInfo();
//页面完全载入后初始化
$(document).ready(function(){
	JS_UserInfo.u = js_u_ui;
	JS_UserInfo.init();
});
//释放
$(window).unload(function(){
	JS_UserInfo = null;
});