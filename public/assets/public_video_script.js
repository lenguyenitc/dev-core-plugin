jQuery(document).ready(function($) {
    /***remove video url if following on premium by straight link***/
    $.ajax({
        type: "post",
        url: arc_ajax_var.url,
        data: {
            action: 'ARC_get_login_data',
            nonce: arc_ajax_var.nonce,
            postId: arc_ajax_var.postId
        },
        success: function (res) {
            if(res == 'show') {
                $('#subscribeModal').removeClass('fade').addClass('show in').show('100');
                $('#subscribeModal .modal-body').remove();
                $('#subscribeModal button.close').remove();
                $('#main article#post-' + arc_ajax_var.postId + ' a#tracking-url').remove();
                $('#main article#post-' + arc_ajax_var.postId + ' header.entry-header div.video-player').empty()
                    .append('<p style="margin: 0 auto"><img style="" width="100%" height="auto" src="' + arc_ajax_var.images +'premium-video.jpg" class="img-fluid"/></p>');
            }
            else if(res == 'show2') {
                $('#subscribeModal').removeClass('fade').addClass('show in').show('100');
                $('#subscribeModal button.close').remove();
                $('#main article#post-' + arc_ajax_var.postId + ' a#tracking-url').remove();
                $('#main article#post-' + arc_ajax_var.postId + ' header.entry-header div.video-player').empty()
                    .append('<p style="margin: 0 auto"><img style="" width="100%" height="auto" src="' + arc_ajax_var.images +'premium-video.jpg" class="img-fluid"/></p>');
            }
        }
    });


    /****remove all tags from video***/
    $(document).on('click', 'span#remove_video_tags', function () {
        var postID = $(this).attr('data-post-id');
        $.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'remove_all_tags_from_video',
                nonce: arc_ajax_var.nonce,
                postID: postID
            },
            success: function (res) {
                $('div.remove-all-tag').remove();
                $('span#remove_video_tags').remove();
                $('#remove_video_tags').text('');
                $('div.tags').removeClass('moretags');
                $('a.a-tags').remove();
                $('a.morelink').remove();
            }
        });
    });
});