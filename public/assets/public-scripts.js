jQuery(document).ready(function($) {
    //add post to watch list
    if(arc_ajax_var.postType === 'post' && arc_ajax_var.postId !== null) {
        $.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'ARC_add_to_watch_list',
                nonce: arc_ajax_var.nonce,
                postId: arc_ajax_var.postId,
                catName: arc_ajax_var.catName
            },
            success: function (res) {
                //console.log(arc_ajax_var.postId + ',' + arc_ajax_var.catName);
            }
        });//end add post to watch list
    }


    //save to playlist
    var existPlaylist = $('#playlistList option:selected').attr('data-id');

    $(document).on('click', '#playlistList-menu li div.ui-menu-item-wrapper', function () {
        var ui_select = $(this).text();
        if(ui_select == 'Create a playlist') {
            $('div#existings').css('display', 'block');
            $('span#playlistList-button').css('margin-bottom', '10px');
            $('#savePlaylist').text('Add video');
            $('#div_playlistTitle').css('display', 'block');
            $('#div_playlistDesc').css('display', 'block');
        } else {
            $('div#existings').css('display', 'inline-flex');
            $('span#playlistList-button').css('margin-bottom', '0 !important');
            $('#savePlaylist').text('Add video');
            $('#div_playlistTitle').css('display', 'none');
            $('#div_playlistDesc').css('display', 'none');
        }
    });

    $(document).on('change', '#playlistList', function () {
        existPlaylist = $('#playlistList option:selected').val();
        var option = $('#playlistList option:selected').attr('data-id');
        if(option == 'noSelect') {
            $('div#existings').css('display', 'block');
            $('span#playlistList-button').css('margin-bottom', '10px');
            $('#savePlaylist').text('Add video');
            $('#div_playlistTitle').css('display', 'block');
            $('#div_playlistDesc').css('display', 'block');
        } else {
            $('div#existings').css('display', 'inline-flex');
            $('span#playlistList-button').css('margin-bottom', '0');
            $('#savePlaylist').text('Add video');
            $('#div_playlistTitle').css('display', 'none');
            $('#div_playlistDesc').css('display', 'none');
        }
    });

    $(document).on('click', '#savePlaylist', function() {
        var option = $('#playlistList option:selected').attr('data-id');
        if(option == 'noSelect') {
            existPlaylist = 'noSelect';
            var title = $('#playlistTitle').val();
            var desc = $('#playlistDesc').val();
            if(title.length == 0) {
                $('#playlistError').css('display','block').text('Title is required');
                $('#playlistTitle').focus();
                setTimeout(function () {
                    $('#playlistError').hide(900).text('');
                }, 2000);
                return;
            }
        } else {
            title = '';
            desc = '';
            existPlaylist = $('#playlistList option:selected').val();
        }
        //console.log(existPlaylist);
        $.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'ARC_create_playlist',
                nonce: arc_ajax_var.nonce,
                titlePlaylist: title,
                descPlaylist: desc,
                postId: arc_ajax_var.postId,
                existPlaylist: existPlaylist
            },
            error: function(res) {
              //console.log(res);
            },
            success: function (res) {
                //console.log(res);
                if(res == 'refresh') {
                    $('#playlistError').css('display','block').text('You have already added video to this playlist.');
                    setTimeout(function () {
                        $('#playlistError').hide(900).text('');
                    }, 2000);
                } else {
                    if(res == 'Missing arguments') {
                        $('#playlistError').css('display','block').text('Title is required');
                        setTimeout(function () {
                            $('#playlistError').hide(900).text('');
                        }, 2000);
                    } else {
                        if(res == 'Playlist exists') {
                            $('#playlistError').css('display','block').text(res);
                            setTimeout(function () {
                                $('#playlistError').hide(900).text('');
                            }, 2000);
                        } else {
                            if(res['exist'] == 'yes') {
                                $('#isnt_in').remove();
                                $('#playlistSuccess').css('display','block').text('Video added to ' + res['name']);
                                setTimeout(function () {
                                    $('#playlistSuccess').text('');
                                    $('#playlistModal button.close').trigger('click');
                                }, 1500);
                                $('ul#videoInPlayList').append('<li data-list="'+res['slug']+'">'+
                                    '<span><a style="float:left" href="'+ arc_ajax_var.siteUrl +'/playlist/'+res['slug']+'" data-list="'+res['slug']+'/">' + res['name']+ '</a>' +
                                    '<a style="float:right" class="removeFromPlaylist" data-post="'+res['post']+'" data-list="'+res['slug']+'"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                                    '<path d="M0 4C0 1.79086 1.79086 0 4 0H14C16.2091 0 18 1.79086 18 4V14C18 16.2091 16.2091 18 14 18H4C1.79086 18 0 16.2091 0 14V4Z" fill="#1E2739" fill-opacity="0.8"/>' +
                                    '<path fill-rule="evenodd" clip-rule="evenodd" d="M13.8302 13.8213C14.0566 13.5832 14.0566 13.1972 13.8302 12.9591L9.81992 8.74166L13.3389 5.04093C13.5653 4.80284 13.5653 4.41681 13.3389 4.17871C13.1125 3.94061 12.7454 3.94061 12.519 4.17871L9.00005 7.87944L5.48098 4.17857C5.25457 3.94048 4.88751 3.94048 4.66111 4.17857C4.43471 4.41667 4.43471 4.8027 4.66111 5.04079L8.18018 8.74166L4.1698 12.9592C3.9434 13.1973 3.9434 13.5833 4.1698 13.8214C4.3962 14.0595 4.76327 14.0595 4.98967 13.8214L9.00005 9.60388L13.0103 13.8213C13.2367 14.0594 13.6038 14.0594 13.8302 13.8213Z" fill="white" fill-opacity="0.5"/>' +
                                    '</svg></a>' +
                                    '</span></li>');
                            } else {
                                $('#playlistSuccess').css('display','block').text('Video added to ' + title);
                                setTimeout(function () {
                                    $('#playlistSuccess').css('display','block').text('');
                                    $('#playlistModal button.close').trigger('click');
                                }, 1500);
                                $('#isnt_in').remove();
                                $('ul#videoInPlayList').append('<li data-list="'+res['slug']+'">'+
                                    '<span><a style="float:left" href="'+ arc_ajax_var.siteUrl +'/playlist/'+res['slug']+'" data-list="'+res['slug']+'/">' + title+ '</a>' +
                                    '<a style="float:right" class="removeFromPlaylist" data-post="'+arc_ajax_var.postId+'" data-list="'+res['slug']+'">' +
                                    '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                                    '<path d="M0 4C0 1.79086 1.79086 0 4 0H14C16.2091 0 18 1.79086 18 4V14C18 16.2091 16.2091 18 14 18H4C1.79086 18 0 16.2091 0 14V4Z" fill="#1E2739" fill-opacity="0.8"/>' +
                                    '<path fill-rule="evenodd" clip-rule="evenodd" d="M13.8302 13.8213C14.0566 13.5832 14.0566 13.1972 13.8302 12.9591L9.81992 8.74166L13.3389 5.04093C13.5653 4.80284 13.5653 4.41681 13.3389 4.17871C13.1125 3.94061 12.7454 3.94061 12.519 4.17871L9.00005 7.87944L5.48098 4.17857C5.25457 3.94048 4.88751 3.94048 4.66111 4.17857C4.43471 4.41667 4.43471 4.8027 4.66111 5.04079L8.18018 8.74166L4.1698 12.9592C3.9434 13.1973 3.9434 13.5833 4.1698 13.8214C4.3962 14.0595 4.76327 14.0595 4.98967 13.8214L9.00005 9.60388L13.0103 13.8213C13.2367 14.0594 13.6038 14.0594 13.8302 13.8213Z" fill="white" fill-opacity="0.5"/>' +
                                    '</svg></a>' +
                                    '</span></li>');
                            }

                        }
                    }
                }
            }
        });
    });//end save to playlist

    //remove from playlist
    $(document).on('click', '.removeFromPlaylist', function (e) {
        e.preventDefault();
        var postId = $(this).attr('data-post');
        var playlistSlug = $(this).attr('data-list');
        $.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'ARC_remove_from_playlist',
                nonce: arc_ajax_var.nonce,
                postId: postId,
                playlistSlug: playlistSlug,
            },
            success: function (res) {
                $('li[data-list='+ res +']').remove();
            }
        });
    });//end remove from playlist


    //add video to favorite


    jQuery('a#add_to_fav_video').click(function (e) {
        e.preventDefault();
        var postId = jQuery(this).attr('data-post');
        var add = jQuery(this).attr('data-add');
        var userID = jQuery(this).attr('data-user');
        if(add !== 'on') {
            jQuery.ajax({
                type: "post",
                url: arc_ajax_var.url,
                data: {
                    action: 'ARC_add_video_to_favorite',
                    nonce: arc_ajax_var.nonce,
                    postId: postId,
                    userID: userID,
                    add: add
                },
                success: function (res) {
                    //console.log('off' + res);
                },
            });
            jQuery('a#add_to_fav_video[data-post="' + postId + '"]').find('i').removeClass('fa-heart-o').addClass('fa-heart');
            jQuery('a#add_to_fav_video[data-post="' + postId + '"]').attr('data-add', 'on');
        } else {
            jQuery.ajax({
                type: "post",
                url: arc_ajax_var.url,
                data: {
                    action: 'ARC_add_video_to_favorite',
                    nonce: arc_ajax_var.nonce,
                    postId: postId,
                    userID: userID,
                    add: add
                },
                success: function (res) {
                    //console.log('on'+res);
                    jQuery('div#favorite_videos div.videos-list article[id="post-' + res + '"]').remove();
                        //var count_fav_video = jQuery('h2.fav_video span').text();
                        //jQuery('h2.fav_video span').text(count_fav_video - 1);

                }
            });
            jQuery('a#add_to_fav_video[data-post="' + postId + '"]').find('i').removeClass('fa-heart').addClass('fa-heart-o');
            jQuery('a#add_to_fav_video[data-post="' + postId + '"]').attr('data-add', 'off');
            if(arc_ajax_var.currentPageUrl == 'favorites') {
                jQuery('div#favorite_videos div.videos-list article[id="post-' + postId + '"]').remove();
                //var count_fav_video = jQuery('h2.fav_video span').text();
                //jQuery('h2.fav_video span').text(count_fav_video - 1);
            }
        }
    });
    jQuery('span#add_to_fav_video').click(function (e) {
        e.preventDefault();
        var postId = jQuery(this).attr('data-post');
        var add = jQuery(this).attr('data-add');
        var userID = jQuery(this).attr('data-user');
        if(add !== 'on') {
            jQuery.ajax({
                type: "post",
                url: arc_ajax_var.url,
                data: {
                    action: 'ARC_add_video_to_favorite',
                    nonce: arc_ajax_var.nonce,
                    postId: postId,
                    userID: userID,
                    add: add
                },
                success: function (res) {
                    //console.log('off' + res);
                },
            });
            jQuery('span#add_to_fav_video[data-post="' + postId + '"]').attr('data-add', 'on');
        } else {
            jQuery.ajax({
                type: "post",
                url: arc_ajax_var.url,
                data: {
                    action: 'ARC_add_video_to_favorite',
                    nonce: arc_ajax_var.nonce,
                    postId: postId,
                    userID: userID,
                    add: add
                },
                success: function (res) {
                    //console.log('on'+res);
                    jQuery('div#favorite_videos div.videos-list article[id="post-' + res + '"]').remove();
                    //var count_fav_video = jQuery('h2.fav_video span').text();
                    //jQuery('h2.fav_video span').text(count_fav_video - 1);

                }
            });
            jQuery('span#add_to_fav_video[data-post="' + postId + '"]').attr('data-add', 'off');
            if(arc_ajax_var.currentPageUrl == 'favorites') {
                jQuery('div#favorite_videos div.videos-list article[id="post-' + postId + '"]').remove();
            }
        }
    });

    //remove playlist
    $('article span.removePlaylist').on('click', function (e) {
        e.preventDefault();
        window.location.redirect = false;
        var playlistId = $(this).attr('data-list');
        var userId = $(this).attr('data-user');
        $.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'ARC_remove_playlist',
                nonce: arc_ajax_var.nonce,
                playlistId: playlistId,
                userId: userId
            },
            success: function (res) {
                $('article[data-list='+ res +']').remove();
            }
        });
    });//end remove playlist

    //subscribe user
    $('button.subscribe').on('click', function(){
        var email = $('#subscribe_email').val();
        if(email === '') return false;
        else {
            $.ajax({
                type: "post",
                url: arc_ajax_var.url,
                data: {
                    action: 'ARC_get_subscribers',
                    nonce: arc_ajax_var.nonce,
                    email: email
                },
                success: function (res) {
                    console.log(res);
                    if(res == 'error') {
                        $('p.subscribe-msg').text('Email is not valid').css('color', 'red');
                        setTimeout(function () {
                            $('p.subscribe-msg').text('');
                        }, 800);
                    } else {
                        $('p.subscribe-msg').text('You have successfully subscribed').css('color', 'green');
                        setTimeout(function () {
                            $('p.subscribe-msg').hide(700).text('');
                            $('div#subscribeModal button.close').trigger('click');
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }

    });//end subscribe user

    /****delete user video***/
    jQuery('#delete_user_video').click(function () {
        var post_id = jQuery(this).attr('data-post');
        jQuery.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'delete_user_video',
                nonce: arc_ajax_var.nonce,
                postId: post_id,
            },
            success: function (res) {
                if(res == 'delete') {
                    $('#modalDelMsg .modal-guts-del div h2').remove();
                    $('#modalDelMsg .modal-guts-del div span.confirm').remove();
                    $('#modalDelMsg .modal-guts-del').find('div').append('<h2>We have sent you an email</h2>');
                    $('#modalDelMsg .modal-guts-del div').append('<span class="confirm">Your video will be removed permanently once you confirm it.</span>');
                    $('.modal-overlay-del').css('z-index', '99999');
                    $('#modalDelMsg').show();
                }
            },
        });
    });

    jQuery('#modalDelMsg #close-button-del').on('click',function () {
        $('#modalDelMsg .modal-guts-del div h2').remove();
        $('#modalDelMsg .modal-guts-del div span.confirm').remove();
        $('#modalDelMsg').hide();
        $('.modal-overlay-del').css('z-index', '-1000');
    });/**** [end] delete user video***/


    /****delete video from playlist***/
    jQuery('span.delete_video_from_playlist').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var post_id = jQuery(this).attr('data-post-id');
        var term_slug = jQuery('#taxonomy_slug').val();
        jQuery.ajax({
            type: "post",
            url: arc_ajax_var.url,
            data: {
                action: 'delete_video_from_playlist',
                nonce: arc_ajax_var.nonce,
                post_id: post_id,
                term_slug: term_slug
            },
            success: function (res) {
                if(res == 'delete') {
                    jQuery('div.videos-list article#post-' + post_id).remove();
                }
            },
        });
    });
});