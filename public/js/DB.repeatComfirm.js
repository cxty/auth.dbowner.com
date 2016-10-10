

function TrepeatComfirm () {
    this.lData = '';
    
    this.checkArray = '';
};

TrepeatComfirm.prototype.init = function () {
	var repeatInfo = parent.joinForm.checkArray.info;
	$('#third_account').html(repeatInfo.auth.uProvider + '：' + repeatInfo.auth.uDisplay_name);
	$('#db_account').html('：' + repeatInfo.user.uEmail);
	
	var authHtml = '';
	var userHtml = '';
	var IcoCode = '';
	if ( parent.joinForm.checkArray.list ) {
		var repeatArray = parent.joinForm.checkArray.list;

		authHtml += '<div class="check_item">';
		authHtml += '<div class="check_i_left">' + repeatArray.auth.db_total.title + '：</div>';
		authHtml += '<div class="check_i_right">' + repeatArray.auth.db_total.total + '</div>';
		authHtml += '</div>';
		authHtml += '<div class="check_item">';
		authHtml += '<div class="check_i_left">' + repeatArray.auth.list_app.title + '：</div>';
		authHtml += '<div class="check_i_right">';
		if ( repeatArray.auth.list_app.list.length > 0 ) {
			for ( var i in repeatArray.auth.list_app.list ) {
				IcoCode = Common.IcoCode2Array(repeatArray.user.list_app.list[0].aIcoCode);
				
				authHtml += '<span><img class="check_img" src="' + repeatComfirm.lData.FilePath + '&filecode=' + IcoCode[3].filecode + '&w=64' + '" />' + repeatArray.auth.list_app.list[i].aName + '</span>';
			}
		} else {
			authHtml += repeatComfirm.lData.NullData;
		}
		authHtml += '</div>';
		authHtml += '</div>';
		authHtml += '<div class="check_item">';
		authHtml += '<div class="check_i_left">' + repeatArray.auth.list_push.title + '：</div>';
		authHtml += '<div class="check_i_right">';
		if ( repeatArray.auth.list_push.list ) {
			for ( var i in repeatArray.auth.list_push.list ) {
				authHtml += '<span>' + repeatArray.auth.list_push.list[i].paAppName + '</span>';
			}
		} else {
			authHtml += repeatComfirm.lData.NullData;
		} 
		authHtml += '</div>';
		authHtml += '</div>';
		authHtml += '<div class="check_item">';
		authHtml += '<div class="check_i_left">' + repeatArray.auth.list_ad.title + '：</div>';
		authHtml += '<div class="check_i_right">';
		if ( repeatArray.auth.list_ad.list ) {
			for ( var i in repeatArray.auth.list_ad.list ) {
				authHtml += '<span>' + repeatArray.auth.list_ad.list[i].aName + '</span>';
			}
		} else {
			authHtml += repeatComfirm.lData.NullData;
		} 
		authHtml += '</div>';
		authHtml += '</div>';
		authHtml += '<div class="check_item">';
		authHtml += '<div class="check_i_left">' + repeatArray.auth.list_expand.title + '：</div>';
		authHtml += '<div class="check_i_right">';
		if ( repeatArray.auth.list_expand.list ) {
			for ( var i in repeatArray.auth.list_expand.list ) {
				IcoCode = Common.IcoCode2Array(repeatArray.user.list_app.list[0].aIcoCode);
				
				authHtml += '<span><img class="check_img" src="' + repeatComfirm.lData.FilePath + '&filecode=' + IcoCode[3].filecode + '&w=64' + '" />' + repeatArray.auth.list_expand.list[i].PlugInName + '</span>';
			}
		} else {
			authHtml += repeatComfirm.lData.NullData;
		} 
		authHtml += '</div>';
		authHtml += '</div>';
		
		userHtml += '<div class="check_item">';
		userHtml += '<div class="check_i_left">' + repeatArray.user.db_total.title + '：</div>';
		userHtml += '<div class="check_i_right">' + repeatArray.user.db_total.total + '</div>';
		userHtml += '</div>';
		userHtml += '<div class="check_item">';
		userHtml += '<div class="check_i_left">' + repeatArray.user.list_app.title + '：</div>';
		userHtml += '<div class="check_i_right">';
		if ( repeatArray.user.list_app.list.length > 0 ) {
			for ( var i in repeatArray.user.list_app.list ) {
				IcoCode = Common.IcoCode2Array(repeatArray.user.list_app.list[0].aIcoCode);
				
				userHtml += '<span><img class="check_img" src="' + repeatComfirm.lData.FilePath + '&filecode=' + IcoCode[3].filecode + '&w=64' + '" />' + repeatArray.user.list_app.list[i].aName + '</span>';
			}
		} else {
			userHtml += repeatComfirm.lData.NullData;
		} 
		userHtml += '</div>';
		userHtml += '</div>';
		userHtml += '<div class="check_item">';
		userHtml += '<div class="check_i_left">' + repeatArray.user.list_push.title + '：</div>';
		userHtml += '<div class="check_i_right">';
		if ( repeatArray.user.list_push.list ) {
			for ( var i in repeatArray.user.list_push.list ) {
				userHtml += '<span>' + repeatArray.user.list_push.list[i].paAppName + '</span>';
			}
		} else {
			userHtml += repeatComfirm.lData.NullData;
		}
		userHtml += '</div>';
		userHtml += '</div>';
		userHtml += '<div class="check_item">';
		userHtml += '<div class="check_i_left">' + repeatArray.user.list_ad.title + '：</div>';
		userHtml += '<div class="check_i_right">';
		if ( repeatArray.user.list_ad.list ) {
			for ( var i in repeatArray.user.list_ad.list ) {
				userHtml += '<span>' + repeatArray.user.list_ad.list[i].aName + '</span>';
			}
		} else {
			userHtml += repeatComfirm.lData.NullData;
		} 
		userHtml += '</div>';
		userHtml += '</div>';
		userHtml += '<div class="check_item">';
		userHtml += '<div class="check_i_left">' + repeatArray.user.list_expand.title + '：</div>';
		userHtml += '<div class="check_i_right">';
		if ( repeatArray.user.list_expand.list ) {
			for ( var i in repeatArray.user.list_expand.list ) {
				IcoCode = Common.IcoCode2Array(repeatArray.user.list_app.list[0].aIcoCode);
				
				userHtml += '<span><img class="check_img" src="' + repeatComfirm.lData.FilePath + '&filecode=' + IcoCode[3].filecode + '&w=64' + '" />' + repeatArray.user.list_expand.list[i].PlugInName + '</span>';
			}
		} else {
			userHtml += repeatComfirm.lData.NullData;
		} 
		userHtml += '</div>';
		userHtml += '</div>';
	}
	
	$('#auth_cont').html(authHtml);
	$('#user_cont').html(userHtml);
	
	//生成滚动条
	$('.check_content').jScrollPane();
	
	
	$('#sub_btn').click(function(){
		Boxy.confirm( parent.joinForm.JS_LANG.ConfirmBinding , 
				  function() { 
						parent.$.fancybox.close();
						parent.joinForm.makeConfirmSubBtn();
				  }, 
				  {title: parent.joinForm.JS_LANG.Remind });
		
	});
	$('#can_btn').click(function(){
		parent.$.fancybox.close();
	});
};
//页面完全再入后初始化
$(document).ready(function(){
	repeatComfirm = new TrepeatComfirm();
	repeatComfirm.lData = lData;
	repeatComfirm.init();
});
//释放
$(window).unload(function(){
	repeatComfirm = null;
});