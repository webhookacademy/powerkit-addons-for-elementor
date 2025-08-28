<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! current_user_can( 'manage_options' ) ) return;

// Where your widgets live:
$widgets_dir = PKAE_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/widgets';

// Discover widget slugs from folder names
$catalog = [];
foreach ( glob( $widgets_dir . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $path ) {
    $slug = basename( $path );

    // Make a human label from slug: "team-members" => "Team Members"
    $label = ucwords( str_replace( ['-', '_'], ' ', $slug ) );

    // Optional: read first PHPDoc line from widget file for description
    $file = trailingslashit( $path ) . $slug . '.php';
    $desc = '';
    if ( file_exists( $file ) ) {
        $header = file_get_contents( $file, false, null, 0, 400 );
        if ( $header && preg_match( '/\*\s*(.+)\r?\n/', $header, $m ) ) {
            $maybe = trim( $m[1] );
            if ( $maybe && stripos( $maybe, 'php' ) === false ) {
                $desc = $maybe;
            }
        }
    }

    $catalog[ $slug ] = [
        'label'       => $label,
        'description' => $desc,
    ];
}

// Current enabled list (default: all discovered)
$enabled = get_option( 'pkae_enabled_widgets', array_keys( $catalog ) );

// Save
if ( isset( $_POST['pkae_save_widgets'] ) && check_admin_referer( 'pkae_save_widgets_nonce', '_wpnonce' ) ) {
    $new = isset( $_POST['widgets'] ) && is_array( $_POST['widgets'] ) ? array_map( 'sanitize_key', $_POST['widgets'] ) : [];
    $new = array_values( array_intersect( array_keys( $catalog ), $new ) ); // keep valid only
    update_option( 'pkae_enabled_widgets', $new );
    $enabled = $new;
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
      <?php foreach ( $catalog as $slug => $meta ) : ?>
        <label class="pkae-card">
          <input type="checkbox" name="widgets[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $enabled, true ) ); ?> />
          <span class="pkae-card__title"><?php echo esc_html( $meta['label'] ); ?></span>
        </label>
      <?php endforeach; ?>
    </div>

    <p class="pkae-widget-save-btn"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save Changes', 'powerkit-addons-for-elementor' ); ?></button></p>
  </form>
</div>
