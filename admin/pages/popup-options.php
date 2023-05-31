<?php
add_action( 'xbox_init', 'popup_options');
function popup_options() {
	$options = [
		'id'         => 'popup-options',
		'title'      => __( 'Pop-up Options', 'arc' ),
		'menu_title' => __( 'Pop-up options','arc' ),
		'icon'       => XBOX_URL . 'img/xbox-light-small.png', //Menu icon
		'skin'       => 'orange', // Skins: blue, lightblue, green, teal, pink, purple, bluepurple, yellow, orange'
		'layout'     => 'boxed', //wide boxed
		'header'     => [
			'icon' => '<img src="' . XBOX_URL . 'img/xbox-light.png"/>',
			'desc' => __( 'This is the page of pop-up settings.', 'arc' ),
		],
		'capability' => 'edit_published_posts',
	];
	$popup   = xbox_new_admin_page( $options );

	/****Tab popup Options*****/
	$popup->add_main_tab( [
		'name'  => 'Main popup tab',
		'id'    => 'main-popup-tab',
		'items' => [
			'main-options' => '<i class="xbox-icon xbox-icon-star"></i>' . __( 'Main settings', 'arc' ),
		]
	] );
	/****End Tab popup Options*****/

	/****main***/
	$popup->open_tab_item( 'main-options' );
	$popup->add_field( [
		'id'      => 'popup-show',
		'name'    => __( 'Display the popup window', 'arc' ),
		'type'    => 'switcher',
		'default' => 'off',
	] );
	$popup->add_field([
		'id' => 'popup-bg-color',
		'name' => __( 'Popup background color', 'arc' ),
		'type' => 'radio',
		'default' => 'white',
		'items'   => [
			'white' => __('White', 'arc'),
			'black' => __('Black', 'arc'),
			'blue' => __('Blue', 'arc'),
			'crimson' => __('Crimson', 'arc'),
			'darkmagenta' => __('Magenta', 'arc'),
			'darkorange' => __('Orange', 'arc'),
			'darkred' => __('Red', 'arc'),
			'gold' => __('Gold', 'arc'),
			'grey' => __('Grey', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	]);
	$popup->add_field( [
		'id'      => 'popup-animation',
		'name'    => __( 'Choose the animation', 'arc' ),
		'desc'    => __( 'Select the place where will show the window', 'arc' ),
		'type'    => 'radio',
		'default' => 'right',
		'items'   => [
			'right' => __('From right side', 'arc'),
			'left' => __('From left side', 'arc'),
			'top' => __('From top', 'arc'),
			'bottom' => __('From bottom', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-place',
		'name'    => __( 'Choose the page', 'arc' ),
		'desc'    => __( 'Select the page where will show the window', 'arc' ),
		'type'    => 'radio',
		'default' => 'main',
		'items'   => [
			'main' => __('Only on the main page', 'arc'),
			'category' => __('On the category page', 'arc'),
			'videos' => __('On the post page', 'arc'),
			'all' => __('On the all pages', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-action',
		'name'    => __( 'Choose the action', 'arc' ),
		'desc'    => __( 'Select the action after which will show the popup', 'arc' ),
		'type'    => 'radio',
		'default' => '15sec',
		'items'   => [
			'15sec' => __('After 15 seconds on site', 'arc'),
			'300px' => __('After scroll 300px on top', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-animation-speed',
		'name'    => __( 'Popup animation speed', 'arc' ),
		'type'    => 'radio',
		'default' => '0.5s',
		'items'   => [
			'0.5s' => __('Fast', 'arc'),
			'1s' => __('Normal', 'arc'),
			'2s' => __('Slow', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-hide',
		'name'    => __( 'Hide popup for user', 'arc' ),
		'desc'    => __( 'Select the time after which will show popup again', 'arc' ),
		'type'    => 'radio',
		'default' => '6',
		'items'   => [
			'6' => __('Hide popup on 6 hour', 'arc'),
			'24' => __('Hide popup on 24 hours', 'arc'),
			'3' => __('Hide popup on 3 days', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-content',
		'name'    => __( 'Popup content (image, video, gif)', 'arc' ),
		'desc'    => __( 'Upload file to media library and insert the link here (.jpg, .png, .mp4, .gif)', 'arc' ),
		'type'    => 'text',
		'options' => [
			'in_line'      => true,//Default: true
			'grid' => '8-of-8',
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-btn',
		'name'    => __( 'Redirect button', 'arc' ),
		'desc'    => __( 'Show the redirect button', 'arc' ),
		'type'    => 'switcher',
		'default' => 'on',
		'items'   => [
			'on' => __('Yes', 'arc'),
			'off' => __('No', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-btn-pulse',
		'name'    => __( 'Pulse button', 'arc' ),
		'desc'    => __( 'Button will pulse', 'arc' ),
		'type'    => 'switcher',
		'default' => 'on',
		'items'   => [
			'on' => __('Yes', 'arc'),
			'off' => __('No', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-redirect',
		'name'    => __( 'Redirect link', 'arc' ),
		'desc'    => __( 'Enter the link where will redirect user after click on button', 'arc' ),
		'type'    => 'text',
		'options' => [
			'in_line'      => true,//Default: true
			'grid' => '8-of-8',
			'show_if' => ['popup-btn', '=', 'on']
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-text',
		'name'    => __( 'Button text', 'arc' ),
		'desc'    => __( 'Enter the text which will display on button', 'arc' ),
		'type'    => 'text',
		'options' => [
			'in_line'      => true,//Default: true
			'grid' => '8-of-8',
			'show_if' => ['popup-btn', '=', 'on']
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-btn-color',
		'name'    => __( 'Button color', 'arc' ),
		'type' => 'radio',
		'default' => '#FFD700',
		'items'   => [
			'#FFD700' => __('Gold', 'arc'),
			'#00FFFF' => __('Aqua', 'arc'),
			'#0000FF' => __('Blue', 'arc'),
			'#DC143C' => __('Crimson', 'arc'),
			'#8B008B' => __('Magenta', 'arc'),
			'#FF8C00' => __('Orange', 'arc'),
			'#FF0000' => __('Red', 'arc'),
			'#FF1493' => __('Pink', 'arc'),
			'#FF00FF' => __('Fuchsia', 'arc'),
			'#FF69B4' => __('Hot pink', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );
	$popup->add_field( [
		'id'      => 'popup-btn-text-color',
		'name'    => __( 'Button text color', 'arc' ),
		'type' => 'radio',
		'default' => 'black',
		'items'   => [
			'black' => __('Black', 'arc'),
			'red' => __('Red', 'arc'),
			'white' => __('White', 'arc'),
			'blue' => __('Blue', 'arc'),
			'gold' => __('Gold', 'arc'),
		],
		'options' => [
			'in_line'      => true,//Default: true
		],
	] );

	$popup->close_tab_item( 'main-options' );
}