<?php
vc_map(
	array(
		'name' => __( 'Row', 'jupiter-donut' ),
		'base' => 'vc_row',
		'html_template' => dirname( __FILE__ ) . '/vc_row.php',
		'is_container' => true,
		'icon' => 'icon-wpb-row',
		'show_settings_on_create' => false,
		'category' => __( 'Content', 'js_composer' ),
		'class' => 'vc_main-sortable-element',
		'description' => __( 'Place content elements inside the row', 'js_composer' ),
		'params' => array(
			array(
				'type' => 'toggle',
				'heading' => __( 'Full Width Row', 'jupiter-donut' ),
				'param_name' => 'fullwidth',
				'value' => 'false',
				'description' => __( 'When enabled, this row will no longer follow the main grid width and will stretch 100% to screen width.', 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Full Width Content?', 'jupiter-donut' ),
				'param_name' => 'fullwidth_content',
				'value' => 'true',
				'description' => __( 'This option works if "Full Width Row" is enabled and it gives you the power to choose whether inside the row follows global grid width or be totally full width.', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'fullwidth',
					'value' => array(
						'true',
					),
				),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Equal Columns', 'jupiter-donut' ),
				'param_name' => 'equal_columns',
				'value' => 'false',
				'description' => __( 'When enabled, columns inside this row will stretch to the highest column.', 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Attached Colums', 'jupiter-donut' ),
				'param_name' => 'attached',
				'value' => 'false',
				'description' => __( 'When enabled, this option attachs child columns to each other. In other words columns inside this row will be stuck to each other.', 'jupiter-donut' ),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Column Paddings', 'jupiter-donut' ),
				'param_name' => 'column_padding',
				'value' => '0',
				'min' => '0',
				'max' => '5',
				'step' => '1',
				'unit' => '%',
				'description' => __( "This option creates pading space inside columns. This option will work when 'Attached Colums' option is enabled. Note that padding unit is by percent and will be applied to all directions.", 'jupiter-donut' ),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Row ID', 'jupiter-donut' ),
				'param_name' => 'id',
				'description' => __( 'This option comes handy when you are creating One page scroll website and here you can set ID which you used in your navigation anchor tag.', 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Use video background?', 'jupiter-donut' ),
				'param_name' => 'video_bg',
				'description' => __( 'If checked, video will be used as row background.', 'jupiter-donut' ),
				'value' => 'false',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'YouTube link', 'jupiter-donut' ),
				'param_name' => 'video_bg_url',
				'value' => 'https://www.youtube.com/watch?v=lMJXxhRFO1k',
				'description' => __( 'Add YouTube link.', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'video_bg',
					'value' => array(
						'true',
					),
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Parallax', 'jupiter-donut' ),
				'param_name' => 'video_bg_parallax',
				'value' => array(
					__( 'None', 'jupiter-donut' ) => '',
					__( 'Simple', 'jupiter-donut' ) => 'content-moving',
					__( 'With fade', 'jupiter-donut' ) => 'content-moving-fade',
				),
				'description' => __( 'Add parallax type background for row.', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'video_bg',
					'value' => array(
						'true',
					),
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Parallax', 'jupiter-donut' ),
				'param_name' => 'parallax',
				'value' => array(
					__( 'None', 'jupiter-donut' ) => '',
					__( 'Simple', 'jupiter-donut' ) => 'content-moving',
					__( 'With fade', 'jupiter-donut' ) => 'content-moving-fade',
				),
				'description' => __( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'video_bg',
					'value' => array(
						'false',
					),
				),
			),
			array(
				'type' => 'attach_image',
				'heading' => __( 'Image', 'jupiter-donut' ),
				'param_name' => 'parallax_image',
				'value' => '',
				'description' => __( 'Select image from media library.', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'parallax',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Parallax speed', 'jupiter-donut' ),
				'param_name' => 'parallax_speed_video',
				'value' => '1.5',
				'description' => __( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'video_bg_parallax',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Parallax speed', 'jupiter-donut' ),
				'param_name' => 'parallax_speed_bg',
				'value' => '1.5',
				'description' => __( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'parallax',
					'not_empty' => true,
				),
			),
			$add_device_visibility,
			$add_css_animations,
			array(
				'type' => 'checkbox',
				'heading' => __( 'Disable row', 'jupiter-donut' ),
				'param_name' => 'disable_element', // Inner param name.
				'description' => __( 'If checked the row won\'t be visible on the public side of your website. You can switch it back any time.', 'jupiter-donut' ),
				'value' => array(
					__( 'Yes', 'jupiter-donut' ) => 'yes',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'jupiter-donut' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'jupiter-donut' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => __( 'CSS box', 'jupiter-donut' ),
				'param_name' => 'css',
				'group' => __( 'Design Options', 'jupiter-donut' ),
			),

		),
		'js_view' => 'VcRowView',
	)
);
