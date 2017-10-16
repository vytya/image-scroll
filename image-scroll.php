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
		 * Plugin folder URL.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $plugin_url = '';

		/**
		 * Plugin folder path.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $plugin_dir = '';

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

			// Register a public javascripts and stylesheets.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_public_assets' ), 1 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Get plugin URL (or some plugin dir/file URL)
		 *
		 * @since  1.0.0
		 * @param  string $path dir or file inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = plugin_dir_url( __FILE__ );
			}

			if ( null !== $path ) {
				$path = wp_normalize_path( $path );

				return $this->plugin_url . ltrim( $path, '/' );
			}

			return $this->plugin_url;
		}

		/**
		 * Get plugin dir path (or some plugin dir/file path)
		 *
		 * @since  1.0.0
		 * @param  string $path dir or file inside plugin dir.
		 * @return string
		 */
		public function plugin_dir( $path = null ) {

			if ( ! $this->plugin_dir ) {
				$this->plugin_dir = plugin_dir_path( __FILE__ );
			}

			if ( null !== $path ) {
				$path = wp_normalize_path( $path );

				return $this->plugin_dir . $path;
			}

			return $this->plugin_dir;
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
		 * Loads files from the '/includes' folder.
		 *
		 * @since 1.0.0
		 */
		public function includes() {
			require_once $this->plugin_dir( 'public/includes/class-media-utilities.php' );
			require_once $this->plugin_dir( 'public/includes/class-image-scroll-shortcode.php' );
		}

		/**
		 * Loads admin files.
		 *
		 * @since 1.0.0
		 */
		public function admin() {
			if ( is_admin() ) {
				require_once $this->plugin_dir( 'admin/includes/class-admin-image-scroll.php' );
			}
		}

		/**
		 * Register public assets.
		 *
		 * @since 1.0.0
		 */
		public function register_public_assets() {
			wp_register_style( 'image-scroll-public', $this->plugin_dir( 'public/assets/css/public.css' ), array(), $this->$version );
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
