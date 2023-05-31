<?php
add_action("wp_ajax_ARC_get_subscribers", "ARC_get_subscribers");
function ARC_get_subscribers() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die ( 'Busted!' );
	}
	$email = (string)$_POST['email'];
	$user = wp_get_current_user();

	if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
		if(get_user_meta($user->ID, 'subscribe', true) === false) {
			update_user_meta($user->ID, 'subscribe', $email);
		}
	} else {
		wp_send_json('error');
	}
	wp_die();
}

//add_action("wp_ajax_test_autoimport", "test_autoimport");
//add_action("wp_ajax_nopriv_test_autoimport", "test_autoimport");
function test_autoimport() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die ( 'Busted!' );
	}
	//categories
	/*$categories = get_categories( [
		'taxonomy'     => 'category',
		'type'         => 'post',
		'child_of'     => 0,
		'hide_empty'   => 1,
		'hierarchical' => 1,
		'number'       => 0,
		'pad_counts'   => false,
	] );

	if($categories){
		foreach($categories as $cat){
			$data[] =  $cat->name;
		}
	}*/
	//posts
	$posts = get_posts(array(
		'numberposts' => -1,
		'category'    => $_POST['catId'],
		'post_type'   => 'post',
		'suppress_filters' => true,
	));
	foreach($posts as $post){
		setup_postdata($post);
		$data[] = [
			'id' => get_post_meta($post->ID, 'video_id', true),
			'partner' => (string)get_post_meta($post->ID, 'partner', true),
			'title' => (string)get_the_title($post),
			'description' => (string)get_post_meta($post->ID, 'description', true),
			'duration' => get_post_meta($post->ID, 'duration', true),
			'scrin_url' => (string)stripslashes(get_post_meta($post->ID, 'thumb', true)),
			'mp4_url' => (string)stripslashes(get_post_meta($post->ID, 'embed', true)),
			'trailer' => (string)stripslashes(get_post_meta($post->ID, 'trailer_url', true)),
			'site' => (string)stripslashes(get_post_meta($post->ID, 'tracking_url', true)),
			'tags' => (string)get_post_meta($post->ID, 'tags', true),
			'actors' => (string)get_post_meta($post->ID, 'pornstars', true),
		];
	}
	wp_reset_postdata();
	$category = $_POST['category'];
	wp_send_json([$category, $data]);
	wp_die();
}

add_action("wp_ajax_ARC_get_login_data", "ARC_get_login_data");
add_action("wp_ajax_nopriv_ARC_get_login_data", "ARC_get_login_data");
function ARC_get_login_data() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die ( 'Busted!' );
	}
	global $post;
	$is_login = is_user_logged_in();
	$current = wp_get_current_user();
	$is_subscribe = get_user_meta($current->ID, 'subscribe', true);
	$is_premium = get_post_meta($_POST['postId'], 'premium_video', true);
	if($is_login == true) {
		if($is_subscribe === false && $is_premium === 'on') wp_send_json('show2');
	} else {
		if($is_premium === 'on') {
			wp_send_json('show');
		}
	}
	wp_die();
}