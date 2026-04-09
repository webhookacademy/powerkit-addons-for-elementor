<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class Advanced_Search extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-advanced-search', plugins_url( 'assets/css/pkae-advanced-search.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_script( 'pkae-advanced-search', plugins_url( 'assets/js/pkae-advanced-search.js', __FILE__ ), [ 'jquery' ], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	public function get_name()          { return 'pkae-advanced-search'; }
	public function get_title()         { return esc_html__( 'Advanced Search', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-search'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-advanced-search' ]; }
	public function get_script_depends(){ return [ 'pkae-advanced-search' ]; }
	public function get_keywords()      { return [ 'search', 'ajax', 'live search', 'advanced', 'powerkit' ]; }

	protected function register_controls() {

		// ── LAYOUT ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'input-button',
			'options' => [
				'input-only'   => esc_html__( 'Input Box Only', 'powerkit-addons-for-elementor' ),
				'input-button' => esc_html__( 'Input Box with Button', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'prefix_class' => 'pkae-search-align-',
		] );

		$this->end_controls_section();

		// ── SEARCH SETTINGS ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_search_settings', [
			'label' => esc_html__( 'Search Settings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'placeholder', [
			'label'       => esc_html__( 'Placeholder Text', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Search...', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
		] );

		$this->add_control( 'button_text', [
			'label'       => esc_html__( 'Button Text', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Search', 'powerkit-addons-for-elementor' ),
			'condition'   => [ 'layout' => 'input-button' ],
			'label_block' => true,
		] );

		$this->add_control( 'search_icon', [
			'label'   => esc_html__( 'Search Icon', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fas fa-search', 'library' => 'fa-solid' ],
		] );

		$this->add_control( 'button_icon', [
			'label'     => esc_html__( 'Button Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-search', 'library' => 'fa-solid' ],
			'condition' => [ 'layout' => 'input-button' ],
		] );

		$this->add_control( 'icon_position', [
			'label'   => esc_html__( 'Icon Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'left',
			'options' => [
				'left'  => esc_html__( 'Left', 'powerkit-addons-for-elementor' ),
				'right' => esc_html__( 'Right', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'layout!' => 'icon-only' ],
		] );

		$this->add_control( 'close_icon', [
			'label'   => esc_html__( 'Close Icon', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fas fa-times', 'library' => 'fa-solid' ],
		] );

		$this->end_controls_section();

		// ── AJAX SETTINGS ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_ajax', [
			'label' => esc_html__( 'AJAX Settings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'enable_ajax', [
			'label'        => esc_html__( 'Enable Live Search', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'min_characters', [
			'label'     => esc_html__( 'Minimum Characters', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 3,
			'min'       => 1,
			'max'       => 10,
			'condition' => [ 'enable_ajax' => 'yes' ],
		] );

		$this->add_control( 'search_delay', [
			'label'       => esc_html__( 'Search Delay (ms)', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 300,
			'min'         => 0,
			'max'         => 2000,
			'step'        => 100,
			'condition'   => [ 'enable_ajax' => 'yes' ],
			'description' => esc_html__( 'Delay before triggering search', 'powerkit-addons-for-elementor' ),
		] );

		$this->add_control( 'results_count', [
			'label'     => esc_html__( 'Number of Results', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 5,
			'min'       => 1,
			'max'       => 20,
			'condition' => [ 'enable_ajax' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── CONTENT TO SEARCH ─────────────────────────────────────────────────
		$this->start_controls_section( 'section_content_search', [
			'label' => esc_html__( 'Content to Search', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'search_post_types', [
			'label'    => esc_html__( 'Post Types', 'powerkit-addons-for-elementor' ),
			'type'     => Controls_Manager::SELECT2,
			'multiple' => true,
			'default'  => [ 'post', 'page' ],
			'options'  => $this->get_post_types(),
		] );

		$this->end_controls_section();

		// ── RESULTS DISPLAY ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_results_display', [
			'label'     => esc_html__( 'Results Display', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'enable_ajax' => 'yes' ],
		] );

		$this->add_control( 'show_thumbnail', [
			'label'        => esc_html__( 'Show Featured Image', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'show_excerpt', [
			'label'        => esc_html__( 'Show Excerpt', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'excerpt_length', [
			'label'     => esc_html__( 'Excerpt Length', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 15,
			'min'       => 5,
			'max'       => 100,
			'condition' => [ 'show_excerpt' => 'yes' ],
		] );

		$this->add_control( 'show_date', [
			'label'        => esc_html__( 'Show Post Date', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'show_post_type', [
			'label'        => esc_html__( 'Show Post Type', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'result_style', [
			'label'   => esc_html__( 'Result Style', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'list',
			'options' => [
				'list' => esc_html__( 'List (Vertical)', 'powerkit-addons-for-elementor' ),
				'grid' => esc_html__( 'Grid (Cards)', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'     => esc_html__( 'Columns', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '2',
			'options'   => [
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			],
			'condition' => [ 'result_style' => 'grid' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-search-results.pkae-style-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
			'selector' => '{{WRAPPER}} .pkae-search-form',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-search-form',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-search-form',
		] );

		$this->end_controls_section();

		// ── STYLE: Input Field ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_input', [
			'label' => esc_html__( 'Input Field', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'input_typography',
			'selector' => '{{WRAPPER}} .pkae-search-input',
		] );

		$this->start_controls_tabs( 'input_tabs' );

		$this->start_controls_tab( 'input_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'input_text_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-search-input' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'input_placeholder_color', [
			'label'     => esc_html__( 'Placeholder Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-search-input::placeholder' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'input_bg_color', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-search-input' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'input_focus', [ 'label' => esc_html__( 'Focus', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'input_focus_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-search-input:focus' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'input_focus_bg', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-search-input:focus' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'input_focus_border', [
			'label'     => esc_html__( 'Border Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-search-input:focus' => 'border-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'input_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '12', 'right' => '15', 'bottom' => '12', 'left' => '15', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'input_height', [
			'label'      => esc_html__( 'Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [ 'min' => 30, 'max' => 100, 'step' => 1 ],
			],
			'default'    => [ 'size' => 50, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-search-input' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'input_border',
			'selector' => '{{WRAPPER}} .pkae-search-input',
		] );

		$this->add_responsive_control( 'input_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'input_width', [
			'label'      => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'px' => [ 'min' => 100, 'max' => 1200, 'step' => 10 ],
				'%'  => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
				'vw' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .pkae-search-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-search-form' => 'width: 100%;',
				'{{WRAPPER}} .pkae-search-input-wrapper' => 'flex: 1; width: 100%;',
			],
		] );

		$this->end_controls_section();

		// ── STYLE: Search Button ──────────────────────────────────────────────
		$this->start_controls_section( 'section_style_button', [
			'label'     => esc_html__( 'Search Button', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'layout' => 'input-button' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'button_typography',
			'selector'  => '{{WRAPPER}} .pkae-search-button',
			'condition' => [ 'layout' => 'input-button' ],
		] );

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'button_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'button_text_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-search-button' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'button_bg_color', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [ '{{WRAPPER}} .pkae-search-button' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'button_hover_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-search-button:hover' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'button_hover_bg', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#4f2fc5',
			'selectors' => [ '{{WRAPPER}} .pkae-search-button:hover' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '12', 'right' => '25', 'bottom' => '12', 'left' => '25', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'button_border',
			'selector' => '{{WRAPPER}} .pkae-search-button',
		] );

		$this->add_responsive_control( 'button_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'button_icon_heading', [
			'label'     => esc_html__( 'Button Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'button_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
			'default'   => [ 'size' => 16 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-button-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-button-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_icon_spacing', [
			'label'     => esc_html__( 'Icon Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
			'default'   => [ 'size' => 8 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-search-button .pkae-button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'button_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-button-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-button-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_icon_hover_color', [
			'label'     => esc_html__( 'Icon Hover Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-search-button:hover .pkae-button-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-search-button:hover .pkae-button-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		// ── STYLE: Search Icon ────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_icon', [
			'label' => esc_html__( 'Search Icon', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
			'default'   => [ 'size' => 18 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-search-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-search-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-search-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-search-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'icon_spacing', [
			'label'     => esc_html__( 'Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
			'default'   => [ 'size' => 10 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-icon-left .pkae-search-icon'  => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-icon-right .pkae-search-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// ── STYLE: Close Icon ─────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_close_icon', [
			'label' => esc_html__( 'Close Icon', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'close_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
			'default'   => [ 'size' => 16 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-close-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-close-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'close_icon_tabs' );

		$this->start_controls_tab( 'close_icon_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'close_icon_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#999',
			'selectors' => [
				'{{WRAPPER}} .pkae-close-icon'     => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-close-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'close_icon_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'close_icon_hover_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#333',
			'selectors' => [
				'{{WRAPPER}} .pkae-close-icon:hover'     => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-close-icon:hover svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── STYLE: Results Dropdown ───────────────────────────────────────────
		$this->start_controls_section( 'section_style_results', [
			'label'     => esc_html__( 'Results Dropdown', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'enable_ajax' => 'yes' ],
		] );

		$this->add_control( 'results_bg', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-search-results' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'results_max_height', [
			'label'      => esc_html__( 'Max Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [ 'px' => [ 'min' => 100, 'max' => 800 ] ],
			'default'    => [ 'size' => 400, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-results' => 'max-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'results_border',
			'selector' => '{{WRAPPER}} .pkae-search-results',
		] );

		$this->add_responsive_control( 'results_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-search-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'results_shadow',
			'selector' => '{{WRAPPER}} .pkae-search-results',
		] );

		$this->end_controls_section();

		// ── STYLE: Result Items ───────────────────────────────────────────────
		$this->start_controls_section( 'section_style_result_items', [
			'label'     => esc_html__( 'Result Items', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'enable_ajax' => 'yes' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'result_title_typography',
			'label'    => esc_html__( 'Title Typography', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-result-title',
		] );

		$this->add_control( 'result_title_color', [
			'label'     => esc_html__( 'Title Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-result-title' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'result_excerpt_typography',
			'label'    => esc_html__( 'Excerpt Typography', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-result-excerpt',
		] );

		$this->add_control( 'result_excerpt_color', [
			'label'     => esc_html__( 'Excerpt Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-result-excerpt' => 'color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'result_item_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '12', 'right' => '15', 'bottom' => '12', 'left' => '15', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-result-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'result_item_hover_bg', [
			'label'     => esc_html__( 'Hover Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f5f5f5',
			'selectors' => [ '{{WRAPPER}} .pkae-result-item:hover' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Thumbnail ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_thumbnail', [
			'label'     => esc_html__( 'Thumbnail', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'enable_ajax' => 'yes', 'show_thumbnail' => 'yes' ],
		] );

		$this->add_responsive_control( 'thumbnail_size', [
			'label'      => esc_html__( 'Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 30, 'max' => 300 ] ],
			'default'    => [ 'size' => 60, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-result-item .pkae-result-thumb' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				'{{WRAPPER}} .pkae-result-card .pkae-result-thumb' => 'width: 100% !important; height: {{SIZE}}{{UNIT}} !important;',
			],
		] );

		$this->add_responsive_control( 'thumbnail_spacing', [
			'label'     => esc_html__( 'Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
			'default'   => [ 'size' => 12 ],
			'selectors' => [ 
				'{{WRAPPER}} .pkae-result-item .pkae-result-thumb' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
			],
		] );

		$this->add_responsive_control( 'thumbnail_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit' => 'px' ],
			'selectors'  => [ 
				'{{WRAPPER}} .pkae-result-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				'{{WRAPPER}} .pkae-result-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		] );

		$this->end_controls_section();
	}

	protected function get_post_types() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];
		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}
		return $options;
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$layout          = ! empty( $s['layout'] ) ? $s['layout'] : 'input-button';
		$placeholder     = ! empty( $s['placeholder'] ) ? $s['placeholder'] : 'Search...';
		$button_text     = ! empty( $s['button_text'] ) ? $s['button_text'] : 'Search';
		$icon_position   = ! empty( $s['icon_position'] ) ? $s['icon_position'] : 'left';
		$enable_ajax     = isset( $s['enable_ajax'] ) && 'yes' === $s['enable_ajax'];
		$min_characters  = ! empty( $s['min_characters'] ) ? $s['min_characters'] : 3;
		$search_delay    = ! empty( $s['search_delay'] ) ? $s['search_delay'] : 300;
		$results_count   = ! empty( $s['results_count'] ) ? $s['results_count'] : 5;
		$post_types      = ! empty( $s['search_post_types'] ) ? $s['search_post_types'] : [ 'post', 'page' ];
		$show_thumbnail  = isset( $s['show_thumbnail'] ) && 'yes' === $s['show_thumbnail'];
		$show_excerpt    = isset( $s['show_excerpt'] ) && 'yes' === $s['show_excerpt'];
		$excerpt_length  = ! empty( $s['excerpt_length'] ) ? $s['excerpt_length'] : 15;
		$show_date       = isset( $s['show_date'] ) && 'yes' === $s['show_date'];
		$show_post_type  = isset( $s['show_post_type'] ) && 'yes' === $s['show_post_type'];
		$result_style    = ! empty( $s['result_style'] ) ? $s['result_style'] : 'list';

		$widget_id = 'pkae-search-' . $this->get_id();

		Icons_Manager::enqueue_shim();

		?>
		<div class="pkae-search-wrapper pkae-layout-<?php echo esc_attr( $layout ); ?> pkae-icon-<?php echo esc_attr( $icon_position ); ?>" id="<?php echo esc_attr( $widget_id ); ?>"
			data-ajax="<?php echo esc_attr( $enable_ajax ? 'yes' : 'no' ); ?>"
			data-min-chars="<?php echo esc_attr( $min_characters ); ?>"
			data-delay="<?php echo esc_attr( $search_delay ); ?>"
			data-count="<?php echo esc_attr( $results_count ); ?>"
			data-post-types="<?php echo esc_attr( implode( ',', $post_types ) ); ?>"
			data-show-thumb="<?php echo esc_attr( $show_thumbnail ? 'yes' : 'no' ); ?>"
			data-show-excerpt="<?php echo esc_attr( $show_excerpt ? 'yes' : 'no' ); ?>"
			data-excerpt-length="<?php echo esc_attr( $excerpt_length ); ?>"
			data-show-date="<?php echo esc_attr( $show_date ? 'yes' : 'no' ); ?>"
			data-show-type="<?php echo esc_attr( $show_post_type ? 'yes' : 'no' ); ?>"
			data-result-style="<?php echo esc_attr( $result_style ); ?>">
			
			<form class="pkae-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				
				<div class="pkae-search-input-wrapper">
					<?php if ( 'left' === $icon_position ) : ?>
						<span class="pkae-search-icon">
							<?php Icons_Manager::render_icon( $s['search_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
					
					<input type="text" class="pkae-search-input" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off">
					
					<span class="pkae-close-icon" style="display: none;">
						<?php Icons_Manager::render_icon( $s['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
					
					<?php if ( 'right' === $icon_position ) : ?>
						<span class="pkae-search-icon">
							<?php Icons_Manager::render_icon( $s['search_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
				</div>

				<?php if ( 'input-button' === $layout ) : ?>
					<button type="submit" class="pkae-search-button">
						<?php if ( ! empty( $s['button_icon']['value'] ) ) : ?>
							<span class="pkae-button-icon">
								<?php Icons_Manager::render_icon( $s['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</span>
						<?php endif; ?>
						<?php if ( ! empty( $button_text ) ) : ?>
							<span class="pkae-button-text"><?php echo esc_html( $button_text ); ?></span>
						<?php endif; ?>
					</button>
				<?php endif; ?>

				<?php if ( $enable_ajax ) : ?>
					<div class="pkae-search-results" style="display: none;"></div>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}
}
