<?php
add_action( 'wp_ajax_remove_all_tags_from_video', 'remove_all_tags_from_video' );
function remove_all_tags_from_video()
{
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$response = wp_set_object_terms($_POST['postID'], NULL, 'post_tag');
	wp_send_json($response);
}

add_action('wp_ajax_delete_user_video', 'delete_user_video');
function delete_user_video() {
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
	$post_id = $_POST['postId'];
	confirm_delete_the_video(wp_get_current_user()->ID, $post_id);
	wp_send_json('delete');
}

add_action('wp_ajax_delete_one_tag_from_video', 'delete_one_tag_from_video');
function delete_one_tag_from_video() {
    if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');
    $res = wp_remove_object_terms( $_POST['video_id'], $_POST['tag_slug'], 'post_tag' );
    if ($res === true) {
        $res = '1';
    }
    wp_send_json($res);
}

add_action( 'wp_ajax_add_tags_in_modal_window', 'add_tags_in_modal_window' );
function add_tags_in_modal_window()
{
	if (empty($_POST['nonce'])) {
		wp_die( 0 );
	}

	$check_ajax_referer = check_ajax_referer(
		'ajax-nonce',
		'nonce',
		false );

	if ($check_ajax_referer) {
		$posttags = get_the_tags($_POST['post_id']);
		$compilation = '';
		if ( $posttags ) {
			if ( true ) {
				foreach ( (array) $posttags as $tag ) {
					$one = '<div class="render-x">';
					$two = '<a href="' . get_tag_link( $tag->term_id ) . '" class="label a-tags" title="' . $tag->name . '"><span><svg class="fa-close" data-tag_slug="'.$tag->slug.'" width="9" height="10" viewBox="0 0 9 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M7.46688 9.02308C7.66888 9.22507 7.99638 9.22507 8.19837 9.02308C8.40037 8.82108 8.40037 8.49358 8.19837 8.29159L4.90645 4.99966L8.19788 1.70823C8.39988 1.50623 8.39988 1.17873 8.19788 0.976736C7.99589 0.774741 7.66839 0.774741 7.46639 0.976736L4.17496 4.26817L0.883474 0.976692C0.681478 0.774696 0.353979 0.774696 0.151984 0.976692C-0.0500114 1.17869 -0.0500113 1.50619 0.151984 1.70818L3.44347 4.99966L0.151497 8.29163C-0.0504988 8.49363 -0.0504988 8.82113 0.151496 9.02312C0.353492 9.22512 0.680991 9.22512 0.882986 9.02312L4.17496 5.73115L7.46688 9.02308Z" fill="#C4C4C4"/>
								</svg></span>' . strtolower($tag->name) . '</a>';
					$three = '</div>';
					$compilation .= $one . $two . $three;
				}
			}
			//$string_end = '</div>';
		}
	} else {
		wp_die( 'Forbidden', '', 403 );
	}
	$res = $compilation;
	wp_send_json($res);
}