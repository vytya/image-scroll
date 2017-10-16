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

		$media_utilities = new Cherry_Media_Utilit();

		// Set up the default arguments.
		$defaults = array(
			'imageids' => '',
		);

		/**
		 * Parse the arguments.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/shortcode_atts
		 */
		$atts = shortcode_atts( $defaults, $atts, $shortcode );

		if ( $atts['images'] !== '' ) {
			if ( strpos( $atts['images'], ',' ) !== false ) {
				$images = explode( ',', str_replace( ' ', '', $atts['images'] ) );
				$image  = array();

				foreach ($images as $key => $value) {
					$image_html = $utility->media->get_image( array(
						'size'        => 'johnnygo-thumb-m',
						'mobile_size' => 'johnnygo-thumb-m',
						'html'        => '<div class="swiper-slide"><img src="%3$s"></div>',
						'echo'        => false,
					), 'attachment', $value );

					array_push($image, $image_html);
				}
				$image = implode('', $image);
			} else {
				$image = $utility->media->get_image( array(
					'size'        => 'johnnygo-thumb-m',
					'html'        => '<img src="%3$s">',
					'echo'        => false,
				), 'attachment', $atts['images'] );
			}
		}

	return sprintf(
		'<div class="some-wrap"><div class="shortcode swiper-container" data-uniq-id="%2$s">
			<div class="swiper-wrapper">
				%1$s
			</div>
			<div class="swiper-scrollbar"></div>
		</div></div>',
		$image,
		rand(5, 15)
	);

		return $before . $this->data->the_team( $data_args ) . $after;
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
