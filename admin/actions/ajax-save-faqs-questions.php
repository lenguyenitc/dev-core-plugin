<?php
add_action('wp_ajax_save_faq_questions', 'save_faq_questions');
function save_faq_questions() {
	$nonce = $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'ajax-nonce'))
		wp_die( 'Busted!');

	$faqs = $_POST['faqs'];
	$faqs_option = [];

	foreach ($faqs['faqs'] as $item => $val) {
		/*$q = explode('~SEP_BITWEEN_Q~', $val)[0];
		$ans = explode('~SEP_BITWEEN_Q~', $val)[1];
		$qroup = explode('~SEP_GROUP_TYPE~', $val)[1];*/
		$faqs_option[$item] = $val;
	}

	//update_option('faqs_questions', $faqs_option, 'yes');
	update_option('faqs_test', $faqs_option, 'yes');

	//wp_send_json($faqs_option);
	wp_die('update');
}