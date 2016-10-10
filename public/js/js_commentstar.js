
function Tcommentstar(){
	this.u = null;
};
Tcommentstar.prototype.init = function(){		
	$('#js_comment_starShell').html(commentstar.initBox());
	
	$('#js_comment_star').raty({
		readOnly:  true,
		start:     commentstar.u.appstar.StarAve,
		showHalf:  true
	});
};
Tcommentstar.prototype.initBox = function(){
	var html = '';
	html += '<link href="' + this.u.css + '" rel="stylesheet" type="text/css" />';
	html += '<div id="js_comment_starBox">';
	html += '<div id="js_comment_title">'+this.u.JS_LANG.CommentScore+'：'+Math.round(parseFloat(this.u.appstar.StarAve,2)*100)/100+' ('+this.u.appstar.StarSum+'/'+this.u.appstar.StarCount+')</div>';
	html += '<div id="js_comment_star"></div>';
	html += '</div>';
	
	return html;
};

var commentstar = new Tcommentstar();
//页面完全载入后初始化
$(document).ready(function(){
	commentstar.u = js_u_star;
	commentstar.init();
});
//释放
$(window).unload(function(){
	commentstar = null;
});