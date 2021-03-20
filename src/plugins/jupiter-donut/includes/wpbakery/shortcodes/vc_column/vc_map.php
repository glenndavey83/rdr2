<?php
global $vc_column_width_list;
$vc_column_width_list = array(
	__( '1 column - 1/12', 'jupiter-donut' ) => '1/12',
	__( '2 columns - 1/6', 'jupiter-donut' ) => '1/6',
	__( '3 columns - 1/4', 'jupiter-donut' ) => '1/4',
	__( '4 columns - 1/3', 'jupiter-donut' ) => '1/3',
	__( '5 columns - 5/12', 'jupiter-donut' ) => '5/12',
	__( '6 columns - 1/2', 'jupiter-donut' ) => '1/2',
	__( '7 columns - 7/12', 'jupiter-donut' ) => '7/12',
	__( '8 columns - 2/3', 'jupiter-donut' ) => '2/3',
	__( '9 columns - 3/4', 'jupiter-donut' ) => '3/4',
	__( '10 columns - 5/6', 'jupiter-donut' ) => '5/6',
	__( '11 columns - 11/12', 'jupiter-donut' ) => '11/12',
	__( '12 columns - 1/1', 'jupiter-donut' ) => '1/1',
);

vc_map(
	array(
		'name' => __( 'Column', 'jupiter-donut' ),
		'base' => 'vc_column',
		'html_template' => dirname( __FILE__ ) . '/vc_column.php',
		'is_container' => true,
		'content_element' => false,
		'params' => array(
			array(
				'type' => 'alpha_colorpicker',
				'heading' => __( 'Column Border Color', 'jupiter-donut' ),
				'param_name' => 'border_color',
				'value' => '',
				'description' => __( 'You can optionally add border color to columns.', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Background Blend Modes', 'jupiter-donut' ),
				'param_name' => 'blend_mode',
				'value' => array(
					__( 'None', 'jupiter-donut' ) => 'none',
					__( 'Multiply', 'jupiter-donut' ) => 'multiply',
					__( 'Screen', 'jupiter-donut' ) => 'screen',
					__( 'Overlay', 'jupiter-donut' ) => 'overlay',
					__( 'Darken', 'jupiter-donut' ) => 'darken',
					__( 'Lighten', 'jupiter-donut' ) => 'lighten',
					__( 'Soft Light', 'jupiter-donut' ) => 'soft-light',
					__( 'Luminosity', 'jupiter-donut' ) => 'luminosity',
				),
				'description' => __( '', 'jupiter-donut' ),
			),
			$add_device_visibility,
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'jupiter-donut' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'jupiter-donut' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => __( 'Css', 'jupiter-donut' ),
				'param_name' => 'css',
				'group' => __( 'Design options', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Width', 'jupiter-donut' ),
				'param_name' => 'width',
				'value' => $vc_column_width_list,
				'group' => __( 'Responsive Options', 'jupiter-donut' ),
				'description' => __( 'Select column width.', 'jupiter-donut' ),
				'std' => '1/1',
			),
			array(
				'type' => 'column_offset',
				'heading' => __( 'Responsiveness', 'jupiter-donut' ),
				'param_name' => 'offset',
				'group' => __( 'Width & Responsiveness', 'jupiter-donut' ),
				'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'jupiter-donut' ),
			),
		),
		'js_view' => 'VcColumnView',
	)
);
vc_map(
	array(
		'name' => __( 'Column', 'jupiter-donut' ),
		'base' => 'vc_column_inner',
		'class' => '',
		'icon' => '',
		'wrapper_class' => '',
		'controls' => 'full',
		'allowed_container_element' => false,
		'content_element' => false,
		'is_container' => true,
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'jupiter-donut' ),
				'param_name' => 'el_class',
				'value' => '',
				'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'jupiter-donut' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => __( 'CSS box', 'jupiter-donut' ),
				'param_name' => 'css',
				'group' => __( 'Design Options', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Width', 'jupiter-donut' ),
				'param_name' => 'width',
				'value' => $vc_column_width_list,
				'group' => __( 'Responsive Options', 'jupiter-donut' ),
				'description' => __( 'Select column width.', 'jupiter-donut' ),
				'std' => '1/1',
			),
			array(
				'type' => 'column_offset',
				'heading' => __( 'Responsiveness', 'jupiter-donut' ),
				'param_name' => 'offset',
				'group' => __( 'Responsive Options', 'jupiter-donut' ),
				'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'jupiter-donut' ),
			),
		),
		'js_view' => 'VcColumnView',
	)
);
