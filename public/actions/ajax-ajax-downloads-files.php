<?php
add_action( 'wp_ajax_ajax_fileload', 'ajax_file_upload_callback');
function ajax_file_upload_callback(){
    check_ajax_referer( 'ajax-nonce', 'nonce' );

    if(empty($_FILES))
        wp_send_json_error('Files empty.');
    else {
        $post_id = (int) $_POST['post_id'];
        $arr_names_str = $_POST['arr_names'];
        $arr = explode('~|~',$arr_names_str);
        $arr_names = [];
        foreach ($arr as $v) {
            if ($v !== '') {
                $arr_names[] = explode('~~', $v);
            }
        }

        global $wpdb;
        $album = htmlentities(trim($_POST['album']));
        // disallow the file size
        $sizedata = getimagesize( $_FILES['userfile']['tmp_name'] );
        $max_size = 3000;
        if( $sizedata[0]/*width*/ > $max_size || $sizedata[1]/*height*/ > $max_size )
            wp_send_json_error( __('The picture can\'t be bigger than '. $max_size .'px in width or height.', 'arc') );

        // file upload
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        // filter allows mime type of files
        add_filter( 'upload_mimes', function( $mimes ){
            return [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            ];
        } );

        $uploaded_imgs = [];

        foreach( $_FILES as $file_id => $data ){

            $attach_id = media_handle_upload( $file_id, $post_id );
//===========================================
            $post_title = get_post($attach_id, ARRAY_A)['post_title'];

	        $old_post_name = get_post($attach_id, ARRAY_A)['post_name'];
	        $flag = true;
	        for ($i = 0; $i < mb_strlen($old_post_name); $i++) {
		        if (ctype_digit($old_post_name[$i]) === false) {
			        $flag = false;
			        break;
		        }
	        }
	        if ($flag) {
		        $new_post_name = $old_post_name . '-img';
	        }
	        $update_attachment = [];
	        $update_attachment['ID'] = $attach_id;
	        $update_attachment['post_name'] = $new_post_name;
	        wp_update_post( wp_slash($update_attachment) );

	        $get_post_name = get_post($attach_id, ARRAY_A)['post_name'];

            foreach ($arr_names as $v) {
                if ($post_title === explode('.', $v[0])[0]) {
                    $my_post = [];
                    $my_post['ID'] = $attach_id;
                    $my_post['post_title'] = htmlentities($v[1]);
                    wp_update_post( wp_slash($my_post) );
                }
            }
//==========================================
            if( is_wp_error( $attach_id ) ) {
                $uploaded_imgs[] = 'File upload error `'. $data['name'] .'`: '. $attach_id->get_error_message();
                wp_send_json_error($uploaded_imgs);
            }
            else {
                $uploaded_imgs[] = wp_get_attachment_url($attach_id);
                $images_array[] = [
                    $attach_id => wp_get_attachment_url($attach_id)
                ];
            }
        }
        foreach ($images_array as $image) {
            foreach ( $image as $id => $url) {
                $ids .= $id .',';
            }
        }
        $ids = mb_substr($ids, 0, -1); //cut last symbol ','
        $start_content = '<!-- wp:gallery {"ids":['.$ids.'],"columns":4,"linkTo":"post"} -->
                <figure class="wp-block-gallery columns-4 is-cropped">
                    <ul class="blocks-gallery-grid">';
        foreach ($images_array as $image) {
            foreach ($image as $id => $url) {
                $center_content .= '<li class="blocks-gallery-item">
                            <figure>
                                <a href="'.site_url().'/?attachment_id='.$id.'">
                                    <img src="'.wp_get_attachment_image_url($id, 'large').'" 
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
        }
        $end_content = '</ul>
                </figure><!-- /wp:gallery -->';
        $finish_content = $start_content.$center_content.$end_content;

        $wpdb->insert(
            'wp_posts',
            [
                'post_author' => get_current_user_id(),
                'post_content' => $finish_content,
                'post_title' => $album,
                'post_status' => 'pending',
                'post_type' => 'photos',
            ],
            ['%d', '%s','%s', '%s', '%s']
        );

        $post_id = $wpdb->get_row("SELECT `ID` FROM `wp_posts` WHERE `post_title` = '". (string)$album . "'", ARRAY_A)['ID'];

        update_post_meta($post_id, 'photo_gallery_views', 0);


        if(!empty($_POST['photo_tags'])) {
            if($post_id) {
                $arr_tags = explode(',',strip_tags($_POST['photo_tags']));
                foreach($arr_tags as $tag){
                    $term_exist = term_exists($tag, 'photos_tag');
                    if(!$term_exist) {
                        wp_set_object_terms($post_id, $tag, 'photos_tag', true);
                    } else {
                        $wpdb->insert(
                            $wpdb->term_relationships,
                            array(
                                'object_id'        => $post_id,
                                'term_taxonomy_id' => $term_exist['term_id'],
                            )
                        );
                    }
                }
            }
        }        
        if(!empty($_POST['photo_categories'])) {
            if($post_id) {
                $photo_categories = strip_tags($_POST['photo_categories']);
                wp_set_object_terms($post_id, intval($photo_categories), 'photos_category', true);
            }
        }
        /****letter for admin****/
        send_letter_submit_photos_adm($post_id);
        /****end letter for admin****/

        /****letter for current user****/
        send_letter_submit_photos_user(wp_get_current_user());
        /****end letter for current user****/

        wp_send_json_success($uploaded_imgs);
    }
}