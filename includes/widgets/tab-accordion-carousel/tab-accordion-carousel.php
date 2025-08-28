<?php
namespace PKAEElementorPowerKitWidgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Accordion_Carousel extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );


		wp_register_style(
			'pkae-owl-carousel',
			plugins_url( 'assets/vendor/owlcarousel/owl.carousel.min.css', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
		wp_register_style(
			'pkae-owl-theme',
			plugins_url( 'assets/vendor/owlcarousel/owl.theme.default.min.css', __FILE__ ),
			[ 'pkae-owl-carousel' ],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);
		wp_register_script(
			'pkae-owl-carousel',
			plugins_url( 'assets/vendor/owlcarousel/owl.carousel.min.js', __FILE__ ),
			[ 'jquery' ],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);

		// Widget CSS/JS
		wp_register_style(
			'pkae-tab-accordion-carousel',
			plugins_url( 'assets/css/tab-accordion-carousel.css', __FILE__ ),
			[ 'pkae-owl-theme' ],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);

		wp_register_script(
			'pkae-tab-accordion-carousel',
			plugins_url( 'assets/js/tab-accordion-carousel.js', __FILE__ ),
			[ 'jquery', 'pkae-owl-carousel' ],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);
	}

	public function get_name() {
		return 'pkae-tab-accordion-carousel';
	}

	public function get_title() {
		return esc_html__( 'Accordion Carousel Skin-02', 'powerkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-slider-device';
	}

	public function get_categories() {
		return [ 'powerkit-carousel-and-slider-categories' ];
	}

	public function get_style_depends() {
		return [ 'pkae-owl-carousel', 'pkae-owl-theme', 'pkae-tab-accordion-carousel' ];
	}

	public function get_script_depends() {
		return [ 'pkae-owl-carousel', 'pkae-tab-accordion-carousel' ];
	}

	protected function register_controls() {
		// Slides
		$this->start_controls_section( 'section_slides', [
			'label' => esc_html__( 'Slides', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();

		$rep->add_control( 'bg_image', [
			'label'   => esc_html__( 'Slide Image', 'powerkit-addons-for-elementor' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
		] );

		$rep->add_control( 'title', [
			'label' => esc_html__( 'Title (optional)', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::TEXT,
			'default' => '',
		] );

		$rep->add_control( 'desc', [
			'label' => esc_html__( 'Description (optional)', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::TEXTAREA,
			'default' => '',
			'rows' => 2,
		] );

		$this->add_control( 'slides', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title || "Slide" }}}',
			'default'     => [
				[ 'bg_image' => [ 'url' => 'https://dummyimage.com/600x400/ef3e3e/231f20.png&text=Carousel' ] ],
				[ 'bg_image' => [ 'url' => 'https://dummyimage.com/600x400/16c1f3/231f20.png&text=Carousel' ] ],
				[ 'bg_image' => [ 'url' => 'https://dummyimage.com/600x400/fff200/231f20.png&text=Carousel' ] ],
				[ 'bg_image' => [ 'url' => 'https://dummyimage.com/600x400/f48480/231f20.png&text=Carousel' ] ],
				[ 'bg_image' => [ 'url' => 'https://dummyimage.com/600x400/8dd8f8/231f20.png&text=Carousel' ] ],
				[ 'bg_image' => [ 'url' => 'https://dummyimage.com/600x400/fffac2/231f20.png&text=Carousel' ] ],
			],
		] );

		$this->end_controls_section();

		// Navigation
		$this->start_controls_section( 'section_nav', [
			'label' => esc_html__( 'Navigation', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_arrows', [
			'label'        => esc_html__( 'Show Arrows', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'show_dots', [
			'label'        => esc_html__( 'Show Dots (Left Thumbs)', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->end_controls_section();

		// Layout
		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'wrapper_width', [
			'label' => esc_html__( 'Wrapper Width (px)', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 240, 'max' => 1400 ] ],
			'default' => [ 'size' => 600, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-content-carousel' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// Typography (optional headings/desc if used)
		$this->start_controls_section( 'section_typo', [
			'label' => esc_html__( 'Typography', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name' => 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-slide-caption .pkae-slide-title',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name' => 'desc_typo',
			'selector' => '{{WRAPPER}} .pkae-slide-caption .pkae-slide-desc',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings    = $this->get_settings_for_display();
		?>
		<div class="pkae-tab-accordion-carousel">
			<h2>Tab Accordion Carousel</h2>
		</div>
		<?php
	}
}
