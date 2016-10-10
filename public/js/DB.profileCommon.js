
//author:wbqing405@sina.com

function TprofileCommon(){
	this.JS_LANG = '';
};
TprofileCommon.prototype.init = function(){
	$('#chooseBirthday select').each(function(){
		$(this).change(function(){
			profileCommon.getChangeTime($('#chooseBirthday select').index(this));
		});
	});
	$('#submit_btn').click(function(){
		profileCommon.subClick();
	});
};
TprofileCommon.prototype.subClick = function(){
	$.get('/main/saveCommon', {nickName:$('#nickName').val(),sex:$('#sex').val(),year:$('select[name="year"]').val(),month:$('select[name="month"]').val(),day:$('select[name="day"]').val(),rnd:Math.random()}, function(data){
		if(data == -1){
			Boxy.alert(
					profileCommon.JS_LANG.Ex_NotEmptyNickName, 
		   			function(){
						$('#nickName').val('').focus();
		   			}, 
		   			{title: profileCommon.JS_LANG.RemindMsg }
				);
		}else if(data == -2){
			Boxy.alert(
					profileCommon.JS_LANG.Ex_NotEmptyYear, 
		   			function(){

		   			}, 
		   			{title: profileCommon.JS_LANG.RemindMsg }
				);
		}else if(data == -3){
			Boxy.alert(
					profileCommon.JS_LANG.Ex_NotEmptyMonth, 
		   			function(){

		   			}, 
		   			{title: profileCommon.JS_LANG.RemindMsg }
				);
		}else if(data == -4){
			Boxy.alert(
					profileCommon.JS_LANG.Ex_ExsitNickName, 
		   			function(){
						$('#nickName').val('').focus();
		   			}, 
		   			{title: profileCommon.JS_LANG.RemindMsg }
				);
		}else if(data == -5){
			Boxy.alert(
					profileCommon.JS_LANG.Ex_SystemUserTimeout, 
		   			function(){
						$('#nickName').val('').focus();
		   			}, 
		   			{title: profileCommon.JS_LANG.RemindMsg }
				);
		}else{
			parent.location.reload();
		}	
	});
};
TprofileCommon.prototype.getChangeTime = function(index){
	var type = $('#chooseBirthday select').eq(index).attr('name');
	
	$.get('/main/changeTime',{type:type, year:$('select[name="year"]').val(), month:$('select[name="month"]').val(), day:$('select[name="day"]').val(), rnd:Math.random()},function(data){	
		if(data == -1){
			Boxy.alert(
					profileCommon.JS_LANG.SelectYear, 
		   			function(){

		   			}, 
		   			{title: profileCommon.JS_LANG.RemindMsg }
				);
		}else{
			if(type == 'year'){
				$('select[name="month"]').html(data.data.month);
				$('select[name="day"]').html(data.data.day);
			}else if(type == 'month'){
				$('select[name="day"]').html(data.data.day);
			}
			
		}	
	}
	,'json'
	);
};