<?php

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'EPKA_Library_Manager' ) ) {

	class EPKA_Library_Manager {


		/**
		 * Creates a single Instance of self
		 *
		 * @var Static data - Define menu main menu name
		 * @since 1.0.0
		 */
		private static $_instance = null;

		/**
		 * Define All Actions
		 *
		 * @var Static data - Define all actions
		 * @since 1.0.0
		 */
		static $element_pro_actions = null;


		/**
		 * Creates and returns the main object for this plugin
		 *
		 *
		 * @since  1.0.0
		 * @return EPKA_Library_Manager
		 */
		static public function init() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;

		}

		/**
		 * Main Constructor that sets up all static data associated with this plugin.
		 *
		 *
		 * @since  1.0.0
		 *
		 */
		private function __construct() {

			add_action( 'wp_ajax_get_epka_templates_library_view', array( $this, 'get_epka_templates_library_view' ) );
			add_action( 'wp_ajax_get_epka_preview', array( $this, 'ajax_get_epka_preview' ) );
			add_action( 'wp_ajax_get_filter_options', array( $this, 'get_template_filter_options_values' ) );
			

			/* Set initial version to the and call update on first use */
			if( get_option( 'epka_current_version' ) == false ) {
				update_option( 'epka_current_version', '0.0.0' );
			}

		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __clone() {

			_doing_it_wrong(
				__FUNCTION__,
				esc_html__( 'Cheatin&#8217; huh?', 'powerkit-addons-for-elementor' ),
				'1.0.0'
			);

		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {

			_doing_it_wrong(
				__FUNCTION__,
				esc_html__( 'Cheatin&#8217; huh?', 'powerkit-addons-for-elementor' ),
				'1.0.0'
			);

		}

		/**
		 * Get templates from the json library
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_epka_templates_library_view() {
			
			$template_list = array();
			$thumb_url = '';
			echo '<script> var EPKA_Index = []; </script>';
				$local_file = EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . '/includes/data/json/info.json';
				if( self::init()->get_filesystem()->exists( $local_file ) ) {
					$data = self::init()->get_filesystem()->get_contents( $local_file );
					$template_list = json_decode( $data, true );
				}
				$thumb_base_url = trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) . 'includes/data/images' );

			echo '<div class="epka-main-tiled-view">';
			if( count( $template_list ) != 0 ) {
				
				for( $i = 0; $i < count( $template_list ); $i++ ) {
					$slug = strtolower( str_replace( ' ', '-', $template_list[$i]['id'] ) );

					if( isset( $template_list[$i]['separator'] ) ) {
						echo '<h2 class="epka-templates-library-template-category" data-theme="'. esc_attr( strtolower( str_replace( ' ', '-', $template_list[$i]['theme'] ) ) ) .'">' . esc_html( $template_list[$i]['separator'] ) . '</h2>';
					}
					
					$thumb_name = isset( $template_list[$i]['thumbnail'] ) ? $template_list[$i]['thumbnail'] : '';
					if ( $thumb_name && ! pathinfo( $thumb_name, PATHINFO_EXTENSION ) ) {
						$thumb_name .= '.png';
					}
					$thumb_url = $thumb_base_url . ltrim( $thumb_name, '/' );

					?>
					<div 
						class="epka-templates-library-template epka-item" 
						data-theme="<?php echo esc_attr( strtolower( str_replace( ' ', '-', $template_list[$i]['theme'] ) ) ) ?>" 
						data-category="<?php echo esc_attr( strtolower( str_replace( ' ', '-', $template_list[$i]['category'] ) ) ) ?>"
						>
						<div class="epka-template-title">
							<?php echo esc_html( $template_list[$i]['name'] ); ?>
						</div>
						<div 
							class="epka-template-thumb epka-index-<?php echo esc_attr( $i ); ?>" 
							data-index="<?php echo esc_attr( $i ); ?>" 
							data-template="<?php echo esc_attr( wp_json_encode( $template_list[$i] ) ); ?>"
							style="background-image:url('<?php echo esc_url( $thumb_url ); ?>');"
						>
						</div>
						<div class="epka-action-bar">
							<div class="epka-grow"> </div>
							<div class="epka-btn-template-insert" data-version="EPKA__version-<?php echo esc_attr( $i ); ?>" data-template-name="<?php echo esc_attr( $slug ); ?>"><?php esc_html_e( 'Insert Template', 'powerkit-addons-for-elementor' ); ?></div>
						</div>
					</div>
				<?php
				}  
			} else {
				echo '<div class="epka-no-results"> <i class="fa fa-frown-o"></i> ' . esc_html__( 'No Templates Found!', 'powerkit-addons-for-elementor' ) . ' </div>';
			}
			
			echo '</div>';	
			
			wp_die();
		
		}

		/**
		 * Get templates themes
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_template_filter_options_values( $data ) {

			$themesList = $templates = array();

			$localJson = EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . '/includes/data/json/info.json';
			if ( self::init()->get_filesystem()->exists( $localJson ) ) {
				$data = self::init()->get_filesystem()->get_contents( $localJson );
				$templates = json_decode( $data, true );
			}

			if ( ! empty( $templates ) ) {
				foreach ( $templates as $template ) {
					if ( isset( $template['theme'] ) ) {
						$themesList[] = strtolower( str_replace( ' ', '-', $template['theme'] ) );
					}
				}
			}

			$themesList = array_unique( $themesList );

			echo json_encode( $themesList );

			wp_die();
		}

		/**
		 * Get  ajax preview template
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function ajax_get_epka_preview() {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$this->get_preview_template( $_POST['data'] );
			wp_die();
		}

		/**
		 * Print the preview window and make callable through ajax
		 *
		 * @return void
		 */
		private function get_preview_template( $data ) {
		
			if ( ! empty( $data['thumbnail'] ) && wp_http_validate_url( $data['thumbnail'] ) ) {
				$thumb_url = $data['thumbnail'];
			} else {
		
				$base = trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) . 'includes/data/images' );

				$thumb = isset( $data['thumbnail'] ) ? trim( $data['thumbnail'] ) : '';
			
				if ( $thumb && ! pathinfo( $thumb, PATHINFO_EXTENSION ) ) {
					$thumb .= '.png';
				}
				$thumb_url = $base . ltrim( $thumb, '/' );
			}
			?>
			<div id="epka-elementor-template-library-preview">
				<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
				<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( isset( $data['name'] ) ? $data['name'] : '' ); ?>" />
			</div>
			<?php
		}


		/**
		 * Get an instance of WP_Filesystem_Direct.
		 *
		 * @since 1.0.0
		 * @return object A WP_Filesystem_Direct instance.
		 */
		public static function get_filesystem() {
		
			global $wp_filesystem;
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();

			return $wp_filesystem;
		}
	}

	// Initialize the Elementor library
	EPKA_Library_Manager::init();

	require __DIR__ . '/powerkit-template-library.php';

} 