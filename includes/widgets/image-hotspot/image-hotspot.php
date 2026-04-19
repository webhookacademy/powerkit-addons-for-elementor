<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Image_Hotspot extends Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style(
            'pkae-image-hotspot',
            plugins_url( 'assets/css/pkae-image-hotspot.css', __FILE__ ),
            [],
            PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
        );
        wp_register_script(
            'pkae-image-hotspot',
            plugins_url( 'assets/js/pkae-image-hotspot.js', __FILE__ ),
            [ 'jquery', 'elementor-frontend' ],
            PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION,
            true
        );
    }

    public function get_name()          { return 'pkae-image-hotspot'; }
    public function get_title()         { return esc_html__( 'Image Hotspot', 'powerkit-addons-for-elementor' ); }
    public function get_icon()          { return 'eicon-image-hotspot'; }
    public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
    public function get_style_depends() { return [ 'pkae-image-hotspot' ]; }
    public function get_script_depends(){ return [ 'pkae-image-hotspot' ]; }
    public function get_keywords()      { return [ 'hotspot', 'image', 'tooltip', 'marker', 'pin', 'powerkit' ]; }

    protected function register_controls() {

        /* ============================================================
         * CONTENT TAB - Section: Image
         * ============================================================ */
        $this->start_controls_section( 'section_image', [
            'label' => __( 'Image', 'powerkit-addons-for-elementor' ),
        ] );

        $this->add_control( 'hotspot_image', [
            'label'   => __( 'Background Image', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ] );

        $this->add_control( 'hotspot_image_size', [
            'label'   => __( 'Image Size', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'full',
            'options' => [
                'full'      => __( 'Full', 'powerkit-addons-for-elementor' ),
                'large'     => __( 'Large', 'powerkit-addons-for-elementor' ),
                'medium'    => __( 'Medium', 'powerkit-addons-for-elementor' ),
                'thumbnail' => __( 'Thumbnail', 'powerkit-addons-for-elementor' ),
            ],
        ] );

        $this->end_controls_section();

        /* ============================================================
         * CONTENT TAB - Section: Hotspots (Repeater)
         * ============================================================ */
        $this->start_controls_section( 'section_hotspots', [
            'label' => __( 'Hotspots', 'powerkit-addons-for-elementor' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'hotspot_label', [
            'label'   => __( 'Label', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Hotspot', 'powerkit-addons-for-elementor' ),
        ] );

        $repeater->add_control( 'hotspot_position_x', [
            'label'      => __( 'Horizontal Position (%)', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'range'      => [ '%' => [ 'min' => 0, 'max' => 100 ] ],
            'default'    => [ 'size' => 30, 'unit' => '%' ],
        ] );

        $repeater->add_control( 'hotspot_position_y', [
            'label'      => __( 'Vertical Position (%)', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'range'      => [ '%' => [ 'min' => 0, 'max' => 100 ] ],
            'default'    => [ 'size' => 40, 'unit' => '%' ],
        ] );

        $repeater->add_control( 'hotspot_icon', [
            'label'   => __( 'Icon', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-plus', 'library' => 'fa-solid' ],
        ] );

        $repeater->add_control( 'hotspot_icon_type', [
            'label'   => __( 'Marker Type', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
                'icon' => __( 'Icon', 'powerkit-addons-for-elementor' ),
                'text' => __( 'Text', 'powerkit-addons-for-elementor' ),
            ],
        ] );

        $repeater->add_control( 'hotspot_marker_text', [
            'label'     => __( 'Marker Text', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => '+',
            'condition' => [ 'hotspot_icon_type' => 'text' ],
        ] );

        $repeater->add_control( 'tooltip_style', [
            'label'   => __( 'Tooltip Style', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => __( 'Default (Box)', 'powerkit-addons-for-elementor' ),
                'line'    => __( 'Line Style', 'powerkit-addons-for-elementor' ),
            ],
        ] );

        $repeater->add_control( 'tooltip_trigger', [
            'label'   => __( 'Trigger', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'hover',
            'options' => [
                'hover' => __( 'Hover', 'powerkit-addons-for-elementor' ),
                'click' => __( 'Click', 'powerkit-addons-for-elementor' ),
            ],
        ] );

        $repeater->add_control( 'tooltip_position', [
            'label'     => __( 'Tooltip Position', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'top',
            'options'   => [
                'top'    => __( 'Top', 'powerkit-addons-for-elementor' ),
                'bottom' => __( 'Bottom', 'powerkit-addons-for-elementor' ),
                'left'   => __( 'Left', 'powerkit-addons-for-elementor' ),
                'right'  => __( 'Right', 'powerkit-addons-for-elementor' ),
            ],
            'condition' => [ 'tooltip_style' => 'default' ],
        ] );

        $repeater->add_control( 'line_direction', [
            'label'     => __( 'Line Direction', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'right-down',
            'options'   => [
                'right-down' => __( 'Right to Down', 'powerkit-addons-for-elementor' ),
                'right-up'   => __( 'Right to Up', 'powerkit-addons-for-elementor' ),
                'left-down'  => __( 'Left to Down', 'powerkit-addons-for-elementor' ),
                'left-up'    => __( 'Left to Up', 'powerkit-addons-for-elementor' ),
            ],
            'condition' => [ 'tooltip_style' => 'line' ],
        ] );

        $repeater->add_control( 'line_length', [
            'label'      => __( 'Line Length (px)', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 30, 'max' => 300 ] ],
            'default'    => [ 'size' => 80, 'unit' => 'px' ],
            'condition'  => [ 'tooltip_style' => 'line' ],
        ] );

        $repeater->add_control( 'tooltip_title', [
            'label'   => __( 'Tooltip Title', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Hotspot Title', 'powerkit-addons-for-elementor' ),
        ] );

        $repeater->add_control( 'tooltip_description', [
            'label'   => __( 'Description', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __( 'Add your description here.', 'powerkit-addons-for-elementor' ),
        ] );

        $repeater->add_control( 'tooltip_show_link', [
            'label'        => __( 'Show Link Button', 'powerkit-addons-for-elementor' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'powerkit-addons-for-elementor' ),
            'label_off'    => __( 'No', 'powerkit-addons-for-elementor' ),
            'return_value' => 'yes',
            'default'      => '',
        ] );

        $repeater->add_control( 'tooltip_link', [
            'label'     => __( 'Link', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::URL,
            'condition' => [ 'tooltip_show_link' => 'yes' ],
        ] );

        $repeater->add_control( 'tooltip_link_text', [
            'label'     => __( 'Link Text', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => __( 'Learn More', 'powerkit-addons-for-elementor' ),
            'condition' => [ 'tooltip_show_link' => 'yes' ],
        ] );

        $repeater->add_control( 'tooltip_show_image', [
            'label'        => __( 'Show Image', 'powerkit-addons-for-elementor' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'powerkit-addons-for-elementor' ),
            'label_off'    => __( 'No', 'powerkit-addons-for-elementor' ),
            'return_value' => 'yes',
            'default'      => '',
        ] );

        $repeater->add_control( 'tooltip_image', [
            'label'     => __( 'Tooltip Image', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::MEDIA,
            'condition' => [ 'tooltip_show_image' => 'yes' ],
        ] );

        $this->add_control( 'hotspots', [
            'label'       => __( 'Hotspot Items', 'powerkit-addons-for-elementor' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ hotspot_label }}}',
            'default'     => [
                [
                    'hotspot_label'       => __( 'Hotspot 1', 'powerkit-addons-for-elementor' ),
                    'hotspot_position_x'  => [ 'size' => 30, 'unit' => '%' ],
                    'hotspot_position_y'  => [ 'size' => 40, 'unit' => '%' ],
                    'tooltip_title'       => __( 'Hotspot Title', 'powerkit-addons-for-elementor' ),
                    'tooltip_description' => __( 'Add your description here.', 'powerkit-addons-for-elementor' ),
                ],
                [
                    'hotspot_label'       => __( 'Hotspot 2', 'powerkit-addons-for-elementor' ),
                    'hotspot_position_x'  => [ 'size' => 65, 'unit' => '%' ],
                    'hotspot_position_y'  => [ 'size' => 55, 'unit' => '%' ],
                    'tooltip_title'       => __( 'Hotspot Title', 'powerkit-addons-for-elementor' ),
                    'tooltip_description' => __( 'Add your description here.', 'powerkit-addons-for-elementor' ),
                ],
            ],
        ] );

        $this->end_controls_section();

        /* ============================================================
         * CONTENT TAB - Section: Settings
         * ============================================================ */
        $this->start_controls_section( 'section_settings', [
            'label' => __( 'Settings', 'powerkit-addons-for-elementor' ),
        ] );

        $this->add_control( 'hotspot_animation', [
            'label'   => __( 'Marker Animation', 'powerkit-addons-for-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'pulse',
            'options' => [
                'pulse'  => __( 'Pulse', 'powerkit-addons-for-elementor' ),
                'glow'   => __( 'Glow', 'powerkit-addons-for-elementor' ),
                'bounce' => __( 'Bounce', 'powerkit-addons-for-elementor' ),
                'none'   => __( 'None', 'powerkit-addons-for-elementor' ),
            ],
        ] );

        $this->add_control( 'close_on_outside_click', [
            'label'        => __( 'Close on Outside Click', 'powerkit-addons-for-elementor' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'powerkit-addons-for-elementor' ),
            'label_off'    => __( 'No', 'powerkit-addons-for-elementor' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE TAB - Section: Image Style
         * ============================================================ */
        $this->start_controls_section( 'section_style_image', [
            'label' => __( 'Image Style', 'powerkit-addons-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'hotspot_image_border',
            'selector' => '{{WRAPPER}} .pkae-hotspot-image',
        ] );

        $this->add_responsive_control( 'hotspot_image_border_radius', [
            'label'      => __( 'Border Radius', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'hotspot_image_box_shadow',
            'selector' => '{{WRAPPER}} .pkae-hotspot-image',
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE TAB - Section: Marker Style
         * ============================================================ */
        $this->start_controls_section( 'section_style_marker', [
            'label' => __( 'Marker Style', 'powerkit-addons-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'hotspot_marker_size', [
            'label'      => __( 'Marker Size', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
            'default'    => [ 'size' => 36, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-marker-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'hotspot_marker_bg', [
            'label'     => __( 'Background Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4054b2',
            'selectors' => [ '{{WRAPPER}} .pkae-hotspot-marker-icon' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_control( 'hotspot_marker_color', [
            'label'     => __( 'Icon Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .pkae-hotspot-marker-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .pkae-hotspot-marker-icon svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'hotspot_marker_icon_size', [
            'label'      => __( 'Icon Size', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
            'default'    => [ 'size' => 16, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .pkae-hotspot-marker-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .pkae-hotspot-marker-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'hotspot_marker_border_radius', [
            'label'      => __( 'Border Radius', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-marker-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'hotspot_marker_border',
            'selector' => '{{WRAPPER}} .pkae-hotspot-marker-icon',
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'hotspot_marker_box_shadow',
            'selector' => '{{WRAPPER}} .pkae-hotspot-marker-icon',
        ] );

        $this->add_control( 'hotspot_pulse_color', [
            'label'     => __( 'Pulse Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(64,84,178,0.5)',
            'selectors' => [ '{{WRAPPER}} .pkae-hotspot-marker.pkae-pulse::before' => 'background: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE TAB - Section: Line Style
         * ============================================================ */
        $this->start_controls_section( 'section_style_line', [
            'label' => __( 'Line Style', 'powerkit-addons-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'line_color', [
            'label'     => __( 'Line Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f0a500',
            'selectors' => [
                '{{WRAPPER}} .pkae-line-h' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .pkae-line-v' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'line_thickness', [
            'label'      => __( 'Line Thickness', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 1, 'max' => 6 ] ],
            'default'    => [ 'size' => 2, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .pkae-line-h' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .pkae-line-v' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'line_text_color', [
            'label'     => __( 'Text Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .pkae-line-label .pkae-hotspot-tooltip-title' => 'color: {{VALUE}};',
                '{{WRAPPER}} .pkae-line-label .pkae-hotspot-tooltip-desc'  => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'line_title_typography',
            'label'    => __( 'Title Typography', 'powerkit-addons-for-elementor' ),
            'selector' => '{{WRAPPER}} .pkae-line-label .pkae-hotspot-tooltip-title',
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'line_desc_typography',
            'label'    => __( 'Description Typography', 'powerkit-addons-for-elementor' ),
            'selector' => '{{WRAPPER}} .pkae-line-label .pkae-hotspot-tooltip-desc',
        ] );

        $this->add_control( 'line_label_box_heading', [
            'label'     => __( 'Label Box', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'line_label_bg', [
            'label'     => __( 'Background Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [ '{{WRAPPER}} .pkae-line-label' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'line_label_padding', [
            'label'      => __( 'Padding', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-line-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'line_label_margin', [
            'label'      => __( 'Margin', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-line-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'line_label_border_radius', [
            'label'      => __( 'Border Radius', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-line-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'line_label_border',
            'selector' => '{{WRAPPER}} .pkae-line-label',
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'line_label_box_shadow',
            'selector' => '{{WRAPPER}} .pkae-line-label',
        ] );

        $this->end_controls_section();

        /* ============================================================
         * STYLE TAB - Section: Tooltip Style
         * ============================================================ */
        $this->start_controls_section( 'section_style_tooltip', [
            'label' => __( 'Tooltip Style', 'powerkit-addons-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'tooltip_width', [
            'label'      => __( 'Tooltip Width', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 150, 'max' => 500 ] ],
            'default'    => [ 'size' => 220, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-tooltip' => 'width: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'tooltip_bg', [
            'label'     => __( 'Background Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .pkae-hotspot-tooltip' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'tooltip_padding', [
            'label'      => __( 'Padding', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'default'    => [ 'top' => '15', 'right' => '15', 'bottom' => '15', 'left' => '15', 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'tooltip_border_radius', [
            'label'      => __( 'Border Radius', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'tooltip_border',
            'selector' => '{{WRAPPER}} .pkae-hotspot-tooltip',
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'tooltip_box_shadow',
            'selector' => '{{WRAPPER}} .pkae-hotspot-tooltip',
        ] );

        $this->add_control( 'tooltip_title_heading', [
            'label'     => __( 'Title', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'tooltip_title_color', [
            'label'     => __( 'Title Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a2e',
            'selectors' => [ '{{WRAPPER}} .pkae-hotspot-tooltip-title' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'tooltip_title_typography',
            'selector' => '{{WRAPPER}} .pkae-hotspot-tooltip-title',
        ] );

        $this->add_control( 'tooltip_desc_heading', [
            'label'     => __( 'Description', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'tooltip_desc_color', [
            'label'     => __( 'Description Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#555555',
            'selectors' => [ '{{WRAPPER}} .pkae-hotspot-tooltip-desc' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'tooltip_desc_typography',
            'selector' => '{{WRAPPER}} .pkae-hotspot-tooltip-desc',
        ] );

        $this->add_control( 'tooltip_link_heading', [
            'label'     => __( 'Link Button', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'tooltip_link_color', [
            'label'     => __( 'Link Text Color', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .pkae-hotspot-tooltip-link' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'tooltip_link_bg', [
            'label'     => __( 'Link Background', 'powerkit-addons-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4054b2',
            'selectors' => [ '{{WRAPPER}} .pkae-hotspot-tooltip-link' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'tooltip_link_padding', [
            'label'      => __( 'Link Padding', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'default'    => [ 'top' => '6', 'right' => '14', 'bottom' => '6', 'left' => '14', 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-tooltip-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'tooltip_link_border_radius', [
            'label'      => __( 'Link Border Radius', 'powerkit-addons-for-elementor' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .pkae-hotspot-tooltip-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'tooltip_link_typography',
            'selector' => '{{WRAPPER}} .pkae-hotspot-tooltip-link',
        ] );

        $this->end_controls_section();

    } // end register_controls

    protected function render() {
        $settings = $this->get_settings_for_display();

        $image     = $settings['hotspot_image'];
        $img_size  = ! empty( $settings['hotspot_image_size'] ) ? $settings['hotspot_image_size'] : 'full';
        $animation = ! empty( $settings['hotspot_animation'] ) ? $settings['hotspot_animation'] : 'pulse';
        $hotspots  = ! empty( $settings['hotspots'] ) ? $settings['hotspots'] : [];

        if ( empty( $image['url'] ) ) {
            return;
        }

        $img_src = $image['url'];
        $img_alt = '';

        if ( ! empty( $image['id'] ) ) {
            $img_data = wp_get_attachment_image_src( $image['id'], $img_size );
            if ( $img_data ) {
                $img_src = $img_data[0];
            }
            $img_alt = get_post_meta( $image['id'], '_wp_attachment_image_alt', true );
        }

        $close_outside = ( ! empty( $settings['close_on_outside_click'] ) && $settings['close_on_outside_click'] === 'yes' ) ? 'yes' : 'no';
        ?>
        <div class="pkae-hotspot-wrapper" data-close-outside="<?php echo esc_attr( $close_outside ); ?>">
            <div class="pkae-hotspot-image-container">
                <img src="<?php echo esc_url( $img_src ); ?>"
                     class="pkae-hotspot-image"
                     alt="<?php echo esc_attr( $img_alt ); ?>">

                <?php foreach ( $hotspots as $index => $hotspot ) :
                    $pos_x     = ! empty( $hotspot['hotspot_position_x']['size'] ) ? $hotspot['hotspot_position_x']['size'] : 30;
                    $pos_y     = ! empty( $hotspot['hotspot_position_y']['size'] ) ? $hotspot['hotspot_position_y']['size'] : 40;
                    $trigger   = ! empty( $hotspot['tooltip_trigger'] ) ? $hotspot['tooltip_trigger'] : 'hover';
                    $position  = ! empty( $hotspot['tooltip_position'] ) ? $hotspot['tooltip_position'] : 'top';
                    $icon_type = ! empty( $hotspot['hotspot_icon_type'] ) ? $hotspot['hotspot_icon_type'] : 'icon';
                    $tip_style = ! empty( $hotspot['tooltip_style'] ) ? $hotspot['tooltip_style'] : 'default';
                    $line_dir  = ! empty( $hotspot['line_direction'] ) ? $hotspot['line_direction'] : 'right-down';
                    $line_len  = ! empty( $hotspot['line_length']['size'] ) ? intval( $hotspot['line_length']['size'] ) : 80;
                    $anim_class = ( 'none' !== $animation ) ? ' pkae-' . esc_attr( $animation ) : '';
                ?>
                <div class="pkae-hotspot-marker<?php echo esc_attr( $anim_class ); ?> pkae-trigger-<?php echo esc_attr( $trigger ); ?> pkae-style-<?php echo esc_attr( $tip_style ); ?>"
                     style="left:<?php echo esc_attr( $pos_x ); ?>%; top:<?php echo esc_attr( $pos_y ); ?>%;"
                     data-trigger="<?php echo esc_attr( $trigger ); ?>"
                     data-position="<?php echo esc_attr( $position ); ?>"
                     data-tooltip-style="<?php echo esc_attr( $tip_style ); ?>"
                     data-line-dir="<?php echo esc_attr( $line_dir ); ?>"
                     data-line-len="<?php echo esc_attr( $line_len ); ?>">

                    <div class="pkae-hotspot-marker-icon">
                        <?php if ( 'text' === $icon_type ) : ?>
                            <span class="pkae-hotspot-marker-text">
                                <?php echo esc_html( ! empty( $hotspot['hotspot_marker_text'] ) ? $hotspot['hotspot_marker_text'] : '+' ); ?>
                            </span>
                        <?php else : ?>
                            <?php if ( ! empty( $hotspot['hotspot_icon']['value'] ) ) : ?>
                                <?php \Elementor\Icons_Manager::render_icon( $hotspot['hotspot_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            <?php else : ?>
                                <i class="fas fa-plus" aria-hidden="true"></i>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ( 'line' === $tip_style ) : ?>
                    <!-- Line Style Tooltip -->
                    <div class="pkae-line-tooltip" data-dir="<?php echo esc_attr( $line_dir ); ?>">
                        <div class="pkae-line-h"></div>
                        <div class="pkae-line-v"></div>
                        <div class="pkae-line-label">
                            <?php if ( ! empty( $hotspot['tooltip_title'] ) ) : ?>
                                <h4 class="pkae-hotspot-tooltip-title"><?php echo esc_html( $hotspot['tooltip_title'] ); ?></h4>
                            <?php endif; ?>
                            <?php if ( ! empty( $hotspot['tooltip_description'] ) ) : ?>
                                <p class="pkae-hotspot-tooltip-desc"><?php echo esc_html( $hotspot['tooltip_description'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php else : ?>
                    <!-- Default Box Tooltip -->
                    <div class="pkae-hotspot-tooltip pkae-tooltip-<?php echo esc_attr( $position ); ?>">
                        <div class="pkae-tooltip-arrow"></div>
                        <div class="pkae-tooltip-inner">

                            <?php if ( 'yes' === $hotspot['tooltip_show_image'] && ! empty( $hotspot['tooltip_image']['url'] ) ) : ?>
                                <img src="<?php echo esc_url( $hotspot['tooltip_image']['url'] ); ?>"
                                     class="pkae-hotspot-tooltip-image" alt="">
                            <?php endif; ?>

                            <?php if ( ! empty( $hotspot['tooltip_title'] ) ) : ?>
                                <h4 class="pkae-hotspot-tooltip-title"><?php echo esc_html( $hotspot['tooltip_title'] ); ?></h4>
                            <?php endif; ?>

                            <?php if ( ! empty( $hotspot['tooltip_description'] ) ) : ?>
                                <p class="pkae-hotspot-tooltip-desc"><?php echo esc_html( $hotspot['tooltip_description'] ); ?></p>
                            <?php endif; ?>

                            <?php if ( 'yes' === $hotspot['tooltip_show_link'] && ! empty( $hotspot['tooltip_link']['url'] ) ) :
                                $link_url    = esc_url( $hotspot['tooltip_link']['url'] );
                                $link_target = ! empty( $hotspot['tooltip_link']['is_external'] ) ? '_blank' : '_self';
                                $link_rel    = ! empty( $hotspot['tooltip_link']['nofollow'] ) ? 'nofollow' : 'noopener';
                                $link_text   = ! empty( $hotspot['tooltip_link_text'] ) ? $hotspot['tooltip_link_text'] : __( 'Learn More', 'powerkit-addons-for-elementor' );
                            ?>
                                <a href="<?php echo $link_url; ?>"
                                   class="pkae-hotspot-tooltip-link"
                                   target="<?php echo esc_attr( $link_target ); ?>"
                                   rel="<?php echo esc_attr( $link_rel ); ?>">
                                    <?php echo esc_html( $link_text ); ?>
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>

            </div>
        </div>
        <?php
    }

}
