<?php
/**
 * Primary Term Query.
 *
 * @package primary-post-category
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Primary Term Query Class.
 */
class PPC_Primary_Term_Query {

	/**
	 * Get Posts by Primary Term.
	 *
	 * Performs a query to return posts by term.
	 *
	 * @param integer|string $term     - Term id, slug or name.
	 * @param string         $taxonomy - Taxonomy slug.
	 * @param array          $args     - Post query arguments.
	 * @return array|false|WP_Error
	 */
	public static function get_posts_by_primary_term( $term, $taxonomy = '', $args = array() ) {
		$term_id = self::get_term_id( $term, $taxonomy );

		if ( ! $term_id ) {
			return new WP_Error( 'noterm', __( 'Term does not exist.', 'arc' ) );
		}

		$query_defaults = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
		);

		if ( '' === $taxonomy ) {
			$wp_term  = get_term( $term_id );
			$taxonomy = ( ! is_wp_error( $wp_term ) && ! is_null( $wp_term ) ) ? $wp_term->taxonomy : '';
		}

		$meta_query = array(
			'key'   => 'ppc_primary_' . $taxonomy,
			'value' => $term_id,
		);

		$query_args               = wp_parse_args( $args, $query_defaults );
		$query_args['meta_query'] = array( $meta_query );
		$query                    = new WP_Query( $query_args );

		if ( $query->post_count > 0 ) {
			return $query->posts;
		}

		return false;
	}

	/**
	 * Returns term id by checking if it exists.
	 *
	 * @param integer|string $term     - Term id, slug or name.
	 * @param string         $taxonomy - Taxonomy slug.
	 * @return integer
	 */
	public static function get_term_id( $term, $taxonomy ) {
		$term_id = wp_cache_get( 'ppc_term_exists_' . $term . '_' . $taxonomy, 'ppc' );

		if ( false === $term_id ) {
			$term_id = term_exists( $term );
			wp_cache_set( 'ppc_term_exists_' . $term . '_' . $taxonomy, $term_id, 'ppc' );
		}

		return $term_id;
	}
}
