<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

define( 'VICETEMPLECORE_DEBUG', false );
define( 'VICETEMPLECORE_VERSION', '1.0.0' );
define( 'VICETEMPLECORE_DIR', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
define( 'VICETEMPLECORE_URL', plugin_dir_url( __FILE__ ) );
define( 'VICETEMPLECORE_FILE', __FILE__ );
define( 'VICETEMPLECORE_NAME', 'Vicetemple theme' );
define( 'VICETEMPLECORE_API', 'https://vicetemple-api.com/products/');
define( 'VICETEMPLECORE_WEB_SERVICE', 'https://vicetemple-api.com/');
define( 'VICETEMPLECORE_UPLOAD', '/plugins/dev-core-plugin/uploads/');
define( 'VICETEMPLECORE_DOWNLOAD', plugins_url(). '/dev-core-plugin/uploads/download_video.php');
define( 'VICETEMPLECORE_PRODUCT', 'Dev Core Plugin');
define( 'VICETEMPLECORE_LIC_URL', 'https://lm.vicetemple-api.com/');
define( 'VICETEMPLECORE_LOGS', 'changelogs/');