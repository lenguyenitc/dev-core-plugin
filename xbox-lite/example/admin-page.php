<?php
add_action( 'admin_enqueue_scripts', 'my_theme_options_scripts');
function my_theme_options_scripts($hook) {
	//echo $hook;
	if('toplevel_page_my-theme-options' == $hook) {
		wp_enqueue_style('xbox-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
		wp_enqueue_script('xbox-popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array('jquery'), '', false);
		wp_enqueue_script('xbox-bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array('jquery'), '', false);
	}
}
add_action( 'xbox_init', 'my_theme_options');
function my_theme_options(){
	$options = array(
		'id' => 'my-theme-options',
		'title' => VICETEMPLECORE_PRODUCT,
		'menu_title' => 'Theme Options',
		'icon' => XBOX_URL.'img/xbox-light-small.png', //Menu icon
		'skin' => 'purple', // Skins: blue, lightblue, green, teal, pink, purple, bluepurple, yellow, orange'
		'layout' => 'boxed', //wide boxed
		'header' => array(
			'icon' => '<img src="'.XBOX_URL.'img/xbox-light.png"/>',
			'desc' => __('This is the page of theme settings.', 'arc'),
		),
		'import_message' => __( 'Settings imported.', 'arc' ),
		'capability' => 'edit_published_posts',
	);
	$xbox = xbox_new_admin_page( $options );

	/****Tab Theme Options*****/
	$xbox->add_main_tab( array(
		'name' => 'Main tab',
		'id' => 'main-tab',
		'items' => array(
			'niches' => '<i class="xbox-icon xbox-icon-star"></i>' . __('Niches', 'arc'),
			'general' => '<i class="xbox-icon xbox-icon-gear"></i>' . __('General', 'arc'),
			'visual-options' => '<i class="xbox-icon xbox-icon-desktop"></i>' . __('Visual options', 'arc'),
			'header' => '<i class="xbox-icon xbox-icon-arrow-up"></i>' . __('Header', 'arc'),
			'content' => '<i class="xbox-icon xbox-icon-th-large"></i>' . __('Content', 'arc'),
			'sidebar' => '<i class="xbox-icon xbox-icon-list-alt"></i>' . __('Sidebar', 'arc'),
			'footer' => '<i class="xbox-icon xbox-icon-arrow-down"></i>' . __('Footer', 'arc'),
			'membership' => '<i class="xbox-icon xbox-icon-user"></i>' . __('Membership', 'arc'),
			'tools' => '<i class="xbox-icon xbox-icon-wrench"></i>' . __('Tools', 'arc'),
			'mobile' => '<i class="xbox-icon xbox-icon-mobile"></i>' . __('Mobile', 'arc'),
		)
	));
	/****End Tab Theme Options*****/

		/****niche***/
		$xbox->open_tab_item('niches');
			$xbox->add_field(array(
				'id' => 'choose-niche',
				'name' => __( 'Choose the niche', 'arc' ),
				'desc' => __('Select a Niche, then click import button. Theme options will be imported in order to change the appearance of your site.', 'arc'),
				'type' => 'image_selector',
				'default' => 'milf',
				'items' => array(
					'milf' => get_template_directory_uri() .'/assets/img/milf.png',
					'college' => get_template_directory_uri() .'/assets/img/college.png',
					'hentai' => get_template_directory_uri() .'/assets/img/hentai.png',
					'livexcams' => get_template_directory_uri() .'/assets/img/livexcams.png',
					'lesbian' => get_template_directory_uri() .'/assets/img/lesbian.png',
					'trans' => get_template_directory_uri() .'/assets/img/trans.png',
					'filf' => get_template_directory_uri() .'/assets/img/filf.png'
				),
				'options' => array(
					'width' => '300px',//Default: 100px
					'height' => '300px',//Default: auto
					'active_class' => 'xbox-active',//Default: xbox-active
					'active_color' => '#379FE7',//Default: #379FE7
					'in_line' => true,//Default: true
				),
			));
		$xbox->close_tab_item('niches');
		/****end niche***/

		/****general***/
		$xbox->open_tab_item('general');
			$xbox->add_field(array(
				'id' => 'number_videos_per_page',
				'name' => __( 'Number of videos per page', 'arc' ),
				'type' => 'number',
				'default' => 28,
				'grid' => '2-of-8',
				'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
				'options' => array(
					'unit' => 'videos/page',
					'show_unit' => true,
					'show_spinner' => true,
					'disable_spinner' => false,
				),
			)); //number
			$xbox->add_field(array(
				'id' => 'number_videos_per_row',
				'name' => __( 'Number of videos per row', 'arc' ),
				'type' => 'number',
				'default' => 4,
				'grid' => '2-of-8',
				'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-row.jpg' . '" />',
				'options' => array(
					'unit' => 'videos/page',
					'show_unit' => true,
					'show_spinner' => true,
					'disable_spinner' => false,
				),
			)); //number
			$xbox->add_field(array(
				'id' => 'show_videos',
				'name' => __( 'Show', 'arc' ),
				'type' => 'radio',
				'default' => 'latest',
				'items' => array(
					'latest' => __('Latest videos', 'arc'),
					'most-viewed' => __('Most viewed videos', 'arc'),
					'longest' => __('Longest videos', 'arc'),
					'popular' => __('Popular videos', 'arc'),
					'random' => __('Random videos', 'arc'),
				),
				'options' => array(
					'in_line' => true,
				)
			)); //radio
			$xbox->add_field(array(
				'id' => 'thumb_rotation',
				'name' => __( 'Aspect ratios of thumbnails', 'arc' ),
				'type' => 'radio',
				'desc' => __('Choose the aspect ratios for all thumbnails.', 'arc'),
				'default' => '16/9',
				'items' => array(
					'16/9' => '16/9',
					'4/3' => '4/3'
				),
				'options' => array(
					'in_line' => true,
				)
			)); //radio
			$xbox->add_field(array(
				'id' => 'thumb_quality',
				'name' => __( 'Main thumbnail quality', 'arc' ),
				'type' => 'radio',
				'desc' => __('Basic = High compression, Normal = Medium compression, Fine = Low compression', 'arc'),
				'default' => 'basic',
				'items' => array(
					'basic' => __('Basic', 'arc'),
					'normal' => __('Normal', 'arc'),
					'fine' => __('Fine', 'arc')
				),
				'options' => array(
					'in_line' => true,
				)
			)); //radio
			$xbox->add_field(array(
				'id' => 'enable_thumb_rotation',
				'name' => __( 'Enable thumbnails rotation', 'arc' ),
				'desc' => __('Enable thumbnails rotation to see a preview of the video on mouseover.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'enable_view',
				'name' => __( 'Enable views system', 'arc' ),
				'desc' => __('Display number of views on thumbnails, under the video player and add a "Most viewed videos" filter.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'enable_duration',
				'name' => __( 'Enable duration system', 'arc' ),
				'desc' => __('Display duration on thumbnails, and add a "Longest videos" filter.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'enable_rating',
				'name' => __( 'Enable rating system', 'arc' ),
				'desc' => __('Display a rating bar with percentage under thumbnails, a rating system under the video player, and add a "Popular videos" filter.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'enable_breadcrumbs',
				'name' => __( 'Enable breadcrumbs', 'arc' ),
				'desc' => __('Display a breadcrumb at the top of each pages of your site to allow your visitors to navigate more easily.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'enable_comments',
				'name' => __( 'Enable comments', 'arc' ),
				'desc' => __('Display a comments section in your single video pages.', 'arc'),
				'type' => 'switcher',
				'default' => 'off'
			)); //switcher
		$xbox->close_tab_item('general');
		/****end general***/

		/****visual options***/
		$xbox->open_tab_item('visual-options');
			$xbox->add_field(array(
				'id' => 'layout',
				'name' => __( 'Layout', 'arc' ),
				'type' => 'image_selector',
				'default' => 'boxed',
				'items' => array(
					'boxed' => get_template_directory_uri().'/assets/img/layout-boxed.jpg',
					'full-width' => get_template_directory_uri().'/assets/img/layout-full-width.jpg'
				),
				'items_desc' => array(
					'boxed' => __('Boxed', 'arc'),
					'full-width' => __('Full Width', 'arc')
				),
				'options' => array(
					'width' => '160px',//Default: 100px
					'height' => 'auto',//Default: auto
					'active_class' => 'xbox-active',//Default: xbox-active
					'active_color' => '#379FE7',//Default: #379FE7
					'in_line' => true,//Default: true
				),
			)); //image selector
			$xbox->add_field(array(
				'id' => 'rendering',
				'name' => __( 'Rendering', 'arc' ),
				'desc' => __('Display gradient and shadow on navigation, button, input, etc.', 'arc'),
				'type' => 'radio',
				'default' => 'flat',
				'items' => array(
					'flat' => __('Flat', 'arc'),
					'gradient' => __('Gradient', 'arc')
				),
				'options' => array(
					'in_line' => true,
				)
			)); //radio
		$xbox->close_tab_item('visual-options');
		/****end visual options***/

		/****header*****/
		$xbox->open_tab_item('header');
			$xbox->add_field(array(
				'id' => 'social-profiles',
				'name' => __( 'Show social profiles', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'your-social-profiles',
				'name' => __( 'YOUR SOCIAL PROFILES', 'arc' ),
				'type' => 'title',
				'options' => array(
					'show_if' => array(
						'social-profiles', '=', 'on'
					)
				)
			));
				$xbox->add_field(array(
					'id' => 'your-fb',
					'name' => __( 'Facebook', 'arc' ),
					'type' => 'text',
					'desc' => 'https://www.facebook.com/...',
					'options' => array(
						'show_if' => array(
							'social-profiles', '=', 'on'
						)
					),
					'grid' => '2-of-8'
				));
				$xbox->add_field(array(
					'id' => 'your-tw',
					'name' => __( 'Twitter', 'arc' ),
					'type' => 'text',
					'desc' => 'https://www.twitter.com/...',
					'options' => array(
						'show_if' => array(
							'social-profiles', '=', 'on'
						)
					),
					'grid' => '2-of-8'
				));
				$xbox->add_field(array(
					'id' => 'your-yt',
					'name' => __( 'Youtube', 'arc' ),
					'type' => 'text',
					'desc' => 'https://www.youtube.com/...',
					'options' => array(
						'show_if' => array(
							'social-profiles', '=', 'on'
						)
					),
					'grid' => '2-of-8'
				));
				$xbox->add_field(array(
					'id' => 'your-ins',
					'name' => __( 'Instagram', 'arc' ),
					'type' => 'text',
					'desc' => 'https://www.instagram.com/...',
					'options' => array(
						'show_if' => array(
							'social-profiles', '=', 'on'
						)
					),
					'grid' => '2-of-8'
				));
			$xbox->add_field(array(
				'id' => 'search-bar',
				'name' => __( 'Show search bar', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
		$xbox->close_tab_item('header');
		/****end header*****/

		/*****content****/
		$xbox->open_tab_item('content');
			$xbox->add_field(array(
				'id' => 'home-page',
				'name' => __( '<i class="xbox-icon xbox-icon-home"></i> HOME PAGE', 'arc' ),
				'type' => 'title'
			)); //title
				$xbox->add_field(array(
					'id' => 'show-carousel-of-videos',
					'name' => __( 'Show carousel of videos', 'arc' ),
					'desc' => __('Will display featured videos.', 'arc'),
					'type' => 'switcher',
					'default' => 'off'
				)); //switcher
			$xbox->add_field(array(
				'name' => __( 'CAROUSEL SETTINGS', 'arc' ),
				'type' => 'title',
				'options' => array(
					'show_if' => array('show-carousel-of-videos', '=', 'on')
				)
			)); //title
					$xbox->add_field(array(
						'id' => 'videos-amount',
						'name' => __( 'Videos amount', 'arc' ),
						'desc' => __('The number of videos displayed in the carousel(10 - 40)', 'arc'),
						'type' => 'number',
						'default' => 19,
						'grid' => '2-of-8',
						'options' => array(
							'unit' => 'videos',
							'show_unit' => true,
							'show_spinner' => true,
							'disable_spinner' => false,
							'show_if' => array('show-carousel-of-videos', '=', 'on')
						),
					)); //number
					$xbox->add_field(array(
						'id' => 'show-video-title',
						'name' => __( 'Show video title', 'arc' ),
						'desc' => __('Video title will be displayed as caption over the thumbnail.', 'arc'),
						'type' => 'switcher',
						'default' => 'on',
						'options' => array(
							'show_if' => array('show-carousel-of-videos', '=', 'on')
						)
					)); //switcher
					$xbox->add_field(array(
						'id' => 'autoplay',
						'name' => __( 'Autoplay', 'arc' ),
						'desc' => __('Autoplay will pause when mouse hovers over carousel.', 'arc'),
						'type' => 'switcher',
						'default' => 'on',
						'options' => array(
							'show_if' => array('show-carousel-of-videos', '=', 'on')
						)
					)); //switcher

				$xbox->add_field(array(
					'id' => 'show-carousel-on-mobile',
					'name' => __( 'Show carousel on mobile', 'arc' ),
					'desc' => __('Will display featured videos on mobile devices.', 'arc'),
					'type' => 'switcher',
					'default' => 'off'
				)); //switcher
				$xbox->add_field(array(
					'id' => 'show-sidebar-content',
					'name' => __( 'Show sidebar', 'arc' ),
					'desc' => __('<a href="'. admin_url() .'widgets.php">Click here</a> to manage your sidebar with widgets.', 'arc'),
					'type' => 'switcher',
					'default' => 'on'
				)); //switcher
				$xbox->add_field(array(
					'id' => 'title',
					'name' => __( 'Title', 'arc' ),
					'desc' => __('Enter a title (h1) to improve your SEO.', 'arc'),
					'type' => 'text'
				)); //text

				$xbox->add_field(array(
					'id' => 'title-desc-pos',
					'name' => __( 'Title and description position', 'arc' ),
					'desc' => __('Choose if you want to display the title and description at the top or the bottom of your homepage.', 'arc'),
					'type' => 'radio',
					'default' => 'bottom',
					'items' => array(
						'top' => __('Top', 'arc'),
						'bottom' => __('Bottom', 'arc')
					),
				));

				$xbox->add_field(array(
					'id' => 'video-single-post',
					'name' => __( '<i class="xbox-icon xbox-icon-play"></i> VIDEO SINGLE POST', 'arc' ),
					'desc' => __('<a href="'. admin_url() .'widgets.php">Click here</a> to use video blocks instead of a videos listing.','arc'),
					'type' => 'title'
				)); //title
					$xbox->add_field(array(
						'id' => 'show-sidebar-video-post',
						'name' => __( 'Show sidebar', 'arc' ),
						'desc' => __('<a href="'. admin_url() .'widgets.php">Click here</a> to manage your sidebar with widgets.', 'arc'),
						'type' => 'switcher',
						'default' => 'on'
					)); //switcher
					$xbox->add_field(array(
						'id' => 'display-tracking-button',
						'name' => __( 'Display tracking button', 'arc' ),
						'desc' => __('Display a button with your tracking link under the video player.', 'arc'),
						'type' => 'switcher',
						'default' => 'off'
					)); //switcher
						$xbox->add_field(array(
							'id' => 'tracking-button-settings',
							'name' => __( 'TRACKING BUTTON SETTINGS', 'arc' ),
							'type' => 'title',
							'options' => array(
								'show_if' => array('display-tracking-button', '=', 'on')
							)
						)); //title

							$xbox->add_field(array(
								'id' => 'tracking-button-link',
								'name' => __( 'Tracking button link', 'arc' ),
								'desc' => __('Use the same link for every tracking buttons.', 'arc'),
								'type' => 'text',
								'grid' => '4-of-8',
								'options' => array(
									'show_if' => array('display-tracking-button', '=', 'on')
								)
							));
							$xbox->add_field(array(
								'id' => 'tracking-button-text',
								'name' => __( 'Tracking button text', 'arc' ),
								'desc' => __('Change the text of the tracking button.', 'arc'),
								'default' => __('Download complete video now!', 'arc'),
								'type' => 'text',
								'grid' => '4-of-8',
								'options' => array(
									'show_if' => array('display-tracking-button', '=', 'on')
								)
							));
						$xbox->add_field(array(
							'id' => 'video-about',
							'name' => __( 'VIDEO ABOUT', 'arc' ),
							'type' => 'title'
						)); //title
							$xbox->add_field(array(
								'id' => 'show-desc',
								'name' => __( 'Show description', 'arc' ),
								'type' => 'switcher',
								'default' => 'on'
							)); //switcher
							$xbox->add_field(array(
								'id' => 'show-author',
								'name' => __( 'Show author', 'arc' ),
								'type' => 'switcher',
								'default' => 'on'
							)); //switcher
							$xbox->add_field(array(
								'id' => 'show-date',
								'name' => __( 'Show publish date', 'arc' ),
								'type' => 'switcher',
								'default' => 'on'
							)); //switcher
							$xbox->add_field(array(
								'id' => 'show-actors',
								'name' => __( 'Show actors', 'arc' ),
								'type' => 'switcher',
								'default' => 'on'
							)); //switcher
							$xbox->add_field(array(
								'id' => 'show-categories',
								'name' => __( 'Show categories', 'arc' ),
								'type' => 'switcher',
								'default' => 'on'
							)); //switcher
							$xbox->add_field(array(
								'id' => 'show-tags',
								'name' => __( 'Show tags', 'arc' ),
								'type' => 'switcher',
								'default' => 'on'
							)); //switcher
						$xbox->add_field(array(
							'id' => 'show-more-settings',
							'name' => __( 'Show more settings', 'arc' ),
							'desc' => __('Display a show more link under the description.', 'arc'),
							'type' => 'switcher',
							'default' => 'on'
						)); //switcher
						$xbox->add_field(array(
							'id' => 'actors-label',
							'name' => __( 'Actors label', 'arc' ),
							'desc' => __('Change the text of the actor label', 'arc'),
							'type' => 'text',
							'default' => 'Actors label'
						)); //text

						$xbox->add_field(array(
							'id' => 'video-share',
							'name' => __( 'Video share', 'arc' ),
							'desc' => __('Display a "Share" tab with social networks sharing buttons.', 'arc'),
							'type' => 'switcher',
							'default' => 'on'
						)); //switcher
							$xbox->add_field(array(
								'id' => 'facebook',
								'name' => __( 'Facebook', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher
							$xbox->add_field(array(
								'id' => 'twitter',
								'name' => __( 'Twitter', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher
							$xbox->add_field(array(
								'id' => 'linkedin',
								'name' => __( 'Linkedin', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher
							$xbox->add_field(array(
								'id' => 'tumblr',
								'name' => __( 'Tumblr', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher
							$xbox->add_field(array(
								'id' => 'reddit',
								'name' => __( 'Reddit', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher
							$xbox->add_field(array(
								'id' => 'odnoklassniki',
								'name' => __( 'Odnoklassniki', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher
							$xbox->add_field(array(
								'id' => 'vk',
								'name' => __( 'VK', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher
							$xbox->add_field(array(
								'id' => 'email',
								'name' => __( 'Email', 'arc' ),
								'type' => 'switcher',
								'default' => 'on',
								'options' => array(
									'show_if' => array('video-share', '=', 'on')
								)
							)); //switcher



						$xbox->add_field(array(
							'id' => 'display-related-videos',
							'name' => __( 'Display related videos', 'arc' ),
							'desc' => __('Display related videos under the video infos.', 'arc'),
							'type' => 'switcher',
							'default' => 'on'
						)); //switcher
							$xbox->add_field(array(
								'id' => 'related-videos-settings',
								'name' => __( 'Related videos settings', 'arc' ),
								'type' => 'number',
								'default' => 8,
								'grid' => '2-of-8',
								'options' => array(
									'unit' => 'videos',
									'show_unit' => true,
									'show_spinner' => true,
									'disable_spinner' => false,
									'show_if' => array('display-related-videos', '=', 'on')
								),
							)); //number

						$xbox->add_field(array(
							'id' => 'categories',
							'name' => __( '<i class="xbox-icon xbox-icon-folder"></i> CATEGORIES', 'arc' ),
							'type' => 'title'
						));
							$xbox->add_field(array(
								'id' => 'show-sidebar-in-content',
								'name' => __( 'Show sidebar', 'arc' ),
								'desc' => __('<a href="'. admin_url() .'widgets.php">Click here</a> to manage your sidebar with widgets.', 'arc'),
								'type' => 'switcher',
								'default' => 'on'
							)); //switcher
							$xbox->add_field(array(
								'id' => 'number-categ-per-page',
								'name' => __( 'Number of categories per page', 'arc' ),
								'type' => 'number',
								'default' => 15,
								'grid' => '5-of-8',
								'options' => array(
									'unit' => 'categories/page',
									'show_unit' => true,
									'show_spinner' => true,
									'disable_spinner' => false
								),
							)); //number
							$xbox->add_field(array(
								'id' => 'number-categ-per-row',
								'name' => __( 'Number of categories per row', 'arc' ),
								'type' => 'number',
								'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-row.jpg' . '" />',
								'default' => 5,
								'grid' => '5-of-8',
								'options' => array(
									'unit' => 'categories/row',
									'show_unit' => true,
									'show_spinner' => true,
									'disable_spinner' => false
								),
							)); //number

							$xbox->add_field(array(
								'id' => 'categories-thumb-quality',
								'name' => __( 'Categories thumbnail quality', 'arc' ),
								'desc' => __('Basic = High compression, Normal = Medium compression, Fine = Low compression', 'arc'),
								'type' => 'radio',
								'default' => 'basic',
								'items' => array(
									'basic' => __('Basic', 'arc'),
									'normal' => __('Normal', 'arc'),
									'fine' => __('Fine', 'arc')
								),
								'options' => array(
									'in_line' => true,
								)
							)); //radio
							$xbox->add_field(array(
								'id' => 'number-vid-per-row',
								'name' => __( 'Number of videos per page', 'arc' ),
								'type' => 'number',
								'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
								'default' => 20,
								'grid' => '5-of-8',
								'options' => array(
									'unit' => 'videos/page',
									'show_unit' => true,
									'show_spinner' => true,
									'disable_spinner' => false
								),
							)); //number


							$xbox->add_field(array(
								'id' => 'categories-desc-position',
								'name' => __( 'Category description position', 'arc' ),
								'desc' => __('Choose if you want to display the category description at the top or the bottom of category page.', 'arc'),
								'type' => 'radio',
								'default' => 'top',
								'items' => array(
									'top' => __('Top', 'arc'),
									'bottom' => __('Bottom', 'arc')
								),
								'options' => array(
									'in_line' => true,
								)
							)); //radio

						$xbox->add_field(array(
							'id' => 'tags',
							'name' => __( '<i class="xbox-icon xbox-icon-tag"></i> TAGS', 'arc' ),
							'type' => 'title'
						));
							$xbox->add_field(array(
								'id' => 'tag-desc-position',
								'name' => __( 'Tag description position', 'arc' ),
								'desc' => __('Choose if you want to display the tag description at the top or the bottom of tag page.', 'arc'),
								'type' => 'radio',
								'default' => 'top',
								'items' => array(
									'top' => __('Top', 'arc'),
									'bottom' => __('Bottom', 'arc')
								),
								'options' => array(
									'in_line' => true,
								)
							)); //radio
						$xbox->add_field(array(
							'id' => 'actors',
							'name' => __( '<i class="xbox-icon xbox-icon-users"></i> ACTORS', 'arc' ),
							'type' => 'title'
						));
							$xbox->add_field(array(
								'id' => 'number-actors-per-page',
								'name' => __( 'Number of actors per page', 'arc' ),
								'type' => 'number',
								'default' => 2,
								'grid' => '4-of-8',
								'options' => array(
									'unit' => 'actors per page',
									'show_unit' => true,
									'show_spinner' => true,
									'disable_spinner' => false
								),
							)); //number

		$xbox->close_tab_item('content');
		/*****end content****/



		/*****sidebar****/
		$xbox->open_tab_item('sidebar');
			$xbox->add_field(array(
				'id' => 'show-sidebar',
				'name' => __( 'Show sidebar', 'arc' ),
				'desc' => __('<a href="'. admin_url() .'widgets.php">Click here</a> to manage your sidebar with widgets.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
				)); //switcher
			$xbox->add_field(array(
				'id' => 'sidebar-settings',
				'name' => __( 'Sidebar settings', 'arc' ),
				'type' => 'image_selector',
				'default' => 'right',
				'items' => array(
					'left' => get_template_directory_uri().'/assets/img/sidebar-left.jpg',
					'right' => get_template_directory_uri().'/assets/img/sidebar-right.jpg'
				),
				'items_desc' => array(
					'left' => __('Left', 'arc'),
					'right' => __('Right', 'arc')
				),

				'options' => array (
					'show_if' => array(
						'show-sidebar',
						'=',
						'on'
					)
				)
			)); //textarea
		$xbox->close_tab_item('sidebar');
		/*****end sidebar****/

		/*****footer****/
		$xbox->open_tab_item('footer');
			$xbox->add_field(array(
				'id' => 'footer-columns',
				'name' => __( 'Footer columns', 'arc' ),
				'desc' => __('<a href="'. admin_url() .'widgets.php">Click here</a> to manage your footer with widgets.', 'arc'),
				'type' => 'image_selector',
				'default' => 'four-columns-footer',
				'items' => array(
					'one-columns-footer' => get_template_directory_uri().'/assets/img/footer-1-column.jpg',
					'two-columns-footer' => get_template_directory_uri().'/assets/img/footer-2-columns.jpg',
					'three-columns-footer' => get_template_directory_uri().'/assets/img/footer-3-columns.jpg',
					'four-columns-footer' => get_template_directory_uri().'/assets/img/footer-4-columns.jpg'
				),
				'items_desc' => array(
					'one-columns-footer' => __('1 Column' , 'arc'),
					'two-columns-footer' => __('2 Columns' , 'arc'),
					'three-columns-footer' => __('3 Columns' , 'arc'),
					'four-columns-footer' => __('4 Columns' , 'arc'),
				),
				'options' => array(
					'width' => '160px',//Default: 100px
					'height' => 'auto',//Default: auto
					'active_class' => 'xbox-active',//Default: xbox-active
					'active_color' => '#379FE7',//Default: #379FE7
					'in_line' => true,//Default: true
				),
			)); //image selector
			$xbox->add_field(array(
				'id' => 'footer-logo',
				'name' => __( 'Logo', 'arc' ),
				'desc' => __('Turn on to display your logo in the footer. It will use the logo image you set in the Logo & Favicon section.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
		$xbox->close_tab_item('footer');
		/*****end footer****/

		/*****membership****/
		$xbox->open_tab_item('membership');
			$xbox->add_field(array(
				'id' => 'enable-membership',
				'name' => __( 'Enable membership', 'arc' ),
				'desc' => __('Enable membership system with login/register feature, user profile, video submit, etc.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'enable-video-submission',
				'name' => __( 'Enable video submission', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'video-submit-settings',
				'name' => __( 'VIDEO SUBMIT SETTINGS', 'arc' ),
				'type' => 'title',
				'options' => array(
					'show_if' => array(
						'enable-video-submission', '=', 'on'
					)
				)
			)); //switcher
				$xbox->add_field(array(
					'id' => 'title-required',
					'name' => __( 'Title required', 'arc' ),
					'type' => 'switcher',
					'default' => 'on',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
				$xbox->add_field(array(
					'id' => 'desc-required',
					'name' => __( 'Description required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
				$xbox->add_field(array(
					'id' => 'video-required',
					'name' => __( 'Video URL required', 'arc' ),
					'type' => 'switcher',
					'default' => 'on',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
				$xbox->add_field(array(
					'id' => 'embed-required',
					'name' => __( 'Embed required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
				$xbox->add_field(array(
					'id' => 'thumb-required',
					'name' => __( 'Thumbnail URL required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
				$xbox->add_field(array(
					'id' => 'tags-required',
					'name' => __( 'Tags required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
				$xbox->add_field(array(
					'id' => 'actors-required',
					'name' => __( 'Actors required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
				$xbox->add_field(array(
					'id' => 'duration-required',
					'name' => __( 'Duration required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
					'options' => array(
						'show_if' => array(
							'enable-video-submission', '=', 'on'
						)
					)
				)); //switcher
			$xbox->add_field(array(
				'id' => 'membership-links',
				'name' => __( 'MEMBERSHIP LINKS', 'arc' ),
				'type' => 'title'
			)); //title
				$xbox->add_field(array(
					'id' => 'display-submit-video',
					'name' => __( 'Display "Submit a Video" link', 'arc'),
					'type' => 'switcher',
					'default' => 'on'
				)); //switcher
				$xbox->add_field(array(
					'id' => 'display-profile-link',
					'name' => __( 'Display "My Profile" link', 'arc'),
					'type' => 'switcher',
					'default' => 'on'
				)); //switcher
				$xbox->add_field(array(
					'id' => 'display-channel-link',
					'name' => __( 'Display "My Channel" link', 'arc'),
					'type' => 'switcher',
					'default' => 'on'
				)); //switcher
			$xbox->add_field(array(
				'id' => 'enable-recaptcha',
				'name' => __( 'Enable reCaptcha', 'arc' ),
				'desc' => __('Enable a Google reCaptcha security code on registration and submit video page. You can get your reCAPTCHA keys here: <a href="https://www.google.com/recaptcha/admin">Google reCaptcha Keys</a>', 'arc'),
				'type' => 'switcher',
				'default' => 'off'
			)); //switcher
			$xbox->add_field(array(
				'id' => 'reCaptcha-settings1',
				'name' => __( 'reCaptcha settings: Site key', 'arc' ),
				'type' => 'text',
				'default' => __('Site key', 'arc'),
				'options' => array(
					'show_if' => array(
						'enable-recaptcha', '=', 'on'
					)
				)
			)); //text
			$xbox->add_field(array(
				'id' => 'reCaptcha-settings2',
				'name' => __( 'reCaptcha settings: Secret key', 'arc' ),
				'type' => 'text',
				'default' => __('Secret key', 'arc'),
				'options' => array(
					'show_if' => array(
						'enable-recaptcha', '=', 'on'
					)
				)
			)); //text
			$xbox->add_field(array(
				'id' => 'display-admin-bar',
				'name' => __( 'Display admin bar for logged in users', 'arc' ),
				'desc' => __('Display the WP admin bar when a user is logged on your site and let him go to the admin.', 'arc'),
				'type' => 'switcher',
				'default' => 'off'
			)); //switcher

		$xbox->close_tab_item('membership');
		/*****end membership****/

		/*****tools****/
		$xbox->open_tab_item('tools');
			$xbox->add_field(array(
				'id' => 'pages',
				'name' => __( 'PAGES', 'arc' ),
				'type' => 'title'
			)); //pages
				$xbox->add_field(array(
					'id' => 'create-categories-page',
					'name' => __( 'Create categories page', 'arc' ),
					'type' => 'button',
					'value' => 'Create categories page',
					'desc' => __('Display illustrated categories like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create categories page'
					),
					'content' => 'Create categories page'
				));
				$xbox->add_field(array(
					'id' => 'create-actors-page',
					'name' => __( 'Create actors page', 'arc' ),
					'type' => 'button',
					'value' => 'Create actors page',
					'desc' => __('Display illustrated actors like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create actors page'
					),
					'content' => 'Create actors page'
				));
				$xbox->add_field(array(
					'id' => 'create-tags-page',
					'name' => __( 'Create tags page', 'arc' ),
					'type' => 'button',
					'value' => 'Create tags page',
					'desc' => __('Display illustrated actors like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create tags page'
					),
					'content' => 'Create tags page'
				));
				$xbox->add_field(array(
					'id' => 'create-videos-page',
					'name' => __( 'Create Videos Submit page', 'arc' ),
					'type' => 'button',
					'value' => 'Create Videos Submit page',
					'desc' => __('Display illustrated Videos Submit like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create Videos Submit page'
					),
					'content' => 'Create Videos Submit page'
				));
				$xbox->add_field(array(
					'id' => 'create-blog-page',
					'name' => __( 'Create blog page', 'arc' ),
					'type' => 'button',
					'value' => 'Create blog page',
					'desc' => __('Display illustrated blog like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create blog page'
					),
					'content' => 'Create blog page'
				));
				$xbox->add_field(array(
					'id' => 'create-profile-page',
					'name' => __( 'Create My Profile page', 'arc' ),
					'type' => 'button',
					'value' => 'Create My Profile page',
					'desc' => __('Display illustrated My Profile like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create My Profile page'
					),
					'content' => 'Create My Profile page'
				));
			$xbox->add_field(array(
				'id' => 'menu',
				'name' => __( 'MENU', 'arc' ),
				'type' => 'title'
			)); //menu
				$xbox->add_field(array(
					'id' => 'create-menu',
					'name' => __( 'Create a menu like on Demo site.', 'arc' ),
					'type' => 'button',
					'value' => 'Create menu',
					'desc' => __('Create a <a href="' . admin_url() . 'nav-menus.php">menu</a> like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create menu'
					),
					'content' => 'Create menu'
				));
			$xbox->add_field(array(
				'id' => 'widgets',
				'name' => __( 'WIDGETS', 'arc' ),
				'type' => 'title'
			)); //widgets
				$xbox->add_field(array(
					'id' => 'create-widgets',
					'name' => __( 'Create widgets like on Demo site.', 'arc' ),
					'type' => 'button',
					'value' => 'Create widgets',
					'desc' => __('Create a <a href="' . admin_url() . 'widgets.php">widgets</a> like on Demo site.', 'arc'),
					'options' => array(
						'tag' => 'button'
					),
					'attributes' => array(
						'value' => 'Create widgets'
					),
					'content' => 'Create widgets'
				));
		$xbox->close_tab_item('tools');
		/*****end tools****/


		/*****mobile****/
		$xbox->open_tab_item( 'mobile');
			$xbox->add_field(array(
				'id' => 'mob-general',
				'name' => __( '<i class="xbox-icon xbox-icon-gear"></i> GENERAL', 'arc' ),
				'type' => 'title'
			)); //general
				$xbox->add_field(array(
					'id' => 'mob-number_videos_per_page',
					'name' => __( 'Number of videos per page', 'arc' ),
					'type' => 'number',
					'default' => 20,
					'grid' => '2-of-8',
					'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page-mobile.jpg' . '" />',
					'options' => array(
						'unit' => 'videos/page',
						'show_unit' => true,
						'show_spinner' => true,
						'disable_spinner' => false,
					),
				)); //number
				$xbox->add_field(array(
					'id' => 'mob-number_videos_per_row',
					'name' => __( 'Number of videos per row', 'arc' ),
					'type' => 'number',
					'default' => 2,
					'grid' => '2-of-8',
					'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-row-mobile.jpg' . '" />',
					'options' => array(
						'unit' => 'videos/page',
						'show_unit' => true,
						'show_spinner' => true,
						'disable_spinner' => false,
					),
				)); //number
				$xbox->add_field(array(
					'id' => 'mob-show-sidebar',
					'name' => __( 'Show sidebar', 'arc' ),
					'desc' => __('Show the sidebar on mobile devices too.', 'arc'),
					'type' => 'switcher',
					'default' => 'on'
				)); //switcher
				$xbox->add_field(array(
					'id' => 'mob-homepage-widgets',
					'name' => __( 'Disable homepage widgets', 'arc' ),
					'desc' => __('Do not display the homepage widgets on mobile devices.', 'arc'),
					'type' => 'switcher',
					'default' => 'off'
				)); //switcher
		$xbox->close_tab_item( 'mobile' );
		/*****mobile****/

	$xbox->close_tab('main-tab');
}
