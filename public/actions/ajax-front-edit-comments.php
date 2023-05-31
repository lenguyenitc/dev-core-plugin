<?php
add_action( 'wp_ajax_approve_comments', 'approve_comments' );
function approve_comments()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

	$comment_id     = $_POST['comments_id'];
	$comment_status = 'approve';
	$response = wp_set_comment_status( $comment_id, $comment_status );
    $response = (int)$response;
    wp_send_json($response);
}

add_action( 'wp_ajax_delete_comments', 'delete_comments' );
function delete_comments()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $comment_id = $_POST['comments_id'];
    $response   = wp_delete_comment($comment_id, true);

    $response = (int)$response;
    wp_send_json($comment_id);
}

add_action( 'wp_ajax_hold_comments', 'hold_comments' );
function hold_comments()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $comment_id     = $_POST['comments_id'];
    $comment_status = '0';
    $response = wp_set_comment_status( $comment_id, $comment_status );

    $response = (int)$response;
    wp_send_json($response);
}

/*****send comment to spam(report)*****/
add_action('wp_ajax_ARC_send_to_spam', 'ARC_send_to_spam');
add_action('wp_ajax_nopriv_ARC_send_to_spam', 'ARC_send_to_spam');
function ARC_send_to_spam() {
	$nonce = $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'ajax-nonce'))
		wp_die( 'Busted!');

	$reports = get_comment_meta($_POST['comment_id'], 'reports', true);
	$ip = $_SERVER['REMOTE_ADDR'];
	if($reports !== false) {
		if(!comment_hasAlreadySpam($_POST['comment_id'])) {
			$span_IPs[$ip] = time();
			update_comment_meta($_POST['comment_id'], "spam_IP", $span_IPs);
			update_comment_meta($_POST['comment_id'], 'reports', (int)$reports + 1);
		}
	} else {
		$span_IPs[$ip] = time();
		update_comment_meta($_POST['comment_id'], "spam_IP", $span_IPs);
		update_comment_meta($_POST['comment_id'], 'reports', 1);
	}


	/****letter for admin****/
	send_letter_report_comment();
	/****end letter for admin****/

	wp_send_json('spam');
	wp_die();
}/***** end send comment to spam(report)*****/