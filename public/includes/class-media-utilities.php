<?php
/**
 * Class Media Utilities
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Cherry_Media_Utilit' ) ) {

	/**
	 * Class Cherry Media Utilit
	 */
	class Cherry_Media_Utilit {

		/**
		 * Get post
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public function get_post_object( $id = 0 ) {
			return get_post( $id );
		}

		/**
		 * Get term
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public function get_term_object( $id = 0 ) {
			return get_term( $id );
		}

		/**
		 * Get post permalink
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_post_permalink() {
			return esc_url( get_the_permalink() );
		}

		/**
		 * Get size information for all currently-registered image sizes.
		 *
		 * @global $_wp_additional_image_sizes
		 * @uses   get_intermediate_image_sizes()
		 * @link   https://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
		 * @since  1.1.6
		 * @return array $sizes Data for all currently-registered image sizes.
		 */
		function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
					$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
					$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );

				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
					$sizes[ $_size ] = array(
						'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
						'height' => $_wp_additional_image_sizes[ $_size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
					);
				}
			}

			return $sizes;
		}

		/**
		 * Get array image size
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_thumbnail_size_array( $size ) {
			$sizes = $this->get_image_sizes();

			if ( isset( $sizes[ $size ] ) ) {
				$size_array = $sizes[ $size ];

			} else if ( isset( $sizes['post-thumbnail'] ) ) {
				$size_array = $sizes['post-thumbnail'];

			} else {
				$size_array = $sizes['thumbnail'];
			}

			return $size_array;
		}

		/**
		 * Output content method.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function output_method( $content = '', $echo = false ) {
			if ( ! filter_var( $echo, FILTER_VALIDATE_BOOLEAN ) ) {
				return $content;
			} else {
				echo $content;
			}
		}

		/**
		 * Get post image.
		 *
		 * @return string
		 */
		public function get_image( $args = array(), $type = 'post', $id = 0 ) {

			if ( is_callable( array( $this, 'get_' . $type . '_object' ) ) ) {
				$object = call_user_func( array( $this, 'get_' . $type . '_object' ), $id );

				if ( 'post' === $type && empty( $object->ID ) || 'term' === $type && empty( $object->term_id ) ) {
					return '';
				}
			}

			$default_args = array(
				'visible'                => true,
				'size'                   => apply_filters( 'cherry_normal_image_size', 'post-thumbnail' ),
				'html'                   => '<a href="%1$s" %2$s ><img src="%3$s" alt="%4$s" %5$s ></a>',
				'class'                  => 'wp-image',
				'placeholder'            => true,
				'placeholder_background' => '000',
				'placeholder_foreground' => 'fff',
				'placeholder_title'      => '',
				'html_tag_suze'          => true,
				'echo'                   => false,
			);

			$args = wp_parse_args( $args, $default_args );
			$html = '';

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {

				$intermediate_image_sizes   = get_intermediate_image_sizes();
				$intermediate_image_sizes[] = 'full';

				$size = $args['size'];
				$size = in_array( $size, $intermediate_image_sizes ) ? $size : 'post-thumbnail';

				// Placeholder defaults attr.
				$size_array = $this->get_thumbnail_size_array( $size );

				switch ( $type ) {
					case 'post':
						$id           = $object->ID;
						$thumbnail_id = get_post_thumbnail_id( $id );
						$alt          = esc_attr( $object->post_title );
						$link         = $this->get_post_permalink();
					break;

					case 'term':
						$id           = $object->term_id;
						$thumbnail_id = get_term_meta( $id, $this->args['meta_key']['term_thumb'] , true );
						$alt          = esc_attr( $object->name );
						$link         = $this->get_term_permalink( $id );
					break;

					case 'attachment':
						$thumbnail_id = $id;
						$alt          = get_the_title( $thumbnail_id );
						$link         = wp_get_attachment_image_url( $thumbnail_id, $size );
					break;
				}

				if ( $thumbnail_id ) {
					$image_data = wp_get_attachment_image_src( $thumbnail_id, $size );
					$src        = $image_data[0];

					$size_array['width']  = $image_data[1];
					$size_array['height'] = $image_data[2];

				} elseif ( filter_var( $args['placeholder'], FILTER_VALIDATE_BOOLEAN ) ) {
					$title = ( $args['placeholder_title'] ) ? $args['placeholder_title'] : $size_array['width'] . 'x' . $size_array['height'];
					$attr = array(
						'width'      => $size_array['width'],
						'height'     => $size_array['height'],
						'background' => $args['placeholder_background'],
						'foreground' => $args['placeholder_foreground'],
						'title'      => $title,
					);

					$attr = array_map( 'esc_attr', $attr );

					$width  = ( 4000 < intval( $attr['width'] ) )  ? 4000 : intval( $attr['width'] );
					$height = ( 4000 < intval( $attr['height'] ) ) ? 4000 : intval( $attr['height'] );

					$src = $this->get_placeholder_url( array(
						'width'      => $width,
						'height'     => $height,
						'background' => $attr['background'],
						'foreground' => $attr['foreground'],
						'title'      => $attr['title'],
					) );
				}

				$class         = ( $args['class'] ) ? 'class="' . esc_attr( $args['class'] ) . '"' : '';
				$html_tag_suze = ( filter_var( $args['html_tag_suze'], FILTER_VALIDATE_BOOLEAN ) ) ? 'width="' . $size_array['width'] . '" height="' . $size_array['height'] . '"' : '';

				if ( isset( $src ) ) {
					$html = sprintf( $args['html'], esc_url( $link ), $class, esc_url( $src ), esc_attr( $alt ), $html_tag_suze );
				}
			}

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * Get placeholder image URL
		 *
		 * @param array $args Image argumnets.
		 * @return string
		 */
		public function get_placeholder_url( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'width'      => 300,
				'height'     => 300,
				'background' => '000',
				'foreground' => 'fff',
				'title'      => '',
			) );

			$args      = array_map( 'urlencode', $args );
			$base_url  = 'http://fakeimg.pl';
			$format    = '%1$s/%2$sx%3$s/%4$s/%5$s/?text=%6$s';
			$image_url = sprintf(
				$format,
				$base_url, $args['width'], $args['height'], $args['background'], $args['foreground'], $args['title']
			);

			/**
			 * Filter image placeholder URL
			 *
			 * @param string $image_url Default URL.
			 * @param string $args      Image arguments.
			 */
			return apply_filters( 'cherry_utility_placeholder_image_url', esc_url( $image_url ), $args );
		}
	}
}
