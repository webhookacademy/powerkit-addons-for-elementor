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
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) exit;

class Info_Box extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-info-box', plugins_url( 'assets/css/pkae-info-box.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
	}

	public function get_name()          { return 'pkae-info-box'; }
	public function get_title()         { return esc_html__( 'Info Box', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-info-box'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-info-box' ]; }
	public function get_keywords()      { return [ 'info box', 'infobox', 'icon box', 'feature box', 'service', 'powerkit' ]; }

	protected function register_controls() {

		// ── ICON / IMAGE ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_icon', [
			'label' => esc_html__( 'Icon / Image', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'icon_type', [
			'label'   => esc_html__( 'Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'none'  => [ 'title' => 'None',  'icon' => 'eicon-ban' ],
				'icon'  => [ 'title' => 'Icon',  'icon' => 'eicon-star' ],
				'image' => [ 'title' => 'Image', 'icon' => 'eicon-image' ],
				'text'  => [ 'title' => 'Text',  'icon' => 'eicon-t-letter' ],
			],
			'default' => 'icon',
			'toggle'  => false,
		] );

		$this->add_control( 'icon', [
			'label'     => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
			'condition' => [ 'icon_type' => 'icon' ],
		] );

		$this->add_control( 'image', [
			'label'     => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => Utils::get_placeholder_image_src() ],
			'condition' => [ 'icon_type' => 'image' ],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image',
			'default'   => 'thumbnail',
			'condition' => [ 'icon_type' => 'image' ],
		] );

		$this->add_control( 'icon_text', [
			'label'     => esc_html__( 'Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '01',
			'condition' => [ 'icon_type' => 'text' ],
		] );

		$this->add_control( 'icon_position', [
			'label'     => esc_html__( 'Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'top'   => [ 'title' => 'Top',   'icon' => 'eicon-v-align-top' ],
				'left'  => [ 'title' => 'Left',  'icon' => 'eicon-h-align-left' ],
				'right' => [ 'title' => 'Right', 'icon' => 'eicon-h-align-right' ],
			],
			'default'   => 'top',
			'toggle'    => false,
			'condition' => [ 'icon_type!' => 'none' ],
		] );

		$this->end_controls_section();

		// ── CONTENT ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Content', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'title_prefix', [
			'label'       => esc_html__( 'Title Prefix', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'e.g. Step 01', 'powerkit-addons-for-elementor' ),
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'title', [
			'label'   => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Info Box Title', 'powerkit-addons-for-elementor' ),
			'dynamic' => [ 'active' => true ],
			'label_block' => true,
		] );

		$this->add_control( 'title_tag', [
			'label'   => esc_html__( 'Title Tag', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'h3',
			'options' => [ 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'p' => 'p', 'span' => 'span' ],
		] );

		$this->add_control( 'description', [
			'label'   => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Add a short description for this info box.', 'powerkit-addons-for-elementor' ),
			'rows'    => 4,
			'dynamic' => [ 'active' => true ],
		] );

		$this->end_controls_section();

		// ── SEPARATOR ─────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_separator', [
			'label' => esc_html__( 'Separator', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_separator', [
			'label'        => esc_html__( 'Show Separator', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'separator_position', [
			'label'     => esc_html__( 'Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'after_title',
			'options'   => [
				'after_icon'  => esc_html__( 'After Icon/Image', 'powerkit-addons-for-elementor' ),
				'after_title' => esc_html__( 'After Title', 'powerkit-addons-for-elementor' ),
				'after_desc'  => esc_html__( 'After Description', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_separator' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── CTA ───────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_cta', [
			'label' => esc_html__( 'Call To Action', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'cta_type', [
			'label'   => esc_html__( 'Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none'   => esc_html__( 'None', 'powerkit-addons-for-elementor' ),
				'text'   => esc_html__( 'Text Link', 'powerkit-addons-for-elementor' ),
				'button' => esc_html__( 'Button', 'powerkit-addons-for-elementor' ),
				'box'    => esc_html__( 'Complete Box', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'cta_text', [
			'label'     => esc_html__( 'Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Read More', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'cta_type' => [ 'text', 'button' ] ],
		] );

		$this->add_control( 'cta_link', [
			'label'       => esc_html__( 'Link', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
			'default'     => [ 'url' => '#' ],
			'condition'   => [ 'cta_type!' => 'none' ],
		] );

		$this->add_control( 'cta_icon', [
			'label'     => esc_html__( 'Button Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => '', 'library' => '' ],
			'condition' => [ 'cta_type' => 'button' ],
		] );

		$this->add_control( 'cta_icon_position', [
			'label'     => esc_html__( 'Icon Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'after',
			'options'   => [
				'before' => esc_html__( 'Before Text', 'powerkit-addons-for-elementor' ),
				'after'  => esc_html__( 'After Text', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'cta_type' => 'button', 'cta_icon[value]!' => '' ],
		] );

		$this->end_controls_section();

		// ── BADGE ─────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_badge', [
			'label' => esc_html__( 'Badge', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_badge', [
			'label'        => esc_html__( 'Show Badge', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'badge_text', [
			'label'     => esc_html__( 'Badge Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'New', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_badge' => 'yes' ],
		] );

		$this->add_control( 'badge_position', [
			'label'     => esc_html__( 'Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'top-right',
			'options'   => [
				'top-left'     => esc_html__( 'Top Left', 'powerkit-addons-for-elementor' ),
				'top-right'    => esc_html__( 'Top Right', 'powerkit-addons-for-elementor' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'powerkit-addons-for-elementor' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_badge' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── RIBBON ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_ribbon', [
			'label' => esc_html__( 'Ribbon', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_ribbon', [
			'label'        => esc_html__( 'Show Ribbon', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'ribbon_text', [
			'label'     => esc_html__( 'Ribbon Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Popular', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_ribbon' => 'yes' ],
		] );

		$this->add_control( 'ribbon_position', [
			'label'     => esc_html__( 'Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'  => [ 'title' => 'Left',  'icon' => 'eicon-h-align-left' ],
				'right' => [ 'title' => 'Right', 'icon' => 'eicon-h-align-right' ],
			],
			'default'   => 'right',
			'toggle'    => false,
			'condition' => [ 'show_ribbon' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Box ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'      => 'center',
			'prefix_class' => 'pkae-ib-align-',
			'selectors'    => [
				'{{WRAPPER}} .pkae-ib' => 'text-align: {{VALUE}};',
			],
		] );

		$this->start_controls_tabs( 'box_tabs' );
		$this->start_controls_tab( 'box_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_bg',
			'selector' => '{{WRAPPER}} .pkae-ib',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-ib',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-ib',
		] );

		$this->end_controls_tab();
		$this->start_controls_tab( 'box_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_bg_hover',
			'selector' => '{{WRAPPER}} .pkae-ib:hover',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border_hover',
			'selector' => '{{WRAPPER}} .pkae-ib:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow_hover',
			'selector' => '{{WRAPPER}} .pkae-ib:hover',
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'separator'  => 'before',
			'selectors'  => [ '{{WRAPPER}} .pkae-ib' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'box_transition', [
			'label'     => esc_html__( 'Transition Duration (s)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 3, 'step' => 0.1 ] ],
			'default'   => [ 'size' => 0.3 ],
			'selectors' => [ '{{WRAPPER}} .pkae-ib' => 'transition: all {{SIZE}}s ease;' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Icon ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_icon', [
			'label'     => esc_html__( 'Icon Style', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'icon_type' => 'icon' ],
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'      => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
			'default'    => [ 'size' => 40, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-ib__icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-ib__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_box_size', [
			'label'      => esc_html__( 'Box Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 300 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'icon_tabs' );
		$this->start_controls_tab( 'icon_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'icon_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-ib__icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-ib__icon svg' => 'fill: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'icon_bg', 'selector' => '{{WRAPPER}} .pkae-ib__icon' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'icon_border', 'selector' => '{{WRAPPER}} .pkae-ib__icon' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'icon_shadow', 'selector' => '{{WRAPPER}} .pkae-ib__icon' ] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'icon_color_hover', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-ib:hover .pkae-ib__icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-ib:hover .pkae-ib__icon svg' => 'fill: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'icon_bg_hover', 'selector' => '{{WRAPPER}} .pkae-ib:hover .pkae-ib__icon' ] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'icon_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'separator'  => 'before',
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'icon_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'icon_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'icon_vertical_align', [
			'label'     => esc_html__( 'Vertical Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Top',    'icon' => 'eicon-v-align-top' ],
				'center'     => [ 'title' => 'Middle', 'icon' => 'eicon-v-align-middle' ],
				'flex-end'   => [ 'title' => 'Bottom', 'icon' => 'eicon-v-align-bottom' ],
			],
			'default'   => 'flex-start',
			'condition' => [ 'icon_position' => [ 'left', 'right' ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-ib__icon-wrap' => 'align-self: {{VALUE}};' ],
		] );
		$this->add_control( 'icon_hover_animation', [
			'label'   => esc_html__( 'Hover Animation', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [ 'none' => 'None', 'grow' => 'Grow', 'shrink' => 'Shrink', 'rotate' => 'Rotate', 'float' => 'Float', 'bounce' => 'Bounce' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Image ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_image', [
			'label'     => esc_html__( 'Image Style', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'icon_type' => 'image' ],
		] );

		$this->add_responsive_control( 'img_width', [
			'label'      => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 600 ], '%' => [ 'min' => 1, 'max' => 100 ] ],
			'default'    => [ 'size' => 80, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'img_height', [
			'label'      => esc_html__( 'Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 600 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;' ],
		] );

		$this->add_responsive_control( 'img_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'img_tabs' );
		$this->start_controls_tab( 'img_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'img_border', 'selector' => '{{WRAPPER}} .pkae-ib__icon img' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'img_shadow', 'selector' => '{{WRAPPER}} .pkae-ib__icon img' ] );
		$this->add_group_control( Group_Control_Css_Filter::get_type(), [ 'name' => 'img_filter', 'selector' => '{{WRAPPER}} .pkae-ib__icon img' ] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'img_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_group_control( Group_Control_Css_Filter::get_type(), [ 'name' => 'img_filter_hover', 'selector' => '{{WRAPPER}} .pkae-ib:hover .pkae-ib__icon img' ] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'img_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'separator'  => 'before',
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'img_vertical_align', [
			'label'     => esc_html__( 'Vertical Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Top',    'icon' => 'eicon-v-align-top' ],
				'center'     => [ 'title' => 'Middle', 'icon' => 'eicon-v-align-middle' ],
				'flex-end'   => [ 'title' => 'Bottom', 'icon' => 'eicon-v-align-bottom' ],
			],
			'default'   => 'flex-start',
			'condition' => [ 'icon_position' => [ 'left', 'right' ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-ib__icon-wrap' => 'align-self: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Text ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_icon_text', [
			'label'     => esc_html__( 'Text Style', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'icon_type' => 'text' ],
		] );

		$this->add_responsive_control( 'icon_text_size', [
			'label'      => esc_html__( 'Font Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
			'default'    => [ 'size' => 40, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon-text' => 'font-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'icon_text_typo',
			'selector' => '{{WRAPPER}} .pkae-ib__icon-text',
		] );

		$this->add_responsive_control( 'icon_text_box_size', [
			'label'      => esc_html__( 'Box Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 300 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'icon_text_tabs' );
		$this->start_controls_tab( 'icon_text_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'icon_text_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__icon-text' => 'color: {{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'icon_text_bg', 'selector' => '{{WRAPPER}} .pkae-ib__icon' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'icon_text_border', 'selector' => '{{WRAPPER}} .pkae-ib__icon' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'icon_text_shadow', 'selector' => '{{WRAPPER}} .pkae-ib__icon' ] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_text_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'icon_text_color_hover', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib:hover .pkae-ib__icon-text' => 'color: {{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'icon_text_bg_hover', 'selector' => '{{WRAPPER}} .pkae-ib:hover .pkae-ib__icon' ] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'icon_text_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'separator'  => 'before',
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'icon_text_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'icon_text_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Title Prefix ───────────────────────────────────────────────
		$this->start_controls_section( 'section_style_prefix', [
			'label'     => esc_html__( 'Title Prefix', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'title_prefix!' => '' ],
		] );

		$this->add_control( 'prefix_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__prefix' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'prefix_typo',
			'selector' => '{{WRAPPER}} .pkae-ib__prefix',
		] );

		$this->add_responsive_control( 'prefix_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
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
			'selectors' => [ '{{WRAPPER}} .pkae-ib__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'title_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib:hover .pkae-ib__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-ib__title',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'title_shadow',
			'selector' => '{{WRAPPER}} .pkae-ib__title',
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Description ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_desc', [
			'label'     => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'description!' => '' ],
		] );

		$this->add_control( 'desc_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__desc' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'desc_typo',
			'selector' => '{{WRAPPER}} .pkae-ib__desc',
		] );

		$this->add_responsive_control( 'desc_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Separator ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_sep', [
			'label'     => esc_html__( 'Separator', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_separator' => 'yes' ],
		] );

		$this->add_control( 'sep_style', [
			'label'   => esc_html__( 'Style', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'solid',
			'options' => [ 'solid' => 'Solid', 'dashed' => 'Dashed', 'dotted' => 'Dotted', 'double' => 'Double' ],
			'selectors' => [ '{{WRAPPER}} .pkae-ib__sep' => 'border-top-style: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'sep_width', [
			'label'      => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'size' => 40, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__sep' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'sep_thickness', [
			'label'     => esc_html__( 'Thickness', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1, 'max' => 20 ] ],
			'default'   => [ 'size' => 2 ],
			'selectors' => [ '{{WRAPPER}} .pkae-ib__sep' => 'border-top-width: {{SIZE}}px;' ],
		] );

		$this->add_control( 'sep_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__sep' => 'border-top-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'sep_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__sep' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: CTA ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_cta', [
			'label'     => esc_html__( 'Call To Action', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'cta_type!' => 'none' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'cta_typo',
			'selector' => '{{WRAPPER}} .pkae-ib__cta',
		] );

		$this->add_responsive_control( 'cta_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'cta_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'cta_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__cta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'cta_tabs' );
		$this->start_controls_tab( 'cta_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'cta_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__cta' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'cta_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__cta' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'cta_border',
			'selector' => '{{WRAPPER}} .pkae-ib__cta',
		] );

		$this->end_controls_tab();
		$this->start_controls_tab( 'cta_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'cta_color_hover', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__cta:hover' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'cta_bg_hover', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ib__cta:hover' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── STYLE: Badge ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_badge', [
			'label'     => esc_html__( 'Badge', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_badge' => 'yes' ],
		] );

		$this->add_control( 'badge_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-ib__badge' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'badge_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e74c3c',
			'selectors' => [ '{{WRAPPER}} .pkae-ib__badge' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge_typo',
			'selector' => '{{WRAPPER}} .pkae-ib__badge',
		] );

		$this->add_responsive_control( 'badge_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '3', 'right' => '10', 'bottom' => '3', 'left' => '10', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'badge_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ib__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Ribbon ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_ribbon', [
			'label'     => esc_html__( 'Ribbon', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_ribbon' => 'yes' ],
		] );

		$this->add_control( 'ribbon_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-ib__ribbon' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'ribbon_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [ '{{WRAPPER}} .pkae-ib__ribbon' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'ribbon_typo',
			'selector' => '{{WRAPPER}} .pkae-ib__ribbon',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s           = $this->get_settings_for_display();
		$icon_type   = ! empty( $s['icon_type'] ) ? $s['icon_type'] : 'icon';
		$icon_pos    = ! empty( $s['icon_position'] ) ? $s['icon_position'] : 'top';
		$title       = ! empty( $s['title'] ) ? $s['title'] : '';
		$title_tag   = ! empty( $s['title_tag'] ) ? $s['title_tag'] : 'h3';
		$prefix      = ! empty( $s['title_prefix'] ) ? $s['title_prefix'] : '';
		$desc        = ! empty( $s['description'] ) ? $s['description'] : '';
		$show_sep    = isset( $s['show_separator'] ) && 'yes' === $s['show_separator'];
		$sep_pos     = ! empty( $s['separator_position'] ) ? $s['separator_position'] : 'after_title';
		$cta_type    = ! empty( $s['cta_type'] ) ? $s['cta_type'] : 'none';
		$cta_text    = ! empty( $s['cta_text'] ) ? $s['cta_text'] : '';
		$cta_url     = ! empty( $s['cta_link']['url'] ) ? $s['cta_link']['url'] : '#';
		$cta_ext     = ! empty( $s['cta_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		$cta_nofollow = ! empty( $s['cta_link']['nofollow'] ) ? ' rel="nofollow"' : '';
		$icon_anim   = ! empty( $s['icon_hover_animation'] ) ? $s['icon_hover_animation'] : 'none';
		$show_badge  = isset( $s['show_badge'] ) && 'yes' === $s['show_badge'];
		$badge_text  = ! empty( $s['badge_text'] ) ? $s['badge_text'] : '';
		$badge_pos   = ! empty( $s['badge_position'] ) ? $s['badge_position'] : 'top-right';
		$show_ribbon = isset( $s['show_ribbon'] ) && 'yes' === $s['show_ribbon'];
		$ribbon_text = ! empty( $s['ribbon_text'] ) ? $s['ribbon_text'] : '';
		$ribbon_pos  = ! empty( $s['ribbon_position'] ) ? $s['ribbon_position'] : 'right';

		$allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span' ];
		$title_tag    = in_array( $title_tag, $allowed_tags, true ) ? $title_tag : 'h3';

		$box_tag   = 'box' === $cta_type ? 'a' : 'div';
		$box_attrs = 'box' === $cta_type ? ' href="' . esc_url( $cta_url ) . '"' . $cta_ext . $cta_nofollow : '';

		$layout_class = 'pkae-ib pkae-ib--icon-' . esc_attr( $icon_pos );
		if ( 'none' !== $icon_anim ) $layout_class .= ' pkae-ib--anim-' . esc_attr( $icon_anim );
		if ( $show_ribbon ) $layout_class .= ' pkae-ib--has-ribbon pkae-ib--ribbon-' . esc_attr( $ribbon_pos );
		?>
		<<?php echo esc_attr( $box_tag ); ?> class="<?php echo esc_attr( $layout_class ); ?>"<?php echo $box_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

			<?php if ( $show_ribbon && $ribbon_text ) : ?>
				<span class="pkae-ib__ribbon"><?php echo esc_html( $ribbon_text ); ?></span>
			<?php endif; ?>

			<?php if ( $show_badge && $badge_text ) : ?>
				<span class="pkae-ib__badge pkae-ib__badge--<?php echo esc_attr( $badge_pos ); ?>"><?php echo esc_html( $badge_text ); ?></span>
			<?php endif; ?>

			<?php if ( 'none' !== $icon_type ) : ?>
				<div class="pkae-ib__icon-wrap">
					<div class="pkae-ib__icon">
						<?php if ( 'icon' === $icon_type && ! empty( $s['icon']['value'] ) ) :
							Icons_Manager::render_icon( $s['icon'], [ 'aria-hidden' => 'true' ] );
						elseif ( 'image' === $icon_type && ! empty( $s['image']['url'] ) ) :
							$img_src = Group_Control_Image_Size::get_attachment_image_src( $s['image']['id'], 'image', $s );
							if ( ! $img_src ) $img_src = $s['image']['url'];
							// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
							echo '<img src="' . esc_url( $img_src ) . '" alt="' . esc_attr( $title ) . '">';
						elseif ( 'text' === $icon_type && ! empty( $s['icon_text'] ) ) :
							echo '<span class="pkae-ib__icon-text">' . esc_html( $s['icon_text'] ) . '</span>';
						endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( 'after_icon' === $sep_pos && $show_sep ) : ?>
				<div class="pkae-ib__sep"></div>
			<?php endif; ?>

			<div class="pkae-ib__content">
				<?php if ( $prefix ) : ?>
					<span class="pkae-ib__prefix"><?php echo esc_html( $prefix ); ?></span>
				<?php endif; ?>

				<?php if ( $title ) : ?>
					<<?php echo esc_attr( $title_tag ); ?> class="pkae-ib__title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
				<?php endif; ?>

				<?php if ( 'after_title' === $sep_pos && $show_sep ) : ?>
					<div class="pkae-ib__sep"></div>
				<?php endif; ?>

				<?php if ( $desc ) : ?>
					<p class="pkae-ib__desc"><?php echo wp_kses_post( $desc ); ?></p>
				<?php endif; ?>

				<?php if ( 'after_desc' === $sep_pos && $show_sep ) : ?>
					<div class="pkae-ib__sep"></div>
				<?php endif; ?>

				<?php if ( 'text' === $cta_type && $cta_text ) : ?>
					<a class="pkae-ib__cta pkae-ib__cta--text" href="<?php echo esc_url( $cta_url ); ?>"<?php echo $cta_ext . $cta_nofollow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $cta_text ); ?></a>
				<?php elseif ( 'button' === $cta_type && $cta_text ) : ?>
					<a class="pkae-ib__cta pkae-ib__cta--btn" href="<?php echo esc_url( $cta_url ); ?>"<?php echo $cta_ext . $cta_nofollow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php if ( ! empty( $s['cta_icon']['value'] ) && 'before' === ( $s['cta_icon_position'] ?? 'after' ) ) :
							Icons_Manager::render_icon( $s['cta_icon'], [ 'aria-hidden' => 'true' ] );
						endif; ?>
						<?php echo esc_html( $cta_text ); ?>
						<?php if ( ! empty( $s['cta_icon']['value'] ) && 'after' === ( $s['cta_icon_position'] ?? 'after' ) ) :
							Icons_Manager::render_icon( $s['cta_icon'], [ 'aria-hidden' => 'true' ] );
						endif; ?>
					</a>
				<?php endif; ?>
			</div>

		</<?php echo esc_attr( $box_tag ); ?>>
		<?php
	}
}
