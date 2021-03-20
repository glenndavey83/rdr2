<?php
$config  = array(
	'title' => sprintf( '%s News Post Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-news',
	'pages' => array(
		'news',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(

	array(
		'name' => __( 'Post Style', 'jupiter-donut' ),
		'desc' => __( 'Please choose post style how they will look in news post loop.', 'jupiter-donut' ),
		'id' => '_news_post_style',
		'default' => 'full-with-image',
		'preview' => false,
		'options' => array(
			'full-with-image' => __( 'Full With Image', 'jupiter-donut' ),
			'full-without-image' => __( 'Full Without Image', 'jupiter-donut' ),
			'half-with-image' => __( 'Half With Image', 'jupiter-donut' ),
			'half-without-image' => __( 'Half Without Image', 'jupiter-donut' ),
			'fourth-with-image' => __( 'One Fourth With Image', 'jupiter-donut' ),
			'fourth-without-image' => __( 'One Fourth Without Image', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

);
new mkMetaboxesGenerator( $config, $options );
