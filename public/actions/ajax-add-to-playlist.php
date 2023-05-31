<?php
add_action("wp_ajax_ARC_get_users_playlists", "ARC_get_users_playlists");
function ARC_get_users_playlists(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	global $post;
	$user_id = get_current_user_id();
	$favorites = get_user_meta($user_id, "userPlaylists");

	if(count($favorites) !== 0) {
		foreach ($favorites as $favorite) {
			$userPlaylists[] = get_term($favorite, 'playlists', 'ARRAY_A');
		}
		arsort($userPlaylists);
		foreach ($userPlaylists as $userPlayList) {
			if(!is_object_in_term($_POST['postId'], 'playlists', $userPlayList['term_id'])){
				$r[] = [
					'id' => $userPlayList['slug'],
					'name' => $userPlayList['name']
					];
			}
		}
		wp_send_json($r);
	} else wp_die();

}

add_action("wp_ajax_ARC_create_playlist", "ARC_create_playlist");
function ARC_create_playlist(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$title = (string)$_POST['titlePlaylist'];
	$desc = (string)$_POST['descPlaylist'];
	$postId = (int)$_POST['postId'];
	$existPlaylist = (string)$_POST['existPlaylist'];

	$thumb_url = get_post_meta($postId, 'thumb', true);
	$user = get_current_user_id();

	if($existPlaylist == 'Watch Later') {
		$existPlaylist = str_replace(' ', '', trim(mb_strtolower($existPlaylist)));
	} else {
		$existPlaylist = str_replace(' ', '-', trim(mb_strtolower($existPlaylist)));
	}
	$existPlaylistName = (string)$_POST['existPlaylist'];

	if($existPlaylistName !== 'noSelect') {
		if(is_object_in_term($postId, 'playlists',$existPlaylist.$user))
			wp_send_json('refresh');
		else {
			wp_set_object_terms($postId, $existPlaylist.$user, 'playlists', true );
			$data = [
				'exist' => 'yes',
				'name' => $existPlaylistName,
				'slug' => $existPlaylist.$user,
				'post' => $postId,
			];
			wp_send_json($data);

		}
		wp_die();
	} else {
		if($title == '') wp_die('Missing arguments');
		else {
			if (is_object_in_term($postId, 'playlists',str_replace(' ', '-', trim(mb_strtolower($title))).$user)) {
				wp_send_json( 'refresh' );
			}
			$terms = get_terms([
				'taxonomy'      => array('playlists'),
				'hide_empty'    => false,
				/*'exclude' => 'watchlater'.get_current_user_id()*/]);
			if(count($terms) !== 0) {
				foreach ($terms as $term) {
					if(trim(mb_strtolower($title.$user)) == trim(mb_strtolower($term->slug))) wp_die('Playlist exists');
					else {
						$userPlaylist = wp_insert_term($title, 'playlists', array(
							'description' => $desc,
							'parent'      => 0,
							'slug'        => $title.$user,
						));
						wp_set_post_terms($postId, $title.$user, 'playlists', true);
						update_term_meta($userPlaylist['term_id'], 'playlist-image', $thumb_url);
						add_user_meta($user, "userPlaylists", $userPlaylist['term_id']);
						$data = [
							'exist' => 'new',
							'name' => $title,
							'slug' => str_replace(' ', '-', trim(mb_strtolower($title.$user))),
							'post' => $postId,
						];
                        update_term_meta($userPlaylist['term_id'], 'playlist_data', time());
						wp_send_json($data);
					}
				}
			} else {
				$userPlaylist = wp_insert_term($title, 'playlists', array(
					'description' => $desc,
					'parent'      => 0,
					'slug'        => $title.$user,
				));
				wp_set_post_terms($postId, $title.$user, 'playlists', true);
				update_term_meta($userPlaylist['term_id'], 'playlist-image', $thumb_url);
				add_user_meta($user, "userPlaylists", $userPlaylist['term_id']);
                update_term_meta($userPlaylist['term_id'], 'playlist_data', time());
				$data = [
					'exist' => 'new',
					'name' => $title,
					'slug' => str_replace(' ', '-', trim(mb_strtolower($title.$user))),
					'post' => $postId,
				];
				wp_send_json($data);
			}
			wp_die();
		}
	}
}

add_action("wp_ajax_ARC_remove_from_playlist", "ARC_remove_from_playlist");
function ARC_remove_from_playlist() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die ( 'Busted!' );
	}
	$postId = (int)$_POST['postId'];
	$playlistSlug = (string)$_POST['playlistSlug'];
	wp_remove_object_terms($postId, $playlistSlug, 'playlists');

	wp_send_json($playlistSlug);
	wp_die();
}

add_action("wp_ajax_ARC_remove_playlist", "ARC_remove_playlist");
function ARC_remove_playlist() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die ( 'Busted!' );
	}
	$playlistId = (int)$_POST['playlistId'];
	$userId = (int)$_POST['userId'];

	wp_delete_term($playlistId, 'playlists');
	delete_user_meta($userId, "userPlaylists", $playlistId);
	wp_send_json($playlistId);
	wp_die();
}

add_action("wp_ajax_ARC_create_default_playlist", "ARC_create_default_playlist");
function ARC_create_default_playlist(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$postId = (int)$_POST['postId'];
	$thumb_url = get_post_meta($postId, 'thumb', true);

	$userID = $_POST['userID'];

	$term = get_term_by('slug', 'watchlater'. $userID, 'playlists');
	wp_set_post_terms($postId, 'watchlater'. $userID, 'playlists', true);
	update_term_meta($term->term_id, 'playlist-image', $thumb_url);
	update_user_meta($userID, "userLaterPlaylists", $term->term_id);
	wp_send_json('added');
	wp_die();
}

add_action("wp_ajax_ARC_remove_from_default_playlist", "ARC_remove_from_default_playlist");
function ARC_remove_from_default_playlist() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die ( 'Busted!' );
	}
	$postId = (int)$_POST['postId'];
	$playlistSlug = (string)$_POST['playlistSlug'];
	wp_remove_object_terms($postId, $playlistSlug, 'playlists');
	wp_send_json($postId);
	wp_die();
}


/****save profile image****/
add_action("wp_ajax_ARC_save_profile_image", "ARC_save_profile_image");
function ARC_save_profile_image(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$curr = wp_get_current_user();
	update_user_meta($curr->ID, 'personal_foto', $_POST['img']);
	wp_die();
}

/****save profile back****/
add_action("wp_ajax_ARC_save_profile_back", "ARC_save_profile_back");
function ARC_save_profile_back(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$curr = get_current_user_id();
	delete_user_meta($curr, 'personal_back');
	$r = add_user_meta($curr, 'personal_back', htmlspecialchars($_POST['img2']));
	wp_die($r);
}


/****save multiselect lang*****/
add_action("wp_ajax_ARC_save_multiselect_lang", "ARC_save_multiselect_lang");
function ARC_save_multiselect_lang() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$curr = wp_get_current_user();
	update_user_meta($curr->ID, 'languages', array_unique($_POST['items']));
	wp_die();
}

/****save multiselect fetish*****/
add_action("wp_ajax_ARC_save_multiselect_fetish", "ARC_save_multiselect_fetish");
function ARC_save_multiselect_fetish() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$curr = wp_get_current_user();
	update_user_meta($curr->ID, 'fetishes', array_unique($_POST['items']));
	wp_die();
}

/****subscribe/unsubscribe****/
add_action("wp_ajax_ARC_subscribe_unsubscribe_author", "ARC_subscribe_unsubscribe_author");
function ARC_subscribe_unsubscribe_author() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	if($_POST['data_subscribe'] == 'off') {
		add_user_meta(get_current_user_id(), 'subscribe_author', $_POST['data_author'], false);
		wp_send_json('subscribed');
	} else {
		delete_user_meta(get_current_user_id(), 'subscribe_author', $_POST['data_author']);
		wp_send_json('unsubscribed');
	}

	wp_die();
}

/****save setting for email*****/
add_action("wp_ajax_ARC_save_setting_for_email", "ARC_save_setting_for_email");
function ARC_save_setting_for_email() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$curr = wp_get_current_user();
	update_user_meta($curr->ID, 'show_email', $_POST['show_email']);
	wp_die();
}

/****save setting for phone*****/
add_action("wp_ajax_ARC_save_setting_for_phone", "ARC_save_setting_for_phone");
function ARC_save_setting_for_phone() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$curr = wp_get_current_user();
	update_user_meta($curr->ID, 'show_phone', $_POST['show_phone']);
	wp_die();
}


/**** add|remove video from favorites****/
add_action("wp_ajax_ARC_add_video_to_favorite", "ARC_add_video_to_favorite");
function ARC_add_video_to_favorite(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$postId = (int)$_POST['postId'];
	$userID = $_POST['userID'];
	$add = $_POST['add'];
	if($add == 'off') {
		if(get_user_meta($userID, 'favorites_video') === false) {
			add_user_meta($userID, 'favorites_video', $postId . ',', true);
			wp_die($postId);
		} else {
			$old_favorites = get_user_meta($userID, 'favorites_video', true);
			$new_favorites = explode(',', $old_favorites . $postId . ',');
			$new_favorites = implode(',',array_unique($new_favorites));
			update_user_meta($userID, 'favorites_video', $new_favorites);
			wp_die($postId);
		}
	} else {
		$old_favorites = get_user_meta($userID, 'favorites_video', true);
		$old_favorites = explode(',', $old_favorites);
		foreach ($old_favorites as $old_favorite) {
			if($postId == $old_favorite) continue;
			else {
				$new_favorites[] = $old_favorite;
			}
		}
		$new_favorites = implode(',',array_unique($new_favorites));
		update_user_meta($userID, 'favorites_video', $new_favorites);
		wp_die($postId);
	}
}
/**** [end] add|remove video from favorites****/


/**** remove specific video from playlist***/
add_action('wp_ajax_delete_video_from_playlist', 'delete_video_from_playlist');
function delete_video_from_playlist() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$post_id = $_POST['post_id'];
	$term_slug = $_POST['term_slug'];
	$taxonomy = 'playlists';
	$done = wp_remove_object_terms($post_id, $term_slug, $taxonomy);
	if($done) {
		wp_send_json('delete');
	}
	wp_die();
}
/**** [end] remove specific video from playlist***/


/***edit public profile***/
add_action("wp_ajax_edit_public_profile_by_admin", "edit_public_profile_by_admin");
function edit_public_profile_by_admin(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$user_id = $_POST['user_id'];
	$action = $_POST['data_action'];

	if($action == 'avatar') {
		delete_user_meta($user_id, 'personal_foto');
	}
	if($action == 'back') {
		delete_user_meta($user_id, 'personal_back');
	}
	if($action == 'email') {
		delete_user_meta($user_id, 'show_email');
	}
	if($action == 'phone') {
		delete_user_meta($user_id, 'phone');
	}
	if($action == 'site') {
		delete_user_meta($user_id, 'user_url');
	}
	if($action == 'facebook') {
		delete_user_meta($user_id, 'facebook');
	}
	if($action == 'instagram') {
		delete_user_meta($user_id, 'instagram');
	}
	if($action == 'twitter') {
		delete_user_meta($user_id, 'twitter');
	}
	if($action == 'snapchat') {
		delete_user_meta($user_id, 'snapchat');
	}
	if($action == 'reddit') {
		delete_user_meta($user_id, 'reddit');
	}
	if($action == 'manyvids') {
		delete_user_meta($user_id, 'manyvids');
	}
	if($action == 'onlyfans') {
		delete_user_meta($user_id, 'onlyfans');
	}

	wp_die();
}


add_action('wp_ajax_ARC_save_setting_for_subscribers_and_views', 'ARC_save_setting_for_subscribers_and_views');
function ARC_save_setting_for_subscribers_and_views() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	if($_POST['data_action'] == 'subs') {
		$curr = wp_get_current_user();
		if($_POST['what_show'] == 'on') update_user_meta($curr->ID, 'show_subs', 'on');
		else delete_user_meta($curr->ID, 'show_subs');
	}
	if($_POST['data_action'] == 'views') {
		$curr = wp_get_current_user();
		if($_POST['what_show'] == 'on') update_user_meta($curr->ID, 'show_views', 'on');
		else delete_user_meta($curr->ID, 'show_views');

	}
	wp_send_json([$_POST['data_action'] => $_POST['what_show']]);
}


add_action('wp_ajax_ARC_save_setting_for_email_preferences', 'ARC_save_setting_for_email_preferences');
function ARC_save_setting_for_email_preferences() {
    if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
    $curr = wp_get_current_user();

    if($_POST['data_action'] == 'video_submission') {
        if($_POST['what_show'] == 'on') update_user_meta($curr->ID, 'video_submission', 'on');
        else update_user_meta($curr->ID, 'video_submission', 'off');
    }
    if($_POST['data_action'] == 'album_submission') {
        if($_POST['what_show'] == 'on') update_user_meta($curr->ID, 'album_submission', 'on');
        else update_user_meta($curr->ID, 'album_submission', 'off');
    }
    if($_POST['data_action'] == 'post_submission') {
        if($_POST['what_show'] == 'on') update_user_meta($curr->ID, 'post_submission', 'on');
        else update_user_meta($curr->ID, 'post_submission', 'off');
    }
    if($_POST['data_action'] == 'video_published') {
        if($_POST['what_show'] == 'on') update_user_meta($curr->ID, 'video_published', 'on');
        else update_user_meta($curr->ID, 'video_published', 'off');
    }
    wp_send_json([$_POST['data_action'] => $_POST['what_show']]);
}


add_action( 'wp_ajax_send_user_report', 'send_user_report' );
add_action( 'wp_ajax_nopriv_send_user_report', 'send_user_report' );
function send_user_report()
{
	if (empty($_POST['nonce'])) {
		wp_die( 0 );
	}

	$check_ajax_referer = check_ajax_referer(
		'ajax-nonce',
		'nonce',
		false );

	if ($check_ajax_referer) {
		global $wpdb;
		$table = $wpdb->prefix. 'reportMsg';
		$msg_data = [
			'date' => date("Y-m-d H:i:s"),
			'msg' => strip_tags($_POST['msg']),
			'type' => $_POST['type'],
			'postId' => (string)strip_tags($_POST['post_id']),
		];
		$types = ['%s', '%s', '%s', '%s'];
		$wpdb->insert($table, $msg_data, $types);

		/****letter for admin****/
		send_letter_report_video($_POST['type']);
		/****end letter for admin****/

		wp_send_json('success');
		wp_die();
	} else {
		wp_die( 'Forbidden', '', 403 );
	}
}