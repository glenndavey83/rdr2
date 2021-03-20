<?php

/**
 * This is a duplicated class of `jupiter-donut/includes/helpers/image-resize.php` to handle compatibility when the plugin is inactive.
 *
 * @since 6.5.3
 */

defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'Mk_Image_Resize' ) ) {
	class Mk_Image_Resize {

		public static function is_default_thumb( $image = null ) {
			return;
		}

		public static function resize_by_id_adaptive( $attachment_id, $image_size, $width, $height, $crop = true, $dummy = true ) {
			return;
		}

	}
}
