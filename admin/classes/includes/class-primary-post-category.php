<?php
/**
 * Primary Post Category.
 *
 * Main plugin class file.
 *
 * @package simple-primary-category
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Plugin Class.
 */
final class Primary_Post_Category {

	/**
	 * PPC Version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Single Plugin Instance.
	 *
	 * @var Primary_Post_Category
	 */
	protected static $instance = null;

	/**
	 * Returns PPC Instance.
	 *
	 * Ensures only one instance of the plugin is loaded or can be loaded.
	 *
	 * @return Primary_Post_Category
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Contructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
	}

	/**
	 * Define constants.
	 */
	public function define_constants() {
		$this->define( 'PPC_VERSION', $this->version );
		$this->define( 'PPC_BASE_NAME', plugin_basename( PPC_PLUGIN_FILE ) );
		$this->define( 'PPC_BASE_URL', trailingslashit( plugin_dir_url( PPC_PLUGIN_FILE ) ) );
		$this->define( 'PPC_BASE_DIR', trailingslashit( plugin_dir_path( PPC_PLUGIN_FILE ) ) );
	}

	/**
	 * Define constant if not defined already.
	 *
	 * @param string $name  - Constant name.
	 * @param string $value - Constant value.
	 */
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Include plugin files.
	 */
	public function includes() {
		require_once dirname( __FILE__ ) . '/class-ppc-primary-term.php';
		require_once dirname( __FILE__ ) . '/class-ppc-primary-term-query.php';
		require_once dirname( __FILE__ ) . '/ppc-functions.php';

		if ( is_admin() ) {
			require_once dirname( __FILE__ ) . '/admin/class-ppc-admin.php';
		}
	}

	/**
	 * Error Logger
	 *
	 * Logs given input into debug.log file in debug mode.
	 *
	 * @param mixed $message - Error message.
	 */
	public function error_log( $message ) {
		if ( WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}
