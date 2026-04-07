<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Google_Map extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style(
			'pkae-google-map',
			plugins_url( 'assets/css/pkae-google-map.css', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
		wp_register_script(
			'pkae-google-map',
			plugins_url( 'assets/js/pkae-google-map.js', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
	}

	public function get_name()          { return 'pkae-google-map'; }
	public function get_title()         { return esc_html__( 'Google Map', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-google-maps'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-google-map' ]; }
	public function get_script_depends(){ return [ 'pkae-google-map' ]; }
	public function get_keywords()      { return [ 'google map', 'map', 'location', 'marker', 'address', 'powerkit' ]; }

	protected function register_controls() {

		// ── MAP SETTINGS ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_map', [
			'label' => esc_html__( 'Map Settings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'map_source', [
			'label'   => esc_html__( 'Map Source', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'embed',
			'options' => [
				'embed' => esc_html__( 'Embed Code', 'powerkit-addons-for-elementor' ),
				'api'   => esc_html__( 'API Key', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'embed_code', [
			'label'       => esc_html__( 'Embed Code', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 5,
			'placeholder' => '<iframe src="https://www.google.com/maps/embed?..." ...></iframe>',
			'description' => esc_html__( 'Paste the full iframe embed code from Google Maps > Share > Embed a map.', 'powerkit-addons-for-elementor' ),
			'condition'   => [ 'map_source' => 'embed' ],
		] );

		$this->add_control( 'api_key', [
			'label'       => esc_html__( 'Google Maps API Key', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'AIzaSy...',
			'label_block' => true,
			'description' => esc_html__( 'Get your API key from Google Cloud Console.', 'powerkit-addons-for-elementor' ),
			'condition'   => [ 'map_source' => 'api' ],
		] );

		$this->add_control( 'map_type', [
			'label'     => esc_html__( 'Map Type', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'roadmap',
			'options'   => [
				'roadmap'   => esc_html__( 'Road Map', 'powerkit-addons-for-elementor' ),
				'satellite' => esc_html__( 'Satellite', 'powerkit-addons-for-elementor' ),
				'hybrid'    => esc_html__( 'Hybrid', 'powerkit-addons-for-elementor' ),
				'terrain'   => esc_html__( 'Terrain', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'map_source' => 'api' ],
		] );

		$this->add_control( 'zoom', [
			'label'     => esc_html__( 'Zoom Level', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1, 'max' => 22 ] ],
			'default'   => [ 'size' => 14 ],
			'condition' => [ 'map_source' => 'api' ],
		] );

		$this->add_responsive_control( 'map_height', [
			'label'      => esc_html__( 'Map Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [ 'px' => [ 'min' => 100, 'max' => 1000 ], 'vh' => [ 'min' => 10, 'max' => 100 ] ],
			'default'    => [ 'size' => 400, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-gmap__canvas' => 'height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── MARKERS ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_markers', [
			'label'     => esc_html__( 'Markers', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'map_source' => 'api' ],
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'location_type', [
			'label'   => esc_html__( 'Location Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'address',
			'options' => [
				'address' => esc_html__( 'Address', 'powerkit-addons-for-elementor' ),
				'latlng'  => esc_html__( 'Lat / Lng', 'powerkit-addons-for-elementor' ),
			],
		] );

		$repeater->add_control( 'address', [
			'label'       => esc_html__( 'Address', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'New York, USA', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'condition'   => [ 'location_type' => 'address' ],
		] );

		$repeater->add_control( 'latitude', [
			'label'     => esc_html__( 'Latitude', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '40.7128',
			'condition' => [ 'location_type' => 'latlng' ],
		] );

		$repeater->add_control( 'longitude', [
			'label'     => esc_html__( 'Longitude', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '-74.0060',
			'condition' => [ 'location_type' => 'latlng' ],
		] );

		$repeater->add_control( 'marker_title', [
			'label'       => esc_html__( 'Marker Title', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'My Location', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'separator'   => 'before',
		] );

		$repeater->add_control( 'marker_desc', [
			'label' => esc_html__( 'Info Window Content', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::TEXTAREA,
			'rows'  => 3,
		] );

		$repeater->add_control( 'marker_icon', [
			'label'       => esc_html__( 'Custom Marker Icon (URL)', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => esc_html__( 'Leave empty to use default marker.', 'powerkit-addons-for-elementor' ),
		] );

		$repeater->add_control( 'marker_icon_size', [
			'label'     => esc_html__( 'Icon Size (px)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 16, 'max' => 100 ] ],
			'default'   => [ 'size' => 40 ],
			'condition' => [ 'marker_icon[url]!' => '' ],
		] );

		$this->add_control( 'markers', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ marker_title }}}',
			'default'     => [
				[
					'location_type' => 'address',
					'address'       => 'New York, USA',
					'marker_title'  => 'New York',
					'marker_desc'   => 'The Big Apple',
				],
			],
		] );

		$this->end_controls_section();

		// ── MAP CONTROLS ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_controls', [
			'label'     => esc_html__( 'Map Controls', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'map_source' => 'api' ],
		] );

		$this->add_control( 'zoom_control', [
			'label'        => esc_html__( 'Zoom Control', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'map_type_control', [
			'label'        => esc_html__( 'Map Type Control', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'street_view_control', [
			'label'        => esc_html__( 'Street View Control', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'fullscreen_control', [
			'label'        => esc_html__( 'Full Screen Control', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'scroll_wheel', [
			'label'        => esc_html__( 'Scroll Wheel Zoom', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'draggable', [
			'label'        => esc_html__( 'Draggable', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->end_controls_section();

		// ── CUSTOM STYLE ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_custom_style', [
			'label'     => esc_html__( 'Custom Map Style', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'map_source' => 'api' ],
		] );

		$this->add_control( 'custom_style_notice', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'Paste a JSON style array from snazzymaps.com or mapstyle.withgoogle.com', 'powerkit-addons-for-elementor' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

		$this->add_control( 'map_style_json', [
			'label'      => esc_html__( 'Style JSON', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::CODE,
			'language'   => 'json',
			'rows'       => 10,
			'default'    => '',
		] );

		$this->end_controls_section();

		// ── STYLE: Box ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'map_border',
			'selector' => '{{WRAPPER}} .pkae-gmap',
		] );

		$this->add_control( 'map_bg', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-gmap' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'map_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-gmap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'map_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-gmap'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				'{{WRAPPER}} .pkae-gmap__canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'map_shadow',
			'selector' => '{{WRAPPER}} .pkae-gmap',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$map_source = ! empty( $s['map_source'] ) ? $s['map_source'] : 'embed';

		// ── Embed mode ────────────────────────────────────────────────────────
		if ( 'embed' === $map_source ) {
			$embed_code = ! empty( $s['embed_code'] ) ? trim( $s['embed_code'] ) : '';

			if ( empty( $embed_code ) ) {
				echo '<div class="pkae-gmap__notice">' . esc_html__( 'Please paste your Google Maps embed code.', 'powerkit-addons-for-elementor' ) . '</div>';
				return;
			}

			// Extract src from iframe and render safely
			if ( preg_match( '/src=["\']([^"\']+)["\']/', $embed_code, $matches ) ) {
				$src = $matches[1];
				// Only allow Google Maps embed URLs
				if ( strpos( $src, 'google.com/maps' ) !== false ) {
					echo '<div class="pkae-gmap"><iframe class="pkae-gmap__canvas" src="' . esc_url( $src ) . '" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>';
					return;
				}
			}

			echo '<div class="pkae-gmap__notice">' . esc_html__( 'Invalid embed code. Please paste a valid Google Maps iframe.', 'powerkit-addons-for-elementor' ) . '</div>';
			return;
		}

		// ── API mode ──────────────────────────────────────────────────────────
		$api_key        = ! empty( $s['api_key'] ) ? trim( $s['api_key'] ) : '';
		$markers        = ! empty( $s['markers'] ) ? $s['markers'] : [];
		$map_type       = ! empty( $s['map_type'] ) ? $s['map_type'] : 'roadmap';
		$zoom           = ! empty( $s['zoom']['size'] ) ? (int) $s['zoom']['size'] : 14;
		$zoom_ctrl      = isset( $s['zoom_control'] ) && 'yes' === $s['zoom_control'];
		$type_ctrl      = isset( $s['map_type_control'] ) && 'yes' === $s['map_type_control'];
		$street_ctrl    = isset( $s['street_view_control'] ) && 'yes' === $s['street_view_control'];
		$full_ctrl      = isset( $s['fullscreen_control'] ) && 'yes' === $s['fullscreen_control'];
		$scroll_wheel   = isset( $s['scroll_wheel'] ) && 'yes' === $s['scroll_wheel'];
		$draggable      = isset( $s['draggable'] ) && 'yes' === $s['draggable'];
		$style_json     = ! empty( $s['map_style_json'] ) ? $s['map_style_json'] : '';

		if ( empty( $api_key ) ) {
			echo '<div class="pkae-gmap__notice">' . esc_html__( 'Please enter your Google Maps API Key in the widget settings.', 'powerkit-addons-for-elementor' ) . '</div>';
			return;
		}

		// Build markers data
		$markers_data = [];
		foreach ( $markers as $marker ) {
			$entry = [
				'type'      => ! empty( $marker['location_type'] ) ? $marker['location_type'] : 'address',
				'address'   => ! empty( $marker['address'] ) ? $marker['address'] : '',
				'lat'       => ! empty( $marker['latitude'] ) ? $marker['latitude'] : '',
				'lng'       => ! empty( $marker['longitude'] ) ? $marker['longitude'] : '',
				'title'     => ! empty( $marker['marker_title'] ) ? $marker['marker_title'] : '',
				'desc'      => ! empty( $marker['marker_desc'] ) ? $marker['marker_desc'] : '',
				'icon'      => ! empty( $marker['marker_icon']['url'] ) ? $marker['marker_icon']['url'] : '',
				'icon_size' => ! empty( $marker['marker_icon_size']['size'] ) ? (int) $marker['marker_icon_size']['size'] : 40,
			];
			$markers_data[] = $entry;
		}

		$map_id = 'pkae-gmap-' . $this->get_id();

		$config = [
			'mapType'         => $map_type,
			'zoom'            => $zoom,
			'zoomControl'     => $zoom_ctrl,
			'mapTypeControl'  => $type_ctrl,
			'streetView'      => $street_ctrl,
			'fullscreen'      => $full_ctrl,
			'scrollWheel'     => $scroll_wheel,
			'draggable'       => $draggable,
			'styleJson'       => $style_json,
			'markers'         => $markers_data,
		];
		?>
		<div class="pkae-gmap">
			<div class="pkae-gmap__canvas"
				id="<?php echo esc_attr( $map_id ); ?>"
				data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
			</div>
		</div>

		<script>
		(function() {
			window.pkaeGmapQueue = window.pkaeGmapQueue || [];
			window.pkaeGmapQueue.push('<?php echo esc_js( $map_id ); ?>');
		})();
		</script>

		<?php if ( ! wp_script_is( 'pkae-google-maps-api', 'enqueued' ) ) :
			$api_url = 'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $api_key ) . '&callback=pkaeGmapInit';
			wp_enqueue_script( 'pkae-google-maps-api', $api_url, [], null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		endif;
	}
}
