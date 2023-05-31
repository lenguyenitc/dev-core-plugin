<?php
add_action( 'wp_ajax_save_user_post', 'save_user_post' );
function save_user_post()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $content = $_POST['content'];
        $content = str_ireplace("\n", '<br>', $content);
        $post_data = array(
            'post_title'    => sanitize_text_field( 'user post_' . get_current_user_id()  ),
            'post_content'  => strip_tags( $content, '<br>' ),
            'post_status'   => 'pending',
            'post_author'   => get_current_user_id(),
            'post_type'      => 'user_post',
        );

        $post_id = wp_insert_post( $post_data );
        if($post_id) {
	        send_letter_submit_posts_adm($post_id);
	        send_letter_submit_posts_user(get_current_user_id());
        }
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    wp_send_json($post_id);
}


/***Send user post report ***/
add_action( 'wp_ajax_send_user_post_report', 'send_user_post_report' );
add_action( 'wp_ajax_nopriv_send_user_post_report', 'send_user_post_report' );
function send_user_post_report()
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