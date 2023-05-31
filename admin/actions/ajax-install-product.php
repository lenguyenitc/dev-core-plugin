<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );
function vicetemple_install_product() {
	check_ajax_referer( 'ajax-nonce', 'nonce' );

	if ( ! isset( $_POST['method'], $_POST['product_type'], $_POST['product_sku'], $_POST['product_zip'], $_POST['product_slug'], $_POST['product_folder_slug'], $_POST['new_version'] ) ) {
		wp_die( 'Some parameters are missing!' );
	}

	$method              = sanitize_text_field( wp_unslash( $_POST['method'] ) );
	$product_type        = sanitize_text_field( wp_unslash( $_POST['product_type'] ) );
	$product_sku         = sanitize_text_field( wp_unslash( $_POST['product_sku'] ) );
	$product_zip         = sanitize_text_field( wp_unslash( $_POST['product_zip'] ) );
	$product_slug        = sanitize_text_field( wp_unslash( $_POST['product_slug'] ) );
	$product_folder_slug = sanitize_text_field( wp_unslash( $_POST['product_folder_slug'] ) );
	$new_version         = sanitize_text_field( wp_unslash( $_POST['new_version'] ) );

	$product   = array(
		'file_path'   => $product_folder_slug . '/' . $product_folder_slug . '.php',
		'package'     => $product_zip,
		'new_version' => $new_version,
		'slug'        => $product_slug,
	);
	$installer = new Vicetemple_Product_Uploader();
	$output    = $installer->upload_product( $product_type, $method, $product);

	// init installed products options.
	$options = array(
		'sku'               => $product_sku,
		'installed_version' => $new_version,
		'state'             => 'deactivated',
	);
	wp_send_json($output);
	wp_die();
}
add_action( 'wp_ajax_vicetemple_install_product', 'vicetemple_install_product' );

function check_is_plugin_install() {
	check_ajax_referer( 'ajax-nonce', 'nonce' );

	if ( $active_plugins = get_option('active_plugins') ) {
		$activate_this = [$_POST['plugin']];
		foreach ( $activate_this as $plugin ) {
			if ( ! in_array( $plugin, $active_plugins ) ) {
				$data = 'not_active';
			} else $data = 'active';
		}

	}
	wp_send_json([$data]);
	wp_die();
}
add_action( 'wp_ajax_check_is_plugin_install', 'check_is_plugin_install');

/*function activate_new_plugin() {
	check_ajax_referer( 'ajax-nonce', 'nonce' );
	do_action('admin_init', $_POST['plugin']);
	wp_send_json('active');
	wp_die();
}
add_action( 'wp_ajax_activate_new_plugin', 'activate_new_plugin');

function true_plugins_activate ($arr_plugin) {
	if ( $active_plugins = get_option('active_plugins') ) {
		$activate_this = [$arr_plugin];
		foreach ( $activate_this as $plugin ) {
			if (!in_array( $plugin, $active_plugins ) ) {
				array_push( $active_plugins, $plugin );
				update_option( 'active_plugins', $active_plugins );
			}
		}
	}
}
add_action( 'admin_init', 'true_plugins_activate', 10, 1);*/

function check_is_theme_install($slug, $name) {
	check_ajax_referer( 'ajax-nonce', 'nonce' );
	$slug = $_POST['slug'];
	$name = $_POST['name'];
	update_option('current_theme', $name);
	update_option('template', $slug);
	update_option('stylesheet', $slug);
	$data = 'active';
	wp_send_json([$data]);
	wp_die();
}
add_action( 'wp_ajax_check_is_theme_install', 'check_is_theme_install', 10, 2);

