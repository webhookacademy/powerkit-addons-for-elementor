<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Accordion_Slider extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'pkae-accordion-slider-style',
			plugins_url( 'assets/css/pkae-accordion-slider.css', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);

		wp_register_script(
			'pkae-accordion-slider-script',
			plugins_url( 'assets/js/pkae-accordion-slider.js', __FILE__ ),
			[ 'jquery' ],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);

	}

	public function get_name() {
		return 'pkae-accordion-slider';
	}

	public function get_title() {
		return esc_html__( 'Accordion Slider', 'powerkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-slider-device';
	}

	public function get_categories() {
		return [ 'powerkit-carousel-and-slider-categories' ];
	}

	public function get_style_depends() {
		return [ 'pkae-accordion-slider-style' ];
	}

	public function get_script_depends() {
		return [ 'pkae-accordion-slider-script' ];
	}

	protected function register_controls() {

		$this->start_controls_section( 'section_slides', [
			'label' => esc_html__( 'Slides', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Designers', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'desc', [
			'label'   => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Tools that work like you do.', 'powerkit-addons-for-elementor' ),
			'rows'    => 3,
		] );

		$repeater->add_control( 'bg_image', [
			'label'   => esc_html__( 'Background Image', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
		] );

		$repeater->add_control( 'thumb_image', [
			'label'   => esc_html__( 'Thumb Image', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
		] );

		$repeater->add_control( 'button_text', [
			'label'   => esc_html__( 'Button Text', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Details', 'powerkit-addons-for-elementor' ),
		] );

		$repeater->add_control( 'button_link', [
			'label'       => esc_html__( 'Button Link', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
			'default'     => [ 'url' => '#' ],
		] );

		$this->add_control( 'slides', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[ 'title' => 'Designers',         'desc' => 'Tools that work like you do.' ],
				[ 'title' => 'Marketers',         'desc' => 'Create faster, explore new possibilities.' ],
				[ 'title' => 'VFX filmmakers',    'desc' => 'From concept to cut, faster.' ],
				[ 'title' => 'Content creators',  'desc' => 'Make scroll-stopping content, easily.' ],
				[ 'title' => 'Art directors',     'desc' => 'Creative control at every stage.' ],
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_nav', [
			'label' => esc_html__( 'Navigation', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_arrows', [
			'label'        => esc_html__( 'Show Arrows', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'powerkit-addons-for-elementor' ),
			'label_off'    => esc_html__( 'No', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'prev_icon', [
			'label'     => esc_html__( 'Prev Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ],
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_control( 'next_icon', [
			'label'     => esc_html__( 'Next Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ],
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_control( 'arrows_position', [
			'label'   => esc_html__( 'Arrows Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'top-right',
			'options' => [
				'top-right'  => esc_html__( 'Top Right', 'powerkit-addons-for-elementor' ),
				'top-left'   => esc_html__( 'Top Left', 'powerkit-addons-for-elementor' ),
				'top-center' => esc_html__( 'Top Center', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_control( 'show_dots', [
			'label'        => esc_html__( 'Show Dots', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'powerkit-addons-for-elementor' ),
			'label_off'    => esc_html__( 'No', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );

		$this->add_control( 'dots_style', [
			'label'   => esc_html__( 'Dots Style', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'circle',
			'options' => [
				'circle'  => esc_html__( 'Circle', 'powerkit-addons-for-elementor' ),
				'square'  => esc_html__( 'Square', 'powerkit-addons-for-elementor' ),
				'line'    => esc_html__( 'Line', 'powerkit-addons-for-elementor' ),
				'stretch' => esc_html__( 'Stretch Active', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_dots' => 'yes' ],
		] );

		$this->add_control( 'dots_position', [
			'label'   => esc_html__( 'Dots Position', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'bottom-center',
			'options' => [
				'bottom-center' => esc_html__( 'Bottom Center', 'powerkit-addons-for-elementor' ),
				'bottom-left'   => esc_html__( 'Bottom Left', 'powerkit-addons-for-elementor' ),
				'bottom-right'  => esc_html__( 'Bottom Right', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'show_dots' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── Autoplay ──────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_autoplay', [
			'label' => esc_html__( 'Autoplay', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'autoplay', [
			'label'        => esc_html__( 'Autoplay', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'powerkit-addons-for-elementor' ),
			'label_off'    => esc_html__( 'No', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'autoplay_speed', [
			'label'     => esc_html__( 'Interval (ms)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 3000,
			'min'       => 500,
			'max'       => 10000,
			'step'      => 100,
			'condition' => [ 'autoplay' => 'yes' ],
		] );

		$this->add_control( 'pause_on_hover', [
			'label'        => esc_html__( 'Pause on Hover', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'powerkit-addons-for-elementor' ),
			'label_off'    => esc_html__( 'No', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [ 'autoplay' => 'yes' ],
		] );

		$this->add_control( 'loop', [
			'label'        => esc_html__( 'Loop', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'powerkit-addons-for-elementor' ),
			'label_off'    => esc_html__( 'No', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'no',
			'condition'    => [ 'autoplay' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── Animation ─────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_animation', [
			'label' => esc_html__( 'Animation', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'transition_speed', [
			'label'   => esc_html__( 'Transition Speed (ms)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 550,
			'min'     => 100,
			'max'     => 2000,
			'step'    => 50,
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card' => 'transition-duration: {{VALUE}}ms;',
				'{{WRAPPER}} .pkae-accordion-slider .project-card__bg' => 'transition-duration: {{VALUE}}ms;',
			],
		] );

		$this->add_control( 'transition_easing', [
			'label'   => esc_html__( 'Easing', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'ease-in-out',
			'options' => [
				'ease'        => 'Ease',
				'ease-in'     => 'Ease In',
				'ease-out'    => 'Ease Out',
				'ease-in-out' => 'Ease In Out',
				'linear'      => 'Linear',
				'cubic-bezier(0.25,0.46,0.45,0.94)' => 'Smooth (default)',
				'cubic-bezier(0.68,-0.55,0.27,1.55)' => 'Bounce',
			],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card' => 'transition-timing-function: {{VALUE}};',
			],
		] );

		$this->add_control( 'active_lift', [
			'label'   => esc_html__( 'Active Card Lift (px)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default' => [ 'size' => 6, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card[active]' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
			],
		] );

		$this->end_controls_section();

		// ── Box ──────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'box_bg_color', [
			'label'     => esc_html__( 'Background Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'height', [
			'label' => esc_html__( 'Card Height (px)', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 200, 'max' => 800 ] ],
			'default' => [ 'size' => 416, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'closed_width', [
			'label'      => esc_html__( 'Closed Width (rem)', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'rem' ],
			'range'      => [ 'rem' => [ 'min' => 3, 'max' => 20 ] ],
			'default'    => [ 'size' => 5, 'unit' => 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--pkae-closed: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'open_width', [
			'label'      => esc_html__( 'Open Width (rem)', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'rem' ],
			'range'      => [ 'rem' => [ 'min' => 12, 'max' => 60 ] ],
			'default'    => [ 'size' => 30, 'unit' => 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--pkae-open: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'gap', [
			'label'      => esc_html__( 'Gap (rem)', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'rem' ],
			'range'      => [ 'rem' => [ 'min' => .25, 'max' => 3, 'step' => .05 ] ],
			'default'    => [ 'size' => 1.25, 'unit' => 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--pkae-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'default'    => [
				'top'    => '16',
				'right'  => '16',
				'bottom' => '16',
				'left'   => '16',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'card_border',
			'label'    => esc_html__( 'Card Border', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .project-card',
		] );

		$this->end_controls_section();

		// ── Arrow Style ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_arrow_style', [
			'label'     => esc_html__( 'Arrow', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_responsive_control( 'arrow_width', [
			'label'      => esc_html__( 'Button Width (px)', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 200 ] ],
			'default'    => [ 'size' => 40, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider .nav-btn' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'arrow_height', [
			'label'      => esc_html__( 'Button Height (px)', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 200 ] ],
			'default'    => [ 'size' => 40, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider .nav-btn' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'arrow_icon_size', [
			'label'   => esc_html__( 'Icon Size (px)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 8, 'max' => 60 ] ],
			'default' => [ 'size' => 16, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .nav-btn' => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-accordion-slider .nav-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'arrow_border_radius', [
			'label'   => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default' => [ 'size' => 50, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .nav-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'arrow_spacing', [
			'label'      => esc_html__( 'Vertical Spacing', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'allowed_dimensions' => [ 'top', 'bottom' ],
			'default'    => [
				'top'    => '30',
				'bottom' => '20',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider .head' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->start_controls_tabs( 'arrow_tabs' );

		$this->start_controls_tab( 'arrow_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'arrow_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .nav-btn' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
		] );

		$this->add_control( 'arrow_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#DEC1E2DE',
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .nav-btn' => 'background: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'arrow_border',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .nav-btn',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'arrow_shadow',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .nav-btn',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'arrow_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'arrow_hover_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .nav-btn:hover' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
		] );

		$this->add_control( 'arrow_hover_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .nav-btn:hover' => 'background: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'arrow_hover_border',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .nav-btn:hover',
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── Dots Style ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_dots_style', [
			'label'     => esc_html__( 'Dots', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_dots' => 'yes' ],
		] );

		$this->add_responsive_control( 'dot_size', [
			'label'   => esc_html__( 'Dot Size (px)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 4, 'max' => 30 ] ],
			'default' => [ 'size' => 13, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'dot_gap', [
			'label'   => esc_html__( 'Gap (px)', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 2, 'max' => 20 ] ],
			'default' => [ 'size' => 8, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .dots' => 'gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'dot_color', [
			'label'     => esc_html__( 'Inactive Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(171,52,240,0.7)',
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .dot' => 'background: {{VALUE}};' ],
		] );

		$this->add_control( 'dot_active_color', [
			'label'     => esc_html__( 'Active Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .dot.active' => 'background: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'dots_spacing', [
			'label'      => esc_html__( 'Vertical Spacing', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'allowed_dimensions' => [ 'top', 'bottom' ],
			'default'    => [
				'top'    => '16',
				'bottom' => '16',
				'unit'   => 'px',
			],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider .dots' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->end_controls_section();

		// ── Typography ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_typo', [
			'label' => esc_html__( 'Typography', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typo',
			'label'    => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .project-card__title',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'desc_typo',
			'label'    => esc_html__( 'Description', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .project-card__desc',
		] );

		$this->end_controls_section();

		// ── Button Style ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_btn_style', [
			'label' => esc_html__( 'Button', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'btn_typo',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .project-card__btn',
		] );

		$this->add_responsive_control( 'btn_width', [
			'label'      => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'range'      => [
				'px' => [ 'min' => 50, 'max' => 500 ],
				'%'  => [ 'min' => 10, 'max' => 100 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card__btn' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'btn_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'btn_border_radius', [
			'label'   => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default' => [ 'size' => 9999, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-slider .project-card__btn' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'btn_tabs' );

		$this->start_controls_tab( 'btn_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'btn_text_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .project-card__btn' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'btn_bg_color', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#B6B6B6',
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .project-card__btn' => 'background: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'btn_border',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .project-card__btn',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'btn_shadow',
			'selector' => '{{WRAPPER}} .pkae-accordion-slider .project-card__btn',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'btn_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );

		$this->add_control( 'btn_hover_text_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .project-card__btn:hover' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'btn_hover_bg_color', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-accordion-slider .project-card__btn:hover' => 'background: {{VALUE}};' ],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings    = $this->get_settings_for_display();
		$slides      = ! empty( $settings['slides'] ) ? $settings['slides'] : [];
		$show_dots   = ( isset( $settings['show_dots'] ) && 'yes' === $settings['show_dots'] ) ? 'yes' : 'no';
		$show_arrows = ( isset( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'] ) ? 'yes' : 'no';
		$autoplay    = ( isset( $settings['autoplay'] ) && 'yes' === $settings['autoplay'] ) ? 'yes' : 'no';
		$autoplay_speed  = ! empty( $settings['autoplay_speed'] ) ? intval( $settings['autoplay_speed'] ) : 3000;
		$pause_on_hover  = ( isset( $settings['pause_on_hover'] ) && 'yes' === $settings['pause_on_hover'] ) ? 'yes' : 'no';
		$loop            = ( isset( $settings['loop'] ) && 'yes' === $settings['loop'] ) ? 'yes' : 'no';
		$dots_style      = ! empty( $settings['dots_style'] ) ? $settings['dots_style'] : 'circle';
		$dots_position   = ! empty( $settings['dots_position'] ) ? $settings['dots_position'] : 'bottom-center';
		$arrows_position = ! empty( $settings['arrows_position'] ) ? $settings['arrows_position'] : 'top-right';

		$wid = 'pkae-accordion-slider-' . esc_attr( $this->get_id() );
		?>
		<section id="<?php echo esc_attr( $wid ); ?>"
			class="pkae-accordion-slider pkae-arrows-<?php echo esc_attr( $arrows_position ); ?> pkae-dots-<?php echo esc_attr( $dots_position ); ?>"
			data-show-dots="<?php echo esc_attr( $show_dots ); ?>"
			data-show-arrows="<?php echo esc_attr( $show_arrows ); ?>"
			data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
			data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>"
			data-pause-on-hover="<?php echo esc_attr( $pause_on_hover ); ?>"
			data-loop="<?php echo esc_attr( $loop ); ?>"
			data-dots-style="<?php echo esc_attr( $dots_style ); ?>"
			aria-roledescription="carousel">

			<div class="head">
				<div class="controls">
					<button class="nav-btn" data-pkae-prev aria-label="<?php esc_attr_e( 'Previous slide', 'powerkit-addons-for-elementor' ); ?>">
						<?php
						if ( ! empty( $settings['prev_icon']['value'] ) ) {
							Icons_Manager::render_icon( $settings['prev_icon'], [ 'aria-hidden' => 'true' ] );
						} else {
							echo '<span aria-hidden="true">&#8249;</span>';
						}
						?>
					</button>
					<button class="nav-btn" data-pkae-next aria-label="<?php esc_attr_e( 'Next slide', 'powerkit-addons-for-elementor' ); ?>">
						<?php
						if ( ! empty( $settings['next_icon']['value'] ) ) {
							Icons_Manager::render_icon( $settings['next_icon'], [ 'aria-hidden' => 'true' ] );
						} else {
							echo '<span aria-hidden="true">&#8250;</span>';
						}
						?>
					</button>
				</div>
			</div>

			<div class="slider">
				<div class="track">
					<?php
					$idx = 0;
					foreach ( $slides as $slide ) :
						$title     = isset( $slide['title'] ) ? $slide['title'] : '';
						$desc      = isset( $slide['desc'] ) ? $slide['desc'] : '';
						$bg        = isset( $slide['bg_image']['url'] ) ? $slide['bg_image']['url'] : Utils::get_placeholder_image_src();
						$thumb     = isset( $slide['thumb_image']['url'] ) ? $slide['thumb_image']['url'] : Utils::get_placeholder_image_src();
						$btn_text  = ! empty( $slide['button_text'] ) ? $slide['button_text'] : esc_html__( 'Details', 'powerkit-addons-for-elementor' );
						$link      = isset( $slide['button_link']['url'] ) ? $slide['button_link']['url'] : '#';
						$target    = ! empty( $slide['button_link']['is_external'] ) ? ' target="_blank" rel="noopener"' : '';
						?>
						<article class="project-card" <?php echo ( 0 === $idx ) ? 'active' : ''; ?> role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $idx + 1 ) . ' / ' . count( $slides ) ); ?>">
							<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
							<img class="project-card__bg" src="<?php echo esc_url( $bg ); ?>" alt="">
							<div class="project-card__content">
								<?php if ( $thumb ) : ?>
									<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
									<img class="project-card__thumb" src="<?php echo esc_url( $thumb ); ?>" alt="">
								<?php endif; ?>
								<div>
									<?php if ( $title ) : ?>
										<h3 class="project-card__title"><?php echo esc_html( $title ); ?></h3>
									<?php endif; ?>
									<?php if ( $desc ) : ?>
										<p class="project-card__desc"><?php echo esc_html( $desc ); ?></p>
									<?php endif; ?>
									<?php if ( $btn_text ) : ?>
										<a class="project-card__btn" href="<?php echo esc_url( $link ); ?>" <?php echo esc_attr( $target ); ?>>
											<?php echo esc_html( $btn_text ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</article>
						<?php
						$idx++;
					endforeach;
					?>
				</div>
			</div>

			<div class="dots pkae-dots-<?php echo esc_attr( $dots_style ); ?>" aria-hidden="false"></div>
		</section>
		<?php
	}
}
