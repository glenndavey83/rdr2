<?php
$config  = array(
	'title' => sprintf( '%s Animated Columns Options', 'Jupiter' ),
	'id' => 'mk-animated-column-meta',
	'pages' => array(
		'animated-columns',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);

$options = array(
	array(
		'name' => __( 'Icon Type', 'jupiter-donut' ),
		'desc' => __( 'Choose whether you want to upload your own image (as icon) or a font icon?', 'jupiter-donut' ),
		'id' => '_icon_type',
		'default' => 'icon',
		'options' => array(
			'icon' => __( 'Font Icon', 'jupiter-donut' ),
			'image' => __( 'Upload Image', 'jupiter-donut' ),

		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Add Icon Class Name', 'jupiter-donut' ),
		'desc' => sprintf( __( '%sClick here%s to get the icon class name', 'jupiter-donut' ), "<a target='_blank' href='" . jupiter_donut_get_icon_library_url() . "'>", '</a>' ),
		'id' => '_icon',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_icon_type',
			'value' => array(
				'icon',
			),
		),
	),
	array(
		'name' => __( 'Upload Image', 'jupiter-donut' ),
		'desc' => __( 'This image will be scaled down to the size you choose in animated column shortcode options.', 'jupiter-donut' ),
		'id' => '_image_icon',
		'default' => '',
		'preview' => false,
		'type' => 'upload',
		'dependency' => array(
			'element' => '_icon_type',
			'value' => array(
				'image',
			),
		),
	),
	array(
		'name' => __( 'Column Title', 'jupiter-donut' ),
		'desc' => __( 'This text will be used as column title', 'jupiter-donut' ),
		'id' => '_title',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Columns Short Description', 'jupiter-donut' ),
		'id' => '_desc',
		'default' => '',
		'type' => 'textarea',
	),
	array(
		'name' => __( 'Button URL', 'jupiter-donut' ),
		'desc' => __( 'Fill this field with a link including http://', 'jupiter-donut' ),
		'id' => '_link',
		'default' => '',
		'type' => 'text',
	),

	array(
		'name' => __( 'Button Text', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_text',
		'default' => 'Learn More',
		'type' => 'text',
	),
	array(
		'name' => __( 'Button Target', 'jupiter-donut' ),
		'desc' => __( 'Please choose this column link target.', 'jupiter-donut' ),
		'id' => '_target',
		'default' => '_self',
		'options' => array(
			'_self' => __( 'Same window', 'jupiter-donut' ),
			'_blank' => __( 'New Window', 'jupiter-donut' ),

		),
		'type' => 'select',
	),
);
new mkMetaboxesGenerator( $config, $options );
