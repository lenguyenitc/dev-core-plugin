jQuery(document).ready(function($) {
    $('a.removeWatchList').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'ARC_clear_watch_list',
                nonce: arc_ajax_var.nonce,
            },
            success: function (res) {
                $('div#watch-videos div.videos-list article').remove();
                $('div.pagination.albums').remove();
                /*setTimeout(function () {
                    location.reload();
                }, 300);*/
            }
        });
    });
});