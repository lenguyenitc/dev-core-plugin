<?php
add_action('wp_ajax_ARC_convert_video', 'ARC_convert_video');
function ARC_convert_video(){
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');

	// emulation Post data
	$resolution = $_POST['resolution'];
	$fileName = str_replace(' ', '-', $_POST['fileName']);
	$fileUrl = $_POST['fileUrl'];
	$path = explode('/', parse_url($fileUrl, PHP_URL_PATH));
	$uploadTo = '../wp-content/uploads/'. $path[3] . '/' . $path[4] . '/';
	$wp_upload_dir = wp_upload_dir();

	// Create preview
	if(file_exists( '../wp-content/uploads/'. $path[3] . '/' . $path[4] . '/' . '240_preview' . $fileName . '.mp4') === false) {
		$command_for_create_preview = 'ffmpeg -ss 00:00:07 -t 00:00:07 -i ' . $fileUrl . ' -vcodec copy -acodec copy ' . $uploadTo . 'preview_' . $fileName . '.mp4';
		exec($command_for_create_preview);
		$command = 'ffmpeg -i ' . $uploadTo . 'preview_' . $fileName . '.mp4' . ' -s  426x240 -c:a copy ' . $uploadTo . '240_preview' . $fileName . '.mp4';
		exec($command);
		unlink($uploadTo . 'preview_' . $fileName . '.mp4');

		//insert preview to media library
		//$file = $path[3] . '/' . $path[4] . '/preview_' . $fileName . '.mp4';
		$file = $path[3] . '/' . $path[4] . '/240_preview' . $fileName . '.mp4';
		$filetype = wp_check_filetype(basename($file), null);
		$attachment_preview = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($file),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($file)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$scaled_video_attachment_id = wp_insert_attachment($attachment_preview, $file, 0);
		$scaled_video_attachment_data = wp_generate_attachment_metadata($scaled_video_attachment_id, $wp_upload_dir['url'] . '/' . basename($file));
		wp_update_attachment_metadata($scaled_video_attachment_id, $scaled_video_attachment_data);
	}
	if(file_exists('../wp-content/uploads/'. $path[3] . '/' . $path[4] . '/' . 'screenshot_' . $fileName . '.jpg') === false) {
		// Create screenshot
		$command_for_create_screenshot = 'ffmpeg -i ' . $fileUrl . ' -an -ss 00:00:05 -r 1 -vframes 1 -s 640x360 -y -f mjpeg ' . $uploadTo . 'screenshot_' . $fileName . '.jpg';
		exec( $command_for_create_screenshot );

		//insert screenshot to media library
		$file_img              = $path[3] . '/' . $path[4] . '/screenshot_' . $fileName . '.jpg';
		$filetype_img          = wp_check_filetype( basename( $file_img ), null );
		$attachment_screenshot = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $file_img ),
			'post_mime_type' => $filetype_img['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_img ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id             = wp_insert_attachment( $attachment_screenshot, $file_img, 0 );
		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $wp_upload_dir['url'] . '/' . basename( $file_img ) );
		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );
	}
	// Redirect section
	if($resolution == 1440) {
		$command = 'ffmpeg -i ' . $fileUrl . ' -s 2560x1440 -c:a copy ' . $uploadTo . '1440_' . $fileName . '.mp4';
		exec($command);

		$file_1440 = $path[3] . '/' . $path[4] . '/1440_' . $fileName . '.mp4';
		$filetype = wp_check_filetype(basename($file_1440), null);
		$attachment_1440 = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($file_1440),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($file_1440)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$scaled_video_attachment_id = wp_insert_attachment($attachment_1440, $file_1440, 0);
		$scaled_video_attachment_data = wp_generate_attachment_metadata($scaled_video_attachment_id, $wp_upload_dir['url'] . '/' . basename($file_1440));
		wp_update_attachment_metadata($scaled_video_attachment_id, $scaled_video_attachment_data);
		die;
	} elseif($resolution == 1080) {
		$command = 'ffmpeg -i ' . $fileUrl . ' -s  1920x1080 -c:a copy ' . $uploadTo . '1080_' . $fileName . '.mp4';
		exec($command);

		$file_1080 = $path[3] . '/' . $path[4] . '/1080_' . $fileName . '.mp4';
		$filetype = wp_check_filetype(basename($file_1080), null);
		$attachment_1080 = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($file_1080),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($file_1080)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$scaled_video_attachment_id = wp_insert_attachment($attachment_1080, $file_1080, 0);
		$scaled_video_attachment_data = wp_generate_attachment_metadata($scaled_video_attachment_id, $wp_upload_dir['url'] . '/' . basename($file_1080));
		wp_update_attachment_metadata($scaled_video_attachment_id, $scaled_video_attachment_data);
		die;
	} elseif($resolution == 720) {
		$command = 'ffmpeg -i ' . $fileUrl . ' -s  1280x720 -c:a copy ' . $uploadTo . '720_' . $fileName . '.mp4';
		exec($command);

		$file_720 = $path[3] . '/' . $path[4] . '/720_' . $fileName . '.mp4';
		$filetype = wp_check_filetype(basename($file_720), null);
		$attachment_720 = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($file_720),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($file_720)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$scaled_video_attachment_id = wp_insert_attachment($attachment_720, $file_720, 0);
		$scaled_video_attachment_data = wp_generate_attachment_metadata($scaled_video_attachment_id, $wp_upload_dir['url'] . '/' . basename($file_720));
		wp_update_attachment_metadata($scaled_video_attachment_id, $scaled_video_attachment_data);
		die;
	} elseif($resolution == 480) {
		$command = 'ffmpeg -i ' . $fileUrl . ' -s  854x480 -c:a copy ' . $uploadTo . '480_' . $fileName . '.mp4';
		exec($command);

		$file_480 = $path[3] . '/' . $path[4] . '/480_' . $fileName . '.mp4';
		$filetype = wp_check_filetype(basename($file_480), null);
		$attachment_480 = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($file_480),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($file_480)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$scaled_video_attachment_id = wp_insert_attachment($attachment_480, $file_480, 0);
		$scaled_video_attachment_data = wp_generate_attachment_metadata($scaled_video_attachment_id, $wp_upload_dir['url'] . '/' . basename($file_480));
		wp_update_attachment_metadata($scaled_video_attachment_id, $scaled_video_attachment_data);
		die;
	} elseif($resolution == 360) {
		$command = 'ffmpeg -i ' . $fileUrl . ' -s  640x360 -c:a copy ' . $uploadTo . '360_' . $fileName . '.mp4';
		exec($command);

		$file_360 = $path[3] . '/' . $path[4] . '/360_' . $fileName . '.mp4';
		$filetype = wp_check_filetype(basename($file_360), null);
		$attachment_360 = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($file_360),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($file_360)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$scaled_video_attachment_id = wp_insert_attachment($attachment_360, $file_360, 0);
		$scaled_video_attachment_data = wp_generate_attachment_metadata($scaled_video_attachment_id, $wp_upload_dir['url'] . '/' . basename($file_360));
		wp_update_attachment_metadata($scaled_video_attachment_id, $scaled_video_attachment_data);
		die;
	} elseif($resolution == 240) {
		$command = 'ffmpeg -i ' . $fileUrl . ' -s  426x240 -c:a copy ' . $uploadTo . '240_' . $fileName . '.mp4';
		exec($command);

		$file_240 = $path[3] . '/' . $path[4] . '/240_' . $fileName . '.mp4';
		$filetype = wp_check_filetype(basename($file_240), null);
		$attachment_240 = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($file_240),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($file_240)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$scaled_video_attachment_id = wp_insert_attachment($attachment_240, $file_240, 0);
		$scaled_video_attachment_data = wp_generate_attachment_metadata($scaled_video_attachment_id, $wp_upload_dir['url'] . '/' . basename($file_240));
		wp_update_attachment_metadata($scaled_video_attachment_id, $scaled_video_attachment_data);
		die;
	}
}

add_action('wp_ajax_ARC_get_height_video', 'ARC_get_height_video');
function ARC_get_height_video() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$command_get_info = 'ffmpeg -i ' . $_POST['fileUrl']. ' -hide_banner 2>info.txt';
	exec($command_get_info);
	$info = file_get_contents('info.txt');
	$info_arr = explode(PHP_EOL, $info);
	$info = explode('x', $info_arr[7])[2];
	$info = explode(',', $info)[0];
	$info = (int)$info;
	wp_send_json($info);
	wp_die();
}