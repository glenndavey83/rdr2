<?php
$config  = array(
	'title' => sprintf( '%s Slideshow Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-slideshow',
	'pages' => array(
		'slideshow',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(

	array(
		'name' => __( 'Caption Title', 'jupiter-donut' ),
		'id' => '_title',
		'default' => '',
		'type' => 'text',
	),

	array(
		'name' => __( 'Caption Description', 'jupiter-donut' ),
		'id' => '_description',
		'default' => '',
		'type' => 'textarea',
	),


	array(
		'name' => __( 'Link To (optional)', 'jupiter-donut' ),
		'desc' => __( 'The url that the slider item links to.', 'jupiter-donut' ),
		'id' => '_link_to',
		'default' => '',
		'type' => 'superlink',
	),




);
new mkMetaboxesGenerator( $config, $options );
