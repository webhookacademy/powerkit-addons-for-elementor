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

class Table extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-table', plugins_url( 'assets/css/pkae-table.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_script( 'pkae-table', plugins_url( 'assets/js/pkae-table.js', __FILE__ ), [ 'jquery' ], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	public function get_name()          { return 'pkae-table'; }
	public function get_title()         { return esc_html__( 'Table', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-table'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-table' ]; }
	public function get_script_depends(){ return [ 'pkae-table' ]; }
	public function get_keywords()      { return [ 'table', 'data table', 'sortable', 'responsive', 'comparison', 'powerkit' ]; }

	protected function register_controls() {

		// ── HEADER ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_header', [
			'label' => esc_html__( 'Table Header', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$header_repeater = new Repeater();

		$header_repeater->add_control( 'cell_text', [
			'label'       => esc_html__( 'Header Text', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Column', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$header_repeater->add_control( 'cell_media_type', [
			'label'     => esc_html__( 'Media Type', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'none',
			'options'   => [
				'none'  => esc_html__( 'N/A', 'powerkit-addons-for-elementor' ),
				'icon'  => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
				'image' => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			],
		] );

		$header_repeater->add_control( 'cell_icon', [
			'label'     => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => '', 'library' => '' ],
			'condition' => [ 'cell_media_type' => 'icon' ],
		] );

		$header_repeater->add_control( 'cell_icon_position', [
			'label'     => esc_html__( 'Icon Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'before',
			'options'   => [
				'before' => esc_html__( 'Before Text', 'powerkit-addons-for-elementor' ),
				'after'  => esc_html__( 'After Text', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'cell_media_type' => 'icon' ],
		] );

		$header_repeater->add_control( 'cell_image', [
			'label'     => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::MEDIA,
			'condition' => [ 'cell_media_type' => 'image' ],
		] );

		$header_repeater->add_control( 'cell_image_position', [
			'label'     => esc_html__( 'Image Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'before',
			'options'   => [
				'before' => esc_html__( 'Before Text', 'powerkit-addons-for-elementor' ),
				'after'  => esc_html__( 'After Text', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'cell_media_type' => 'image' ],
		] );

		$header_repeater->add_control( 'cell_colspan', [
			'label'   => esc_html__( 'Colspan', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 1,
			'min'     => 1,
			'max'     => 20,
		] );

		$header_repeater->add_control( 'cell_align', [
			'label'   => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default' => 'left',
		] );

		$this->add_control( 'header_cells', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $header_repeater->get_controls(),
			'title_field' => '{{{ cell_text }}}',
			'default'     => [
				[ 'cell_text' => esc_html__( 'Name', 'powerkit-addons-for-elementor' ) ],
				[ 'cell_text' => esc_html__( 'Category', 'powerkit-addons-for-elementor' ) ],
				[ 'cell_text' => esc_html__( 'Price', 'powerkit-addons-for-elementor' ) ],
				[ 'cell_text' => esc_html__( 'Status', 'powerkit-addons-for-elementor' ) ],
			],
		] );

		$this->end_controls_section();

		// ── BODY ──────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_body', [
			'label' => esc_html__( 'Table Body', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$row_repeater  = new Repeater();
		$cell_repeater = new Repeater();

		$cell_repeater->add_control( 'cell_content', [
			'label'       => esc_html__( 'Cell Content', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Cell', 'powerkit-addons-for-elementor' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$cell_repeater->add_control( 'cell_type', [
			'label'   => esc_html__( 'Cell Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'td',
			'options' => [
				'td' => esc_html__( 'Data (td)', 'powerkit-addons-for-elementor' ),
				'th' => esc_html__( 'Header (th)', 'powerkit-addons-for-elementor' ),
			],
		] );

		$cell_repeater->add_control( 'cell_media_type', [
			'label'   => esc_html__( 'Media Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none'  => esc_html__( 'N/A', 'powerkit-addons-for-elementor' ),
				'icon'  => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
				'image' => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			],
		] );

		$cell_repeater->add_control( 'cell_icon', [
			'label'     => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => '', 'library' => '' ],
			'condition' => [ 'cell_media_type' => 'icon' ],
		] );

		$cell_repeater->add_control( 'cell_icon_position', [
			'label'     => esc_html__( 'Icon Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'before',
			'options'   => [
				'before' => esc_html__( 'Before Text', 'powerkit-addons-for-elementor' ),
				'after'  => esc_html__( 'After Text', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'cell_media_type' => 'icon' ],
		] );

		$cell_repeater->add_control( 'cell_image', [
			'label'     => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::MEDIA,
			'condition' => [ 'cell_media_type' => 'image' ],
		] );

		$cell_repeater->add_control( 'cell_image_position', [
			'label'     => esc_html__( 'Image Position', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'before',
			'options'   => [
				'before' => esc_html__( 'Before Text', 'powerkit-addons-for-elementor' ),
				'after'  => esc_html__( 'After Text', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'cell_media_type' => 'image' ],
		] );

		$cell_repeater->add_control( 'cell_link', [
			'label'       => esc_html__( 'Link', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
		] );

		$cell_repeater->add_control( 'cell_colspan', [
			'label'   => esc_html__( 'Colspan', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 1,
			'min'     => 1,
			'max'     => 20,
		] );

		$cell_repeater->add_control( 'cell_rowspan', [
			'label'   => esc_html__( 'Rowspan', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 1,
			'min'     => 1,
			'max'     => 20,
		] );

		$cell_repeater->add_control( 'cell_align', [
			'label'   => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default' => 'left',
		] );

		$cell_repeater->add_control( 'cell_bg_color', [
			'label'     => esc_html__( 'Cell Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'separator' => 'before',
		] );

		$cell_repeater->add_control( 'cell_text_color', [
			'label' => esc_html__( 'Cell Text Color', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::COLOR,
		] );

		$row_repeater->add_control( 'row_cells', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $cell_repeater->get_controls(),
			'title_field' => '{{{ cell_content }}}',
			'default'     => [
				[ 'cell_content' => esc_html__( 'Item', 'powerkit-addons-for-elementor' ) ],
				[ 'cell_content' => esc_html__( 'Category', 'powerkit-addons-for-elementor' ) ],
				[ 'cell_content' => '$10.00' ],
				[ 'cell_content' => esc_html__( 'Active', 'powerkit-addons-for-elementor' ) ],
			],
		] );

		$row_repeater->add_control( 'row_bg_color', [
			'label'     => esc_html__( 'Row Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'separator' => 'before',
		] );

		$this->add_control( 'body_rows', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $row_repeater->get_controls(),
			'title_field' => esc_html__( 'Row', 'powerkit-addons-for-elementor' ),
			'default'     => [
				[
					'row_cells' => [
						[ 'cell_content' => 'Product A' ],
						[ 'cell_content' => 'Electronics' ],
						[ 'cell_content' => '$99.00' ],
						[ 'cell_content' => 'In Stock' ],
					],
				],
				[
					'row_cells' => [
						[ 'cell_content' => 'Product B' ],
						[ 'cell_content' => 'Clothing' ],
						[ 'cell_content' => '$49.00' ],
						[ 'cell_content' => 'Out of Stock' ],
					],
				],
				[
					'row_cells' => [
						[ 'cell_content' => 'Product C' ],
						[ 'cell_content' => 'Books' ],
						[ 'cell_content' => '$19.00' ],
						[ 'cell_content' => 'In Stock' ],
					],
				],
			],
		] );

		$this->end_controls_section();

		// ── SETTINGS ──────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_settings', [
			'label' => esc_html__( 'Settings', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'sortable', [
			'label'        => esc_html__( 'Sortable Columns', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'description'  => esc_html__( 'Click column headers to sort.', 'powerkit-addons-for-elementor' ),
		] );

		$this->add_control( 'searchable', [
			'label'        => esc_html__( 'Search / Filter', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'search_placeholder', [
			'label'     => esc_html__( 'Search Placeholder', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Search...', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'searchable' => 'yes' ],
		] );

		$this->add_control( 'show_footer', [
			'label'        => esc_html__( 'Show Footer', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'striped', [
			'label'        => esc_html__( 'Striped Rows', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'hover_highlight', [
			'label'        => esc_html__( 'Hover Highlight', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'responsive', [
			'label'        => esc_html__( 'Responsive (Horizontal Scroll)', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'enable_pagination', [
			'label'        => esc_html__( 'Enable Pagination', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
			'separator'    => 'before',
		] );

		$this->add_control( 'rows_per_page', [
			'label'     => esc_html__( 'Rows Per Page', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 5,
			'min'       => 1,
			'max'       => 100,
			'condition' => [ 'enable_pagination' => 'yes' ],
		] );

		$this->add_control( 'pagination_align', [
			'label'     => esc_html__( 'Pagination Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'flex-end',
			'condition' => [ 'enable_pagination' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Box ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_bg',
			'selector' => '{{WRAPPER}} .pkae-table-wrap',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .pkae-table-wrap',
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .pkae-table-wrap',
		] );

		$this->end_controls_section();

		// ── STYLE: Header ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_header', [
			'label' => esc_html__( 'Header', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'header_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [ '{{WRAPPER}} .pkae-table thead th' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'header_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-table thead th' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'header_typo',
			'selector' => '{{WRAPPER}} .pkae-table thead th',
		] );

		$this->add_responsive_control( 'header_padding', [
			'label'      => esc_html__( 'Cell Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '14', 'right' => '16', 'bottom' => '14', 'left' => '16', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'header_sort_icon_color', [
			'label'     => esc_html__( 'Sort Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.6)',
			'selectors' => [ '{{WRAPPER}} .pkae-table thead th .pkae-sort-icon' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'header_icon_heading', [
			'label'     => esc_html__( 'Header Cell Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'header_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .pkae-table thead th .pkae-cell-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-table thead th .pkae-cell-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'header_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 8, 'max' => 60 ] ],
			'default'    => [ 'size' => 16, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-table thead th .pkae-cell-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-table thead th .pkae-cell-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'header_icon_spacing', [
			'label'     => esc_html__( 'Icon Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'default'   => [ 'size' => 6 ],
			'selectors' => [ '{{WRAPPER}} .pkae-table thead th .pkae-cell-icon' => 'margin-right: {{SIZE}}px;' ],
		] );

		$this->add_control( 'header_img_heading', [
			'label'     => esc_html__( 'Header Cell Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'header_img_width', [
			'label'      => esc_html__( 'Image Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
			'default'    => [ 'size' => 24, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table thead th .pkae-cell-img img' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'header_img_height', [
			'label'      => esc_html__( 'Image Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table thead th .pkae-cell-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;' ],
		] );

		$this->add_responsive_control( 'header_img_border_radius', [
			'label'      => esc_html__( 'Image Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table thead th .pkae-cell-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'header_img_spacing', [
			'label'     => esc_html__( 'Image Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'default'   => [ 'size' => 6 ],
			'selectors' => [ '{{WRAPPER}} .pkae-table thead th .pkae-cell-img' => 'margin-right: {{SIZE}}px;' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Body ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_body', [
			'label' => esc_html__( 'Body', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'body_bg', [
			'label'     => esc_html__( 'Row Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-table tbody tr' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'body_odd_bg', [
			'label'     => esc_html__( 'Odd Row Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f8f7ff',
			'selectors' => [ '{{WRAPPER}} .pkae-table--striped tbody tr:nth-child(odd)' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'body_hover_bg', [
			'label'     => esc_html__( 'Hover Row Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ede9ff',
			'selectors' => [ '{{WRAPPER}} .pkae-table--hover tbody tr:hover' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'body_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-table tbody td' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'body_typo',
			'selector' => '{{WRAPPER}} .pkae-table tbody td',
		] );

		$this->add_responsive_control( 'body_padding', [
			'label'      => esc_html__( 'Cell Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '12', 'right' => '16', 'bottom' => '12', 'left' => '16', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table tbody td, {{WRAPPER}} .pkae-table tbody th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'body_icon_heading', [
			'label'     => esc_html__( 'Body Cell Icon', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'body_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-table tbody .pkae-cell-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-table tbody .pkae-cell-icon svg' => 'fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'body_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 8, 'max' => 60 ] ],
			'default'    => [ 'size' => 16, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-table tbody .pkae-cell-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-table tbody .pkae-cell-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'body_icon_spacing', [
			'label'     => esc_html__( 'Icon Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'default'   => [ 'size' => 6 ],
			'selectors' => [ '{{WRAPPER}} .pkae-table tbody .pkae-cell-icon' => 'margin-right: {{SIZE}}px;' ],
		] );

		$this->add_control( 'body_img_heading', [
			'label'     => esc_html__( 'Body Cell Image', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'body_img_width', [
			'label'      => esc_html__( 'Image Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
			'default'    => [ 'size' => 32, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table tbody .pkae-cell-img img' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'body_img_height', [
			'label'      => esc_html__( 'Image Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table tbody .pkae-cell-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;' ],
		] );

		$this->add_responsive_control( 'body_img_border_radius', [
			'label'      => esc_html__( 'Image Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table tbody .pkae-cell-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'body_img_spacing', [
			'label'     => esc_html__( 'Image Spacing', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'default'   => [ 'size' => 6 ],
			'selectors' => [ '{{WRAPPER}} .pkae-table tbody .pkae-cell-img' => 'margin-right: {{SIZE}}px;' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Border ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_border', [
			'label' => esc_html__( 'Cell Border', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'cell_border_style', [
			'label'   => esc_html__( 'Border Style', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'solid',
			'options' => [ 'none' => 'None', 'solid' => 'Solid', 'dashed' => 'Dashed', 'dotted' => 'Dotted' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-table td, {{WRAPPER}} .pkae-table th' => 'border-style: {{VALUE}};',
			],
		] );

		$this->add_control( 'cell_border_width', [
			'label'     => esc_html__( 'Border Width (px)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
			'default'   => [ 'size' => 1 ],
			'selectors' => [
				'{{WRAPPER}} .pkae-table td, {{WRAPPER}} .pkae-table th' => 'border-width: {{SIZE}}px;',
			],
		] );

		$this->add_control( 'cell_border_color', [
			'label'     => esc_html__( 'Border Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e5e5e5',
			'selectors' => [
				'{{WRAPPER}} .pkae-table td, {{WRAPPER}} .pkae-table th' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		// ── STYLE: Search ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_search', [
			'label'     => esc_html__( 'Search Box', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'searchable' => 'yes' ],
		] );

		$this->add_control( 'search_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-table-search' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'search_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-table-search' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'search_border',
			'selector' => '{{WRAPPER}} .pkae-table-search',
		] );

		$this->add_responsive_control( 'search_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'search_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'search_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'bottom' => '16', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-search-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'search_width', [
			'label'      => esc_html__( 'Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 50, 'max' => 1000 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
			'default'    => [ 'size' => 100, 'unit' => '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-search' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'search_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'flex-start',
			'selectors' => [ '{{WRAPPER}} .pkae-table-search-wrap' => 'display: flex; justify-content: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Pagination ─────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_pagination', [
			'label'     => esc_html__( 'Pagination', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'enable_pagination' => 'yes' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'pagination_typo',
			'selector' => '{{WRAPPER}} .pkae-table-page-btn',
		] );

		$this->add_responsive_control( 'pagination_btn_size', [
			'label'     => esc_html__( 'Button Size', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 20, 'max' => 80 ] ],
			'default'   => [ 'size' => 34 ],
			'selectors' => [ '{{WRAPPER}} .pkae-table-page-btn' => 'min-width: {{SIZE}}px; height: {{SIZE}}px;' ],
		] );

		$this->add_responsive_control( 'pagination_gap', [
			'label'     => esc_html__( 'Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'default'   => [ 'size' => 4 ],
			'selectors' => [ '{{WRAPPER}} .pkae-table-pagination' => 'gap: {{SIZE}}px;' ],
		] );

		$this->add_responsive_control( 'pagination_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-page-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'pagination_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '12', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-table-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'pagination_tabs' );

		$this->start_controls_tab( 'pagination_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'pagination_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-table-page-btn' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'pagination_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-table-page-btn' => 'background-color: {{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'pagination_border',
			'selector' => '{{WRAPPER}} .pkae-table-page-btn',
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_active', [ 'label' => esc_html__( 'Active / Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'pagination_active_color', [
			'label'     => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .pkae-table-page-btn.pkae-active'          => 'color: {{VALUE}};',
				'{{WRAPPER}} .pkae-table-page-btn:hover:not(.pkae-disabled)' => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'pagination_active_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [
				'{{WRAPPER}} .pkae-table-page-btn.pkae-active'          => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .pkae-table-page-btn:hover:not(.pkae-disabled)' => 'background-color: {{VALUE}};',
			],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$s            = $this->get_settings_for_display();
		$header_cells = ! empty( $s['header_cells'] ) ? $s['header_cells'] : [];
		$body_rows    = ! empty( $s['body_rows'] ) ? $s['body_rows'] : [];
		$sortable     = isset( $s['sortable'] ) && 'yes' === $s['sortable'];
		$searchable   = isset( $s['searchable'] ) && 'yes' === $s['searchable'];
		$show_footer  = isset( $s['show_footer'] ) && 'yes' === $s['show_footer'];
		$striped      = isset( $s['striped'] ) && 'yes' === $s['striped'];
		$hover        = isset( $s['hover_highlight'] ) && 'yes' === $s['hover_highlight'];
		$responsive   = isset( $s['responsive'] ) && 'yes' === $s['responsive'];
		$search_ph    = ! empty( $s['search_placeholder'] ) ? $s['search_placeholder'] : 'Search...';
		$pagination   = isset( $s['enable_pagination'] ) && 'yes' === $s['enable_pagination'];
		$rows_per_page = ! empty( $s['rows_per_page'] ) ? (int) $s['rows_per_page'] : 5;
		$pag_align    = ! empty( $s['pagination_align'] ) ? $s['pagination_align'] : 'flex-end';
		$widget_id    = 'pkae-table-' . $this->get_id();
		Icons_Manager::enqueue_shim();

		$table_class = 'pkae-table';
		if ( $striped ) $table_class .= ' pkae-table--striped';
		if ( $hover )   $table_class .= ' pkae-table--hover';
		if ( $sortable ) $table_class .= ' pkae-table--sortable';
		?>
		<div class="pkae-table-wrap" id="<?php echo esc_attr( $widget_id ); ?>"
			data-pagination="<?php echo $pagination ? 'yes' : 'no'; ?>"
			data-rows-per-page="<?php echo esc_attr( $rows_per_page ); ?>">

			<?php if ( $searchable ) : ?>
				<div class="pkae-table-search-wrap">
					<input type="text" class="pkae-table-search" placeholder="<?php echo esc_attr( $search_ph ); ?>" aria-label="<?php esc_attr_e( 'Search table', 'powerkit-addons-for-elementor' ); ?>">
				</div>
			<?php endif; ?>

			<div class="pkae-table-scroll<?php echo $responsive ? ' pkae-table-responsive' : ''; ?>">
				<table class="<?php echo esc_attr( $table_class ); ?>">

					<?php // THEAD ?>
					<?php if ( ! empty( $header_cells ) ) : ?>
						<thead>
							<tr>
								<?php foreach ( $header_cells as $cell ) :
									$text       = ! empty( $cell['cell_text'] ) ? $cell['cell_text'] : '';
									$colspan    = ! empty( $cell['cell_colspan'] ) && (int) $cell['cell_colspan'] > 1 ? ' colspan="' . (int) $cell['cell_colspan'] . '"' : '';
									$align      = ! empty( $cell['cell_align'] ) ? $cell['cell_align'] : 'left';
									$media_type = ! empty( $cell['cell_media_type'] ) ? $cell['cell_media_type'] : 'none';
									$has_icon   = 'icon' === $media_type && ! empty( $cell['cell_icon']['value'] );
									$has_img    = 'image' === $media_type && ! empty( $cell['cell_image']['url'] );
									$icon_pos   = ! empty( $cell['cell_icon_position'] ) ? $cell['cell_icon_position'] : 'before';
									$img_pos    = ! empty( $cell['cell_image_position'] ) ? $cell['cell_image_position'] : 'before';
									?>
									<th<?php echo $colspan; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> style="text-align:<?php echo esc_attr( $align ); ?>;" data-sort="<?php echo esc_attr( sanitize_title( $text ) ); ?>">
										<?php
										$has_img  = ! empty( $cell['cell_image']['url'] );
										$img_pos  = ! empty( $cell['cell_image_position'] ) ? $cell['cell_image_position'] : 'before';
										?>
										<?php if ( $has_icon && 'before' === $icon_pos ) : ?>
											<span class="pkae-cell-icon"><?php Icons_Manager::render_icon( $cell['cell_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
										<?php elseif ( $has_img && 'before' === $img_pos ) : ?>
											<span class="pkae-cell-img"><img src="<?php echo esc_url( $cell['cell_image']['url'] ); ?>" alt="<?php echo esc_attr( $text ); ?>" loading="lazy"></span>
										<?php endif; ?>
										<?php echo esc_html( $text ); ?>
										<?php if ( $has_icon && 'after' === $icon_pos ) : ?>
											<span class="pkae-cell-icon"><?php Icons_Manager::render_icon( $cell['cell_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
										<?php elseif ( $has_img && 'after' === $img_pos ) : ?>
											<span class="pkae-cell-img"><img src="<?php echo esc_url( $cell['cell_image']['url'] ); ?>" alt="<?php echo esc_attr( $text ); ?>" loading="lazy"></span>
										<?php endif; ?>
										<?php if ( $sortable ) : ?>
											<span class="pkae-sort-icon" aria-hidden="true">⇅</span>
										<?php endif; ?>
									</th>
								<?php endforeach; ?>
							</tr>
						</thead>
					<?php endif; ?>

					<?php // TBODY ?>
					<tbody>
						<?php foreach ( $body_rows as $row ) :
							$row_cells = ! empty( $row['row_cells'] ) ? $row['row_cells'] : [];
							$row_bg    = ! empty( $row['row_bg_color'] ) ? 'background-color:' . esc_attr( $row['row_bg_color'] ) . ';' : '';
							?>
							<tr<?php echo $row_bg ? ' style="' . esc_attr( $row_bg ) . '"' : ''; ?>>
								<?php foreach ( $row_cells as $cell ) :
									$content    = ! empty( $cell['cell_content'] ) ? $cell['cell_content'] : '';
									$type       = ! empty( $cell['cell_type'] ) ? $cell['cell_type'] : 'td';
									$colspan    = ! empty( $cell['cell_colspan'] ) && (int) $cell['cell_colspan'] > 1 ? ' colspan="' . (int) $cell['cell_colspan'] . '"' : '';
									$rowspan    = ! empty( $cell['cell_rowspan'] ) && (int) $cell['cell_rowspan'] > 1 ? ' rowspan="' . (int) $cell['cell_rowspan'] . '"' : '';
									$align      = ! empty( $cell['cell_align'] ) ? $cell['cell_align'] : 'left';
									$link_url   = ! empty( $cell['cell_link']['url'] ) ? $cell['cell_link']['url'] : '';
									$link_ext   = ! empty( $cell['cell_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
									$media_type = ! empty( $cell['cell_media_type'] ) ? $cell['cell_media_type'] : 'none';
									$has_icon   = 'icon' === $media_type && ! empty( $cell['cell_icon']['value'] );
									$has_cell_img = 'image' === $media_type && ! empty( $cell['cell_image']['url'] );
									$cell_icon_pos = ! empty( $cell['cell_icon_position'] ) ? $cell['cell_icon_position'] : 'before';
									$cell_img_pos  = ! empty( $cell['cell_image_position'] ) ? $cell['cell_image_position'] : 'before';
									$cell_bg    = ! empty( $cell['cell_bg_color'] ) ? 'background-color:' . esc_attr( $cell['cell_bg_color'] ) . ';' : '';
									$cell_clr   = ! empty( $cell['cell_text_color'] ) ? 'color:' . esc_attr( $cell['cell_text_color'] ) . ';' : '';
									$cell_style = $cell_bg . $cell_clr . 'text-align:' . esc_attr( $align ) . ';';
									$allowed_types = [ 'td', 'th' ];
									$type = in_array( $type, $allowed_types, true ) ? $type : 'td';
									?>
									<<?php echo esc_attr( $type ); ?><?php echo $colspan . $rowspan; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> style="<?php echo esc_attr( $cell_style ); ?>">
										<?php
										$has_cell_img = ! empty( $cell['cell_image']['url'] );
										$cell_img_pos = ! empty( $cell['cell_image_position'] ) ? $cell['cell_image_position'] : 'before';
										?>
										<?php if ( $has_icon && 'before' === ( $cell['cell_icon_position'] ?? 'before' ) ) : ?>
											<span class="pkae-cell-icon"><?php Icons_Manager::render_icon( $cell['cell_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
										<?php elseif ( $has_cell_img && 'before' === $cell_img_pos ) : ?>
											<span class="pkae-cell-img"><img src="<?php echo esc_url( $cell['cell_image']['url'] ); ?>" alt="<?php echo esc_attr( $content ); ?>" loading="lazy"></span>
										<?php endif; ?>
										<?php if ( $link_url ) : ?>
											<a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_ext; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $content ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $content ); ?>
										<?php endif; ?>
										<?php if ( $has_icon && 'after' === ( $cell['cell_icon_position'] ?? 'before' ) ) : ?>
											<span class="pkae-cell-icon"><?php Icons_Manager::render_icon( $cell['cell_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
										<?php elseif ( $has_cell_img && 'after' === $cell_img_pos ) : ?>
											<span class="pkae-cell-img"><img src="<?php echo esc_url( $cell['cell_image']['url'] ); ?>" alt="<?php echo esc_attr( $content ); ?>" loading="lazy"></span>
										<?php endif; ?>
									</<?php echo esc_attr( $type ); ?>>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>

					<?php // TFOOT ?>
					<?php if ( $show_footer && ! empty( $header_cells ) ) : ?>
						<tfoot>
							<tr>
								<?php foreach ( $header_cells as $cell ) :
									$text    = ! empty( $cell['cell_text'] ) ? $cell['cell_text'] : '';
									$colspan = ! empty( $cell['cell_colspan'] ) && (int) $cell['cell_colspan'] > 1 ? ' colspan="' . (int) $cell['cell_colspan'] . '"' : '';
									$align   = ! empty( $cell['cell_align'] ) ? $cell['cell_align'] : 'left';
									?>
									<th<?php echo $colspan; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> style="text-align:<?php echo esc_attr( $align ); ?>;"><?php echo esc_html( $text ); ?></th>
								<?php endforeach; ?>
							</tr>
						</tfoot>
					<?php endif; ?>

				</table>
			</div>

		<?php if ( $pagination ) : ?>
			<div class="pkae-table-pagination" style="justify-content:<?php echo esc_attr( $pag_align ); ?>;"></div>
		<?php endif; ?>

		</div>
		<?php
	}
}
