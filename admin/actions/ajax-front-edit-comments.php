<?php
add_action( 'wp_ajax_approve_comments', 'approve_comments' );
function approve_comments()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'my_nonce_for_examle_ajax',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $comment_id     = $_POST['comments_id'];
        $comment_status = 'approve';
        $response = wp_set_comment_status( $comment_id, $comment_status );
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    $response = (int)$response;
    wp_send_json($response);
}

add_action( 'wp_ajax_delete_comments', 'delete_comments' );
function delete_comments()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'my_nonce_for_examle_ajax',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $comment_id = $_POST['comments_id'];
        $response   = wp_delete_comment($comment_id, true);
    } else {
        wp_die('Forbidden', '', 403);
    }
    $response = (int)$response;
    wp_send_json($comment_id);
}

add_action( 'wp_ajax_hold_comments', 'hold_comments' );
function hold_comments()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'my_nonce_for_examle_ajax',
        'nonce',
        false );

    if ($check_ajax_referer) {
        $comment_id     = $_POST['comments_id'];
        $comment_status = '0';
        $response = wp_set_comment_status( $comment_id, $comment_status );
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    $response = (int)$response;
    wp_send_json($response);
}
