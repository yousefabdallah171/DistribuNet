<?php
/**
 * Custom Header functionality for Guido
 *
 * @package WordPress
 * @subpackage Guido
 * @since Guido 1.0
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses guido_header_style()
 */
function guido_custom_header_setup() {
	$color_scheme        = guido_get_color_scheme();
	$default_text_color  = trim( $color_scheme[4], '#' );

	/**
	 * Filter Guido custom-header support arguments.
	 *
	 * @since Guido 1.0
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default_text_color     Default color of the header text.
	 *     @type int    $width                  Width in pixels of the custom header image. Default 954.
	 *     @type int    $height                 Height in pixels of the custom header image. Default 1300.
	 *     @type string $wp-head-callback       Callback function used to styles the header image and text
	 *                                          displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'guido_custom_header_args', array(
		'default-text-color'     => $default_text_color,
		'width'                  => 954,
		'height'                 => 1300,
		'wp-head-callback'       => 'guido_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'guido_custom_header_setup' );

if ( ! function_exists( 'guido_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog.
 *
 * @since Guido 1.0
 *
 * @see guido_custom_header_setup()
 */
function guido_header_style() {
	return '';
}
endif; // guido_header_style

