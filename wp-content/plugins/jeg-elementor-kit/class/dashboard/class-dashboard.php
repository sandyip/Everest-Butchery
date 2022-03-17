<?php
/**
 * Jeg Elementor Kit Class
 *
 * @package jeg-elementor-kit
 *
 * @author Jegstudio
 *
 * @since 2.0.0
 */

namespace Jeg\Elementor_Kit\Dashboard;

use Jeg\Elementor_Kit\Dashboard\Template\Header_Dashboard_Template;
use Jeg\Elementor_Kit\Dashboard\Template\Footer_Dashboard_Template;

/**
 * Class Dashboard
 *
 * @package Jeg\Elementor_Kit
 */
class Dashboard {
	/**
	 * Slug for accessing JKit Dashboard
	 *
	 * @var string
	 */
	public static $slug = 'jkit-dashboard';

	/**
	 * Slug for accessing JKit Header Post Type
	 *
	 * @var string
	 */
	public static $jkit_header = 'jkit-header';

	/**
	 * Slug for accessing JKit Footer Post Type
	 *
	 * @var string
	 */
	public static $jkit_footer = 'jkit-footer';

	/**
	 * Slug for meta condition
	 *
	 * @var string
	 */
	public static $jkit_condition = 'jkit-condition';

	/**
	 * Ajax endpoint
	 *
	 * @var string
	 */
	private $endpoint = 'jkit-ajax-request';

	/**
	 * Template slug
	 *
	 * @var string
	 */
	private $template_slug = 'templates/dashboard/dashboard';

	/**
	 * Class instance
	 *
	 * @var Element
	 */
	private static $instance;

	/**
	 * Module constructor.
	 */
	public function __construct() {
		$this->setup_init();
		$this->setup_hook();
	}

	/**
	 * Setup Classes
	 */
	private function setup_init() {
	}

	/**
	 * Setup Hooks
	 */
	private function setup_hook() {
		add_action( 'init', array( $this, 'post_type' ), 9 );
		add_action( 'admin_init', array( $this, 'init_dashboard' ) );

		add_action( 'admin_menu', array( $this, 'parent_menu' ) );
		add_action( 'admin_menu', array( $this, 'child_menu' ) );

		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'admin_footer', array( $this, 'print_script_template' ) );
	}

	/**
	 * Get class instance
	 *
	 * @return Dashboard
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize Dashboard
	 */
	public function init_dashboard() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_style' ) );
		}

		if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array( self::$slug, 'jkit-user-data', 'jkit-elements' ), true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_asset' ) );
		}
	}

	/**
	 * Load style
	 */
	public function load_style() {
		wp_enqueue_style( 'jkit-dashboard', JEG_ELEMENTOR_KIT_URL . '/assets/css/admin/dashboard.css', null, JEG_ELEMENTOR_KIT_VERSION );
	}

	/**
	 * Load scripts
	 */
	public function load_asset() {
		wp_register_script( 'jkit-dashboard', JEG_ELEMENTOR_KIT_URL . '/assets/js/dashboard/dashboard.js', array( 'underscore', 'jquery', 'jquery-ui-draggable', 'jquery-ui-sortable' ), JEG_ELEMENTOR_KIT_VERSION, true );
		wp_add_inline_script( 'jkit-dashboard', $this->ajax_url() );
		wp_localize_script( 'jkit-dashboard', 'jkit_dashboard_localize', $this->localize_array() );
		wp_enqueue_script( 'jkit-dashboard' );

		wp_enqueue_style( 'notiflix', JEG_ELEMENTOR_KIT_URL . '/assets/js/notiflix/notiflix.min.css', null, '2.7.0' );
		wp_enqueue_script( 'notiflix', JEG_ELEMENTOR_KIT_URL . '/assets/js/notiflix/notiflix.min.js', array(), '2.7.0', true );
	}

	/**
	 * Type List
	 *
	 * @return array
	 */
	public static function post_type_list() {
		return array(
			self::$jkit_header,
			self::$jkit_footer,
		);
	}

	/**
	 * Post Type
	 */
	public function post_type() {
		foreach ( self::post_type_list() as $list ) {
			register_post_type(
				$list,
				array(
					'public'          => true,
					'show_ui'         => false,
					'capability_type' => 'post',
					'hierarchical'    => false,
					'supports'        => array( 'title', 'revisions', 'page-attributes', 'elementor' ),
					'map_meta_cap'    => true,
					'rewrite'         => array(
						'slug'       => $list,
						'with_front' => false,
					),
				)
			);
		}
	}

	/**
	 * Admin Menu
	 *
	 * @return array
	 */
	public function get_admin_menu() {
		$menu[] = array(
			'title'        => esc_html__( 'User Data', 'jeg-elementor-kit' ),
			'menu'         => esc_html__( 'User Data', 'jeg-elementor-kit' ),
			'slug'         => self::$slug,
			'action'       => array( &$this, 'user_data' ),
			'priority'     => 57,
			'show_on_menu' => true,
		);

		$menu[] = array(
			'title'        => esc_html__( 'Elements', 'jeg-elementor-kit' ),
			'menu'         => esc_html__( 'Elements', 'jeg-elementor-kit' ),
			'slug'         => 'jkit-elements',
			'action'       => array( &$this, 'elements' ),
			'priority'     => 58,
			'show_on_menu' => true,
		);

		$menu[] = array(
			'title'        => esc_html__( 'Header Template', 'jeg-elementor-kit' ),
			'menu'         => esc_html__( 'Header Template', 'jeg-elementor-kit' ),
			'slug'         => self::$jkit_header,
			'action'       => array( &$this, 'header_template' ),
			'priority'     => 59,
			'show_on_menu' => true,
		);

		$menu[] = array(
			'title'        => esc_html__( 'Footer Template', 'jeg-elementor-kit' ),
			'menu'         => esc_html__( 'Footer Template', 'jeg-elementor-kit' ),
			'slug'         => self::$jkit_footer,
			'action'       => array( &$this, 'footer_template' ),
			'priority'     => 60,
			'show_on_menu' => true,
		);

		return apply_filters( 'jkit_admin_menu', $menu );
	}

	/**
	 * Parent Menu
	 *
	 * @return void
	 */
	public function parent_menu() {
		$args = array(
			'page_title' => esc_html__( 'Jeg Elementor Kit', 'jeg-elementor-kit' ),
			'menu_title' => esc_html__( 'Jeg Elementor Kit', 'jeg-elementor-kit' ),
			'capability' => 'edit_theme_options',
			'menu_slug'  => self::$slug,
			'function'   => null,
			'icon_url'   => JEG_ELEMENTOR_KIT_URL . '/assets/img/admin/icon.svg',
			'position'   => 76,
		);

		$args = apply_filters( 'jkit_parent_menu', $args );

		add_menu_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'], $args['icon_url'], $args['position'] );
	}

	/**
	 * Child Menu
	 */
	public function child_menu() {
		$self  = $this;
		$menus = $this->get_admin_menu();

		foreach ( $menus as $menu ) {
			if ( $menu['show_on_menu'] ) {
				if ( $menu['action'] ) {
					add_submenu_page(
						self::$slug,
						$menu['title'],
						$menu['menu'],
						'edit_theme_options',
						$menu['slug'],
						function () use ( $self, $menu ) {
							$self->render_header();
							call_user_func( $menu['action'] );
						}
					);
				} else {
					add_submenu_page(
						self::$slug,
						$menu['title'],
						$menu['menu'],
						'edit_theme_options',
						$menu['slug']
					);
				}
			}
		}
	}

	/**
	 * Header Dashboard
	 */
	public function render_header() {
		jkit_get_template_part( $this->template_slug, 'header' );
	}

	/**
	 * Dashboard
	 */
	public function dashboard_content() {
		jkit_get_template_part( $this->template_slug, 'content' );
	}

	/**
	 * User Data
	 */
	public function user_data() {
		jkit_get_template_part( $this->template_slug, 'user-data' );
	}

	/**
	 * Header
	 */
	public function header_template() {
		new Header_Dashboard_Template();
	}

	/**
	 * Footer
	 */
	public function footer_template() {
		new Footer_Dashboard_Template();
	}

	/**
	 * Elements
	 */
	public function elements() {
		jkit_get_template_part( $this->template_slug, 'elements' );
	}

	/**
	 * Add ajax URL
	 */
	public function ajax_url() {
		if ( is_admin() ) {
			$ajax_url = add_query_arg( array( $this->endpoint => 'jkit_user_data' ), esc_url( home_url( '/' ) ) );

			return 'var jkit_ajax_url = "' . esc_url( $ajax_url ) . '", jkit_nonce = "' . jkit_create_global_nonce() . '";';
		}

		return null;
	}

	/**
	 * Get URL to Elementor Builder
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string
	 */
	public static function editor_url( $post_id ) {
		$the_id = ( strlen( $post_id ) > 0 ? $post_id : get_the_ID() );

		$parameter = array(
			'post'   => $the_id,
			'action' => 'elementor',
		);

		return admin_url( 'post.php' ) . '?' . build_query( $parameter );
	}

	/**
	 * Localize Script
	 */
	public function localize_array() {
		return array(
			'something_wrong' => esc_html__( 'Something went wrong', 'jeg-elementor-kit' ),
			'save_failed'     => esc_html__( 'Save Failed', 'jeg-elementor-kit' ),
		);
	}

	/**
	 * Admin footer
	 */
	public function admin_footer() {
		?>
		<div class="create-element-builder-overlay" id="create-element-popup-overlay"></div>
		<div class="create-element-builder-wrapper"></div>
		<?php
	}

	/**
	 * Print Script Template
	 */
	public function print_script_template() {
		?>
		<script id="tmpl-jkit-builder-empty" type="text/html">
			<div class="empty-content">
				<div class="empty-content-wrapper">
					<h1>{{ data.lang.createfirst }}</h1>
					<p>{{ data.lang.createdescription }}</p>
					<button type="button" class='create-element-button jkit-button'>
						{{ data.lang.addnewelement }}
					</button>
				</div>
			</div>
		</script>
		<script id="tmpl-jkit-popup" type="text/html">
			<div class="popup-option">
				<div class="popup-header">
					<h2>{{ data.lang.createelement }}</h2>
					<span class="close">
						<i class="fa fa-close"></i>
					</span>
				</div>
				<div class="popup-body">
					<div class="popup-content"></div>
				</div>
				<div class="popup-footer">
					<div class="close">{{ data.lang.close }}</div>
					<div class="generate">{{ data.lang.create }}</div>
				</div>
			</div>
		</script>
		<script id="tmpl-jkit-condition-container" type="text/html">
			<div class="jkit-condition-container">
				<div class="jkit-condition-empty">
					<h1>{{data.lang.createcondition}}</h1>
					<p>{{data.lang.createconditiondesc}}</p>
				</div>
				<div class="jkit-condition-wrapper"></div>
				<div class="jkit-condition-add">
					<button type="button"> {{data.lang.addcondition}}</button>
				</div>
			</div>
			<div class="jkit-condition-global">
				{{data.lang.globalelement}}
			</div>
		</script>
		<script id="tmpl-form-segment-multi" type="text/html">
			<div class="jkit-condition-item">
				<div class="jkit-condition-header" data-id="{{ data.id }}">
					{{{ data.name }}} <span class="tab-delete dashicons dashicons-trash" title="<?php esc_html_e( 'Delete', 'jeg-elementor-kit' ); ?>"></span>
				</div>
				<div class="jkit-condition-content" data-id="{{ data.id }}"></div>
			</div>
		</script>
		<script id="tmpl-jkit-builder-content" type="text/html">
			<div class="content-exist">
				<h2>{{ data.lang.manageelement }}</h2>
				<p>{{ data.lang.managedescription }}</p>
				<div class="active-element-wrapper">
					<h2>{{data.lang.activeelement}}</h2>
					<div class="active-element-heading">
						<ul>
							<li class="name">{{data.lang.elementname}}</li>
							<li class="priority">{{data.lang.priority}}</li>
							<li class="edit">{{data.lang.edit}}</li>
							<li class="clone">{{data.lang.clone}}</li>
							<li class="delete">{{data.lang.delete}}</li>
						</ul>
					</div>
					<div class="content-body connectedSortable" id="active-element"></div>
					<div class="content-button">
						<button type="button" class='create-element-button jkit-button'>
							{{ data.lang.addnewelement }}
						</button>
					</div>
				</div>
				<div class="inactive-element-wrapper">
					<h2>{{data.lang.inactiveelement}}</h2>
					<div class="content-body connectedSortable" id="inactive-element"></div>
				</div>
			</div>
		</script>
		<script id="tmpl-jkit-element-container" type="text/html">
			<div class="jkit-element-container" data-id="{{ data.id }}">
				<div class="jkit-container-header">
					<h3 title="<?php esc_html_e( 'Setup Condition', 'jeg-elementor-kit' ); ?>"><i class="fa fa-cog"></i> <span>{{{ data.title }}}</span></h3>
					<div class="jkit-header-action">
						<div class="tab-delete" title="<?php esc_html_e( 'Delete', 'jeg-elementor-kit' ); ?>">
							<i class="fa fa-trash-o"></i>
						</div>
						<div class="tab-clone" title="<?php esc_html_e( 'Clone', 'jeg-elementor-kit' ); ?>">
							<i class="fa fa-clone"></i>
						</div>
						<div class="tab-edit" title="<?php esc_html_e( 'Modify', 'jeg-elementor-kit' ); ?>">
							<a href="{{ data.url }}" target="_blank">
								<i class="fa fa-pencil"></i>
							</a>
						</div>
						<div class="tab-priority">&nbsp;</div>
					</div>
				</div>
				<div class="jkit-container-body">
					<div class="jkit-loading">{{data.lang.loading}}</div>
				</div>
			</div>
		</script>
		<?php
	}
}
