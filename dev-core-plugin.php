<?php
/**
 * Plugin Name: Dev Core Plugin
 * Description: A plugin that keeps your copy of PornX functional.
 * Version: 1.2
 * Text Domain: dev-core-plugin
 * Author: Citadel Solutions B.V.
 * Author URI: https://vicetemple.com/
 * Domain Path: /languages
 * Requires at least: 5.4
 * Requires PHP: 7.0.0
 */
error_reporting(0);
require_once 'config.php';
require_once 'xbox/xbox.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
final class VICETEMPLECORE {
	private static $instance;
	private static $config;

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'arc' ), '1.0.0' );
	}

	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'arc' ), '1.0.0' );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof VICETEMPLECORE ) ) {
			self::$instance = new VICETEMPLECORE();
			/***here require files from admin folder****/
			require_once 'admin/hooks.php';
			require_once 'admin/classes/class-installer.php';
			require_once 'admin/classes/primary-post-category.php';
			require_once 'admin/my-register/my-register.php';
			require_once 'admin/custom-urls/custom-urls.php';
			require_once 'admin/pages/page-dashboard.php';
			require_once 'admin/pages/admin-page.php';
			require_once 'admin/pages/faq-edit-page.php';
			require_once 'admin/pages/admin-partner-column.php';
			require_once 'admin/actions/ajax-load-data-about-plugin.php';
			require_once 'admin/actions/ajax-install-product.php';
			require_once 'admin/actions/ajax-create-full-site.php';
			require_once 'admin/actions/ajax-convert-video.php';
			require_once 'admin/actions/ajax-check-license.php';
			require_once 'admin/actions/ajax-save-faqs-questions.php';
			require_once 'admin/actions/ajax-discussion.php';

			if(!is_admin()) {
				self::$instance->load_public_hooks();
			}
			if ( is_admin() ) {
				self::$instance->auto_load_php_files('public');
				self::$instance->load_hooks();
			}
		}
		return self::$instance;
	}

	public function auto_load_php_files( $dir ) {
		$dirs = (array) ( plugin_dir_path( __FILE__ ) . $dir . '/' );
		foreach ( (array) $dirs as $dir ) {
			$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir ) );
			if ( ! empty( $files ) ) {
				foreach ( $files as $file ) {

					if ( $file->isDir() ) {
						continue; }

					if ( $file->getPathname() === 'index.php' ) {
						continue; }

					if ( substr( $file->getPathname(), -4 ) !== '.php' ) {
						continue; }

					if ( substr( $file->getPath(), -2 ) === '-x' ) {
						continue; }
					if ( substr( $file->getPathname(), -6 ) === '-x.php' ) {
						continue; }

					require $file->getPathname();
				}
			}
		}
	}

	public function load_public_hooks() {
		add_action( 'wp_footer', array( $this,'register_niches_style'));
	}

	public function register_niches_style(){
        if(is_single()) {
            wp_enqueue_script('arc-download-script', plugin_dir_url(__FILE__) . 'public/assets/download-video.js', ['jquery'], '', true);
            wp_localize_script('arc-download-script', 'arc_download', [
                'plUrl' => VICETEMPLECORE_DOWNLOAD
            ]);
        }


		if(is_single() && !is_singular('blog') && !is_singular('photos')) {
			wp_enqueue_style( 'arc-public-style', plugin_dir_url( __FILE__ ) . 'public/assets/public-styles.css', [], '', 'all' );
			wp_enqueue_script('arc-public-video-script', plugin_dir_url(__FILE__) . 'public/assets/public_video_script.js', ['jquery'], '', true);
		}
		if(is_user_logged_in() && !is_author()) {
			wp_enqueue_style( 'arc-public-style', plugin_dir_url( __FILE__ ) . 'public/assets/public-styles.css', [], '', 'all' );
			wp_enqueue_script('arc-public-script', plugin_dir_url(__FILE__) . 'public/assets/public-scripts.js', ['jquery'], '', true);
		}
		if(is_author()) {
			wp_enqueue_script('page-channel-script', plugin_dir_url(__FILE__) . 'public/assets/page-channel-script.js', ['jquery'], '', true);
		}
		if (get_theme_mod('popup_show') == 'on' && !is_customize_preview()) {
			if('main' == get_theme_mod('popup_page')) {
				if(is_front_page()) {
					wp_enqueue_script('custom-popup-script', plugin_dir_url(__FILE__) . 'public/assets/popup-script.js', ['jquery'], '', true);
				}
			}
			if('category' == get_theme_mod('popup_page')) {
				if(is_page_template('template-categories.php')) {
					wp_enqueue_script('custom-popup-script', plugin_dir_url(__FILE__) . 'public/assets/popup-script.js', ['jquery'], '', true);
				}
			}
			if('videos' == get_theme_mod('popup_page')) {
				if(is_single()) {
					wp_enqueue_script('custom-popup-script', plugin_dir_url(__FILE__) . 'public/assets/popup-script.js', ['jquery'], '', true);
				}
			}
			if('all' == get_theme_mod('popup_page')) {
				wp_enqueue_script('custom-popup-script', plugin_dir_url(__FILE__) . 'public/assets/popup-script.js', ['jquery'], '', true);
			}
		}
	}

	public function load_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_arc_load_scripts' ), 100, 1);
		add_action('admin_menu', array( $this,'arc_register_menus'));
		if(VICETEMPLECORE()->get_license_key() !== false) {
			if(is_plugin_active('vicetemple-player/vicetemple-player.php')) {
				add_action('admin_menu', array($this, 'Vicetemple_player_page'));
			}
			if(is_plugin_active('vicetemple-single-embedder/vicetemple-single-embedder.php')) {
				add_action('admin_menu', array($this, 'ASEV_single_import_videos_page'));
			}
			if(is_plugin_active('vicetemple-mass-grabber/vicetemple-mass-grabber.php')) {
				add_action('admin_menu', array($this, 'AMG_mass_import_videos_page'));
			}
			if(is_plugin_active('vicetemple-mass-embedder/vicetemple-mass-embedder.php')) {
				add_action('admin_menu', array($this, 'AMVE_mass_import_videos_page'));
			}
			if(is_plugin_active('vicetemple-delete-broken-videos/vicetemple-delete-broken-videos.php')) {
				add_action('admin_menu', array($this, 'ADBV_delete_broken_videos_page'));
			}
		}

		register_activation_hook( __FILE__, array( $this, 'activation' ) );

        add_action('admin_footer', function() {
            echo "<script>
                jQuery(document).ready(function(){
                    var core_title = jQuery('tr[data-slug=dev-core-plugin]').find('td.plugin-title strong').text();
                    jQuery('tr[data-slug=dev-core-plugin]').find('td.plugin-title strong').text(core_title.replace('Dev Core Plugin', 'PornX Core'));
                    
                    jQuery('select#plugin option').each(function() {
                        if(jQuery(this).val().indexOf('dev-core-plugin') > -1) {
                            jQuery(this).remove();
                        }
                    });
                });
                </script>";
        });
        add_action('admin_init', function () {
            global $wpdb;
            $table = $wpdb->prefix . "vicetempleCoreLogs";
            $wpdb->query("DELETE FROM $table WHERE `product` = 'Dev Core Plugin'" );
        });
	}
	public function admin_arc_load_scripts($hook) {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugins = get_plugins();
        $plugins_keys = array_keys($plugins);

        $i = 0;
        foreach ($plugins as $plugin) {
            $plugin_root_file   = $plugins_keys[$i];
            $plugin_title       = $plugin['Name'];
            $plugin_author       = $plugin['Author'];
            $plugin_status      = is_plugin_active($plugin_root_file) ? 'active' : 'inactive';
            if ($plugin_status == 'active' && ($plugin_author == 'Vicetemple' || $plugin_author == 'Citadel Solutions B.V.')) {
                $plugin['status'] = 'active';
                $plug_name[] = $plugin;
            }
            elseif($plugin_status == 'inactive' && ($plugin_author == 'Vicetemple' || $plugin_author == 'Citadel Solutions B.V.')) {
                $plugin['status'] = 'inactive';
                $plug_name[] = $plugin;
            }
            $i++;
        }

        $data_about_plugins = [
            'plugins' => $plug_name
        ];

        $currentTheme = wp_get_theme();
        $data_about_theme['theme'] = [
            'name' => $currentTheme->get('Name'),
            'description' => $currentTheme->get('Description'),
            'version' => $currentTheme->get('Version'),
            'status' => $currentTheme->get('Status'),
            'author' => $currentTheme->get('Author'),
            'author_uri' => $currentTheme->get('AuthorURI'),
            'requiresWP' => $currentTheme->get('RequiresWP'),
            'requiresPHP' => $currentTheme->get('RequiresPHP'),
        ];

        $infos_dt = [
            'plugins' => $plug_name,
            'theme' => [
                'name' => $currentTheme->get('Name'),
                'description' => $currentTheme->get('Description'),
                'version' => $currentTheme->get('Version'),
                'status' => $currentTheme->get('Status'),
                'author' => $currentTheme->get('Author'),
                'author_uri' => $currentTheme->get('AuthorURI'),
                'requiresWP' => $currentTheme->get('RequiresWP'),
                'requiresPHP' => $currentTheme->get('RequiresPHP'),
            ]
        ];
        file_put_contents(WP_CONTENT_DIR . '/plugins/dev-core-plugin/info.txt', serialize($infos_dt));

		if('toplevel_page_arc-dashboard' == $hook) {
			wp_enqueue_style('arc-bootstrap', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/bootstrap.min.css');
			wp_enqueue_script('arc-popper', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/popper.min.js', ['jquery'], '', false);
			wp_enqueue_script('arc-bootstrap-js', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/bootstrap.min.js', ['jquery'], '', false);
			wp_enqueue_style('arc-custom-style', plugin_dir_url(__FILE__) . 'admin/vendors/arc-custom-styles.css');
			wp_enqueue_style('arc-font-awesome', plugin_dir_url(__FILE__) . 'admin/vendors/font-awesome/css/all.css', '', '4.7');
			wp_enqueue_script('arc-font-awesome-script', plugin_dir_url(__FILE__) . 'admin/vendors/font-awesome/js/all.js', ['jquery'], '', false);
			wp_enqueue_script('arc-custom-script', plugin_dir_url(__FILE__) . 'admin/vendors/arc-custom-scripts.js', ['jquery'], '', false);
			wp_localize_script('arc-custom-script', 'arc_dashboard', [
				'success_btn' => __('Success', 'arc'),
				'error_btn' => __('Error', 'arc'),
				'url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ajax-nonce' ),
				'service' => VICETEMPLECORE_WEB_SERVICE,
				'source' => VICETEMPLECORE_API,
				'autoImport' => VICETEMPLECORE_WEB_SERVICE . 'autoimport/',
				'plUrl' => plugins_url(). '/dev-core-plugin/uploads/download_video.php',
				'userFalse' => __('You don`t have permission for use this key!', 'arc'),
				'licenseExist' => __('License key has been already activated!', 'arc'),
				'licenseOk' => __('License key activated', 'arc'),
				'activate' => __('Activate', 'arc'),
				'installingBegan' => __('Installing began...', 'arc'),
				'installingDone' => __('Installation done!', 'arc'),
				'upgradingBegan' => __('Updating', 'arc'),
				'upgradingDone' => __('Upgrade done!', 'arc'),
				'autoImportDone' => __('Auto import done!', 'arc'),
				'category' => __('Category', 'arc'),
				'createdCategory' => __('created. Videos importing...', 'arc'),
                'data_about_plugins' => $data_about_plugins,
                'data_about_theme' => $data_about_theme,
                'site_name' => $_SERVER['SERVER_NAME']
			]);
		}
		if('theme-dashboard_page_convert-videos' == $hook) {
			if (!did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}
			wp_enqueue_style('arc-bootstrap', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/bootstrap.min.css');
			wp_enqueue_script('arc-popper', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/popper.min.js', ['jquery'], '', false);
			wp_enqueue_script('arc-bootstrap-js', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/bootstrap.min.js', ['jquery'], '', false);
			wp_enqueue_style('arc-custom-style', plugin_dir_url(__FILE__) . 'admin/vendors/arc-custom-styles.css');
			wp_enqueue_style('arc-font-awesome', plugin_dir_url(__FILE__) . 'admin/vendors/font-awesome/css/all.css', '', '4.7');
			wp_enqueue_script('arc-font-awesome-script', plugin_dir_url(__FILE__) . 'admin/vendors/font-awesome/js/all.js', ['jquery'], '', false);
			wp_enqueue_script('arc-convert-videos', plugin_dir_url(__FILE__) . 'admin/vendors/arc-convert-videos.js', ['jquery'], '', false);
			wp_localize_script('arc-convert-videos', 'arc_convert', [
				'url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ajax-nonce' ),
				'service' => VICETEMPLECORE_WEB_SERVICE,
				'source' => VICETEMPLECORE_API,
				'autoImport' => VICETEMPLECORE_WEB_SERVICE . 'autoimport/',
				'postMaxSize' => ini_get('post_max_size'),
				'uploadMaxSize' => ini_get('upload_max_size'),
				'startConvert' => __('Video start converting. Wait some minutes. Do not leave the page!', 'arc'),
				'chooseFile' => __('Choose the file', 'arc'),
				'chooseResolution' => __('Choose the resolution', 'arc'),
				'chooseMp4' => __('Choose the mp4 file', 'arc'),
				'convertDone' => __('Convert done!', 'arc'),
			]);
		}
		if('toplevel_page_faq-edit' == $hook) {
			wp_enqueue_style('faq-bootstrap', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/bootstrap.min.css');
			wp_enqueue_script('faq-popper', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/popper.min.js', ['jquery'], '', false);
			wp_enqueue_script('faq-bootstrap-js', plugin_dir_url(__FILE__) . 'admin/vendors/bootstrap/bootstrap.min.js', ['jquery'], '', false);
			wp_localize_script('faq-bootstrap-js', 'faqs_obj', [
				'url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ajax-nonce' ),
			]);
		}
	}

	public function arc_register_menus() {
		add_menu_page(
			'FAQ',
			'FAQ',
			'manage_options',
			'faq-edit',
			'faq_edit_page',
			'dashicons-editor-help',
			'26');
		add_menu_page(
			'Theme Dashboard',
			'Theme Dashboard',
			'manage_options',
			'arc-dashboard',
			'arc_dashboard_page',
		'dashicons-admin-generic',
		'3');
		if(VICETEMPLECORE()->get_license_key() !== false) {
			add_submenu_page( 'arc-dashboard',
				'Vicetemple Theme Options',
				'Theme Options',
				'manage_options',
				'my-theme-options');

		add_submenu_page( 'arc-dashboard',
			'Email Settings',
			'Email Settings',
			'manage_options',
			'email-settings',
			'arc_email_settings');
		add_submenu_page( 'arc-dashboard',
			'Convert Videos',
			'Convert Videos',
			'manage_options',
			'convert-videos',
			'arc_convert_videos');
        }
	}
	public function Vicetemple_player_page() {
        if(VICETEMPLECORE()->get_license_key() !== false) {
            add_submenu_page('arc-dashboard',
                'Player Options',
                'Vicetemple Player',
                'manage_options',
                'vicetemplepl-options');
        }
	}
	public function ASEV_single_import_videos_page() {
        if(VICETEMPLECORE()->get_license_key() !== false) {
            add_submenu_page('arc-dashboard',
                'Import Tubes videos from their URL',
                'Single Embedder',
                'manage_options',
                'asev-options',
                'ASEV_import_single_setting');
        }
	}
	public function AMG_mass_import_videos_page() {
        if(VICETEMPLECORE()->get_license_key() !== false) {
            add_submenu_page('arc-dashboard',
                'Search videos to import',
                'Mass Grabber',
                'manage_options',
                'amvg-options',
                'AMG_import_single_setting');
        }
	}
	public function AMVE_mass_import_videos_page() {
        if(VICETEMPLECORE()->get_license_key() !== false) {
            add_submenu_page('arc-dashboard',
                'Search videos to import',
                'Mass Embedder',
                'manage_options',
                'amve-options',
                'AME_import_single_setting');
        }
	}
	public function ADBV_delete_broken_videos_page() {
        if(VICETEMPLECORE()->get_license_key() !== false) {
            add_submenu_page('arc-dashboard',
                'Search broken videos',
                'Find Broken Videos',
                'manage_options',
                'adbv-page',
                'ADBV_page_check_links');
        }
	}

	public static function activation() {
		global $wpdb;
		$table = $wpdb->prefix . "vicetempleCoreLogs";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
			$sql = "CREATE TABLE IF NOT EXISTS $table (
			  `id` INT NOT NULL AUTO_INCREMENT,
			  `date` VARCHAR(45) NULL,
			  `type` VARCHAR(45) NULL,
			  `product` VARCHAR(255) NULL,
			  `message` VARCHAR(255) NULL,
			  `location` VARCHAR(255) NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
			ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			$wpdb->query($sql);
		}
		$wpdb->insert($table, ["date" => date("Y-m-d H:i:s"),
		                       "type" => "success",
		                       "product" => VICETEMPLECORE_PRODUCT,
		                       "message" => "Success. Plugin activated.",
		                       "location" => "...." . explode('/plugins/', __FILE__)[1] .":". __LINE__ ],
			["%s", "%s", "%s", "%s", "%s"]);

		$supportTable = $wpdb->prefix . "supportMsg";
		if ($wpdb->get_var("SHOW TABLES LIKE '$supportTable'") != $supportTable) {
			$sql = "CREATE TABLE IF NOT EXISTS $supportTable (
				  `id` INT NOT NULL AUTO_INCREMENT,
				  `date` VARCHAR(255) NULL,
				  `title` VARCHAR(255) NULL,
				  `msg` LONGTEXT NULL,
				  `type` VARCHAR(255) NULL,
				  `name` VARCHAR(45) NULL,
				  `email` VARCHAR(45) NULL,
				  PRIMARY KEY (`id`))
				ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			$wpdb->query($sql);
		}

		$reportTable = $wpdb->prefix . "reportMsg";
		if ($wpdb->get_var("SHOW TABLES LIKE '$reportTable'") != $reportTable) {
			$sql = "CREATE TABLE IF NOT EXISTS $reportTable (
				  `id` INT NOT NULL AUTO_INCREMENT,
				  `date` VARCHAR(255) NULL,
				  `msg` LONGTEXT NULL,
				  `type` VARCHAR(255) NULL,
				  `postId` VARCHAR(25) NULL,
				  PRIMARY KEY (`id`))
				ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			$wpdb->query($sql);
		}
		$table_search = $wpdb->prefix . 'smart_search';
		if ( $wpdb->get_var("SHOW TABLE LIKE '".$table_search."'") != $table_search ) {
			$sql = "CREATE TABLE IF NOT EXISTS $table_search (
                `id` INT NOT NULL AUTO_INCREMENT,
                  `phrase` VARCHAR(255) NULL,
                  PRIMARY KEY (`id`))
                ENGINE = InnoDB DEFAULT CHARSET=utf8;";
			$wpdb->query($sql);
		}

		$table_ip_country_trend = $wpdb->prefix . 'ip_country_trend';
		if ($wpdb->get_var("SHOW TABLE LIKE '" . $table_ip_country_trend . "'") != $table_ip_country_trend) {
			$sql = "CREATE TABLE IF NOT EXISTS $table_ip_country_trend (
				  `id` INT NOT NULL AUTO_INCREMENT,
				  `country` VARCHAR(45) NULL,
				  `ip` VARCHAR(45) NULL,
				  `arr_tag` MEDIUMTEXT NULL,
				  PRIMARY KEY (`id`))
				ENGINE = InnoDB DEFAULT CHARSET=utf8;";
							$wpdb->query($sql);
		}

		$table_name_bitcion = $wpdb->prefix . 'vicetemple_payment_bitcoin';
		if ($wpdb->get_var("SHOW TABLE LIKE '" . $table_name_bitcion . "'") != $table_name_bitcion) {
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name_bitcion` (
				  `id` INT NOT NULL AUTO_INCREMENT,
				  `status` INT NULL,
				  `client_email` VARCHAR(255) NULL,
				  `time_start` INT NULL,
				  `period` VARCHAR(65) NULL,
				  `client_id` INT NULL,
				  `address` VARCHAR(255) NULL,
				  `time_end` INT NULL,
				  `cost` FLOAT NULL,
				  PRIMARY KEY (`id`))
				ENGINE = InnoDB;";
			$wpdb->query( $sql );
		}
	}

	private function load_textdomain() {
		$lang_dir = dirname( plugin_basename( VICETEMPLECORE_FILE ) ) . '/languages/';
		$mofile = sprintf( '%1$s-%2$s.mo', 'vicetemplecore_lang', get_locale() );
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/vicetemplecore_lang/' . $mofile;
		if ( file_exists( $mofile_global ) ) {
			load_textdomain( 'arc', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			load_textdomain( 'arc', $mofile_local );
		} else {
			load_plugin_textdomain( 'arc', false, $lang_dir );
		}
		return false;
	}

	public function get_wp_cats() {
		return (array) get_terms( 'category', array( 'hide_empty' => 0 ) );
	}

	public function get_license_key() {
		return get_option('_current_site_user_license');
	}

	public function update_license_key($new_option) {
		return update_option('_current_site_user_license', $new_option);
	}

	public function media_sideload_image( $file, $post_id, $desc = null, $source ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		if ( ! empty( $file ) ) {
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$tmp                = explode( '.', basename( $matches[0] ) );
			$file_ext           = end( $tmp );
			$file_array         = [];
			$file_array['name'] = sanitize_title( get_the_title( $post_id ) ) . '.' . $file_ext;
			unset( $tmp, $file_ext );
			$file_array['tmp_name'] = download_url( $file );
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}
			$id = media_handle_sideload( $file_array, $post_id, $desc );
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				return $id;
			}
			$src = wp_get_attachment_url( $id );
		}
		if ( ! empty( $src ) ) {
			$alt  = isset( $desc ) ? esc_attr( $desc ) : '';
			$html = "<img src='$src' alt='$alt' />";
			return $html;
		}
	}
}
function VICETEMPLECORE() {
	return VICETEMPLECORE::instance();
}
VICETEMPLECORE();
register_uninstall_hook( __FILE__, 'VICETEMPLECORE_uninstall');
function VICETEMPLECORE_uninstall() {
	global $wpdb;
	$table = $wpdb->prefix."vicetempleCoreLogs";
	$sql = "DROP TABLE `" . $table . "`;";
	$wpdb->query($sql);
	$support = $wpdb->prefix."supportMsg";
	$sql = "DROP TABLE `" . $support . "`;";
	$wpdb->query($sql);
	$smart_search = $wpdb->prefix."smart_search";
	$sql = "DROP TABLE `" . $smart_search . "`;";
	$wpdb->query($sql);
	$table_ip_country_trend = $wpdb->prefix . 'ip_country_trend';
	$sql = "DROP TABLE IF EXISTS $table_ip_country_trend;";
	$wpdb->query($sql);
	$bitcoin = $wpdb->prefix."vicetemple_payment_bitcoin";
	$sql = "DROP TABLE `" . $bitcoin . "`;";
	$wpdb->query($sql);
	delete_option('autoImportCategory');
	delete_option('autoImportPost');
	delete_option('autoimport');
	delete_option('vicetemple_update_theme');
	delete_option('vicetemple_update_plugin');
	delete_option('_current_site_user_license');
	delete_option('block_modal');
	delete_option('milf_logo');
	delete_option('college_logo');
	delete_option('hentai_logo');
	delete_option('livexcams_logo');
	delete_option('lesbian_logo');
	delete_option('trans_logo');
	delete_option('filf_logo');
	delete_option('milf_icon');
	delete_option('college_icon');
	delete_option('hentai_icon');
	delete_option('livexcams_icon');
	delete_option('lesbian_icon');
	delete_option('trans_icon');
	delete_option('filf_icon');
	delete_option('faqs_test');
	global $wp_rewrite;
	delete_option("custom_config");
	remove_action('generate_rewrite_rules', 'custom_generate_rewrite_rules');
	$wp_rewrite->flush_rules();
}

function pluggable_functions_email() {
	if (!function_exists('wp_password_change_notification')) {
		function wp_password_change_notification($user) {return; }
	}
}
pluggable_functions_email();