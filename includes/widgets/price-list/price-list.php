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
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit;

class Price_List extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-price-list', plugins_url( 'assets/css/pkae-price-list.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
	}

	public function get_name()          { return 'pkae-price-list'; }
	public function get_title()         { return esc_html__( 'Price List', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-price-list'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-price-list' ]; }
	public function get_keywords()      { return [ 'price list', 'menu', 'pricing', 'food menu', 'catalog', 'powerkit' ]; }

	protected function register_controls() {

		// ── ITEMS ─────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_items', [
			'label' => esc_html__( 'Price List Items', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'item_title', [
			'label'       => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Item Title', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'item_desc', [
			'label'   => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'dynamic' => [ 'active' => true ],
		] );

		$repeater->add_control( 'item_price', [
			'label'   => esc_html__( 'Price', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '$9.99',
			'dynamic' => [ 'active' => true ],
		] );

		$repeater->add_control( 'item_original_price', [
			'label'       => esc_html__( 'Original Price (strikethrough)', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => '$19.99',
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'item_badge', [
			'label'       => esc_html__( 'Badge Label', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'New / Hot / Sale', 'powerkit-addons-for-elementor' ),
		] );

		$repeater->add_control( 'item_image', [
			'label'     => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::MEDIA,
			'separator' => 'before',
		] );

		$repeater->add_control( 'item_icon', [
			'label'   => esc_html__( 'Icon (if no image)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => '', 'library' => '' ],
		] );

		$repeater->add_control( 'item_link', [
			'label'       => esc_html__( 'Link', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
			'separator'   => 'before',
		] );

		$repeater->add_control( 'item_highlight', [
			'label'        => esc_html__( 'Highlight Item', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
			'separator'    => 'before',
		] );

		$this->add_control( 'items', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ item_title }}}',
			'default'     => [
				[ 'item_title' => 'Espresso',    'item_price' => '$3.50', 'item_desc' => 'Rich and bold single shot.' ],
				[ 'item_title' => 'Cappuccino',  'item_price' => '$4.50', 'item_desc' => 'Espresso with steamed milk foam.' ],
				[ 'item_title' => 'Latte',       'item_price' => '$5.00', 'item_desc' => 'Smooth espresso with milk.' ],
				[ 'item_title' => 'Cold Brew',   'item_price' => '$5.50', 'item_desc' => 'Slow-steeped for 12 hours.' ],
			],
		] );

		$this->end_controls_section();

		// ── SETTINGS ──────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_settings', [
			'label' => esc_html__( 'Settings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'list',
			'options' => [
				'list' => esc_html__( 'List (Stacked)', 'powerkit-addons-for-elementor' ),
				'grid' => esc_html__( 'Grid (Cards)', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_responsive_control( 'columns', [
			'label'          => esc_html__( 'Columns', 'powerkit-addons-for-elementor' ),
			'type'           => Controls_Manager::SELECT,
			'default'        => '2',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'options'        => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
			'condition'      => [ 'layout' => 'grid' ],
		] );

		$this->add_control( 'image_position', [
			'label'   => esc_html__( 'Image Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'top'   => [ 'title' => 'Top',   'icon' => 'eicon-v-align-top' ],
				'left'  => [ 'title' => 'Left',  'icon' => 'eicon-h-align-left' ],
				'right' => [ 'title' => 'Right', 'icon' => 'eicon-h-align-right' ],
			],
			'default' => 'right',
			'toggle'  => false,
		] );

		$this->add_control( 'show_separator', [
			'label'        => esc_html__( 'Show Separator', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [ 'layout' => 'list' ],
		] );

		$this->add_control( 'separator_style', [
			'label'     => esc_html__( 'Separator Style', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'dashed',
			'options'   => [ 'solid' => 'Solid', 'dashed' => 'Dashed', 'dotted' => 'Dotted', 'double' => 'Double' ],
			'condition' => [ 'show_separator' => 'yes', 'layout' => 'list' ],
			'selectors' => [ '{{WRAPPER}} .pkae-pl__sep' => 'border-bottom-style: {{VALUE}};' ],
		] );

		$this->add_control( 'title_tag', [
			'label'   => esc_html__( 'Title Tag', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'h4',
			'options' => [ 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'p' => 'p', 'span' => 'span' ],
		] );

		$this->add_control( 'price_position', [
			'label'   => esc_html__( 'Price Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'beside_title',
			'options' => [
				'before_title' => esc_html__( 'Before Title', 'powerkit-addons-for-elementor' ),
				'beside_title' => esc_html__( 'Beside Title (default)', 'powerkit-addons-for-elementor' ),
				'below_title'  => esc_html__( 'Below Title', 'powerkit-addons-for-elementor' ),
				'below_desc'   => esc_html__( 'Below Description', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_responsive_control( 'content_align', [
			'label'     => esc_html__( 'Content Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'      => 'left',
			'prefix_class' => 'pkae-pl-align-',
			'selectors'    => [
				'{{WRAPPER}} .pkae-pl__body' => 'text-align: {{VALUE}};',
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
			'selector' => '{{WRAPPER}} .pkae-pl',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-pl',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-pl',
		] );

		$this->end_controls_section();

		// ── STYLE: Item ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_item', [
			'label' => esc_html__( 'Item', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'item_gap', [
			'label'     => esc_html__( 'Gap Between Items', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'   => [ 'size' => 10 ],
			'selectors' => [ '{{WRAPPER}} .pkae-pl__list' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_padding', [
			'label'      => esc_html__( 'Item Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '16', 'right' => '10', 'bottom' => '16', 'left' => '10', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'item_tabs' );
		$this->start_controls_tab( 'item_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'item_bg',
			'selector' => '{{WRAPPER}} .pkae-pl__item',
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'item_border',
			'selector' => '{{WRAPPER}} .pkae-pl__item',
		] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'item_shadow',
			'selector' => '{{WRAPPER}} .pkae-pl__item',
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'item_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'item_bg_hover',
			'selector' => '{{WRAPPER}} .pkae-pl__item:hover',
		] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'item_shadow_hover',
			'selector' => '{{WRAPPER}} .pkae-pl__item:hover',
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'item_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'separator'  => 'before',
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'item_transition', [
			'label'     => esc_html__( 'Transition (s)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ] ],
			'default'   => [ 'size' => 0.3 ],
			'selectors' => [ '{{WRAPPER}} .pkae-pl__item' => 'transition: all {{SIZE}}s ease;' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Image ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_image', [
			'label' => esc_html__( 'Image / Icon', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_size', [
			'label'      => esc_html__( 'Image Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 600 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
			'default'    => [ 'size' => 80, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-pl--img-left .pkae-pl__img img,
				 {{WRAPPER}} .pkae-pl--img-right .pkae-pl__img img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-pl__icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'image_top_height', [
			'label'      => esc_html__( 'Image Height (Top layout)', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh', 'em' ],
			'range'      => [ 'px' => [ 'min' => 50, 'max' => 600 ] ],
			'default'    => [ 'size' => 220, 'unit' => 'px' ],
			'condition'  => [ 'image_position' => 'top' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl--img-top .pkae-pl__img' => 'height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'image_fit', [
			'label'     => esc_html__( 'Image Fit', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'cover',
			'options'   => [
				'cover'   => 'Cover',
				'contain' => 'Contain',
				'fill'    => 'Fill',
				'none'    => 'None',
			],
			'selectors' => [ '{{WRAPPER}} .pkae-pl__img img' => 'object-fit: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'image_spacing', [
			'label'     => esc_html__( 'Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 16 ],
			'selectors' => [ '{{WRAPPER}} .pkae-pl__media' => 'margin-right: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Title ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_title', [
			'label' => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-pl__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'title_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-pl__item:hover .pkae-pl__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-pl__title',
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Description ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_desc', [
			'label' => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'desc_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-pl__desc' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'desc_typo',
			'selector' => '{{WRAPPER}} .pkae-pl__desc',
		] );

		$this->end_controls_section();

		// ── STYLE: Price ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_price', [
			'label' => esc_html__( 'Price', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'price_color', [
			'label'     => esc_html__( 'Price Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [ '{{WRAPPER}} .pkae-pl__price' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'price_typo',
			'selector' => '{{WRAPPER}} .pkae-pl__price',
		] );

		$this->add_control( 'price_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-pl__price' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'price_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'price_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'original_price_color', [
			'label'     => esc_html__( 'Original Price Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#999',
			'separator' => 'before',
			'selectors' => [ '{{WRAPPER}} .pkae-pl__original-price' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'original_price_typo',
			'selector' => '{{WRAPPER}} .pkae-pl__original-price',
		] );

		$this->end_controls_section();

		// ── STYLE: Separator ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_sep', [
			'label'     => esc_html__( 'Separator', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_separator' => 'yes', 'layout' => 'list' ],
		] );

		$this->add_control( 'sep_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e5e5e5',
			'selectors' => [ '{{WRAPPER}} .pkae-pl__sep' => 'border-bottom-color: {{VALUE}};' ],
		] );

		$this->add_control( 'sep_weight', [
			'label'     => esc_html__( 'Weight (px)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1, 'max' => 10 ] ],
			'default'   => [ 'size' => 1 ],
			'selectors' => [ '{{WRAPPER}} .pkae-pl__sep' => 'border-bottom-width: {{SIZE}}px;' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Badge ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_badge', [
			'label' => esc_html__( 'Badge', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'badge_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-pl__badge' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'badge_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e74c3c',
			'selectors' => [ '{{WRAPPER}} .pkae-pl__badge' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge_typo',
			'selector' => '{{WRAPPER}} .pkae-pl__badge',
		] );

		$this->add_responsive_control( 'badge_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '2', 'right' => '8', 'bottom' => '2', 'left' => '8', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'badge_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-pl__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Highlight ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_highlight', [
			'label' => esc_html__( 'Highlighted Item', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'highlight_bg',
			'selector' => '{{WRAPPER}} .pkae-pl__item--highlight',
		] );

		$this->add_control( 'highlight_title_color', [
			'label'     => esc_html__( 'Title Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-pl__item--highlight .pkae-pl__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'highlight_price_color', [
			'label'     => esc_html__( 'Price Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-pl__item--highlight .pkae-pl__price' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'highlight_border',
			'selector' => '{{WRAPPER}} .pkae-pl__item--highlight',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s             = $this->get_settings_for_display();
		$items         = ! empty( $s['items'] ) ? $s['items'] : [];
		$layout        = ! empty( $s['layout'] ) ? $s['layout'] : 'list';
		$columns       = ! empty( $s['columns'] ) ? (int) $s['columns'] : 2;
		$col_t         = ! empty( $s['columns_tablet'] ) ? (int) $s['columns_tablet'] : 2;
		$col_m         = ! empty( $s['columns_mobile'] ) ? (int) $s['columns_mobile'] : 1;
		$img_pos       = ! empty( $s['image_position'] ) ? $s['image_position'] : 'right';
		$show_sep      = isset( $s['show_separator'] ) && 'yes' === $s['show_separator'];
		$title_tag     = ! empty( $s['title_tag'] ) ? $s['title_tag'] : 'h4';
		$price_pos     = ! empty( $s['price_position'] ) ? $s['price_position'] : 'beside_title';
		$allowed_tags  = [ 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span' ];
		$title_tag     = in_array( $title_tag, $allowed_tags, true ) ? $title_tag : 'h4';

		Icons_Manager::enqueue_shim();

		$grid_style = 'grid';
		if ( 'grid' === $layout ) {
			$grid_style = '--pkae-pl-cols:' . $columns . ';--pkae-pl-cols-t:' . $col_t . ';--pkae-pl-cols-m:' . $col_m . ';';
		}
		?>
		<div class="pkae-pl pkae-pl--<?php echo esc_attr( $layout ); ?> pkae-pl--img-<?php echo esc_attr( $img_pos ); ?>">
			<div class="pkae-pl__list" <?php echo 'grid' === $layout ? 'style="' . esc_attr( $grid_style ) . '"' : ''; ?>>
				<?php
				$total = count( $items );
				foreach ( $items as $idx => $item ) :
					$title     = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
					$desc      = ! empty( $item['item_desc'] ) ? $item['item_desc'] : '';
					$price     = ! empty( $item['item_price'] ) ? $item['item_price'] : '';
					$orig      = ! empty( $item['item_original_price'] ) ? $item['item_original_price'] : '';
					$badge     = ! empty( $item['item_badge'] ) ? $item['item_badge'] : '';
					$img_url   = ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '';
					$link_url  = ! empty( $item['item_link']['url'] ) ? $item['item_link']['url'] : '';
					$link_ext  = ! empty( $item['item_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
					$highlight = isset( $item['item_highlight'] ) && 'yes' === $item['item_highlight'];
					$has_icon  = ! empty( $item['item_icon']['value'] );

					$item_class = 'pkae-pl__item';
					if ( $highlight ) $item_class .= ' pkae-pl__item--highlight';
					if ( $link_url )  $item_class .= ' pkae-pl__item--linked';

					$wrap_tag   = $link_url ? 'a' : 'div';
					$wrap_attrs = $link_url ? ' href="' . esc_url( $link_url ) . '"' . $link_ext : '';
					?>
					<<?php echo esc_attr( $wrap_tag ); ?> class="<?php echo esc_attr( $item_class ); ?>"<?php echo $wrap_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

						<?php // Image/Icon — left position ?>
						<?php if ( in_array( $img_pos, [ 'left', 'top' ], true ) && ( $img_url || $has_icon ) ) : ?>
							<div class="pkae-pl__media">
								<?php if ( $img_url ) : ?>
									<div class="pkae-pl__img">
										<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
										<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
									</div>
								<?php elseif ( $has_icon ) : ?>
									<div class="pkae-pl__icon">
										<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<div class="pkae-pl__body">
							<?php // Price: before title ?>
							<?php if ( 'before_title' === $price_pos ) : ?>
								<div class="pkae-pl__price-wrap">
									<?php if ( $orig ) : ?><span class="pkae-pl__original-price"><?php echo esc_html( $orig ); ?></span><?php endif; ?>
									<?php if ( $price ) : ?><span class="pkae-pl__price"><?php echo esc_html( $price ); ?></span><?php endif; ?>
								</div>
							<?php endif; ?>

							<div class="pkae-pl__header">
								<div class="pkae-pl__title-wrap">
									<?php if ( $title ) : ?>
										<<?php echo esc_attr( $title_tag ); ?> class="pkae-pl__title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
									<?php endif; ?>
									<?php if ( $badge ) : ?>
										<span class="pkae-pl__badge"><?php echo esc_html( $badge ); ?></span>
									<?php endif; ?>
								</div>
								<?php // Price: beside title (default) ?>
								<?php if ( 'beside_title' === $price_pos ) : ?>
									<div class="pkae-pl__price-wrap">
										<?php if ( $orig ) : ?><span class="pkae-pl__original-price"><?php echo esc_html( $orig ); ?></span><?php endif; ?>
										<?php if ( $price ) : ?><span class="pkae-pl__price"><?php echo esc_html( $price ); ?></span><?php endif; ?>
									</div>
								<?php endif; ?>
							</div>

							<?php // Price: below title ?>
							<?php if ( 'below_title' === $price_pos ) : ?>
								<div class="pkae-pl__price-wrap">
									<?php if ( $orig ) : ?><span class="pkae-pl__original-price"><?php echo esc_html( $orig ); ?></span><?php endif; ?>
									<?php if ( $price ) : ?><span class="pkae-pl__price"><?php echo esc_html( $price ); ?></span><?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ( $desc ) : ?>
								<p class="pkae-pl__desc"><?php echo esc_html( $desc ); ?></p>
							<?php endif; ?>

							<?php // Price: below description ?>
							<?php if ( 'below_desc' === $price_pos ) : ?>
								<div class="pkae-pl__price-wrap">
									<?php if ( $orig ) : ?><span class="pkae-pl__original-price"><?php echo esc_html( $orig ); ?></span><?php endif; ?>
									<?php if ( $price ) : ?><span class="pkae-pl__price"><?php echo esc_html( $price ); ?></span><?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php // Image/Icon — right position ?>
						<?php if ( 'right' === $img_pos && ( $img_url || $has_icon ) ) : ?>
							<div class="pkae-pl__media pkae-pl__media--right">
								<?php if ( $img_url ) : ?>
									<div class="pkae-pl__img">
										<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
										<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
									</div>
								<?php elseif ( $has_icon ) : ?>
									<div class="pkae-pl__icon">
										<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					</<?php echo esc_attr( $wrap_tag ); ?>>

					<?php if ( $show_sep && 'list' === $layout && $idx < $total - 1 ) : ?>
						<div class="pkae-pl__sep"></div>
					<?php endif; ?>

				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
