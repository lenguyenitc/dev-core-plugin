<?php
add_action( 'wp_ajax_get_photo_id_from_gallery', 'get_photo_id_from_gallery' );
function get_photo_id_from_gallery()
{
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$response = wp_set_object_terms($_POST['postID'], NULL, 'post_tag');
	$id = attachment_url_to_post_id($_POST['url']);
	wp_send_json($id);
}