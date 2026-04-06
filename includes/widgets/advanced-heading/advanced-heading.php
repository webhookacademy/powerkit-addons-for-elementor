<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Advanced_Heading extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'pkae-advanced-heading',
			plugins_url( 'assets/css/pkae-advanced-heading.css', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
	}

	public function get_name()       { return 'pkae-advanced-heading'; }
	public function get_title()      { return esc_html__( 'Advanced Heading', 'powerkit-addons-for-elementor' ); }
	public function get_icon()       { return 'eicon-heading'; }
	public function get_categories() { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-advanced-heading' ]; }
	public function get_keywords()   { return [ 'heading', 'title', 'advanced', 'sub heading', 'separator', 'powerkit' ]; }

	protected function register_controls() {

		// ── CONTENT ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_general', [
			'label' => esc_html__( 'General', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading_tag', [
			'label'   => esc_html__( 'Heading Tag', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'h2',
			'options' => [
				'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
				'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
				'p'  => 'p',  'div' => 'div', 'span' => 'span',
			],
		] );

		$this->add_control( 'heading_text', [
			'label'       => esc_html__( 'Heading', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'Add Your Heading Text Here', 'powerkit-addons-for-elementor' ),
			'placeholder' => esc_html__( 'Enter heading', 'powerkit-addons-for-elementor' ),
			'rows'        => 2,
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'heading_link', [
			'label'       => esc_html__( 'Link', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'sub_heading_text', [
			'label'       => esc_html__( 'Sub Heading', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Sub Heading', 'powerkit-addons-for-elementor' ),
			'placeholder' => esc_html__( 'Enter sub heading', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
			'separator'   => 'before',
		] );

		$this->add_control( 'sub_heading_position', [
			'label'   => esc_html__( 'Sub Heading Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'above',
			'options' => [
				'above' => esc_html__( 'Above Heading', 'powerkit-addons-for-elementor' ),
				'below' => esc_html__( 'Below Heading', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'sub_heading_text!' => '' ],
		] );

		$this->add_control( 'description_text', [
			'label'       => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXTAREA,
			'placeholder' => esc_html__( 'Enter description', 'powerkit-addons-for-elementor' ),
			'rows'        => 4,
			'dynamic'     => [ 'active' => true ],
			'separator'   => 'before',
		] );

		$this->end_controls_section();

		// ── HIGHLIGHTED TEXT ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_highlight', [
			'label' => esc_html__( 'Highlighted Text', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'highlight_notice', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'Wrap any word in your heading with <mark> tag to highlight it. Example: Hello <mark>World</mark>', 'powerkit-addons-for-elementor' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

		$this->add_control( 'highlight_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__title mark' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'highlight_bg', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__title mark' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'highlight_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__title mark' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'highlight_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__title mark' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'highlight_typo',
			'selector' => '{{WRAPPER}} .pkae-adv-heading__title mark',
		] );

		$this->end_controls_section();

		// ── SEPARATOR ─────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_separator', [
			'label' => esc_html__( 'Separator', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'separator_style', [
			'label'   => esc_html__( 'Style', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none'       => esc_html__( 'None', 'powerkit-addons-for-elementor' ),
				'line'       => esc_html__( 'Line', 'powerkit-addons-for-elementor' ),
				'line_icon'  => esc_html__( 'Line with Icon', 'powerkit-addons-for-elementor' ),
				'line_image' => esc_html__( 'Line with Image', 'powerkit-addons-for-elementor' ),
				'line_text'  => esc_html__( 'Line with Text', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'separator_position', [
			'label'   => esc_html__( 'Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'below_heading',
			'options' => [
				'above_heading' => esc_html__( 'Above Heading', 'powerkit-addons-for-elementor' ),
				'below_heading' => esc_html__( 'Below Heading', 'powerkit-addons-for-elementor' ),
				'below_desc'    => esc_html__( 'Below Description', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'separator_style!' => 'none' ],
		] );

		$this->add_control( 'separator_line_style', [
			'label'   => esc_html__( 'Line Style', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'solid',
			'options' => [
				'solid'  => esc_html__( 'Solid', 'powerkit-addons-for-elementor' ),
				'dashed' => esc_html__( 'Dashed', 'powerkit-addons-for-elementor' ),
				'dotted' => esc_html__( 'Dotted', 'powerkit-addons-for-elementor' ),
				'double' => esc_html__( 'Double', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'separator_style!' => 'none' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-adv-heading__sep-line' => 'border-top-style: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'separator_width', [
			'label'      => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 600 ], '%' => [ 'min' => 1, 'max' => 100 ] ],
			'default'    => [ 'size' => 100, 'unit' => 'px' ],
			'condition'  => [ 'separator_style!' => 'none' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-adv-heading__sep-line' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'separator_thickness', [
			'label'     => esc_html__( 'Thickness (px)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1, 'max' => 20 ] ],
			'default'   => [ 'size' => 2, 'unit' => 'px' ],
			'condition' => [ 'separator_style!' => 'none' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-adv-heading__sep-line' => 'border-top-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'separator_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#333333',
			'condition' => [ 'separator_style!' => 'none' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-adv-heading__sep-line' => 'border-top-color: {{VALUE}};',
			],
		] );

		// Icon
		$this->add_control( 'separator_icon', [
			'label'     => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
			'condition' => [ 'separator_style' => 'line_icon' ],
		] );

		$this->add_control( 'separator_icon_size', [
			'label'     => esc_html__( 'Icon Size (px)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 8, 'max' => 80 ] ],
			'default'   => [ 'size' => 20, 'unit' => 'px' ],
			'condition' => [ 'separator_style' => 'line_icon' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-adv-heading__sep-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-adv-heading__sep-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'separator_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'separator_style' => 'line_icon' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-adv-heading__sep-icon'     => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-adv-heading__sep-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		// Image
		$this->add_control( 'separator_image', [
			'label'     => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => Utils::get_placeholder_image_src() ],
			'condition' => [ 'separator_style' => 'line_image' ],
		] );

		$this->add_control( 'separator_image_size', [
			'label'     => esc_html__( 'Image Size (px)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
			'default'   => [ 'size' => 30, 'unit' => 'px' ],
			'condition' => [ 'separator_style' => 'line_image' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-adv-heading__sep-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; object-fit: contain;',
			],
		] );

		// Text
		$this->add_control( 'separator_text', [
			'label'     => esc_html__( 'Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( '✦', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'separator_style' => 'line_text' ],
		] );

		$this->add_control( 'separator_text_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'separator_style' => 'line_text' ],
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__sep-text' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'separator_text_typo',
			'selector'  => '{{WRAPPER}} .pkae-adv-heading__sep-text',
			'condition' => [ 'separator_style' => 'line_text' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Box ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'box_bg_color', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'box_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-adv-heading',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: General ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_general', [
			'label' => esc_html__( 'General', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => esc_html__( 'Left', 'powerkit-addons-for-elementor' ),   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => esc_html__( 'Center', 'powerkit-addons-for-elementor' ), 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => esc_html__( 'Right', 'powerkit-addons-for-elementor' ),  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'left',
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading' => 'text-align: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Heading ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_heading', [
			'label' => esc_html__( 'Heading', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'heading_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__title, {{WRAPPER}} .pkae-adv-heading__title a' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typo',
			'selector' => '{{WRAPPER}} .pkae-adv-heading__title',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'heading_text_shadow',
			'selector' => '{{WRAPPER}} .pkae-adv-heading__title',
		] );

		$this->add_responsive_control( 'heading_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		// ── Background Text ───────────────────────────────────────────────────
		$this->add_control( 'bg_text_heading', [
			'label'     => esc_html__( 'Background Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'bg_text', [
			'label'       => esc_html__( 'Text', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'Watermark', 'powerkit-addons-for-elementor' ),
		] );

		$this->add_control( 'bg_text_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0,0,0,0.07)',
			'condition' => [ 'bg_text!' => '' ],
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__title::before' => 'color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'bg_text_size', [
			'label'     => esc_html__( 'Font Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'size_units'=> [ 'px', 'em', 'rem', 'vw' ],
			'range'     => [ 'px' => [ 'min' => 20, 'max' => 300 ] ],
			'default'   => [ 'size' => 80, 'unit' => 'px' ],
			'condition' => [ 'bg_text!' => '' ],
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__title::before' => 'font-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'bg_text_horizontal', [
			'label'      => esc_html__( 'Horizontal Position', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [ '%' => [ 'min' => 0, 'max' => 100 ], 'px' => [ 'min' => -500, 'max' => 500 ] ],
			'default'    => [ 'size' => 50, 'unit' => '%' ],
			'condition'  => [ 'bg_text!' => '' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-adv-heading__title::before' => 'left: {{SIZE}}{{UNIT}}; transform: translate(-{{SIZE}}{{UNIT}}, -50%);',
			],
		] );

		$this->add_responsive_control( 'bg_text_vertical', [
			'label'      => esc_html__( 'Vertical Position', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [ '%' => [ 'min' => 0, 'max' => 100 ], 'px' => [ 'min' => -500, 'max' => 500 ] ],
			'default'    => [ 'size' => 50, 'unit' => '%' ],
			'condition'  => [ 'bg_text!' => '' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-adv-heading__title::before' => 'top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// ── STYLE: Sub Heading ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_sub_heading', [
			'label'     => esc_html__( 'Sub Heading', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'sub_heading_text!' => '' ],
		] );

		$this->add_control( 'sub_heading_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__sub' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'sub_heading_bg', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__sub' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'sub_heading_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'sub_heading_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__sub' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sub_heading_typo',
			'selector' => '{{WRAPPER}} .pkae-adv-heading__sub',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'sub_heading_text_shadow',
			'selector' => '{{WRAPPER}} .pkae-adv-heading__sub',
		] );

		$this->add_responsive_control( 'sub_heading_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Separator ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_sep', [
			'label'     => esc_html__( 'Separator', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'separator_style!' => 'none' ],
		] );

		$this->add_responsive_control( 'sep_margin', [
			'label'      => esc_html__( 'Spacing', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__sep' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
		$this->start_controls_section( 'section_style_desc', [
			'label'     => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'description_text!' => '' ],
		] );

		$this->add_control( 'desc_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-adv-heading__desc' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'desc_typo',
			'selector' => '{{WRAPPER}} .pkae-adv-heading__desc',
		] );

		$this->add_responsive_control( 'desc_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-adv-heading__desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$heading_tag      = ! empty( $s['heading_tag'] ) ? $s['heading_tag'] : 'h2';
		$heading_text     = ! empty( $s['heading_text'] ) ? $s['heading_text'] : '';
		$sub_heading      = ! empty( $s['sub_heading_text'] ) ? $s['sub_heading_text'] : '';
		$sub_pos          = ! empty( $s['sub_heading_position'] ) ? $s['sub_heading_position'] : 'above';
		$desc             = ! empty( $s['description_text'] ) ? $s['description_text'] : '';
		$sep_style        = ! empty( $s['separator_style'] ) ? $s['separator_style'] : 'none';
		$sep_position     = ! empty( $s['separator_position'] ) ? $s['separator_position'] : 'below_heading';
		$link_url         = ! empty( $s['heading_link']['url'] ) ? $s['heading_link']['url'] : '';
		$link_target      = ! empty( $s['heading_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		$link_nofollow    = ! empty( $s['heading_link']['nofollow'] ) ? ' rel="nofollow"' : '';

		$allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' ];
		$heading_tag  = in_array( $heading_tag, $allowed_tags, true ) ? $heading_tag : 'h2';

		$bg_text = ! empty( $s['bg_text'] ) ? $s['bg_text'] : '';
		$bg_text_h = ! empty( $s['bg_text_horizontal']['size'] ) ? $s['bg_text_horizontal']['size'] : 50;
		$bg_text_h_unit = ! empty( $s['bg_text_horizontal']['unit'] ) ? $s['bg_text_horizontal']['unit'] : '%';
		$bg_text_v = ! empty( $s['bg_text_vertical']['size'] ) ? $s['bg_text_vertical']['size'] : 50;
		$bg_text_v_unit = ! empty( $s['bg_text_vertical']['unit'] ) ? $s['bg_text_vertical']['unit'] : '%';
		?>
		<div class="pkae-adv-heading">

			<?php if ( $sub_heading && 'above' === $sub_pos ) : ?>
				<span class="pkae-adv-heading__sub"><?php echo esc_html( $sub_heading ); ?></span>
			<?php endif; ?>

			<?php if ( 'above_heading' === $sep_position && 'none' !== $sep_style ) : ?>
				<?php $this->render_separator( $s ); ?>
			<?php endif; ?>

			<?php if ( $heading_text ) : ?>
				<<?php echo esc_attr( $heading_tag ); ?> class="pkae-adv-heading__title"<?php echo $bg_text ? ' data-bg-text="' . esc_attr( $bg_text ) . '" style="--pkae-bg-x:' . esc_attr( $bg_text_h . $bg_text_h_unit ) . ';--pkae-bg-y:' . esc_attr( $bg_text_v . $bg_text_v_unit ) . ';"' : ''; ?>>
					<?php if ( $link_url ) : ?>
						<a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_target . $link_nofollow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $heading_text ); ?></a>
					<?php else : ?>
						<?php echo wp_kses_post( $heading_text ); ?>
					<?php endif; ?>
				</<?php echo esc_attr( $heading_tag ); ?>>
			<?php endif; ?>

			<?php if ( 'below_heading' === $sep_position && 'none' !== $sep_style ) : ?>
				<?php $this->render_separator( $s ); ?>
			<?php endif; ?>

			<?php if ( $sub_heading && 'below' === $sub_pos ) : ?>
				<span class="pkae-adv-heading__sub"><?php echo esc_html( $sub_heading ); ?></span>
			<?php endif; ?>

			<?php if ( $desc ) : ?>
				<p class="pkae-adv-heading__desc"><?php echo wp_kses_post( $desc ); ?></p>
			<?php endif; ?>

			<?php if ( 'below_desc' === $sep_position && 'none' !== $sep_style ) : ?>
				<?php $this->render_separator( $s ); ?>
			<?php endif; ?>

		</div>
		<?php
	}

	protected function render_separator( $s ) {
		$style = ! empty( $s['separator_style'] ) ? $s['separator_style'] : 'line';
		?>
		<div class="pkae-adv-heading__sep pkae-adv-heading__sep--<?php echo esc_attr( $style ); ?>">
			<span class="pkae-adv-heading__sep-line"></span>

			<?php if ( 'line_icon' === $style && ! empty( $s['separator_icon']['value'] ) ) : ?>
				<span class="pkae-adv-heading__sep-icon">
					<?php Icons_Manager::render_icon( $s['separator_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
				<span class="pkae-adv-heading__sep-line"></span>

			<?php elseif ( 'line_image' === $style && ! empty( $s['separator_image']['url'] ) ) : ?>
				<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
				<img class="pkae-adv-heading__sep-img" src="<?php echo esc_url( $s['separator_image']['url'] ); ?>" alt="">
				<span class="pkae-adv-heading__sep-line"></span>

			<?php elseif ( 'line_text' === $style && ! empty( $s['separator_text'] ) ) : ?>
				<span class="pkae-adv-heading__sep-text"><?php echo esc_html( $s['separator_text'] ); ?></span>
				<span class="pkae-adv-heading__sep-line"></span>
			<?php endif; ?>
		</div>
		<?php
	}
}
