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

class Accordion_Carousel extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'pkae-accordion-carousel',
			plugins_url( 'assets/css/pkae-accordion-carousel.css', __FILE__ ),
			[],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION
		);

		wp_register_script(
			'pkae-accordion-carousel',
			plugins_url( 'assets/js/pkae-accordion-carousel.js', __FILE__ ),
			[ 'jquery' ],
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION,
			true
		);

	}

	public function get_name() {
		return 'pkae-accordion-carousel';
	}

	public function get_title() {
		return esc_html__( 'Accordion Carousel Skin-01', 'powerkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-slider-device';
	}

	public function get_categories() {
		return [ 'powerkit-carousel-and-slider-categories' ];
	}

	public function get_style_depends() {
		return [ 'pkae-accordion-carousel' ];
	}

	public function get_script_depends() {
		return [ 'pkae-accordion-carousel' ];
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

		$this->add_control( 'show_dots', [
			'label'        => esc_html__( 'Show Dots', 'powerkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'powerkit-addons-for-elementor' ),
			'label_off'    => esc_html__( 'No', 'powerkit-addons-for-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
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
				'{{WRAPPER}} .pkae-accordion-carousel .project-card' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'closed_width', [
			'label' => esc_html__( 'Closed Width (rem)', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::SLIDER,
			'size_units' => [ 'rem' ],
			'range' => [ 'rem' => [ 'min' => 3, 'max' => 20 ] ],
			'default' => [ 'size' => 5, 'unit' => 'rem' ],
			'selectors' => [
				'{{WRAPPER}}' => '--pkae-closed: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'open_width', [
			'label' => esc_html__( 'Open Width (rem)', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::SLIDER,
			'size_units' => [ 'rem' ],
			'range' => [ 'rem' => [ 'min' => 12, 'max' => 60 ] ],
			'default' => [ 'size' => 30, 'unit' => 'rem' ],
			'selectors' => [
				'{{WRAPPER}}' => '--pkae-open: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'gap', [
			'label' => esc_html__( 'Gap (rem)', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::SLIDER,
			'size_units' => [ 'rem' ],
			'range' => [ 'rem' => [ 'min' => .25, 'max' => 3, 'step' => .05 ] ],
			'default' => [ 'size' => 1.25, 'unit' => 'rem' ],
			'selectors' => [
				'{{WRAPPER}}' => '--pkae-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'radius', [
			'label' => esc_html__( 'Border Radius', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
			'default' => [ 'size' => 16, 'unit' => 'px' ],
			'selectors' => [
				'{{WRAPPER}} .pkae-accordion-carousel .project-card' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'accent', [
			'label' => esc_html__( 'Accent Color', 'powerkit-addons-for-elementor' ),
			'type'  => Controls_Manager::COLOR,
			'default' => '#ff6b35',
			'selectors' => [
				'{{WRAPPER}}' => '--pkae-accent: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_typo', [
			'label' => esc_html__( 'Typography', 'powerkit-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name' => 'title_typo',
			'selector' => '{{WRAPPER}} .pkae-accordion-carousel .project-card__title',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name' => 'desc_typo',
			'selector' => '{{WRAPPER}} .pkae-accordion-carousel .project-card__desc',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings    = $this->get_settings_for_display();
		$slides      = ! empty( $settings['slides'] ) ? $settings['slides'] : [];
		$show_dots   = ( isset( $settings['show_dots'] ) && 'yes' === $settings['show_dots'] ) ? 'yes' : 'no';
		$show_arrows = ( isset( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'] ) ? 'yes' : 'no';

		$wid = 'pkae-accordion-carousel-' . esc_attr( $this->get_id() );
		?>
		<section id="<?php echo esc_attr( $wid ); ?>"
			class="pkae-accordion-carousel"
			data-show-dots="<?php echo esc_attr( $show_dots ); ?>"
			data-show-arrows="<?php echo esc_attr( $show_arrows ); ?>"
			aria-roledescription="carousel">

			<div class="head">
				<div class="controls">
					<button class="nav-btn" data-pkae-prev aria-label="<?php esc_attr_e( 'Previous slide', 'powerkit-addons-for-elementor' ); ?>">‹</button>
					<button class="nav-btn" data-pkae-next aria-label="<?php esc_attr_e( 'Next slide', 'powerkit-addons-for-elementor' ); ?>">›</button>
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

			<div class="dots" aria-hidden="false"></div>
		</section>
		<?php
	}
}
