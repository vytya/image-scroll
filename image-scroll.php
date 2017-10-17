<?php
/**
 * Plugin Name: Image Scroll
 * Plugin URI: https://vytya.com/image-scroll
 * Version: 1.0.0
 * Author: Vytya
 * Author URI: http://vytya.com
 * Text Domain: image-scroll
 * Description: Wordpress plugin for making image scrolling blocks
 * Domain Path: /languages
 * License: GPL2
 */

// If this file is called directly, abort.
if ( ! defined ( 'WPINC' ) ) {
	die;
}

define( 'IMAGE_SCROLL_VERSION', '1.6.5' );
define( 'IMAGE_SCROLL__FILE__', __FILE__ );
define( 'IMAGE_SCROLL_PLUGIN_BASE', plugin_basename( IMAGE_SCROLL__FILE__ ) );
define( 'IMAGE_SCROLL_URL', plugins_url( '/', IMAGE_SCROLL__FILE__ ) );
define( 'IMAGE_SCROLL_PATH', plugin_dir_path( IMAGE_SCROLL__FILE__ ) );

if ( ! class_exists( 'Image_Scroll_Plugin' ) ) {
	/**
	 * Sets up and initializes the Image Scroll plugin.
	 *
	 * @since 1.0.0
	 */
	class Image_Scroll_Plugin {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Plugin version.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $version = '1.0.0';

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Internationalize the text strings used.
			add_action( 'plugin_loaded', array( $this, 'lang' ), 1 );

			// Load the functions files
			add_action( 'after_setup_theme', array( $this, 'includes' ), 2 );

			// Load the admin files.
			add_action( 'after_setup_theme', array( $this, 'admin' ), 3 );

			// Register image sizes.
			add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ), 4 );

			// Register a public javascripts and stylesheets.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_public_assets' ), 1 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 */
		public function lang() {
			load_plugin_textdomain( 'image-scroll', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Loads admin files.
		 *
		 * @since 1.0.0
		 */
		public function admin() {
			require_once IMAGE_SCROLL_PATH . 'public/includes/class-media-utilities.php';

			if ( is_admin() ) {
				require_once IMAGE_SCROLL_PATH . 'admin/includes/class-admin-image-scroll.php';
			}
		}

		/**
		 * Register image sizes.
		 *
		 * @since 1.0.0
		 */
		public function register_image_sizes() {

			add_image_size( 'image-scroll-thumb', 550, 630, true );
		}

		/**
		 * Loads files from the '/includes' folder.
		 *
		 * @since 1.0.0
		 */
		public function includes() {
			require_once IMAGE_SCROLL_PATH . 'public/includes/class-image-scroll-shortcode.php';
		}

		/**
		 * Register public assets.
		 *
		 * @since 1.0.0
		 */
		public function register_public_assets() {

			// Register scripts
			wp_register_script( 'imagesloaded', IMAGE_SCROLL_URL . 'public/assets/js/min/imagesloaded.pkgd.min.js', array( 'jquery' ), '4.1.3', true );
			wp_register_script( 'swiper', IMAGE_SCROLL_URL . 'public/assets/js/min/swiper.jquery.min.js', array( 'jquery', 'imagesloaded' ), '4.0.1', true );
			wp_register_script( 'magnific-popup', IMAGE_SCROLL_URL . 'public/assets/js/min/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
			wp_register_script( 'image-scroll-scripts-public', IMAGE_SCROLL_URL . 'public/assets/js/public.js', array( 'jquery', 'swiper', 'magnific-popup', 'imagesloaded' ), IMAGE_SCROLL_VERSION, true );

			// Register styles
			wp_register_style( 'swiper', IMAGE_SCROLL_URL . 'public/assets/css/swiper.min.css', array(), '3.3.0' );
			wp_register_style( 'magnific-popup', IMAGE_SCROLL_URL . 'public/assets/css/magnific-popup.min.css', array(), '1.1.0' );
			wp_register_style( 'image-scroll-public', IMAGE_SCROLL_URL . 'public/assets/css/public.css', array(), IMAGE_SCROLL_VERSION );
		}

		/**
		 * On plugin activation.
		 *
		 * @since 1.0.0
		 */
		public function activation() {
			flush_rewrite_rules();
		}

		/**
		 * On plugin deactivation.
		 *
		 * @since 1.0.0
		 */
		public function deactivation() {
			flush_rewrite_rules();
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
}

if ( ! function_exists( 'image_scroll_plugin' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function image_scroll_plugin() {
		return Image_Scroll_Plugin::get_instance();
	}
}

image_scroll_plugin();
