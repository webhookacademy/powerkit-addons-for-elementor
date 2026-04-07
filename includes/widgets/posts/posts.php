<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) exit;

class Posts extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'pkae-posts', plugins_url( 'assets/css/pkae-posts.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_script( 'pkae-posts', plugins_url( 'assets/js/pkae-posts.js', __FILE__ ), [ 'jquery' ], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION, true );
	}

	public function get_name()          { return 'pkae-posts'; }
	public function get_title()         { return esc_html__( 'Posts', 'powerkit-addons-for-elementor' ); }
	public function get_icon()          { return 'eicon-post-list'; }
	public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
	public function get_style_depends() { return [ 'pkae-posts' ]; }
	public function get_script_depends(){ return [ 'pkae-posts' ]; }
	public function get_keywords()      { return [ 'posts', 'blog', 'grid', 'masonry', 'carousel', 'query', 'powerkit' ]; }

	protected function register_controls() {

		// ── LAYOUT ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'skin', [
			'label'   => esc_html__( 'Skin', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'classic',
			'options' => [
				'classic' => esc_html__( 'Classic', 'powerkit-addons-for-elementor' ),
				'card'    => esc_html__( 'Card', 'powerkit-addons-for-elementor' ),
				'overlay' => esc_html__( 'Overlay', 'powerkit-addons-for-elementor' ),
				'list'    => esc_html__( 'List', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'grid',
			'options' => [
				'grid'    => esc_html__( 'Grid', 'powerkit-addons-for-elementor' ),
				'masonry' => esc_html__( 'Masonry', 'powerkit-addons-for-elementor' ),
			],
			'condition' => [ 'skin!' => 'list' ],
		] );

		$this->add_responsive_control( 'columns', [
			'label'          => esc_html__( 'Columns', 'powerkit-addons-for-elementor' ),
			'type'           => Controls_Manager::SELECT,
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'options'        => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ],
			'condition'      => [ 'skin!' => 'list' ],
		] );

		$this->add_responsive_control( 'column_gap', [
			'label'     => esc_html__( 'Column Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 20 ],
		] );

		$this->add_responsive_control( 'row_gap', [
			'label'     => esc_html__( 'Row Gap', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'   => [ 'size' => 20 ],
		] );

		$this->end_controls_section();

		// ── QUERY ─────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_query', [
			'label' => esc_html__( 'Query', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'post_type', [
			'label'   => esc_html__( 'Post Type', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'post',
			'options' => $this->get_post_types(),
		] );

		$this->add_control( 'posts_per_page', [
			'label'   => esc_html__( 'Posts Per Page', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 6,
			'min'     => 1,
			'max'     => 100,
		] );

		$this->add_control( 'offset', [
			'label'   => esc_html__( 'Offset', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 0,
			'min'     => 0,
		] );

		$this->add_control( 'order_by', [
			'label'   => esc_html__( 'Order By', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'date',
			'options' => [
				'date'          => esc_html__( 'Date', 'powerkit-addons-for-elementor' ),
				'title'         => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
				'modified'      => esc_html__( 'Last Modified', 'powerkit-addons-for-elementor' ),
				'comment_count' => esc_html__( 'Comment Count', 'powerkit-addons-for-elementor' ),
				'rand'          => esc_html__( 'Random', 'powerkit-addons-for-elementor' ),
				'menu_order'    => esc_html__( 'Menu Order', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'order', [
			'label'   => esc_html__( 'Order', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'DESC',
			'options' => [
				'DESC' => esc_html__( 'Descending', 'powerkit-addons-for-elementor' ),
				'ASC'  => esc_html__( 'Ascending', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'categories', [
			'label'       => esc_html__( 'Categories', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'label_block' => true,
			'options'     => $this->get_categories_list(),
			'condition'   => [ 'post_type' => 'post' ],
		] );

		$this->add_control( 'tags', [
			'label'       => esc_html__( 'Tags', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'label_block' => true,
			'options'     => $this->get_tags_list(),
			'condition'   => [ 'post_type' => 'post' ],
		] );

		$this->add_control( 'authors', [
			'label'       => esc_html__( 'Authors', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'label_block' => true,
			'options'     => $this->get_authors_list(),
		] );

		$this->add_control( 'exclude_current', [
			'label'        => esc_html__( 'Exclude Current Post', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'exclude_ids', [
			'label'       => esc_html__( 'Exclude Posts (IDs)', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => '1, 2, 3',
			'label_block' => true,
			'description' => esc_html__( 'Comma separated post IDs.', 'powerkit-addons-for-elementor' ),
		] );

		$this->end_controls_section();

		// ── POST ELEMENTS ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_elements', [
			'label' => esc_html__( 'Post Elements', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_image', [
			'label'        => esc_html__( 'Featured Image', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'medium_large',
			'condition' => [ 'show_image' => 'yes' ],
		] );

		$this->add_responsive_control( 'image_height', [
			'label'      => esc_html__( 'Image Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [ 'px' => [ 'min' => 50, 'max' => 800 ] ],
			'default'    => [ 'size' => 220, 'unit' => 'px' ],
			'condition'  => [ 'show_image' => 'yes' ],
		] );

		$this->add_control( 'show_category', [
			'label'        => esc_html__( 'Category', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );

		$this->add_control( 'show_title', [
			'label'        => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'title_tag', [
			'label'     => esc_html__( 'Title Tag', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'h3',
			'options'   => [ 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6' ],
			'condition' => [ 'show_title' => 'yes' ],
		] );

		$this->add_control( 'show_meta', [
			'label'        => esc_html__( 'Meta', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );

		$this->add_control( 'meta_items', [
			'label'       => esc_html__( 'Meta Items', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'default'     => [ 'date', 'author' ],
			'options'     => [
				'date'     => esc_html__( 'Date', 'powerkit-addons-for-elementor' ),
				'author'   => esc_html__( 'Author', 'powerkit-addons-for-elementor' ),
				'comments' => esc_html__( 'Comments', 'powerkit-addons-for-elementor' ),
				'reading_time' => esc_html__( 'Reading Time', 'powerkit-addons-for-elementor' ),
			],
			'condition'   => [ 'show_meta' => 'yes' ],
		] );

		$this->add_control( 'show_excerpt', [
			'label'        => esc_html__( 'Excerpt', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );

		$this->add_control( 'excerpt_length', [
			'label'     => esc_html__( 'Excerpt Length (words)', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 20,
			'min'       => 5,
			'condition' => [ 'show_excerpt' => 'yes' ],
		] );

		$this->add_control( 'show_cta', [
			'label'        => esc_html__( 'Read More Button', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );

		$this->add_control( 'cta_text', [
			'label'     => esc_html__( 'Button Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Read More', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_cta' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── FILTER ────────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_filter', [
			'label'     => esc_html__( 'Filter', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'post_type' => 'post' ],
		] );

		$this->add_control( 'show_filters', [
			'label'        => esc_html__( 'Show Filters', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'no',
		] );

		$this->add_control( 'filter_all_label', [
			'label'     => esc_html__( '"All" Label', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'All', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'show_filters' => 'yes' ],
		] );

		$this->add_control( 'filter_categories', [
			'label'       => esc_html__( 'Filter Categories', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'label_block' => true,
			'options'     => $this->get_categories_list(),
			'description' => esc_html__( 'Select categories to show in filter. Leave empty to show all categories from displayed posts.', 'powerkit-addons-for-elementor' ),
			'condition'   => [ 'show_filters' => 'yes' ],
		] );

		$this->add_responsive_control( 'filter_align', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'condition' => [ 'show_filters' => 'yes' ],
		] );

		$this->end_controls_section();

		// ── PAGINATION ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_pagination', [
			'label' => esc_html__( 'Pagination', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'pagination', [
			'label'   => esc_html__( 'Pagination', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none'     => esc_html__( 'None', 'powerkit-addons-for-elementor' ),
				'numbers'  => esc_html__( 'Numbers', 'powerkit-addons-for-elementor' ),
				'load_more'=> esc_html__( 'Load More', 'powerkit-addons-for-elementor' ),
				'infinite' => esc_html__( 'Infinite Scroll', 'powerkit-addons-for-elementor' ),
			],
		] );

		$this->add_control( 'load_more_text', [
			'label'     => esc_html__( 'Load More Text', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Load More', 'powerkit-addons-for-elementor' ),
			'condition' => [ 'pagination' => 'load_more' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Box ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_box', [
			'label' => esc_html__( 'Box', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'post_bg',
			'selector' => '{{WRAPPER}} .pkae-posts__item',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'post_border',
			'selector' => '{{WRAPPER}} .pkae-posts__item',
		] );

		$this->add_responsive_control( 'post_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'post_shadow',
			'selector' => '{{WRAPPER}} .pkae-posts__item',
		] );

		$this->add_responsive_control( 'content_padding', [
			'label'      => esc_html__( 'Content Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Image ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_image', [
			'label'     => esc_html__( 'Image', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_image' => 'yes' ],
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'img_filter',
			'selector' => '{{WRAPPER}} .pkae-posts__img img',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'img_filter_hover',
			'label'    => esc_html__( 'CSS Filters (Hover)', 'powerkit-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .pkae-posts__item:hover .pkae-posts__img img',
		] );

		$this->add_responsive_control( 'img_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Category ───────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_cat', [
			'label'     => esc_html__( 'Category', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_category' => 'yes' ],
		] );

		$this->add_control( 'cat_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__cat a' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'cat_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__cat a' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'cat_typo',
			'selector' => '{{WRAPPER}} .pkae-posts__cat a',
		] );

		$this->add_responsive_control( 'cat_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__cat a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'cat_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__cat a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Title ──────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_title', [
			'label'     => esc_html__( 'Title', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_title' => 'yes' ],
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__title a' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'title_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__title a:hover' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-posts__title',
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Meta ───────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_meta', [
			'label'     => esc_html__( 'Meta', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_meta' => 'yes' ],
		] );

		$this->add_control( 'meta_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__meta, {{WRAPPER}} .pkae-posts__meta a' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'meta_typo',
			'selector' => '{{WRAPPER}} .pkae-posts__meta',
		] );

		$this->add_responsive_control( 'meta_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: Excerpt ────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_excerpt', [
			'label'     => esc_html__( 'Excerpt', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_excerpt' => 'yes' ],
		] );

		$this->add_control( 'excerpt_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__excerpt' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'excerpt_typo',
			'selector' => '{{WRAPPER}} .pkae-posts__excerpt',
		] );

		$this->add_responsive_control( 'excerpt_margin', [
			'label'      => esc_html__( 'Margin', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── STYLE: CTA ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_cta', [
			'label'     => esc_html__( 'Read More Button', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_cta' => 'yes' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'cta_typo',
			'selector' => '{{WRAPPER}} .pkae-posts__cta',
		] );

		$this->add_responsive_control( 'cta_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'cta_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'cta_tabs' );
		$this->start_controls_tab( 'cta_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'cta_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__cta' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'cta_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__cta' => 'background-color: {{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'cta_border',
			'selector' => '{{WRAPPER}} .pkae-posts__cta',
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'cta_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'cta_color_hover', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__cta:hover' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'cta_bg_hover', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__cta:hover' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── STYLE: Filter ─────────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_filter', [
			'label'     => esc_html__( 'Filter Buttons', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_filters' => 'yes' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'filter_typo',
			'selector' => '{{WRAPPER}} .pkae-posts__filter-btn',
		] );

		$this->add_responsive_control( 'filter_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '6', 'right' => '18', 'bottom' => '6', 'left' => '18', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'filter_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'filter_tabs' );
		$this->start_controls_tab( 'filter_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'filter_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__filter-btn' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'filter_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__filter-btn' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'filter_active', [ 'label' => esc_html__( 'Active', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'filter_active_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .pkae-posts__filter-btn.pkae-active' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'filter_active_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6040e0',
			'selectors' => [ '{{WRAPPER}} .pkae-posts__filter-btn.pkae-active' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// ── STYLE: Pagination ─────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_pagination', [
			'label'     => esc_html__( 'Pagination', 'powerkit-addons-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'pagination!' => 'none' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'pagination_typo',
			'selector' => '{{WRAPPER}} .pkae-posts__pagination a, {{WRAPPER}} .pkae-posts__load-more',
		] );

		$this->add_responsive_control( 'pagination_padding', [
			'label'      => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-posts__pagination a, {{WRAPPER}} .pkae-posts__load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'pagination_tabs' );
		$this->start_controls_tab( 'pagination_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'pagination_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__pagination a, {{WRAPPER}} .pkae-posts__load-more' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'pagination_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__pagination a, {{WRAPPER}} .pkae-posts__load-more' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_active', [ 'label' => esc_html__( 'Active / Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'pagination_active_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__pagination a.current, {{WRAPPER}} .pkae-posts__pagination a:hover, {{WRAPPER}} .pkae-posts__load-more:hover' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'pagination_active_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-posts__pagination a.current, {{WRAPPER}} .pkae-posts__pagination a:hover, {{WRAPPER}} .pkae-posts__load-more:hover' => 'background-color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	// ── Helpers ───────────────────────────────────────────────────────────────
	protected function get_post_types() {
		$types = [ 'post' => esc_html__( 'Post', 'powerkit-addons-for-elementor' ), 'page' => esc_html__( 'Page', 'powerkit-addons-for-elementor' ) ];
		$custom = get_post_types( [ 'public' => true, '_builtin' => false ], 'objects' );
		foreach ( $custom as $type ) {
			$types[ $type->name ] = $type->label;
		}
		return $types;
	}

	protected function get_categories_list() {
		$cats = [];
		foreach ( get_categories( [ 'hide_empty' => false ] ) as $cat ) {
			$cats[ $cat->term_id ] = $cat->name;
		}
		return $cats;
	}

	protected function get_tags_list() {
		$tags = [];
		foreach ( get_tags( [ 'hide_empty' => false ] ) as $tag ) {
			$tags[ $tag->term_id ] = $tag->name;
		}
		return $tags;
	}

	protected function get_authors_list() {
		$authors = [];
		foreach ( get_users( [ 'who' => 'authors' ] ) as $user ) {
			$authors[ $user->ID ] = $user->display_name;
		}
		return $authors;
	}

	protected function build_query( $s, $paged = 1 ) {
		$args = [
			'post_type'      => ! empty( $s['post_type'] ) ? $s['post_type'] : 'post',
			'posts_per_page' => ! empty( $s['posts_per_page'] ) ? (int) $s['posts_per_page'] : 6,
			'offset'         => ! empty( $s['offset'] ) ? (int) $s['offset'] : 0,
			'orderby'        => ! empty( $s['order_by'] ) ? $s['order_by'] : 'date',
			'order'          => ! empty( $s['order'] ) ? $s['order'] : 'DESC',
			'paged'          => $paged,
			'post_status'    => 'publish',
		];

		if ( ! empty( $s['categories'] ) ) {
			$args['category__in'] = array_map( 'intval', $s['categories'] );
		}
		if ( ! empty( $s['tags'] ) ) {
			$args['tag__in'] = array_map( 'intval', $s['tags'] );
		}
		if ( ! empty( $s['authors'] ) ) {
			$args['author__in'] = array_map( 'intval', $s['authors'] );
		}
		if ( isset( $s['exclude_current'] ) && 'yes' === $s['exclude_current'] && is_singular() ) {
			$args['post__not_in'] = [ get_the_ID() ];
		}
		if ( ! empty( $s['exclude_ids'] ) ) {
			$exclude = array_map( 'intval', explode( ',', $s['exclude_ids'] ) );
			$args['post__not_in'] = array_merge( $args['post__not_in'] ?? [], $exclude );
		}

		return new \WP_Query( $args );
	}

	protected function render() {
		$s           = $this->get_settings_for_display();
		$skin        = ! empty( $s['skin'] ) ? $s['skin'] : 'classic';
		$layout      = ! empty( $s['layout'] ) ? $s['layout'] : 'grid';
		$columns     = ! empty( $s['columns'] ) ? (int) $s['columns'] : 3;
		$col_t       = ! empty( $s['columns_tablet'] ) ? (int) $s['columns_tablet'] : 2;
		$col_m       = ! empty( $s['columns_mobile'] ) ? (int) $s['columns_mobile'] : 1;
		$col_gap     = isset( $s['column_gap']['size'] ) ? (int) $s['column_gap']['size'] : 20;
		$row_gap     = isset( $s['row_gap']['size'] ) ? (int) $s['row_gap']['size'] : 20;
		$pagination  = ! empty( $s['pagination'] ) ? $s['pagination'] : 'none';
		$show_filter = isset( $s['show_filters'] ) && 'yes' === $s['show_filters'];
		$filter_all  = ! empty( $s['filter_all_label'] ) ? $s['filter_all_label'] : 'All';
		$filter_align= ! empty( $s['filter_align'] ) ? $s['filter_align'] : 'center';
		$img_height  = isset( $s['image_height']['size'] ) ? $s['image_height']['size'] : 220;
		$img_height_u= isset( $s['image_height']['unit'] ) ? $s['image_height']['unit'] : 'px';
		$img_size    = $this->get_settings( 'thumbnail_size' ) ?: 'medium_large';
		$excerpt_len = ! empty( $s['excerpt_length'] ) ? (int) $s['excerpt_length'] : 20;
		$title_tag   = ! empty( $s['title_tag'] ) ? $s['title_tag'] : 'h3';
		$cta_text    = ! empty( $s['cta_text'] ) ? $s['cta_text'] : 'Read More';
		$widget_id   = 'pkae-posts-' . $this->get_id();

		$query = $this->build_query( $s );

		// Collect categories for filter
		$filter_cats = [];
		if ( $show_filter ) {
			$selected_filter_cats = ! empty( $s['filter_categories'] ) ? array_map( 'intval', $s['filter_categories'] ) : [];

			if ( ! empty( $selected_filter_cats ) ) {
				// Use manually selected categories
				foreach ( $selected_filter_cats as $tid ) {
					$term = get_term( $tid, 'category' );
					if ( $term && ! is_wp_error( $term ) ) {
						$filter_cats[ $tid ] = $term->name;
					}
				}
			} elseif ( $query->have_posts() ) {
				// Auto: collect from displayed posts
				foreach ( $query->posts as $post ) {
					$cats = get_the_category( $post->ID );
					foreach ( $cats as $cat ) {
						$filter_cats[ $cat->term_id ] = $cat->name;
					}
				}
			}
		}

		$grid_style = '--pkae-posts-cols:' . $columns . ';--pkae-posts-cols-t:' . $col_t . ';--pkae-posts-cols-m:' . $col_m . ';--pkae-posts-col-gap:' . $col_gap . 'px;--pkae-posts-row-gap:' . $row_gap . 'px;';
		?>
		<div class="pkae-posts pkae-posts--<?php echo esc_attr( $skin ); ?> pkae-posts--<?php echo esc_attr( $layout ); ?>"
			id="<?php echo esc_attr( $widget_id ); ?>"
			data-skin="<?php echo esc_attr( $skin ); ?>"
			data-layout="<?php echo esc_attr( $layout ); ?>"
			data-pagination="<?php echo esc_attr( $pagination ); ?>"
			data-page="1"
			data-max-pages="<?php echo esc_attr( $query->max_num_pages ); ?>"
			data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">

			<?php if ( $show_filter && ! empty( $filter_cats ) ) : ?>
				<div class="pkae-posts__filters" style="justify-content:<?php echo esc_attr( $filter_align ); ?>;">
					<button class="pkae-posts__filter-btn pkae-active" data-filter="*"><?php echo esc_html( $filter_all ); ?></button>
					<?php foreach ( $filter_cats as $tid => $tname ) : ?>
						<button class="pkae-posts__filter-btn" data-filter="cat-<?php echo esc_attr( $tid ); ?>"><?php echo esc_html( $tname ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="pkae-posts__grid" style="<?php echo esc_attr( $grid_style ); ?>">
				<?php
				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) :
						$query->the_post();
						$post_id   = get_the_ID();
						$post_cats = get_the_category( $post_id );
						$cat_classes = '';
						foreach ( $post_cats as $cat ) {
							$cat_classes .= ' cat-' . $cat->term_id;
						}
						?>
						<article class="pkae-posts__item<?php echo esc_attr( $cat_classes ); ?>">

							<?php if ( isset( $s['show_image'] ) && 'yes' === $s['show_image'] && has_post_thumbnail() ) : ?>
								<div class="pkae-posts__img" style="height:<?php echo esc_attr( $img_height . $img_height_u ); ?>;">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( $img_size, [ 'loading' => 'lazy' ] ); ?>
									</a>
								</div>
							<?php endif; ?>

							<div class="pkae-posts__content">

								<?php if ( isset( $s['show_category'] ) && 'yes' === $s['show_category'] && ! empty( $post_cats ) ) : ?>
									<div class="pkae-posts__cat">
										<?php foreach ( array_slice( $post_cats, 0, 2 ) as $cat ) : ?>
											<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

								<?php if ( isset( $s['show_title'] ) && 'yes' === $s['show_title'] ) : ?>
									<<?php echo esc_attr( $title_tag ); ?> class="pkae-posts__title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</<?php echo esc_attr( $title_tag ); ?>>
								<?php endif; ?>

								<?php if ( isset( $s['show_meta'] ) && 'yes' === $s['show_meta'] ) : ?>
									<div class="pkae-posts__meta">
										<?php
										$meta_items = ! empty( $s['meta_items'] ) ? $s['meta_items'] : [ 'date', 'author' ];
										foreach ( $meta_items as $meta ) :
											if ( 'date' === $meta ) :
												echo '<span class="pkae-posts__meta-item pkae-posts__date"><time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time></span>';
											elseif ( 'author' === $meta ) :
												echo '<span class="pkae-posts__meta-item pkae-posts__author"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
											elseif ( 'comments' === $meta ) :
												echo '<span class="pkae-posts__meta-item pkae-posts__comments">' . esc_html( get_comments_number() ) . ' ' . esc_html__( 'Comments', 'powerkit-addons-for-elementor' ) . '</span>';
											elseif ( 'reading_time' === $meta ) :
												$words = str_word_count( wp_strip_all_tags( get_the_content() ) );
												$mins  = max( 1, (int) ceil( $words / 200 ) );
												echo '<span class="pkae-posts__meta-item pkae-posts__reading-time">' . esc_html( $mins ) . ' ' . esc_html__( 'min read', 'powerkit-addons-for-elementor' ) . '</span>';
											endif;
										endforeach;
										?>
									</div>
								<?php endif; ?>

								<?php if ( isset( $s['show_excerpt'] ) && 'yes' === $s['show_excerpt'] ) : ?>
									<p class="pkae-posts__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_len ) ); ?></p>
								<?php endif; ?>

								<?php if ( isset( $s['show_cta'] ) && 'yes' === $s['show_cta'] ) : ?>
									<a class="pkae-posts__cta" href="<?php the_permalink(); ?>"><?php echo esc_html( $cta_text ); ?></a>
								<?php endif; ?>

							</div>
						</article>
					<?php
					endwhile;
					wp_reset_postdata();
				else :
					echo '<p class="pkae-posts__no-posts">' . esc_html__( 'No posts found.', 'powerkit-addons-for-elementor' ) . '</p>';
				endif;
				?>
			</div>

			<?php // Pagination ?>
			<?php if ( 'numbers' === $pagination && $query->max_num_pages > 1 ) : ?>
				<div class="pkae-posts__pagination">
					<?php
					$big = 999999999;
					echo wp_kses_post( str_replace( $big, '%#%', paginate_links( [
						'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'  => '?paged=%#%',
						'current' => max( 1, get_query_var( 'paged' ) ),
						'total'   => $query->max_num_pages,
						'type'    => 'list',
					] ) ) );
					?>
				</div>
			<?php elseif ( 'load_more' === $pagination && $query->max_num_pages > 1 ) : ?>
				<div class="pkae-posts__load-more-wrap">
					<button class="pkae-posts__load-more" data-widget="<?php echo esc_attr( $widget_id ); ?>">
						<?php echo esc_html( ! empty( $s['load_more_text'] ) ? $s['load_more_text'] : 'Load More' ); ?>
					</button>
				</div>
			<?php endif; ?>

		</div>
		<?php
	}
}
