jQuery(document).ready(function($){
    $('.upload_image_button').click(function(){
        $('#infos').text('').css('display', 'none');
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        wp.media.editor.send.attachment = function(props, attachment) {
            $(button).parent().prev().attr('src', attachment.url);
            $(button).parent().prev().attr('name', attachment.title);
            $(button).parent().prev().attr('value', attachment.filename);
            $(button).parent().prev().attr('data-height', attachment.height);
            $(button).parent().prev().attr('data-size', attachment.filesizeInBytes);
            $(button).parent().prev().attr('data-format', attachment.mime);
            $(button).prev().val(attachment.id);

            $('#file_info').css('display', 'flex');
            $('#fileThumb video').attr('src', attachment.url);
            $('#fileThumb video source').attr({
                'src' : attachment.url,
                'type' :attachment.mime
            });
            /**file name***/
            $('#fileName strong').text(attachment.filename);
            $('label[for="fileName"]').text('File: ');
            /**upload Date***/
            $('#uploadDate').text(attachment.dateFormatted);
            $('label[for="uploadDate"]').text('Upload date: ');
            /** uploaded  By***/
            $('#uploadedBy').text(attachment.authorName);
            $('label[for="uploadedBy"]').text('Uploaded by: ');
            /**file Type***/
            $('#fileType').text(attachment.mime);
            $('label[for="fileType"]').text('File type: ');
            /**file Size***/
            $('#fileSize').text(attachment.filesizeHumanReadable);
            $('label[for="fileSize"]').text('File size: ');
            /*/!**file Length***!/
            $('#fileName').text(attachment.filename);
            $('label[for="fileName"]').text('Length: ');*/


            $('#selectResolution option.resolution').remove();
            $.ajax({
                type: "post",
                url: arc_convert.url,
                data: {
                    action: 'ARC_get_height_video',
                    nonce: arc_convert.nonce,
                    fileUrl: attachment.url,
                },
               /* beforeSend: function () {
                   console.log(attachment.url);
                },*/
                success: function (res) {
                    //console.log(res);
                    if(res >= '2160') {
                        $('#selectResolution').append('<option class="resolution" value="1440" data-value="1440">1440</option>' +
                            '<option class="resolution" value="1080" data-value="1080">1080</option>' +
                            '<option class="resolution" value="720" data-value="720">720</option>' +
                            '<option class="resolution" value="720" data-value="720">720</option>' +
                            '<option class="resolution" value="480" data-value="480">480</option>' +
                            '<option class="resolution" value="360" data-value="360">360</option>' +
                            '<option class="resolution" value="240" data-value="240">240</option>');
                    }
                    if(res >= '1440' && res < '2160') {
                        $('#selectResolution').append('<option class="resolution" value="1080" data-value="1080">1080</option>' +
                            '<option class="resolution" value="720" data-value="720">720</option>' +
                            '<option class="resolution" value="480" data-value="480">480</option>' +
                            '<option class="resolution" value="360" data-value="360">360</option>' +
                            '<option class="resolution" value="240" data-value="240">240</option>');
                    }
                    if(res >= '1080' && res < '1440') {
                        $('#selectResolution').append('<option class="resolution" value="720" data-value="720">720</option>' +
                            '<option class="resolution" value="480" data-value="480">480</option>' +
                            '<option class="resolution" value="360" data-value="360">360</option>' +
                            '<option class="resolution" value="240" data-value="240">240</option>');
                    }
                    if(res >= '720' && res < '1080') {
                        $('#selectResolution').append('<option class="resolution" value="480" data-value="480">480</option>' +
                            '<option class="resolution" value="360" data-value="360">360</option>' +
                            '<option class="resolution" value="240" data-value="240">240</option>');
                    }
                    if(res >= '480' && res < '720') {
                        $('#selectResolution').append('<option class="resolution" value="360" data-value="360">360</option>' +
                            '<option class="resolution" value="240" data-value="240">240</option>');
                    }
                    if(res >= '360' && res < '480') {
                        $('#selectResolution').append( '<option class="resolution" value="240" data-value="240">240</option>');
                    }
                    if(res < '360') {
                        $('#infos').text('Your videos has a minimal access resolution. No other solutions are available.').css('display', 'block');
                    }
                },
            });

            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        wp.media.editor.open(button);
        return false;
    });



    $('#convert_video').on('click', function () {
        $('#allerts').text('').css('display', 'none');
        $('#infos').text('').css('display', 'none');
        $('#success_msg').text('').css('display', 'none');
        var hidden = $('#convert-form input[type="hidden"]');
        var videoSize = hidden.next().attr('data-size');
        var videoLink = hidden.next().attr('src');
        var videoName = hidden.next().attr('name');
        var videoFormat = hidden.next().attr('data-format');
        var videoResolution = $('#selectResolution option:selected').val();
        if($('#fileName').text() == '') {
            $('#allerts').text('').text(arc_convert.chooseFile).css('display', 'block');
            return false;
        }
        else if (videoFormat.indexOf('mp4') < 0) {
            $('#allerts').text('').text(arc_convert.chooseMp4).css('display', 'block');
            return false;
        }
        else if (videoResolution == "0") {
            $('#allerts').text('').text(arc_convert.chooseResolution).css('display', 'block');
            return false;
        }
        else {
            var xhr = $.ajax({
                type: "post",
                url: arc_convert.url,
                data: {
                    action: 'ARC_convert_video',
                    nonce: arc_convert.nonce,
                    fileUrl: videoLink,
                    fileName: videoName,
                    resolution: parseInt(videoResolution),
                },
                beforeSend: function () {
                    $('#allerts').text('').css('display', 'none');
                    $('#infos').text('').text(arc_convert.startConvert).css('display', 'block');
                    $('#convert_video').find('svg').removeClass('fa-crop-alt').addClass('fa-spinner fa-pulse');
                },
                success: function (res) {
                    //console.log(res);
                },
                complete: function (res) {
                    //console.log(res);
                    if(res['status'] == 200) {
                        $('#infos').text('').css('display', 'none');
                        $('#success_msg').text('').text(arc_convert.convertDone).css('display', 'block');
                    } else if(res['status'] == 504 || res['status'] == 500) {
                        $('#allerts').text('Your video too lange.').css('display', 'block');
                        $('#infos').text('Recommended choose the video no more than 100MB').css('display', 'block');
                        $('#success_msg').text('').css('display', 'none');
                        xhr.abort();
                    }
                    $('#convert_video').find('svg').removeClass('fa-spinner fa-pulse').addClass('fa-crop-alt');
                }
            });
        }
    });
});