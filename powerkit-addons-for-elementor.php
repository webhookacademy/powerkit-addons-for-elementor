<?php
/**
 * Plugin Name:       PowerKit Addons For Elementor
 * Description:       A plugin that provides a collection of Elementor Templates (Pages, Sections, Block) created by the powerkit team
 * Version:           1.0.0
 * Author:            webhookacademy
 * Author URI:        https://webhookacademy.com/
 * plugin URI:        https://webhookacademy.com/plugin/powerkit-addons-for-elementor/
 * Text Domain:       powerkit-addons-for-elementor
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 6.0
 * Tested up to:      6.8
 * Elementor tested up to: 3.99
 * Elementor Pro tested up to: 3.99
 *
 */

namespace EPKA_Elementor_PowerKit_Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION' ) ) {
	define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION', get_file_data( __FILE__, [ 'Version' ] )[0] ); // phpcs:ignore
}

define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__', __FILE__ );
define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_PLUGIN_BASE', plugin_basename( EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__ ) );
define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_PLUGIN_DIR', dirname( EPKA_ELEMENTOR_POWERKIT_ADDONS_PLUGIN_BASE ) );

define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH', plugin_dir_path( EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__ ) );
define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_URL', plugin_dir_url( EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__ ) );

// Instance the plugin
EPKA_Elementor_PowerKit_Addons::instance();

/**
 * Main Class for PowerKit Addons For Elementor
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class EPKA_Elementor_PowerKit_Addons {
	/**
	 * Minimum Elementor Version
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 * @since 1.0.0
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 * @since 1.0.0
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @var EPKA_Elementor_PowerKit_Addons The single instance of the class.
	 * @since 1.0.0
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @return EPKA_Elementor_PowerKit_Addons An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		self::includes();

		add_action( 'init', array( $this, 'i18n' ) );

		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'plugin_css' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'plugin_css' ) );
		
		add_action( 'elementor/editor/footer', array( $this, 'plugin_scripts' ) );
		add_action( 'elementor/editor/footer', array( $this, 'insert_js_templates' ) );

		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'epka_enqueue_admin_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'epka_enqueue_admin_global_css' ], 99 );
		

	}

	/** Register top-level admin menu */
	public function register_admin_menu() {
		add_menu_page(
			__( 'PowerKit Dashboard', 'powerkit-addons-for-elementor' ), // Page title
			__( 'PowerKit Addons', 'powerkit-addons-for-elementor' ),           // Menu title
			'manage_options',
			'powerkit-dashboard',                             // Menu slug
			[ $this, 'render_dashboard_page' ],                          // Callback
			//'dashicons-layout',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/images/logo.svg',
			58
		);
	}

	/** Render the dashboard page */
	public function render_dashboard_page() {
		include EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'admin/pages/admin-dashboard.php';
	}

	/**
	 * ✅ ADDED: Enqueue admin CSS/JS only on our dashboard screen
	 */
	public function epka_enqueue_admin_assets() {
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $page !== 'powerkit-dashboard' ) {
			return;
		}
		wp_enqueue_style(
			'epka-admin-dashboard-style',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'admin/assets/css/epka-admin-dashboard.css',
			[],
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
		wp_enqueue_script(
			'epka-admin-dashboard-script',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'admin/assets/js/epka-admin-dashboard.js',
			[ 'jquery' ],
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
	}

	public function epka_enqueue_admin_global_css() {
		// A small global admin stylesheet for menu icon tweaks
		wp_enqueue_style(
			'epka-admin-global',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'admin/assets/css/epka-admin-global.css',
			[],
			filemtime( EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'admin/assets/css/epka-admin-global.css' )
		);
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'powerkit-addons-for-elementor', false, EPKA_ELEMENTOR_POWERKIT_ADDONS_PLUGIN_DIR . '/languages' );
	}

	/**
	 * Includes files
	 * @method includes
	 *
	 * @return void
	 */
	public function includes() {
		include_once EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/powerkit-elementor-widgets.php';
		include_once EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/powerkit-template-manager.php';
	}

	/**
	 * Get Editor Templates
	 *
	 * @return void
	 */
	public function insert_js_templates() {
		ob_start();
			require_once EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/template-kit/templates.php';
		ob_end_flush();
	}

	/**
	 * Enqueue plugin styles.
	 */
	public function plugin_css() {	
		wp_enqueue_style( 'powerkit-addons-for-elementor', EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/css/powerkit-elementor-addons.css', [], EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_enqueue_style( 'select2', EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/vendors/select2/select2.css', [], EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION );
	}

	/**
	 * Enqueue plugin scripts.
	 */
	public function plugin_scripts() {
		wp_enqueue_script( 'select2', EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/vendors/select2/select2.full.min.js', array( 'jquery' ), EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
		wp_enqueue_script( 'powerkit-addons-for-elementor', EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/js/powerkit-elementor-addons.js', array( 'jquery', 'wp-util', 'select2' ), EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
		wp_enqueue_script( 'template-script-addons', EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/js/template-script.js', array( 'jquery'), EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return false;
		}

		return true;
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		// Add Plugin actions
	}


	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$plugin = 'elementor/elementor.php';
		$installed_plugins = get_plugins();
		$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

		if ( $is_elementor_installed ) {
		
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to activated.', 'powerkit-addons-for-elementor' ),
				'<strong>' . esc_html__( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'powerkit-addons-for-elementor' ) . '</strong>'
			);

			$button_text = esc_html__( 'Activate Elementor', 'powerkit-addons-for-elementor' );
			$button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		
		} else {

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed.', 'powerkit-addons-for-elementor' ),
				'<strong>' . esc_html__( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'powerkit-addons-for-elementor' ) . '</strong>'
			);

			$button_text = esc_html__( 'Install Elementor', 'powerkit-addons-for-elementor' );
			$button_link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		}

		$button = sprintf(
			'<a class="button button-primary" href="%1$s">%2$s</a>',
			esc_url( $button_link ),
			esc_html( $button_text )
		);

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%1$s</p> <p>%2$s</p></div>',
			esc_html( $message ),
			wp_kses_post( $button )
		);


	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'powerkit-addons-for-elementor' ),
			'<strong>' . esc_html__( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>',
			esc_html( $message )
		);

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name, 2: Required plugin/theme name, 3: Required version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'powerkit-addons-for-elementor' ),
			'<strong>' . esc_html__( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'powerkit-addons-for-elementor' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );
	}
}