<?php
/**
 * The file that defines the activator class of the plugin.
 *
 * A class definition that holds the code that would execute on plugin activation.
 *
 * @link       https://github.com/vermadarsh/
 * @since      1.0.0
 *
 * @package    Core_Functions
 * @subpackage Core_Functions/includes
 */

/**
 * The activation class.
 *
 * A class definition that holds the code that would execute on plugin activation.
 *
 * @since      1.0.0
 * @package    Core_Functions
 * @author     Adarsh Verma <adarsh.srmcem@gmail.com>
 */
class Cf_Core_Functions_Activator {
	/**
	 * Enqueue scripts for admin end.
	 */
	public static function run() {
		$blog_page = get_page_by_path( 'blog', ARRAY_A );
		if ( is_array( $blog_page ) && ! empty( $blog_page ) ) {
			return;
		}
		$create_page = array(
			'post_type'   => 'page',
			'post_title'  => 'Blog',
			'post_name'   => 'blog',
			'post_status' => 'publish',
		);
		wp_insert_post( $create_page );
	}
}
