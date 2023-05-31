<?php
add_action('wp_ajax_set_min_required_characters', 'set_min_required_characters');
function set_min_required_characters() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce'))
		die ( 'Busted!');
	update_option('min_required_characters', $_POST['min']);
	wp_die();
}