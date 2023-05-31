<?php
add_action( 'wp_ajax_add_photo_to_favorite', 'add_photo_to_favorite' );
function add_photo_to_favorite()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

   $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        //delete_user_meta( $_POST['user_id'], 'favorite_photos' );
        $arr_favorite_photos = get_user_meta( $_POST['user_id'], 'favorite_photos', true );
        if ($arr_favorite_photos === '') {
            $arr = [];
            $arr[] = $_POST['photo_id'];
            $value = serialize($arr);
            update_user_meta( $_POST['user_id'], 'favorite_photos', $value );
            $response = 'start';
        } else {
            $arr_favorite_photos = unserialize($arr_favorite_photos);
            if (array_search($_POST['photo_id'], $arr_favorite_photos) === false) {
                $arr_favorite_photos[] = $_POST['photo_id'];
                $value = serialize($arr_favorite_photos);
                update_user_meta( $_POST['user_id'], 'favorite_photos', $value );
                $response = 'add';
            } else {
                $key = array_search($_POST['photo_id'], $arr_favorite_photos);
                unset($arr_favorite_photos[$key]);
                $value = serialize($arr_favorite_photos);
                update_user_meta( $_POST['user_id'], 'favorite_photos', $value );
                $response = 'delete';
            }
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    wp_send_json($response);
}

add_action( 'wp_ajax_autoplay', 'autoplay' );
add_action("wp_ajax_nopriv_autoplay", "autoplay");
function autoplay()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $photo_id   = $_POST['photo_id'];
        $gallery_id = $_POST['gallery_id'];
        function get_post_block_gallery_images($photo_id, $gallery_id) {
            $post = get_post( $gallery_id);

            $all_id_for_each_gallery = [];

                $post_blocks = parse_blocks( $post->post_content );
                foreach ( $post_blocks as $block ) {
                    if ( $block['blockName'] === 'core/gallery' && ! empty( $block['attrs']['ids'] ) ) {
                        $all_id_for_each_gallery[$post->ID] = array_map( function ( $image_id ) {
                            return $image_id;
                        }, $block['attrs']['ids'] );
                    }
                }

            foreach ($all_id_for_each_gallery as $one_gallery) {
                if (array_search($photo_id, $one_gallery) !== false) {
                    return $one_gallery;
                }
            }
            return null;
        }
        $all_photos_id_from_gallery = get_post_block_gallery_images($photo_id, $gallery_id);
        $key = array_search($photo_id, $all_photos_id_from_gallery);
        if ($key !== false) {
            if ((count($all_photos_id_from_gallery) - 1) == $key) {
                $key = 0;
            } else {
                $key++;
            }

            $res['img']    = wp_get_attachment_image($all_photos_id_from_gallery[$key], 'full', ['class'=>'gallery_photo full-img']);
            $res['newId'] = $all_photos_id_from_gallery[$key];
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }


    wp_send_json($res);
}

add_action( 'wp_ajax_next_photo_fs', 'next_photo_fs' );
add_action("wp_ajax_nopriv_next_photo_fs", "next_photo_fs");
function next_photo_fs()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $res = [];
        $photo_id   = $_POST['photo_id'];
        $gallery_id = $_POST['gallery_id'];
        function get_post_block_gallery_images($photo_id, $gallery_id) {
            $post = get_post( $gallery_id);

            $all_id_for_each_gallery = [];

            $post_blocks = parse_blocks( $post->post_content );
            foreach ( $post_blocks as $block ) {
                if ( $block['blockName'] === 'core/gallery' && ! empty( $block['attrs']['ids'] ) ) {
                    $all_id_for_each_gallery[$post->ID] = array_map( function ( $image_id ) {
                        return $image_id;
                    }, $block['attrs']['ids'] );
                }
            }

            foreach ($all_id_for_each_gallery as $one_gallery) {
                if (array_search($photo_id, $one_gallery) !== false) {
                    return $one_gallery;
                }
            }
            return null;
        }
        $all_photos_id_from_gallery = get_post_block_gallery_images($photo_id, $gallery_id);
        $key = array_search($photo_id, $all_photos_id_from_gallery);
        if ($key !== false) {
            if ((count($all_photos_id_from_gallery) - 1) == $key) {
                $key = 0;
            } else {
                $key++;
            }
            /** local */
            $parent_id = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_parent'];
            if ($parent_id === 0) {
                $urn = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_name'];
            } else {
                $url_part_one = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_name'];
                $url_part_two = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_name'];
                $urn   = $url_part_two . '/' . $url_part_one;
            }
            $res['urn'] = $urn;
            /** local */
            $res['img']    = wp_get_attachment_image($all_photos_id_from_gallery[$key], 'full', ['class'=>'gallery_photo full-img']);
            $res['newId'] = $all_photos_id_from_gallery[$key];
            $res['photo_id'] = $photo_id;
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }

    wp_send_json($res);
}

add_action( 'wp_ajax_prev_photo_fs', 'prev_photo_fs' );
add_action("wp_ajax_nopriv_prev_photo_fs", "prev_photo_fs");
function prev_photo_fs()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $photo_id   = $_POST['photo_id'];
        $gallery_id = $_POST['gallery_id'];
        function get_post_block_gallery_images($photo_id, $gallery_id) {
            $post = get_post( $gallery_id);

            $all_id_for_each_gallery = [];

            $post_blocks = parse_blocks( $post->post_content );
            foreach ( $post_blocks as $block ) {
                if ( $block['blockName'] === 'core/gallery' && ! empty( $block['attrs']['ids'] ) ) {
                    $all_id_for_each_gallery[$post->ID] = array_map( function ( $image_id ) {
                        return $image_id;
                    }, $block['attrs']['ids'] );
                }
            }

            foreach ($all_id_for_each_gallery as $one_gallery) {
                if (array_search($photo_id, $one_gallery) !== false) {
                    return $one_gallery;
                }
            }
            return null;
        }
        $all_photos_id_from_gallery = get_post_block_gallery_images($photo_id, $gallery_id);
        $key = array_search($photo_id, $all_photos_id_from_gallery);
        if ($key !== false) {
            if ($key === 0) {
                $key = (count($all_photos_id_from_gallery) - 1);
            } else {
                $key--;
            }
            /** local */
            $parent_id = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_parent'];
            if ($parent_id === 0) {
                $urn = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_name'];
            } else {
                $url_part_one = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_name'];
                $url_part_two = get_post( $all_photos_id_from_gallery[$key], ARRAY_A )['post_name'];
                $urn   = $url_part_two . '/' . $url_part_one;
            }
            $res['urn'] = $urn;
            /** local */
            $res['img'] = wp_get_attachment_image($all_photos_id_from_gallery[$key], 'full', ['class' => 'gallery_photo full-img']);
            $res['newId'] = $all_photos_id_from_gallery[$key];
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }


    wp_send_json($res);
}

add_action( 'wp_ajax_change_full_image_by_click_on_photo', 'change_full_image_by_click_on_photo' );
add_action("wp_ajax_nopriv_change_full_image_by_click_on_photo", "change_full_image_by_click_on_photo");
function change_full_image_by_click_on_photo()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $photo_id   = $_POST['photo_id'];
        $gallery_id = $_POST['gallery_id'];
        function get_post_block_gallery_images($photo_id, $gallery_id) {
            $post = get_post( $gallery_id);

            $all_id_for_each_gallery = [];

            $post_blocks = parse_blocks( $post->post_content );
            foreach ( $post_blocks as $block ) {
                if ( $block['blockName'] === 'core/gallery' && ! empty( $block['attrs']['ids'] ) ) {
                    $all_id_for_each_gallery[$post->ID] = array_map( function ( $image_id ) {
                        return $image_id;
                    }, $block['attrs']['ids'] );
                }
            }

            foreach ($all_id_for_each_gallery as $one_gallery) {
                if (array_search($photo_id, $one_gallery) !== false) {
                    return $one_gallery;
                }
            }
            return null;
        }
        $all_photos_id_from_gallery = get_post_block_gallery_images($photo_id, $gallery_id);
        $key = array_search($photo_id, $all_photos_id_from_gallery);
        if ($key !== false) {
            /** local */
            $parent_id = get_post( $photo_id, ARRAY_A )['post_parent'];
            if ($parent_id === 0) {
                $urn = get_post( $photo_id, ARRAY_A )['post_name'];
            } else {
                $url_part_one = get_post( $photo_id, ARRAY_A )['post_name'];
                $url_part_two = get_post( $photo_id, ARRAY_A )['post_name'];
                $urn   = $url_part_two . '/' . $url_part_one;
            }
            $res['urn'] = $urn;
            /** local */
            $res['img'] = wp_get_attachment_image($photo_id, 'full', ['class' => 'gallery_photo full-img']);
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    wp_send_json($res);
}

add_action( 'wp_ajax_rerender_heart', 'rerender_heart' );
add_action("wp_ajax_nopriv_rerender_heart", "rerender_heart");
function rerender_heart()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $arr_favorite_photos = get_user_meta( get_current_user_id(), 'favorite_photos', true );
        $arr_favorite_photos = unserialize($arr_favorite_photos);
        if (array_search($_POST['photo_id'], $arr_favorite_photos) === false || !is_user_logged_in()){
            $response = '0';
        } else {
            $response = '1';
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    wp_send_json($response);
}

add_action( 'wp_ajax_send_photo_report', 'send_photo_report' );
add_action( 'wp_ajax_nopriv_send_photo_report', 'send_photo_report' );
function send_photo_report()
{
	if (empty($_POST['nonce'])) {
		wp_die( 0 );
	}

	$check_ajax_referer = check_ajax_referer(
		'ajax-nonce',
		'nonce',
		false );

	if ($check_ajax_referer) {
		global $wpdb;
		$table = $wpdb->prefix. 'reportMsg';
		$msg_data = [
			'date' => date("Y-m-d H:i:s"),
			'msg' => strip_tags($_POST['msg']),
			'type' => $_POST['type'],
			'postId' => $_POST['post_id'],
		];
		$types = ['%s', '%s', '%s', '%s'];
		$wpdb->insert($table, $msg_data, $types);

		/****letter for admin****/
		send_letter_report_video($_POST['type']);
		/****end letter for admin****/

		wp_send_json('success');
		wp_die();
	} else {
		wp_die( 'Forbidden', '', 403 );
	}
}