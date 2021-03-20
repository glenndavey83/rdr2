<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */

$config = array(
	'title'    => sprintf( '%s Page Options', 'Jupiter' ),
	'id'       => 'mk-metaboxes-general',
	'pages'    => array(
		'employees',
	),
	'callback' => '',
	'context'  => 'normal',
	'priority' => 'core',
);

$options = array(
	array(
		'name'        => __( 'Main Navigation Location', 'jupiter-donut' ),
		'desc'        => sprintf(
			__( 'Choose which menu location to be used in this page. If left blank, Primary Menu will be used. You should first %1$screate menu%2$s and then %3$s assign to menu locations%4$s', 'jupiter-donut' ),
			"<a target='_blank' href='" . admin_url( 'nav-menus.php' ) . "'>",
			'</a>',
			"<a target='_blank' href='" . admin_url( 'nav-menus.php' ) . "?action=locations'>",
			'</a>'
		),
		'id' => '_menu_location',
		'default'     => '',
		'placeholder' => 'true',
		'width'       => 400,
		'options' => array(
			'primary-menu' => __( 'Primary Navigation', 'jupiter-donut' ),
			'second-menu'  => __( 'Second Navigation',  'jupiter-donut' ),
			'third-menu'   => __( 'Third Navigation',   'jupiter-donut' ),
			'fourth-menu'  => __( 'Fourth Navigation',  'jupiter-donut' ),
			'fifth-menu'   => __( 'Fifth Navigation',   'jupiter-donut' ),
			'sixth-menu'   => __( 'Sixth Navigation',   'jupiter-donut' ),
		),
		'type'        => 'select',
	),
);

new mkMetaboxesGenerator( $config, $options );

$config = array(
	'title' => sprintf( '%s Employees Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-notab',
	'pages' => array(
		'employees',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(
	array(
		'name' => __( 'Single Employee Page?', 'jupiter-donut' ),
		'desc' => __( 'If you enable this option, This employee member will have a single post so you can add extra content in above editor.', 'jupiter-donut' ),
		'id' => '_single_post',
		'default' => 'false',
		'options' => array(
			'false' => __( 'No', 'jupiter-donut' ),
			'true' => __( 'Yes please', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Single Post Layout', 'jupiter-donut' ),
		'desc' => __( 'Choose single post layout style.', 'jupiter-donut' ),
		'id' => '_employees_single_layout',
		'default' => 'style1',
		'preview' => false,
		'options' => array(
			'style1' => __( 'Style 1', 'jupiter-donut' ),
			'style2' => __( 'Style 2', 'jupiter-donut' ),
			'style3' => __( 'Style 3', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_single_post',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Header Hero Background Image', 'jupiter-donut' ),
		'desc' => __( 'Upload an image for single post > style 3 layout > header hero background image. Best image size for this field is 1920px * 550px.  (Specific to style 3)', 'jupiter-donut' ),
		'id' => '_header_hero_bg_image',
		'default' => '',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_single_post',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Header Hero Background Color', 'jupiter-donut' ),
		'desc' => __( 'choose a color for single post > style 3 layout > header hero background color. (Specific to style 3)', 'jupiter-donut' ),
		'id' => '_header_hero_bg_color',
		'default' => '#636667',
		'type' => 'color',
		'dependency' => array(
			'element' => '_single_post',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Header Hero Content Skin', 'jupiter-donut' ),
		'desc' => __( 'Specific to style 3', 'jupiter-donut' ),
		'id' => '_header_hero_skin',
		'default' => 'light',
		'preview' => false,
		'options' => array(
			'light' => __( 'Light', 'jupiter-donut' ),
			'dark' => __( 'Dark', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Link to a URL', 'jupiter-donut' ),
		'desc' => __( 'Optionally you can add URL to this memeber such as a detailed page. Please note that this option will only work when you dont enable single employee page in above option.', 'jupiter-donut' ),
		'id' => '_permalink',
		'default' => '',
		'type' => 'superlink',
		'dependency' => array(
			'element' => '_single_post',
			'value' => array(
				'false',
			),
		),
	),

	array(
		'name' => __( 'Employee Position', 'jupiter-donut' ),
		'desc' => __( "Please enter team member's Position in the company.", 'jupiter-donut' ),
		'id' => '_position',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'About Member', 'jupiter-donut' ),
		'desc' => __( 'This text will be shown in employees loop. To show content in single employee, you should add your content into above WP editor.', 'jupiter-donut' ),
		'id' => '_desc',
		'default' => '',
		'type' => 'editor',
	),

	array(
		'name' => __( 'Email Address', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_email',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Social Network (Facebook)', 'jupiter-donut' ),
		'desc' => __( 'Please enter full URL of this social network(include http://).', 'jupiter-donut' ),
		'id' => '_facebook',
		'default' => '',
		'type' => 'text',
	),

	array(
		'name' => __( 'Social Network (Twitter)', 'jupiter-donut' ),
		'desc' => __( 'Please enter full URL of this social network(include http://).', 'jupiter-donut' ),
		'id' => '_twitter',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Social Network (Google Plus)', 'jupiter-donut' ),
		'desc' => __( 'Please enter full URL of this social network(include http://).', 'jupiter-donut' ),
		'id' => '_googleplus',
		'default' => '',
		'type' => 'text',
	),

	array(
		'name' => __( 'Social Network (Linked In)', 'jupiter-donut' ),
		'desc' => __( 'Please enter full URL of this social network(include http://).', 'jupiter-donut' ),
		'id' => '_linkedin',
		'default' => '',
		'type' => 'text',
	),

	array(
		'name' => __( 'Social Network (Instagram)', 'jupiter-donut' ),
		'desc' => __( 'Please enter full URL of this social network(include http://).', 'jupiter-donut' ),
		'id' => '_instagram',
		'default' => '',
		'type' => 'text',
	),

	array(
		'desc' => __( 'Please Use the featured image metabox to upload your employee photo and then assign to the post.', 'jupiter-donut' ),
		'type' => 'info',
	),
);
new mkMetaboxesGenerator( $config, $options );
