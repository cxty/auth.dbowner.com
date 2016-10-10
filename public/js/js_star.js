
function Tstar(){
	this.u = null;
};
var keditor;
var items_per_page = 10;  
var page_index = 0; 
Tstar.prototype.init = function(){
	$('#js_appscoreBox').html(star.showCommentBox());
	
	$('#js_appscore_star').raty();

	star.getDataList(page_index); 
	
	$('#js_appscore_com').click(function(){
		$('#js_appscore_com').css({'display':'none'});
		$('#js_appscore_tea').DropInDown();
	});
	
	$('#socre_submit').click(function(){
		if(keditor.text() == ''){
			Boxy.alert( star.u.JS_LANG.Ex_NullComment ,function(){},{title: star.u.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
		}else{
			$.post(star.u.js_root+'/comment/saveComment',{client_id:$('#js_client_id').val(),comment:keditor.text(),score:$('#js_appscore_star-score').val(),rnd:Math.random()},function(data){
				if(data == -1){
					Boxy.alert( star.u.JS_LANG.Ex_Relogin ,function(){},{title: star.u.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
				}else if(data == -2){
					Boxy.alert( star.u.JS_LANG.Ex_NotComment ,function(){},{title: star.u.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
				}else if(data == -3){
					Boxy.alert( star.u.JS_LANG.Ex_Exceed ,function(){},{title: star.u.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
				}else{
					Boxy.alert( star.u.JS_LANG.Score_Success ,function(){$('#js_appscore_com').hide();$('#js_appscore_tea').hide();star.getDataList(page_index);},{title: star.u.JS_LANG.Remind ,modal:true,unloadOnHide:true});	
				}
			});
		}	
	});
}; 
Tstar.prototype.showCommentBox = function(){
    var html = '';
	html += '<link href="' + u.css1 + '" rel="stylesheet" type="text/css" />';
	html += '<link href="' + u.css2 + '" rel="stylesheet" type="text/css" />';
	html += '<link href="' + u.css3 + '" rel="stylesheet" type="text/css" />';
	html += '<link href="' + u.css4 + '" rel="stylesheet" type="text/css" />';
	html += '<link href="' + u.css5 + '" rel="stylesheet" type="text/css" />';
	html += '<link href="' + u.css6 + '" rel="stylesheet" type="text/css" />';
	html += '<div class="js_appscore_box">';
	html += '<input type="hidden" id="js_client_id" value="'+u.client_id+'" />';
	if(u.show){
		html += '<div id="js_appscore_com"><a class="js_appscore_href" id="socre_comment" href="javascript:void(0)">'+u.JS_LANG.Score_Comment+'</a></div>';
		html += '<div id="js_appscore_tea" style="display:none;">'; 
		html += '<div id="js_appscore_star"></div>';
		html += '<textarea name="comment" id="editor" style="width:100%;height:150px"></textarea>';
		html += '<div class="js_appscore_remind">'+u.JS_LANG.Score_Word+':<span id="word_count">0</span>/<span id="word_total">120</span>.&nbsp;&nbsp;<a class="js_appscore_href" id="socre_submit" href="javascript:void(0)">'+u.JS_LANG.Score_Submit+'</a></div>';
		html += '</div>';
	}
	html += '<div class="js_appscore_detail" id="app_table_cont"></div>';
	html += '<div class="js_appscore_page"><div id="pagination" class="pagination" style="display:none"></div></div>';
	html += '</div>';
	
	return html;
};
Tstar.prototype.editor = function(){	
	KindEditor.ready(function(K) {
		keditor = K.create('textarea[id="editor"]', {
			filterMode : true,
			resizeType : 1,
			allowFileManager : true,
			allowPreviewEmoticons : false,
			allowImageUpload : false,
			filterMode : true ,
			items : [
				'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'emoticons'],
			afterChange : function() {
				$.get(star.u.js_root+'/comment/getwordcount',{comment:this.text(),rnd:Math.random()},function(data){
					$('#word_count').html(data);
				});
			}
		});
	});	
};
Tstar.prototype.show = function(){	
	prettyPrint();
};
Tstar.prototype.getDataList = function (index){  
    var pageIndex = index;  

    $.fancybox.showLoading();
    $.ajax({
    	   type: "POST",
    	   url: star.u.js_root+"/comment/getmemberlist",
    	   data: "client_id="+$('#js_client_id').val()+"&pageIndex="+pageIndex+'&items_per_page='+items_per_page+"&rnd="+Math.random(),
    	   //dataType: 'json',  
           //contentType: "application/x-www-form-urlencoded",
           dataType:"jsonp",  // 此处必须要为jsonp,jsonp解决跨域使用的
           jsonp: 'callback',
    	   success: function(data){	
    	     //初始化分页
             if($("#pagination").html().length === 0){  
                 $("#pagination").pagination(data.count, {  
                     'items_per_page'      : items_per_page,  
                     'num_display_entries' : 10,  
                     'num_edge_entries'    : 2,  
                     'prev_text'           : star.u.JS_LANG.PrePage,  
                     'next_text'           : star.u.JS_LANG.NextPage,  
                     'callback'            : star.pageselectCallback  
                 });  
             }else{    
            	 var html = '';
          		 var starScore = data.star.AllStars[0] == 0 ? 0 : Math.round(parseFloat((data.star.AllStars[1]/data.star.AllStars[0]),2)*100)/100;
          		 html += '<div class="js_appscore_situation">';
          		 html += '<ul class="js_appscore_sit_left">';
          		 html += '<li><span class="star_title">'+star.u.JS_LANG.AppPage_FiveStars+'</span><span class="star_bar star5" style="width:'+(data.star.FiveStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.FiveStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+star.u.JS_LANG.AppPage_FourStars+'</span><span class="star_bar star4" style="width:'+(data.star.FourStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.FourStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+star.u.JS_LANG.AppPage_ThreeStars+'</span><span class="star_bar star3" style="width:'+(data.star.ThreeStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.ThreeStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+star.u.JS_LANG.AppPage_TwoStars+'</span><span class="star_bar star2" style="width:'+(data.star.TwoStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.TwoStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+star.u.JS_LANG.AppPage_OneStars+'</span><span class="star_bar star1" style="width:'+(data.star.OneStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.OneStars[0]+'</span></li>';
          		 html += '</ul>';
          		 html += '<div class="js_appscore_sit_right">';
          		 html += '<div class="score_title">'+star.u.JS_LANG.AppPage_AverageDecile+':</div>';
          		 html += '<div class="score_avValue">'+starScore+'</div>';
          		 html += '<div class="score_stars" id="js_votescore_star"></div>';
          		 html += '<div class="score_votes">'+data.star.AllStars[0]+'</div>';
          		 html += '</div>';
          		 html += '</div>';
          		 
            	 if(data.list.length > 0){
           		     html += '<table class="js_app_table" id="app_table" cellspacing="0" cellpadding="0" border="0">'; 
            		 for(var i=0;i<data.list.length;i++){
            			 html += '<tr>';
            			 html += '<td class="js_app_solid_bottom js_app_td" rowspan="3">';
            			 html += '<div class="'+data.list[i].aStar+'"></div>';
            			 html += '</td>';
            			 html += '</tr>';
            			 html += '<tr>';
            			 html += '<td class="js_app_dashed_bottom js_app_times">'+star.u.JS_LANG.From+':'+data.list[i].uName+'&nbsp;&nbsp;'+star.u.JS_LANG.ScoreTime+':'+data.list[i].aAppendTime+'</td>';
            			 html += '<td class="js_app_dashed_bottom js_app_center">'+(data.list[i].floor-i)+star.u.JS_LANG.Floor+'</td>';
            			 html += '</tr>';   //<img src="'+data.list[i].uhURL+'" />
            			 html += '<tr>';
            			 html += '<td class="js_app_solid_bottom js_app_td_com" colspan="2">';
            			 html += data.list[i].aComment;
            			 html += '</td>';
            			 html += '</tr>';             			
            		 }
            		 html += '</table>';

            		 if(data.list[0].floor > 10){
            			 $('#pagination').css({'display':''});
            		 }	 
            	 }
             }    
             $('#app_table_cont').html(html);
			 $('#js_votescore_star').raty({
				 readOnly:  true,
				 start:     starScore,
				 showHalf:  true
			 });
			 html = null;
			 
             $.fancybox.hideLoading(); 
    	   }
    	}); 
}; 
Tstar.prototype.pageselectCallback = function (page_index, jq){  
	star.getDataList(page_index);  
};

var star = new Tstar();
//页面完全载入后初始化
$(document).ready(function(){
	star.u = js_u_starlist;
	star.init();
	star.editor();
});
//释放
$(window).unload(function(){
	star = null;
});