jQuery(document).ready(function($){
    var offset = new Date().getTimezoneOffset();
    var ids = [];
    $('td.column-date').each(function (ind, el) {
       ids.push($(el).attr('data-id'));
    });
    $.ajax({
        url: obj_for_ajax.url,
        type: 'POST',
        data: {
            action: 'display_ads_publication_date',
            nonce: obj_for_ajax.nonce,
            offset: offset,
            data_id: ids
        },
        success: function (res) {
            for(var key in res) {
                $('td.column-date[data-id=' + key+ '] div.submitted-on').text(res[key]);
            }
        }
    });


    /** Delete ad in db [start]*/
    $('.delete_ads').on('click', function(){
        var data_id_for_delete = $(this).attr('data-id-for-delete');
        delete_ad_in_db(data_id_for_delete);
    })
    function delete_ad_in_db(data_id_for_delete){
        $.ajax({
            url: obj_for_ajax.url,
            type: 'POST',
            data: {
                action             : 'delete_ad_in_db',
                nonce              : obj_for_ajax.nonce,
                data_id_for_delete : data_id_for_delete,
            },
            success: function( response ) {
                $("tr" + "." + response).css('background-color', '#ffdada');
                setTimeout(() => {
                    $("tr" + "." + response).fadeOut(200).remove();
                }, 1000);
            }
        });

    }
    /** Save ad in db [start]*/

    /** Publish ad [start]*/
    $('.to_publish').on('click', function(){
        var data_id_for_publish = $(this).attr('data-id-for-publish');
        publish_ad(data_id_for_publish);
    });
    function publish_ad(data_id_for_publish){
        $.ajax({
            url: obj_for_ajax.url,
            type: 'POST',
            data: {
                action              : 'publish_ad',
                nonce               : obj_for_ajax.nonce,
                data_id_for_publish : data_id_for_publish,
            },
            success: function( response ) {
                $("tr" + "." + response).css('background-color', '#defccf');
                setTimeout(() => {
                    $("tr" + "." + response).fadeOut(200).remove();
                }, 1000);
            }
        });
    }
    /** Publish ad [end]*/

    /** Edit ad [start]*/
    $('button.edit').on('click', function(){
        var data_id_for_edit = $(this).attr('data-id-for-edit');
        var text_message   = $('p[data-class="text_' + data_id_for_edit + '"]').text();
        $('.popup-fade').fadeIn(200);
        $('#ads_text_msg').val(text_message);
        $('button.apply_edits_made').attr('data-id-for-save', data_id_for_edit);
    });
    $('.popup-close').click(function() {
        $(this).parents('.popup-fade').fadeOut();
        $('#ads_text_msg').val('');
        $('button.apply_edits_made').attr('data-id-for-save', '');
        return false;
    });
    $(document).keydown(function(e) {
        if (e.keyCode === 27) {
            e.stopPropagation();
            $('.popup-fade').fadeOut();
            $('#ads_text_msg').val('');
            $('button.apply_edits_made').attr('data-id-for-save', '');
        }
    });

    $('.apply_edits_made').on('click', function(){
        var data_id_for_save = $(this).attr('data-id-for-save');
        var text_message   = $('textarea#ads_text_msg').val();
        edit_ad(data_id_for_save, text_message);
        $('.popup-fade').fadeOut();
        $('#ads_text_msg').val('');
        $('button.apply_edits_made').attr('data-id-for-save', '');
    });
    function edit_ad(data_id_for_save, text_message){
        $.ajax({
            url: obj_for_ajax.url,
            type: 'POST',
            data: {
                action              : 'edit_ad',
                nonce               : obj_for_ajax.nonce,
                data_id_for_save    : data_id_for_save,
                text_message        : text_message,
            },
            success: function( response ) {
                $('p[data-class="text_' + data_id_for_save + '"]').text(response);
            }
        });
    }
    /** Edit ad [end]*/

})