<?php
/**
 * Elements View Abstract Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.1.0
 */

namespace Jeg\Elementor_Kit\Elements\Views;

use Jeg\Element\Elements\Elements_View_Abstract;
use Elementor\Icons_Manager;

/**
 * Class View_Abstract
 *
 * @package Jeg\Elementor_Kit\Elements\Views
 */
class View_Abstract extends Elements_View_Abstract {
	/**
	 * Render element for frontend site
	 *
	 * @param array  $attr Options.
	 * @param string $column_class Column class.
	 *
	 * @return mixed
	 */
	public function render_frontend( $attr, $column_class ) {
		$this->set_attribute( $attr );
		return $this->build_content( $attr );
	}

	/**
	 * Render element for backend editor
	 *
	 * @param array  $attr Options.
	 * @param string $column_class Column class.
	 *
	 * @return mixed
	 */
	public function render_backend( $attr, $column_class ) {
		$this->set_attribute( $attr );
		return $this->build_content( $attr );
	}

	/**
	 * Build block content
	 * Extended by each element
	 */
	public function build_content() {
		return null;
	}

	/**
	 * Render element
	 *
	 * @param array  $attr Options.
	 * @param string $column_class Column class.
	 */
	public function render_element( $attr, $column_class ) {
		$this->set_attribute( $attr );
		return $this->build_content( $attr );
	}

	/**
	 * Render result element
	 *
	 * @param array $result Result element.
	 * @param array $attr Options.
	 */
	public function render_result_element( $result, $attr ) {
		$this->set_attribute( $attr );
		return $this->build_content( $attr );
	}

	/**
	 * Build URL element from given setting
	 *
	 * @param array  $attr URL options.
	 * @param string $additional_id ID.
	 * @param string $additional_class Class.
	 * @param string $child_element Element wrapped by URL.
	 * @param string $data_attribute Data attribute.
	 *
	 * @return string
	 */
	protected function render_url_element( $attr, $additional_id = null, $additional_class = null, $child_element = null, $data_attribute = null ) {
		$id                = ! empty( $additional_id ) ? ' id="' . $additional_id . '"' : '';
		$class             = ! empty( $additional_class ) ? ' class="' . $additional_class . '"' : '';
		$target            = 'on' === $attr['is_external'] ? ' target="_blank" ' : '';
		$nofollow          = 'on' === $attr['nofollow'] ? ' rel="nofollow" ' : '';
		$custom_attributes = '';

		foreach ( explode( ',', $attr['custom_attributes'] ) as $attribute ) {
			if ( $attribute ) {
				$value             = explode( '|', $attribute );
				$custom_attributes = $custom_attributes . ' ' . $value[0] . '="' . $value[1] . '" ';
			}
		}

		return '<a href="' . esc_url( $attr['url'] ) . '" ' . $id . $class . $target . $nofollow . $custom_attributes . $data_attribute . '>' . $child_element . '</a>';
	}

	/**
	 * Build image element from given setting
	 *
	 * @param array  $attr Image options.
	 * @param string $image_size Image size.
	 * @param string $additional_id ID.
	 * @param string $additional_class Class.
	 * @param string $additional_alt Alt.
	 *
	 * @return string
	 */
	protected function render_image_element( $attr, $image_size = 'thumbnail', $additional_id = null, $additional_class = null, $additional_alt = null ) {
		$id         = ! empty( $additional_id ) ? ' id="' . $additional_id . '"' : '';
		$class      = ! empty( $additional_class ) ? ' class="' . $additional_class . '"' : '';
		$alt        = ! empty( $additional_alt ) ? ' alt="' . $additional_alt . '"' : '';
		$attachment = ! empty( $attr['id'] ) ? wp_get_attachment_image_src( $attr['id'], $image_size ) : '';
		$image      = ! empty( $attachment[0] ) ? '<img src="' . esc_url( $attachment[0] ) . '" ' . $id . $class . $alt . '>' : '';
		$image      = ! empty( $attr['url'] ) && empty( $image ) ? '<img src="' . esc_url( $attr['url'] ) . '" ' . $id . $class . $alt . '>' : $image;

		return $image;
	}

	/**
	 * Build icon element from given setting. Use Elementor Icon Manager. Render as string
	 *
	 * @param array $icon Icon options from the setting.
	 * @param array $attr Additonal attribute. Default: aria-hidden true.
	 *
	 * @return string
	 */
	protected function render_icon_element( $icon, $attr = array() ) {
		$attr = array_merge( $attr, array( 'aria-hidden' => 'true' ) );

		ob_start();
		Icons_Manager::render_icon( $icon, $attr );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Build element with wrapper
	 *
	 * @param string $element_name Element name.
	 * @param string $inner Inner element.
	 * @param array  $array_classes Classes.
	 * @param array  $array_data Data attribute.
	 * @param array  $id Element ID.
	 *
	 * @return string
	 */
	protected function render_wrapper( $element_name, $inner, $array_classes = array(), $array_data = array(), $id = null ) {
		$classes = '';
		$data    = '';

		foreach ( $array_classes as $class ) {
			$classes = $classes . ' ' . $class;
		}

		foreach ( $array_data as $key => $value ) {
			$data = $data . ' data-' . $key . '="' . $value . '"';
		}

		if ( $id ) {
			$id = 'id="' . $id . '"';
		}

		$classes = 'jeg-elementor-kit jkit-' . $element_name . $classes . ' ' . $this->unique_id;

		return '<div ' . $id . ' class="' . $classes . '" ' . $data . '>' . $inner . '</div>';
	}

	/**
	 * Get post date
	 *
	 * @param int|\WP_Post $post Post.
	 * @param string       $format Format.
	 * @param string       $type Type.
	 * @param string       $custom Custom format.
	 *
	 * @return false|string
	 */
	public function get_post_date( $post, $format, $type, $custom ) {
		if ( 'ago' === $format ) {
			$output = jkit_get_post_ago_time( $type, $post );
		} elseif ( 'custom' === $format ) {
			$output = jkit_get_post_date( $custom, $post, $type );
		} else {
			$output = jkit_get_post_date( null, $post, $type );
		}

		return $output;
	}

	/**
	 * Get color scheme
	 *
	 * @return mixed
	 */
	public function color_scheme() {
		return isset( $this->attribute['scheme'] ) ? $this->attribute['scheme'] : '';
	}

	/**
	 * Return empty content element
	 *
	 * @return mixed
	 */
	public function empty_content() {
		$no_content = '<div class="jeg_empty_module">' . esc_attr( $this->attribute['st_nocontent_text'] ) . '</div>';

		return apply_filters( 'jeg_module_no_content', $no_content );
	}

	/**
	 * Get post thumbnail
	 *
	 * @param  int    $post_id Post ID.
	 * @param  string $size    Image size.
	 * @return mixed
	 */
	public function get_thumbnail( $post_id, $size ) {
		$additional_class = '';

		if ( ! has_post_thumbnail( $post_id ) ) {
			$additional_class = 'no_thumbnail';
		}

		$thumbnail =
		'<div class="thumbnail-container ' . $additional_class . '">
            ' . get_the_post_thumbnail( $post_id, $size ) . '
        </div>';

		return $thumbnail;
	}

	/**
	 * Render result block element
	 *
	 * @param array $results Results.
	 */
	public function render_result_block_element( $results ) {
		$pagination       = $this->render_pagination( true, intval( $this->attribute['max_num_pages'] ) );
		$pagination_align = isset( $this->attribute['pagination_align'] ) ? esc_attr( $this->attribute['pagination_align'] ) : '';

		if ( ! empty( $results ) ) {
			$content = $this->build_column( $results );
		} else {
			$content = $this->empty_content();
		}

		return '<div class="jkit-block-container">' . apply_filters( 'jkit_module_block_container_extend', $content, $this->attribute ) . '</div>
		<div class="jkit-block-pagination jkit-align' . $pagination_align . '">' . apply_filters( 'jkit_module_block_pagination_extend', $pagination, $this->attribute ) . '</div>';
	}

	/**
	 * Render pagination
	 *
	 * @param  bool $next Next.
	 * @param  int  $total Total page.
	 * @return string
	 */
	protected function render_pagination( $next = false, $total = 1 ) {
		$output           = '';
		$icon             = $this->render_icon_element( $this->attribute['pagination_icon'] );
		$icon_position    = esc_attr( $this->attribute['pagination_icon_position'] );
		$pagination_align = isset( $this->attribute['pagination_align'] ) ? esc_attr( $this->attribute['pagination_align'] ) : '';

		if ( in_array( $this->attribute['pagination_mode'], array( 'loadmore', 'scrollload' ), true ) && $next ) {
			$output = '<a href="#" data-load="' . esc_attr( $this->attribute['pagination_loadmore_text'] ) . '" data-loading="' . esc_attr( $this->attribute['pagination_loading_text'] ) . '"> ' . esc_attr( $this->attribute['pagination_loadmore_text'] ) . '</a>';

			if ( ! empty( $icon ) ) {
				if ( 'before' === $icon_position ) {
					$output = $icon . $output;
				} else {
					$output = $output . $icon;
				}
			}

			$output = '<div class="jkit-block-loadmore icon-position-' . $icon_position . '">' . $output . '</div>';
			$output = '<div class="jkit-block-pagination jkit-align' . $pagination_align . '">' . apply_filters( 'jkit_module_block_pagination_extend', $output, $this->attribute ) . '</div>';
		}

		return $output;
	}

	/**
	 * Get settings attribute
	 *
	 * @return string
	 */
	protected function render_settings() {
		if ( $this->unique_id ) {
			$keys = $this->get_ajax_param();

			$attr = array_filter(
				$this->attribute,
				function ( $key ) use ( $keys ) {
					return in_array( $key, $keys, true );
				},
				ARRAY_FILTER_USE_KEY
			);

			$attr['paged'] = 1;
			$attr['class'] = $this->id;

			return htmlspecialchars( wp_json_encode( $attr ), ENT_QUOTES, 'UTF-8' );
		}
	}

	/**
	 * Build query for ajax request
	 *
	 * @param  array $attr Array of attribute.
	 * @return mixed
	 */
	public function build_ajax_query( $attr ) {
		$args                = $attr['attr'];
		$args['paged']       = $attr['current_page'];
		$args['number_post'] = $attr['attr']['number_post'];

		if ( is_array( $attr['attr']['pagination_number_post'] ) ) {
			$args['pagination_number_post'] = $attr['attr']['pagination_number_post']['size'];
		} else {
			$args['pagination_number_post'] = $attr['attr']['pagination_number_post'];
		}

		return $args;
	}

	/**
	 * Ajax request handler. Override in element view class.
	 */
	public function ajax_request() {
	}

	/**
	 * Render column method
	 *
	 * @param  array $result Result.
	 * @return mixed|string
	 */
	public function render_column( $result ) {
		return '<div class="jkit-posts jkit-ajax-flag">
            ' . $this->build_column( $result ) . '
        </div>';
	}

	/**
	 * Render alt column method (ajax request)
	 *
	 * @param  array $result Result.
	 * @return mixed|string
	 */
	public function render_column_alt( $result ) {
		return $this->build_column( $result );
	}

	/**
	 * Get Current Page
	 *
	 * @return mixed
	 */
	public function get_current_page() {
		$page  = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		return max( $page, $paged );
	}

	/**
	 * Render block element
	 *
	 * @return mixed
	 */
	public function render_block_element() {
		if ( isset( $this->attribute['results'] ) ) {
			$results = $this->attribute['results'];
		} else {
			$results = $this->build_query( $this->attribute );
		}

		if ( ! empty( $results['result'] ) ) {
			$content = $this->render_column( $results['result'] );
		} else {
			$content = $this->empty_content();
		}

		$pagination = $this->render_pagination( $results['next'], $results['total_page'] );

		return '<div class="jkit-block-container">
            ' . apply_filters( 'jkit_module_block_container_extend', $content, $this->attribute ) . '
        </div>' . $pagination;
	}

	/**
	 * Filter attribute for post query
	 *
	 * @param array $attr Attributes.
	 *
	 * @return array
	 */
	public function filter_post_attribute( $attr ) {
		if ( isset( $attr['content_selection'] ) && 'related' === $attr['content_selection'] ) {
			$post_id   = get_the_ID();
			$post_type = get_post_type( $post_id );
			$category  = $post_tag  = array();

			if ( in_array( $attr['related_filter'], array( 'category', 'both' ), true ) ) {
				$cats = get_the_category( $post_id );

				if ( $cats ) {
					foreach ( $cats as $cat ) {
						$category[] = $cat->term_id;
					}
				}
			}

			if ( in_array( $attr['related_filter'], array( 'tag', 'both' ), true ) ) {
				$tags = get_the_tags( $post_id );

				if ( $tags ) {
					foreach ( $tags as $tag ) {
						$post_tag[] = $tag->term_id;
					}
				}
			}

			if ( ! empty( $attr['exclude_post'] ) ) {
				$attr['exclude_post'] .= ',' . $post_id;
			} else {
				$attr['exclude_post'] = $post_id;
			}

			if ( ! empty( $post_type ) ) {
				$attr['post_type'] = $post_type;
			} else {
				$attr['post_type'] = array();
			}

			$attr['include_tag']      = implode( ',', $post_tag );
			$attr['include_category'] = implode( ',', $category );

			add_filter(
				'jeg_default_query_args',
				function ( $args, $filtered_attr ) use ( $attr ) {
					if ( isset( $attr['content_selection'] ) && 'related' === $attr['content_selection'] ) {
						if ( in_array( $attr['related_filter'], array( 'tag', 'both' ), true ) ) {
							if ( empty( $attr['include_tag'] ) ) {
								$args['tag__in'] = array( 0 );
							}
						}

						if ( in_array( $attr['related_filter'], array( 'category', 'both' ), true ) ) {
							if ( empty( $attr['include_category'] ) ) {
								$args['category__in'] = array( 0 );
							}
						}
					}

					return $args;
				},
				10,
				2
			);
		}

		return $attr;
	}
}
