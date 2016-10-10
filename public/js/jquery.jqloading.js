/**
 * 加载图片
 * 
 * wbqing405@sina.com
 */
(function($){
	var Q_value = {
			defaults:{
					img: '../public/images/loading_128.gif'
				}			
	};
	
	var Q_obj = {
		img: '<text style="width:30px;height:30px;display:block;background-image: url(\'' + Q_value.defaults.img + '\');background-position: -50px -50px;margin:0 auto;"></text>',
	};
	
	$.extend({
	    QshowLoading : function( id ){
	        $("#" + id).html('').append(Q_obj.img).css({'text-align':'center'});
	    },
	    QhideLoading : function( id ){
	    	$("#" + id).html('');  
	    }
	});
	
})(jQuery);