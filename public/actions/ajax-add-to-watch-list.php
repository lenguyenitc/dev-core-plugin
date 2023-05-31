<?php
add_action("wp_ajax_ARC_add_to_watch_list", "ARC_add_to_watch_list");
function ARC_add_to_watch_list(){
	$postId = $_POST['postId'];
	$catName = $_POST['catName'];
	$user = wp_get_current_user();
	$post_type = get_post_type($postId);
	if(($catName == null || $catName == "null") && $postId !== null) {
		if($post_type != 'attachment' && !is_attachment($postId) && $_SERVER['REQUEST_URI'] != '/watched-videos/' && !is_page_template('template-watchlist.php') && $post_type !== 'page' && !is_author() && count(wp_get_post_terms($postId, 'pornstars')) == 0 && get_post_type($postId) !== 'faqs' && get_post_type($postId) !== 'photos' && get_post_type($postId) !== 'blog' && (get_post_meta($postId, 'video_url', true) !== false || get_post_meta($postId, 'embed', true) !== false)) {
			$watchList = get_user_meta($user->ID, 'watchList');
			if(count($watchList) > 0){
				foreach ($watchList as $watch) {
					if(in_array($postId, $watchList) == false) {
						add_user_meta($user->ID, 'watchList', (int)$postId);
						break;
					} else break;
				}
			} else {
				add_user_meta($user->ID, 'watchList', (int)$postId);
				wp_die();
			}
		}
	}
	wp_die();
}

add_action("wp_ajax_ARC_clear_watch_list", "ARC_clear_watch_list");
function ARC_clear_watch_list(){
	$user = wp_get_current_user();
	delete_user_meta(get_current_user_id(), 'watchList');
	wp_die();
}