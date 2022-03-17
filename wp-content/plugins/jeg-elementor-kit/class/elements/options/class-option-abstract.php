<?php
/**
 * Elements Option Abstract Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.1.0
 */

namespace Jeg\Elementor_Kit\Elements\Options;

use Jeg\Element\Elements\Elements_Option_Abstract;

/**
 * Class Option_Abstract
 *
 * @package Jeg\Elementor_Kit\Elements\Options
 */
class Option_Abstract extends Elements_Option_Abstract {
	/**
	 * Dependency
	 *
	 * @var int
	 */
	protected $dependency = array(
		'field'    => 'sort_by',
		'operator' => 'in',
		'value'    => array(
			'latest',
			'oldest',
			'alphabet_asc',
			'alphabet_desc',
			'random',
			'random_week',
			'random_month',
			'most_comment',
		),
	);

	/**
	 * Show color scheme flag for element.
	 *
	 * @return bool
	 */
	public function show_color_scheme() {
		return false;
	}

	/**
	 * Compatibility column
	 *
	 * @return array
	 */
	public function compatible_column() {
		return array();
	}

	/**
	 * Override function to avoid elements load on another page builder
	 */
	public function map_vc() {
	}

	/**
	 * Override function to remove compatible column alert
	 */
	public function set_compatible_column_option() {
	}

	/**
	 * Element name
	 *
	 * @return string
	 */
	public function get_element_name() {
		return esc_html__( 'JKit - Elements', 'jeg-elementor-kit' );
	}

	/**
	 * Element category
	 *
	 * @return string
	 */
	public function get_category() {
		return esc_html__( 'Jeg Elementor Kit', 'jeg-elementor-kit' );
	}

	/**
	 * Element options
	 */
	public function set_options() {
		unset( $this->options['column_width'] );
	}

	/**
	 * Option segments
	 */
	public function set_segments() {
		$this->set_style_segment();
	}

	/**
	 * Set CSS Box Option
	 */
	public function set_style_segment() {
		$this->segments['style_css'] = array(
			'name'      => esc_html__( 'CSS Box', 'jeg-elementor-kit' ),
			'priority'  => 50,
			'kit_style' => true,
		);
	}

	/**
	 * Set No Content Style Option
	 */
	public function set_nocontent_style_segment() {
		$this->segments['style_nocontent'] = array(
			'name'      => esc_html__( 'No Content', 'jeg-elementor-kit' ),
			'priority'  => 49,
			'kit_style' => true,
		);
	}

	/**
	 * Set Pagination Option
	 */
	public function set_pagination_segment() {
		$this->segments['segment_pagination'] = array(
			'name'     => esc_html__( 'Pagination', 'jeg-elementor-kit' ),
			'priority' => 50,
		);
	}

	/**
	 * Add Additional Style.
	 */
	public function additional_style() {
		$this->options['st_css_custom'] = array(
			'type'    => 'css_editor',
			'title'   => esc_html__( 'Custom CSS', 'jeg-elementor-kit' ),
			'segment' => 'style_css',
		);
	}

	/**
	 * Pagination option
	 *
	 * @param int $number Number post.
	 */
	public function pagination_option( $number ) {
		$this->options['pagination_alert'] = array(
			'type'        => 'alert',
			'title'       => esc_html__( 'Pagination Alert', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Pagination doesn\'t work if content is sorted by Jetpack Popular Post.', 'jeg-elementor-kit' ),
			'segment'     => 'segment_pagination',
			'dependency'  => array(
				array(
					'field'    => 'sort_by',
					'operator' => 'in',
					'value'    => array(
						'popular_post_jetpack_day',
						'popular_post_jetpack_week',
						'popular_post_jetpack_month',
						'popular_post_jetpack_all',
					),
				),
			),
		);

		$this->options['pagination_mode'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Pagination Mode', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Choose pagination that you want to use for this block.', 'jeg-elementor-kit' ),
			'segment'     => 'segment_pagination',
			'default'     => 'disable',
			'options'     => array(
				'disable'    => esc_html__( 'No Pagination', 'jeg-elementor-kit' ),
				'loadmore'   => esc_html__( 'Load More', 'jeg-elementor-kit' ),
				'scrollload' => esc_html__( 'Auto Load on Scroll', 'jeg-elementor-kit' ),
			),
			'dependency'  => array(
				$this->dependency,
			),
		);

		$this->options['pagination_loadmore_text'] = array(
			'type'       => 'text',
			'title'      => esc_html__( 'Load More Text', 'jeg-elementor-kit' ),
			'segment'    => 'segment_pagination',
			'default'    => 'Load More',
			'dependency' => array(
				$this->dependency,
				array(
					'field'    => 'pagination_mode',
					'operator' => 'in',
					'value'    => array( 'loadmore', 'scrollload' ),
				),
			),
		);

		$this->options['pagination_loading_text'] = array(
			'type'       => 'text',
			'title'      => esc_html__( 'Loading Text', 'jeg-elementor-kit' ),
			'segment'    => 'segment_pagination',
			'default'    => 'Loading...',
			'dependency' => array(
				$this->dependency,
				array(
					'field'    => 'pagination_mode',
					'operator' => 'in',
					'value'    => array( 'loadmore', 'scrollload' ),
				),
			),
		);

		$this->options['pagination_number_post'] = array(
			'type'        => 'slider',
			'title'       => esc_html__( 'Pagination Post', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Number of posts loaded for ajax pagination.', 'jeg-elementor-kit' ),
			'segment'     => 'segment_pagination',
			'options'     => array(
				'min'  => 1,
				'max'  => 30,
				'step' => 1,
			),
			'default'     => $number,
			'dependency'  => array(
				$this->dependency,
				array(
					'field'    => 'pagination_mode',
					'operator' => 'in',
					'value'    => array( 'loadmore', 'scrollload' ),
				),
			),
		);

		$this->options['pagination_scroll_limit'] = array(
			'type'        => 'number',
			'title'       => esc_html__( 'Auto Load Limit', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Limit of auto load when scrolling, set to zero to always load until end of content.', 'jeg-elementor-kit' ),
			'segment'     => 'segment_pagination',
			'options'     => array(
				'min'  => 0,
				'max'  => 9999,
				'step' => 1,
			),
			'default'     => 0,
			'dependency'  => array(
				$this->dependency,
				array(
					'field'    => 'pagination_mode',
					'operator' => '==',
					'value'    => array( 'scrollload' ),
				),
			),
		);

		$this->options['pagination_icon'] = array(
			'type'       => 'iconpicker',
			'title'      => esc_html__( 'Button Icon', 'jeg-elementor-kit' ),
			'segment'    => 'segment_pagination',
			'dependency' => array(
				$this->dependency,
				array(
					'field'    => 'pagination_mode',
					'operator' => '==',
					'value'    => array( 'loadmore', 'scrollload' ),
				),
			),
		);

		$this->options['pagination_icon_position'] = array(
			'type'       => 'select',
			'title'      => esc_html__( 'Icon Position', 'jeg-elementor-kit' ),
			'default'    => 'before',
			'segment'    => 'segment_pagination',
			'options'    => array(
				'before' => esc_html__( 'Before', 'jeg-elementor-kit' ),
				'after'  => esc_html__( 'After', 'jeg-elementor-kit' ),
			),
			'dependency' => array(
				$this->dependency,
				array(
					'field'    => 'pagination_mode',
					'operator' => '==',
					'value'    => array( 'loadmore', 'scrollload' ),
				),
			),
		);
	}

	/**
	 * No Content Style Options
	 */
	public function nocontent_style() {
		$this->options['st_nocontent_text'] = array(
			'type'        => 'text',
			'title'       => esc_html__( 'Text', 'jeg-elementor-kit' ),
			'default'     => esc_html__( 'No Content Available', 'jeg-elementor-kit' ),
			'segment'     => 'style_nocontent',
			'label_block' => false,
		);

		$this->options['st_nocontent_alignment'] = array(
			'type'       => 'radio',
			'title'      => esc_html__( 'Alignment', 'jeg-elementor-kit' ),
			'segment'    => 'style_nocontent',
			'options'    => array(
				'left'   => array(
					'title' => esc_html__( 'Left', 'jeg-elementor-kit' ),
					'icon'  => 'fas fa-align-left',
				),
				'center' => array(
					'title' => esc_html__( 'Center', 'jeg-elementor-kit' ),
					'icon'  => 'fas fa-align-center',
				),
				'right'  => array(
					'title' => esc_html__( 'Right', 'jeg-elementor-kit' ),
					'icon'  => 'fas fa-align-right',
				),
			),
			'responsive' => true,
			'default'    => 'left',
			'selectors'  => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
			'attribute'  => 'text-align',
		);

		$this->options['st_nocontent_typography'] = array(
			'type'      => 'typography',
			'title'     => esc_html__( 'Typography', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
		);

		$this->options['st_nocontent_color'] = array(
			'type'       => 'color',
			'title'      => esc_html__( 'Color', 'jeg-elementor-kit' ),
			'segment'    => 'style_nocontent',
			'responsive' => true,
			'selectors'  => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
		);

		$this->options['st_nocontent_background'] = array(
			'type'      => 'background',
			'title'     => esc_html__( 'Background', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
			'options'   => array(
				'classic',
				'gradient',
			),
		);

		$this->options['st_nocontent_border'] = array(
			'type'      => 'border',
			'title'     => esc_html__( 'Border', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
		);

		$this->options['st_nocontent_boxshadow'] = array(
			'type'      => 'boxshadow',
			'title'     => esc_html__( 'Box Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
		);

		$this->options['st_nocontent_textshadow'] = array(
			'type'      => 'textshadow',
			'title'     => esc_html__( 'Text Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
		);

		$this->options['st_nocontent_padding'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Padding', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'units'     => array( 'px', '%', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
			'attribute' => 'padding',
		);

		$this->options['st_nocontent_margin'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Margin', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'units'     => array( 'px', '%', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
			'attribute' => 'margin',
		);

		$this->options['st_nocontent_border_radius'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Border Radius', 'jeg-elementor-kit' ),
			'segment'   => 'style_nocontent',
			'units'     => array( 'px', '%', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-postblock .jeg_empty_module',
			'attribute' => 'border-radius',
		);
	}

	/**
	 * Set content filter option
	 *
	 * @param int  $number           Default number of element.
	 * @param bool $hide_number_post Hide number of post flag if we don't allow user to change number directly.
	 */
	public function set_content_filter_option( $number = 10, $hide_number_post = false ) {
		$this->options['content_selection'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Content Selection', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Choose content selection.', 'jeg-elementor-kit' ),
			'segment'     => 'content-filter',
			'default'     => 'selection',
			'options'     => array(
				'selection' => esc_html__( 'Selection', 'jeg-elementor-kit' ),
				'related'   => esc_html__( 'Related', 'jeg-elementor-kit' ),
			),
			'dependency'  => array(
				$this->dependency,
			),
		);

		$this->options['related_filter'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Post Related Filter', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Choose which filter that you want to use as related post filter.', 'jeg-elementor-kit' ),
			'default'     => 'none',
			'segment'     => 'content-filter',
			'options'     => array(
				'none'     => esc_html__( 'None (Only Post Type)', 'jeg-elementor-kit' ),
				'category' => esc_html__( 'Category', 'jeg-elementor-kit' ),
				'tag'      => esc_html__( 'Post Tag', 'jeg-elementor-kit' ),
				'both'     => esc_html__( 'Both', 'jeg-elementor-kit' ),
			),
			'dependency'  => array(
				$this->dependency,
				array(
					'field'    => 'content_selection',
					'operator' => '==',
					'value'    => 'related',
				),
			),
		);

		parent::set_content_filter_option( $number, $hide_number_post );

		$this->options['post_type'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Include Post Type', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Choose post type for this content.', 'jeg-elementor-kit' ),
			'segment'     => 'content-filter',
			'default'     => 'post',
			'options'     => 'jeg_get_enable_post_type',
			'dependency'  => array(
				$this->dependency,
				array(
					'field'    => 'content_selection',
					'operator' => '==',
					'value'    => 'selection',
				),
			),
		);

		$this->options['include_post'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_post',
			'options'     => 'jeg_get_post_option',
			'nonce'       => wp_create_nonce( 'jeg_find_post' ),
			'title'       => esc_html__( 'Include Post ID', 'jeg-elementor-kit' ),
			'description' => wp_kses( __( 'Tips :<br/> - You can search post id by inputing title, clicking search title, and you will have your post id.<br/>- You can also directly insert your post id, and click enter to add it on the list.', 'jeg-elementor-kit' ), wp_kses_allowed_html() ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				$this->dependency,
				array(
					'field'    => 'content_selection',
					'operator' => '==',
					'value'    => 'selection',
				),
			),
		);

		$this->options['include_category'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_category',
			'options'     => 'jeg_get_category_option',
			'nonce'       => wp_create_nonce( 'jeg_find_category' ),
			'title'       => esc_html__( 'Include Category', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Choose which category you want to show on this module.', 'jeg-elementor-kit' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				$this->dependency,
				array(
					'field'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
				array(
					'field'    => 'content_selection',
					'operator' => '==',
					'value'    => 'selection',
				),
			),
		);

		$this->options['exclude_category'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_category',
			'options'     => 'jeg_get_category_option',
			'nonce'       => wp_create_nonce( 'jeg_find_category' ),
			'title'       => esc_html__( 'Exclude Category', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Choose excluded category for this module.', 'jeg-elementor-kit' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				'custom' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'sort_by',
									'operator' => 'in',
									'value'    => array(
										'latest',
										'oldest',
										'alphabet_asc',
										'alphabet_desc',
										'random',
										'random_week',
										'random_month',
										'most_comment',
									),
								),
								array(
									'name'     => 'post_type',
									'operator' => '==',
									'value'    => 'post',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'sort_by',
									'operator' => 'in',
									'value'    => array(
										'latest',
										'oldest',
										'alphabet_asc',
										'alphabet_desc',
										'random',
										'random_week',
										'random_month',
										'most_comment',
									),
								),
								array(
									'name'     => 'content_selection',
									'operator' => '==',
									'value'    => 'related',
								),
							),
						),
					),
				),
			),
		);

		$this->options['include_tag'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_tag',
			'options'     => 'jeg_get_tag_option',
			'nonce'       => wp_create_nonce( 'jeg_find_tag' ),
			'title'       => esc_html__( 'Include Tags', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Write to search post tag.', 'jeg-elementor-kit' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				$this->dependency,
				array(
					'field'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
				array(
					'field'    => 'content_selection',
					'operator' => '==',
					'value'    => 'selection',
				),
			),
		);

		$this->options['exclude_tag'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_tag',
			'options'     => 'jeg_get_tag_option',
			'nonce'       => wp_create_nonce( 'jeg_find_tag' ),
			'title'       => esc_html__( 'Exclude Tags', 'jeg-elementor-kit' ),
			'description' => esc_html__( 'Write to search post tag.', 'jeg-elementor-kit' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				'custom' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'sort_by',
									'operator' => 'in',
									'value'    => array(
										'latest',
										'oldest',
										'alphabet_asc',
										'alphabet_desc',
										'random',
										'random_week',
										'random_month',
										'most_comment',
									),
								),
								array(
									'name'     => 'post_type',
									'operator' => '==',
									'value'    => 'post',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'sort_by',
									'operator' => 'in',
									'value'    => array(
										'latest',
										'oldest',
										'alphabet_asc',
										'alphabet_desc',
										'random',
										'random_week',
										'random_month',
										'most_comment',
									),
								),
								array(
									'name'     => 'content_selection',
									'operator' => '==',
									'value'    => 'related',
								),
							),
						),
					),
				),
			),
		);
	}

	/**
	 * Custom Taxonomy option
	 */
	public function set_taxonomy_option() {
		$taxonomies = jeg_get_enabled_custom_taxonomy();

		foreach ( $taxonomies as $key => $value ) {
			$this->options[ $key ] = array(
				'type'        => 'select',
				'multiple'    => PHP_INT_MAX,
				'title'       => $value['name'],
				/* translators: %s: taxonomy name */
				'description' => sprintf( esc_html__( 'Write to search the %s that you want to include as filter.', 'jeg-elementor-kit' ), strtolower( $value['name'] ) ),
				'ajax'        => 'jeg_find_custom_term',
				'nonce'       => wp_create_nonce( 'jeg_find_custom_term' ),
				'options'     => 'jeg_get_custom_term_option',
				'ajaxoptions' => 'jeg_get_custom_term_option',
				'slug'        => $key,
				'segment'     => 'content-filter',
				'dependency'  => array(
					array(
						'field'    => 'post_type',
						'operator' => '==',
						'value'    => $value['post_types'],
					),
					array(
						'field'    => 'content_selection',
						'operator' => '==',
						'value'    => 'selection',
					),
				),
			);
		}
	}
}
