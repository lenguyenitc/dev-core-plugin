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
	$options = [
		'id' => 'my-theme-options',
		'title' => 'Theme Options',
		'menu_title' => 'Theme Options',
		/*'icon' => '', //Menu icon*/
		'skin' => 'teal', // Skins: blue, lightblue, green, teal, pink, purple, bluepurple, yellow, orange'
		'layout' => 'boxed', //wide boxed
		'header' => [
			'icon' => '<img src="'.get_template_directory_uri() . '/assets/img/icons/PX-X-2.png"/>',
			'desc' => __('PornX Settings', 'arc'),
		],
		'import_message' => __( 'Settings imported.', 'arc' ),
		'capability' => 'edit_published_posts',
	];
	$xbox = xbox_new_admin_page( $options );

	/****Tab Theme Options*****/
	$xbox->add_main_tab([
		'name' => 'Main tab',
		'id' => 'main-tab',
		'items' => [
			'niches' => '<i class="xbox-icon xbox-icon-star"></i>' . __('Niches', 'arc'),
			'general' => '<i class="xbox-icon xbox-icon-gear"></i>' . __('General', 'arc'),
			'visual-options' => '<i class="xbox-icon xbox-icon-desktop"></i>' . __('Visual options', 'arc'),
			'header' => '<i class="xbox-icon xbox-icon-arrow-up"></i>' . __('Header', 'arc'),
			'home_page' => '<i class="xbox-icon xbox-icon-home"></i>' . __('Homepage', 'arc'),
			'login' => '<i class="xbox-icon xbox-icon-sign-in"></i>' . __('Login', 'arc'),
			'video_single_post' => '<i class="xbox-icon xbox-icon-play"></i>' . __('Single Video Page', 'arc'),
			'premium' => '<i class="xbox-icon xbox-icon-money"></i>' . __('Premium label', 'arc'),
			'categories' => '<i class="xbox-icon xbox-icon-folder"></i>' . __('Categories', 'arc'),
			'tags' => '<i class="xbox-icon xbox-icon-tags"></i>' . __('Tags', 'arc'),
			'actors' => '<i class="xbox-icon xbox-icon-users"></i>' . __('Pornstars', 'arc'),
			'photos' => '<i class="xbox-icon xbox-icon-image"></i>' . __('Photos & GIFs', 'arc'),
			'sidebar' => '<i class="xbox-icon xbox-icon-list-alt"></i>' . __('Sidebar', 'arc'),
			'footer' => '<i class="xbox-icon xbox-icon-arrow-down"></i>' . __('Footer', 'arc'),
			'membership' => '<i class="xbox-icon xbox-icon-user"></i>' . __('Upload Settings', 'arc'),
			'tools' => '<i class="xbox-icon xbox-icon-wrench"></i>' . __('Default Pages', 'arc'),
			'mobile' => '<i class="xbox-icon xbox-icon-mobile"></i>' . __('Mobile', 'arc'),
			'email' => '<i class="xbox-icon xbox-icon-at"></i>' . __('Email Settings', 'arc'),
			'community' => '<i class="xbox-icon xbox-icon-bullhorn"></i>' . __('Community', 'arc'),
			/*'export' => '<i class="xbox-icon xbox-icon-download"></i>' . __('Export & Import', 'arc'),*/
		]
	]);
	/****End Tab Theme Options*****/

		/****niche***/
		$xbox->open_tab_item('niches');
			$xbox->add_field([
				'id' => 'choose-niche',
				'name' => __( 'Pick a niche', 'arc' ),
				'desc' => __('Select your preferred niche, then click the Save changes button. Your site\'s appearance will change to reflect the chosen color palette. You may further customize its appearance through <a href="'.admin_url().'customize.php?return=%2Fwp-admin%2Fadmin.php%3Fpage%3Dmy-theme-options" target="_blank">Customize</a> > Theme Colors.', 'arc'),
				'type' => 'image_selector',
				'default' => 'trans',
				'items' => [
					'trans' => get_template_directory_uri() .'/assets/img/pornx-default.png',
					'light' => get_template_directory_uri() .'/assets/img/pornx-light.png',
					'milf' => get_template_directory_uri() .'/assets/img/milf.png',
					'college' => get_template_directory_uri() .'/assets/img/teen.png',
					'hentai' => get_template_directory_uri() .'/assets/img/hentai.png',
					'livexcams' => get_template_directory_uri() .'/assets/img/gay.png',
					'transs' => get_template_directory_uri() .'/assets/img/trans.png',
					'lesbian' => get_template_directory_uri() .'/assets/img/lesbian.png',
					'filf' => get_template_directory_uri() .'/assets/img/fetish.png'
				],
				'options' => [
					'width' => '300px',//Default: 100px
					'height' => '300px',//Default: auto
					'active_class' => 'xbox-active',//Default: xbox-active
					'active_color' => '#379FE7',//Default: #379FE7
					'in_line' => true,//Default: true
				],
			]);
		$xbox->close_tab_item('niches');
		/****end niche***/

		/****general***/
		$xbox->open_tab_item('general');
			$xbox->add_field([
				'id' => 'number_videos_per_page',
				'name' => __( 'Videos per page', 'arc' ),
				'type' => 'number',
				'default' => 28,
				'grid' => '4-of-8',
				'desc' => 'This option only affects the All Videos section on the homepage. Categories and widgets have their own settings. <br><img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
				'options' => [
					'unit' => 'videos/page',
					'show_unit' => true,
					'show_spinner' => true,
					'disable_spinner' => false,
				],
			]); //number
			$xbox->add_field([
				'id' => 'number_videos_per_row',
				'name' => __( 'Videos per row', 'arc' ),
				'type' => 'number',
				'default' => 4,
				'grid' => '4-of-8',
				'desc' => '<p>Min: 2; Max: 5 videos per row</p><img src="' . get_template_directory_uri() . '/assets/img/videos-per-row.jpg' . '" />',
				'options' => [
					'unit' => 'videos/row',
					'show_unit' => true,
					'show_spinner' => true,
					'disable_spinner' => false,
				],
				'attributes' => array(
					'min' => 2,
					'max' => 5,
					'step' => 1,
					'precision' => 0,
				)
			]); //number
			$xbox->add_field([
				'id' => 'thumb_rotation',
				'name' => __( 'Thumbnail aspect ratio', 'arc' ),
				'type' => 'radio',
				'default' => '16/9',
				'items' => [
					'16/9' => '16/9',
					'4/3' => '4/3'
				],
				'options' => [
					'in_line' => true,
				]
			]); //radio
			$xbox->add_field([
				'id' => 'thumb_quality',
				'name' => __( 'Thumbnail quality', 'arc' ),
				'type' => 'radio',
				'desc' => __('Basic = High compression, Normal = Medium compression, Fine = Low compression', 'arc'),
				'default' => 'full',
				'items' => [
					'small' => __('Basic', 'arc'),
					'medium' => __('Normal', 'arc'),
					'full' => __('Fine', 'arc')
				],
				'options' => [
					'in_line' => true,
				]
			]); //radio
			$xbox->add_field([
				'id' => 'enable_preview',
				'name' => __( 'Enable trailers on mouseover', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'enable_thumb_rotation',
				'name' => __( 'Enable thumbnails on mouseover', 'arc' ),
				'type' => 'switcher',
				'default' => 'off',
				'options' => [
					'show_if' => ['enable_preview', '=', 'on']
				]
			]); //switcher
			$xbox->add_field([
				'id' => 'display_upgrade_btn',
				'name' => __( 'Display Upgrade button', 'arc' ),
				'desc' => __('Display the Upgrade button next to the search bar.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]);
			$xbox->add_field([
				'id' => 'upgrade-button-text',
				'name' => __( 'Upgrade button\'s text', 'arc' ),
				'desc' => 'Max: 20 characters',
				'default' => __('Upgrade', 'arc'),
				'type' => 'text',
				'grid' => '4-of-8',
				'options' => [
					'show_if' => ['display_upgrade_btn', '=', 'on']
				]
			]);

			$xbox->add_field([
				'id' => 'enable_view',
				'name' => __( 'Enable views', 'arc' ),
				'desc' => __('Display view count under the video player, video thumbnails, and images. This also enables the "Most viewed videos" filter.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'enable_duration',
				'name' => __( 'Enable duration', 'arc' ),
				'desc' => __('Display video duration under thumbnails and enable the "Longest videos" filter.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'enable_rating',
				'name' => __( 'Enable rating', 'arc' ),
				'desc' => __('Display rating under the video player, video thumbnails, comments, and images.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'allow_rating',
				'name' => __( 'Allow guests to rate', 'arc' ),
				'desc' => __('Allow guests to rate comments, images, and videos without having to log in.', 'arc'),
				'type' => 'switcher',
				'default' => 'on',
				'options' => [
					'show_if' => ['enable_rating', '=', 'on']
				]
			]); //switcher
			$xbox->add_field([
				'id' => 'enable_breadcrumbs',
				'name' => __( 'Enable breadcrumbs', 'arc' ),
				'desc' => __('Display breadcrumbs on pages for easier navigation.', 'arc'),
				'type' => 'switcher',
				'default' => 'off'
			]);

		$xbox->close_tab_item('general');
		/****end general***/

		/****visual options***/
		$xbox->open_tab_item('visual-options');
			$xbox->add_field([
				'id' => 'layout',
				'name' => __( 'Website layout', 'arc' ),
				'type' => 'image_selector',
				'default' => 'boxed',
				'items' => [
					'boxed' => get_template_directory_uri().'/assets/img/layout-boxed.jpg',
					'full-width' => get_template_directory_uri().'/assets/img/layout-full-width.jpg'
				],
				'items_desc' => [
					'boxed' => __('Boxed', 'arc'),
					'full-width' => __('Full Width', 'arc')
				],
				'options' => [
					'width' => '160px',
					'height' => 'auto',
					'active_class' => 'xbox-active',
					'active_color' => '#379FE7',
					'in_line' => true,
				],
			]); //image selector
			$xbox->add_field([
				'id' => 'rendering',
				'name' => __( 'Rendering', 'arc' ),
				'desc' => __('Display gradient and shadows for buttons, input fields, and the selected menu option.', 'arc'),
				'type' => 'radio',
				'default' => 'flat',
				'items' => [
					'flat' => __('Flat', 'arc'),
					'gradient' => __('Gradient', 'arc')
				],
				'options' => [
					'in_line' => true,
				]
			]); //radio
		$xbox->close_tab_item('visual-options');
		/****end visual options***/

		/****header*****/
		$xbox->open_tab_item('header');
			$xbox->add_field([
				'id' => 'social-profiles',
				'name' => __( 'Show social links', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher

			$xbox->add_field([
				'id' => 'your-social-profiles',
				'name' => __( 'YOUR SOCIAL PROFILES', 'arc' ),
				'type' => 'title',
				'options' => [
					'show_if' => ['social-profiles', '=', 'on']]
			]);
    $xbox->open_mixed_field( array('name' => 'Link 1','options' => [
        'show_if' => ['social-profiles', '=', 'on']
    ],));
                $xbox->add_field([
                    'id' => 'your-fb-name',
                    'name' => __( 'Text', 'arc' ),
                    'type' => 'text',
                    'options' => [
                        'show_if' => ['social-profiles', '=', 'on']
                    ],
                    'attributes' => array(
                        'maxlength' => 10,
                    ),
                    'grid' => '8-of-8'
                ]);
				$xbox->add_field([
					'id' => 'your-fb',
					'name' => __( 'URL', 'arc' ),
					'type' => 'text',
					'options' => [
						'show_if' => ['social-profiles', '=', 'on']
					],
					'grid' => '8-of-8'
				]);
    $xbox->close_mixed_field();
    $xbox->open_mixed_field( array('name' => 'Link 2','options' => [
        'show_if' => ['social-profiles', '=', 'on']
    ],));
            $xbox->add_field([
                'id' => 'your-tw-name',
                'name' => __( 'Text', 'arc' ),
                'type' => 'text',
                'options' => [
                    'show_if' => ['social-profiles', '=', 'on']
                ],
                'attributes' => array(
                    'maxlength' => 10,
                ),
                'grid' => '8-of-8'
            ]);
				$xbox->add_field([
					'id' => 'your-tw',
					'name' => __( 'URL', 'arc' ),
					'type' => 'text',
					'options' => [
						'show_if' => ['social-profiles', '=', 'on']
					],
					'grid' => '8-of-8'
				]);
    $xbox->close_mixed_field();
    $xbox->open_mixed_field( array('name' => 'Link 3','options' => [
        'show_if' => ['social-profiles', '=', 'on']
    ],));
            $xbox->add_field([
                'id' => 'your-yt-name',
                'name' => __( 'Text', 'arc' ),
                'type' => 'text',
                'options' => [
                    'show_if' => ['social-profiles', '=', 'on']
                ],
                'attributes' => array(
                    'maxlength' => 10,
                ),
                'grid' => '8-of-8'
            ]);
				$xbox->add_field([
					'id' => 'your-yt',
					'name' => __( 'URL', 'arc' ),
					'type' => 'text',
					'options' => [
						'show_if' => ['social-profiles', '=', 'on']
					],
					'grid' => '8-of-8'
				]);
    $xbox->close_mixed_field();
    $xbox->open_mixed_field( array('name' => 'Link 4','options' => [
        'show_if' => ['social-profiles', '=', 'on']
    ],));
            $xbox->add_field([
                'id' => 'your-ins-name',
                'name' => __( 'Text', 'arc' ),
                'type' => 'text',
                'options' => [
                    'show_if' => ['social-profiles', '=', 'on']
                ],
                'attributes' => array(
                    'maxlength' => 10,
                ),
                'grid' => '8-of-8'
            ]);
				$xbox->add_field([
					'id' => 'your-ins',
					'name' => __( 'URL', 'arc' ),
					'type' => 'text',
					'options' => [
						'show_if' => ['social-profiles', '=', 'on']
					],
					'grid' => '8-of-8'
				]);
    $xbox->close_mixed_field();
			$xbox->add_field([
				'id' => 'search-bar',
				'name' => __( 'Show search bar', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'user-links',
				'name' => __( 'USER MENU LINKS', 'arc' ),
				'type' => 'title'
			]); //title
			$xbox->add_field([
				'id' => 'display-submit-video',
				'name' => __( 'Video upload', 'arc'),
				'desc' => 'Display the Video link in the Upload menu.',
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display-submit-photo',
				'name' => __( 'Album upload', 'arc'),
                'desc' => 'Display the Album link in the Upload menu.',
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display-channel-link',
				'name' => __( 'My Uploads', 'arc'),
                'desc' => 'Display the My Uploads link in the User menu.',
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display-public-profile-link',
				'name' => __( 'Public Profile', 'arc'),
                'desc' => 'Display the Public Profile link in the User menu.',
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display-watch-list-link',
				'name' => __( 'Watched Videos', 'arc'),
                'desc' => 'Display the Watched Video link in the User menu.',
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display-subscriptions-link',
				'name' => __( 'Account Settings', 'arc'),
                'desc' => 'Display the Account Settings link in the User menu.',
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display-playlists-link',
				'name' => __( 'My Playlists', 'arc'),
				'desc' => __( 'Display the My Playlists link in the User menu.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display-favorites-link',
				'name' => __( 'My Favorites', 'arc'),
				'desc' => __( 'Display the My Favorites link in the User menu.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
		$xbox->close_tab_item('header');
		/****end header*****/

	/***login tab***/
	$xbox->open_tab_item('login');
		$xbox->add_field([
			'id' => 'homepage_login',
			'name' => __( 'Homepage', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url('/').'">Homepage.</a>', 'arc'),
			'default' => 'popup',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'video_login',
			'name' => __( 'Video pages', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on video pages.', 'arc'),
			'default' => 'popup',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'tags_login',
			'name' => __( 'Tags', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url().'/tags/">Tags</a> page.', 'arc'),
			'default' => 'page',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'categories_login',
			'name' => __( 'Categories', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url().'/categories/">Categories</a> page.', 'arc'),
			'default' => 'popup',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'community_login',
			'name' => __( 'Community', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url().'/community/">Community</a> page.', 'arc'),
			'default' => 'popup',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'actors_login',
			'name' => __( 'Pornstars', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url().'/pornstars/">Pornstars</a> page.', 'arc'),
			'default' => 'page',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'photos_login',
			'name' => __( 'Photos & GIFs', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url().'/photos/">Photos & GIFs</a> page.', 'arc'),
			'default' => 'popup',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'profile_login',
			'name' => __( 'Public Profile', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on public profiles.', 'arc'),
			'default' => 'popup',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'upload_video_login',
			'name' => __( 'Video Upload', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url().'/upload/">Video Upload</a> page.', 'arc'),
			'default' => 'page',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'upload_images_login',
			'name' => __( 'New Album', 'arc' ),
			'type' => 'radio',
			'desc' => __('Choose which login method should be used on the <a target="_blank" href="'.site_url().'/new-album/">New Album</a> page.', 'arc'),
			'default' => 'page',
			'items' => [
				'popup' => 'Login popup',
				'page' => 'Login page'
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
	$xbox->close_tab_item('login');
	/*** end login tab***/

	/***home-page***/
	$xbox->open_tab_item('home_page');
		$xbox->add_field([
			'id' => 'show-carousel-of-videos',
			'name' => __( 'Enable video carousel', 'arc' ),
			'desc' => __('Featured videos will be shown in the carousel.', 'arc'),
			'type' => 'switcher',
			'default' => 'off'
		]); //switcher
		$xbox->add_field([
			'name' => __( 'CAROUSEL SETTINGS', 'arc' ),
			'type' => 'title',
			'options' => [
				'show_if' => ['show-carousel-of-videos', '=', 'on']
			]
		]); //title
		$xbox->add_field([
			'id' => 'videos-amount',
			'name' => __( 'Number of videos displayed', 'arc' ),
			'desc' => __('Enter a number between 6 and 40', 'arc'),
			'type' => 'number',
			'default' => 15,
			'grid' => '4-of-8',
			'options' => [
				'unit' => 'videos',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
				'show_if' => ['show-carousel-of-videos', '=', 'on']
			],
		]); //number
		$xbox->add_field([
			'id' => 'show-video-title',
			'name' => __( 'Show video titles', 'arc' ),
			'desc' => __('Display video titles over thumbnails.', 'arc'),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['show-carousel-of-videos', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'show-carousel-on-mobile',
			'name' => __( 'Show carousel on mobile devices', 'arc' ),
			'type' => 'switcher',
			'default' => 'off'
		]); //switcher

	$xbox->add_field([
		'id' => 'enable_all_videos',
		'name' => __( 'Enable the All videos section', 'arc' ),
		'type' => 'switcher',
		'default' => 'on'
	]); //switcher

	$xbox->add_field([
		'id' => 'title_all_videos',
		'name' => __( 'All videos section\'s title', 'arc' ),
		'type' => 'text',
		'default' => 'All videos',
		'options' => [
			'show_if' => ['enable_all_videos', '=', 'on']
		]
	]);

	$xbox->close_tab_item('home_page');
	/*** end home-page***/

	/***video_single_post***/
	$xbox->open_tab_item('video_single_post');
		$xbox->add_field([
			'id' => 'enable_thumbs_below',
			'name' => __('Display thumbnails below videos', 'arc' ),
			'desc' => __('Displays up to 6 thumbnails below videos, if available.', 'arc'),
			'type' => 'switcher',
			'default' => 'off'
		]);
		$xbox->add_field([
			'id' => 'display-tracking-button',
			'name' => __( 'Enable video downloading', 'arc' ),
			'desc' => __('You need to update <strong><a target="_blank" href="'.admin_url().'options-permalink.php">permalinks</a></strong> for this feature to work correctly.', 'arc'),
			'type' => 'switcher',
			'default' => 'off'
		]); //switcher

        /*$xbox->add_field([
            'id' => 'hotlink-protection',
            'name' => __( 'Disable download videos', 'arc' ),
            'desc' => __('If this option is enabled, users will not be able to download videos via direct link. Once the option is enabled or disabled, <strong> you need to <a target="_blank" href="'.admin_url().'options-permalink.php">update permalinks</a></strong>.', 'arc'),
            'type' => 'switcher',
            'default' => 'off'
        ]); *///switcher


		$xbox->add_field([
			'id' => 'tracking-button-settings',
			'name' => __( 'DOWNLOAD BUTTON\'S SETTINGS', 'arc' ),
			'type' => 'title',
			'options' => [
				'show_if' => ['display-tracking-button', '=', 'on']
			]
		]); //title
		$xbox->add_field([
			'id' => 'tracking-button-link',
			'name' => __( 'Download button link', 'arc' ),
			'desc' => __('Use the same link for all download buttons.', 'arc'),
			'type' => 'text',
			'grid' => '4-of-8',
			'options' => [
				'show_if' => ['display-tracking-button', '=', 'on']
			]
		]);
		$xbox->add_field([
			'id' => 'tracking-button-text',
			'name' => __( 'Download button text', 'arc' ),
			'desc' => 'Leave blank to display only the download icon.',
			'default' => '',
			'type' => 'text',
			'grid' => '4-of-8',
			'options' => [
				'show_if' => ['display-tracking-button', '=', 'on']
			]
		]);
		$xbox->add_field([
			'id' => 'close-ads--button-settings',
			'name' => __( 'CLOSE BUTTON\'S SETTINGS', 'arc' ),
			'type' => 'title',
			'options' => [
				'show_if' => ['display-tracking-button', '=', 'on']
			]
		]); //title
		$xbox->add_field([
			'id' => 'close-ads--button-text',
			'name' => __( 'Close button\'s text', 'arc' ),
			'default' => __('Close', 'arc'),
			'type' => 'text',
			'grid' => '4-of-8',
		]);
		$xbox->add_field([
			'id' => 'video-about',
			'name' => __( 'About Section\'s Settings', 'arc' ),
			'type' => 'title'
		]); //title
		$xbox->add_field([
			'id' => 'show-desc',
			'name' => __( 'Show description', 'arc' ),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'show-author',
			'name' => __( 'Show uploader', 'arc' ),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'show_subscribe_button',
			'name' => __('Show subscription button', 'arc' ),
			'type' => 'switcher',
			'default' => 'on'
		]);
		$xbox->add_field([
			'id' => 'show-date',
			'name' => __( 'Show publish date', 'arc' ),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'show-actors',
			'name' => __( 'Show pornstars', 'arc' ),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'show-actors-thumb',
			'name' => __( 'Show pornstar thumbnails', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['show-actors', '=', 'on']
			],
			'desc' => 'Display pornstar thumbnails from the Pornstars page.'
		]); //switcher
		$xbox->add_field([
			'id' => 'show-actors-pixels',
			'name' => __('Pornstar thumbnail size', 'arc' ),
			'type' => 'number',
			'default' => 25,
			'grid' => '4-of-8',
			'options' => [
				'unit' => 'pixels',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
				'show_if' => ['show-actors-thumb', '=', 'on'],
			],
			'attributes' => array(
				'min' => 25,
				'max' => 40,
				'step' => 1,
				'precision' => 0,
			),
			'desc' => 'Set the width and height of displayed thumbnails. Width and height are always matching (e.g. 25x25px). Min: 25; Max: 40.'
		]); //switcher

		$xbox->add_field([
			'id' => 'show-categories',
			'name' => __( 'Show categories', 'arc' ),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'show-tags',
			'name' => __( 'Show tags', 'arc' ),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher

		$xbox->add_field([
			'id' => 'show-more-settings',
			'name' => __( 'Show Read more', 'arc' ),
			'desc' => __('Adds a Read more separator to long descriptions.', 'arc'),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'actors-label',
			'name' => __( 'Pornstars label', 'arc' ),
			'desc' => __('Change how the site refers to pornstars.', 'arc'),
			'type' => 'text',
			'default' => 'Pornstars label'
		]); //text
		$xbox->add_field([
			'id' => 'video-share',
			'name' => __( 'Enable sharing', 'arc' ),
			'desc' => __('Display the Share tab with social network buttons.', 'arc'),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'facebook',
			'name' => __( 'Facebook', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' =>[
				'show_if' => ['video-share', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'twitter',
			'name' => __( 'Twitter', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['video-share', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'linkedin',
			'name' => __( 'LinkedIn', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['video-share', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'tumblr',
			'name' => __( 'Tumblr', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['video-share', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'reddit',
			'name' => __( 'Reddit', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['video-share', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'odnoklassniki',
			'name' => __( 'Odnoklassniki', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['video-share', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'email',
			'name' => __( 'Email', 'arc' ),
			'type' => 'switcher',
			'default' => 'on',
			'options' => [
				'show_if' => ['video-share', '=', 'on']
			]
		]); //switcher
		$xbox->add_field([
			'id' => 'enable_embed_code',
			'name' => __('Enable Embedding', 'arc' ),
			'desc' => __('Display an Embed button in the Share tab.', 'arc'),
			'type' => 'switcher',
			'default' => 'on'
		]);
		$xbox->add_field([
			'id' => 'enable_playlists_tab',
			'name' => __('Enable playlists', 'arc' ),
			'desc' => __('Display the Add to playlist tab.', 'arc'),
			'type' => 'switcher',
			'default' => 'on'
		]);
		$xbox->add_field([
			'id' => 'enable_report_video',
			'name' => __('Enable reporting', 'arc' ),
			'desc' => __('Display the Report tab.', 'arc'),
			'type' => 'switcher',
			'default' => 'on'
		]);

		$xbox->add_field([
			'id' => 'display-related-videos',
			'name' => __( 'Display related videos', 'arc' ),
			'desc' => __('Display the Related videos section on the page.', 'arc'),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher
		$xbox->add_field([
			'id' => 'related-videos-settings',
			'name' => __( 'Number of related videos shown', 'arc' ),
			'type' => 'radio',
			'default' => '4',
			'items' => [
				'4' => __('4 videos', 'arc'),
				'8' => __('8 videos', 'arc')
			],
			'options' => [
				'in_line' => true,
				'show_if' => ['display-related-videos', '=', 'on']
			],
		]); //number
	$xbox->close_tab_item('video_single_post');
	/*** end video_single_post***/

	/***premium label***/
	$xbox->open_tab_item('premium');
		$xbox->add_field([
			'id' => 'use-premium-label',
			'name' => __( 'Use the Dashboard icon for video thumbnails', 'arc' ),
			'desc' => __('Choose if you want to use the same image in the backend and on video thumbnails.', 'arc'),
			'type' => 'switcher',
			'default' => 'off'
		]); //switcher

		$xbox->add_field(array(
			'id' => 'thumb-premium-label',
			'name' => __( 'Video thumbnails (Frontend)', 'arc' ),
			'desc' => __('Change the premium icon displayed on video thumbnails.<br>
							Maximum upload file size: 120 KB. Dimensions: 50x50px.', 'arc'),
			'type' => 'file',
			'options' => array(
				'multiple' => false,
				'mime_types' => array( 'jpg', 'jpeg', 'png'),
				'preview_size' => array( 'width' => '50px' ),
			)
		));
	$xbox->close_tab_item('premium');

	/***categories***/
	$xbox->open_tab_item('categories');
		$xbox->add_field([
			'id' => 'show_videos',
			'name' => __( 'Starting filter on category pages', 'arc' ),
			'type' => 'radio',
			'default' => 'all',
			'items' => [
				'all' => __('All videos', 'arc'),
				'latest' => __('Latest videos', 'arc'),
				'most-viewed' => __('Most viewed videos', 'arc'),
				'longest' => __('Longest videos', 'arc'),
				'popular' => __('Popular videos', 'arc'),
				/*'random' => __('Random videos', 'arc'),*/
				'featured' => __('Featured videos', 'arc'),
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio
		$xbox->add_field([
			'id' => 'number-categ-per-page',
			'name' => __( 'Number of categories per page', 'arc' ),
			'type' => 'number',
			'default' => 15,
			'grid' => '5-of-8',
			'options' => [
				'unit' => 'categories/page',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false
			],
            'attributes' => array(
                'min' => 1,
                'step' => 1,
                'precision' => 0,
            )
		]); //number
		$xbox->add_field([
			'id' => 'number-categ-per-row',
			'name' => __( 'Number of categories per row', 'arc' ),
			'type' => 'number',
			'desc' => '<p>min: 2, max: 5</p><img src="' . get_template_directory_uri() . '/assets/img/videos-per-row.jpg' . '" />',
			'default' => 5,
			'grid' => '5-of-8',
			'options' => [
				'unit' => 'categories/row',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false
			],
			'attributes' => array(
				'min' => 2,
				'max' => 5,
				'step' => 1,
				'precision' => 0,
			)
		]); //number
		$xbox->add_field([
			'id' => 'categories-thumb-quality',
			'name' => __( 'Category thumbnail quality', 'arc' ),
			'desc' => __('Basic = High compression, Normal = Medium compression, Fine = Low compression', 'arc'),
			'type' => 'radio',
			'default' => 'full',
			'items' => [
				'small' => __('Basic', 'arc'),
				'medium' => __('Normal', 'arc'),
				'full' => __('Fine', 'arc')
			],
			'options' => ['in_line' => true,]
		]); //radio
		$xbox->add_field([
			'id' => 'number-vid-per-row',
			'name' => __( 'Number of videos per page', 'arc' ),
			'type' => 'number',
			'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
			'default' => 20,
			'grid' => '5-of-8',
			'options' => [
				'unit' => 'videos/page',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false
			],
            'attributes' => array(
                'min' => 1,
                'step' => 1,
                'precision' => 0,
            )
		]); //number
		$xbox->add_field([
			'id' => 'categories-desc-position',
			'name' => __( 'Category description position', 'arc' ),
			'desc' => __('Choose if you want to display the category description at the top or bottom of the page.', 'arc'),
			'type' => 'radio',
			'default' => 'top',
			'items' => [
				'top' => __('Top', 'arc'),
				'bottom' => __('Bottom', 'arc')
			],
			'options' => ['in_line' => true,]
		]); //radio
	$xbox->close_tab_item('categories');
	/*** end categories***/

	/***tags***/
	$xbox->open_tab_item('tags');
		$xbox->add_field([
			'id' => 'tag-desc-position',
			'name' => __( 'Tag description position', 'arc' ),
			'desc' => __('Choose if you want to display the tag description at the top or bottom of the page.', 'arc'),
			'type' => 'radio',
			'default' => 'top',
			'items' => [
				'top' => __('Top', 'arc'),
				'bottom' => __('Bottom', 'arc')
			],
			'options' => ['in_line' => true,]
		]); //radio
		$xbox->add_field([
			'id' => 'tag_letter_case',
			'name' => __( 'Letter case', 'arc' ),
			'desc' => __('Choose which letter case variation should be used to format the tags.', 'arc'),
			'type' => 'radio',
			'default' => 'default',
			'items' => [
				'lower' => __('All lowercase', 'arc'),
				'upper' => __('All uppercase', 'arc'),
				'first_upper' => __('First letter uppercase', 'arc'),
				'default' => __('Allow all cases', 'arc')
			],
			'options' => ['in_line' => true,]
		]); //radio
			$xbox->add_field([
				'id' => 'first_letter',
				'name' => __( 'First letter uppercase after space or symbol', 'arc' ),
				'type' => 'radio',
				'default' => 'no',
				'items' => [
					'no' => __('No', 'arc'),
					'after_space' => __('After space', 'arc'),
					'all' => __('All', 'arc'),
				],
				'options' => ['in_line' => true,
				              'show_if' => ['tag_letter_case', '=', 'first_upper']
				]
			]); //radio

		$xbox->add_field([
			'id' => 'tag_spacing',
			'name' => __( 'Tag spacing', 'arc' ),
			'desc' => __('Choose whether you want tags to contain whitespaces.', 'arc'),
			'type' => 'radio',
			'default' => 'allow_spaces',
			'items' => [
				'replace_spaces' => __('Replace spaces', 'arc'),
				'remove_spaces' => __('Remove spaces', 'arc'),
				'allow_spaces' => __('Allow spaces', 'arc'),
			],
			'options' => ['in_line' => true,]
		]); //radio

		$xbox->add_field([
			'id' => 'replacement_symbol',
			'name' => __( 'Set a replacement symbol', 'arc' ),
			'desc' => __('Set a replacement symbol for whitespaces.', 'arc'),
			'type' => 'text',
			'grid' => '4-of-8',
			'default' => '/',
			'options' => [
				'show_if' => ['tag_spacing', '=', 'replace_spaces']
			]
		]); //radio

		$xbox->add_field([
			'id' => 'tag_symbols',
			'name' => __( 'Tag symbols', 'arc' ),
			'desc' => __('Choose whether you want tags to contain symbols.', 'arc'),
			'type' => 'radio',
			'default' => 'allow_symbols',
			'items' => [
				'replace_symbols' => __('Replace symbols', 'arc'),
				'remove_symbols' => __('Remove symbols', 'arc'),
				'allow_symbols' => __('Allow symbols', 'arc'),
			],
			'options' => ['in_line' => true,]
		]); //radio
		$xbox->add_field([
			'id' => 'symbol',
			'name' => __( 'Set a replacement symbol', 'arc' ),
			'desc' => __('Set a replacement symbol for symbols.', 'arc'),
			'type' => 'text',
			'grid' => '4-of-8',
			'default' => '/',
			'options' => [
				'show_if' => ['tag_symbols', '=', 'replace_symbols']
			]
		]); //radio

		$xbox->add_field([
			'id' => 'ignore_title',
			'name' => __( 'Ignore character sets', 'arc' ),
			'type' => 'title',
		]);

		$xbox->add_field([
			'id' => 'ignore_arabic',
			'name' => __( 'Arabic', 'arc' ),
			'desc' => 'Leaving this option disabled allows for Arabic characters to be treated as symbols.',
			'type' => 'switcher',
			'default' => 'on'
		]);
		$xbox->add_field([
			'id' => 'ignore_cyrillic',
			'name' => __( 'Cyrillic', 'arc' ),
			'desc' => 'Leaving this option disabled allows for Cyrillic characters to be treated as symbols.',
			'type' => 'switcher',
			'default' => 'on'
		]);
		$xbox->add_field([
			'id' => 'ignore_chinese',
			'name' => __( 'Chinese', 'arc' ),
			'desc' => 'Leaving this option disabled allows for Chinese characters to be treated as symbols.',
			'type' => 'switcher',
			'default' => 'on'
		]);

	$xbox->add_field([
			'id' => 'treat_dashes',
			'name' => __( 'Treat dashes (-) as spaces', 'arc' ),
			'desc' => 'Enabling this causes the dashes to be affected by the Tag spacing option.',
			'type' => 'switcher',
			'default' => 'off'
		]); //switcher
	$xbox->close_tab_item('tags');
	/*** end tags***/

	/***actors***/
	$xbox->open_tab_item('actors');
		$xbox->add_field([
			'id' => 'number-actors-per-page',
			'name' => __( 'Number of pornstars per page', 'arc' ),
			'type' => 'number',
			'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
			'default' => 2,
			'grid' => '4-of-8',
			'options' => [
				'unit' => 'pornstars per page',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false
			],
		]);
		$xbox->add_field([
			'id' => 'number-actor-per-row',
			'name' => __( 'Number of pornstars per row', 'arc' ),
			'type' => 'number',
			'desc' => '<p>min: 2, max: 5</p><img src="' . get_template_directory_uri() . '/assets/img/videos-per-row.jpg' . '" />',
			'default' => 5,
			'grid' => '5-of-8',
			'options' => [
				'unit' => 'pornstars/row',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false
			],
			'attributes' => array(
				'min' => 2,
				'max' => 5,
				'step' => 1,
				'precision' => 0,
			)
		]);
		$xbox->add_field([
			'id' => 'actors-thumb-quality',
			'name' => __( 'Pornstar thumbnail quality', 'arc' ),
			'desc' => __('Basic = High compression, Normal = Medium compression, Fine = Low compression', 'arc'),
			'type' => 'radio',
			'default' => 'full',
			'items' => [
				'small' => __('Basic', 'arc'),
				'medium' => __('Normal', 'arc'),
				'full' => __('Fine', 'arc')
			],
			'options' => ['in_line' => true,]
		]);
		$xbox->add_field([
			'id' => 'number-video-per-actors-page',
			'name' => __( 'Number of videos per page', 'arc' ),
			'type' => 'number',
			'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
			'default' => 20,
			'grid' => '5-of-8',
			'options' => [
				'unit' => 'videos/page',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false
			],
            'attributes' => array(
                'min' => 1,
                'step' => 1,
                'precision' => 0,
            )
		]);

		$xbox->add_field([
			'id' => 'display_pornstars_views',
			'name' => __( 'Display pornstar views', 'arc' ),
			'desc' => 'Display view count under pornstar thumbnails.',
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher

		$xbox->add_field([
			'id' => 'actors-desc-position',
			'name' => __( 'Pornstar description position', 'arc' ),
			'desc' => __('Choose if you want to display the pornstar description at the top or bottom of the page.', 'arc'),
			'type' => 'radio',
			'default' => 'top',
			'items' => [
				'top' => __('Top', 'arc'),
				'bottom' => __('Bottom', 'arc')
			],
			'options' => ['in_line' => true,]
		]);
	$xbox->close_tab_item('actors');
	/*** end actors***/

	/***photos***/
	$xbox->open_tab_item('photos');
		$xbox->add_field([
			'id' => 'number_albums_per_page',
			'name' => __( 'Number of albums per page', 'arc' ),
			'type' => 'number',
			'default' => 12,
			'grid' => '4-of-8',
			'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
			'options' => [
				'unit' => 'albums/page',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
            'attributes' => array(
                'min' => 1,
                'step' => 1,
                'precision' => 0,
            )
		]); //number
		$xbox->add_field([
			'id' => 'number_albums_per_row',
			'name' => __( 'Number of albums per row', 'arc' ),
			'type' => 'number',
			'default' => 3,
			'grid' => '4-of-8',
			'desc' => '<p>min: 2, max: 5</p><img src="' . get_template_directory_uri() . '/assets/img/videos-per-row.jpg' . '" />',
			'options' => [
				'unit' => 'albums/row',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
			'attributes' => array(
				'min' => 2,
				'max' => 5,
				'step' => 1,
				'precision' => 0,
			)
		]); //number
		$xbox->add_field([
			'id' => 'album_thumb_quality',
			'name' => __( 'Album thumbnail quality', 'arc' ),
			'type' => 'radio',
			'desc' => __('Basic = High compression, Normal = Medium compression, Fine = Low compression', 'arc'),
			'default' => 'full',
			'items' => [
				'small' => __('Basic', 'arc'),
				'medium' => __('Normal', 'arc'),
				'full' => __('Fine', 'arc')
			],
			'options' => [
				'in_line' => true,
			]
		]); //radio

		$xbox->add_field([
			'id' => 'number_images_per_page',
			'name' => __( 'Number of images per page', 'arc' ),
			'type' => 'number',
			'default' => 10,
			'grid' => '4-of-8',
			'desc' => '<img src="' . get_template_directory_uri() . '/assets/img/videos-per-page.jpg' . '" />',
			'options' => [
				'unit' => 'images/page',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
            'attributes' => array(
                'min' => 1,
                'step' => 1,
                'precision' => 0,
            )
		]); //number
		$xbox->add_field([
			'id' => 'number_photos_per_row',
			'name' => __( 'Number of images per row', 'arc' ),
			'type' => 'number',
			'default' => 6,
			'grid' => '4-of-8',
			'desc' => '<p>min: 2, max: 6</p><img src="' . get_template_directory_uri() . '/assets/img/videos-per-row.jpg' . '" />',
			'options' => [
				'unit' => 'photos/row',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
			'attributes' => array(
				'min' => 2,
				'max' => 6,
				'step' => 1,
				'precision' => 0,
			)
		]); //number
		$xbox->add_field([
			'id' => 'title_desc_photos_pos',
			'name' => __( 'Description position', 'arc' ),
			'desc' => __('Choose if you want to display the description at the top or bottom of the Photos & GIFs page.', 'arc'),
			'type' => 'radio',
			'default' => 'top',
			'items' => [
				'top' => __('Top', 'arc'),
				'bottom' => __('Bottom', 'arc')
			],
			'options' => ['in_line' => true,]
		]);
		$xbox->add_field([
			'id' => 'slideshow_duration',
			'name' => __( 'Slideshow duration', 'arc' ),
			'desc' => __( 'Default: 5 sec; Min: 2 sec; Max: 50 sec', 'arc' ),
			'type' => 'number',
			'default' => 5,
			'grid' => '4-of-8',
			'options' => [
				'unit' => 'seconds',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
			'attributes' => array(
				'min' => 2,
				'max' => 60,
				'step' => 1,
				'precision' => 0,
			)
		]); //number
	$xbox->close_tab_item('photos');
	/*** end photos***/

		/*****sidebar****/
		$xbox->open_tab_item('sidebar');
			$xbox->add_field([
				'id' => 'sidebar-settings',
				'name' => __( 'Sidebar position', 'arc' ),
				'type' => 'image_selector',
				'default' => 'right',
				'items' => [
					'left' => get_template_directory_uri().'/assets/img/sidebar-left.jpg',
					'right' => get_template_directory_uri().'/assets/img/sidebar-right.jpg'
				],
				'items_desc' => [
					'left' => __('Left', 'arc'),
					'right' => __('Right', 'arc')
				],
			]); //textarea
			$xbox->add_field([
				'id' => 'show-sidebar-other-pages',
				'name' => __( 'Display the sidebar on new pages', 'arc' ),
				'desc' => __('All new pages that didn\'t come with the theme.'),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-submit-page',
				'name' => __( 'Display sidebar on the Video Upload page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-submit-photo-page',
				'name' => __( 'Display sidebar on the New Album page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-author-page',
				'name' => __( 'Display sidebar on the My Uploads page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher

			$xbox->add_field([
				'id' => 'show-sidebar-content',
				'name' => __( 'Display sidebar on the Homepage', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-search-page',
				'name' => __( 'Display the sidebar next to search results', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-video-post',
				'name' => __( 'Display the sidebar on video pages', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-category',
				'name' => __( 'Display sidebar on category pages', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-in-content',
				'name' => __( 'Display sidebar on the Categories page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-actors',
				'name' => __( 'Display sidebar on pornstar pages', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-in-actors',
				'name' => __( 'Display sidebar on the Pornstars page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-tags',
				'name' => __( 'Display sidebar on tag pages', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-in-tags',
				'name' => __( 'Display sidebar on the Tags page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-blog',
				'name' => __( 'Display the sidebar next to articles', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-in-blog',
				'name' => __( 'Display sidebar on the blog', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-photos',
				'name' => __( 'Display the sidebar next to albums', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-in-photos',
				'name' => __( 'Display sidebar on the Photos & GIFs page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-playlist',
				'name' => __( 'Display the sidebar next to playlists', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-favorite-photos',
				'name' => __( 'Display sidebar on the My Favorites page', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-in-playlist',
				'name' => __( 'Display sidebar on the My Playlists page', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-watchlist',
				'name' => __( 'Display sidebar on the Watched Videos page', 'arc' ),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'show-sidebar-on-public-profile-page',
				'name' => __( 'Display the sidebar on public profiles', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
		$xbox->close_tab_item('sidebar');
		/*****end sidebar****/

		/*****footer****/
		$xbox->open_tab_item('footer');
			$xbox->add_field([
				'id' => 'show-sidebar-footer',
				'name' => __( 'Enable ad blocks', 'arc' ),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'footer-columns',
				'name' => __( 'Number of ad blocks displayed', 'arc' ),
				'desc' => __('<a href="'. admin_url() .'widgets.php">Click here</a> to manage your footer widgets.', 'arc'),
				'type' => 'image_selector',
				'default' => 'two-columns-footer',
				'items' => [
					'one-columns-footer' => get_template_directory_uri().'/assets/img/footer-1-column.jpg',
					'two-columns-footer' => get_template_directory_uri().'/assets/img/footer-2-columns.jpg',
					'three-columns-footer' => get_template_directory_uri().'/assets/img/footer-3-columns.jpg',
					'four-columns-footer' => get_template_directory_uri().'/assets/img/footer-4-columns.jpg'
				],
				'items_desc' => [
					'one-columns-footer' => __('1 block' , 'arc'),
					'two-columns-footer' => __('2 blocks' , 'arc'),
					'three-columns-footer' => __('3 blocks' , 'arc'),
					'four-columns-footer' => __('4 blocks' , 'arc'),
				],
				'options' => [
					'width' => '160px',//Default: 100px
					'height' => 'auto',//Default: auto
					'active_class' => 'xbox-active',//Default: xbox-active
					'active_color' => '#379FE7',//Default: #379FE7
					'in_line' => true,//Default: true
				],
			]); //image selector
			$xbox->add_field([
				'id' => 'footer-logo',
				'name' => __( 'Display logo', 'arc' ),
				'desc' => __('Enable to display the site\'s logo in the footer.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]); //switcher
			$xbox->add_field([
				'id' => 'display_trending_tags',
				'name' => __( 'Display trending tags', 'arc' ),
				'desc' => __('Enable to display trending tags in the footer.', 'arc'),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher

			$xbox->add_field([
				'id' => 'display_trending_tags_by_country',
				'name' => __( 'Trending tags by country', 'arc' ),
				'desc' => __('Add a drop-down that will allow for trending tags to be viewed by country.', 'arc'),
				'type' => 'switcher',
				'default' => 'on',
				'options' => [
					'show_if' => ['display_trending_tags', '=', 'on']
				]
			]); //switcher

		$xbox->close_tab_item('footer');
		/*****end footer****/

		/*****membership****/
		$xbox->open_tab_item('membership');
			/*$xbox->add_field([
				'id' => 'enable-membership',
				'name' => __( 'Enable membership', 'arc' ),
				'desc' => __('Enable the membership system, with the login/register features, user profiles, video submission, etc.', 'arc'),
				'type' => 'switcher',
				'default' => 'on'
			]);*/ //switcher
			$xbox->add_field([
				'id' => 'video-submit-settings',
				'name' => __( 'Video Upload', 'arc' ),
				'type' => 'title',
			]); //switcher
				$xbox->add_field([
					'id' => 'title-required',
					'name' => __( 'Title required', 'arc' ),
					'type' => 'switcher',
					'default' => 'on',
				]); //switcher
				$xbox->add_field([
					'id' => 'desc-required',
					'name' => __( 'Description required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
				]); //switcher
				$xbox->add_field([
					'id' => 'video-required',
					'name' => __( 'Video source required', 'arc' ),
					'desc' => 'One source field (URL, iframe, or file) needs to be filled in before submitting the form.',
					'type' => 'switcher',
					'default' => 'on',
				]); //switcher

				$xbox->add_field([
					'id' => 'orientation-required',
					'name' => __( 'Orientation required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
				]); //switcher

				$xbox->add_field([
					'id' => 'category-required',
					'name' => __( 'Category required', 'arc' ),
					'type' => 'switcher',
					'default' => 'on',
				]); //switcher

				$xbox->add_field([
					'id' => 'thumb-required',
					'name' => __( 'Thumbnail URL required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
				]); //switcher
				$xbox->add_field([
					'id' => 'tags-required',
					'name' => __( 'Tags required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
				]); //switcher
				$xbox->add_field([
					'id' => 'actors-required',
					'name' => __( 'Pornstars required', 'arc' ),
					'type' => 'switcher',
					'default' => 'off',
				]); //switcher

			$xbox->add_field([
				'id' => 'enable-recaptcha',
				'name' => __( 'Enable reCAPTCHA', 'arc' ),
				'desc' => __('Enable Google reCAPTCHA security on the following pages: Register, Video Upload, New Album, Contact. <br> Get your reCAPTCHA keys <a href="https://www.google.com/recaptcha/admin">here</a>.', 'arc'),
				'type' => 'switcher',
				'default' => 'off'
			]); //switcher
			$xbox->add_field([
				'id' => 'reCaptcha-settings1',
				'name' => __( 'reCAPTCHA — site key', 'arc' ),
				'type' => 'text',
				'default' => __('Site key', 'arc'),
				'options' => [
					'show_if' => ['enable-recaptcha', '=', 'on']
				]
			]); //text
			$xbox->add_field([
				'id' => 'reCaptcha-settings2',
				'name' => __( 'reCAPTCHA — secret key', 'arc' ),
				'type' => 'text',
				'default' => __('Secret key', 'arc'),
				'options' => [
					'show_if' => ['enable-recaptcha', '=', 'on']
				]
			]); //text
		$xbox->close_tab_item('membership');
		/*****end membership****/

		/*****tools****/
		$xbox->open_tab_item('tools');
			$xbox->add_field([
				'id' => 'pages',
				'name' => __( 'PAGES', 'arc' ),
				'type' => 'title'
			]); //pages
				$xbox->add_field([
					'id' => 'create-categories-page',
					'name' => __( 'Categories', 'arc' ),
					'type' => 'button',
					'value' => 'Add Categories',
					'desc' => __('The Categories page will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add Categories'
					],
					'content' => 'Add Categories'
				]);
				$xbox->add_field([
					'id' => 'create-actors-page',
					'name' => __( 'Pornstars', 'arc' ),
					'type' => 'button',
					'value' => 'Add Pornstars',
					'desc' => __('The Pornstars page will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add Pornstars'
					],
					'content' => 'Add Pornstars'
				]);
				$xbox->add_field([
					'id' => 'create-tags-page',
					'name' => __( 'Tags', 'arc' ),
					'type' => 'button',
					'value' => 'Add Tags',
					'desc' => __('The Tags page will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add Tags'
					],
					'content' => 'Add Tags'
				]);
				$xbox->add_field([
					'id' => 'create-videos-page',
					'name' => __( 'Video Upload', 'arc' ),
					'type' => 'button',
					'value' => 'Add Video Upload',
					'desc' => __('The Video Upload page will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add Video Upload'
					],
					'content' => 'Add Video Upload'
				]);
				$xbox->add_field([
					'id' => 'create-blog-page',
					'name' => __( 'Blog', 'arc' ),
					'type' => 'button',
					'value' => 'Add Blog',
					'desc' => __('The Blog page will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add Blog'
					],
					'content' => 'Add Blog'
				]);
				$xbox->add_field([
					'id' => 'create-profile-page',
					'name' => __( 'My Uploads', 'arc' ),
					'type' => 'button',
					'value' => 'Add My Uploads',
					'desc' => __('My Uploads page will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add My Uploads'
					],
					'content' => 'Add My Uploads'
				]);
			$xbox->add_field([
				'id' => 'menu',
				'name' => __( 'MENUS', 'arc' ),
				'type' => 'title'
			]); //menu
				$xbox->add_field([
					'id' => 'create-menu',
					'name' => __( 'Menus', 'arc' ),
					'type' => 'button',
					'value' => 'Add menus',
					'desc' => __('PornX menus will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add menus'
					],
					'content' => 'Add menus'
				]);
			$xbox->add_field([
				'id' => 'widgets',
				'name' => __( 'WIDGETS', 'arc' ),
				'type' => 'title'
			]); //widgets
				$xbox->add_field([
					'id' => 'create-widgets',
					'name' => __( 'Widgets', 'arc' ),
					'type' => 'button',
					'value' => 'Add widgets',
					'desc' => __('PornX widgets will be re-created.', 'arc'),
					'options' => [
						'tag' => 'button'
					],
					'attributes' => [
						'value' => 'Add widgets'
					],
					'content' => 'Add widgets'
				]);
		$xbox->close_tab_item('tools');
		/*****end tools****/


		/*****mobile****/
		$xbox->open_tab_item( 'mobile');
				$xbox->add_field([
					'id' => 'mob-number_videos_per_page',
					'name' => __( 'Number of videos per page', 'arc' ),
					'type' => 'number',
					'default' => 20,
					'grid' => '4-of-8',
					'desc' => 'This option only affects the All Videos section on the homepage.<br> <img src="' . get_template_directory_uri() . '/assets/img/videos-per-page-mobile.jpg' . '" />',
					'options' => [
						'unit' => 'videos/page',
						'show_unit' => true,
						'show_spinner' => true,
						'disable_spinner' => false,
					],
				]); //number
				$xbox->add_field([
					'id' => 'mob-number_videos_per_row',
					'name' => __( 'Number of videos per row', 'arc' ),
					'type' => 'radio',
					'default' => '2',
					'grid' => '4-of-8',
					'desc' => 'The entire website is affected by this option. <br><img src="' . get_template_directory_uri() . '/assets/img/videos-per-row-mobile.jpg' . '" />',
					'items' => [
						'1' => 'One video',
						'2' => 'Two videos'
					],
					'options' => [
						'in_line' => true,
					]
				]); //number
				$xbox->add_field([
					'id' => 'mob-show-sidebar',
					'name' => __( 'Display sidebar', 'arc' ),
					'desc' => __('Display the sidebar on mobile devices.', 'arc'),
					'type' => 'switcher',
					'default' => 'off'
				]); //switcher
				$xbox->add_field([
					'id' => 'mob-homepage-widgets',
					'name' => __( 'Hide the sidebar from the homepage', 'arc' ),
					'desc' => __('Skip displaying the sidebar on the homepage.', 'arc'),
					'type' => 'switcher',
					'default' => 'off'
				]); //switcher
		$xbox->close_tab_item( 'mobile' );
		/*****mobile****/



	/****email****/
	$xbox->open_tab_item('email');
		$group = $xbox->add_group( array(
			'name' => 'Backup admin emails',
			'id' => 'additional_emails',
			'options' => array(
				'add_item_text' => __('Add another email', 'arc'),
			),
			'controls' => array(
				'name' => 'Email #'
			)
		));
		$group->add_field(array(
			'id' => 'email-name',
			'name' => __( 'Email', 'arc' ),
			'type' => 'text',
			'grid' => '4-of-8',
		));

		$xbox->add_field(array(
			'id' => 'support-email',
			'name' => __('Support email', 'arc'),
			'desc' => 'This email will be displayed by the [support_email] shortcode in email templates.',
			'type' => 'text',
			'grid' => '4-of-8',
		));
	$xbox->close_tab_item('email');
	/*****email****/


	/****community****/
	$xbox->open_tab_item('community');
		$xbox->add_field([
			'id' => 'post_character',
			'name' => __( 'Post character limit', 'arc' ),
			'type' => 'number',
			'default' => 100,
			'grid' => '4-of-8',
			'desc' => 'Set the character limit for Community Feed posts.',
			'options' => [
				'unit' => 'characters',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
			'attributes' => array(
				'min' => 1,
				'step' => 1,
				'precision' => 0,
			)
		]); //number
		$xbox->add_field([
			'id' => 'user_post_interval',
			'name' => __( 'Post interval', 'arc' ),
			'type' => 'number',
			'default' => 0,
			'grid' => '4-of-8',
			'desc' => 'The amount of seconds a user has to wait before they can submit another post to feed. (e.g., 900 is 15 minutes).',
			'options' => [
				'unit' => 'seconds',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
			'attributes' => array(
				'min' => 0,
				'step' => 1,
				'precision' => 0,
			)
		]); //number

		$xbox->add_field([
			'id' => 'display_recent_activity',
			'name' => __( 'Display recent activity in the feed', 'arc' ),
			'desc' => __('This option will display recently uploaded videos and albums in the Community Feed.', 'arc'),
			'type' => 'switcher',
			'default' => 'off'
		]); //switcher
		$xbox->add_field([
			'id' => 'display_activity_sidebar',
			'name' => __( 'Enable the Recent activity sidebar', 'arc' ),
			'desc' => __('Display the Recent activity sidebar right of the feed with recently uploaded videos and albums.', 'arc'),
			'type' => 'switcher',
			'default' => 'on'
		]); //switcher


		$xbox->add_field([
			'id' => 'number_of_recent_uploads',
			'name' => __( 'Number of recent uploads shown', 'arc' ),
			'type' => 'number',
			'default' => 5,
			'grid' => '4-of-8',
			'desc' => 'Set how many recent uploads you want to be shown in the Recent activity sidebar. Min: 5; Max: 15.',
			'options' => [
				'unit' => 'uploads',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
			'attributes' => array(
				'min' => 5,
				'max' => 15,
				'step' => 1,
				'precision' => 0,
			)
		]); //number

		$xbox->add_field([
			'id' => 'uploaded_images_shown',
			'name' => __( 'Image thumbnails per album', 'arc' ),
			'type' => 'number',
			'default' => 3,
			'grid' => '4-of-8',
			'desc' => 'Set how many image thumbnails you want to be shown per album in the Recent activity sidebar. Min: 1; Max: 5.',
			'options' => [
				'unit' => 'thumbnails',
				'show_unit' => true,
				'show_spinner' => true,
				'disable_spinner' => false,
			],
			'attributes' => array(
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'precision' => 0,
			)
		]); //number

	$xbox->close_tab_item('community');
	/*****community****/

	/*$xbox->open_tab_item('export');
		$xbox->add_export_field(array(
			'name' => 'Export Settings',
			'desc' => 'Download and make a backup of your options.',
			'options' => array(
				'export_button_text'  => __( 'Download', 'xbox' ),
				'export_file_name' => 'xbox-backup-file',//Name of the json file for backup.
			)
		));
		$xbox->add_import_field(array(
			'name' => 'Import Settings',
			'default' => 'from_file',
			'desc' => 'Select file, then click import button',
		));
	$xbox->close_tab_item('export');*/

	$xbox->close_tab('main-tab');
}