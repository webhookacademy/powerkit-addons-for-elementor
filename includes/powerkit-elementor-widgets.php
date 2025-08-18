<?php
namespace ElementorPowerKitWidgets;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

EPKA_Widgets::instance();

/**
 * Main Class for PowerKit Addons For Elementor Widgets.
 *
 * @since 1.0.0
 */
class EPKA_Widgets {

	/**
	 * Instance
	 *
	 * @var EPKA_Widgets The single instance of the class.
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
		add_action( 'elementor/init', array( $this, 'init' ), 9 );
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
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function init_widgets() {

			$found_slugs = [];
			foreach ( glob( __DIR__ . '/widgets/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $path ) {
				$found_slugs[] = str_replace( __DIR__ . '/widgets/', '', $path );
			}
			$enabled = get_option( 'epka_enabled_widgets', $found_slugs );

			foreach ( glob( __DIR__ . '/widgets/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $path ) {
				$slug  = str_replace( __DIR__ . '/widgets/', '', $path );
				if ( ! in_array( $slug, $enabled, true ) ) {
					continue;
				}

				$slug_ = str_replace( '-', '_', $slug );
				$file  = trailingslashit( $path ) . $slug . '.php';

				if ( file_exists( $file ) ) {
					require_once $file;

					$class_name = '\ElementorPowerKitWidgets\\' . ucwords( $slug_, '_' );

					if ( class_exists( $class_name ) ) {
						Plugin::instance()->widgets_manager->register( new $class_name() );
					}
				}
			}
	}



	/**
	 * Add Widget Categories
	 *
	 * Add custom widget categories to Elementor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function add_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'powerkit-addons-for-elementor',
			[
				'title' => __( 'PowerKit Addons', 'powerkit-addons-for-elementor' ),
				'icon' => 'fa fa-plug'
			]
		);

		$elements_manager->add_category(
			'powerkit-carousel-and-slider-categories',
			[
				'title' => __( 'PowerKit — Sliders & Carousels', 'powerkit-addons-for-elementor' ),
				'icon' => 'fa fa-plug'
			]
		);
	}

	/**
	 * Get a list of all the allowed HTML tags.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Array of allowed HTML tags.
	 */
	public static function get_allowed_html_tags() {
		$allowed_html = [
			'b' => [],
			'i' => [],
			'u' => [],
			's' => [],
			'br' => [],
			'em' => [],
			'del' => [],
			'ins' => [],
			'sub' => [],
			'sup' => [],
			'code' => [],
			'mark' => [],
			'small' => [],
			'strike' => [],
			'abbr' => [
				'title' => [],
			],
			'span' => [
				'class' => [],
			],
			'strong' => [],
			'a' => [
				'href' => [],
				'title' => [],
				'class' => [],
				'id' => [],
			],
			'q' => [
				'cite' => [],
			],
			'img' => [
				'src' => [],
				'alt' => [],
				'height' => [],
				'width' => [],
			],
			'dfn' => [
				'title' => [],
			],
			'time' => [
				'datetime' => [],
			],
			'cite' => [
				'title' => [],
			],
			'acronym' => [
				'title' => [],
			],
			'hr' => [],
		];

		return $allowed_html;
	}

	/**
	 * Strip all the tags except allowed html tags
	 *
	 * @param string $string
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
	public static function custom_kses( $string = '' ) {
		return wp_kses( $string, self::get_allowed_html_tags() );
	}
}
