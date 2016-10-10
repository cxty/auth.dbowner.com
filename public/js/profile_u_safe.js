
function TuserSafe(){
	this.JS_LANG = '';
};
TuserSafe.prototype.init = function(){
	$('#appAuth li dt select').change(function(){
		var index = $('#appAuth li dt select').index(this);
		var tValue = $('select').eq(index).val();
		$.get('/main/updateAuthApp/',{type:index,tValue:tValue,rnd:Math.random()},function(data){
			
		});
	});
	
	$('#AccountSafe input').change(function(){
		var index = $('#AccountSafe li dt input').index(this);
		var tValue = $('input').eq(index).val();
		if(index == 0){
			userSafe.isEmail(index,tValue);
		}else if(index == 1){
			userSafe.isTel(index,tValue);
		}
	});
	
	$('#safeRealName').click(function(){
		userSafe.clickSafeAuth('safeRealName');
	});
	$('#safeEmail').click(function(){
		userSafe.clickSafeAuth('safeEmail');
	});
	$('#safePhone').click(function(){
		userSafe.clickSafeAuth('safePhone');
	});
};
TuserSafe.prototype.clickSafeAuth = function(type){
	$.fancybox({
        type: 'iframe',
        href: '/main/'+type,
        scrolling: 'no',
        width: 760,
        height: 480,
        autoScale: false,
        centerOnScroll: true,
        hideOnContentClick: true,
        afterLoad : function(){

        }
    });
};
TuserSafe.prototype.isEmail = function(index,eMail) {
    var rules = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;//验证Mail的正则表达式,^[a-zA-Z0-9_-]:开头必须为字母,下划线,数字,
    if (rules.test(eMail)) {
    	$.get('/main/checkSafeEmail',{uSafeEmail:eMail,rnd:Math.random()},function(data){
    	});    	
    } else {
    	Boxy.alert(
    			userSafe.JS_LANG.EffectiveEmail, 
    			function(){
    				$('input').eq(index).val('').focus();
    			}, 
    			{title: userSafe.JS_LANG.RemindMsg }
    	);
    }
};
TuserSafe.prototype.isTel = function(index,tel) {
    var rules = /^1[3,5]\d{9}$/;
    if(rules.test(tel)){
    	$.get('/main/checkSafePhone',{uSafePhone:tel,rnd:Math.random()},function(data){

    	});    	
    }else{
    	Boxy.alert(
    			userSafe.JS_LANG.EffectivePhone, 
    			function(){
    				$('input').eq(index).val('').focus();
    			}, 
    			{title: userSafe.JS_LANG.RemindMsg }
    	);
    }
};
TuserSafe.prototype.closeFancybox = function(){
	$.fancybox.close();
};
//var _box;
TuserSafe.prototype.domodify = function(type,title,pwd){
	$.fancybox({
        type: 'iframe',
        href: '/main/resetpwd',
        scrolling: 'no',
        width: 760,
        height: 480,
        autoScale: false,
        centerOnScroll: true,
        helpers: {
	        overlay:{
	        	closeClick: false
	        }
	    },
        afterLoad : function(){
        }
    }); 
};