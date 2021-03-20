<?php
vc_map(
	array(
		'name' => __( 'Contact Info', 'jupiter-donut' ),
		'base' => 'mk_contact_info',
		'html_template' => dirname( __FILE__ ) . '/mk_contact_info.php',
		'icon' => 'icon-mk-contact-info vc_mk_element-icon',
		'category' => __( 'Social', 'jupiter-donut' ),
		'description' => __( 'Adds Contact info details.', 'jupiter-donut' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'jupiter-donut' ),
				'param_name' => 'title',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Phone', 'jupiter-donut' ),
				'param_name' => 'phone',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Fax', 'jupiter-donut' ),
				'param_name' => 'fax',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Email', 'jupiter-donut' ),
				'param_name' => 'email',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Address', 'jupiter-donut' ),
				'param_name' => 'address',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Person', 'jupiter-donut' ),
				'param_name' => 'person',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Company', 'jupiter-donut' ),
				'param_name' => 'company',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Skype Username', 'jupiter-donut' ),
				'param_name' => 'skype',
				'value' => '',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Website', 'jupiter-donut' ),
				'param_name' => 'website',
				'value' => '',
			),
			$add_device_visibility,
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'jupiter-donut' ),
				'param_name' => 'el_class',
				'value' => '',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.', 'jupiter-donut' ),
			),
		),
	)
);
