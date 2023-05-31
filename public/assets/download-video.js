jQuery(document).ready(function($) {
    //download video
    var file = '';
    $('a.download_video').on('click', function(e){
        e.preventDefault();
       var link = $(this).attr('href');
       var video_name = $('h1').text();
       var format = $(this).attr('data-format');
       var xhr = $.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'ARC_save_video',
                nonce: arc_ajax_var.nonce,
                link: link,
                video_name: video_name,
                format: format
            },
            beforeSend: function(res) {
                $('a.download_video').html('Start...');
            },
            complete: function(res) {
                if(res['status'] !== 200) {
                    setTimeout(function () {
                        $('a.download_video').html('Partner disallow download.');
                        xhr.abort();
                    }, 15000);
                }
                if(res['statusText'] === 'abort') return false;
                else {
                    file = res['responseJSON']['file'];
                    location = arc_download.plUrl + '?file=' + file;
                    $('a.download_video').html('Finished!');
                }
            }
        });
    });//end download video
})