
function TUserProfile(){
	this.IMAGES = '';
	this.JS_LANG = '';
	this.oEmail = $('#contactInfo input').eq(1).val();
};
TUserProfile.prototype.init = function(){
	//设置全局配置
	$.jUploader.setDefaults({
	    cancelable: true,
	    allowedExtensions: ['jpg', 'png', 'gif'],
	    messages: {
	        upload: UserProfile.JS_LANG.Load,
	        cancel: UserProfile.JS_LANG.Cancel,
	        emptyFile: UserProfile.JS_LANG.EmptyFile,
	        invalidExtension: UserProfile.JS_LANG.InvalidExtension,
	        onLeave: UserProfile.JS_LANG.OnLeave
	    }
	});
	
	UserProfile.upfile_ini();
	
	$('#common_btn').click(function(){
		UserProfile.modifyClick('profileCommon');
	});
	
	$('#contact_btn').click(function(){
		UserProfile.modifyClick('profileContact');
	});
	
	$('#work_btn').click(function(){
		UserProfile.modifyClick('profileWork');
	});
	
	$('#education_btn').click(function(){
		UserProfile.modifyClick('profileEducation');
	});
	
	$('.li_show a').hide();
	$('.li_show').hover(
		function(){
			$('.li_show a').show();
		},
		function(){
			$('.li_show a').hide();
		}
	);
	
	$('#activateBtn').click(function(){
		UserProfile.doactive();
	});
	
	/*
	$oNickName = $('#CommonInfo input').eq(0).val();
	
	$('#contactInfo input').change(function(){
		var index = $('#contactInfo input').index(this);
		var tValue = $('#contactInfo input').eq(index).val();	
		if(index == 0){
			$.get('/main/saveContact',{type:'uComeFrom',uComeFrom:tValue,rnd:Math.random()},function(data){

	    	});  
		}else if(index == 1){
			//UserProfile.isEmail(index,tValue);
			UserProfile.isEmail(1,'');
		}		
	});
	
	$('#CommonInfo select').change(function(){
		var index = $('#CommonInfo select').index(this);

		if(index == 0){			
			var tValue = $('#CommonInfo select').eq(index).val();
			//$.get('/main/saveCommon',{type:'uSex',uSex:tValue,rnd:Math.random()},function(data){});
		}else{
			UserProfile.getChangeTime(index);
		}
		
	});
	
	$('#CommonInfo input').change(function(){
		var index = $('#CommonInfo input').index(this);
		var tValue = $('#CommonInfo input').eq(index).val();

		if(index == 0){
			$.post('/main/saveBaseInfo',{type:'uName',tValue:tValue,rnd:Math.random()},function(data){
					if(data == -1){
						Boxy.alert(
								UserProfile.JS_LANG.RepeatNickName, 
		           			function(){
		           				$('#CommonInfo input').eq(index).val($oNickName);
		           			}, 
		           			{title: UserProfile.JS_LANG.RemindMsg }
		                );
					}
				});
		}else if(index == 1){
			//$.get('/main/saveCommon',{type:'ubirthday',uBirthday:tValue,rnd:Math.random()},function(data){});
		}		
	});
	*/
};
TUserProfile.prototype.modifyClick = function(type){
	$.fancybox({
        type: 'iframe',
        href: '/main/'+type,
        scrolling: 'no',
        width: 760,
        height: 300,
        autoScale: false,
        centerOnScroll: true,
        hideOnContentClick: true,
        afterLoad : function(){

        }
    });
};
TUserProfile.prototype.closeFancybox = function(){
	$.fancybox.close();
};
TUserProfile.prototype.upfile_ini = function(){
	$.jUploader({
        button: 'user_ico_tool_bar', // 这里设置按钮id
        action: '/file/up-protrait', // 这里设置上传处理接口，这个加了参数test_cancel=1来测试取消
        onUpload: function (fileName) {
        	$.fancybox.showLoading();//显示loading提示框
        },
        // 上传完成事件
        onComplete: function (fileName, response) {
        	$.fancybox.hideLoading();//关闭loading提示框
            if (response.state) {
            	var _ico_url= 'http://file.dbowner.com/index.php?act=get&filecode='+response.data.filecode;
                $('.user_ico_b img').attr('src', _ico_url+'&w='+UserProfile.IMAGES.BIG);
                $('.user_ico_m img').attr('src', _ico_url+'&w='+UserProfile.IMAGES.MID);
                $('.user_ico_s img').attr('src', _ico_url+'&w='+UserProfile.IMAGES.SMA);
                $('#user_ico').attr('src',_ico_url+'&w='+UserProfile.IMAGES.MID);
                $('#header_img').attr('src',_ico_url+'&w='+UserProfile.IMAGES.MID);
            } else {
                Boxy.alert(
                	UserProfile.JS_LANG.LoadFail, 
           			function(){
           				
           			}, 
           			{title: UserProfile.JS_LANG.RemindMsg }
                );
            }
        }
    });
};
TUserProfile.prototype.workEdit = function(id){
	$.fancybox({
        type: 'iframe',
        href: '/main/profileWork?id='+id,
        scrolling: 'no',
        width: 760,
        height: 300,
        autoScale: false,
        centerOnScroll: true,
        hideOnContentClick: true,
        afterLoad : function(){

        }
    });
};
TUserProfile.prototype.workDele = function(id){
	Boxy.confirm( UserProfile.JS_LANG.ConfirmDelete ,
		function(){
			$.get('/main/delProfileWork', {AutoID:id, rnd:Math.random()}, function(data){
				$('#li_'+id).remove();
			});
		},
		{title: UserProfile.JS_LANG.RemindMsg }
	);	
};
TUserProfile.prototype.doactive = function(){
	var uEmail = $('#contactInfo input').eq(1).val();
	if(confirm( UserProfile.JS_LANG.SeConfirm )){
		$.get('/main/doactive/',{uEmail:uEmail,rnd:Math.random()},function(data){
			
		});
	}	
};
/*
TUserProfile.prototype.isEmail = function(index,uEmail) {	
	var uEmail = $('#contactInfo input').eq(index).val();
    var rules = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;//验证Mail的正则表达式,^[a-zA-Z0-9_-]:开头必须为字母,下划线,数字,
    if (rules.test(uEmail)) { 	
    	if(this.theEamil == uEmail){
    		$.get('/main/ReActivate',{uEmail:this.theEamil,rnd:Math.random()},function(data){  
    			Boxy.alert( UserProfile.JS_LANG.ReActivate ,function(){},{title: UserProfile.JS_LANG.RemindMsg });return;
	    	});		
    	}else{
	    	$.get('/main/checkEmail',{uEmail:uEmail,rnd:Math.random()},function(data){  
	    		if(data == -1){
	    			Boxy.confirm( UserProfile.JS_LANG.ConfirmEmail ,
	    					function(){
	    						UserProfile.mandEmail(uEmail,data);
	    					},
	    					{title: UserProfile.JS_LANG.RemindMsg }
	    				);
	    		}else{
	    			Boxy.alert( UserProfile.JS_LANG.ExchangeEmail ,
	    					function(){
	    						$('#contactInfo input').eq(index).val(UserProfile.oEmail).focus();
	    					},
	    					{title: UserProfile.JS_LANG.RemindMsg }
	    				);
	    			return;
	    		}
	    	});		
    	}
    } else {
    	Boxy.alert(
    			 UserProfile.JS_LANG.EffectiveEmail, 
    			function(){
    				$('#contactInfo input').eq(index).val('').focus();
    			}, 
    			{title: UserProfile.JS_LANG.RemindMsg }
    	);
    }
};
TUserProfile.prototype.mandEmail = function(uEmail,type){
	$.get('/main/saveContactEmail',{type:'uEmail',uEmail:uEmail,UserID:type,rnd:Math.random()},function(data){
		location = '?';
	});   
};
TUserProfile.prototype.getChangeTime = function(index){
	var type = $('#CommonInfo select').eq(index).attr('name');
	var year = $('#CommonInfo select').eq(1).val();
	var month = $('#CommonInfo select').eq(2).val();
	var day = $('#CommonInfo select').eq(3).val();

	$.get('/main/changeTime',{type:type,year:year,month:month,day:day,rnd:Math.random()},function(data){	
		if(data == -1){
			Boxy.alert(
					UserProfile.JS_LANG.SelectYear, 
		   			function(){

		   			}, 
		   			{title: UserProfile.JS_LANG.RemindMsg }
				);
		}else{
			if(type == 'year'){
				$('#CommonInfo select').eq(2).html(data.month);
				$('#CommonInfo select').eq(3).html(data.day);
			}else if(type == 'month'){
				$('#CommonInfo select').eq(3).html(data.day);
			}
			
		}	
	}
	,'json'
	);
};
*/