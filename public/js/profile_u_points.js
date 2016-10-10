
function TUpoints(){
	this.JS_LANG = '';
};
TUpoints.prototype.init = function(){
	
};
function checkInter(type,title){
	if(type == 'PointRecord'){
		var content = $('#pointRecord').html();
		var _box;
		_box = new Boxy(content,{title:title,center:true,modal:true,closeText:'关闭',unloadOnHide:true},function(){});
	    _box.tween($(document).width()-800,$(document).height()-500); 
	}else if(type == 'PointChange'){
		Boxy.confirm("您确认要兑换:", function() {  }, {title: title});
	}

    //_box.confirm('aaa');
	//Boxy.alert("文件未找到", null, {title: "提示信息"});
};