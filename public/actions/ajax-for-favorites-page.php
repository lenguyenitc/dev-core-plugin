<?php
add_action( 'wp_ajax_delete_photo_from_favorite', 'delete_photo_from_favorite' );
//add_action("wp_ajax_nopriv_next_photo_fs", "next_photo_fs");
function delete_photo_from_favorite()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $arr_favorite_photos = get_user_meta( $_POST['user_id'], 'favorite_photos', true );
        $arr_favorite_photos = unserialize($arr_favorite_photos);

        if (array_search($_POST['photo_id'], $arr_favorite_photos) !== false) {
            $key = array_search($_POST['photo_id'], $arr_favorite_photos);
            unset($arr_favorite_photos[$key]);

            $arr_favorite_photos = serialize($arr_favorite_photos);
            update_user_meta( $_POST['user_id'], 'favorite_photos', $arr_favorite_photos );
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }

    wp_send_json($arr_favorite_photos);
}