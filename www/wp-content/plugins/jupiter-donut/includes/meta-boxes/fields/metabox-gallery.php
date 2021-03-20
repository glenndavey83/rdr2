<?php
$config  = array(
	'title' => sprintf( '%s Photo Album Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-gallery',
	'pages' => array(
		'photo_album',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);

$options = array(


	array(
		'name' => __( 'Gallery Images', 'jupiter-donut' ),
		'desc' => __( 'You can re-arrange images by drag and drop as well as deleting images.', 'jupiter-donut' ),
		'id' => '_gallery_images',
		'default' => '',
		'type' => 'gallery',
	),

	array(
		'name' => __( 'Short Description', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_desc',
		'default' => '',
		'type' => 'textarea',
	),


);
new mkMetaboxesGenerator( $config, $options );
