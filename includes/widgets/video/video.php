<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit;

class Video extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-video', plugins_url( 'assets/css/pkae-video.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_script( 'pkae-video', plugins_url( 'assets/js/pkae-video.js', __FILE__ ), [ 'jquery' ], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	public function get_name()          { return 'pkae-video'; }
	public function get_title()         { return esc_html__( 'Video', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-youtube'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-video' ]; }
	public function get_script_depends(){ return [ 'pkae-video' ]; }
	public function get_keywords()      { return [ 'video', 'youtube', 'vimeo', 'embed', 'player', 'powerkit' ]; }

	protected function register_controls() {

		// ── VIDEO ─────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_video', [
			'label' => esc_html__( 'Video', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'video_type', [
			'label'   => esc_html__( 'Source', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'youtube',
			'options' => [
				'youtube'     => esc_html__( 'YouTube', 'powerkit-addons-for-elementor' ),
				'vimeo'       => esc_html__( 'Vimeo', 'powerkit-addons-for-elementor' ),
				'self_hosted' => esc_html__( 'Self Hosted', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'youtube_url', [
			'label'       => esc_html__( 'YouTube URL', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
			'placeholder' => 'https://www.youtube.com/watch?v=...',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
			'condition'   => [ 'video_type' => 'youtube' ],
		] );

		$this->add_control( 'vimeo_url', [
			'label'       => esc_html__( 'Vimeo URL', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://vimeo.com/...',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
			'condition'   => [ 'video_type' => 'vimeo' ],
		] );

		$this->add_control( 'self_hosted_url', [
			'label'       => esc_html__( 'Video URL / File', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::MEDIA,
			'media_type'  => 'video',
			'condition'   => [ 'video_type' => 'self_hosted' ],
		] );

		$this->add_control( 'aspect_ratio', [
			'label'   => esc_html__( 'Aspect Ratio', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '16_9',
			'options' => [
				'16_9' => '16:9',
				'4_3'  => '4:3',
				'3_2'  => '3:2',
				'1_1'  => '1:1',
				'9_16' => '9:16 (Vertical)',
			],
		] );

		$this->end_controls_section();

		// ── VIDEO OPTIONS ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_options', [
			'label' => esc_html__( 'Video Options', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'autoplay', [
			'label'        => esc_html__( 'Autoplay', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'mute', [
			'label'        => esc_html__( 'Mute', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'loop', [
			'label'        => esc_html__( 'Loop', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'controls', [
			'label'        => esc_html__( 'Player Controls', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'rel', [
			'label'        => esc_html__( 'Suggested Videos', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
			'description'  => esc_html__( 'Show related videos at end (YouTube). When off, shows only same channel videos.', 'powerkit-addons-for-elementor' ),
			'condition'    => [ 'video_type' => 'youtube' ],
		] );

		$this->add_control( 'start_time', [
			'label'       => esc_html__( 'Start Time (seconds)', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'placeholder' => '0',
			'condition'   => [ 'video_type' => [ 'youtube', 'vimeo' ] ],
		] );

		$this->add_control( 'end_time', [
			'label'       => esc_html__( 'End Time (seconds)', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'placeholder' => '0',
			'condition'   => [ 'video_type' => 'youtube' ],
		] );

		$this->end_controls_section();

		// ── THUMBNAIL ─────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_thumbnail', [
			'label' => esc_html__( 'Thumbnail', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'custom_thumbnail', [
			'label'        => esc_html__( 'Custom Thumbnail', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'thumbnail_image', [
			'label'     => esc_html__( 'Thumbnail Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => Utils::get_placeholder_image_src() ],
			'condition' => [ 'custom_thumbnail' => 'yes' ],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'large',
			'condition' => [ 'custom_thumbnail' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── PLAY BUTTON ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_play_button', [
			'label' => esc_html__( 'Play Button', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_play_button', [
			'label'        => esc_html__( 'Show Play Button', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'play_button_type', [
			'label'     => esc_html__( 'Button Type', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'circle',
			'options'   => [
				'circle'  => esc_html__( 'Circle', 'powerkit-addons-for-elementor' ),
				'square'  => esc_html__( 'Square', 'powerkit-addons-for-elementor' ),
				'outline' => esc_html__( 'Outline Circle', 'powerkit-addons-for-elementor' ),
				'custom'  => esc_html__( 'Custom Icon', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_play_button' => 'yes' ],
		] );

		$this->add_control( 'play_icon', [
			'label'     => esc_html__( 'Custom Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-play', 'library' => 'fa-solid' ],
			'condition' => [ 'show_play_button' => 'yes', 'play_button_type' => 'custom' ],
		] );

		$this->add_control( 'play_animation', [
			'label'     => esc_html__( 'Hover Animation', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'pulse',
			'options'   => [
				'none'  => esc_html__( 'None', 'powerkit-addons-for-elementor' ),
				'pulse' => esc_html__( 'Pulse', 'powerkit-addons-for-elementor' ),
				'grow'  => esc_html__( 'Grow', 'powerkit-addons-for-elementor' ),
				'shake' => esc_html__( 'Shake', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_play_button' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── CAPTION ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_caption', [
			'label' => esc_html__( 'Caption', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_caption', [
			'label'        => esc_html__( 'Show Caption', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'caption_text', [
			'label'       => esc_html__( 'Caption', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Watch the video', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
			'condition'   => [ 'show_caption' => 'yes' ],
		] );

		$this->add_control( 'caption_position', [
			'label'     => esc_html__( 'Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'below',
			'options'   => [
				'above'   => esc_html__( 'Above Video', 'powerkit-addons-for-elementor' ),
				'below'   => esc_html__( 'Below Video', 'powerkit-addons-for-elementor' ),
				'overlay' => esc_html__( 'Overlay (on thumbnail)', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_caption' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Box ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-video-wrap'       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				'{{WRAPPER}} .pkae-video-thumbnail'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-video-wrap',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-video-wrap',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-video-outer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'box_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-video-outer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Overlay ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_overlay', [
			'label' => esc_html__( 'Overlay', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'overlay_bg',
			'selector' => '{{WRAPPER}} .pkae-video-overlay',
		] );

		$this->add_control( 'overlay_opacity', [
			'label'     => esc_html__( 'Opacity', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default'   => [ 'size' => 0.3 ],
			'selectors' => [ '{{WRAPPER}} .pkae-video-overlay' => 'opacity: {{SIZE}};' ],
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'thumbnail_filter',
			'label'    => esc_html__( 'Thumbnail CSS Filter', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-video-thumbnail img',
		] );

		$this->end_controls_section();

		// ── STYLE: Play Button ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_play', [
			'label'     => esc_html__( 'Play Button', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_play_button' => 'yes' ],
		] );

		$this->add_responsive_control( 'play_size', [
			'label'     => esc_html__( 'Button Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 30, 'max' => 200 ] ],
			'default'   => [ 'size' => 72 ],
			'selectors' => [ '{{WRAPPER}} .pkae-video-play' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ],
		] );

		$this->add_responsive_control( 'play_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 80 ] ],
			'default'   => [ 'size' => 28 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-video-play i'   => 'font-size: {{SIZE}}px;',
				'{{WRAPPER}} .pkae-video-play svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
			],
		] );

		$this->start_controls_tabs( 'play_tabs' );
		$this->start_controls_tab( 'play_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'play_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .pkae-video-play i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-video-play svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_control( 'play_bg_color', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.2)',
			'selectors' => [ '{{WRAPPER}} .pkae-video-play' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'play_border',
			'selector' => '{{WRAPPER}} .pkae-video-play',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'play_shadow',
			'selector' => '{{WRAPPER}} .pkae-video-play',
		] );

		$this->end_controls_tab();
		$this->start_controls_tab( 'play_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'play_icon_color_hover', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .pkae-video-play:hover i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-video-play:hover svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_control( 'play_bg_hover', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.35)',
			'selectors' => [ '{{WRAPPER}} .pkae-video-play:hover' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'play_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%' ],
			'separator'  => 'before',
			'selectors'  => [ '{{WRAPPER}} .pkae-video-play' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Caption ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_caption', [
			'label'     => esc_html__( 'Caption', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_caption' => 'yes' ],
		] );

		$this->add_control( 'caption_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-video-caption' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_typo',
			'selector' => '{{WRAPPER}} .pkae-video-caption',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'caption_bg',
			'selector' => '{{WRAPPER}} .pkae-video-caption',
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-video-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'caption_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'selectors' => [ '{{WRAPPER}} .pkae-video-caption' => 'text-align: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	protected function get_video_src( $s ) {
		$type = $s['video_type'] ?? 'youtube';
		$params = [];

		if ( 'youtube' === $type ) {
			$url = $s['youtube_url'] ?? '';
			preg_match( '/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m );
			$vid = $m[1] ?? '';
			if ( ! $vid ) return '';
			if ( isset( $s['autoplay'] ) && 'yes' === $s['autoplay'] ) $params[] = 'autoplay=1';
			if ( isset( $s['mute'] ) && 'yes' === $s['mute'] ) $params[] = 'mute=1';
			if ( isset( $s['loop'] ) && 'yes' === $s['loop'] ) { $params[] = 'loop=1'; $params[] = 'playlist=' . $vid; }
			if ( ! isset( $s['controls'] ) || 'yes' !== $s['controls'] ) $params[] = 'controls=0';
			if ( isset( $s['rel'] ) && 'yes' === $s['rel'] ) {
				$params[] = 'rel=1';
			} else {
				$params[] = 'rel=0';
			}
			if ( ! empty( $s['start_time'] ) ) $params[] = 'start=' . (int) $s['start_time'];
			if ( ! empty( $s['end_time'] ) ) $params[] = 'end=' . (int) $s['end_time'];
			$params[] = 'enablejsapi=1';
			return 'https://www.youtube.com/embed/' . $vid . ( $params ? '?' . implode( '&', $params ) : '' );
		}

		if ( 'vimeo' === $type ) {
			$url = $s['vimeo_url'] ?? '';
			preg_match( '/vimeo\.com\/(\d+)/', $url, $m );
			$vid = $m[1] ?? '';
			if ( ! $vid ) return '';
			if ( isset( $s['autoplay'] ) && 'yes' === $s['autoplay'] ) $params[] = 'autoplay=1';
			if ( isset( $s['mute'] ) && 'yes' === $s['mute'] ) $params[] = 'muted=1';
			if ( isset( $s['loop'] ) && 'yes' === $s['loop'] ) $params[] = 'loop=1';
			if ( ! isset( $s['controls'] ) || 'yes' !== $s['controls'] ) $params[] = 'controls=0';
			if ( ! empty( $s['start_time'] ) ) $params[] = '#t=' . (int) $s['start_time'];
			return 'https://player.vimeo.com/video/' . $vid . ( $params ? '?' . implode( '&', $params ) : '' );
		}

		return '';
	}

	protected function render() {
		$s            = $this->get_settings_for_display();
		$type         = ! empty( $s['video_type'] ) ? $s['video_type'] : 'youtube';
		$aspect_map = [
			'16_9' => '56.25',
			'4_3'  => '75',
			'3_2'  => '66.67',
			'1_1'  => '100',
			'9_16' => '177.78',
		];
		$aspect_key = ! empty( $s['aspect_ratio'] ) ? $s['aspect_ratio'] : '16_9';
		$padding_bottom = $aspect_map[ $aspect_key ] ?? '56.25';
		$show_play    = isset( $s['show_play_button'] ) && 'yes' === $s['show_play_button'];
		$play_type    = ! empty( $s['play_button_type'] ) ? $s['play_button_type'] : 'circle';
		$play_anim    = ! empty( $s['play_animation'] ) ? $s['play_animation'] : 'pulse';
		$custom_thumb = isset( $s['custom_thumbnail'] ) && 'yes' === $s['custom_thumbnail'];
		$show_caption = isset( $s['show_caption'] ) && 'yes' === $s['show_caption'];
		$caption_pos  = ! empty( $s['caption_position'] ) ? $s['caption_position'] : 'below';
		$widget_id    = 'pkae-video-' . $this->get_id();

		$video_src = $this->get_video_src( $s );

		// Thumbnail
		$thumb_url = '';
		if ( $custom_thumb && ! empty( $s['thumbnail_image']['url'] ) ) {
			$thumb_url = $s['thumbnail_image']['url'];
		} elseif ( 'youtube' === $type ) {
			preg_match( '/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $s['youtube_url'] ?? '', $m );
			$vid = $m[1] ?? '';
			if ( $vid ) $thumb_url = 'https://img.youtube.com/vi/' . $vid . '/maxresdefault.jpg';
		}

		$has_thumb = ! empty( $thumb_url ) || 'self_hosted' !== $type;
		$use_facade = $has_thumb && 'self_hosted' !== $type;

		// Autoplay enabled → bypass facade, load iframe directly
		if ( isset( $s['autoplay'] ) && 'yes' === $s['autoplay'] ) {
			$use_facade = false;
		}

		Icons_Manager::enqueue_shim();
		?>
		<?php if ( $show_caption && 'above' === $caption_pos ) : ?>
			<div class="pkae-video-caption pkae-video-caption--above"><?php echo esc_html( $s['caption_text'] ?? '' ); ?></div>
		<?php endif; ?>

		<div class="pkae-video-outer">
		<div class="pkae-video-wrap pkae-video--<?php echo esc_attr( $play_type ); ?>"
			id="<?php echo esc_attr( $widget_id ); ?>"
			style="padding-bottom: <?php echo esc_attr( $padding_bottom ); ?>%;"
			data-src="<?php echo esc_attr( $video_src ); ?>"
			data-type="<?php echo esc_attr( $type ); ?>"
			data-facade="<?php echo $use_facade ? 'yes' : 'no'; ?>">

			<?php if ( $use_facade ) : ?>
				<?php // Thumbnail facade — iframe loads only on click ?>
				<div class="pkae-video-facade">
					<?php if ( $thumb_url ) : ?>
						<div class="pkae-video-thumbnail">
							<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $s['caption_text'] ?? '' ); ?>" loading="lazy">
						</div>
					<?php endif; ?>

					<div class="pkae-video-overlay"></div>

					<?php if ( $show_caption && 'overlay' === $caption_pos ) : ?>
						<div class="pkae-video-caption pkae-video-caption--overlay"><?php echo esc_html( $s['caption_text'] ?? '' ); ?></div>
					<?php endif; ?>

					<?php if ( $show_play ) : ?>
						<button class="pkae-video-play pkae-video-play--<?php echo esc_attr( $play_type ); ?> pkae-video-play--<?php echo esc_attr( $play_anim ); ?>" aria-label="<?php esc_attr_e( 'Play video', 'powerkit-addons-for-elementor' ); ?>">
							<?php if ( 'custom' === $play_type && ! empty( $s['play_icon']['value'] ) ) :
								Icons_Manager::render_icon( $s['play_icon'], [ 'aria-hidden' => 'true' ] );
							else : ?>
								<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
							<?php endif; ?>
						</button>
					<?php endif; ?>
				</div>

			<?php elseif ( 'self_hosted' === $type && ! empty( $s['self_hosted_url']['url'] ) ) : ?>
				<video class="pkae-video-player"
					src="<?php echo esc_url( $s['self_hosted_url']['url'] ); ?>"
					<?php echo isset( $s['controls'] ) && 'yes' === $s['controls'] ? 'controls' : ''; ?>
					<?php echo isset( $s['autoplay'] ) && 'yes' === $s['autoplay'] ? 'autoplay' : ''; ?>
					<?php echo isset( $s['mute'] ) && 'yes' === $s['mute'] ? 'muted' : ''; ?>
					<?php echo isset( $s['loop'] ) && 'yes' === $s['loop'] ? 'loop' : ''; ?>
					style="width:100%;height:100%;object-fit:cover;">
				</video>

			<?php elseif ( $video_src ) : ?>
				<iframe class="pkae-video-iframe" src="<?php echo esc_url( $video_src ); ?>" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
			<?php endif; ?>

		</div>

		<?php if ( $show_caption && 'below' === $caption_pos ) : ?>
			<div class="pkae-video-caption pkae-video-caption--below"><?php echo esc_html( $s['caption_text'] ?? '' ); ?></div>
		<?php endif; ?>
		</div><?php // .pkae-video-outer ?>
		<?php
	}
}
