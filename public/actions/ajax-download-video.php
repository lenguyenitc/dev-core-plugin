<?php
add_action("wp_ajax_ARC_save_video", "ARC_save_video");
add_action("wp_ajax_nopriv_ARC_save_video", "ARC_save_video");
function ARC_save_video() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die ( 'Busted!' );
	}
	$url = $_POST['link'];
	$path = explode('/themes/', get_template_directory())[0];

	if($_POST['format'] == 'mp4') $file_name = str_replace(' ', '_', mb_strtolower($_POST['video_name'])) . '.mp4';
	elseif($_POST['format'] == 'webm') $file_name = str_replace(' ', '_', mb_strtolower($_POST['video_name'])) . '.webm';

	$dir_to_save = $path . VICETEMPLECORE_UPLOAD . $file_name;
	$input = file_get_contents($url);
	file_put_contents($dir_to_save, $input);
	$data = [
		'status' => 'saved',
		'file' => $file_name
	];
	wp_send_json($data);
	wp_die();
}