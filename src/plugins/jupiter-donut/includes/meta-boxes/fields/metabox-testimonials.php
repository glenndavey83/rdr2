<?php
$config  = array(
	'title' => sprintf( '%s Testimonials Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-notab',
	'pages' => array(
		'testimonial',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(
	array(
		'name' => __( 'Author Name', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_author',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Company Name / Job Title', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_company',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( "URL to Author's Website (optional)", 'jupiter-donut' ),
		'desc' => __( 'include http://', 'jupiter-donut' ),
		'id' => '_url',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Quote', 'jupiter-donut' ),
		'desc' => __( 'Please fill below area with the quote', 'jupiter-donut' ),
		'id' => '_desc',
		'default' => '',
		'type' => 'editor',
	),


);
new mkMetaboxesGenerator( $config, $options );
