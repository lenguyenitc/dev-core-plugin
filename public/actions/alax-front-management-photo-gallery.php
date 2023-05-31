<?php
add_action( 'wp_ajax_delete_gallery', 'delete_gallery' );
function delete_gallery()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
    	if($_POST['last_photo_for_delete'] === false) {
		    $res = confirm_delete_the_album(get_current_user_id(), $_POST['gallery_id'], false);
	    } else {
		    $res = confirm_delete_the_album(get_current_user_id(), $_POST['gallery_id'], $_POST['last_photo_for_delete']);
	    }
	    if ($res) {
		    $res = '1';
	    } else {
		    $res = '0';
	    }

    } else {
        wp_die( 'Forbidden', '', 403 );
    }


    wp_send_json($res);
}

add_action( 'wp_ajax_delete_one_photo', 'delete_one_photo' );
function delete_one_photo()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $str = $_POST['href'];
        $gallery_id = $_POST['gallery_id'];
        $flag = strpos($str, '?attachment_id=');
        if ($flag === false) {
            $length = strlen($str);

            $i = ($length - 1);
            $arr_litter = [];
            while($i >= 0){
                $arr_litter[] = $str[$i];
                $i--;
            }

            $j = 0;
            $res = [];
            if ($arr_litter[0] == '/') {
                foreach ($arr_litter as $v) {
                    if ($j == 0) {
                        $j++;
                        continue;
                    }
                    if ($v == '/') break;
                    $res[] = $v;
                }
            } else {
                foreach ($arr_litter as $v) {
                    if ($v == '/') break;
                    $res[] = $v;
                }
            }
            foreach (array_reverse($res) as $v) {
                $slag .= $v;
            }
            global $wpdb;
            $photo_id = $wpdb->get_row( "SELECT * FROM `wp_posts` WHERE `post_name` ='" . $slag . "'",ARRAY_A )['ID'];
        } else {
            $photo_id = explode('?attachment_id=', $str)[1];
        }

        $post_content = get_post($gallery_id, ARRAY_A)['post_content'];
        /** Delete from content gallery [start]**/
        $post_blocks = parse_blocks($post_content);
        foreach ($post_blocks as $block ) {
            if ( $block['blockName'] === 'core/gallery' && ! empty( $block['attrs']['ids'] ) ) {
                $all_photos_id = array_map(function ($image_id) {
                    return $image_id;
                }, $block['attrs']['ids'] );
            }
        }
        $key = array_search($photo_id, $all_photos_id);
        unset($all_photos_id[$key]);

        $ids = implode(',', $all_photos_id);


	    /*** delete from favorites ***/
        $get_all_users_id = get_users(['fields' => 'ID']);
        foreach($get_all_users_id as $item => $user_id) {
            $arr_favorite_photos = unserialize(get_user_meta($user_id, 'favorite_photos', true ));
            $key = array_search($photo_id, $arr_favorite_photos);
            unset($arr_favorite_photos[$key]);
            $new_favorite_photos = serialize($arr_favorite_photos);
            update_user_meta($user_id, 'favorite_photos', $new_favorite_photos);
        }
	    /*** [end] delete from favorites ***/


        $start_content = '<!-- wp:gallery {"ids":['.$ids.'],"columns":4,"linkTo":"post","sizeSlug":"full"} -->
                <figure class="wp-block-gallery columns-4 is-cropped">
                    <ul class="blocks-gallery-grid">';
        foreach ($all_photos_id as $id) {
                $center_content .= '<li class="blocks-gallery-item">
                            <figure>
                                <a href="'.site_url().'/?attachment_id='.$id.'">
                                    <img src="'.wp_get_attachment_image_url($id, 'full').'" 
                                        alt="" 
                                        data-id="'.$id.'" 
                                        data-full-url="'.wp_get_attachment_image_url($id, 'full').'" 
                                        data-link="'.site_url().'/?attachment_id='.$id.'" 
                                        class="wp-image-'.$id.'"/>
                                </a>
                            </figure>
                        </li>                      
                    ';

        }
        $end_content = '</ul>
                </figure><!-- /wp:gallery -->';
        $finish_content = $start_content.$center_content.$end_content;

        $new_album_data = [
            'ID' => $gallery_id,
            'post_content' => $finish_content
        ];
        wp_update_post($new_album_data);
        /** Delete from content gallery [end]**/

        /** Delete from attachment [start]**/
        $d = wp_delete_attachment($photo_id, true);
        /** Delete from attachment [end]**/


        /** Delete physical [start]**/
        $link = wp_get_attachment_image_url($photo_id, 'full');
        $upload_dir = wp_upload_dir()['basedir'];
        $file = explode('/wp-content/uploads/', $link)[1];
        $file_path = $upload_dir . '/'. $file;
        unlink($file_path);
        /** Delete physical [end]**/
    } else {
        wp_die( 'Forbidden', '', 403 );
    }

    wp_send_json($photo_id);
}