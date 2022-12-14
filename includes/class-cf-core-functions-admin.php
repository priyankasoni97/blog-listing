<?php
/**
 * The file that defines the core plugin class.
 *
 * A class definition that holds all the hooks regarding all the custom functionalities.
 *
 * @link      https://github.com/priyankasoni97/
 * @since      1.0.0
 *
 * @package    Blog_List_Admin
 * @subpackage Blog_List_Admin/includes
 */

/**
 * The core plugin class.
 *
 * A class definition that holds all the hooks regarding all the custom functionalities.
 *
 * @since      1.0.0
 * @package    Blog_List
 * @author     Priyanka Soni <priyanka.soni@cmsminds.com>
 */
class Cf_Core_Functions_Admin {
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load all the hooks here.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'cf_admin_enqueue_scripts_callback' ) );
	}

	/**
	 * Enqueue scripts for admin end.
	 */
	public function cf_admin_enqueue_scripts_callback() {
		// Custom admin style.
		wp_enqueue_style(
			'core-functions-admin-style',
			CF_PLUGIN_URL . 'assets/admin/css/core-functions-admin.css',
			array(),
			filemtime( CF_PLUGIN_PATH . 'assets/admin/css/core-functions-admin.css' ),
		);

		// Custom admin script.
		wp_enqueue_script(
			'core-functions-admin-script',
			CF_PLUGIN_URL . 'assets/admin/js/core-functions-admin.js',
			array( 'jquery' ),
			filemtime( CF_PLUGIN_PATH . 'assets/admin/js/core-functions-admin.js' ),
			true
		);

		// Localize admin script.
		wp_localize_script(
			'core-functions-admin-script',
			'CF_Admin_JS_Obj',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
}
