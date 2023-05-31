<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

function ARC_check_license() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$theme = wp_get_theme();
	$theme_name = $theme->get('Name');
	$theme_version = $theme->get('Version');
	$arr = [
		$_SERVER['HTTP_USER_AGENT'],
		$_SERVER['SERVER_NAME'],
		$_SERVER['SERVER_ADDR'],
		VICETEMPLECORE_NAME,
		VICETEMPLECORE_VERSION,
		$theme_name,
		$theme_version,
		'license' => trim($_POST['license']),
		'server_name' => trim($_POST['server_name']),
		'error' => false,
		'time' => date('Y-m-d H:i:s')
	];
	$data = VICETEMPLECORE()->update_license_key(maybe_serialize($arr));
	wp_send_json($data);
	wp_die();
}
add_action('wp_ajax_ARC_check_license', 'ARC_check_license');

function ARC_curl_license() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$userData = json_encode([
		'license' => $_POST['license'],
		'server_name' => $_POST['server_name'],
		'identification' => 'tuk_tuk'
	]);
	if($curl = curl_init()) {
		//curl_setopt($curl, CURLOPT_URL, VICETEMPLECORE_WEB_SERVICE . 'check_license/');
		curl_setopt($curl, CURLOPT_URL, VICETEMPLECORE_LIC_URL . 'license-management/');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'userData=' . $userData);
		$data = curl_exec($curl);
		curl_close($curl);
	}
	wp_send_json($data);
	wp_die();
}
add_action('wp_ajax_ARC_curl_license', 'ARC_curl_license');