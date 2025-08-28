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

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 *
 * Elementor widget that inserts a customizable list of team members.
 *
 * @since 1.0.0
 */
class Team_Members extends Widget_Base {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'pkae-team-members', plugins_url( 'css/frontend.css', __FILE__ ), [], PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION );
		wp_register_style( 
			'pkae-awesomefont-brands', 
			plugins_url( 'css/brands.min.css', __FILE__ ), 
			[], 
			PKAE_ELEMENTOR_POWERKIT_ADDONS_VERSION 
		);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pkae-team-members';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Team Members', 'powerkit-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'powerkit-addons-for-elementor' ];
	}

	/**
	 * Render the Widget.
	 *
	 * Renders the widget on the frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div>
			<h1>Hello</h1>
		</div>
		<?php
	}
}
