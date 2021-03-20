<?php

defined( 'ABSPATH' ) || die();

/**
 * Helper functions for get views part of the template
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.1
 * @package     artbees
 */



/**
 * Get template parts from views folder
 *
 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
 *
 * @param string    $slug
 * @param string    $name
 * @param boolean   $return
 * @return object
 */
if ( ! function_exists( 'mk_get_view' ) ) {
	function mk_get_view( $slug, $name = '', $return = false, $view_params = array() ) {
		if ( $return ) {
			ob_start();
			mk_get_template_part( 'views/' . $slug . '/' . $name, $view_params );
			return ob_get_clean();
		} else {
			mk_get_template_part( 'views/' . $slug . '/' . $name, $view_params );
		}
	}
}

/**
 * Get header components and put them together. this function passes variables into the file too.
 *
 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
 *
 * @param string    $slug
 * @param string    $name
 * @param boolean   $return
 * @return object
 */
if ( ! function_exists( 'mk_get_header_view' ) ) {
	function mk_get_header_view( $location, $component, $view_params = array(), $return = false ) {
		if ( $return ) {
			ob_start();
			mk_get_template_part( 'views/header/' . $location . '/' . $component, $view_params );
			return ob_get_clean();
		} else {
			mk_get_template_part( 'views/header/' . $location . '/' . $component, $view_params );
		}

	}
}

/**
 * Get template parts from shortcodes folder
 *
 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
 *
 * @param string    $slug
 * @param string    $name
 * @param boolean   $return
 * @return object
 */
if ( ! function_exists( 'mk_get_shortcode_view' ) ) {
	function mk_get_shortcode_view( $shortcode_name, $name = '', $return = false, $view_params = array() ) {

		$name = sanitize_text_field( $name );

		if ( is_dir( JUPITER_DONUT_INCLUDES_DIR . '/wpbakery/shortcodes/' . $shortcode_name ) ) {
			$directory = 'wpbakery';
		} elseif ( is_dir( get_template_directory() . '/components/shortcodes/' . $shortcode_name ) ) {
			$directory = 'components';
		}

		if ( empty( $directory ) ) {
			return;
		}

		if ( $return ) {
			ob_start();
			mk_get_template_part( $directory . '/shortcodes/' . $shortcode_name . '/' . $name, $view_params );
			return ob_get_clean();
		} else {
			mk_get_template_part( $directory . '/shortcodes/' . $shortcode_name . '/' . $name, $view_params );
		}

	}
}


/**
 * Like get_template_part() put lets you pass args to the template file
 * Args are available in the tempalte as $view_params array
 *
 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
 *
 * @param string filepart
 * @param mixed wp_args style argument list
 *
 * @since 5.0.0
 * @since 5.9.1 Refactored the function to improve performance.
 */
if ( ! function_exists( 'mk_get_template_part' ) ) {
	function mk_get_template_part( $file, $view_params = array() ) {
		global $post;
		$view_params = wp_parse_args( $view_params );

		$file_path = mk_get_overridable_path( $file . '.php' );

		if ( empty( $file_path ) ) {
			return;
		}

		ob_start();
		require( $file_path );
		$output = ob_get_clean();
		echo $output;
	}
}

if ( ! function_exists( 'mk_get_overridable_path' ) ) {
	/**
	 * Get an overridable path.
	 *
	 * @param string path
	 *
	 * @since 1.0.7
	 */
	function mk_get_overridable_path( $path ) {

		if ( file_exists( get_stylesheet_directory() . '/' . $path ) ) {
			$path = realpath( get_stylesheet_directory() . '/' . $path );
		} elseif ( file_exists( JUPITER_DONUT_INCLUDES_DIR . '/' . $path ) ) {
			$path = realpath( JUPITER_DONUT_INCLUDES_DIR . '/' . $path );
		} elseif ( file_exists( get_template_directory() . '/' . $path ) ) {
			$path = realpath( get_template_directory() . '/' . $path );
		}

		return $path;
	}
}
