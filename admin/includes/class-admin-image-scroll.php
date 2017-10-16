<?php
/**
 * Sets up the admin functionality for the plugin.
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Class for admin functionally.
 *
 * @since 1.0.0
 */
class Image_Scroll_Admin {

	/**
	 * Holds the instances of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Sets up needed actions/filters for the admin to initialize.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {

		// Add tinymce controls.
		add_action( 'init', array( $this, 'setup_tinymce_plugin' ) );

		// Add scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'register_public_assets' ), 1 );
	}

	/**
	 * Add scripts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_public_assets() {
		$utility = new Cherry_Media_Utilit();
		$sizes = array();

		if ( $utility->get_image_sizes() ) {
			foreach ( $utility->get_image_sizes() as $key => $value ) {
				array_push( $sizes, $key );
			}
		}

		wp_register_script( 'image-scroll-admin-js', IMAGE_SCROLL_URL . 'admin/assets/js/admin.js', false, '1.0.0' );
		wp_enqueue_script( 'image-scroll-admin-js' );

		wp_localize_script( 'image-scroll-admin-js', 'imageScrollData', array(
			'image_sizes' => $sizes
		) );
	}

	/**
	 * Add tinymce controls.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function setup_tinymce_plugin() {

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can ( 'edit_pages' ) ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) !== 'true' ) {
			return;
		}

		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons_2', array( $this, 'add_tinymce_toolbar_button' ) );
	}

	public function add_tinymce_plugin( $plugin_array ) {

		$plugin_array['image_scroll'] = IMAGE_SCROLL_URL . 'admin/assets/js/tinymce-image-scroll.js';

		return $plugin_array;
	}

	public function add_tinymce_toolbar_button( $buttons ) {

		array_push( $buttons, 'image_scroll' );

		return $buttons;
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

Image_Scroll_Admin::get_instance();
