<?php
add_action('wp_ajax_confirm_deleting_users_account', 'confirm_deleting_users_account');
function confirm_deleting_users_account()
{
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	confirm_delete_the_account(wp_get_current_user());
	wp_die();
}