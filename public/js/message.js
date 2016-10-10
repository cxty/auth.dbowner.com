
function Tmsg(){
	this.JS_LANG = ''; //语言包
	this.keditor = '';
};
Tmsg.prototype.init = function(){
	$(function(){
		var toolObj = $('.user_profile_left_tool ul li');
		toolObj.click(function(){
			var index = toolObj.index(this);
			var id = toolObj.eq(index).attr('id');

			location = '/main/message?type='+id;
		});
	});
};