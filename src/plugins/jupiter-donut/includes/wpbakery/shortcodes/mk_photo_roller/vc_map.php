<?php
/**
 * Photo Roller shortcode map.
 *
 * @package Jupiter
 * @subpackage Visual_Composer
 * @since 5.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

vc_map( array(
	'name' => __( 'Photo Roller', 'jupiter-donut' ),
	'base' => 'mk_photo_roller',
	'html_template' => dirname( __FILE__ ) . '/mk_photo_roller.php',
	'show_settings_on_create' => true,
	'description' => __( 'Infinite rolling background image', 'jupiter-donut' ),
	'icon' => 'icon-mk-photo-roller',
	'category' => __( 'General', 'jupiter-donut' ),
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'jupiter-donut' ),
			'param_name' => 'image',
			'value' => '',
			'admin_label' => true,
			'description' => __( 'Select an appropriate image (width bigger than height) from Media Library.', 'jupiter-donut' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Rolling axis', 'js_composer' ),
			'param_name' => 'rolling_axis',
			'value' => array(
				__( 'Horizontal', 'js_composer' ) => 'horizontal',
				// __( 'Vertical', 'js_composer' ) => 'vertical',
			),
			'std' => 'horizontal',
		),
		array(
			'type' => 'toggle',
			'heading' => __( 'Reverse direction', 'jupiter-donut' ),
			'param_name' => 'reverse_direction',
			'value' => 'false',
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type' => 'toggle',
			'heading' => __( 'Pause on hover', 'jupiter-donut' ),
			'param_name' => 'pause_hover',
			'value' => 'false',
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type' => 'range',
			'heading' => __( 'Rolling speed', 'jupiter-donut' ),
			'param_name' => 'rolling_speed',
			'value' => '250',
			'min' => '1',
			'max' => '300',
			'step' => '1',
			'unit' => 'px/s',
			'description' => __( 'Adjust the rolling speed. The width/height will affect the speed.', 'jupiter-donut' ),
		),
		$add_device_visibility,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'jupiter-donut' ),
			'param_name' => 'el_class',
			'value' => '',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'jupiter-donut' ),
		),
	),
) );
