<?php
/** Send message [start]*/
add_action('wp_ajax_send_message', 'send_message');
add_action('wp_ajax_nopriv_send_message', 'send_message');
function send_message(){
	if(empty($_POST['nonce'])){
		wp_die( 0 );
	}
	$check_ajax_referer = check_ajax_referer( 'ajax-nonce', 'nonce', false );
	if ($check_ajax_referer) {
		$text_message = $_POST['text_message'];
		$trim = trim($_POST['text_message']);
		if(empty($text_message) || $trim == '' || wp_get_current_user()->ID != $_POST['id_sender']){
			wp_send_json('0');
		}

		$id_recipient = $_POST['id_recipient'];
		$id_sender = $_POST['id_sender'];
		$date_sent = time();
		$status = 0;
		global $wpdb;
		$table = $wpdb->prefix . 'chat';
		$wpdb->insert(
			$table,
			array( 'sender_id' => $id_sender, 'recepient_id' => $id_recipient, 'message_text' => $text_message, 'date_sent' => $date_sent, 'status' => $status ),
			array( '%d', '%d', '%s', '%d', '%d' )
		);
	} else {
		wp_die( 'Forbidden', '', 403 );
	}
	wp_send_json('1');
}
/** Send message [end]*/

/** Check status [start]*/
add_action('wp_ajax_check_status', 'check_status');
add_action('wp_ajax_nopriv_check_status', 'check_status');
function check_status(){
	if(empty($_POST['nonce'])){
		wp_die( 0 );
	}

	$check_ajax_referer = check_ajax_referer( 'ajax-nonce', 'nonce', false );
	if ($check_ajax_referer) {
		global $wpdb;
		$table = $wpdb->prefix . 'chat';
		$id_recipient = $_POST['id_recipient'];
		$status = 0;
		$res = $wpdb->get_row( "SELECT * FROM $table WHERE recepient_id = '" . $id_recipient . "' AND status = '" . $status . "'"  );
	} else {
		wp_die( 'Forbidden', '', 403 );
	}
	wp_send_json($res);
}
/** Check status [end]*/

/** Render list message [start]*/
add_action('wp_ajax_render_list_message', 'render_list_message');
function render_list_message(){
	$start_id = $_POST['start_id'];
	global $wpdb;
	$table = $wpdb->prefix . 'chat';
	$all_message_sender = $wpdb->get_col( "SELECT message_text FROM $table WHERE sender_id = '" . $start_id . "' AND recepient_id = '" . wp_get_current_user()->ID . "'" );
	$time_sender = $wpdb->get_col( "SELECT date_sent FROM $table WHERE sender_id = '" . $start_id . "' AND recepient_id = '" . wp_get_current_user()->ID . "'" );
	$all_message_recipient = $wpdb->get_col( "SELECT message_text FROM $table WHERE sender_id = '" . wp_get_current_user()->ID . "' AND recepient_id = '" . $start_id . "'" );
	$time_recipient = $wpdb->get_col( "SELECT date_sent FROM $table WHERE sender_id = '" . wp_get_current_user()->ID . "' AND recepient_id = '" . $start_id . "'" );

	$i = 0;
	$final_array = [];
	foreach($all_message_sender as $value){
		$final_array[$time_sender[$i]] = [
			'sender',
			$value,
		];
		$i++;
	}
	$i = 0;
	foreach($all_message_recipient as $value){
		$final_array[$time_recipient[$i]] = [
			'recipient',
			$value,
		];
		$i++;
	}
	ksort($final_array);

	$response = '';
	foreach($final_array as $key => $v):
		if($v[0] === 'sender'):
			if(get_user_meta($start_id, 'personal_foto', true) != false) {
				$avatarSender = get_user_meta($start_id,'personal_foto', true);
			} else $avatarSender = get_template_directory_uri(). '/assets/img/picture.png';

			$response .='<article>
                    <div class="avatar">
                        <img alt="avatar" style="max-width: 40px" src="'. $avatarSender . '" />
                    </div>
                    <div class="msg">
                        <div class="tri"></div>
                        <div class="msg_inner">' .$v[1]. '<br><span class="time_msg" data-time="'.$key .'">'. date('Y-m-d H:i', ($key - $_POST['offset']*60)) . '</span></div>
                    </div>
                </article>';
		else:
			if(get_user_meta($start_id, 'personal_foto', true) != false) {
				$avatarRecepient = get_user_meta(wp_get_current_user()->ID,'personal_foto', true);
			} else $avatarRecepient = get_template_directory_uri(). '/assets/img/picture.png';
			$response .='<article class="right">
                    <div class="avatar">
                        <img alt="avatar" style="max-width: 40px" src="'. $avatarRecepient . '" />
                    </div>
                    <div class="msg">
                        <div class="tri"></div>
                        <div class="msg_inner">' .$v[1]. '<br><span class="time_msg" data-time="'.$key .'">'.date('Y-m-d H:i', ($key - $_POST['offset']*60)) .'</span></div>
                    </div>
                </article>';
		endif;
	endforeach;

	$value = 0;
	$array_status = $wpdb->get_col( "SELECT status FROM $table WHERE status = '" . $value . "' AND sender_id = '" . $start_id . "'" );
	if($array_status){
		$wpdb->update( $table,
			array( 'status' => 1 ),
			array( 'status' => 0, 'sender_id' => $start_id )
		);
	}

	wp_send_json($response);
}
/** Render list message [end]*/

/** Check status for new message [start]*/
add_action('wp_ajax_check_status_for_new_message', 'check_status_for_new_message');
function check_status_for_new_message(){
	if(empty($_POST['nonce'])){
		wp_die( 0 );
	}

	$check_ajax_referer = check_ajax_referer( 'my_nonce_for_ban_ip_ajax', 'nonce', false );

	if ($check_ajax_referer) {
		$id_sender = $_POST['id_sender'];
		global $wpdb;
		$table = $wpdb->prefix . 'chat';
		$value = 0;
		$array_status = $wpdb->get_col( "SELECT status FROM $table WHERE status = '" . $value . "' AND sender_id = '" . $id_sender . "'" );
	} else {
		wp_die( 'Forbidden', '', 403 );
	}
	wp_send_json($array_status);
}
/** Check status for new message  [end]*/

/** Send message response [start]*/
add_action('wp_ajax_send_message_response', 'send_message_response');
function send_message_response(){
	if(empty($_POST['nonce'])){
		wp_die( 0 );
	}

	$check_ajax_referer = check_ajax_referer( 'my_nonce_for_ban_ip_ajax', 'nonce', false );

	if ( $check_ajax_referer) {
		$text_message = $_POST['text_message'];
		$id_recipient = $_POST['id_recipient'];
		$id_sender = $_POST['id_sender'];
		$date_sent = time();
		$status = 0;
		global $wpdb;
		$table = $wpdb->prefix . 'chat';
		$wpdb->insert(
			$table,
			array( 'sender_id' => $id_sender, 'recepient_id' => $id_recipient, 'message_text' => $text_message, 'date_sent' => $date_sent, 'status' => $status ),
			array( '%d', '%d', '%s', '%d', '%d' )
		);

		/****letter for recepient****/
		/*$subAdm = 'New message from user!';
		$admMsg  = '<h2>You got a new message from user.</h2>';
		$admMsg .= '<p>Check your <a href="'.site_url().'/chat/?xxx='. $id_sender . '">chat page</a></p>';
		$headers = [
			'From: '. get_userdata($id_sender)->display_name. ' <'. get_userdata($id_sender)->user_email . '>',
			'content-type: text/html',
			'Cc: '. get_userdata($id_recipient)->display_name. ' <'. get_userdata($id_recipient)->user_email . '>',
			'Cc: '. get_userdata($id_sender)->user_email,
		];
		wp_mail(get_userdata($id_sender)->user_email, $subAdm, $admMsg, $headers);*/
		/****end letter for recepient****/

	} else {
		wp_die( 'Forbidden', '', 403 );
	}
	if(get_user_meta(wp_get_current_user()->ID, 'personal_foto', true) != false) {
		$currentAvatar = get_user_meta(wp_get_current_user()->ID,'personal_foto', true);
	} else $currentAvatar = get_template_directory_uri(). '/assets/img/picture.png';

	$response = '<article class="right">
                    <div class="avatar">
                        <img alt="avatar" style="max-width: 40px" src="'.$currentAvatar . '" />
                    </div>
                    <div class="msg">
                        <div class="tri"></div>
                        <div class="msg_inner">' .$text_message. '<br><span class="time_msg" data-time="'.$date_sent .'">'. date('Y-m-d H:i', ($date_sent - $_POST['offset']*60)). '</span></div>
                    </div>
                </article>';
	wp_send_json($response);
}
/** Send message response [end]*/

add_action('wp_ajax_ARC_get_user_avatar', 'ARC_get_user_avatar');
function ARC_get_user_avatar() {
	if ( empty( $_POST['nonce'] ) ) {
		wp_die( 0 );
	}
	if(get_user_meta($_POST['start_id'], 'personal_foto', true) != false) {
		$avatarSender = get_user_meta($_POST['start_id'],'personal_foto', true);
	} else $avatarSender = get_template_directory_uri(). '/assets/img/picture.png';

	$res = [
		$avatarSender, get_userdata($_POST['start_id'])->display_name
	];
	wp_send_json($res);
}

add_action('wp_ajax_ARC_set_color_scheme', 'ARC_set_color_scheme');
function ARC_set_color_scheme() {
	if ( empty( $_POST['nonce'] ) ) {
		wp_die( 0 );
	}
	update_user_meta(wp_get_current_user()->ID, 'color_chat_scheme', $_POST['scheme']);
	wp_die();
}
