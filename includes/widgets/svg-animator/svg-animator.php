<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) exit;

class SVG_Animator extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-svg-animator', plugins_url( 'assets/css/pkae-svg-animator.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_script( 'pkae-svg-animator', plugins_url( 'assets/js/pkae-svg-animator.js', __FILE__ ), [ 'jquery' ], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	public function get_name()          { return 'pkae-svg-animator'; }
	public function get_title()         { return esc_html__( 'SVG Animator', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-animation'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-svg-animator' ]; }
	public function get_script_depends(){ return [ 'pkae-svg-animator' ]; }
	public function get_keywords()      { return [ 'svg', 'animator', 'animation', 'draw', 'path', 'powerkit' ]; }

	protected function register_controls() {

		// ── SVG SOURCE ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_svg_source', [
			'label' => esc_html__( 'SVG Source', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'svg_source', [
			'label'   => esc_html__( 'SVG Source', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'file',
			'options' => [
				'file' => esc_html__( 'Upload File', 'powerkit-addons-for-elementor' ),
				'code' => esc_html__( 'SVG Code', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'svg_file', [
			'label'      => esc_html__( 'Upload SVG', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::MEDIA,
			'media_type' => 'image',
			'condition'  => [ 'svg_source' => 'file' ],
		] );

		$this->add_control( 'svg_code', [
			'label'       => esc_html__( 'SVG Code', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 10,
			'placeholder' => '<svg>...</svg>',
			'condition'   => [ 'svg_source' => 'code' ],
		] );

		$this->end_controls_section();

		// ── ANIMATION SETTINGS ────────────────────────────────────────────────
		$this->start_controls_section( 'section_animation', [
			'label' => esc_html__( 'Animation Settings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'animation_type', [
			'label'   => esc_html__( 'Animation Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'draw',
			'options' => [
				'draw'   => esc_html__( 'Draw (Stroke)', 'powerkit-addons-for-elementor' ),
				'fill'   => esc_html__( 'Fill', 'powerkit-addons-for-elementor' ),
				'scale'  => esc_html__( 'Scale', 'powerkit-addons-for-elementor' ),
				'rotate' => esc_html__( 'Rotate', 'powerkit-addons-for-elementor' ),
				'fade'   => esc_html__( 'Fade', 'powerkit-addons-for-elementor' ),
				'slide'  => esc_html__( 'Slide', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'animation_trigger', [
			'label'   => esc_html__( 'Animation Trigger', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'viewport',
			'options' => [
				'load'     => esc_html__( 'On Page Load', 'powerkit-addons-for-elementor' ),
				'viewport' => esc_html__( 'On Scroll Into View', 'powerkit-addons-for-elementor' ),
				'hover'    => esc_html__( 'On Hover', 'powerkit-addons-for-elementor' ),
				'click'    => esc_html__( 'On Click', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'viewport_threshold', [
			'label'       => esc_html__( 'Viewport Threshold (%)', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::SLIDER,
			'range'       => [ '%' => [ 'min' => 0, 'max' => 100 ] ],
			'default'     => [ 'size' => 50 ],
			'condition'   => [ 'animation_trigger' => 'viewport' ],
			'description' => esc_html__( 'Animation starts when this % of SVG is visible', 'powerkit-addons-for-elementor' ),
		] );

		$this->add_control( 'duration', [
			'label'   => esc_html__( 'Duration (ms)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 1500,
			'min'     => 100,
			'max'     => 10000,
			'step'    => 100,
		] );

		$this->add_control( 'delay', [
			'label'   => esc_html__( 'Delay (ms)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 0,
			'min'     => 0,
			'max'     => 10000,
			'step'    => 100,
		] );

		$this->add_control( 'easing', [
			'label'   => esc_html__( 'Easing', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'ease',
			'options' => [
				'linear'     => esc_html__( 'Linear', 'powerkit-addons-for-elementor' ),
				'ease'       => esc_html__( 'Ease', 'powerkit-addons-for-elementor' ),
				'ease-in'    => esc_html__( 'Ease In', 'powerkit-addons-for-elementor' ),
				'ease-out'   => esc_html__( 'Ease Out', 'powerkit-addons-for-elementor' ),
				'ease-in-out'=> esc_html__( 'Ease In Out', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->end_controls_section();

		// ── PATH ANIMATION ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_path_animation', [
			'label' => esc_html__( 'Path Animation', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'path_animation_mode', [
			'label'   => esc_html__( 'Path Animation Mode', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'together',
			'options' => [
				'together'   => esc_html__( 'All Paths Together', 'powerkit-addons-for-elementor' ),
				'sequential' => esc_html__( 'Sequential', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'stagger_delay', [
			'label'     => esc_html__( 'Stagger Delay (ms)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 100,
			'min'       => 0,
			'max'       => 2000,
			'step'      => 50,
			'condition' => [ 'path_animation_mode' => 'sequential' ],
		] );

		$this->add_control( 'direction', [
			'label'   => esc_html__( 'Direction', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'normal',
			'options' => [
				'normal'    => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ),
				'reverse'   => esc_html__( 'Reverse', 'powerkit-addons-for-elementor' ),
				'alternate' => esc_html__( 'Alternate', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->end_controls_section();

		// ── PLAYBACK OPTIONS ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_playback', [
			'label' => esc_html__( 'Playback Options', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'loop', [
			'label'        => esc_html__( 'Loop Animation', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'yoyo', [
			'label'        => esc_html__( 'Yoyo Effect', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
			'description'  => esc_html__( 'Animation reverses on each iteration', 'powerkit-addons-for-elementor' ),
		] );

		$this->add_control( 'replay_on_click', [
			'label'        => esc_html__( 'Replay on Click', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'repeat_on_scroll', [
			'label'        => esc_html__( 'Repeat on Scroll', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
			'condition'    => [ 'animation_trigger' => 'viewport' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Box ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'selectors' => [ '{{WRAPPER}}' => 'display: flex; justify-content: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_bg',
			'selector' => '{{WRAPPER}} .pkae-svg-animator',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-svg-animator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'box_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-svg-animator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-svg-animator',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-svg-animator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-svg-animator',
		] );

		$this->end_controls_section();

		// ── STYLE: SVG ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_svg', [
			'label' => esc_html__( 'SVG', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'svg_width', [
			'label'      => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'px' => [ 'min' => 10, 'max' => 1000 ],
				'%'  => [ 'min' => 1, 'max' => 100 ],
			],
			'default'    => [ 'size' => 300, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-svg-animator svg' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'svg_max_width', [
			'label'      => esc_html__( 'Max Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [ 'min' => 10, 'max' => 2000 ],
				'%'  => [ 'min' => 1, 'max' => 100 ],
			],
			'selectors'  => [ '{{WRAPPER}} .pkae-svg-animator svg' => 'max-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'svg_height', [
			'label'      => esc_html__( 'Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [
				'px' => [ 'min' => 10, 'max' => 1000 ],
			],
			'selectors'  => [ '{{WRAPPER}} .pkae-svg-animator svg' => 'height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'svg_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator' => 'text-align: {{VALUE}};' ],
		] );

		$this->add_control( 'svg_stroke_color', [
			'label'     => esc_html__( 'Stroke Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator svg path, {{WRAPPER}} .pkae-svg-animator svg line, {{WRAPPER}} .pkae-svg-animator svg polyline, {{WRAPPER}} .pkae-svg-animator svg polygon, {{WRAPPER}} .pkae-svg-animator svg circle, {{WRAPPER}} .pkae-svg-animator svg ellipse, {{WRAPPER}} .pkae-svg-animator svg rect' => 'stroke: {{VALUE}};' ],
		] );

		$this->add_control( 'svg_fill_color', [
			'label'     => esc_html__( 'Fill Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator svg path, {{WRAPPER}} .pkae-svg-animator svg polygon, {{WRAPPER}} .pkae-svg-animator svg circle, {{WRAPPER}} .pkae-svg-animator svg ellipse, {{WRAPPER}} .pkae-svg-animator svg rect' => 'fill: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'svg_stroke_width', [
			'label'     => esc_html__( 'Stroke Width', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator svg path, {{WRAPPER}} .pkae-svg-animator svg line, {{WRAPPER}} .pkae-svg-animator svg polyline, {{WRAPPER}} .pkae-svg-animator svg polygon, {{WRAPPER}} .pkae-svg-animator svg circle, {{WRAPPER}} .pkae-svg-animator svg ellipse, {{WRAPPER}} .pkae-svg-animator svg rect' => 'stroke-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'svg_stroke_linecap', [
			'label'   => esc_html__( 'Stroke Linecap', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'butt'   => esc_html__( 'Butt', 'powerkit-addons-for-elementor' ),
				'round'  => esc_html__( 'Round', 'powerkit-addons-for-elementor' ),
				'square' => esc_html__( 'Square', 'powerkit-addons-for-elementor' ),
			],
			'default'   => 'round',
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator svg path, {{WRAPPER}} .pkae-svg-animator svg line, {{WRAPPER}} .pkae-svg-animator svg polyline' => 'stroke-linecap: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'svg_rotate', [
			'label'     => esc_html__( 'Rotate', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'deg' => [ 'min' => -360, 'max' => 360 ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator svg' => 'transform: rotate({{SIZE}}deg);' ],
		] );

		$this->add_responsive_control( 'svg_opacity', [
			'label'     => esc_html__( 'Opacity', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.1 ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator svg' => 'opacity: {{SIZE}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Effects ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_effects', [
			'label' => esc_html__( 'Effects', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'svg_css_filters',
			'selector' => '{{WRAPPER}} .pkae-svg-animator svg',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'svg_box_shadow',
			'selector' => '{{WRAPPER}} .pkae-svg-animator svg',
		] );

		$this->end_controls_section();

		// ── STYLE: Hover Effects ──────────────────────────────────────────────
		$this->start_controls_section( 'section_style_hover', [
			'label' => esc_html__( 'Hover Effects', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'hover_stroke_color', [
			'label'     => esc_html__( 'Hover Stroke Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator:hover svg path, {{WRAPPER}} .pkae-svg-animator:hover svg line, {{WRAPPER}} .pkae-svg-animator:hover svg polyline, {{WRAPPER}} .pkae-svg-animator:hover svg polygon, {{WRAPPER}} .pkae-svg-animator:hover svg circle, {{WRAPPER}} .pkae-svg-animator:hover svg ellipse, {{WRAPPER}} .pkae-svg-animator:hover svg rect' => 'stroke: {{VALUE}};' ],
		] );

		$this->add_control( 'hover_fill_color', [
			'label'     => esc_html__( 'Hover Fill Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator:hover svg path, {{WRAPPER}} .pkae-svg-animator:hover svg polygon, {{WRAPPER}} .pkae-svg-animator:hover svg circle, {{WRAPPER}} .pkae-svg-animator:hover svg ellipse, {{WRAPPER}} .pkae-svg-animator:hover svg rect' => 'fill: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'hover_scale', [
			'label'     => esc_html__( 'Hover Scale', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.1 ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator:hover svg' => 'transform: scale({{SIZE}});' ],
		] );

		$this->add_responsive_control( 'hover_rotate', [
			'label'     => esc_html__( 'Hover Rotate', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'deg' => [ 'min' => -360, 'max' => 360 ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator:hover svg' => 'transform: rotate({{SIZE}}deg);' ],
		] );

		$this->add_control( 'hover_transition', [
			'label'     => esc_html__( 'Transition Duration (ms)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 3000 ] ],
			'default'   => [ 'size' => 300 ],
			'selectors' => [ '{{WRAPPER}} .pkae-svg-animator svg' => 'transition: all {{SIZE}}ms ease;' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$svg_source   = ! empty( $s['svg_source'] ) ? $s['svg_source'] : 'file';
		$svg_content  = '';

		if ( 'file' === $svg_source && ! empty( $s['svg_file']['url'] ) ) {
			$svg_url = $s['svg_file']['url'];
			if ( strpos( $svg_url, '.svg' ) !== false ) {
				$response = wp_remote_get( $svg_url );
				if ( ! is_wp_error( $response ) ) {
					$svg_content = wp_remote_retrieve_body( $response );
				}
			}
		} elseif ( 'code' === $svg_source && ! empty( $s['svg_code'] ) ) {
			$svg_content = $s['svg_code'];
		}

		if ( empty( $svg_content ) ) {
			echo '<div class="pkae-svg-animator-placeholder">' . esc_html__( 'Please add an SVG file or code', 'powerkit-addons-for-elementor' ) . '</div>';
			return;
		}

		$animation_type      = ! empty( $s['animation_type'] ) ? $s['animation_type'] : 'draw';
		$animation_trigger   = ! empty( $s['animation_trigger'] ) ? $s['animation_trigger'] : 'viewport';
		$viewport_threshold  = isset( $s['viewport_threshold']['size'] ) ? $s['viewport_threshold']['size'] : 50;
		$duration            = ! empty( $s['duration'] ) ? $s['duration'] : 1500;
		$delay               = ! empty( $s['delay'] ) ? $s['delay'] : 0;
		$easing              = ! empty( $s['easing'] ) ? $s['easing'] : 'ease';
		$path_animation_mode = ! empty( $s['path_animation_mode'] ) ? $s['path_animation_mode'] : 'together';
		$stagger_delay       = ! empty( $s['stagger_delay'] ) ? $s['stagger_delay'] : 100;
		$direction           = ! empty( $s['direction'] ) ? $s['direction'] : 'normal';
		$loop                = isset( $s['loop'] ) && 'yes' === $s['loop'];
		$yoyo                = isset( $s['yoyo'] ) && 'yes' === $s['yoyo'];
		$replay_on_click     = isset( $s['replay_on_click'] ) && 'yes' === $s['replay_on_click'];
		$repeat_on_scroll    = isset( $s['repeat_on_scroll'] ) && 'yes' === $s['repeat_on_scroll'];

		$widget_id = 'pkae-svg-' . $this->get_id();

		?>
		<div class="pkae-svg-animator" id="<?php echo esc_attr( $widget_id ); ?>"
			data-animation-type="<?php echo esc_attr( $animation_type ); ?>"
			data-animation-trigger="<?php echo esc_attr( $animation_trigger ); ?>"
			data-viewport-threshold="<?php echo esc_attr( $viewport_threshold ); ?>"
			data-duration="<?php echo esc_attr( $duration ); ?>"
			data-delay="<?php echo esc_attr( $delay ); ?>"
			data-easing="<?php echo esc_attr( $easing ); ?>"
			data-path-mode="<?php echo esc_attr( $path_animation_mode ); ?>"
			data-stagger-delay="<?php echo esc_attr( $stagger_delay ); ?>"
			data-direction="<?php echo esc_attr( $direction ); ?>"
			data-loop="<?php echo esc_attr( $loop ? 'yes' : 'no' ); ?>"
			data-yoyo="<?php echo esc_attr( $yoyo ? 'yes' : 'no' ); ?>"
			data-replay-click="<?php echo esc_attr( $replay_on_click ? 'yes' : 'no' ); ?>"
			data-repeat-scroll="<?php echo esc_attr( $repeat_on_scroll ? 'yes' : 'no' ); ?>">
			<?php echo $svg_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php
	}
}
