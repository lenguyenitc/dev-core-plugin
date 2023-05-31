<?php
add_action( 'wp_ajax_remove_updates_from_the_recent_activity', 'remove_updates_from_the_recent_activity' );
function remove_updates_from_the_recent_activity()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $res = update_post_meta( $_POST['post_id'], 'recent_activity', 'invisible' );
    } else {
        wp_die( 'Forbidden', '', 403 );
    }

    if ($res) {
        $res = $_POST['post_id'];
    }
    wp_send_json($res);
}


add_action('wp_ajax_remove_user_post_to_trash_on_front', 'remove_user_post_to_trash_on_front');
function remove_user_post_to_trash_on_front() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$postID = $_POST['postID'];
	global $wpdb;
	$wpdb->update('wp_posts',
		['post_status' => 'trash'],
		['ID' => $postID]
	);
	wp_send_json($postID);
}