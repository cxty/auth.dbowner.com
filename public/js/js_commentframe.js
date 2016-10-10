
function Tcommentframe(){
	this.JData = '';
};
Tcommentframe.prototype.init = function(){	
	commentframe.doloop();	
};
Tcommentframe.prototype.doloop = function(){
	$.ajax({
        url: this.JData.root + '/comment/changeCommentHeight',
        data:null,
        type:"get",
        async:false,
        dataType:"jsonp",  // 此处必须要为jsonp,jsonp解决跨域使用的
        jsonp: 'callback',
        success:function(data){
        	$('#js_appscore_iframe').height(data.ctHeight);
    		setTimeout("commentframe.doloop()", 1000 );	
         }
     });
};

var commentframe = new Tcommentframe();
//页面完全载入后初始化
$(document).ready(function(){
	commentframe.JData = js_u;
	commentframe.init();
});
//释放
$(window).unload(function(){
	commentframe = null;
});
