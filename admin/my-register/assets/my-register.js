jQuery(document).ready(function($){
    /*var windowWidth = $(window).width();
    $('.page-container ,.text').mousemove(function(event){
        var moveX = (($(window).width() / 2) - event.pageX) * 0.1;
        var moveY = (($(window).height() / 2) - event.pageY) * 0.1;
        $('.page-back').css('margin-left',moveX + 'px');
        $('.page-back').css('margin-top',moveY + 'px');
    });*/

    var img_src = my_reg_obj.form_logo;
    var show_logo = my_reg_obj.show_form_logo;

    if(show_logo !== "false") {
        $('#registerform, #lostpasswordform, #loginform').prepend('<p id="form_logo" style="text-align: center;margin-bottom: 30px">' +
            '<a href="'+my_reg_obj.site_url+'"><img style="width: 100%" src="' + img_src + '"/></a></p>');
    }
});