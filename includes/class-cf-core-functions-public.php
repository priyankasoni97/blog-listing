<?php
/**
 * The file that defines the core plugin class.
 *
 * A class definition that holds all the hooks regarding all the custom functionalities.
 *
 * @link       https://github.com/vermadarsh/
 * @since      1.0.0
 *
 * @package    Core_Functions_Public
 * @subpackage Core_Functions_Public/includes
 */

/**
 * The core plugin class.
 *
 * A class definition that holds all the hooks regarding all the custom functionalities.
 *
 * @since      1.0.0
 * @package    Core_Functions
 * @author     Adarsh Verma <adarsh@cmsminds.com>
 */
class Cf_Core_Functions_Public {
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load all the hooks here.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'cf_wp_enqueue_scripts_callback' ) );
		add_action( 'template_include', array( $this, 'cf_template_include_callback' ), 99 );
		add_action( 'wp_ajax_fetch_posts', array( $this, 'fetch_posts_callback' ) );
		add_action( 'wp_ajax_nopriv_fetch_posts', array( $this, 'fetch_posts_callback' ) );
		add_action( 'cf_posts_args', array( $this, 'cf_posts_args_callback' ) );
		
	}

	/**
	 * Enqueue scripts for public end.
	 */
	public function cf_wp_enqueue_scripts_callback() {
		// Bootstarap min style.
		wp_enqueue_style(
			'bootstrap-min-style',
			CF_PLUGIN_URL . 'assets/public/css/lib/bootstrap.min.css',
			array(),
			filemtime( CF_PLUGIN_PATH . 'assets/public/lib/bootstrap.min.css' ),
		);

		// Font Awsome Style.
		wp_enqueue_style(
			'font-awsome-style',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css',
		);

		// Custom public style.
		wp_enqueue_style(
			'core-functions-public-style',
			CF_PLUGIN_URL . 'assets/public/css/core-functions-public.css',
			array(),
			filemtime( CF_PLUGIN_PATH . 'assets/public/css/core-functions-public.css' ),
		);

		// Bootstrap min script.
		wp_enqueue_script(
			'bootstrap-min-script',
			CF_PLUGIN_URL . 'assets/public/js/lib/bootstrap.min.js',
			array( 'jquery' ),
			filemtime( CF_PLUGIN_PATH . 'assets/public/js/lib/bootstrap.min.js' ),
			true
		);

		// Custom public script.
		wp_enqueue_script(
			'core-functions-public-script',
			CF_PLUGIN_URL . 'assets/public/js/core-functions-public.js',
			array( 'jquery' ),
			filemtime( CF_PLUGIN_PATH . 'assets/public/js/core-functions-public.js' ),
			true
		);

		// Localize public script.
		wp_localize_script(
			'core-functions-public-script',
			'CF_Public_JS_Obj',
			array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'is_blog_page' => is_page( 'blog' ) ? 'yes' : 'no',
			)
		);
	}

	/**
	 * Template Redirect hook for archive pages and others.
	 *
	 * @since 1.0.0
	 * @param    array $templates This variable holds the all the templates array.
	 */
	public function cf_template_include_callback( $templates ) {
		if ( is_page( 'blog' ) ) {
			$file_name = 'blog.php';
			$templates = CF_PLUGIN_PATH . 'templates/' . $file_name;
		}
		return $templates;
	}

	/**
	 * Function to serve ajax request for lists posts.
	 */
	public function fetch_posts_callback() {
		$paged       = (int) filter_input( INPUT_POST, 'paged', FILTER_SANITIZE_NUMBER_INT );
		$posts_query = cf_get_posts();
		$all_posts   = $posts_query->posts;
		$count       = $posts_query->found_posts;
		$loaded      = (int) ceil( $count / $posts_query->query['posts_per_page'] );
		if ( $loaded > 1 ) {
			$loadmore = 2;
		}
		if ( isset( $paged ) && ! empty( $paged ) ) {
			$loadmore = $paged + 1;
		}
		if ( $loaded === $paged ) {
			$loadmore = 0;
		}
		if ( ! empty( $all_posts ) && is_array( $all_posts ) ) {
			$code    = 'success';
			$html    = cf_list_posts_html( $all_posts );
			$paged   = $loadmore;
			$message = 'Posts Found successfully..!';
		} else {
			$code    = 'failed';
			$html    = '';
			$message = 'Oops! No Posts Found..!';
		}
		wp_send_json_success(
			array(
				'code'    => $code,
				'html'    => $html,
				'paged'   => $paged,
				'message' => $message,
			)
		);
		wp_die();
	}

	/**
	 * Function for apply filter for get posts.
	 *
	 * @param array $args Hold arguments array.
	 */
	public function cf_posts_args_callback( $args ) {
		$paged       = filter_input( INPUT_POST, 'paged', FILTER_SANITIZE_NUMBER_INT );
		$category_id = filter_input( INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT );
		$searchtext  = filter_input( INPUT_POST, 'searchtext', FILTER_SANITIZE_STRING );

		$args        = array(
			'paged'          => 1,
			'posts_per_page' => 3,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		);
		if ( isset( $paged ) && $paged > 0 ) {
			$args['paged'] = $paged;
		}
		if ( isset( $category_id ) && ! empty( $category_id ) ) {
			$args['category__in'] = $category_id;
		}
		if ( isset( $searchtext ) && ! empty( $searchtext ) ) {
			$args['s'] = $searchtext;
		}
		return $args;
	}
}
