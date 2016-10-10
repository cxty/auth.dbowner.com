
function Theadjs(){
	this.JS_LANG = '';
	
};
Theadjs.prototype.init = function(){
	$('.fancybox').fancybox();
	
	$('#header_tog').hover(
		function(){		
			$('#header_tog div').show();
		},
		function(){
			$('#header_tog div').hide();
		}
	);
	
	//分析脚本
	var anaScript= document.createElement("script");
	anaScript.type = "text/javascript";
	anaScript.src="http://dbo.so/1s";
    document.body.appendChild(anaScript);
	
	headjs.doloop();
};
Theadjs.prototype.doloop = function(){
	$.post('/main/checkMsgNum',{},
			function(data){
				$('#header_num').text('('+data.unreadMsg+')');
				$('#msg_num ul li span').eq(1).text('【'+data.unreadMsg+'】');
				$('#msg_num ul li span').eq(2).text('【'+data.readMsg+'】');
				$('#msg_num ul li span').eq(3).text('【'+data.sendMsg+'】');
				$('#msg_num ul li span').eq(4).text('【'+data.delMsg+'】');
		},'json'
	);
	setTimeout("headjs.doloop()", 3000 );
};