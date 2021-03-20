<?php
if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Adds support to Visual Composer page builder. It also adds some features, elimniates some features from the plugin that plays not well with the theme.
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.1
 * @package     artbees
 */


// Do not proceed if Visual Composer plugin is not active.
if ( ! class_exists( 'WPBakeryShortCode' ) ) {
	return false;
}

// Disable some Visual Composer actions hook during template installation.
$mk_disable_vc_hook_on_template_installation_actions = [
	'abb_install_template_procedure',
	'abb_install_plugin',
	'abb_update_plugin',
	'abb_remove_plugin',
	'abb_get_templates_categories',
	'abb_template_lazy_load',
	'abb_is_restore_db',
];

if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $mk_disable_vc_hook_on_template_installation_actions ) ) ) {

	add_action( 'admin_init', 'mk_disable_vc_hook_on_template_installation' );

	function mk_disable_vc_hook_on_template_installation() {

		/**
		 * @since 4.5 used to call update file once option is changed
		 */
		remove_action(
			'update_option_wpb_js_compiled_js_composer_less', array(
				'Vc_Settings',
				'buildCustomColorCss',
			)
		);

		/**
		 * @since 4.5 used to call update file once option is changed
		 */
		remove_action(
			'update_option_wpb_js_custom_css', array(
				'Vc_Settings',
				'buildCustomCss',
			)
		);

		/**
		 * @since 4.5 used to call update file once option is changed
		 */
		remove_action(
			'add_option_wpb_js_compiled_js_composer_less', array(
				'Vc_Settings',
				'buildCustomColorCss',
			)
		);

		/**
		 * @since 4.5 used to call update file once option is changed
		 */
		remove_action(
			'add_option_wpb_js_custom_css', array(
				'Vc_Settings',
				'buildCustomCss',
			)
		);

		remove_action(
			'vc_after_init', array(
				'Vc_Manager',
				'rebuild',
			)
		);
	}
}

/*
*
* Set Visual Composer to act as bundled with the theme
* Load theme built-in shortcodes template files located in components/shortcodes
* Disable Frontend of Visual Composer due to the incompatibilities
*
*/

if ( ! function_exists( 'mk_set_visual_composer_as_bundled' ) ) {
	function mk_set_visual_composer_as_bundled() {

		vc_set_as_theme();
		vc_set_shortcodes_templates_dir( get_stylesheet_directory() . '/components/shortcodes' );
	}

	add_action( 'vc_before_init', 'mk_set_visual_composer_as_bundled' );
}




/*
*
* Add global params that are used in other shortcodes.
* load vc_map locted in /components/shortcodes/SHORTCODE_NAME/vc_map.php
* If child theme os active and vc_map exists in the same directory, the child theme will override the parent file
*
*/

if ( ! function_exists( 'mk_visual_composer_mapper' ) ) {
	function mk_visual_composer_mapper() {

		include( THEME_PLUGIN_INTEGRATIONS . '/visual-composer/global-params.php' );

		$shortcodes_dir = get_template_directory() . '/components/shortcodes/*/vc_map.php';

			$shortcodes = glob( $shortcodes_dir );

		if ( is_array( $shortcodes ) && ! empty( $shortcodes ) ) {
			foreach ( $shortcodes as $shortcode ) {

				$shortcode_name = array_reverse( explode( '/', $shortcode ) );
				$shortcode_name = $shortcode_name[1];

				$vc_map_path_child_theme = get_stylesheet_directory() . '/components/shortcodes/' . $shortcode_name . '/vc_map.php';

				if ( is_child_theme() ) {
					if ( file_exists( $vc_map_path_child_theme ) ) {
							include_once( $vc_map_path_child_theme );
					} else {
						include_once( $shortcode );
					}
				} else {
					include_once( $shortcode );
				}
			}
		}

		// For custom post types added in child theme
		$external_shortcodes_dir = get_stylesheet_directory() . '/components/shortcodes/*/vc_map.php';

		$external_shortcodes = glob( $external_shortcodes_dir );

		if ( is_array( $external_shortcodes ) && ! empty( $external_shortcodes ) ) {
			foreach ( $external_shortcodes as $shortcode ) {

				$shortcode_name = array_reverse( explode( '/', $shortcode ) );
				$shortcode_name = $shortcode_name[1];

						include_once( get_stylesheet_directory() . '/components/shortcodes/' . $shortcode_name . '/vc_map.php' );
			}
		}
	}

	add_action( 'vc_mapper_init_before', 'mk_visual_composer_mapper' );
}

/*
*
* Initialising theme built-in shortcodes for Visual Composer to detect them.
*/
class WPBakeryShortCode_mk_products extends WPBakeryShortCode{}
class WPBakeryShortCode_mk_header extends WPBakeryShortCode{}
