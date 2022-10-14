<?php
/**
 * This file is used for writing all the re-usable custom functions.
 *
 * @since   1.0.0
 * @package Core_Functions
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'cf_get_posts' ) ) {
	/**
	 * Get the posts.
	 *
	 * @param string $post_type Post type.
	 * @param int    $paged Paged value.
	 * @param int    $posts_per_page Posts per page.
	 * @return object
	 * @since 1.0.0
	 */
	function cf_get_posts( $post_type = 'post', $paged = 1, $posts_per_page = -1 ) {
		// Prepare the arguments array.
		$args = array(
			'post_type'      => $post_type,
			'paged'          => $paged,
			'posts_per_page' => $posts_per_page,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		/**
		 * Posts/custom posts listing arguments filter.
		 *
		 * This filter helps to modify the arguments for retreiving posts of default/custom post types.
		 *
		 * @param array $args Holds the post arguments.
		 * @return array
		 */
		$args = apply_filters( 'cf_posts_args', $args );

		return new WP_Query( $args );
	}
}

if ( ! function_exists( 'cf_list_posts_html' ) ) {
	/**
	 * Display the posts.
	 *
	 * @param array $wp_posts Holds Post ids.
	 * @since 1.0.0
	 */
	function cf_list_posts_html( $wp_posts ) {
		// Prepare Html.
		ob_start();
		foreach ( $wp_posts as $wp_post_id ) {
			$featured_image = get_the_post_thumbnail_url( $wp_post_id );
			$post_obj       = get_post( $wp_post_id );
			$post_tags      = get_the_terms( $wp_post_id, 'post_tag' );
			$new_tag        = ( in_array( 'new', $post_tags, true ) ) ? 'New' : '';
			$post_title     = get_the_title( $wp_post_id );
			$product_terms  = get_the_terms( $wp_post_id, 'category' );
			$post_content   = wp_filter_nohtml_kses( get_the_excerpt( $wp_post_id ) ); // Strips all HTML from a post content .
			$post_link      = get_permalink( $wp_post_id );
			?>
			<div class="col-md-4">
				<div class="blog_image">
					<img src="<?php echo esc_url( $featured_image ); ?>" alt="">
				</div>
				<a href="<?php echo esc_url( $post_link ); ?>" class="blog_title_link"><h4> <?php echo esc_html( $post_title ); ?> </h4></a>
				<p class="blog_content"> <?php echo esc_html( $post_content ); ?> </p>
			</div>
			<?php
		}

		return ob_get_clean();
	}
}
