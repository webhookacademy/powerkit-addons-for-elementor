<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) exit;

class Image_Gallery extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-image-gallery', plugins_url( 'assets/css/pkae-image-gallery.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_script( 'pkae-image-gallery', plugins_url( 'assets/js/pkae-image-gallery.js', __FILE__ ), [ 'jquery' ], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	public function get_name()          { return 'pkae-image-gallery'; }
	public function get_title()         { return esc_html__( 'Image Gallery', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-gallery-grid'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-image-gallery' ]; }
	public function get_script_depends(){ return [ 'pkae-image-gallery' ]; }
	public function get_keywords()      { return [ 'image gallery', 'gallery', 'grid', 'masonry', 'lightbox', 'filter', 'powerkit' ]; }

	protected function register_controls() {

		// ── GALLERY ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_gallery', [
			'label' => esc_html__( 'Gallery', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
		] );

		$repeater->add_control( 'caption', [
			'label'       => esc_html__( 'Caption', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'filter_label', [
			'label'       => esc_html__( 'Filter Category', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'e.g. Nature, Travel', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Custom Link', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
		] );

		$this->add_control( 'gallery_items', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '<# if(caption){#>{{{caption}}}<#}else{#>Image<#}#>',
			'default'     => [
				[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ], 'caption' => 'Image 1' ],
				[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ], 'caption' => 'Image 2' ],
				[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ], 'caption' => 'Image 3' ],
				[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ], 'caption' => 'Image 4' ],
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'    => 'thumbnail',
			'default' => 'medium_large',
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
			'options'        => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ],
		] );

		$this->add_responsive_control( 'gap', [
			'label'     => esc_html__( 'Gap (px)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 10 ],
		] );

		$this->add_control( 'aspect_ratio', [
			'label'     => esc_html__( 'Aspect Ratio', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '1/1',
			'options'   => [
				'1/1'  => '1:1 Square',
				'4/3'  => '4:3',
				'16/9' => '16:9',
				'3/2'  => '3:2',
				'2/3'  => '2:3 Portrait',
				'auto' => esc_html__( 'Auto (Original)', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'layout' => 'grid' ],
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
			'label'     => esc_html__( '"All" Button Label', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'All', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_filter' => 'yes' ],
		] );

		$this->add_responsive_control( 'filter_align', [
			'label'     => esc_html__( 'Filter Alignment', 'powerkit-addons-for-elementor' ),
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

		// ── LIGHTBOX ──────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_lightbox', [
			'label' => esc_html__( 'Lightbox', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'lightbox', [
			'label'        => esc_html__( 'Enable Lightbox', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'lightbox_caption', [
			'label'        => esc_html__( 'Show Caption in Lightbox', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [ 'lightbox' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── OVERLAY ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_overlay', [
			'label' => esc_html__( 'Overlay', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_overlay', [
			'label'        => esc_html__( 'Show Overlay', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'overlay_icon', [
			'label'     => esc_html__( 'Overlay Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-search-plus', 'library' => 'fa-solid' ],
			'condition' => [ 'show_overlay' => 'yes' ],
		] );

		$this->add_control( 'show_caption', [
			'label'        => esc_html__( 'Show Caption on Hover', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [ 'show_overlay' => 'yes' ],
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
			'selector' => '{{WRAPPER}} .pkae-ig',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ig' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-ig',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ig' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-ig',
		] );

		$this->end_controls_section();

		// ── STYLE: Image ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_image', [
			'label' => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'image_border',
			'selector' => '{{WRAPPER}} .pkae-ig__item-img',
		] );

		$this->add_responsive_control( 'image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-ig__item-img'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .pkae-ig__item-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'image_shadow',
			'selector' => '{{WRAPPER}} .pkae-ig__item-wrap',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'image_css_filter',
			'selector' => '{{WRAPPER}} .pkae-ig__item-img',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'image_css_filter_hover',
			'label'    => esc_html__( 'CSS Filters (Hover)', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-ig__item-wrap:hover .pkae-ig__item-img',
		] );

		$this->end_controls_section();

		// ── STYLE: Overlay ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_overlay', [
			'label'     => esc_html__( 'Overlay', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_overlay' => 'yes' ],
		] );

		$this->add_control( 'overlay_color', [
			'label'     => esc_html__( 'Overlay Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.5)',
			'selectors' => [ '{{WRAPPER}} .pkae-ig__overlay' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'overlay_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .pkae-ig__overlay-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-ig__overlay-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'overlay_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 80 ] ],
			'default'   => [ 'size' => 24 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-ig__overlay-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-ig__overlay-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
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
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-ig__caption' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_typo',
			'selector' => '{{WRAPPER}} .pkae-ig__caption',
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ig__caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
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
			'selector' => '{{WRAPPER}} .pkae-ig__filter-btn',
		] );

		$this->add_responsive_control( 'filter_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '6', 'right' => '18', 'bottom' => '6', 'left' => '18', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ig__filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'filter_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ig__filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'filter_gap', [
			'label'     => esc_html__( 'Gap Between Buttons', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
			'default'   => [ 'size' => 8 ],
			'selectors' => [ '{{WRAPPER}} .pkae-ig__filter' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'filter_margin_bottom', [
			'label'     => esc_html__( 'Bottom Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'   => [ 'size' => 20 ],
			'selectors' => [ '{{WRAPPER}} .pkae-ig__filter' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'filter_tabs' );

		$this->start_controls_tab( 'filter_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'filter_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ig__filter-btn' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'filter_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ig__filter-btn' => 'background-color: {{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'filter_border',
			'selector' => '{{WRAPPER}} .pkae-ig__filter-btn',
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'filter_active', [ 'label' => esc_html__( 'Active', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'filter_active_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-ig__filter-btn.pkae-active' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'filter_active_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [ '{{WRAPPER}} .pkae-ig__filter-btn.pkae-active' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── STYLE: Lightbox Close Button ──────────────────────────────────────
		$this->start_controls_section( 'section_style_lb_close', [
			'label'     => esc_html__( 'Lightbox Close Button', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'lightbox' => 'yes' ],
		] );

		$this->add_control( 'lb_close_position_h', [
			'label'   => esc_html__( 'Horizontal Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'left'  => [ 'title' => 'Left',  'icon' => 'eicon-h-align-left' ],
				'right' => [ 'title' => 'Right', 'icon' => 'eicon-h-align-right' ],
			],
			'default'   => 'right',
			'toggle'    => false,
		] );

		$this->add_responsive_control( 'lb_close_offset_h', [
			'label'      => esc_html__( 'Horizontal Offset', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 1000 ], '%' => [ 'min' => 0, 'max' => 100 ], 'rem' => [ 'min' => 0, 'max' => 1000 ] ],
			'default'    => [ 'size' => 19, 'unit' => '%' ],
		] );

		$this->add_control( 'lb_close_position_v', [
			'label'   => esc_html__( 'Vertical Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'top'    => [ 'title' => 'Top',    'icon' => 'eicon-v-align-top' ],
				'bottom' => [ 'title' => 'Bottom', 'icon' => 'eicon-v-align-bottom' ],
			],
			'default' => 'top',
			'toggle'  => false,
		] );

		$this->add_responsive_control( 'lb_close_offset_v', [
			'label'      => esc_html__( 'Vertical Offset', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 1000 ], '%' => [ 'min' => 0, 'max' => 100 ], 'rem' => [ 'min' => 0, 'max' => 1000 ] ],
			'default'    => [ 'size' => 16, 'unit' => 'px' ],
		] );

		$this->add_responsive_control( 'lb_close_size', [
			'label'      => esc_html__( 'Button Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
			'default'    => [ 'size' => 36, 'unit' => 'px' ],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'lb_close_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
			'default'    => [ 'size' => 20, 'unit' => 'px' ],
		] );

		$this->add_control( 'lb_close_color', [
			'label'   => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#ffffff',
		] );

		$this->add_control( 'lb_close_bg', [
			'label'   => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(255,255,255,0.15)',
		] );

		$this->add_control( 'lb_close_bg_hover', [
			'label'   => esc_html__( 'Background (Hover)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(255,255,255,0.3)',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'lb_close_border',
			'selector' => '{{WRAPPER}}', // handled via JS inline style
		] );

		$this->add_responsive_control( 'lb_close_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px' ],
		] );

		$this->add_responsive_control( 'lb_close_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s             = $this->get_settings_for_display();
		$items         = ! empty( $s['gallery_items'] ) ? $s['gallery_items'] : [];
		$layout        = ! empty( $s['layout'] ) ? $s['layout'] : 'grid';
		$columns       = ! empty( $s['columns'] ) ? (int) $s['columns'] : 3;
		$col_tablet    = ! empty( $s['columns_tablet'] ) ? (int) $s['columns_tablet'] : 2;
		$col_mobile    = ! empty( $s['columns_mobile'] ) ? (int) $s['columns_mobile'] : 1;
		$gap           = isset( $s['gap']['size'] ) ? (int) $s['gap']['size'] : 10;
		$aspect_ratio  = ! empty( $s['aspect_ratio'] ) ? $s['aspect_ratio'] : '1/1';
		$show_filter   = isset( $s['show_filter'] ) && 'yes' === $s['show_filter'];
		$filter_all    = ! empty( $s['filter_all_label'] ) ? $s['filter_all_label'] : 'All';
		$filter_align  = ! empty( $s['filter_align'] ) ? $s['filter_align'] : 'center';
		$lightbox      = isset( $s['lightbox'] ) && 'yes' === $s['lightbox'];
		$lb_caption    = isset( $s['lightbox_caption'] ) && 'yes' === $s['lightbox_caption'];
		$show_overlay  = isset( $s['show_overlay'] ) && 'yes' === $s['show_overlay'];
		$show_caption  = isset( $s['show_caption'] ) && 'yes' === $s['show_caption'];
		$hover_anim    = ! empty( $s['hover_animation'] ) ? $s['hover_animation'] : 'zoom-in';

		// Collect unique filter categories
		$categories = [];
		foreach ( $items as $item ) {
			if ( ! empty( $item['filter_label'] ) ) {
				foreach ( array_map( 'trim', explode( ',', $item['filter_label'] ) ) as $cat ) {
					if ( $cat && ! in_array( $cat, $categories, true ) ) {
						$categories[] = $cat;
					}
				}
			}
		}

		$widget_id = 'pkae-ig-' . $this->get_id();
		$img_size  = $this->get_settings( 'thumbnail_size' ) ?: 'medium_large';

		$config = [
			'layout'    => $layout,
			'gap'       => $gap,
			'cols'      => $columns,
			'colsT'     => $col_tablet,
			'colsM'     => $col_mobile,
			'lightbox'  => $lightbox,
			'lbCaption' => $lb_caption,
			'lbClose'   => [
				'posH'    => ! empty( $s['lb_close_position_h'] ) ? $s['lb_close_position_h'] : 'right',
				'posV'    => ! empty( $s['lb_close_position_v'] ) ? $s['lb_close_position_v'] : 'top',
				'offH'    => isset( $s['lb_close_offset_h']['size'] ) ? $s['lb_close_offset_h']['size'] : 16,
				'offHU'   => isset( $s['lb_close_offset_h']['unit'] ) ? $s['lb_close_offset_h']['unit'] : 'px',
				'offV'    => isset( $s['lb_close_offset_v']['size'] ) ? $s['lb_close_offset_v']['size'] : 16,
				'offVU'   => isset( $s['lb_close_offset_v']['unit'] ) ? $s['lb_close_offset_v']['unit'] : 'px',
				'size'    => isset( $s['lb_close_size']['size'] ) ? $s['lb_close_size']['size'] : 36,
				'iconSz'  => isset( $s['lb_close_icon_size']['size'] ) ? $s['lb_close_icon_size']['size'] : 20,
				'color'   => ! empty( $s['lb_close_color'] ) ? $s['lb_close_color'] : '#ffffff',
				'bg'      => ! empty( $s['lb_close_bg'] ) ? $s['lb_close_bg'] : 'rgba(255,255,255,0.15)',
				'bgHover' => ! empty( $s['lb_close_bg_hover'] ) ? $s['lb_close_bg_hover'] : 'rgba(255,255,255,0.3)',
				'radius'  => isset( $s['lb_close_border_radius'] ) ? $s['lb_close_border_radius'] : [],
				'padding' => isset( $s['lb_close_padding'] ) ? $s['lb_close_padding'] : [],
			],
		];

		// Build close button CSS from settings
		$lb_pos_h   = ! empty( $s['lb_close_position_h'] ) ? $s['lb_close_position_h'] : 'right';
		$lb_pos_v   = ! empty( $s['lb_close_position_v'] ) ? $s['lb_close_position_v'] : 'top';
		$lb_off_h   = ( isset( $s['lb_close_offset_h']['size'] ) ? $s['lb_close_offset_h']['size'] : 16 ) . ( isset( $s['lb_close_offset_h']['unit'] ) ? $s['lb_close_offset_h']['unit'] : 'px' );
		$lb_off_v   = ( isset( $s['lb_close_offset_v']['size'] ) ? $s['lb_close_offset_v']['size'] : 16 ) . ( isset( $s['lb_close_offset_v']['unit'] ) ? $s['lb_close_offset_v']['unit'] : 'px' );
		$lb_size    = ( isset( $s['lb_close_size']['size'] ) ? $s['lb_close_size']['size'] : 36 ) . 'px';
		$lb_icon_sz = ( isset( $s['lb_close_icon_size']['size'] ) ? $s['lb_close_icon_size']['size'] : 20 ) . 'px';
		$lb_color   = ! empty( $s['lb_close_color'] ) ? $s['lb_close_color'] : '#ffffff';
		$lb_bg      = ! empty( $s['lb_close_bg'] ) ? $s['lb_close_bg'] : 'rgba(255,255,255,0.15)';
		$lb_bg_h    = ! empty( $s['lb_close_bg_hover'] ) ? $s['lb_close_bg_hover'] : 'rgba(255,255,255,0.3)';
		$r          = isset( $s['lb_close_border_radius'] ) && is_array( $s['lb_close_border_radius'] ) ? $s['lb_close_border_radius'] : [];
		$ru         = ! empty( $r['unit'] ) ? $r['unit'] : 'px';
		$lb_radius  = ! empty( $r ) ? ( ( $r['top'] ?? 4 ) . $ru . ' ' . ( $r['right'] ?? 4 ) . $ru . ' ' . ( $r['bottom'] ?? 4 ) . $ru . ' ' . ( $r['left'] ?? 4 ) . $ru ) : '4px';
		$p          = isset( $s['lb_close_padding'] ) && is_array( $s['lb_close_padding'] ) ? $s['lb_close_padding'] : [];
		$pu         = ! empty( $p['unit'] ) ? $p['unit'] : 'px';
		$lb_pad     = ! empty( $p ) ? ( ( $p['top'] ?? 0 ) . $pu . ' ' . ( $p['right'] ?? 0 ) . $pu . ' ' . ( $p['bottom'] ?? 0 ) . $pu . ' ' . ( $p['left'] ?? 0 ) . $pu ) : '0';
		$lb_opp_h   = 'right' === $lb_pos_h ? 'left' : 'right';
		$lb_opp_v   = 'top' === $lb_pos_v ? 'bottom' : 'top';

		// Pass widget_id to JS config for close button class
		$config['lbClose']['widgetId'] = $widget_id;
		?>

		<?php if ( $lightbox ) : ?>
		<style>
		.pkae-ig-lb-close-<?php echo esc_attr( $widget_id ); ?> {
			<?php echo esc_attr( $lb_pos_v ); ?>: <?php echo esc_attr( $lb_off_v ); ?> !important;
			<?php echo esc_attr( $lb_pos_h ); ?>: <?php echo esc_attr( $lb_off_h ); ?> !important;
			<?php echo esc_attr( $lb_opp_v ); ?>: auto !important;
			<?php echo esc_attr( $lb_opp_h ); ?>: auto !important;
			width: <?php echo esc_attr( $lb_size ); ?> !important;
			height: <?php echo esc_attr( $lb_size ); ?> !important;
			font-size: <?php echo esc_attr( $lb_icon_sz ); ?> !important;
			color: <?php echo esc_attr( $lb_color ); ?> !important;
			background: <?php echo esc_attr( $lb_bg ); ?> !important;
			border-radius: <?php echo esc_attr( $lb_radius ); ?> !important;
			padding: <?php echo esc_attr( $lb_pad ); ?> !important;
		}
		.pkae-ig-lb-close-<?php echo esc_attr( $widget_id ); ?>:hover {
			background: <?php echo esc_attr( $lb_bg_h ); ?> !important;
		}
		</style>
		<?php endif; ?>

		<div class="pkae-ig pkae-ig--<?php echo esc_attr( $layout ); ?> pkae-ig--hover-<?php echo esc_attr( $hover_anim ); ?>"
			id="<?php echo esc_attr( $widget_id ); ?>"
			data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">

			<?php if ( $show_filter && ! empty( $categories ) ) : ?>
				<div class="pkae-ig__filter" style="justify-content:<?php echo esc_attr( $filter_align ); ?>;">
					<button class="pkae-ig__filter-btn pkae-active" data-filter="*"><?php echo esc_html( $filter_all ); ?></button>
					<?php foreach ( $categories as $cat ) : ?>
						<button class="pkae-ig__filter-btn" data-filter="<?php echo esc_attr( sanitize_title( $cat ) ); ?>"><?php echo esc_html( $cat ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="pkae-ig__grid"
				style="--pkae-ig-cols:<?php echo esc_attr( $columns ); ?>;--pkae-ig-cols-t:<?php echo esc_attr( $col_tablet ); ?>;--pkae-ig-cols-m:<?php echo esc_attr( $col_mobile ); ?>;--pkae-ig-gap:<?php echo esc_attr( $gap ); ?>px;--pkae-ig-ratio:<?php echo esc_attr( $aspect_ratio ); ?>;">

				<?php foreach ( $items as $item ) :
					$img_url    = ! empty( $item['image']['url'] ) ? $item['image']['url'] : '';
					$img_id     = ! empty( $item['image']['id'] ) ? $item['image']['id'] : 0;
					$caption    = ! empty( $item['caption'] ) ? $item['caption'] : '';
					$link_url   = ! empty( $item['link']['url'] ) ? $item['link']['url'] : '';
					$link_ext   = ! empty( $item['link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
					$filter_raw = ! empty( $item['filter_label'] ) ? $item['filter_label'] : '';
					$filter_cls = '';
					if ( $filter_raw ) {
						foreach ( array_map( 'trim', explode( ',', $filter_raw ) ) as $fc ) {
							$filter_cls .= ' pkae-filter-' . sanitize_title( $fc );
						}
					}

					// Get proper sized image
					$img_src = $img_url;
					if ( $img_id ) {
						$img_data = wp_get_attachment_image_src( $img_id, $img_size );
						if ( $img_data ) $img_src = $img_data[0];
					}
					$full_src = $img_url;
					if ( $img_id ) {
						$full_data = wp_get_attachment_image_src( $img_id, 'full' );
						if ( $full_data ) $full_src = $full_data[0];
					}
					?>
					<div class="pkae-ig__item<?php echo esc_attr( $filter_cls ); ?>">
						<div class="pkae-ig__item-wrap">
							<?php
							$href = $lightbox ? $full_src : ( $link_url ?: '#' );
							$data_caption = $lb_caption && $caption ? ' data-caption="' . esc_attr( $caption ) . '"' : '';
							$lb_attr = $lightbox ? ' data-lightbox="pkae-ig-' . esc_attr( $this->get_id() ) . '"' . $data_caption : '';
							$no_lb_attr = ! $lightbox ? ' data-elementor-open-lightbox="no"' : '';
							?>
							<a href="<?php echo esc_url( $href ); ?>"<?php echo $link_url && ! $lightbox ? $link_ext : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $lb_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $no_lb_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="pkae-ig__item-link">
								<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
								<img class="pkae-ig__item-img" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $caption ); ?>" loading="lazy">

								<?php if ( $show_overlay ) : ?>
									<div class="pkae-ig__overlay">
										<?php if ( ! empty( $s['overlay_icon']['value'] ) ) : ?>
											<span class="pkae-ig__overlay-icon">
												<?php \Elementor\Icons_Manager::render_icon( $s['overlay_icon'], [ 'aria-hidden' => 'true' ] ); ?>
											</span>
										<?php endif; ?>
										<?php if ( $show_caption && $caption ) : ?>
											<span class="pkae-ig__caption"><?php echo esc_html( $caption ); ?></span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
		<?php
	}
}
