<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) exit;

class Video_Gallery extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-video-gallery', plugins_url( 'assets/css/pkae-video-gallery.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_script( 'pkae-video-gallery', plugins_url( 'assets/js/pkae-video-gallery.js', __FILE__ ), [ 'jquery' ], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	public function get_name()          { return 'pkae-video-gallery'; }
	public function get_title()         { return esc_html__( 'Video Gallery', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-gallery-grid'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-video-gallery' ]; }
	public function get_script_depends(){ return [ 'pkae-video-gallery' ]; }
	public function get_keywords()      { return [ 'video gallery', 'youtube gallery', 'vimeo gallery', 'video grid', 'powerkit' ]; }

	protected function register_controls() {

		// ── VIDEOS ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_videos', [
			'label' => esc_html__( 'Videos', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'video_type', [
			'label'   => esc_html__( 'Source', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'youtube',
			'options' => [
				'youtube' => 'YouTube',
				'vimeo'   => 'Vimeo',
			],
		] );

		$repeater->add_control( 'video_url', [
			'label'       => esc_html__( 'Video URL', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'description', [
			'label'   => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'dynamic' => [ 'active' => true ],
		] );

		$repeater->add_control( 'filter_label', [
			'label'       => esc_html__( 'Filter Category', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'e.g. Tutorial, Review', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'custom_thumbnail', [
			'label'     => esc_html__( 'Custom Thumbnail', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::MEDIA,
			'separator' => 'before',
		] );

		$this->add_control( 'videos', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '<# if(title){#>{{{title}}}<#}else{#>{{{video_url}}}<#}#>',
			'default'     => [
				[ 'video_url' => 'https://www.youtube.com/watch?v=XHOmBV4js_E', 'title' => 'Video One',   'filter_label' => 'Tutorial' ],
				[ 'video_url' => 'https://www.youtube.com/watch?v=XHOmBV4js_E', 'title' => 'Video Two',   'filter_label' => 'Review' ],
				[ 'video_url' => 'https://www.youtube.com/watch?v=XHOmBV4js_E', 'title' => 'Video Three', 'filter_label' => 'Tutorial' ],
			],
		] );

		$this->end_controls_section();

		// ── LAYOUT ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'grid',
			'options' => [
				'grid'    => esc_html__( 'Grid', 'powerkit-addons-for-elementor' ),
				'masonry' => esc_html__( 'Masonry', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_responsive_control( 'columns', [
			'label'          => esc_html__( 'Columns', 'powerkit-addons-for-elementor' ),
			'type'           => Controls_Manager::SELECT,
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'options'        => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' ],
		] );

		$this->add_responsive_control( 'column_gap', [
			'label'     => esc_html__( 'Column Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 16 ],
		] );

		$this->add_responsive_control( 'row_gap', [
			'label'     => esc_html__( 'Row Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 16 ],
		] );

		$this->add_control( 'aspect_ratio', [
			'label'   => esc_html__( 'Aspect Ratio', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '16_9',
			'options' => [
				'16_9' => '16:9',
				'4_3'  => '4:3',
				'1_1'  => '1:1',
				'3_2'  => '3:2',
			],
		] );

		$this->end_controls_section();

		// ── FILTER ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_filter', [
			'label' => esc_html__( 'Filter', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_filter', [
			'label'        => esc_html__( 'Show Filter', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'filter_all_label', [
			'label'     => esc_html__( '"All" Label', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'All', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_filter' => 'yes' ],
		] );

		$this->add_responsive_control( 'filter_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'condition' => [ 'show_filter' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── OVERLAY ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_overlay', [
			'label' => esc_html__( 'Overlay & Play Button', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_title_overlay', [
			'label'        => esc_html__( 'Show Title on Hover', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'play_icon', [
			'label'   => esc_html__( 'Play Icon', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fas fa-play', 'library' => 'fa-solid' ],
		] );

		$this->add_control( 'hover_animation', [
			'label'   => esc_html__( 'Hover Animation', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'zoom-in',
			'options' => [
				'none'    => esc_html__( 'None', 'powerkit-addons-for-elementor' ),
				'zoom-in' => esc_html__( 'Zoom In', 'powerkit-addons-for-elementor' ),
				'zoom-out'=> esc_html__( 'Zoom Out', 'powerkit-addons-for-elementor' ),
				'slide-up'=> esc_html__( 'Slide Up', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->end_controls_section();

		// ── STYLE: Box ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_bg',
			'selector' => '{{WRAPPER}} .pkae-vg',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-vg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-vg',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-vg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-vg',
		] );

		$this->end_controls_section();

		// ── STYLE: Item ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_item', [
			'label' => esc_html__( 'Item', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'item_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '10', 'right' => '10', 'bottom' => '10', 'left' => '10', 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-vg__item'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				'{{WRAPPER}} .pkae-vg__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'item_shadow',
			'selector' => '{{WRAPPER}} .pkae-vg__item',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'thumbnail_filter',
			'selector' => '{{WRAPPER}} .pkae-vg__thumbnail img',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'thumbnail_filter_hover',
			'label'    => esc_html__( 'CSS Filter (Hover)', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-vg__item:hover .pkae-vg__thumbnail img',
		] );

		$this->end_controls_section();

		// ── STYLE: Overlay ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_overlay', [
			'label' => esc_html__( 'Overlay', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'overlay_color', [
			'label'     => esc_html__( 'Overlay Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.5)',
			'selectors' => [ '{{WRAPPER}} .pkae-vg__overlay' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'play_icon_color', [
			'label'     => esc_html__( 'Play Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .pkae-vg__play i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-vg__play svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'play_icon_size', [
			'label'     => esc_html__( 'Play Icon Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 100 ] ],
			'default'   => [ 'size' => 40 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-vg__play i'   => 'font-size: {{SIZE}}px;',
				'{{WRAPPER}} .pkae-vg__play svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
			],
		] );

		$this->add_responsive_control( 'play_btn_size', [
			'label'     => esc_html__( 'Play Button Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 30, 'max' => 150 ] ],
			'default'   => [ 'size' => 64 ],
			'selectors' => [ '{{WRAPPER}} .pkae-vg__play' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ],
		] );

		$this->add_control( 'play_btn_bg', [
			'label'     => esc_html__( 'Play Button Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.2)',
			'selectors' => [ '{{WRAPPER}} .pkae-vg__play' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'play_btn_radius', [
			'label'      => esc_html__( 'Play Button Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-vg__play' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Title ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_title', [
			'label'     => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_title_overlay' => 'yes' ],
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-vg__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-vg__title',
		] );

		$this->add_control( 'desc_color', [
			'label'     => esc_html__( 'Description Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.8)',
			'selectors' => [ '{{WRAPPER}} .pkae-vg__desc' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'desc_typo',
			'selector' => '{{WRAPPER}} .pkae-vg__desc',
		] );

		$this->end_controls_section();

		// ── STYLE: Filter ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_filter', [
			'label'     => esc_html__( 'Filter Buttons', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_filter' => 'yes' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'filter_typo',
			'selector' => '{{WRAPPER}} .pkae-vg__filter-btn',
		] );

		$this->add_responsive_control( 'filter_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '6', 'right' => '18', 'bottom' => '6', 'left' => '18', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-vg__filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'filter_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-vg__filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'filter_gap', [
			'label'     => esc_html__( 'Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
			'default'   => [ 'size' => 8 ],
			'selectors' => [ '{{WRAPPER}} .pkae-vg__filter' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'filter_margin', [
			'label'     => esc_html__( 'Bottom Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'   => [ 'size' => 24 ],
			'selectors' => [ '{{WRAPPER}} .pkae-vg__filter' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'filter_tabs' );
		$this->start_controls_tab( 'filter_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'filter_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-vg__filter-btn' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'filter_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-vg__filter-btn' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'filter_active', [ 'label' => esc_html__( 'Active', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'filter_active_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-vg__filter-btn.pkae-active' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'filter_active_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [ '{{WRAPPER}} .pkae-vg__filter-btn.pkae-active' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── STYLE: Lightbox Close Button ──────────────────────────────────────
		$this->start_controls_section( 'section_style_lb_close', [
			'label' => esc_html__( 'Lightbox Close Button', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'lb_close_icon', [
			'label'   => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fas fa-times', 'library' => 'fa-solid' ],
		] );

		$this->add_responsive_control( 'lb_close_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 8, 'max' => 60 ] ],
			'default'    => [ 'size' => 20 ],
		] );

		$this->add_responsive_control( 'lb_close_btn_size', [
			'label'      => esc_html__( 'Button Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
			'default'    => [ 'size' => 40 ],
		] );

		$this->add_control( 'lb_close_color', [
			'label'   => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#ffffff',
		] );

		$this->add_control( 'lb_close_bg', [
			'label'   => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.5)',
		] );

		$this->add_control( 'lb_close_bg_hover', [
			'label'   => esc_html__( 'Background (Hover)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.8)',
		] );

		$this->add_responsive_control( 'lb_close_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%' ],
		] );

		$this->add_control( 'lb_close_pos_h', [
			'label'     => esc_html__( 'Horizontal Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'  => [ 'title' => 'Left',  'icon' => 'eicon-h-align-left' ],
				'right' => [ 'title' => 'Right', 'icon' => 'eicon-h-align-right' ],
			],
			'default'   => 'right',
			'toggle'    => false,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'lb_close_offset_x', [
			'label'      => esc_html__( 'X Offset', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'rem' ],
			'range'      => [ 'px' => [ 'min' => -100, 'max' => 1000 ], '%' => [ 'min' => -100, 'max' => 100 ], 'rem' => [ 'min' => -10, 'max' => 1000 ] ],
			'default'    => [ 'size' => 0, 'unit' => 'px' ],
		] );

		$this->add_control( 'lb_close_pos_v', [
			'label'   => esc_html__( 'Vertical Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'top'    => [ 'title' => 'Top',    'icon' => 'eicon-v-align-top' ],
				'bottom' => [ 'title' => 'Bottom', 'icon' => 'eicon-v-align-bottom' ],
			],
			'default' => 'top',
			'toggle'  => false,
		] );

		$this->add_responsive_control( 'lb_close_offset_y', [
			'label'      => esc_html__( 'Y Offset', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'rem' ],
			'range'      => [ 'px' => [ 'min' => -100, 'max' => 1000 ], '%' => [ 'min' => -100, 'max' => 100 ], 'rem' => [ 'min' => -10, 'max' => 1000 ] ],
			'default'    => [ 'size' => -50, 'unit' => 'px' ],
		] );

		$this->end_controls_section();
	}

	protected function get_video_embed_url( $type, $url ) {
		if ( 'youtube' === $type ) {
			preg_match( '/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m );
			$vid = $m[1] ?? '';
			return $vid ? 'https://www.youtube.com/embed/' . $vid . '?autoplay=1&rel=0' : '';
		}
		if ( 'vimeo' === $type ) {
			preg_match( '/vimeo\.com\/(\d+)/', $url, $m );
			$vid = $m[1] ?? '';
			return $vid ? 'https://player.vimeo.com/video/' . $vid . '?autoplay=1&muted=1' : '';
		}
		return '';
	}

	protected function get_video_thumbnail( $type, $url, $custom_url = '' ) {
		if ( $custom_url ) return $custom_url;
		if ( 'youtube' === $type ) {
			preg_match( '/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m );
			$vid = $m[1] ?? '';
			return $vid ? 'https://img.youtube.com/vi/' . $vid . '/maxresdefault.jpg' : '';
		}
		return Utils::get_placeholder_image_src();
	}

	protected function render() {
		$s           = $this->get_settings_for_display();
		$videos      = ! empty( $s['videos'] ) ? $s['videos'] : [];
		$layout      = ! empty( $s['layout'] ) ? $s['layout'] : 'grid';
		$columns     = ! empty( $s['columns'] ) ? (int) $s['columns'] : 3;
		$col_t       = ! empty( $s['columns_tablet'] ) ? (int) $s['columns_tablet'] : 2;
		$col_m       = ! empty( $s['columns_mobile'] ) ? (int) $s['columns_mobile'] : 1;
		$col_gap     = isset( $s['column_gap']['size'] ) ? (int) $s['column_gap']['size'] : 16;
		$row_gap     = isset( $s['row_gap']['size'] ) ? (int) $s['row_gap']['size'] : 16;
		$aspect      = ! empty( $s['aspect_ratio'] ) ? str_replace( '_', '/', $s['aspect_ratio'] ) : '16/9';
		$show_filter = isset( $s['show_filter'] ) && 'yes' === $s['show_filter'];
		$filter_all  = ! empty( $s['filter_all_label'] ) ? $s['filter_all_label'] : 'All';
		$filter_align= ! empty( $s['filter_align'] ) ? $s['filter_align'] : 'center';
		$show_title  = isset( $s['show_title_overlay'] ) && 'yes' === $s['show_title_overlay'];
		$hover_anim  = ! empty( $s['hover_animation'] ) ? $s['hover_animation'] : 'zoom-in';
		$widget_id   = 'pkae-vg-' . $this->get_id();

		Icons_Manager::enqueue_shim();

		// Close button config
		$lb_close_icon    = ! empty( $s['lb_close_icon'] ) ? $s['lb_close_icon'] : [ 'value' => 'fas fa-times', 'library' => 'fa-solid' ];
		$lb_icon_sz       = isset( $s['lb_close_icon_size']['size'] ) ? $s['lb_close_icon_size']['size'] : 20;
		$lb_btn_sz        = isset( $s['lb_close_btn_size']['size'] ) ? $s['lb_close_btn_size']['size'] : 40;
		$lb_color         = ! empty( $s['lb_close_color'] ) ? $s['lb_close_color'] : '#ffffff';
		$lb_bg            = ! empty( $s['lb_close_bg'] ) ? $s['lb_close_bg'] : 'rgba(0,0,0,0.5)';
		$lb_bg_h          = ! empty( $s['lb_close_bg_hover'] ) ? $s['lb_close_bg_hover'] : 'rgba(0,0,0,0.8)';
		$lb_cr            = isset( $s['lb_close_border_radius'] ) && is_array( $s['lb_close_border_radius'] ) ? $s['lb_close_border_radius'] : [];
		$lb_cru           = ! empty( $lb_cr['unit'] ) ? $lb_cr['unit'] : '%';
		$lb_radius        = ! empty( $lb_cr ) ? ( ( $lb_cr['top'] ?? 50 ) . $lb_cru . ' ' . ( $lb_cr['right'] ?? 50 ) . $lb_cru . ' ' . ( $lb_cr['bottom'] ?? 50 ) . $lb_cru . ' ' . ( $lb_cr['left'] ?? 50 ) . $lb_cru ) : '50%';
		$lb_pos_h         = ! empty( $s['lb_close_pos_h'] ) ? $s['lb_close_pos_h'] : 'right';
		$lb_pos_v         = ! empty( $s['lb_close_pos_v'] ) ? $s['lb_close_pos_v'] : 'top';
		$lb_off_x         = ( isset( $s['lb_close_offset_x']['size'] ) ? $s['lb_close_offset_x']['size'] : 0 ) . ( isset( $s['lb_close_offset_x']['unit'] ) ? $s['lb_close_offset_x']['unit'] : 'px' );
		$lb_off_y         = ( isset( $s['lb_close_offset_y']['size'] ) ? $s['lb_close_offset_y']['size'] : -50 ) . ( isset( $s['lb_close_offset_y']['unit'] ) ? $s['lb_close_offset_y']['unit'] : 'px' );
		$widget_id        = 'pkae-vg-' . $this->get_id();

		ob_start();
		Icons_Manager::render_icon( $lb_close_icon, [ 'aria-hidden' => 'true' ] );
		$lb_icon_html = ob_get_clean();

		// Collect filter categories
		$filter_cats = [];
		foreach ( $videos as $v ) {
			if ( ! empty( $v['filter_label'] ) ) {
				foreach ( array_map( 'trim', explode( ',', $v['filter_label'] ) ) as $cat ) {
					if ( $cat && ! in_array( $cat, $filter_cats, true ) ) {
						$filter_cats[] = $cat;
					}
				}
			}
		}

		$grid_style = '--pkae-vg-cols:' . $columns . ';--pkae-vg-cols-t:' . $col_t . ';--pkae-vg-cols-m:' . $col_m . ';--pkae-vg-col-gap:' . $col_gap . 'px;--pkae-vg-row-gap:' . $row_gap . 'px;';
		?>
		<div id="pkae-vg-lb-icon-<?php echo esc_attr( $widget_id ); ?>" style="display:none;" aria-hidden="true"><?php echo $lb_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="pkae-vg pkae-vg--<?php echo esc_attr( $layout ); ?>" id="<?php echo esc_attr( $widget_id ); ?>"
			data-lb-widget-id="<?php echo esc_attr( $widget_id ); ?>"
			data-lb-icon-size="<?php echo esc_attr( $lb_icon_sz ); ?>"
			data-lb-btn-size="<?php echo esc_attr( $lb_btn_sz ); ?>"
			data-lb-color="<?php echo esc_attr( $lb_color ); ?>"
			data-lb-bg="<?php echo esc_attr( $lb_bg ); ?>"
			data-lb-bg-hover="<?php echo esc_attr( $lb_bg_h ); ?>"
			data-lb-radius="<?php echo esc_attr( $lb_radius ); ?>"
			data-lb-pos-h="<?php echo esc_attr( $lb_pos_h ); ?>"
			data-lb-pos-v="<?php echo esc_attr( $lb_pos_v ); ?>"
			data-lb-off-x="<?php echo esc_attr( $lb_off_x ); ?>"
			data-lb-off-y="<?php echo esc_attr( $lb_off_y ); ?>">

			<?php if ( $show_filter && ! empty( $filter_cats ) ) : ?>
				<div class="pkae-vg__filter" style="justify-content:<?php echo esc_attr( $filter_align ); ?>;">
					<button class="pkae-vg__filter-btn pkae-active" data-filter="*"><?php echo esc_html( $filter_all ); ?></button>
					<?php foreach ( $filter_cats as $cat ) : ?>
						<button class="pkae-vg__filter-btn" data-filter="<?php echo esc_attr( sanitize_title( $cat ) ); ?>"><?php echo esc_html( $cat ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="pkae-vg__grid" style="<?php echo esc_attr( $grid_style ); ?>">
				<?php foreach ( $videos as $v ) :
					$type      = ! empty( $v['video_type'] ) ? $v['video_type'] : 'youtube';
					$url       = ! empty( $v['video_url'] ) ? $v['video_url'] : '';
					$title     = ! empty( $v['title'] ) ? $v['title'] : '';
					$desc      = ! empty( $v['description'] ) ? $v['description'] : '';
					$custom_th = ! empty( $v['custom_thumbnail']['url'] ) ? $v['custom_thumbnail']['url'] : '';
					$thumb     = $this->get_video_thumbnail( $type, $url, $custom_th );
					$embed_url = $this->get_video_embed_url( $type, $url );
					$filter_raw = ! empty( $v['filter_label'] ) ? $v['filter_label'] : '';
					$filter_cls = '';
					foreach ( array_map( 'trim', explode( ',', $filter_raw ) ) as $fc ) {
						if ( $fc ) $filter_cls .= ' pkae-filter-' . sanitize_title( $fc );
					}
					?>
					<div class="pkae-vg__item pkae-vg--hover-<?php echo esc_attr( $hover_anim ); ?><?php echo esc_attr( $filter_cls ); ?>"
						data-embed="<?php echo esc_attr( $embed_url ); ?>"
						style="aspect-ratio: <?php echo esc_attr( $aspect ); ?>;">

						<?php if ( $thumb ) : ?>
							<div class="pkae-vg__thumbnail">
								<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
								<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
							</div>
						<?php endif; ?>

						<div class="pkae-vg__overlay"></div>

						<button class="pkae-vg__play" aria-label="<?php esc_attr_e( 'Play video', 'powerkit-addons-for-elementor' ); ?>">
							<?php if ( ! empty( $s['play_icon']['value'] ) ) :
								Icons_Manager::render_icon( $s['play_icon'], [ 'aria-hidden' => 'true' ] );
							else : ?>
								<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
							<?php endif; ?>
						</button>

						<?php if ( $show_title && ( $title || $desc ) ) : ?>
							<div class="pkae-vg__info">
								<?php if ( $title ) : ?>
									<h4 class="pkae-vg__title"><?php echo esc_html( $title ); ?></h4>
								<?php endif; ?>
								<?php if ( $desc ) : ?>
									<p class="pkae-vg__desc"><?php echo esc_html( $desc ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					</div>
				<?php endforeach; ?>
			</div>

		</div>
		<?php
	}
}
