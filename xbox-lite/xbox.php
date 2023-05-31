<?php
if( ! class_exists( 'XboxLoader120', false ) ) {
	include dirname( __FILE__ ) . '/loader.php';
	$loader = new XboxLoader120( '1.2.0', 980 );
	$loader->init();
}


/*
|---------------------------------------------------------------------------------------------------
| Usage example | These files are just for the example. Comment or remove these lines if you don't need it.
|---------------------------------------------------------------------------------------------------
*/
if( function_exists('my_theme_options') || function_exists('my_simple_metabox') ){
	return;
}

if( ! defined( 'XBOX_HIDE_DEMO' ) || ( defined( 'XBOX_HIDE_DEMO' ) && ! XBOX_HIDE_DEMO ) ){
	//include dirname( __FILE__ ) . '/example/admin-page.php';
	//include dirname( __FILE__ ) . '/example/admin-page2.php';
	//include dirname( __FILE__ ) . '/example/metabox.php';
}



