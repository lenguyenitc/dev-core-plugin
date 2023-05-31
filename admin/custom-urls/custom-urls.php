<?php
add_action('init', 'custom_init_urls');
add_action('init', 'custom_init_redirect');
add_action('generate_rewrite_rules', 'custom_generate_rewrite_rules');
include_once 'functions.php';
if(is_admin()) {
	add_action('admin_init', 'custom_admin_init');
	//register_deactivation_hook( __FILE__, 'custom_deactivate' );
	include_once 'functions-admin.php';
}