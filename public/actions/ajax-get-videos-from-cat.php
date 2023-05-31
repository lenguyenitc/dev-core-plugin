<?php
add_action("wp_ajax_ARC_get_videos_from_cat", "ARC_get_videos_from_cat");
add_action("wp_ajax_nopriv_ARC_get_videos_from_cat", "ARC_get_videos_from_cat");
function ARC_get_videos_from_cat(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) die ( 'Busted!');


	if(isset($_GET['filter']) && !empty($_GET['filter'])) {
		$tv = $_GET['filter'];
	} else {
		$tv = xbox_get_field_value('my-theme-options', 'show_videos');
	}
	$args = unserialize(stripslashes($_POST['query']));

	$category_name = json_decode(stripslashes($_POST['query']), true)['category_name'];
	$cat = get_category_by_slug($category_name);
	$current_cat_id = $cat->term_id;


	switch( $tv ){
		case 'latest':
			$args = array(
				'post_type'      => 'post',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => xbox_get_field_value('my-theme-options', 'number-vid-per-row'),
				'cat'            => $current_cat_id,
			);
			break;
		case 'random':
			$args = array(
				'post_type'      => 'post',
				'orderby'        => 'rand',
				'posts_per_page' => xbox_get_field_value('my-theme-options', 'number-vid-per-row'),
				'cat'            => $current_cat_id,
			);
			break;
		case 'featured':
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' 	=> xbox_get_field_value('my-theme-options', 'number-vid-per-row'),
				'orderby'        => 'featured_video',
				'order'          => 'ASC',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'=> 'featured_video',
						'value' => 'on',
						'compare' => '='
					),
					array(
						'key'=> 'featured_video',
						'compare' => 'NOT EXISTS'
					),
				),
				'cat' => $current_cat_id,
			);
			break;
		case 'popular':
			$args = array(
				'post_type'      => 'post',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'=> 'likes_count',
						'compare' => 'EXISTS'
					),
					array(
						'key'=> 'likes_count',
						'compare' => 'NOT EXISTS'
					)
				),
				'posts_per_page' => xbox_get_field_value('my-theme-options', 'number-vid-per-row'),
				'cat'            => $current_cat_id,
			);
			break;
		case 'most-viewed':
			$args = array(
				'post_type'      => 'post',
				'meta_key'       => 'post_views_count',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
				'posts_per_page' => xbox_get_field_value('my-theme-options', 'number-vid-per-row'),
				'cat'            => $current_cat_id,
			);
			break;
		case 'longest':
			$args = array(
				'post_type'      => 'post',
				'meta_key'       => 'duration',
				'orderby'        => 'meta_value_num',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'=> 'duration',
						'compare' => 'EXISTS'
					),
					array(
						'key'=> 'duration',
						'compare' => 'NOT EXISTS'
					)
				),
				'order'          => 'DESC',
				'posts_per_page' => xbox_get_field_value('my-theme-options', 'number-vid-per-row'),
				//'cat'            => $current_cat_id,
				'category__in' => $current_cat_id
			);
			break;
		case 'all':
			$args = array(
				'post_type'      => 'post',
				'orderby'        => 'meta_value_num',
				'meta_key'       => 'post_views_count',
				'meta_query'     => array(
					'relation'  => 'OR',
					array(
						'key'     => 'post_views_count',
						'compare' => 'NOT EXISTS'
					),
					array(
						'key'     => 'post_views_count',
						'compare' => 'EXISTS'
					)
				),
				'order'          => 'DESC',
				'posts_per_page' => xbox_get_field_value('my-theme-options', 'number-vid-per-row'),
				'cat'            => $current_cat_id,
			);
			break;
	}


	$args['paged'] = $_POST['page'] + 1;
	$args['post_status'] = 'publish';


	query_posts($args);
	if(have_posts()) :
		while( have_posts() ): the_post();
			get_template_part( 'template-parts/loop', 'video2' );
		endwhile;
	endif;
	wp_die();
}