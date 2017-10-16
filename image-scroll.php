<?php
/**
 * Plugin Name: TinyMCE Small Button
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
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			// Set the constants needed by the plugin.
			add_action( 'plugin_loaded', array( $this, 'constants' ), 0 );

			// Internationalize the text strings used.
			add_action( 'plugin_loaded', array( $this, 'lang' ), 1 );

			// Load the functions files
			add_action( 'after_setup_theme', array( $this, 'includes' ), 2 );

			// Load the admin files.
			add_action( 'after_setup_theme', array( $this, 'admin' ), 3 );

			// Init modules
			add_action( 'after_setup_theme', array( $this, 'init_modules' ), 10 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		function constants() {

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$plugin_data = get_plugin_data( plugin_dir_path( __FILE__ ) . basename( __FILE__ ) );

			/**
			 * Set the version number of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'IMAGE_SCROLL_VERSION', $plugin_data['Version'] );

			/**
			 * Set constant path to the plugin directory.
			 *
			 * @since 1.0.0
			 */
			define( 'IMAGE_SCROLL_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			/**
			 * Set constant path to the plugin URI.
			 *
			 * @since 1.0.0
			 */
			define( 'IMAGE_SCROLL_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}

		/**
		 * Loads files from the '/includes' folder.
		 *
		 * @since 1.0.0
		 */
		function includes() {
			require_once( IMAGE_SCROLL_DIR . 'includes/class-image-scroll.php' );
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 */
		function lang() {
			load_plugin_textdomain( 'image-scroll', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Loads admin files.
		 *
		 * @since 1.0.0
		 */
		function admin() {
			if ( is_admin() ) {
				require_once( IMAGE_SCROLL_DIR . 'admin/includes/class-admin-image-scroll.php' );
			}
		}

		/**
		 * On plugin activation.
		 *
		 * @since 1.0.0
		 */
		function activation() {
			flush_rewrite_rules();
		}

		/**
		 * On plugin deactivation.
		 *
		 * @since 1.0.0
		 */
		function deactivation() {
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
