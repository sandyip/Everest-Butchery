<?php
/**
 * Jeg Elementor Kit Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.0.0
 */

namespace Jeg\Elementor_Kit\Elements;

use Elementor\Controls_Manager;
use Jeg\Element\Element as Jeg_Element;

/**
 * Class Element
 *
 * @package Jeg\Elementor_Kit
 */
class Element {
	/**
	 * Element Manager
	 *
	 * @var Elements_Manager
	 */
	public $manager;

	/**
	 * Class instance
	 *
	 * @var Element
	 */
	private static $instance;

	/**
	 * Module constructor.
	 */
	private function __construct() {
		$this->setup_init();
		$this->setup_hook();
	}

	/**
	 * Setup Classes
	 */
	private function setup_init() {
		$this->manager = Jeg_Element::instance()->manager;
	}

	/**
	 * Setup Hooks
	 */
	private function setup_hook() {
		add_filter( 'jeg_register_elements', array( $this, 'register_element' ) );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'add_widget_options' ), 10 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'add_column_options' ), 10, 2 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'add_section_options' ), 10, 2 );
	}

	/**
	 * Get class instance
	 *
	 * @return Element
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register all elements
	 *
	 * @param array $elements Elements.
	 *
	 * @return array
	 */
	public function register_element( $elements ) {
		$element_config = get_option( 'jkit_elements_enable', array() );

		foreach ( $this->list_elements() as $item ) {
			$item_key = 'jkit_' . strtolower( $item );

			if ( ! isset( $element_config[ $item_key ] ) || $element_config[ $item_key ] ) {
				$namespace             = '\Jeg\Elementor_Kit\Elements';
				$elements[ $item_key ] = array(
					'option'    => $namespace . '\Options\\' . $item . '_Option',
					'view'      => $namespace . '\Views\\' . $item . '_View',
					'elementor' => $namespace . '\Elementor\\' . $item . '_Elementor',
				);
			}
		}

		return $elements;
	}

	/**
	 * List of elements
	 *
	 * @return array
	 */
	public function list_elements() {
		return array(
			'Nav_Menu',
			'Off_Canvas',
			'Search',
			'Icon_Box',
			'Image_Box',
			'Fun_Fact',
			'Progress_Bar',
			'Client_Logo',
			'Testimonials',
			'Accordion',
			'Gallery',
			'Team',
			'Pie_Chart',
			'Portfolio_Gallery',
			'Tabs',
			'Animated_Text',
			'Heading',
			'Countdown',
			'Button',
			'Dual_Button',
			'Video_Button',
			'Social_Share',
			'Post_Block',
			'Post_List',
			'Category_List',
			'Feature_List',
			'Contact_Form_7',
			'Mailchimp',
			'Post_Title',
			'Post_Featured_Image',
			'Post_Comment',
			'Post_Terms',
			'Post_Excerpt',
			'Post_Date',
			'Post_Author',
		);
	}

	/**
	 * Add custom option to elementor widgets
	 *
	 * @param \Elementor\Element_Base $element The edited element.
	 */
	public function add_widget_options( $element ) {
		$element->start_controls_section(
			'jkit_transform_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Transform', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_responsive_control(
			'jkit_transform_rotate',
			array(
				'label'     => esc_html__( 'Rotate', 'jeg-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 360,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => '-moz-transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
				),
			)
		);

		$element->end_controls_section();

		$element->start_controls_section(
			'jkit_glass_blur_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Glass Blur Effect', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_responsive_control(
			'jkit_glass_blur_level',
			array(
				'label'       => esc_html__( 'Blur', 'jeg-elementor-kit' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'description' => esc_html__( 'The blur effect will be set on the widget container. Make sure to set background to transparent to see the blur effect.', 'jeg-elementor-kit' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-widget-container' => '-webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}); backdrop-filter: blur({{SIZE}}{{UNIT}});',
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Add custom option to elementor columns
	 *
	 * @param \Elementor\Element_Base $column The edited element.
	 * @param array @args The         $args that sent to $element->start_controls_section.
	 */
	public function add_column_options( $column, $args ) {
		$column->start_controls_section(
			'jkit_glass_blur_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Glass Blur Effect', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$column->add_responsive_control(
			'jkit_glass_blur_level',
			array(
				'label'     => esc_html__( 'Blur', 'jeg-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-element-populated' => '-webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}); backdrop-filter: blur({{SIZE}}{{UNIT}});',
				),
			)
		);

		$column->end_controls_section();
	}

	/**
	 * Add custom option to elementor sections
	 *
	 * @param \Elementor\Element_Base $section The edited element.
	 * @param array                   $args The args that sent to $element->start_controls_section.
	 */
	public function add_section_options( $section, $args ) {
		$section->start_controls_section(
			'jkit_glass_blur_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Glass Blur Effect', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$section->add_responsive_control(
			'jkit_glass_blur_level',
			array(
				'label'     => esc_html__( 'Blur', 'jeg-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '-webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}); backdrop-filter: blur({{SIZE}}{{UNIT}});',
				),
			)
		);

		$section->end_controls_section();
	}
}
