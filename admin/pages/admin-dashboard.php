<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! current_user_can( 'manage_options' ) ) return;

$pkae_widgets_dir = PKAE_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/widgets';

$pkae_catalog = [];
foreach ( glob( $pkae_widgets_dir . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $pkae_path ) {
	$pkae_slug  = basename( $pkae_path );
	$pkae_label = ucwords( str_replace( [ '-', '_' ], ' ', $pkae_slug ) );

	$pkae_file = trailingslashit( $pkae_path ) . $pkae_slug . '.php';
	$pkae_desc = '';
	if ( file_exists( $pkae_file ) ) {
		$pkae_header = file_get_contents( $pkae_file, false, null, 0, 400 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( $pkae_header && preg_match( '/\*\s*(.+)\r?\n/', $pkae_header, $pkae_m ) ) {
			$pkae_maybe = trim( $pkae_m[1] );
			if ( $pkae_maybe && stripos( $pkae_maybe, 'php' ) === false ) {
				$pkae_desc = $pkae_maybe;
			}
		}
	}

	$pkae_catalog[ $pkae_slug ] = [
		'label'       => $pkae_label,
		'description' => $pkae_desc,
	];
}

$pkae_enabled = get_option( 'pkae_enabled_widgets', array_keys( $pkae_catalog ) );

if ( isset( $_POST['pkae_save_widgets'] ) && check_admin_referer( 'pkae_save_widgets_nonce', '_wpnonce' ) ) {
	$pkae_new = isset( $_POST['widgets'] ) && is_array( $_POST['widgets'] ) ? array_map( 'sanitize_key', $_POST['widgets'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$pkae_new = array_values( array_intersect( array_keys( $pkae_catalog ), $pkae_new ) );
	update_option( 'pkae_enabled_widgets', $pkae_new );
	$pkae_enabled = $pkae_new;
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'powerkit-addons-for-elementor' ) . '</p></div>';
}
?>

<div class="wrap pkae-dashboard">
  <div class="pkae-hero">
    <div class="pkae-hero__content">
      <h1><?php esc_html_e( 'Welcome to PowerKit Addons Dashboard', 'powerkit-addons-for-elementor' ); ?></h1>
      <p><?php esc_html_e( 'Enable or disable widgets. Disabled widgets will not appear in the Elementor editor.', 'powerkit-addons-for-elementor' ); ?></p>
      <div class="pkae-hero__actions">
        <button id="pkae-select-all" class="button"><?php esc_html_e( 'Select all', 'powerkit-addons-for-elementor' ); ?></button>
        <button id="pkae-deselect-all" class="button"><?php esc_html_e( 'Deselect all', 'powerkit-addons-for-elementor' ); ?></button>
      </div>
    </div>
  </div>

  <form method="post" class="pkae-form">
    <?php wp_nonce_field( 'pkae_save_widgets_nonce' ); ?>
    <input type="hidden" name="pkae_save_widgets" value="1" />

    <div class="pkae-grid">
      <?php foreach ( $pkae_catalog as $pkae_slug => $pkae_meta ) : ?>
        <label class="pkae-card">
          <input type="checkbox" name="widgets[]" value="<?php echo esc_attr( $pkae_slug ); ?>" <?php checked( in_array( $pkae_slug, $pkae_enabled, true ) ); ?> />
          <span class="pkae-card__title"><?php echo esc_html( $pkae_meta['label'] ); ?></span>
        </label>
      <?php endforeach; ?>
    </div>

    <p class="pkae-widget-save-btn"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save Changes', 'powerkit-addons-for-elementor' ); ?></button></p>
  </form>
</div>
