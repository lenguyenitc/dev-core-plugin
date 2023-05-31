<?php
function devcore_add_columns($defaults) {
	$defaults['thumb']   = __( 'Thumbnail', 'arc' );
	$defaults['partner'] = __( 'Partner', 'arc' );
	return $defaults;
}
add_filter( 'manage_edit-post_columns', 'devcore_add_columns' );

function devcore_columns_content($name) {
	global $post;
	switch ($name) {
		case 'thumb':
			$attachment = '';
			$attr       = '';
			if (isset($attachment) && is_object($attachment)) {
				$attr = [
					'alt'   => trim(wp_strip_all_tags($attachment->post_excerpt)),
					'title' => trim(wp_strip_all_tags($attachment->post_title)),
				];
			}
			if ( has_post_thumbnail() ) {
				echo get_the_post_thumbnail( $post->ID, 'devcore_thumb_admin', $attr );
			} elseif (get_post_meta( $post->ID, 'thumb', true)) {
				echo wp_kses(
					'<img width="100%" height="auto" src="' . get_post_meta($post->ID, 'thumb', true) . '"  alt="' . get_the_title() . '" />',
					wp_kses_allowed_html(
						[
							'img' => [
								'alt'    => [],
								'class'  => [],
								'height' => [],
								'src'    => [],
								'width'  => [],
							],
						]
					)
				);
			} else {
				echo wp_kses(
					'<img width="100%" height="auto" src="'. plugins_url() . '/dev-core-plugin/admin/vendors/img/admin-no-image.jpg" />',
					wp_kses_allowed_html(
						[
							'img' => [
								'alt'    => [],
								'class'  => [],
								'height' => [],
								'src'    => [],
								'width'  => [],
							],
						]
					)
				);
			}
			break;
		case 'partner':
			$partner = get_post_meta($post->ID, 'partner', true);
			if(!empty($partner))
			echo wp_kses(
				'<img width="100%" height="auto" src="'. plugins_url() . '/dev-core-plugin/admin/vendors/img/'. $partner . '.jpg" alt="' . $partner . '"/>',
				wp_kses_allowed_html(
					[
						'img' => [
							'alt'    => [],
							'class'  => [],
							'height' => [],
							'src'    => [],
							'width'  => [],
						],
					]
				)
			);
			break;
	}
}
add_action( 'manage_posts_custom_column', 'devcore_columns_content' );

/**
 * Hook to create admin thumbnails for posts listings.
 */
add_image_size('devcore_thumb_admin', '95', '70', '1');