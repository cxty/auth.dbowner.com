
function TUser_Profile(){
    this.ns='';
    this.JS_LANG = '';
};
TUser_Profile.prototype.init = function(){
    $('.user_profile_left_tool li').click(function(){
        location='/main/index-'+User_Profile.ns+$(this).attr('id');
    });
    $('.user_profile_right_box_b').mouseover(function(){
        $(this).addClass('mup');
    });
    $('.user_profile_right_box_b').mouseout(function(){
        $(this).removeClass('mup');
    });
};