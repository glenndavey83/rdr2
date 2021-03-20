<?php
vc_map(array(
	'name' => __( 'Button', 'jupiter-donut' ),
	'base' => 'mk_button',
	'html_template' => dirname( __FILE__ ) . '/mk_button.php',
	'category' => __( 'General', 'jupiter-donut' ),
	'icon' => 'icon-mk-button vc_mk_element-icon',
	'description' => __( 'Powerful & versatile button shortcode', 'jupiter-donut' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'jupiter-donut' ),
			'param_name' => 'dimension',
			'value' => array(
				__( '3D', 'jupiter-donut' ) => 'three',
				__( '2D', 'jupiter-donut' ) => 'two',
				__( 'Flat', 'jupiter-donut' ) => 'flat',
				__( 'Outline', 'jupiter-donut' ) => 'outline',
				__( 'Savvy', 'jupiter-donut' ) => 'savvy',
				__( 'Double Outline ', 'jupiter-donut' ) => 'double-outline',
			),
		) ,
		array(
			'type' => 'textarea',
			'holder' => 'div',
			'heading' => __( 'Button Text', 'jupiter-donut' ),
			'param_name' => 'content',
			'rows' => 1,
			'value' => '',
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Corner style', 'jupiter-donut' ),
			'param_name' => 'corner_style',
			'value' => array(
				'Pointed' => 'pointed',
				'Rounded' => 'rounded',
				'Full Rounded' => 'full_rounded',
			),
			'description' => __( 'How will your button corners look like?', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'jupiter-donut' ),
			'param_name' => 'size',
			'value' => array(
				'Small' => 'small',
				'Medium' => 'medium',
				'Large' => 'large',
				'X-Large' => 'x-large',
				'XX-Large' => 'xx-large',
			),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Button Text Letter spacing', 'jupiter-donut' ),
			'param_name' => 'letter_spacing',
			'value' => '0',
			'min' => '0',
			'max' => '20',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'Using this option you can add space between each character.', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'icon_selector',
			'heading' => __( 'Add Icon', 'jupiter-donut' ),
			'param_name' => 'icon',
			'value' => '',
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Animate Icon?', 'jupiter-donut' ),
			'param_name' => 'icon_anim',
			'value' => array(
				__( 'No animation', 'jupiter-donut' ) => 'none',
				__( 'Side animation', 'jupiter-donut' ) => 'side',
				__( 'Vertical animation ', 'jupiter-donut' ) => 'vertical',
			),
			'description' => __( "Button icon animates once the user's mouse rolls over the button", 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'dimension',
				'value' => array(
					'two',
					'three',
					'flat',
					'outline',
				),
			),
		) ,
		array(
			'type' => 'textfield',
			'heading' => __( 'Button URL', 'jupiter-donut' ),
			'param_name' => 'url',
			'value' => '',
		) ,
		array(
			'type' => 'autocomplete',
			'heading' => __( 'Select Specific Product', 'jupiter-donut' ),
			'param_name' => 'product_id',
			'settings' => array(
				'multiple' => false,
				'sortable' => false,
				'unique_values' => true,
			),
			'description' => __( 'Search for product ID/title to get autocomplete suggestions', 'jupiter-donut' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Target', 'jupiter-donut' ),
			'param_name' => 'target',
			'width' => 200,
			'value' => $target_arr,
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Add rel:nofollow to the Link?', 'jupiter-donut' ),
			'param_name' => 'nofollow',
			'value' => 'false',
			'description' => __( "Nofollow provides a way for you to tell search engines \"Don't follow this specific link.\". So you instruct search engines that the hyperlink should not influence the ranking of the link's target in the search engine's index.", 'jupiter-donut' ),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Align', 'jupiter-donut' ),
			'param_name' => 'align',
			'width' => 150,
			'value' => array(
				__( 'Left', 'jupiter-donut' ) => 'left',
				__( 'Right', 'jupiter-donut' ) => 'right',
				__( 'Center', 'jupiter-donut' ) => 'center',
				__( 'None', 'jupiter-donut' ) => 'none',
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Full width button?', 'jupiter-donut' ),
			'param_name' => 'fullwidth',
			'value' => 'false',
			'description' => __( 'Using this option you can make the button full width and cover one row.', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Custom Button Width', 'jupiter-donut' ),
			'param_name' => 'button_custom_width',
			'value' => '0',
			'min' => '0',
			'max' => '1500',
			'step' => '1',
			'unit' => 'px',
			'dependency' => array(
				'element' => 'fullwidth',
				'value' => array(
					'false',
				),
			),
		) ,
		array(
			'type' => 'textfield',
			'heading' => __( 'Button ID', 'jupiter-donut' ),
			'param_name' => 'id',
			'value' => '',
			'description' => __( 'If your want to use id for this button to refer it in your custom JS, fill this textbox.', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Margin Top', 'jupiter-donut' ),
			'param_name' => 'margin_top',
			'value' => '0',
			'min' => '-30',
			'max' => '500',
			'step' => '1',
			'unit' => 'px',
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Margin Bottom', 'jupiter-donut' ),
			'param_name' => 'margin_bottom',
			'value' => '15',
			'min' => '-30',
			'max' => '500',
			'step' => '1',
			'unit' => 'px',
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Margin Right', 'jupiter-donut' ),
			'param_name' => 'margin_right',
			'value' => '15',
			'min' => '0',
			'max' => '50',
			'step' => '1',
			'unit' => 'px',
		) ,
		$add_css_animations,
		$add_device_visibility,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'jupiter-donut' ),
			'param_name' => 'el_class',
			'value' => '',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Skin', 'jupiter-donut' ),
			'param_name' => 'outline_skin',
			'value' => array(
				'Dark Color' => 'dark',
				'Light Color' => 'light',
				'Custom Color' => 'custom',
			),
			'dependency' => array(
				'element' => 'dimension',
				'value' => array(
					'outline',
					'savvy',
					'double-outline',
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Button Background Color', 'jupiter-donut' ),
			'param_name' => 'outline_active_color',
			'value' => '',
			'description' => __( 'The background and border color of button', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'outline_skin',
				'value' => array(
					'custom'
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Button Text Color', 'jupiter-donut' ),
			'param_name' => 'outline_active_text_color',
			'value' => '',
			'description' => __( 'The text color of button', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'outline_skin',
				'value' => array(
					'custom'
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Button Hover Background Color', 'jupiter-donut' ),
			'param_name' => 'outline_hover_bg_color',
			'value' => '',
			'description' => __( 'The background color when hovered on button', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'outline_skin',
				'value' => array(
					'custom'
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Button Hover Text Color', 'jupiter-donut' ),
			'param_name' => 'outline_hover_color',
			'value' => '',
			'description' => __( 'The text color when hovered on button', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'outline_skin',
				'value' => array(
					'custom'
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Button Background Color', 'jupiter-donut' ),
			'param_name' => 'bg_color',
			'value' => '',
			'dependency' => array(
				'element' => 'dimension',
				'value' => array(
					'two',
					'three',
					'flat',
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Button Background Color (Hover)', 'jupiter-donut' ),
			'param_name' => 'btn_hover_bg',
			'value' => '',
			'description' => __( 'Please note that this option is only for Flat style', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'dimension',
				'value' => array(
					'flat'
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Text Color', 'jupiter-donut' ),
			'param_name' => 'text_color',
			'value' => array(
				'Light' => 'light',
				'Dark' => 'dark',
			),
			'dependency' => array(
				'element' => 'dimension',
				'value' => array(
					'two',
					'three',
					'flat',
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Button Text Color (Hover)', 'jupiter-donut' ),
			'param_name' => 'btn_hover_txt_color',
			'value' => '',
			'dependency' => array(
				'element' => 'dimension',
				'value' => array(
					'flat'
				),
			),
			'group' => __( 'Colors', 'jupiter-donut' ),
		) ,
	),
));
