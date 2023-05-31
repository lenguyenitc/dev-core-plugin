<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

function ARC_get_category_from_db() {
	/*$categories = explode(',', get_option('autoImportCategory'));
	foreach($categories as $category) {
		wp_delete_term($category, 'category');
	}
	delete_option('autoImportCategory');*/
	$old_posts = explode(',', str_replace("[", "", str_replace("]", "", get_option('autoImportPost'))));
	foreach($old_posts as $post){
		wp_delete_post($post, true);
	}
	/*$posts = get_posts( array(
		'numberposts' => -1,
		'category'  => 1,
		'post_type'   => 'post',
		'suppress_filters' => true,
	));
	foreach( $posts as $post ){
		setup_postdata($post);
		wp_delete_post( $post->ID, true);
	}
	wp_reset_postdata();*/
	delete_option('autoImportPost');
	delete_option('autoimport');
	wp_send_json('done');
}
add_action('wp_ajax_ARC_get_category_from_db', 'ARC_get_category_from_db');

function ARC_create_category(){
	if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce'))
		die ( 'Busted!');
	$importData = json_decode(stripslashes($_POST['importData']), true);
	$newCat = explode(',', $_POST['newCategory'])[0];
	$count_video = explode(',', $_POST['newCategory'])[1];
	$new_category = [
		'cat_name'          => $newCat,
		'taxonomy'          => 'category'
	];
	//create category
	wp_create_category($newCat);
	$category_id = get_cat_ID($newCat);
	//import video
	$i = 0;
	foreach ($importData as $key) {
		if($i >= $count_video) break;
		else {
			$title = $key['title'];
			$desc = ($key['description'] !== false) ? $key['description'] : '';
			$post_args = [
				'post_author'     => '1',
				'post_status'     => (string) 'publish',
				'post_type'       => (string) 'post',
				'post_title'      => (string) $title,
				'post_content'    => (string) $desc,
			];
			//insert post
			$post_id = wp_insert_post( $post_args );
			//add post metas & taxonomies
			if($post_id) {
				$arr_posts[] = $post_id;
				//add video id
				update_post_meta( $post_id, 'video_id', (string) $key['id'] );
				//add main thumb
				update_post_meta( $post_id, 'thumb', (string) $key['scrin_url'] );
				//add video length
				update_post_meta( $post_id, 'duration', (string) $key['duration'] );
				$dur = $key['duration'];
				if ( false == $dur ) {
					$hours = 0;
					$min   = 0;
					$sec   = 0;
				} else if ( strpos( $dur, "min" ) > 0 ) {
					$dur   = trim( str_replace( "min", "", $dur ) );
					$hours = 0;
					$min   = (int) $dur - ( $hours * 60 );
					$sec   = (int) $dur % 60;
				} else if ( strpos( $dur, "sec" ) > 0 ) {
					$dur   = trim( str_replace( "sec", "", $dur ) );
					$hours = floor( (int) $dur / 60 / 60 );
					$min   = floor( (int) $dur / 60 - ( $hours * 60 ) );
					$sec   = (int) $dur % 60;
				} else if ( strpos( $dur, "m" ) > 0 || strpos( $dur, "s" ) > 0 ) {
					$hours = 0;
					$min   = explode( "m", $dur )[0];
					$sec   = trim( str_replace( "s", "", explode( "m", $dur )[1] ) );
				} else if ( strpos( $dur, ":" ) > 0 ) {
					$count_time_part = explode( ":", $dur );
					if ( count( $count_time_part ) == 3 ) {
						$hours = $count_time_part[0];
						$min   = $count_time_part[1];
						$sec   = $count_time_part[2];
					} else {
						$hours = "0";
						$min   = $count_time_part[0];
						$sec   = $count_time_part[1];
					}
					if ( $min < 10 && count( $min ) == 2 ) {
						$min = $min[1];
					} else if ( $min < 10 && count( $min ) == 1 ) {
						$min = $min[0];
					} else if ( $min == 10 ) {
						$min = $min;
					} else {
						$min = $min;
					}
					if ( $sec < 10 && count( $sec ) == 2 ) {
						$sec = $sec[1];
					} else if ( $sec == 10 ) {
						$sec = $sec;
					} else {
						$sec = $sec;
					}
				} else {
					$hours = floor( (int) $dur / 60 / 60 );
					$min   = floor( (int) $dur / 60 - ( $hours * 60 ) );
					$sec   = (int) $dur % 60;
				}
				if ( $hours < 10 ) {
					$hours = "0" . $hours;
				} else if ( $hours == 10 ) {
					$hours = $hours;
				}
				if ( $min < 10 ) {
					$min = "0" . $min;
				} else if ( $min == 10 ) {
					$min = $min;
				}
				if ( $sec < 10 ) {
					$sec = "0" . $sec;
				} else if ( $sec == 10 ) {
					$sec = $sec;
				}

				update_post_meta( $post_id, 'hours', $hours );
				update_post_meta( $post_id, 'minute', $min );
				update_post_meta( $post_id, 'second', $sec );
				update_post_meta( $post_id, 'partner', $key['partner'] );
				//add embed player
				update_post_meta( $post_id, 'embed', (string) $key['mp4_url'] );
				//add video trailer
				update_post_meta( $post_id, 'trailer_url', (string) $key['trailer'] );
				//add tracking url
				update_post_meta( $post_id, 'tracking_url', (string) $key['site'] );
				//add category
				wp_set_object_terms( $post_id, intval( $category_id ), 'category', false );
				//add tags
				$custom_tags = 'post_tag';
				$post_tags   = [];
				if ( $key['tags'] !== false ) {
					if ( gettype( $key['tags'] ) == "string" ) {
						wp_set_object_terms( $post_id, explode(',', $key['tags']), $custom_tags, false );
					} else {
						foreach ( $key['tags'] as $tags ) {
							for ( $i = 0; $i < count( $tags ); $i ++ ) {
								if ( $tags[ $i ] !== "" ) {
									array_push( $post_tags, (string) $tags[ $i ] );
								}
							}
						}
						wp_set_object_terms( $post_id, $post_tags, $custom_tags, false );
					}
				} else wp_set_object_terms( $post_id, $newCat, $custom_tags, false );
				//add actors
				$custom_actors = 'pornstars';
				$post_actors   = [];
				if ( $key['actors'] !== false ) {
					if ( ! empty( $key['actors'] ) ) {
						if ( gettype( $key['actors'] ) == "string" ) {
							wp_set_object_terms( $post_id, explode(',', $key['actors']), $custom_actors, false );
						} else {
							foreach ( $key['actors'] as $actors ) {
								if ( ! is_array( $actors ) ) {
									array_push( $post_actors, (string) $actors );
								} else {
									for ( $i = 0; $i < count( $actors ); $i ++ ) {
										if ( $actors[ $i ] !== "" ) {
											array_push( $post_actors, (string) $actors[ $i ] );
										}
									}
								}
							}
							wp_set_object_terms( $post_id, $post_actors, $custom_actors, false );
						}
					}
				}
				//post format video
				set_post_format( $post_id, 'video' );
			}
			$i++;
		}
	}
	if(get_option('autoImportPost', false) === false) {
		update_option('autoImportPost', json_encode($arr_posts), false);
	} else {
		$old_post_arr = get_option('autoImportPost');
		update_option('autoImportPost', $old_post_arr . ',' . json_encode($arr_posts), false);
	}
	update_option('autoimport', 'done', false);
	$data = [
		'catName' => strtolower(trim(str_replace(' ', '-', $newCat))),
		'catId' => $category_id
	];
	wp_send_json($data);
	wp_die();
}
add_action('wp_ajax_ARC_create_category', 'ARC_create_category');


/***test****/
/*function ARC_test() {

	wp_die();
}
add_action('wp_ajax_ARC_test', 'ARC_test');*/