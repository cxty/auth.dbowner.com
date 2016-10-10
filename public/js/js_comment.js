
function Tcomment(){
	this.APP_LANG = '';
	this.JData = '';
};
var keditor;
var items_per_page = 10;  
var page_index = 0;
Tcomment.prototype.init = function(){
	$('#js_appscore_star').raty();
	
	comment.getDataList(page_index);
	
	$('#js_appscore_com').click(function(){
		$('#js_appscore_com').css({'display':'none'});
		$('#js_appscore_tea').DropInDown();
		comment.resize();
	});
	
	$('#socre_submit').click(function(){
		if(keditor.text() == ''){
			Boxy.alert( this.APP_LANG.Ex_NullComment ,function(){},{title: this.APP_LANG.Remind ,modal:true,unloadOnHide:true});	
		}else{
			$.post( this.JData.root + '/comment/saveComment',{client_id:$('#js_client_id').val(),comment:keditor.text(),score:$('#js_appscore_star-score').val(),rnd:Math.random()},function(data){
				if(data == -1){
					Boxy.alert( comment.APP_LANG.Ex_Relogin ,function(){location = '';},{title: comment.APP_LANG.Remind ,modal:true,unloadOnHide:true});	
				}else if(data == -2){
					Boxy.alert( comment.APP_LANG.Ex_NotComment ,function(){},{title: comment.APP_LANG.Remind ,modal:true,unloadOnHide:true});	
				}else if(data == -3){
					Boxy.alert( comment.APP_LANG.Ex_Exceed ,function(){},{title: comment.APP_LANG.Remind ,modal:true,unloadOnHide:true});	
				}else{
					Boxy.alert( comment.APP_LANG.Score_Success ,function(){$('#js_appscore_com').hide();$('#js_appscore_tea').hide();comment.getDataList(page_index);},{title: comment.APP_LANG.Remind ,modal:true,unloadOnHide:true});	
				}
			});
		}	
	});
};
Tcomment.prototype.resize = function(){
	var sh = $('#js_appscore_editor').parent().height()+$('#js_appscore_wrap').height();
	
	if ( $('#js_appscore_com').css('display') == 'none' ) {
		sh += $('#js_appscore_tea').height();
	}
	
	$.ajax({
        url: this.JData.root + '/comment/changeCommentHeight',
        data: 'type=set&ctHeight=' + sh,
        type:"get",
        async:false,
        dataType:"jsonp",  // 此处必须要为jsonp,jsonp解决跨域使用的
        jsonp: 'callback',
        success:function(data){
        	
         }
     });
	/*
	try{
		parent.location.hash = sh;
	}catch(e){
        // ie、chrome的安全机制无法修改parent.location.hash，
        // 所以要利用一个中间的cnblogs域下的代理iframe
        var js_changecoment_iframe = document.createElement('js_changecoment_iframe');
        js_changecoment_iframe.style.display = 'none';
        js_changecoment_iframe.src = this.JData.root + '/comment/changeComment#'+sh;
        document.body.appendChild(js_changecoment_iframe);
	}	
	*/
};
Tcomment.prototype.editor = function(){
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
				$.get(this.JData.root + '/comment/getwordcount',{comment:this.text(),rnd:Math.random()},function(data){
					$('#word_count').html(data);
				});
			}
		});
	});	
};
Tcomment.prototype.show = function(){	
	prettyPrint();
};
Tcomment.prototype.getDataList = function (index){  
    var pageIndex = index;  

    $.fancybox.showLoading();
    $.ajax({
    	   type: "POST",
    	   url: this.JData.root + "/comment/getmemberlist",
    	   data: "client_id="+$('#js_client_id').val()+"&pageIndex="+pageIndex+'&items_per_page='+items_per_page+"&rnd="+Math.random(),
    	   dataType: 'json',  
           contentType: "application/x-www-form-urlencoded",
    	   success: function(data){	
    	     //初始化分页
             if($("#pagination").html().length === 0){  
                 $("#pagination").pagination(data.count, {  
                     'items_per_page'      : items_per_page,  
                     'num_display_entries' : 10,  
                     'num_edge_entries'    : 2,  
                     'prev_text'           : comment.APP_LANG.PrePage,  
                     'next_text'           : comment.APP_LANG.NextPage,  
                     'callback'            : comment.pageselectCallback  
                 });  
             }else{    
            	 var html = '';
          		 var starScore = data.star.AllStars[0] === 0 ? 0 : Math.round(parseFloat((data.star.AllStars[1]/data.star.AllStars[0]),2)*100)/100;
          		 html += '<div class="js_appscore_situation" id="js_appscore_wrap">';
          		 html += '<ul class="js_appscore_sit_left" id="js_appscore_situation">';
          		 html += '<li><span class="star_title">'+comment.APP_LANG.AppPage_FiveStars+'</span><span class="star_bar star5" style="width:'+(data.star.FiveStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.FiveStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+comment.APP_LANG.AppPage_FourStars+'</span><span class="star_bar star4" style="width:'+(data.star.FourStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.FourStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+comment.APP_LANG.AppPage_ThreeStars+'</span><span class="star_bar star3" style="width:'+(data.star.ThreeStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.ThreeStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+comment.APP_LANG.AppPage_TwoStars+'</span><span class="star_bar star2" style="width:'+(data.star.TwoStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.TwoStars[0]+'</span></li>';
          		 html += '<li><span class="star_title">'+comment.APP_LANG.AppPage_OneStars+'</span><span class="star_bar star1" style="width:'+(data.star.OneStars[1]+5)+'px;">&nbsp;</span><span class="star_count">'+data.star.OneStars[0]+'</span></li>';
          		 html += '</ul>';
          		 html += '<div class="js_appscore_sit_right">';
          		 html += '<div class="score_title">'+comment.APP_LANG.AppPage_AverageDecile+':</div>';
          		 html += '<div class="score_avValue">'+starScore+'</div>';
          		 html += '<div class="score_stars" id="js_votescore_star"></div>';
          		 html += '<div class="score_votes">'+data.star.AllStars[1]+'</div>';
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
            			 html += '<td class="js_app_dashed_bottom js_app_times">'+comment.APP_LANG.From+':'+data.list[i].uName+'&nbsp;&nbsp;'+comment.APP_LANG.ScoreTime+':'+data.list[i].aAppendTime+'</td>';
            			 html += '<td class="js_app_dashed_bottom js_app_center">'+(data.list[i].floor-i)+comment.APP_LANG.Floor+'</td>';
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
            	 
            	 $('#app_table_cont').html(html);
    			 $('#js_votescore_star').raty({
    				 readOnly:  true,
    				 start:     starScore,
    				 showHalf:  true
    			 });
    			 html = null;
    			
    			 comment.resize();
             }       
			 
             $.fancybox.hideLoading(); 
    	   }
    	}); 
}; 
Tcomment.prototype.pageselectCallback = function (page_index, jq){  
	comment.getDataList(page_index);  
};