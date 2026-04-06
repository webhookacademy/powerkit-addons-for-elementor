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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit;

class Content_Toggle extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style(
			'pkae-content-toggle',
			plugins_url( 'assets/css/pkae-content-toggle.css', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
		wp_register_script(
			'pkae-content-toggle',
			plugins_url( 'assets/js/pkae-content-toggle.js', __FILE__ ),
			[ 'jquery' ],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
	}

	public function get_name()          { return 'pkae-content-toggle'; }
	public function get_title()         { return esc_html__( 'Content Toggle', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-toggle'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-content-toggle' ]; }
	public function get_script_depends(){ return [ 'pkae-content-toggle' ]; }
	public function get_keywords()      { return [ 'content toggle', 'switch', 'tab', 'toggle', 'comparison', 'powerkit' ]; }

	protected function register_controls() {

		// ── PRIMARY CONTENT ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_primary', [
			'label' => esc_html__( 'Primary Content', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'primary_label', [
			'label'   => esc_html__( 'Label', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Monthly', 'powerkit-addons-for-elementor' ),
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'primary_content_type', [
			'label'   => esc_html__( 'Content Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'items',
			'options' => [
				'items'    => esc_html__( 'Items (Repeater)', 'powerkit-addons-for-elementor' ),
				'template' => esc_html__( 'Saved Template', 'powerkit-addons-for-elementor' ),
			],
		] );

		$primary_repeater = new Repeater();
		$this->add_repeater_fields( $primary_repeater );

		$this->add_control( 'primary_items', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $primary_repeater->get_controls(),
			'title_field' => '{{{ item_title }}}',
			'default'     => [
				[ 'item_title' => esc_html__( 'Feature One', 'powerkit-addons-for-elementor' ), 'item_desc' => esc_html__( 'Description for feature one.', 'powerkit-addons-for-elementor' ) ],
				[ 'item_title' => esc_html__( 'Feature Two', 'powerkit-addons-for-elementor' ), 'item_desc' => esc_html__( 'Description for feature two.', 'powerkit-addons-for-elementor' ) ],
			],
			'condition'   => [ 'primary_content_type' => 'items' ],
		] );

		$this->add_control( 'primary_template', [
			'label'     => esc_html__( 'Select Template', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => $this->get_elementor_templates(),
			'condition' => [ 'primary_content_type' => 'template' ],
		] );

		$this->end_controls_section();

		// ── SECONDARY CONTENT ─────────────────────────────────────────────────
		$this->start_controls_section( 'section_secondary', [
			'label' => esc_html__( 'Secondary Content', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'secondary_label', [
			'label'   => esc_html__( 'Label', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Yearly', 'powerkit-addons-for-elementor' ),
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'secondary_content_type', [
			'label'   => esc_html__( 'Content Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'items',
			'options' => [
				'items'    => esc_html__( 'Items (Repeater)', 'powerkit-addons-for-elementor' ),
				'template' => esc_html__( 'Saved Template', 'powerkit-addons-for-elementor' ),
			],
		] );

		$secondary_repeater = new Repeater();
		$this->add_repeater_fields( $secondary_repeater );

		$this->add_control( 'secondary_items', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $secondary_repeater->get_controls(),
			'title_field' => '{{{ item_title }}}',
			'default'     => [
				[ 'item_title' => esc_html__( 'Feature One', 'powerkit-addons-for-elementor' ), 'item_desc' => esc_html__( 'Description for feature one.', 'powerkit-addons-for-elementor' ) ],
				[ 'item_title' => esc_html__( 'Feature Two', 'powerkit-addons-for-elementor' ), 'item_desc' => esc_html__( 'Description for feature two.', 'powerkit-addons-for-elementor' ) ],
			],
			'condition'   => [ 'secondary_content_type' => 'items' ],
		] );

		$this->add_control( 'secondary_template', [
			'label'     => esc_html__( 'Select Template', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => $this->get_elementor_templates(),
			'condition' => [ 'secondary_content_type' => 'template' ],
		] );

		$this->end_controls_section();

		// ── TOGGLE SETTINGS ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_toggle', [
			'label' => esc_html__( 'Toggle Settings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'default_display', [
			'label'   => esc_html__( 'Default Display', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'primary',
			'options' => [
				'primary'   => esc_html__( 'Primary', 'powerkit-addons-for-elementor' ),
				'secondary' => esc_html__( 'Secondary', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'toggle_style', [
			'label'   => esc_html__( 'Switch Style', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'round',
			'options' => [
				'round'   => esc_html__( 'Round', 'powerkit-addons-for-elementor' ),
				'square'  => esc_html__( 'Square', 'powerkit-addons-for-elementor' ),
				'label'   => esc_html__( 'Label Switch', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'toggle_animation', [
			'label'        => esc_html__( 'Smooth Animation', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->end_controls_section();

		// ── STYLE: Box ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'widget_bg',
			'selector' => '{{WRAPPER}} .pkae-ct',
		] );

		$this->add_responsive_control( 'widget_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'widget_border',
			'selector' => '{{WRAPPER}} .pkae-ct',
		] );

		$this->add_responsive_control( 'widget_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'widget_shadow',
			'selector' => '{{WRAPPER}} .pkae-ct',
		] );

		$this->end_controls_section();

		// ── STYLE: Layout ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_layout', [
			'label' => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'layout', [
			'label'   => esc_html__( 'Heading Layout', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'inline',
			'options' => [
				'inline' => esc_html__( 'Inline', 'powerkit-addons-for-elementor' ),
				'stack'  => esc_html__( 'Stack', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'selectors' => [
				'{{WRAPPER}} .pkae-ct--layout-inline .pkae-ct__header' => 'justify-content: {{VALUE}};',
				'{{WRAPPER}} .pkae-ct--layout-stack  .pkae-ct__header' => 'align-items: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'gap_between', [
			'label'     => esc_html__( 'Gap Between Label & Switch', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 15, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .pkae-ct__header' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Switcher ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_switcher', [
			'label' => esc_html__( 'Switcher', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'switch_width', [
			'label'     => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 30, 'max' => 120 ] ],
			'default'   => [ 'size' => 56, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .pkae-ct__switch' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'switch_height', [
			'label'     => esc_html__( 'Height', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 16, 'max' => 60 ] ],
			'default'   => [ 'size' => 28, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .pkae-ct__switch' => 'height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'switch_bg_off', [
			'label'     => esc_html__( 'Background (Off)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#cccccc',
			'selectors' => [ '{{WRAPPER}} .pkae-ct__switch' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'switch_bg_on', [
			'label'     => esc_html__( 'Background (On)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#8D58D5',
			'selectors' => [ '{{WRAPPER}} .pkae-ct__switch.pkae-ct--active' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'knob_color', [
			'label'     => esc_html__( 'Knob Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-ct__knob' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'switch_shadow',
			'selector' => '{{WRAPPER}} .pkae-ct__switch',
		] );

		$this->end_controls_section();

		// ── STYLE: Headings ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_headings', [
			'label' => esc_html__( 'Headings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'heading_primary', [
			'label' => esc_html__( 'Primary Label', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_control( 'primary_label_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__label--primary' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'primary_label_active_color', [
			'label'     => esc_html__( 'Active Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__label--primary.pkae-ct--active' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'primary_label_typo',
			'selector' => '{{WRAPPER}} .pkae-ct__label--primary',
		] );

		$this->add_control( 'heading_secondary', [
			'label'     => esc_html__( 'Secondary Label', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'secondary_label_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__label--secondary' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'secondary_label_active_color', [
			'label'     => esc_html__( 'Active Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__label--secondary.pkae-ct--active' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'secondary_label_typo',
			'selector' => '{{WRAPPER}} .pkae-ct__label--secondary',
		] );

		$this->end_controls_section();

		// ── STYLE: Content ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_content', [
			'label' => esc_html__( 'Content', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'items_heading', [
			'label' => esc_html__( 'Items Layout', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'items_columns', [
			'label'   => esc_html__( 'Columns', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => [
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			],
			'selectors' => [
				'{{WRAPPER}} .pkae-ct__items' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
			],
		] );

		$this->add_responsive_control( 'items_gap', [
			'label'     => esc_html__( 'Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'   => [ 'size' => 20, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .pkae-ct__items' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'icon_position', [
			'label'     => esc_html__( 'Icon / Image Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => esc_html__( 'Left', 'powerkit-addons-for-elementor' ),   'icon' => 'eicon-h-align-left' ],
				'top'    => [ 'title' => esc_html__( 'Top', 'powerkit-addons-for-elementor' ),    'icon' => 'eicon-v-align-top' ],
				'right'  => [ 'title' => esc_html__( 'Right', 'powerkit-addons-for-elementor' ),  'icon' => 'eicon-h-align-right' ],
				'bottom' => [ 'title' => esc_html__( 'Bottom', 'powerkit-addons-for-elementor' ), 'icon' => 'eicon-v-align-bottom' ],
			],
			'default'   => 'left',
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'icon_align', [
			'label'     => esc_html__( 'Icon / Image Align', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Top / Left',    'icon' => 'eicon-v-align-top' ],
				'center'     => [ 'title' => 'Center',        'icon' => 'eicon-v-align-middle' ],
				'flex-end'   => [ 'title' => 'Bottom / Right','icon' => 'eicon-v-align-bottom' ],
			],
			'default'   => 'flex-start',
			'condition' => [ 'icon_position' => [ 'left', 'right' ] ],
			'selectors' => [ '{{WRAPPER}} .pkae-ct__item' => 'align-items: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'content_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'content_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__content-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'content_bg',
			'selector' => '{{WRAPPER}} .pkae-ct__content-wrap',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'content_border',
			'selector' => '{{WRAPPER}} .pkae-ct__content-wrap',
		] );

		$this->add_responsive_control( 'content_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'content_shadow',
			'selector' => '{{WRAPPER}} .pkae-ct__content-wrap',
		] );

		$this->end_controls_section();

		// ── STYLE: Primary Items ──────────────────────────────────────────────
		$this->register_items_style_section( 'primary', esc_html__( 'Primary Items Style', 'powerkit-addons-for-elementor' ) );

		// ── STYLE: Secondary Items ────────────────────────────────────────────
		$this->register_items_style_section( 'secondary', esc_html__( 'Secondary Items Style', 'powerkit-addons-for-elementor' ) );
	}

	protected function register_items_style_section( $side, $label ) {
		$p = $side . '_item_'; // prefix e.g. primary_item_

		$this->start_controls_section( 'section_style_' . $side . '_items', [
			'label'     => $label,
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ $side . '_content_type' => 'items' ],
		] );

		// ── Item Box ──────────────────────────────────────────────────────────
		$this->add_control( $p . 'box_heading', [
			'label' => esc_html__( 'Item Box', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => $p . 'box_bg',
			'selector' => '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item',
		] );

		$this->add_responsive_control( $p . 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( $p . 'box_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => $p . 'box_border',
			'selector' => '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item',
		] );

		$this->add_responsive_control( $p . 'box_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => $p . 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item',
		] );

		// ── Icon / Image ──────────────────────────────────────────────────────
		$this->add_control( $p . 'media_heading', [
			'label'     => esc_html__( 'Icon / Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( $p . 'icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-media i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-media svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( $p . 'icon_size', [
			'label'      => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 8, 'max' => 120 ] ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-media i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-media svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( $p . 'image_width', [
			'label'      => esc_html__( 'Image Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 400 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-media img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( $p . 'media_margin', [
			'label'      => esc_html__( 'Spacing', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		// ── Title ─────────────────────────────────────────────────────────────
		$this->add_control( $p . 'title_heading', [
			'label'     => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( $p . 'title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-title' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => $p . 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-title',
		] );

		$this->add_responsive_control( $p . 'title_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		// ── Description ───────────────────────────────────────────────────────
		$this->add_control( $p . 'desc_heading', [
			'label'     => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( $p . 'desc_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-desc' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => $p . 'desc_typo',
			'selector' => '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-desc',
		] );

		$this->add_responsive_control( $p . 'desc_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		// ── Button ────────────────────────────────────────────────────────────
		$this->add_control( $p . 'btn_heading', [
			'label'     => esc_html__( 'Button', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => $p . 'btn_typo',
			'selector' => '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn',
		] );

		$this->add_responsive_control( $p . 'btn_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( $p . 'btn_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( $p . 'btn_tabs' );

		$this->start_controls_tab( $p . 'btn_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( $p . 'btn_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( $p . 'btn_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn' => 'background-color: {{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => $p . 'btn_border',
			'selector' => '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn',
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( $p . 'btn_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( $p . 'btn_hover_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn:hover' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( $p . 'btn_hover_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-ct__pane--' . $side . ' .pkae-ct__item-btn:hover' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function get_elementor_templates() {
		$templates = [ '' => esc_html__( '— Select —', 'powerkit-addons-for-elementor' ) ];
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$items = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
			foreach ( $items as $item ) {
				$templates[ $item['template_id'] ] = $item['title'];
			}
		}
		return $templates;
	}

	protected function add_repeater_fields( $repeater ) {

		$repeater->add_control( 'item_icon', [
			'label'   => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => '', 'library' => '' ],
		] );

		$repeater->add_control( 'item_image', [
			'label'   => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
		] );

		$repeater->add_control( 'item_title', [
			'label'       => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Feature Title', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'item_title_tag', [
			'label'   => esc_html__( 'Title Tag', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'h4',
			'options' => [ 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'p' => 'p', 'span' => 'span' ],
		] );

		$repeater->add_control( 'item_desc', [
			'label'   => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Add a short description here.', 'powerkit-addons-for-elementor' ),
			'rows'    => 3,
			'dynamic' => [ 'active' => true ],
		] );

		$repeater->add_control( 'item_btn_text', [
			'label'     => esc_html__( 'Button Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '',
			'separator' => 'before',
		] );

		$repeater->add_control( 'item_btn_link', [
			'label'       => esc_html__( 'Button Link', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
			'default'     => [ 'url' => '#' ],
			'condition'   => [ 'item_btn_text!' => '' ],
		] );

		$repeater->add_control( 'item_btn_icon', [
			'label'     => esc_html__( 'Button Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => '', 'library' => '' ],
			'condition' => [ 'item_btn_text!' => '' ],
		] );
	}

	protected function render() {
		$s               = $this->get_settings_for_display();
		$default         = ! empty( $s['default_display'] ) ? $s['default_display'] : 'primary';
		$toggle_style    = ! empty( $s['toggle_style'] ) ? $s['toggle_style'] : 'round';
		$layout          = ! empty( $s['layout'] ) ? $s['layout'] : 'inline';
		$animation       = isset( $s['toggle_animation'] ) && 'yes' === $s['toggle_animation'] ? 'yes' : 'no';
		$is_secondary    = ( 'secondary' === $default );

		$primary_label   = ! empty( $s['primary_label'] ) ? $s['primary_label'] : esc_html__( 'Monthly', 'powerkit-addons-for-elementor' );
		$secondary_label = ! empty( $s['secondary_label'] ) ? $s['secondary_label'] : esc_html__( 'Yearly', 'powerkit-addons-for-elementor' );

		$widget_id = 'pkae-ct-' . $this->get_id();
		$icon_pos  = ! empty( $s['icon_position'] ) ? $s['icon_position'] : 'left';
		$columns   = ! empty( $s['items_columns'] ) ? $s['items_columns'] : '1';
		?>
		<div class="pkae-ct pkae-ct--<?php echo esc_attr( $toggle_style ); ?> pkae-ct--layout-<?php echo esc_attr( $layout ); ?>"
			id="<?php echo esc_attr( $widget_id ); ?>"
			data-default="<?php echo esc_attr( $default ); ?>"
			data-animation="<?php echo esc_attr( $animation ); ?>">

			<div class="pkae-ct__header">
				<span class="pkae-ct__label pkae-ct__label--primary<?php echo ! $is_secondary ? ' pkae-ct--active' : ''; ?>">
					<?php echo esc_html( $primary_label ); ?>
				</span>

				<button class="pkae-ct__switch<?php echo $is_secondary ? ' pkae-ct--active' : ''; ?>"
					role="switch"
					aria-checked="<?php echo $is_secondary ? 'true' : 'false'; ?>"
					aria-label="<?php esc_attr_e( 'Toggle content', 'powerkit-addons-for-elementor' ); ?>">
					<span class="pkae-ct__knob"></span>
					<?php if ( 'label' === $toggle_style ) : ?>
						<span class="pkae-ct__switch-label pkae-ct__switch-label--off"><?php echo esc_html( $primary_label ); ?></span>
						<span class="pkae-ct__switch-label pkae-ct__switch-label--on"><?php echo esc_html( $secondary_label ); ?></span>
					<?php endif; ?>
				</button>

				<span class="pkae-ct__label pkae-ct__label--secondary<?php echo $is_secondary ? ' pkae-ct--active' : ''; ?>">
					<?php echo esc_html( $secondary_label ); ?>
				</span>
			</div>

			<div class="pkae-ct__content-wrap">
				<div class="pkae-ct__pane pkae-ct__pane--primary<?php echo ! $is_secondary ? ' pkae-ct--active' : ''; ?>">
					<?php $this->render_pane_content( $s, 'primary', $icon_pos ); ?>
				</div>
				<div class="pkae-ct__pane pkae-ct__pane--secondary<?php echo $is_secondary ? ' pkae-ct--active' : ''; ?>">
					<?php $this->render_pane_content( $s, 'secondary', $icon_pos ); ?>
				</div>
			</div>

		</div>
		<?php
	}

	protected function render_pane_content( $s, $side, $icon_pos = 'left' ) {
		$type     = ! empty( $s[ $side . '_content_type' ] ) ? $s[ $side . '_content_type' ] : 'items';
		$items    = ! empty( $s[ $side . '_items' ] ) ? $s[ $side . '_items' ] : [];
		$template = ! empty( $s[ $side . '_template' ] ) ? (int) $s[ $side . '_template' ] : 0;

		if ( 'template' === $type && $template > 0 ) {
			echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		if ( empty( $items ) ) return;

		echo '<div class="pkae-ct__items">';
		foreach ( $items as $item ) :
			$title     = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
			$title_tag = ! empty( $item['item_title_tag'] ) ? $item['item_title_tag'] : 'h4';
			$desc      = ! empty( $item['item_desc'] ) ? $item['item_desc'] : '';
			$btn_text  = ! empty( $item['item_btn_text'] ) ? $item['item_btn_text'] : '';
			$btn_url   = ! empty( $item['item_btn_link']['url'] ) ? $item['item_btn_link']['url'] : '#';
			$btn_ext   = ! empty( $item['item_btn_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
			$allowed_tags = [ 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span' ];
			$title_tag = in_array( $title_tag, $allowed_tags, true ) ? $title_tag : 'h4';
			$has_media = ! empty( $item['item_image']['url'] ) || ! empty( $item['item_icon']['value'] );
			$item_class = 'pkae-ct__item pkae-ct__item--icon-' . esc_attr( $icon_pos );
			?>
			<div class="<?php echo esc_attr( $item_class ); ?>">
				<?php if ( $has_media && in_array( $icon_pos, [ 'left', 'top' ], true ) ) : ?>
					<div class="pkae-ct__item-media">
						<?php if ( ! empty( $item['item_image']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $item['item_image']['url'] ); ?>" alt="<?php echo esc_attr( $title ); ?>">
						<?php elseif ( ! empty( $item['item_icon']['value'] ) ) : ?>
							<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="pkae-ct__item-body">
					<?php if ( $title ) : ?>
						<<?php echo esc_attr( $title_tag ); ?> class="pkae-ct__item-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
					<?php endif; ?>
					<?php if ( $desc ) : ?>
						<p class="pkae-ct__item-desc"><?php echo esc_html( $desc ); ?></p>
					<?php endif; ?>
					<?php if ( $btn_text ) : ?>
						<a class="pkae-ct__item-btn" href="<?php echo esc_url( $btn_url ); ?>"<?php echo $btn_ext; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php if ( ! empty( $item['item_btn_icon']['value'] ) ) :
								Icons_Manager::render_icon( $item['item_btn_icon'], [ 'aria-hidden' => 'true' ] );
							endif; ?>
							<?php echo esc_html( $btn_text ); ?>
						</a>
					<?php endif; ?>
				</div>

				<?php if ( $has_media && in_array( $icon_pos, [ 'right', 'bottom' ], true ) ) : ?>
					<div class="pkae-ct__item-media">
						<?php if ( ! empty( $item['item_image']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $item['item_image']['url'] ); ?>" alt="<?php echo esc_attr( $title ); ?>">
						<?php elseif ( ! empty( $item['item_icon']['value'] ) ) : ?>
							<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php
		endforeach;
		echo '</div>';
	}
}
