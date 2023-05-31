<?php
add_action( 'wp_ajax_ban_on_id', 'ban_on_id' );
function ban_on_id()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );
    $_POST['user_id'];
    if ($check_ajax_referer) {

        if ($_POST['user_id'] == false) {
            wp_die( 0 );
        }

        if (get_user_meta($_POST['user_id'], 'ban_on_id', true) === 'active') {
            wp_die( 0 );
        }
        $response = update_user_meta( $_POST['user_id'], 'ban_on_id', 'active' );
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    if ($response) {
        $response = $_POST['user_id'];
    }
    wp_send_json( $response );
}

add_action( 'wp_ajax_unban_on_id', 'unban_on_id' );
function unban_on_id()
{
    if (empty($_POST['nonce'])) {
        wp_die( 0 );
    }

    $check_ajax_referer = check_ajax_referer(
        'ajax-nonce',
        'nonce',
        false );
    $_POST['user_id'];
    if ($check_ajax_referer) {

        if ($_POST['user_id'] == false) {
            wp_die( 0 );
        }
        $response = delete_user_meta( $_POST['user_id'], 'ban_on_id' );
    } else {
        wp_die( 'Forbidden', '', 403 );
    }
    if ($response) {
        $response = $_POST['user_id'];
    }
    wp_send_json( $response );
}