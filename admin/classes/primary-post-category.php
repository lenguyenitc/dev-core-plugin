<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'PPC_PLUGIN_FILE' ) ) {
	define( 'PPC_PLUGIN_FILE', __FILE__ );
}
if ( ! class_exists( 'Primary_Post_Category' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-primary-post-category.php';
}
function ARC_set_post_primary_category() {
	return Primary_Post_Category::instance();
}
ARC_set_post_primary_category();