<?php
vc_map(
	array(
		'name' => __( 'Text Block', 'jupiter-donut' ),
		'base' => 'vc_column_text',
		'html_template' => dirname( __FILE__ ) . '/vc_column_text.php',
		'icon' => 'icon-wpb-layer-shape-text',
		'wrapper_class' => 'jupiter-donut-clearfix',
		'category' => __( 'Content', 'js_composer' ),
		'description' => __( 'A block of text with WYSIWYG editor', 'js_composer' ),
		'params' => array(
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => __( 'Text', 'jupiter-donut' ),
				'param_name' => 'content',
				'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'jupiter-donut' ),
				'description' => __( 'Enter your content.', 'jupiter-donut' ),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'jupiter-donut' ),
				'param_name' => 'title',
				'value' => '',
				'description' => __( "You can optionally have global style title for this text block or leave this blank if you create your own title. Moreover you can disable this heading title's pattern divider using below option.", 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Title Pattern?', 'jupiter-donut' ),
				'param_name' => 'disable_pattern',
				'value' => 'true',
				'description' => __( 'If you want to remove the title pattern, disable this option.', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Align', 'jupiter-donut' ),
				'param_name' => 'align',
				'width' => 150,
				'value' => array(
					__( 'Left', 'jupiter-donut' ) => 'left',
					__( 'Right', 'jupiter-donut' ) => 'right',
					__( 'Center', 'jupiter-donut' ) => 'center',
				),
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Margin Bottom', 'jupiter-donut' ),
				'param_name' => 'margin_bottom',
				'value' => '0',
				'min' => '0',
				'max' => '200',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
			),
			$add_css_animations,
			$add_device_visibility,
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'jupiter-donut' ),
				'param_name' => 'el_class',
				'value' => '',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'jupiter-donut' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => __( 'CSS box', 'js_composer' ),
				'param_name' => 'css',
				'group' => __( 'Design Options', 'js_composer' ),
			),
		),
	)
);
