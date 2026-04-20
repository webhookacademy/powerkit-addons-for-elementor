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

class Mega_Menu extends Widget_Base {

public function __construct( $data = [], $args = null ) {
    parent::__construct( $data, $args );
    $css_ver = PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION . '.' . filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/pkae-mega-menu.css' );
    $js_ver  = PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION . '.' . filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/pkae-mega-menu.js' );
    wp_register_style( 'pkae-mega-menu', plugins_url( 'assets/css/pkae-mega-menu.css', __FILE__ ), [], $css_ver );
    wp_register_script( 'pkae-mega-menu', plugins_url( 'assets/js/pkae-mega-menu.js', __FILE__ ), [], $js_ver, true );
}

public function get_name()          { return 'pkae-mega-menu'; }
public function get_title()         { return esc_html__( 'Mega Menu', 'powerkit-addons-for-elementor' ); }
public function get_icon()          { return 'eicon-nav-menu'; }
public function get_categories()    { return [ 'powerkit-addons-for-elementor' ]; }
public function get_style_depends() { return [ 'pkae-mega-menu' ]; }
public function get_script_depends(){ return [ 'pkae-mega-menu' ]; }
public function get_keywords()      { return [ 'menu', 'mega', 'nav', 'navigation', 'dropdown', 'woocommerce', 'posts', 'powerkit' ]; }

private function get_wp_menus() {
    $menus  = wp_get_nav_menus();
    $result = [ '' => esc_html__( '— Select Menu —', 'powerkit-addons-for-elementor' ) ];
    foreach ( $menus as $menu ) { $result[ $menu->term_id ] = $menu->name; }
    return $result;
}

private function get_post_types() {
    $types  = get_post_types( [ 'public' => true ], 'objects' );
    $result = [];
    foreach ( $types as $type ) { $result[ $type->name ] = $type->label; }
    return $result;
}

	protected function register_controls() {
		$this->start_controls_section( 'section_layout', [ 'label' => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
		$this->add_control( 'menu_layout', [ 'label' => esc_html__( 'Orientation', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::CHOOSE, 'options' => [ 'horizontal' => [ 'title' => esc_html__( 'Horizontal', 'powerkit-addons-for-elementor' ), 'icon' => 'eicon-navigation-horizontal' ], 'vertical' => [ 'title' => esc_html__( 'Vertical', 'powerkit-addons-for-elementor' ), 'icon' => 'eicon-navigation-vertical' ] ], 'default' => 'horizontal', 'toggle' => false ] );
		$this->add_responsive_control( 'nav_alignment', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => esc_html__( 'Left', 'powerkit-addons-for-elementor' ),   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => esc_html__( 'Center', 'powerkit-addons-for-elementor' ), 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => esc_html__( 'Right', 'powerkit-addons-for-elementor' ),  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'flex-start',
			'toggle'    => false,
			'condition' => [ 'menu_layout' => 'horizontal' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-mm-horizontal .pkae-mm-nav' => 'justify-content:{{VALUE}};',
			],
		] );
		$this->add_responsive_control( 'dropdown_alignment', [
			'label'     => esc_html__( 'Dropdown Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => esc_html__( 'Left', 'powerkit-addons-for-elementor' ),   'icon' => 'eicon-h-align-right' ],
				'center' => [ 'title' => esc_html__( 'Center', 'powerkit-addons-for-elementor' ), 'icon' => 'eicon-h-align-center' ],
				'right'  => [ 'title' => esc_html__( 'Right', 'powerkit-addons-for-elementor' ),  'icon' => 'eicon-h-align-left' ],
			],
			'default'   => 'left',
			'toggle'    => false,
			'condition' => [ 'menu_layout' => 'horizontal' ],
			'selectors_dictionary' => [
				'left'   => 'left:0; right:auto;',
				'center' => 'left:50%; right:auto; transform:translateX(-50%) translateY(8px);',
				'right'  => 'left:auto; right:0;',
			],
			'selectors' => [
				'(desktop){{WRAPPER}} .pkae-mm-horizontal .pkae-mm-item:not(.pkae-mm-mega-item) > .pkae-mm-dropdown' => '{{VALUE}}',
			],
		] );
		$this->add_control( 'dropdown_trigger', [ 'label' => esc_html__( 'Dropdown Trigger', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => [ 'hover' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ), 'click' => esc_html__( 'Click', 'powerkit-addons-for-elementor' ) ], 'default' => 'hover' ] );
		$this->add_control( 'dropdown_animation', [ 'label' => esc_html__( 'Dropdown Animation', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => [ 'slide' => esc_html__( 'Slide', 'powerkit-addons-for-elementor' ), 'fade' => esc_html__( 'Fade', 'powerkit-addons-for-elementor' ), 'zoom' => esc_html__( 'Zoom', 'powerkit-addons-for-elementor' ) ], 'default' => 'slide' ] );
		$this->add_control( 'dropdown_indicator_icon', [
			'label'       => esc_html__( 'Dropdown Indicator Icon', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::ICONS,
			'default'     => [
				'value'   => 'fas fa-chevron-down',
				'library' => 'fa-solid',
			],
			'recommended' => [
				'fa-solid' => [ 'chevron-down', 'angle-down', 'caret-down', 'arrow-down' ],
			],
		] );
		$this->add_control( 'mobile_heading', [ 'label' => esc_html__( 'Mobile Settings', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'mobile_breakpoint', [ 'label' => esc_html__( 'Mobile Breakpoint (px)', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::NUMBER, 'default' => 1024, 'min' => 320, 'max' => 1920, 'description' => esc_html__( 'Below this width the hamburger menu appears.', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'show_hamburger', [ 'label' => esc_html__( 'Show Hamburger Button', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => [ 'menu_layout' => 'horizontal' ] ] );
		
		$this->add_control( 'hamburger_open_icon', [
			'label'       => esc_html__( 'Hamburger Open Icon', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::ICONS,
			'default'     => [
				'value'   => 'fas fa-bars',
				'library' => 'fa-solid',
			],
			'recommended' => [
				'fa-solid' => [ 'bars', 'align-justify', 'grip-lines', 'stream' ],
			],
			'condition'   => [ 'menu_layout' => 'horizontal', 'show_hamburger' => 'yes' ],
		] );
		
		$this->add_control( 'hamburger_close_icon', [
			'label'       => esc_html__( 'Hamburger Close Icon', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::ICONS,
			'default'     => [
				'value'   => 'fas fa-times',
				'library' => 'fa-solid',
			],
			'recommended' => [
				'fa-solid' => [ 'times', 'times-circle', 'xmark', 'close' ],
			],
			'condition'   => [ 'menu_layout' => 'horizontal', 'show_hamburger' => 'yes' ],
		] );
		
		$this->add_control( 'vertical_heading', [ 'label' => esc_html__( 'Vertical Sidebar', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => [ 'menu_layout' => 'vertical' ] ] );
		$this->add_control( 'cat_header_label', [ 'label' => esc_html__( 'Header Label', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'All Categories', 'powerkit-addons-for-elementor' ), 'condition' => [ 'menu_layout' => 'vertical' ] ] );
		$this->add_control( 'cat_header_open', [ 'label' => esc_html__( 'Open by Default', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => [ 'menu_layout' => 'vertical' ] ] );

		$this->add_responsive_control( 'vertical_sidebar_width', [
			'label'      => esc_html__( 'Sidebar Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [ 'px' => [ 'min' => 150, 'max' => 600 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
			'default'    => [ 'size' => 280, 'unit' => 'px' ],
			'condition'  => [ 'menu_layout' => 'vertical' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-wrap.pkae-mm-vertical' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'vertical_dropdown_width', [
			'label'      => esc_html__( 'Dropdown Panel Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 300, 'max' => 1200 ] ],
			'default'    => [ 'size' => 680, 'unit' => 'px' ],
			'condition'  => [ 'menu_layout' => 'vertical' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-mm-vertical .pkae-mm-dropdown'             => 'min-width:{{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-mm-vertical .pkae-mm-dropdown.pkae-mm-mega' => 'min-width:{{SIZE}}{{UNIT}};width:{{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'simple_dropdown_width', [
			'label'      => esc_html__( 'Simple Dropdown Width', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 150, 'max' => 600 ] ],
			'default'    => [ 'size' => 220, 'unit' => 'px' ],
			'condition'  => [ 'menu_layout' => 'horizontal' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-horizontal .pkae-mm-dropdown:not(.pkae-mm-mega)' => 'min-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
		$this->start_controls_section( 'section_items', [ 'label' => esc_html__( 'Menu Items', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
		$repeater = new Repeater();
		$repeater->add_control( 'item_label', [ 'label' => esc_html__( 'Label', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Menu Item', 'powerkit-addons-for-elementor' ), 'label_block' => true ] );
		$repeater->add_control( 'item_link', [ 'label' => esc_html__( 'Link', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::URL, 'placeholder' => 'https://', 'default' => [ 'url' => '#' ] ] );
		$repeater->add_control( 'item_icon', [ 'label' => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::ICONS ] );
		$repeater->add_control( 'badge_sep', [ 'label' => esc_html__( 'Badge', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$repeater->add_control( 'item_badge', [ 'label' => esc_html__( 'Badge Text', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT, 'placeholder' => esc_html__( 'e.g. HOT, NEW, SALE', 'powerkit-addons-for-elementor' ) ] );
		$repeater->add_control( 'item_badge_color', [ 'label' => esc_html__( 'Badge Background', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#e74c3c', 'condition' => [ 'item_badge!' => '' ], 'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .pkae-mm-badge' => 'background:{{VALUE}};' ] ] );
		$repeater->add_control( 'item_badge_text_color', [ 'label' => esc_html__( 'Badge Text Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'condition' => [ 'item_badge!' => '' ], 'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .pkae-mm-badge' => 'color:{{VALUE}};' ] ] );
		$repeater->add_control( 'badge_position', [ 'label' => esc_html__( 'Badge Position', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => [ 'inline' => esc_html__( 'Inline (next to label)', 'powerkit-addons-for-elementor' ), 'top' => esc_html__( 'Above label (ribbon)', 'powerkit-addons-for-elementor' ) ], 'default' => 'inline', 'condition' => [ 'item_badge!' => '' ] ] );
		$repeater->add_control( 'dropdown_sep', [ 'label' => esc_html__( 'Dropdown', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$repeater->add_control( 'dropdown_type', [ 'label' => esc_html__( 'Dropdown Type', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => [ 'none' => esc_html__( 'None', 'powerkit-addons-for-elementor' ), 'simple' => esc_html__( 'Simple List', 'powerkit-addons-for-elementor' ), 'mega' => esc_html__( 'Mega Panel', 'powerkit-addons-for-elementor' ), 'wp_menu' => esc_html__( 'WP Custom Menu', 'powerkit-addons-for-elementor' ), 'posts' => esc_html__( 'Recent Posts', 'powerkit-addons-for-elementor' ), 'products' => esc_html__( 'WooCommerce Products', 'powerkit-addons-for-elementor' ) ], 'default' => 'none' ] );
		$sub = new Repeater();
		$sub->add_control( 'sub_label', [ 'label' => esc_html__( 'Label', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Sub Item', 'powerkit-addons-for-elementor' ), 'label_block' => true ] );
		$sub->add_control( 'sub_link', [ 'label' => esc_html__( 'Link', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ] ] );
		$sub->add_control( 'sub_icon', [ 'label' => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::ICONS ] );
		$sub->add_control( 'sub_desc', [ 'label' => esc_html__( 'Description', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'sub_items', [ 'label' => esc_html__( 'Sub Items', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::REPEATER, 'fields' => $sub->get_controls(), 'title_field' => '{{{ sub_label }}}', 'condition' => [ 'dropdown_type' => 'simple' ], 'default' => [ [ 'sub_label' => 'Sub Item 1', 'sub_link' => [ 'url' => '#' ] ], [ 'sub_label' => 'Sub Item 2', 'sub_link' => [ 'url' => '#' ] ] ] ] );
		$col = new Repeater();
		$col->add_control( 'col_heading', [ 'label' => esc_html__( 'Column Heading', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Category', 'powerkit-addons-for-elementor' ), 'label_block' => true ] );
		
		$col_link = new Repeater();
		$col_link->add_control( 'link_label', [ 'label' => esc_html__( 'Label', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => 'Link Item', 'label_block' => true ] );
		$col_link->add_control( 'link_url', [ 'label' => esc_html__( 'URL', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ] ] );
		$col_link->add_control( 'link_icon', [ 'label' => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::ICONS ] );
		$col_link->add_control( 'link_image', [ 'label' => esc_html__( 'Image', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::MEDIA ] );
		
		$col->add_control( 'col_links', [
			'label'       => esc_html__( 'Links', 'powerkit-addons-for-elementor' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $col_link->get_controls(),
			'title_field' => '{{{ link_label }}}',
			'default'     => [
				[ 'link_label' => 'Item One', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Item Two', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Item Three', 'link_url' => [ 'url' => '#' ] ],
			],
		] );
		$repeater->add_control( 'mega_columns', [ 'label' => esc_html__( 'Mega Columns', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::REPEATER, 'fields' => $col->get_controls(), 'title_field' => '{{{ col_heading }}}', 'condition' => [ 'dropdown_type' => 'mega' ], 'default' => [
			[ 'col_heading' => 'Furniture', 'col_links' => [
				[ 'link_label' => 'Dining Chairs', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Counter Stools', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Occasional Chairs', 'link_url' => [ 'url' => '#' ] ],
			] ],
			[ 'col_heading' => 'Accessories', 'col_links' => [
				[ 'link_label' => 'Cabinets', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Screens', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Outdoor Furniture', 'link_url' => [ 'url' => '#' ] ],
			] ],
			[ 'col_heading' => 'Lightings', 'col_links' => [
				[ 'link_label' => 'Benches', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Dining Tables', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Coffee Tables', 'link_url' => [ 'url' => '#' ] ],
			] ],
			[ 'col_heading' => 'Texture Lab', 'col_links' => [
				[ 'link_label' => 'Side Tables', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Beside Tables', 'link_url' => [ 'url' => '#' ] ],
				[ 'link_label' => 'Lounge Chairs', 'link_url' => [ 'url' => '#' ] ],
			] ],
		] ] );
		$repeater->add_control( 'mega_image', [ 'label' => esc_html__( 'Mega Panel Image', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::MEDIA, 'condition' => [ 'dropdown_type' => 'mega' ] ] );
		$repeater->add_control( 'mega_promo_text', [ 'label' => esc_html__( 'Promo Bar Text', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::TEXT, 'condition' => [ 'dropdown_type' => 'mega' ] ] );
		$repeater->add_control( 'mega_promo_link', [ 'label' => esc_html__( 'Promo Bar Link', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ], 'condition' => [ 'dropdown_type' => 'mega', 'mega_promo_text!' => '' ] ] );
		$repeater->add_control( 'wp_menu_id', [ 'label' => esc_html__( 'Select WP Menu', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => $this->get_wp_menus(), 'condition' => [ 'dropdown_type' => 'wp_menu' ] ] );
		$repeater->add_control( 'posts_post_type', [ 'label' => esc_html__( 'Post Type', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => $this->get_post_types(), 'default' => 'post', 'condition' => [ 'dropdown_type' => 'posts' ] ] );
		$repeater->add_control( 'posts_count', [ 'label' => esc_html__( 'Number of Posts', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 12, 'condition' => [ 'dropdown_type' => 'posts' ] ] );
		$repeater->add_control( 'posts_columns', [ 'label' => esc_html__( 'Columns', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ], 'default' => '3', 'condition' => [ 'dropdown_type' => 'posts' ] ] );
		$repeater->add_control( 'posts_show_thumb', [ 'label' => esc_html__( 'Show Thumbnail', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => [ 'dropdown_type' => 'posts' ] ] );
		$repeater->add_control( 'posts_show_date', [ 'label' => esc_html__( 'Show Date', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => [ 'dropdown_type' => 'posts' ] ] );
		$repeater->add_responsive_control( 'products_count', [ 'label' => esc_html__( 'Number of Products', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::NUMBER, 'default' => 4, 'min' => 1, 'max' => 12, 'condition' => [ 'dropdown_type' => 'products' ] ] );
		$repeater->add_responsive_control( 'products_columns', [ 'label' => esc_html__( 'Columns', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ], 'default' => '4', 'condition' => [ 'dropdown_type' => 'products' ] ] );
		$repeater->add_control( 'products_orderby', [ 'label' => esc_html__( 'Order By', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => [ 'date' => esc_html__( 'Latest', 'powerkit-addons-for-elementor' ), 'popularity' => esc_html__( 'Popular', 'powerkit-addons-for-elementor' ), 'rating' => esc_html__( 'Top Rated', 'powerkit-addons-for-elementor' ), 'rand' => esc_html__( 'Random', 'powerkit-addons-for-elementor' ) ], 'default' => 'date', 'condition' => [ 'dropdown_type' => 'products' ] ] );
		$repeater->add_control( 'products_featured', [ 'label' => esc_html__( 'Featured Only', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '', 'condition' => [ 'dropdown_type' => 'products' ] ] );
		$repeater->add_control( 'products_show_price', [ 'label' => esc_html__( 'Show Price', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => [ 'dropdown_type' => 'products' ] ] );
		$this->add_control( 'menu_items', [ 'label' => esc_html__( 'Items', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'title_field' => '{{{ item_label }}}', 'default' => [ [ 'item_label' => 'Home', 'item_link' => [ 'url' => '#' ], 'dropdown_type' => 'none' ], [ 'item_label' => 'Shop', 'item_link' => [ 'url' => '#' ], 'dropdown_type' => 'mega', 'item_badge' => 'NEW' ], [ 'item_label' => 'Blog', 'item_link' => [ 'url' => '#' ], 'dropdown_type' => 'posts' ], [ 'item_label' => 'Contact', 'item_link' => [ 'url' => '#' ], 'dropdown_type' => 'none' ] ] ] );
		$this->end_controls_section();
		$this->start_controls_section( 'style_nav', [ 'label' => esc_html__( 'Nav Bar', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'nav_bg', 'types' => [ 'classic', 'gradient' ], 'selector' => '{{WRAPPER}} .pkae-mm-wrap' ] );
		$this->add_responsive_control( 'nav_padding', [ 'label' => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-wrap' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'nav_border', 'selector' => '{{WRAPPER}} .pkae-mm-wrap' ] );
		$this->add_responsive_control( 'nav_border_radius', [ 'label' => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-wrap' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'nav_shadow', 'selector' => '{{WRAPPER}} .pkae-mm-wrap' ] );
		$this->end_controls_section();
		$this->start_controls_section( 'style_items', [ 'label' => esc_html__( 'Menu Items', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'item_typo', 'selector' => '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link' ] );
		$this->add_responsive_control( 'item_padding', [ 'label' => esc_html__( 'Item Padding', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'item_gap', [ 'label' => esc_html__( 'Items Gap', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav' => 'gap:{{SIZE}}{{UNIT}};' ] ] );
		$this->start_controls_tabs( 'tabs_item' );
		$this->start_controls_tab( 'tab_item_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'item_color', [ 'label' => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link' => 'color:{{VALUE}};' ] ] );
		$this->add_control( 'item_bg', [ 'label' => esc_html__( 'Background', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link' => 'background:{{VALUE}};' ] ] );
		$this->end_controls_tab();
		$this->start_controls_tab( 'tab_item_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'item_color_hover', [ 'label' => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item:hover > .pkae-mm-link, {{WRAPPER}} .pkae-mm-nav > .pkae-mm-item.pkae-mm-open > .pkae-mm-link' => 'color:{{VALUE}};' ] ] );
		$this->add_control( 'item_bg_hover', [ 'label' => esc_html__( 'Background', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item:hover > .pkae-mm-link, {{WRAPPER}} .pkae-mm-nav > .pkae-mm-item.pkae-mm-open > .pkae-mm-link' => 'background:{{VALUE}};' ] ] );
		$this->add_control( 'item_underline_color', [ 'label' => esc_html__( 'Active Underline Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item:hover > .pkae-mm-link::after, {{WRAPPER}} .pkae-mm-nav > .pkae-mm-item.pkae-mm-open > .pkae-mm-link::after' => 'background:{{VALUE}};' ] ] );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control( 'item_border_radius', [ 'label' => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'separator' => 'before', 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'item_font_size', [ 'label' => esc_html__( 'Font Size', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em', 'rem' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link' => 'font-size:{{SIZE}}{{UNIT}};' ] ] );
		$this->end_controls_section();
		$this->start_controls_section( 'style_cat_header', [ 'label' => esc_html__( 'Category Header', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'menu_layout' => 'vertical' ] ] );
		$this->add_control( 'cat_header_bg', [ 'label' => esc_html__( 'Background', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#f0a500', 'selectors' => [ '{{WRAPPER}} .pkae-mm-cat-header' => 'background:{{VALUE}};' ] ] );
		$this->add_control( 'cat_header_color', [ 'label' => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .pkae-mm-cat-header' => 'color:{{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'cat_header_typo', 'selector' => '{{WRAPPER}} .pkae-mm-cat-header .pkae-mm-cat-label' ] );
		$this->add_responsive_control( 'cat_header_padding', [ 'label' => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-cat-header' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'cat_header_radius', [ 'label' => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-cat-header' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'cat_header_font_size', [ 'label' => esc_html__( 'Font Size', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em', 'rem' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-cat-header .pkae-mm-cat-label' => 'font-size:{{SIZE}}{{UNIT}};' ] ] );
		$this->end_controls_section();
		$this->start_controls_section( 'style_dropdown', [ 'label' => esc_html__( 'Dropdown Panel', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'dropdown_bg', 'types' => [ 'classic', 'gradient' ], 'selector' => '{{WRAPPER}} .pkae-mm-dropdown' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'dropdown_border', 'selector' => '{{WRAPPER}} .pkae-mm-dropdown' ] );
		$this->add_responsive_control( 'dropdown_radius', [ 'label' => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-dropdown' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'dropdown_shadow', 'selector' => '{{WRAPPER}} .pkae-mm-dropdown' ] );
		$this->add_responsive_control( 'dropdown_padding', [ 'label' => esc_html__( 'Inner Padding', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-mega-cols, {{WRAPPER}} .pkae-mm-simple-list, {{WRAPPER}} .pkae-mm-wp-wrap, {{WRAPPER}} .pkae-mm-posts-panel, {{WRAPPER}} .pkae-mm-products-panel' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'dropdown_min_width', [ 'label' => esc_html__( 'Min Width (simple)', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 150, 'max' => 600 ] ], 'default' => [ 'size' => 220, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-dropdown:not(.pkae-mm-mega)' => 'min-width:{{SIZE}}{{UNIT}};' ] ] );
		$this->end_controls_section();
		$this->start_controls_section( 'style_sublinks', [ 'label' => esc_html__( 'Sub-menu Links', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'sub_typo', 'selector' => '{{WRAPPER}} .pkae-mm-simple-list a, {{WRAPPER}} .pkae-mm-col-links a, {{WRAPPER}} .pkae-mm-wp-wrap a' ] );
		$this->add_control( 'sub_color', [ 'label' => esc_html__( 'Text Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-simple-list a, {{WRAPPER}} .pkae-mm-col-links a, {{WRAPPER}} .pkae-mm-wp-wrap a' => 'color:{{VALUE}};' ] ] );
		$this->add_control( 'sub_color_hover', [ 'label' => esc_html__( 'Hover Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-simple-list a:hover, {{WRAPPER}} .pkae-mm-col-links a:hover, {{WRAPPER}} .pkae-mm-wp-wrap a:hover' => 'color:{{VALUE}};' ] ] );
		$this->add_control( 'col_title_color', [ 'label' => esc_html__( 'Column Title Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'separator' => 'before', 'selectors' => [ '{{WRAPPER}} .pkae-mm-col-title' => 'color:{{VALUE}}; border-color:{{VALUE}};' ] ] );
		$this->add_control( 'col_title_color_dot', [ 'label' => esc_html__( 'Column Title Dot Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-col-title::before' => 'background:{{VALUE}};' ] ] );
		$this->add_responsive_control( 'sub_link_padding', [ 'label' => esc_html__( 'Link Padding', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'separator' => 'before', 'selectors' => [ '{{WRAPPER}} .pkae-mm-simple-list li a, {{WRAPPER}} .pkae-mm-col-links a, {{WRAPPER}} .pkae-mm-wp-wrap ul li a' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'sub_font_size', [ 'label' => esc_html__( 'Font Size', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em', 'rem' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 30 ] ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-simple-list a, {{WRAPPER}} .pkae-mm-col-links a, {{WRAPPER}} .pkae-mm-wp-wrap a' => 'font-size:{{SIZE}}{{UNIT}};' ] ] );
		$this->end_controls_section();
		// ── Posts Style Section ──────────────────────────────────────────────
		$this->start_controls_section( 'style_posts', [ 'label' => esc_html__( 'Posts Dropdown', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'posts_show_image_global', [
			'label'        => esc_html__( 'Show Image', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->add_control( 'posts_show_title_global', [
			'label'        => esc_html__( 'Show Title', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->add_control( 'posts_show_date_global', [
			'label'        => esc_html__( 'Show Date', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'posts_style_image_heading', [ 'label' => esc_html__( 'Image', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_responsive_control( 'posts_image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-post-thumb' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'posts_image_height', [
			'label'      => esc_html__( 'Image Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 40, 'max' => 300 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-post-thumb' => 'height:{{SIZE}}{{UNIT}}; aspect-ratio:unset;' ],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'posts_image_border',
			'selector' => '{{WRAPPER}} .pkae-mm-post-thumb',
		] );

		$this->add_control( 'posts_style_title_heading', [ 'label' => esc_html__( 'Title', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'posts_title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-post-title' => 'color:{{VALUE}};' ],
		] );
		$this->add_control( 'posts_title_color_hover', [
			'label'     => esc_html__( 'Hover Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-post-card a:hover .pkae-mm-post-title' => 'color:{{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'posts_title_typo', 'selector' => '{{WRAPPER}} .pkae-mm-post-title' ] );

		$this->add_control( 'posts_style_date_heading', [ 'label' => esc_html__( 'Date', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'posts_date_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-post-meta' => 'color:{{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'posts_date_typo', 'selector' => '{{WRAPPER}} .pkae-mm-post-meta' ] );

		$this->add_control( 'posts_style_card_heading', [ 'label' => esc_html__( 'Card', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'posts_card_bg', [
			'label'     => esc_html__( 'Card Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-post-card' => 'background:{{VALUE}};' ],
		] );
		$this->add_responsive_control( 'posts_card_padding', [
			'label'      => esc_html__( 'Card Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-post-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'posts_card_radius', [
			'label'      => esc_html__( 'Card Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-post-card' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'posts_panel_gap', [
			'label'      => esc_html__( 'Cards Gap', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-posts-grid' => 'gap:{{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── Products Style Section ───────────────────────────────────────────
		$this->start_controls_section( 'style_products', [ 'label' => esc_html__( 'Products Dropdown', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'products_show_image_global', [
			'label'        => esc_html__( 'Show Image', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->add_control( 'products_show_title_global', [
			'label'        => esc_html__( 'Show Title', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->add_control( 'products_show_price_global', [
			'label'        => esc_html__( 'Show Price', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'products_style_image_heading', [ 'label' => esc_html__( 'Image', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_responsive_control( 'products_image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-product-thumb' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'products_image_height', [
			'label'      => esc_html__( 'Image Height', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 40, 'max' => 400 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-product-thumb' => 'height:{{SIZE}}{{UNIT}}; aspect-ratio:unset;' ],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'products_image_border',
			'selector' => '{{WRAPPER}} .pkae-mm-product-thumb',
		] );

		$this->add_control( 'products_style_title_heading', [ 'label' => esc_html__( 'Title', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'products_title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-product-title' => 'color:{{VALUE}};' ],
		] );
		$this->add_control( 'products_title_color_hover', [
			'label'     => esc_html__( 'Hover Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-product-card a:hover .pkae-mm-product-title' => 'color:{{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'products_title_typo', 'selector' => '{{WRAPPER}} .pkae-mm-product-title' ] );

		$this->add_control( 'products_style_price_heading', [ 'label' => esc_html__( 'Price', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'products_price_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-product-price' => 'color:{{VALUE}};' ],
		] );
		$this->add_control( 'products_price_sale_color', [
			'label'     => esc_html__( 'Sale Price Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-product-price ins' => 'color:{{VALUE}};' ],
		] );
		$this->add_control( 'products_price_old_color', [
			'label'     => esc_html__( 'Old Price Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-product-price del' => 'color:{{VALUE}};' ],
		] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'products_price_typo', 'selector' => '{{WRAPPER}} .pkae-mm-product-price' ] );

		$this->add_control( 'products_style_card_heading', [ 'label' => esc_html__( 'Card', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'products_card_bg', [
			'label'     => esc_html__( 'Card Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-product-card' => 'background:{{VALUE}};' ],
		] );
		$this->add_responsive_control( 'products_card_padding', [
			'label'      => esc_html__( 'Card Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-product-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'products_card_radius', [
			'label'      => esc_html__( 'Card Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-product-card' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'products_panel_gap', [
			'label'      => esc_html__( 'Cards Gap', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-products-grid' => 'gap:{{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── Mega Panel Links Style Section ───────────────────────────────────
		$this->start_controls_section( 'style_mega_links', [ 'label' => esc_html__( 'Mega Panel Links', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_responsive_control( 'mega_link_alignment', [
			'label'     => esc_html__( 'Alignment', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => esc_html__( 'Left', 'powerkit-addons-for-elementor' ),   'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => esc_html__( 'Center', 'powerkit-addons-for-elementor' ), 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => esc_html__( 'Right', 'powerkit-addons-for-elementor' ),  'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'flex-start',
			'selectors' => [ '{{WRAPPER}} .pkae-mm-col-links .pkae-mm-col-link-item' => 'justify-content:{{VALUE}} !important;' ],
		] );

		$this->add_control( 'mega_link_icon_heading', [ 'label' => esc_html__( 'Icon', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_responsive_control( 'mega_link_icon_size', [
			'label'      => esc_html__( 'Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-mm-col-link-icon' => 'font-size:{{SIZE}}{{UNIT}}; width:{{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-mm-col-link-icon svg' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-mm-col-link-icon i' => 'font-size:{{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_control( 'mega_link_icon_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-mm-col-link-icon' => 'color:{{VALUE}};',
				'{{WRAPPER}} .pkae-mm-col-link-icon svg' => 'fill:{{VALUE}};',
			],
		] );
		$this->add_control( 'mega_link_icon_color_hover', [
			'label'     => esc_html__( 'Hover Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-mm-col-link-item:hover .pkae-mm-col-link-icon' => 'color:{{VALUE}};',
				'{{WRAPPER}} .pkae-mm-col-link-item:hover .pkae-mm-col-link-icon svg' => 'fill:{{VALUE}};',
			],
		] );

		$this->add_control( 'mega_link_image_heading', [ 'label' => esc_html__( 'Image', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_responsive_control( 'mega_link_image_size', [
			'label'      => esc_html__( 'Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-mm-col-link-img' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-mm-col-link-img img' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_responsive_control( 'mega_link_image_radius', [
			'label'      => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-mm-col-link-img' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .pkae-mm-col-link-img img' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'mega_link_image_border',
			'selector' => '{{WRAPPER}} .pkae-mm-col-link-img, {{WRAPPER}} .pkae-mm-col-link-img img',
		] );

		$this->add_control( 'mega_link_title_heading', [ 'label' => esc_html__( 'Title', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->start_controls_tabs( 'tabs_mega_link_title' );
		
		$this->start_controls_tab( 'tab_mega_link_title_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'mega_link_title_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-col-links a' => 'color:{{VALUE}};' ],
		] );
		$this->add_control( 'mega_link_title_bg', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-col-link-item' => 'background:{{VALUE}};' ],
		] );
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tab_mega_link_title_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'mega_link_title_color_hover', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-col-links a:hover' => 'color:{{VALUE}};' ],
		] );
		$this->add_control( 'mega_link_title_bg_hover', [
			'label'     => esc_html__( 'Background', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pkae-mm-col-link-item:hover' => 'background:{{VALUE}};' ],
		] );
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'mega_link_title_typo', 'selector' => '{{WRAPPER}} .pkae-mm-col-links a', 'separator' => 'before' ] );
		$this->add_responsive_control( 'mega_link_padding', [
			'label'      => esc_html__( 'Link Padding', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-col-link-item' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'mega_link_radius', [
			'label'      => esc_html__( 'Link Border Radius', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-col-link-item' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_responsive_control( 'mega_link_gap', [
			'label'      => esc_html__( 'Gap Between Items', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-col-links' => 'gap:{{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── Dropdown Indicator Style Section ─────────────────────────────────
		$this->start_controls_section( 'style_dropdown_indicator', [ 'label' => esc_html__( 'Dropdown Indicator', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_responsive_control( 'indicator_size', [
			'label'      => esc_html__( 'Size', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [ 'px' => [ 'min' => 8, 'max' => 40 ] ],
			'default'    => [ 'size' => 18, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .pkae-mm-indicator' => 'font-size:{{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-mm-indicator svg' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pkae-mm-indicator i' => 'font-size:{{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'tabs_indicator' );
		
		$this->start_controls_tab( 'tab_indicator_normal', [ 'label' => esc_html__( 'Normal', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'indicator_color', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .pkae-mm-indicator' => 'color:{{VALUE}};',
				'{{WRAPPER}} .pkae-mm-indicator svg' => 'fill:{{VALUE}};',
			],
		] );
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tab_indicator_hover', [ 'label' => esc_html__( 'Hover', 'powerkit-addons-for-elementor' ) ] );
		$this->add_control( 'indicator_color_hover', [
			'label'     => esc_html__( 'Color', 'powerkit-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pkae-mm-item:hover .pkae-mm-indicator' => 'color:{{VALUE}};',
				'{{WRAPPER}} .pkae-mm-item:hover .pkae-mm-indicator svg' => 'fill:{{VALUE}};',
				'{{WRAPPER}} .pkae-mm-item.pkae-mm-open .pkae-mm-indicator' => 'color:{{VALUE}};',
				'{{WRAPPER}} .pkae-mm-item.pkae-mm-open .pkae-mm-indicator svg' => 'fill:{{VALUE}};',
			],
		] );
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_control( 'indicator_rotate_on_open', [
			'label'        => esc_html__( 'Rotate on Open', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );

		$this->add_responsive_control( 'indicator_spacing', [
			'label'      => esc_html__( 'Spacing', 'powerkit-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
			'default'    => [ 'size' => 6, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .pkae-mm-indicator' => 'margin-left:{{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'style_hamburger', [ 'label' => esc_html__( 'Hamburger Button', 'powerkit-addons-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'hamburger_color', [ 'label' => esc_html__( 'Color', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-hamburger' => 'color:{{VALUE}};' ] ] );
		$this->add_control( 'hamburger_bg', [ 'label' => esc_html__( 'Background', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .pkae-mm-hamburger' => 'background:{{VALUE}};' ] ] );
		$this->add_responsive_control( 'hamburger_size', [ 'label' => esc_html__( 'Size', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 24, 'max' => 80 ] ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-hamburger' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'hamburger_radius', [ 'label' => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-hamburger' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->add_responsive_control( 'hamburger_padding', [ 'label' => esc_html__( 'Padding', 'powerkit-addons-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .pkae-mm-hamburger' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->end_controls_section();
	}

	protected function render_dropdown( $item, $type, $s = [] ) {
		switch ( $type ) {
			case 'simple':
				$subs = ! empty( $item['sub_items'] ) ? $item['sub_items'] : [];
				echo '<ul class="pkae-mm-simple-list" role="menu">';
				foreach ( $subs as $sub ) {
					$su  = ! empty( $sub['sub_link']['url'] ) ? $sub['sub_link']['url'] : '#';
					$st  = ! empty( $sub['sub_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
					echo '<li role="none"><a href="' . esc_url( $su ) . '" role="menuitem"' . $st . '>';
					if ( ! empty( $sub['sub_icon']['value'] ) ) {
						echo '<span class="pkae-mm-sub-icon" aria-hidden="true">';
						Icons_Manager::render_icon( $sub['sub_icon'], [ 'aria-hidden' => 'true' ] );
						echo '</span>';
					}
					echo '<span><span class="pkae-mm-sub-label">' . esc_html( $sub['sub_label'] ) . '</span>';
					if ( ! empty( $sub['sub_desc'] ) ) {
						echo '<span class="pkae-mm-sub-desc">' . esc_html( $sub['sub_desc'] ) . '</span>';
					}
					echo '</span></a></li>';
				}
				echo '</ul>';
				break;

			case 'mega':
				$cols      = ! empty( $item['mega_columns'] ) ? $item['mega_columns'] : [];
				$img_url   = ! empty( $item['mega_image']['url'] ) ? $item['mega_image']['url'] : '';
				$promo     = ! empty( $item['mega_promo_text'] ) ? $item['mega_promo_text'] : '';
				$promo_url = ! empty( $item['mega_promo_link']['url'] ) ? $item['mega_promo_link']['url'] : '#';
				$col_count = max( 1, count( $cols ) );
				echo '<div class="pkae-mm-mega-inner">';
				echo '<div class="pkae-mm-mega-cols" style="--mm-cols:' . esc_attr( $col_count ) . ';">';
				foreach ( $cols as $col ) {
					$heading   = ! empty( $col['col_heading'] ) ? $col['col_heading'] : '';
					$col_links = ! empty( $col['col_links'] ) ? $col['col_links'] : [];
					echo '<div class="pkae-mm-mega-col">';
					if ( $heading ) {
						echo '<div class="pkae-mm-col-title">' . esc_html( $heading ) . '</div>';
					}
					echo '<div class="pkae-mm-col-links">';
					foreach ( $col_links as $link ) {
						$lbl   = ! empty( $link['link_label'] ) ? $link['link_label'] : '';
						$url   = ! empty( $link['link_url']['url'] ) ? $link['link_url']['url'] : '#';
						$ext   = ! empty( $link['link_url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
						$icon  = ! empty( $link['link_icon']['value'] ) ? $link['link_icon'] : false;
						$img   = ! empty( $link['link_image']['url'] ) ? $link['link_image']['url'] : '';
						
						echo '<a href="' . esc_url( $url ) . '"' . $ext . ' class="pkae-mm-col-link-item">';
						if ( $img ) {
							// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
							echo '<span class="pkae-mm-col-link-img"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( $lbl ) . '" loading="lazy"></span>';
						}
						if ( $icon ) {
							echo '<span class="pkae-mm-col-link-icon">';
							Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
							echo '</span>';
						}
						if ( $lbl ) {
							echo '<span class="pkae-mm-col-link-label">' . esc_html( $lbl ) . '</span>';
						}
						echo '</a>';
					}
					echo '</div></div>';
				}
				echo '</div>';
				if ( $img_url ) {
					// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
					echo '<div class="pkae-mm-mega-image"><img src="' . esc_url( $img_url ) . '" alt="" loading="lazy"></div>';
				}
				echo '</div>';
				if ( $promo ) {
					echo '<div class="pkae-mm-promo-bar"><a href="' . esc_url( $promo_url ) . '">' . esc_html( $promo ) . '</a></div>';
				}
				break;

			case 'wp_menu':
				$menu_id = ! empty( $item['wp_menu_id'] ) ? (int) $item['wp_menu_id'] : 0;
				if ( $menu_id ) {
					echo '<div class="pkae-mm-wp-wrap">';
					wp_nav_menu( [ 'menu' => $menu_id, 'container' => false, 'items_wrap' => '<ul role="menu">%3$s</ul>', 'echo' => true ] );
					echo '</div>';
				}
				break;

			case 'posts':
				$post_type  = ! empty( $item['posts_post_type'] ) ? $item['posts_post_type'] : 'post';
				$count      = ! empty( $item['posts_count'] ) ? (int) $item['posts_count'] : 3;
				$columns    = ! empty( $item['posts_columns'] ) ? (int) $item['posts_columns'] : 3;
				// Global show/hide overrides (from Style tab), fallback to per-item setting
				$show_thumb = isset( $s['posts_show_image_global'] ) ? 'yes' === $s['posts_show_image_global'] : ( ! empty( $item['posts_show_thumb'] ) && 'yes' === $item['posts_show_thumb'] );
				$show_title = ! isset( $s['posts_show_title_global'] ) || 'yes' === $s['posts_show_title_global'];
				$show_date  = isset( $s['posts_show_date_global'] ) ? 'yes' === $s['posts_show_date_global'] : ( ! empty( $item['posts_show_date'] ) && 'yes' === $item['posts_show_date'] );
				$q = new \WP_Query( [ 'post_type' => $post_type, 'posts_per_page' => $count, 'post_status' => 'publish', 'no_found_rows' => true ] );
				if ( $q->have_posts() ) {
					echo '<div class="pkae-mm-posts-panel"><div class="pkae-mm-posts-grid" style="--mm-post-cols:' . esc_attr( $columns ) . ';">';
					while ( $q->have_posts() ) {
						$q->the_post();
						$thumb = get_the_post_thumbnail_url( null, 'medium' );
						echo '<div class="pkae-mm-post-card"><a href="' . esc_url( get_permalink() ) . '">';
						if ( $show_thumb && $thumb ) {
							// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
							echo '<img class="pkae-mm-post-thumb" src="' . esc_url( $thumb ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy">';
						}
						if ( $show_title ) {
							echo '<span class="pkae-mm-post-title">' . esc_html( get_the_title() ) . '</span>';
						}
						echo '</a>';
						if ( $show_date ) {
							echo '<span class="pkae-mm-post-meta">' . esc_html( get_the_date() ) . '</span>';
						}
						echo '</div>';
					}
					wp_reset_postdata();
					echo '</div></div>';
				}
				break;

			case 'products':
				if ( ! class_exists( 'WooCommerce' ) ) {
					echo '<p style="padding:16px;font-size:13px;color:#999;">' . esc_html__( 'WooCommerce required.', 'powerkit-addons-for-elementor' ) . '</p>';
					break;
				}
				$count   = ! empty( $item['products_count'] ) ? (int) $item['products_count'] : 4;
				$columns = ! empty( $item['products_columns'] ) ? (int) $item['products_columns'] : 4;
				$orderby = ! empty( $item['products_orderby'] ) ? $item['products_orderby'] : 'date';
				$feat    = ! empty( $item['products_featured'] ) && 'yes' === $item['products_featured'];
				// Global show/hide overrides (from Style tab)
				$show_image = ! isset( $s['products_show_image_global'] ) || 'yes' === $s['products_show_image_global'];
				$show_title = ! isset( $s['products_show_title_global'] ) || 'yes' === $s['products_show_title_global'];
				$show_price = isset( $s['products_show_price_global'] ) ? 'yes' === $s['products_show_price_global'] : ( ! isset( $item['products_show_price'] ) || 'yes' === $item['products_show_price'] );
				$ord_map = [
					'date'       => [ 'orderby' => 'date',            'order' => 'DESC', 'meta_key' => '' ],
					'popularity' => [ 'orderby' => 'meta_value_num',  'order' => 'DESC', 'meta_key' => 'total_sales' ],
					'rating'     => [ 'orderby' => 'meta_value_num',  'order' => 'DESC', 'meta_key' => '_wc_average_rating' ],
					'rand'       => [ 'orderby' => 'rand',            'order' => 'ASC',  'meta_key' => '' ],
				];
				$ord  = $ord_map[ $orderby ] ?? $ord_map['date'];
				$args = [ 'post_type' => 'product', 'posts_per_page' => $count, 'post_status' => 'publish', 'orderby' => $ord['orderby'], 'order' => $ord['order'], 'no_found_rows' => true ];
				if ( $ord['meta_key'] ) $args['meta_key'] = $ord['meta_key']; // phpcs:ignore WordPress.DB.SlowDBQuery
				if ( $feat ) $args['meta_query'] = [ [ 'key' => '_featured', 'value' => 'yes' ] ]; // phpcs:ignore WordPress.DB.SlowDBQuery
				$q = new \WP_Query( $args );
				if ( $q->have_posts() ) {
					echo '<div class="pkae-mm-products-panel"><div class="pkae-mm-products-grid" style="--mm-prod-cols:' . esc_attr( $columns ) . ';">';
					while ( $q->have_posts() ) {
						$q->the_post();
						global $product;
						$product    = wc_get_product( get_the_ID() );
						$thumb      = get_the_post_thumbnail_url( null, 'woocommerce_thumbnail' );
						$price_html = $product ? $product->get_price_html() : '';
						echo '<div class="pkae-mm-product-card"><a href="' . esc_url( get_permalink() ) . '">';
						if ( $show_image && $thumb ) {
							// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
							echo '<img class="pkae-mm-product-thumb" src="' . esc_url( $thumb ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy">';
						}
						if ( $show_title ) {
							echo '<span class="pkae-mm-product-title">' . esc_html( get_the_title() ) . '</span>';
						}
						echo '</a>';
						if ( $show_price && $price_html ) {
							echo '<span class="pkae-mm-product-price">' . wp_kses_post( $price_html ) . '</span>';
						}
						echo '</div>';
					}
					wp_reset_postdata();
					echo '</div></div>';
				}
				break;
		}
	}

	protected function render() {
		$s         = $this->get_settings_for_display();
		$items     = ! empty( $s['menu_items'] ) ? $s['menu_items'] : [];
		$layout    = ! empty( $s['menu_layout'] ) ? $s['menu_layout'] : 'horizontal';
		$trigger   = ! empty( $s['dropdown_trigger'] ) ? $s['dropdown_trigger'] : 'hover';
		$animation = ! empty( $s['dropdown_animation'] ) ? $s['dropdown_animation'] : 'slide';
		$bp        = ! empty( $s['mobile_breakpoint'] ) ? (int) $s['mobile_breakpoint'] : 1024;
		$show_ham  = ! empty( $s['show_hamburger'] ) && 'yes' === $s['show_hamburger'];
		$is_vert   = 'vertical' === $layout;
		$cat_label = ! empty( $s['cat_header_label'] ) ? $s['cat_header_label'] : esc_html__( 'All Categories', 'powerkit-addons-for-elementor' );
		$cat_open  = ! empty( $s['cat_header_open'] ) && 'yes' === $s['cat_header_open'];
		$wid       = $this->get_id();
		?>
		<style>
		/* ── DESKTOP ─────────────────────────────────────────── */
		@media (min-width: <?php echo esc_attr( $bp + 1 ); ?>px) {
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-hamburger { display: none !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav { display: flex !important; flex-wrap: wrap !important; }
		}
		/* ── MOBILE ──────────────────────────────────────────── */
		@media (max-width: <?php echo esc_attr( $bp ); ?>px) {
			/* Show hamburger */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-hamburger { display: flex !important; }

			/* Hide nav by default, show when open */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav { display: none !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav.pkae-mm-nav-open {
				display: flex !important;
				flex-direction: column !important;
				flex-wrap: nowrap !important;
				align-items: stretch !important;
				position: absolute !important;
				top: 100% !important; left: 0 !important; right: 0 !important;
				z-index: 99999 !important;
				background: #fff !important;
				border: 1px solid #e8e8e8 !important;
				border-radius: 0 0 8px 8px !important;
				box-shadow: 0 8px 30px rgba(0,0,0,.12) !important;
				max-height: 80vh !important;
				overflow-y: auto !important;
				width: 100% !important;
			}

			/* Each nav item: full width, stacked */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav > .pkae-mm-item {
				width: 100% !important;
				border-bottom: 1px solid #f0f0f0 !important;
			}
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav > .pkae-mm-item:last-child { border-bottom: none !important; }

			/* Item link: full width row */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link {
				display: flex !important;
				align-items: center !important;
				width: 100% !important;
				padding: 14px 18px !important;
				font-size: 15px !important;
			}
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link .pkae-mm-label { flex: 1 !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-nav > .pkae-mm-item > .pkae-mm-link::after { display: none !important; }

			/* Dropdown: static/inline accordion — appears right below its item */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-dropdown,
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-item:not(.pkae-mm-mega-item) > .pkae-mm-dropdown {
				position: static !important;
				transform: none !important;
				opacity: 0 !important;
				visibility: hidden !important;
				display: none !important;
				width: 100% !important;
				min-width: unset !important;
				box-shadow: none !important;
				border-left: none !important;
				border-right: none !important;
				border-bottom: none !important;
				border-top: 1px solid #f0f0f0 !important;
				border-radius: 0 !important;
				pointer-events: auto !important;
				left: 0 !important;
				right: auto !important;
				/* NO background override — let Elementor style control apply */
			}
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-item.pkae-mm-open > .pkae-mm-dropdown,
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-horizontal .pkae-mm-item.pkae-mm-open > .pkae-mm-dropdown {
				display: block !important;
				opacity: 1 !important;
				visibility: visible !important;
			}

			/* Flatten mega grid */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-mega-inner { flex-direction: column !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-mega-cols { grid-template-columns: 1fr !important; padding: 16px !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-mega-col { padding: 0 !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-mega-col + .pkae-mm-mega-col { border-left: none !important; border-top: 1px solid #f0f0f0 !important; margin-top: 12px !important; padding-top: 12px !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-mega-image { width: 100% !important; border-left: none !important; border-top: 1px solid #f0f0f0 !important; }

			/* Posts/products: 1 column on mobile */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-posts-grid { grid-template-columns: 1fr !important; padding: 12px 16px !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-products-grid { grid-template-columns: 1fr 1fr !important; padding: 12px 16px !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-posts-panel,
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-products-panel { padding: 12px 16px !important; width: 100% !important; max-width: 100% !important; overflow: hidden !important; }
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-simple-list,
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-wp-wrap { width: 100% !important; max-width: 100% !important; overflow: hidden !important; }

			/* Vertical layout: accordion style on mobile */
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-vertical .pkae-mm-dropdown {
				position: static !important;
				transform: none !important;
				left: 0 !important;
				top: auto !important;
				width: 100% !important;
				min-width: unset !important;
				border-radius: 0 !important;
				display: none !important;
				opacity: 1 !important;
				visibility: visible !important;
			}
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-vertical .pkae-mm-item.pkae-mm-open > .pkae-mm-dropdown {
				display: block !important;
			}
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-vertical .pkae-mm-nav > .pkae-mm-item {
				border-bottom: 1px solid #f0f0f0 !important;
			}
		}
		@media (max-width: 480px) {
			.elementor-element-<?php echo esc_attr( $wid ); ?> .pkae-mm-products-grid { grid-template-columns: 1fr !important; }
		}
		</style>
		<div class="pkae-mm-wrap pkae-mm-<?php echo esc_attr( $layout ); ?> pkae-mm-anim-<?php echo esc_attr( $animation ); ?>"
			data-pkae-trigger="<?php echo esc_attr( $trigger ); ?>"
			data-pkae-bp="<?php echo esc_attr( $bp ); ?>"
			data-indicator-rotate="<?php echo esc_attr( $s['indicator_rotate_on_open'] === 'yes' ? 'yes' : 'no' ); ?>"
			role="navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'powerkit-addons-for-elementor' ); ?>">

		<?php if ( $is_vert ) : ?>
		<button class="pkae-mm-cat-header<?php echo $cat_open ? ' pkae-mm-cat-open' : ''; ?>"
			aria-expanded="<?php echo $cat_open ? 'true' : 'false'; ?>"
			aria-controls="pkae-mm-nav-<?php echo esc_attr( $wid ); ?>">
			<span class="pkae-mm-cat-icon" aria-hidden="true">&#9776;</span>
			<span class="pkae-mm-cat-label"><?php echo esc_html( $cat_label ); ?></span>
		</button>
		<?php endif; ?>

		<?php if ( ! $is_vert && $show_ham ) : ?>
		<button class="pkae-mm-hamburger"
			aria-label="<?php esc_attr_e( 'Toggle Menu', 'powerkit-addons-for-elementor' ); ?>"
			aria-expanded="false"
			aria-controls="pkae-mm-nav-<?php echo esc_attr( $wid ); ?>">
			<span class="pkae-mm-hamburger-open">
				<?php
				if ( ! empty( $s['hamburger_open_icon']['value'] ) ) {
					Icons_Manager::render_icon( $s['hamburger_open_icon'], [ 'aria-hidden' => 'true' ] );
				} else {
					echo '<span></span><span></span><span></span>';
				}
				?>
			</span>
			<span class="pkae-mm-hamburger-close">
				<?php
				if ( ! empty( $s['hamburger_close_icon']['value'] ) ) {
					Icons_Manager::render_icon( $s['hamburger_close_icon'], [ 'aria-hidden' => 'true' ] );
				} else {
					echo '<span></span><span></span><span></span>';
				}
				?>
			</span>
		</button>
		<?php endif; ?>

		<ul class="pkae-mm-nav<?php echo ( $is_vert && $cat_open ) ? ' pkae-mm-nav-open' : ''; ?>"
			id="pkae-mm-nav-<?php echo esc_attr( $wid ); ?>" role="menubar">
		<?php foreach ( $items as $item ) :
			$type      = ! empty( $item['dropdown_type'] ) ? $item['dropdown_type'] : 'none';
			$has_drop  = 'none' !== $type;
			$label     = ! empty( $item['item_label'] ) ? $item['item_label'] : '';
			$url       = ! empty( $item['item_link']['url'] ) ? $item['item_link']['url'] : '#';
			$ext       = ! empty( $item['item_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
			$nofollow  = ! empty( $item['item_link']['nofollow'] ) ? ' rel="nofollow"' : '';
			$badge     = ! empty( $item['item_badge'] ) ? $item['item_badge'] : '';
			$badge_pos = ! empty( $item['badge_position'] ) ? $item['badge_position'] : 'inline';
			$ikey      = $item['_id'];
		?>
		<li class="pkae-mm-item<?php echo $has_drop ? ' pkae-mm-has-drop' : ''; ?><?php echo ( $has_drop && 'mega' === $type ) ? ' pkae-mm-mega-item' : ''; ?> elementor-repeater-item-<?php echo esc_attr( $ikey ); ?>" role="none">
			<a href="<?php echo esc_url( $url ); ?>" class="pkae-mm-link" role="menuitem"<?php echo $ext . $nofollow; ?>>
				<?php if ( ! empty( $item['item_icon']['value'] ) ) : ?>
				<span class="pkae-mm-item-icon" aria-hidden="true"><?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<?php endif; ?>
				<?php if ( $badge && 'top' === $badge_pos ) : ?>
				<span class="pkae-mm-badge-top"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
				<span class="pkae-mm-label"><?php echo esc_html( $label ); ?></span>
				<?php if ( $badge && 'inline' === $badge_pos ) : ?>
				<span class="pkae-mm-badge"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
				<?php if ( $has_drop ) : ?>
					<span class="pkae-mm-indicator" aria-hidden="true">
						<?php
						if ( ! empty( $s['dropdown_indicator_icon']['value'] ) ) {
							Icons_Manager::render_icon( $s['dropdown_indicator_icon'], [ 'aria-hidden' => 'true' ] );
						} else {
							echo '&#8964;';
						}
						?>
					</span>
				<?php endif; ?>
			</a>
			<?php if ( $has_drop ) : ?>
			<div class="pkae-mm-dropdown<?php echo 'mega' === $type ? ' pkae-mm-mega' : ''; ?>" role="region">
				<?php $this->render_dropdown( $item, $type, $s ); ?>
			</div>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
		</ul>
		</div>
		<script>
		(function(){
			var wid = '<?php echo esc_js( $wid ); ?>';
			var bp  = <?php echo (int) $bp; ?>;
			var trigger = '<?php echo esc_js( $trigger ); ?>';

			function init() {
				var wrap = document.querySelector('.elementor-element-' + wid + ' .pkae-mm-wrap');
				if (!wrap || wrap.dataset.pkaeInit) return;
				wrap.dataset.pkaeInit = '1';

				var nav       = wrap.querySelector('.pkae-mm-nav');
				var hamburger = wrap.querySelector('.pkae-mm-hamburger');
				var catHeader = wrap.querySelector('.pkae-mm-cat-header');
				var isVert    = wrap.classList.contains('pkae-mm-vertical');

				function isMob() { return window.innerWidth <= bp; }

				/* hamburger */
				if (hamburger && nav) {
					hamburger.addEventListener('click', function(e) {
						e.stopPropagation();
						var open = nav.classList.toggle('pkae-mm-nav-open');
						hamburger.classList.toggle('is-active', open);
						hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
					});
				}

				/* cat header */
				if (catHeader && nav) {
					catHeader.addEventListener('click', function(e) {
						e.stopPropagation();
						var open = nav.classList.toggle('pkae-mm-nav-open');
						catHeader.classList.toggle('pkae-mm-cat-open', open);
						catHeader.setAttribute('aria-expanded', open ? 'true' : 'false');
					});
				}

				/* dropdown items */
				var items = Array.prototype.slice.call(wrap.querySelectorAll('.pkae-mm-nav > .pkae-mm-item.pkae-mm-has-drop'));

				function closeAll(ex) {
					items.forEach(function(it) {
						if (it === ex) return;
						it.classList.remove('pkae-mm-open');
						var lnk = it.querySelector('.pkae-mm-link');
						if (lnk) lnk.setAttribute('aria-expanded','false');
					});
				}

				items.forEach(function(item) {
					var link = item.querySelector('.pkae-mm-link');
					var drop = item.querySelector('.pkae-mm-dropdown');
					if (!link || !drop) return;
					link.setAttribute('aria-haspopup','true');
					link.setAttribute('aria-expanded','false');

					function open()   { closeAll(item); item.classList.add('pkae-mm-open'); link.setAttribute('aria-expanded','true'); }
					function close()  { item.classList.remove('pkae-mm-open'); link.setAttribute('aria-expanded','false'); }
					function toggle(e){ e.preventDefault(); e.stopPropagation(); item.classList.contains('pkae-mm-open') ? close() : open(); }

					if (trigger === 'click') {
						link.addEventListener('click', toggle);
					} else {
						var t;
						// Hover mode
						item.addEventListener('mouseenter', function(){
							// Horizontal on mobile: skip hover
							if(isMob() && !isVert) return;
							clearTimeout(t);
							open();
						});
						item.addEventListener('mouseleave', function(){
							// Horizontal on mobile: skip hover
							if(isMob() && !isVert) return;
							t = setTimeout(close, 150);
						});
						// Mobile horizontal: click to toggle
						link.addEventListener('click', function(e){
							if(!isMob() || isVert) return;
							toggle(e);
						});
					}
				});

				/* outside click */
				document.addEventListener('click', function(e) {
					if (wrap.contains(e.target)) return;
					closeAll(null);
					if (nav) nav.classList.remove('pkae-mm-nav-open');
					if (hamburger) { hamburger.classList.remove('is-active'); hamburger.setAttribute('aria-expanded','false'); }
					if (catHeader) { catHeader.classList.remove('pkae-mm-cat-open'); catHeader.setAttribute('aria-expanded','false'); }
				});

				/* resize */
				window.addEventListener('resize', function() {
					if (!isMob() && !isVert) {
						if (nav) nav.classList.remove('pkae-mm-nav-open');
						if (hamburger) { hamburger.classList.remove('is-active'); hamburger.setAttribute('aria-expanded','false'); }
					}
				});
			}

			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', init);
			} else {
				init();
			}
		})();
		</script>
		<?php
	}

}
