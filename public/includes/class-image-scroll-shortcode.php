<?php
/**
 * Image Scroll shortcode class.
 */

/**
 * Class for Image Scroll shortcode build data.
 *
 * @since 1.0.0
 */
class Image_Scroll_Shortcode {

	/**
	 * Shortcode name.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public static $name = 'imagescroll';

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Current instance arguments
	 *
	 * @since  1.0.0
	 * @var array
	 */
	public $args = null;

	/**
	 * Storage for data object
	 * @since 1.0.0
	 * @var   null|object
	 */
	public $data = null;

	/**
	 * Sets up our actions/filters.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register shortcode on 'init'.
		add_action( 'init', array( $this, 'register_shortcode' ), 0 );

		$this->data = Image_Scroll_Plugin::get_instance();
	}

	/**
	 * Registers the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcode() {

		add_shortcode( $this->tag(), array( $this, 'do_shortcode' ) );
	}

	/**
	 * Returns shortcode tag.
	 *
	 * @return string
	 */
	public function tag() {

		/**
		 * Filters a shortcode name.
		 *
		 * @since 1.0.0
		 * @param string $this->name Shortcode name.
		 */
		$tag = apply_filters( self::$name . '_shortcode_name', self::$name );

		return $tag;
	}

	/**
	 * The shortcode function.
	 *
	 * @since  1.0.0
	 * @param  array  $atts      The user-inputted arguments.
	 * @param  string $content   The enclosed content (if the shortcode is used in its enclosing form).
	 * @param  string $shortcode The shortcode tag, useful for shared callback functions.
	 * @return string
	 */
	public function do_shortcode( $atts, $content = null, $shortcode = 'imagescroll' ) {

		wp_enqueue_script( 'swiper' );
		wp_enqueue_script( 'image-scroll-scripts-public' );

		wp_enqueue_style( 'swiper' );
		wp_enqueue_style( 'image-scroll-public' );

		wp_localize_script( 'image-scroll-scripts-public', 'imageScrollData', array(
			'loading_image'    => esc_html__( 'Loading image #%curr%...', 'image-scroll' ),
			'not_loaded_image' => esc_html__( '<a href="%url%">The image #%curr%</a> could not be loaded.', 'image-scroll' ),
		) );

		$utility = new Cherry_Media_Utilit();

		// Set up the default arguments.
		$defaults = array(
			'imageids' => '',
			'size'     => 'image-scroll-thumb',
			'lightbox' => 'true'
		);

		/**
		 * Parse the arguments.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/shortcode_atts
		 */
		$atts   = shortcode_atts( $defaults, $atts, $shortcode );
		$size   = $utility->get_thumbnail_size_array( $atts['size'] );
		$width  = $size['width'];
		$height = $size['height'];

		$css_style = 'style="max-width: ' . $width . 'px; max-height: ' . $height . 'px;"';

		if ( $atts['imageids'] !== '' ) {

			if ( strpos( $atts['imageids'], ',' ) !== false ) {

				if ( $atts['lightbox'] == 'true' ) {
					wp_enqueue_script( 'magnific-popup' );
					wp_enqueue_style( 'magnific-popup' );
				}

				$images = explode( ',', str_replace( ' ', '', $atts['imageids'] ) );
				$image  = array();

				foreach ( $images as $key => $value ) {
					$full_image_src = $utility->get_image( array(
						'size'        => 'full',
						'html'        => '%3$s',
						'echo'        => false,
					), 'attachment', $value );

					$display_image = $utility->get_image( array(
						'size'        => $atts['size'],
						'html'        => '<img src="%3$s">',
						'echo'        => false,
					), 'attachment', $value );

					$format_lightbox   = '<div class="swiper-slide"><a href="%1$s" class="image-scroll-popup">%2$s</a></div>';
					$format_nolightbox = '<div class="swiper-slide">%2$s</div>';

					$format = ( $atts['lightbox'] == 'true' ) ? $format_lightbox : $format_nolightbox;

					$html = sprintf(
						$format,
						$full_image_src,
						$display_image
					);

					array_push( $image, $html );
				}

				$image = implode( '', $image );

			} else {

				$image = $utility->get_image( array(
					'size'        => $atts['size'],
					'html'        => '<img src="%3$s">',
					'echo'        => false,
				), 'attachment', $atts['images'] );
			}
		}

		return sprintf(
			'<div class="image-scroll-wrap">
				<div class="image-scroll swiper-container" data-uniq-id="%2$s" %3$s>
					<div class="swiper-wrapper">
						%1$s
					</div>
					<div class="swiper-scrollbar">
				</div>
			</div></div>',
			$image,
			rand(),
			$css_style
		);
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

Image_Scroll_Shortcode::get_instance();
