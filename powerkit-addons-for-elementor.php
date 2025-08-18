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
 */

namespace EPKA_Elementor_PowerKit_Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION' ) ) {
	define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION', get_file_data( __FILE__, [ 'Version' ] )[0] );
}

define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__', __FILE__ );
define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_PLUGIN_BASE', plugin_basename( EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__ ) );
define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_PLUGIN_DIR', dirname( EPKA_ELEMENTOR_POWERKIT_ADDONS_PLUGIN_BASE ) );

define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH', plugin_dir_path( EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__ ) );
define( 'EPKA_ELEMENTOR_POWERKIT_ADDONS_URL', plugin_dir_url( EPKA_ELEMENTOR_POWERKIT_ADDONS__FILE__ ) );

final class EPKA_Elementor_PowerKit_Addons {

	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
	const MINIMUM_PHP_VERSION       = '7.0';

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'epka_enqueue_admin_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'epka_enqueue_admin_global_css' ), 99 );

		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
	}

	public function on_plugins_loaded() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		if ( ! $this->is_compatible() ) {
			return;
		}

		$this->includes();

		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'plugin_css' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'plugin_css' ) );

		add_action( 'elementor/editor/footer', array( $this, 'plugin_scripts' ) );
		add_action( 'elementor/editor/footer', array( $this, 'insert_js_templates' ) );

		$this->init();
	}

	public function register_admin_menu() {
		add_menu_page(
			__( 'PowerKit Dashboard', 'powerkit-addons-for-elementor' ),
			__( 'PowerKit Addons', 'powerkit-addons-for-elementor' ),
			'manage_options',
			'powerkit-dashboard',
			array( $this, 'render_dashboard_page' ),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/images/logo.svg',
			58
		);
	}

	public function render_dashboard_page() {
		include EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'admin/pages/admin-dashboard.php';
	}

	public function epka_enqueue_admin_assets() {
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		if ( $page !== 'powerkit-dashboard' ) {
			return;
		}
		wp_enqueue_style(
			'epka-admin-dashboard-style',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'admin/assets/css/epka-admin-dashboard.css',
			array(),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
		wp_enqueue_script(
			'epka-admin-dashboard-script',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'admin/assets/js/epka-admin-dashboard.js',
			array( 'jquery' ),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
	}

	public function epka_enqueue_admin_global_css() {
		$path = EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'admin/assets/css/epka-admin-global.css';
		wp_enqueue_style(
			'epka-admin-global',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'admin/assets/css/epka-admin-global.css',
			array(),
			file_exists( $path ) ? filemtime( $path ) : EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
	}

	public function includes() {
		include_once EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/powerkit-elementor-widgets.php';
		include_once EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/powerkit-template-manager.php';
	}

	public function insert_js_templates() {
		ob_start();
		require_once EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/template-kit/templates.php';
		ob_end_flush();
	}

	public function plugin_css() {
		wp_enqueue_style(
			'powerkit-addons-for-elementor',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/css/powerkit-elementor-addons.css',
			array(),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
		wp_enqueue_style(
			'select2',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/vendors/select2/select2.css',
			array(),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
	}

	public function plugin_scripts() {
		wp_enqueue_script(
			'select2',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/vendors/select2/select2.full.min.js',
			array( 'jquery' ),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
		wp_enqueue_script(
			'powerkit-addons-for-elementor',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/js/powerkit-elementor-addons.js',
			array( 'jquery', 'wp-util', 'select2' ),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
		wp_enqueue_script(
			'template-script-addons',
			EPKA_ELEMENTOR_POWERKIT_ADDONS_URL . 'assets/js/template-script.js',
			array( 'jquery' ),
			EPKA_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
	}

	public function is_compatible() {
		if ( defined( 'ELEMENTOR_VERSION' ) && ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return false;
		}
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return false;
		}
		return true;
	}

	public function init() {
		// Add Plugin actions if needed.
	}

	public function admin_notice_missing_main_plugin() {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$plugin = 'elementor/elementor.php';
		$installed_plugins = function_exists( 'get_plugins' ) ? get_plugins() : array();
		$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

		if ( $is_elementor_installed ) {
			$message = sprintf(
				__( '"%1$s" requires %2$s to be activated.', 'powerkit-addons-for-elementor' ),
				'<strong>' . __( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
				'<strong>' . __( 'Elementor', 'powerkit-addons-for-elementor' ) . '</strong>'
			);
			$button_text = __( 'Activate Elementor', 'powerkit-addons-for-elementor' );
			$button_link = wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . $plugin ),
				'activate-plugin_' . $plugin
			);
		} else {
			$message = sprintf(
				__( '"%1$s" requires %2$s to be installed.', 'powerkit-addons-for-elementor' ),
				'<strong>' . __( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
				'<strong>' . __( 'Elementor', 'powerkit-addons-for-elementor' ) . '</strong>'
			);
			$button_text = __( 'Install Elementor', 'powerkit-addons-for-elementor' );
			$button_link = wp_nonce_url(
				self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ),
				'install-plugin_elementor'
			);
		}

		$button = sprintf(
			'<p><a class="button button-primary" href="%1$s">%2$s</a></p>',
			esc_url( $button_link ),
			esc_html( $button_text )
		);

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>',
			wp_kses_post( $message ),
			$button
		);
	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
			__( '"%1$s" requires %2$s version %3$s or greater.', 'powerkit-addons-for-elementor' ),
			'<strong>' . __( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
			'<strong>' . __( 'Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
			__( '"%1$s" requires %2$s version %3$s or greater.', 'powerkit-addons-for-elementor' ),
			'<strong>' . __( 'PowerKit Addons For Elementor', 'powerkit-addons-for-elementor' ) . '</strong>',
			'<strong>' . __( 'PHP', 'powerkit-addons-for-elementor' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}
}

EPKA_Elementor_PowerKit_Addons::instance();
