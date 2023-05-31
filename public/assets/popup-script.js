jQuery(document).ready(function($) {
    var action = $('#popupModal').attr('data-action');
    var time = $('#popupModal button.close').attr('data-time');
    var animation = $('#popupModal .modal-content').attr('data-anim');
    if(time == 6) {
        time = 21600;
    }//6 hours
    else if(time == 24) {
        time = 86400;
    } //24 hours
    else if(time == 3) {
        time = 259200;
    } //3 days
    else {
        time = time * 3600 * 24;
    }
    var timer;
    var timer1 = setInterval(function(){
        if(disc_obj.discShow == "1") {
            if(getCookieLegal('is_legal') == 'yes') {
                timer = setInterval(function(){
                    var blocked = checkUserCookie();
                    if(blocked == 'show') {
                        show_modal(action);
                        clearInterval(timer1);
                        clearInterval(timer);
                    }
                    else {
                        clearInterval(timer1);
                        clearInterval(timer);
                        $('#popupModal').remove();
                        $('div#page').css('filter', 'grayscale(0) !important');
                    }
                }, 500);
            }
        } else {
            var timer = setInterval(function(){
                var blocked = checkUserCookie();
                if(blocked == 'show') {
                    show_modal(action);
                    clearInterval(timer);
                    clearInterval(timer1);
                } else {
                    clearInterval(timer);
                    clearInterval(timer1);
                    $('#popupModal').remove();
                    $('div#page').css('filter', 'grayscale(0) !important');
                }
            }, 500);
        }
    }, 500);



    //close modal
    $('#popupModal button.close').on('click', function (){
        $('div#page').css('filter', 'grayscale(0)');
        var time = $(this).attr('data-time');
        if(time == 6) {
            time = 21600;
        }//6 hours
        if(time == 24) {
            time = 86400;
        } //24 hours
        if(time == 3) {
           time = 259200;
        } //3 days
        else {
            time = time * 3600 * 24;
        }
        setCookie("block_modal", 'modal_blocked', time);
        checkCookie(time)
        $('#popupModal').css('display', 'none').slideUp(200);
    });//end close modal

    //show modal
    function show_modal(action) {
        if(action == '15sec') {
            setTimeout(function () {
                $('#popupModal').css('display', 'block');
                $('#popupModal .modal-content').addClass(animation);
                $('div#page').css('filter', 'grayscale(1)');
            }, 15000);
        } else {
            $(window).scroll(function(){
                var top = $(this).scrollTop();
                if(top >= 300) {
                    var blocked = checkUserCookie();
                    //console.log(blocked);
                    if(blocked === false) {
                        clearInterval(timer1);
                        clearInterval(timer);
                        $('#popupModal').remove();
                        $('div#page').css('filter', 'grayscale(0)');
                    } else {
                        $('#popupModal').css('display', 'block');
                        $('#popupModal .modal-content').addClass(animation);
                        $('div#page').css('filter', 'grayscale(1)');
                    }
                }
            });
        }
    }//end show modal

    function setCookie(name, value, time) {
        document.cookie = name + "=" + value + "; max-age=" + time + ";path=/";
    }

    function getCookie(name) {
        var cookie = document.cookie;
        //if (cookie.length > 0) {
        if(cookie.indexOf(name) !== -1 && cookie.indexOf('modal_blocked') !== -1) {
            return 'blocked';
        } else return false;
       // } else return false;
    }

    function getCookieLegal(name) {
        var cookie = document.cookie;
        if (cookie.length > 0) {
            if(cookie.indexOf(name) !== -1 && cookie.indexOf('yes') !== -1) {
                return 'yes';
            } else return false;
        } else return false;
    }

    function checkCookie(time) {
        var block_modal = getCookie("block_modal");
        if (block_modal == false) {
            $('div#page').attr('style="filter:grayscale(0) !important"');
            setCookie("block_modal", 'modal_blocked', time);
            //location.reload();
        } else return false;
    }

    function checkUserCookie() {
        var block_modal = getCookie("block_modal");
        if (block_modal == 'blocked') {
            return false;
        } else {
            return 'show';
        }
    }

    //redirect
    $('#popupModal div.modal-footer a.btn').on('click', function (e){
        e.preventDefault();
        var link = $(this).attr('href');
        window.open(link, '_self');
        //window.location.href = link;
        checkCookie(time);
    });//end redirect
});