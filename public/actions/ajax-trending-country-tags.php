<?php
/*****get another tags for trending country*****/
add_action('wp_ajax_ARC_trending_country_tags', 'ARC_trending_country_tags');
add_action('wp_ajax_nopriv_ARC_trending_country_tags', 'ARC_trending_country_tags');
function ARC_trending_country_tags() {
	global $wpdb;
	$table_ip_country_trend = $wpdb->prefix . 'ip_country_trend';
	$nonce = $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'ajax-nonce'))
		wp_die( 'Busted!');

	$country = $_POST['country'];

	$res = $wpdb->get_row( "SELECT * FROM $table_ip_country_trend WHERE `country` = '" .$country. "'" );
	if($res){
		$arr_tag = $wpdb->get_col("SELECT `arr_tag` FROM $table_ip_country_trend WHERE `country` = '" .$country. "'");
		$arr_tag = unserialize($arr_tag[0]);
		if(count($arr_tag) < 15){
			$num_rand = 15 - count($arr_tag);
			$all_tags = get_tags( 'orderby=name&order=ASC');
			shuffle($all_tags);
			$i = 0;
			foreach ($all_tags as $value) {
				if(!in_array($value->slug, $arr_tag)) {
					if($i >= $num_rand) break;
					$i++;
					$rand_tag[] = $value->slug;
				}
			}
			$arr_tag = array_merge($arr_tag, $rand_tag);
			foreach ($arr_tag as $tag){
				$tags[] = [
					'link' => get_tag_link( get_term_by( 'slug', $tag, 'post_tag' )->term_id ),
					'slug' => $tag
				];
			}
		} else {
			$i = 0;
			shuffle($arr_tag);
			foreach ($arr_tag as $tag) {
				if ( $i >= 15 ) {
					break;
				} else {
					$tags[] = [
						'link' => get_tag_link( get_term_by( 'slug', $tag, 'post_tag' )->term_id ),
						'slug' => $tag
					];
				}
			}
		}
	} else {
		$all_tags = get_tags('orderby=name&order=ASC');
		$i = 0;
		shuffle( $all_tags );
		foreach ( $all_tags as $tag ) {
			if ( $i >= 15 ) break;
			else {
				$tags[] = [
					'link' => get_tag_link($tag->term_id),
					'slug' => $tag->slug
				];
			}
			$i++;
		}
	}
	foreach ($tags as &$v) {
		$v['slug'] = restyle_tag($v['slug']);
	}
	wp_send_json($tags);
	wp_die();
}