<?php

/**
 * This is a duplicated class of `jupiter-donut/includes/helpers/svg-icons.php` to handle compatibility when the plugin is inactive.
 *
 * @since 6.5.3
 */

defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'Mk_SVG_Icons' ) ) {
	class Mk_SVG_Icons {

		static function get_svg_icon_by_class_name( $echo, $name, $height = null, $fill = null, $gradient_type = null, $gradient_direction = null, $gradient_start = null, $gradient_stop = null, $id = null ) {
			return;
		}
	}
}
