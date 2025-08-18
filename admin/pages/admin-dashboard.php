<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! current_user_can( 'manage_options' ) ) return;

// Where your widgets live:
$widgets_dir = EPKA_ELEMENTOR_POWERKIT_ADDONS_PATH . 'includes/widgets';

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
$enabled = get_option( 'epka_enabled_widgets', array_keys( $catalog ) );

// Save
if ( isset( $_POST['epka_save_widgets'] ) && check_admin_referer( 'epka_save_widgets_nonce', '_wpnonce' ) ) {
    $new = isset( $_POST['widgets'] ) && is_array( $_POST['widgets'] ) ? array_map( 'sanitize_key', $_POST['widgets'] ) : [];
    $new = array_values( array_intersect( array_keys( $catalog ), $new ) ); // keep valid only
    update_option( 'epka_enabled_widgets', $new );
    $enabled = $new;
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'powerkit-addons-for-elementor' ) . '</p></div>';
}
?>

<div class="wrap epka-dashboard">
  <div class="epka-hero">
    <div class="epka-hero__content">
      <h1><?php esc_html_e( 'Welcome to PowerKit Addons Dashboard', 'powerkit-addons-for-elementor' ); ?></h1>
      <p><?php esc_html_e( 'Enable or disable widgets. Disabled widgets will not appear in the Elementor editor.', 'powerkit-addons-for-elementor' ); ?></p>
      <div class="epka-hero__actions">
        <button id="epka-select-all" class="button"><?php esc_html_e( 'Select all', 'powerkit-addons-for-elementor' ); ?></button>
        <button id="epka-deselect-all" class="button"><?php esc_html_e( 'Deselect all', 'powerkit-addons-for-elementor' ); ?></button>
      </div>
    </div>
  </div>

  <form method="post" class="epka-form">
    <?php wp_nonce_field( 'epka_save_widgets_nonce' ); ?>
    <input type="hidden" name="epka_save_widgets" value="1" />

    <div class="epka-grid">
      <?php foreach ( $catalog as $slug => $meta ) : ?>
        <label class="epka-card">
          <input type="checkbox" name="widgets[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $enabled, true ) ); ?> />
          <span class="epka-card__title"><?php echo esc_html( $meta['label'] ); ?></span>
        </label>
      <?php endforeach; ?>
    </div>

    <p class="epka-widget-save-btn"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save Changes', 'powerkit-addons-for-elementor' ); ?></button></p>
  </form>
</div>
