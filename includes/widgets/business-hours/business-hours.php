<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class Business_Hours extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style(
			'pkae-business-hours',
			plugins_url( 'assets/css/pkae-business-hours.css', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
	}

	public function get_name()          { return 'pkae-business-hours'; }
	public function get_title()         { return esc_html__( 'Business Hours', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-clock-o'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-business-hours' ]; }
	public function get_keywords()      { return [ 'business hours', 'opening hours', 'schedule', 'time', 'powerkit' ]; }

	protected function register_controls() {

		// ── CONTENT ───────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_title', [
			'label' => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'title', [
			'label'   => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Business Hours', 'powerkit-addons-for-elementor' ),
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'title_icon', [
			'label'   => esc_html__( 'Title Icon', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => '', 'library' => '' ],
		] );

		$this->end_controls_section();

		// ── HOURS ─────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_hours', [
			'label' => esc_html__( 'Business Hours', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'day', [
			'label'   => esc_html__( 'Day', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Monday', 'powerkit-addons-for-elementor' ),
		] );

		$repeater->add_control( 'day_icon', [
			'label'   => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => '', 'library' => '' ],
		] );

		$repeater->add_control( 'closed', [
			'label'        => esc_html__( 'Closed', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$repeater->add_control( 'closed_text', [
			'label'     => esc_html__( 'Closed Label', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Closed', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'closed' => 'yes' ],
		] );

		$repeater->add_control( 'open_time', [
			'label'     => esc_html__( 'Opening Time', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '09:00 AM',
			'condition' => [ 'closed!' => 'yes' ],
		] );

		$repeater->add_control( 'close_time', [
			'label'     => esc_html__( 'Closing Time', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '06:00 PM',
			'condition' => [ 'closed!' => 'yes' ],
		] );

		$repeater->add_control( 'highlight', [
			'label'        => esc_html__( 'Highlight Row', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
			'separator'    => 'before',
		] );

		$this->add_control( 'hours', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ day }}}',
			'default'     => [
				[ 'day' => 'Monday',    'open_time' => '09:00 AM', 'close_time' => '06:00 PM' ],
				[ 'day' => 'Tuesday',   'open_time' => '09:00 AM', 'close_time' => '06:00 PM' ],
				[ 'day' => 'Wednesday', 'open_time' => '09:00 AM', 'close_time' => '06:00 PM' ],
				[ 'day' => 'Thursday',  'open_time' => '09:00 AM', 'close_time' => '06:00 PM' ],
				[ 'day' => 'Friday',    'open_time' => '09:00 AM', 'close_time' => '06:00 PM' ],
				[ 'day' => 'Saturday',  'open_time' => '10:00 AM', 'close_time' => '04:00 PM' ],
				[ 'day' => 'Sunday',    'closed' => 'yes', 'closed_text' => 'Closed' ],
			],
		] );

		$this->end_controls_section();

		// ── EXTRA FEATURES ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_extra', [
			'label' => esc_html__( 'Extra', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'auto_highlight_today', [
			'label'        => esc_html__( 'Auto Highlight Today', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'description'  => esc_html__( 'Automatically highlights the current day row.', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'show_open_badge', [
			'label'        => esc_html__( 'Show Open/Closed Badge', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'description'  => esc_html__( 'Shows a live "Open Now" or "Closed Now" badge based on current time.', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'open_badge_text', [
			'label'     => esc_html__( 'Open Badge Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Open Now', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_open_badge' => 'yes' ],
		] );

		$this->add_control( 'closed_badge_text', [
			'label'     => esc_html__( 'Closed Badge Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Closed Now', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_open_badge' => 'yes' ],
		] );

		$this->add_control( 'time_separator', [
			'label'   => esc_html__( 'Time Separator', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '-',
		] );

		$this->end_controls_section();

		// ── STYLE: Box ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_bg',
			'selector' => '{{WRAPPER}} .pkae-bh',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', 'rem' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-bh',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-bh',
		] );

		$this->end_controls_section();

		// ── STYLE: Title ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_title', [
			'label'     => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'title!' => '' ],
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-bh__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'title_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-bh__title i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-bh__title svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'title_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 8, 'max' => 80 ] ],
			'default'    => [ 'size' => 22, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-bh__title i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-bh__title svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'title_icon_gap', [
			'label'      => esc_html__( 'Icon Spacing', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
			'default'    => [ 'size' => 8, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh__title' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-bh__title',
		] );

		$this->add_responsive_control( 'title_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'selectors' => [ '{{WRAPPER}} .pkae-bh__title' => 'justify-content: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Row ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_row', [
			'label' => esc_html__( 'Row', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'row_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-bh__row' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'row_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '12', 'right' => '16', 'bottom' => '12', 'left' => '16', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh__row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'row_gap', [
			'label'     => esc_html__( 'Row Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'   => [ 'size' => 4, 'unit' => 'px' ],
			'selectors' => [ '{{WRAPPER}} .pkae-bh__list' => 'gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'row_border',
			'selector' => '{{WRAPPER}} .pkae-bh__row',
		] );

		$this->add_responsive_control( 'row_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh__row' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Day ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_day', [
			'label' => esc_html__( 'Day', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'day_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-bh__day' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'day_typo',
			'selector' => '{{WRAPPER}} .pkae-bh__day',
		] );

		$this->add_control( 'day_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-bh__day-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-bh__day-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'day_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 8, 'max' => 40 ] ],
			'default'   => [ 'size' => 18, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-bh__day-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-bh__day-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// ── STYLE: Time ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_time', [
			'label' => esc_html__( 'Time', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'time_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-bh__time' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'time_typo',
			'selector' => '{{WRAPPER}} .pkae-bh__time',
		] );

		$this->add_control( 'closed_color', [
			'label'     => esc_html__( 'Closed Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-bh__row--closed .pkae-bh__time' => 'color: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->end_controls_section();

		// ── STYLE: Highlight ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_highlight', [
			'label' => esc_html__( 'Highlight (Today / Manual)', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'highlight_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f0f4ff',
			'selectors' => [ '{{WRAPPER}} .pkae-bh__row--highlight' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'highlight_day_color', [
			'label'     => esc_html__( 'Day Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-bh__row--highlight .pkae-bh__day' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'highlight_time_color', [
			'label'     => esc_html__( 'Time Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-bh__row--highlight .pkae-bh__time' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'highlight_border',
			'selector' => '{{WRAPPER}} .pkae-bh__row--highlight',
		] );

		$this->end_controls_section();

		// ── STYLE: Badge ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_badge', [
			'label'     => esc_html__( 'Open/Closed Badge', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_open_badge' => 'yes' ],
		] );

		$this->add_control( 'badge_open_bg', [
			'label'     => esc_html__( 'Open Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#22c55e',
			'selectors' => [ '{{WRAPPER}} .pkae-bh__badge--open' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'badge_closed_bg', [
			'label'     => esc_html__( 'Closed Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ef4444',
			'selectors' => [ '{{WRAPPER}} .pkae-bh__badge--closed' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'badge_text_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-bh__badge' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge_typo',
			'selector' => '{{WRAPPER}} .pkae-bh__badge',
		] );

		$this->add_responsive_control( 'badge_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '4', 'right' => '12', 'bottom' => '4', 'left' => '12', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'badge_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'badge_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-bh__badge-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'badge_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'selectors' => [ '{{WRAPPER}} .pkae-bh__badge-wrap' => 'justify-content: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s               = $this->get_settings_for_display();
		$hours           = ! empty( $s['hours'] ) ? $s['hours'] : [];
		$title           = ! empty( $s['title'] ) ? $s['title'] : '';
		$separator       = ! empty( $s['time_separator'] ) ? $s['time_separator'] : '-';
		$auto_today      = isset( $s['auto_highlight_today'] ) && 'yes' === $s['auto_highlight_today'];
		$show_badge      = isset( $s['show_open_badge'] ) && 'yes' === $s['show_open_badge'];
		$open_badge_txt  = ! empty( $s['open_badge_text'] ) ? $s['open_badge_text'] : esc_html__( 'Open Now', 'powerkit-addons-for-elementor' );
		$closed_badge_txt = ! empty( $s['closed_badge_text'] ) ? $s['closed_badge_text'] : esc_html__( 'Closed Now', 'powerkit-addons-for-elementor' );

		// Current day index: 0=Sunday … 6=Saturday → map to day names
		$today_name = gmdate( 'l' ); // e.g. "Monday"

		// Determine open/closed badge based on today's row
		$is_open_now = false;
		if ( $show_badge ) {
			foreach ( $hours as $row ) {
				if ( strtolower( trim( $row['day'] ) ) === strtolower( $today_name ) ) {
					if ( empty( $row['closed'] ) || 'yes' !== $row['closed'] ) {
						$is_open_now = true;
					}
					break;
				}
			}
		}

		Icons_Manager::enqueue_shim();
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'elementor-icons' );
		?>
		<div class="pkae-bh">

			<?php if ( $title || ! empty( $s['title_icon']['value'] ) ) : ?>
				<div class="pkae-bh__title">
					<?php if ( ! empty( $s['title_icon']['value'] ) ) :
						Icons_Manager::render_icon( $s['title_icon'], [ 'aria-hidden' => 'true' ] );
					endif; ?>
					<?php if ( $title ) : ?>
						<span><?php echo esc_html( $title ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $show_badge ) : ?>
				<div class="pkae-bh__badge-wrap">
					<?php if ( $is_open_now ) : ?>
						<span class="pkae-bh__badge pkae-bh__badge--open"><?php echo esc_html( $open_badge_txt ); ?></span>
					<?php else : ?>
						<span class="pkae-bh__badge pkae-bh__badge--closed"><?php echo esc_html( $closed_badge_txt ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<ul class="pkae-bh__list">
				<?php foreach ( $hours as $row ) :
					$is_closed    = ! empty( $row['closed'] ) && 'yes' === $row['closed'];
					$is_highlight = ( ! empty( $row['highlight'] ) && 'yes' === $row['highlight'] );
					$is_today     = $auto_today && ( strtolower( trim( $row['day'] ) ) === strtolower( $today_name ) );
					$classes      = 'pkae-bh__row';
					if ( $is_closed )                 $classes .= ' pkae-bh__row--closed';
					if ( $is_highlight || $is_today ) $classes .= ' pkae-bh__row--highlight';
					?>
					<li class="<?php echo esc_attr( $classes ); ?>">
						<span class="pkae-bh__day">
							<?php if ( ! empty( $row['day_icon']['value'] ) ) : ?>
								<span class="pkae-bh__day-icon">
									<?php Icons_Manager::render_icon( $row['day_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php endif; ?>
							<?php echo esc_html( $row['day'] ); ?>
						</span>
						<span class="pkae-bh__time">
							<?php if ( $is_closed ) : ?>
								<?php echo esc_html( ! empty( $row['closed_text'] ) ? $row['closed_text'] : esc_html__( 'Closed', 'powerkit-addons-for-elementor' ) ); ?>
							<?php else : ?>
								<?php echo esc_html( $row['open_time'] ); ?>
								<span class="pkae-bh__sep"><?php echo esc_html( $separator ); ?></span>
								<?php echo esc_html( $row['close_time'] ); ?>
							<?php endif; ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>

		</div>
		<?php
	}
}
