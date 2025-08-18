<?php
namespace Elementor\TemplateLibrary;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'elementor/init', function () {

	if ( ! class_exists( '\Elementor\TemplateLibrary\Source_Base' ) ) {
		return;
	}

	class EPKA_Library_Source extends Source_Base {

		public function __construct() {
			parent::__construct();
			add_action( 'wp_ajax_get_content_from_powerkit_export_file', [ $this, 'get_finalized_data' ] );
		}

		public function get_id() {}
		public function get_title() {}
		public function register_data() {}
		public function get_items( $args = [] ) {}
		public function get_item( $template_id ) {}
		public function get_data( array $args ) {}
		public function delete_template( $template_id ) {}
		public function save_item( $template_data ) {}
		public function update_item( $new_data ) {}
		public function export_template( $template_id ) {}

		public function get_finalized_data() {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$filename = sanitize_file_name( $_POST['filename'] ?? '' );
			$local_file = EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . '/includes/data/json/' . $filename;

			if ( ! file_exists( $local_file ) ) {
				wp_send_json_error([ 'error' => 'Template file not found: ' . $filename ]);
			}

			$data = file_get_contents( $local_file );
			$data = json_decode( $data, true );

			if ( ! isset( $data['content'] ) || empty( $data['content'] ) ) {
				wp_send_json_error([ 'error' => 'Template JSON missing "content" field.' ]);
			}

			$content = $data['content'];
			$content = $this->process_export_import_content( $content, 'on_import' );
			$content = $this->replace_elements_ids( $content );

			wp_send_json_success( $content );
		}

		public static function get_filesystem() {
			global $wp_filesystem;
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
			return $wp_filesystem;
		}
	}

	new EPKA_Library_Source();
});
