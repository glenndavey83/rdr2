<?php

if ( jupiter_donut_is_jupiterx() ) {
	return;
}

$config  = array(
	'title' => sprintf( '%s Widget Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-widgets',
	'pages' => array(
		'page',
		'portfolio',
		'news',
		'post',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'default',
);
$options = array(
	array(
		'name' => __( 'Footer Widget Area - First Column', 'jupiter-donut' ),
		'desc' => __( 'Choose which widget area you would like to show in this column for this post/page', 'jupiter-donut' ),
		'id' => '_widget_first_col',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
	),
	array(
		'name' => __( 'Footer Widget Area - Second Column', 'jupiter-donut' ),
		'desc' => __( 'Choose which widget area you would like to show in this column for this post/page', 'jupiter-donut' ),
		'id' => '_widget_second_col',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
	),
	array(
		'name' => __( 'Footer Widget Area - Third Column', 'jupiter-donut' ),
		'desc' => __( 'Choose which widget area you would like to show in this column for this post/page', 'jupiter-donut' ),
		'id' => '_widget_third_col',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
	),
	array(
		'name' => __( 'Footer Widget Area - Fourth Column', 'jupiter-donut' ),
		'desc' => __( 'Choose which widget area you would like to show in this column for this post/page', 'jupiter-donut' ),
		'id' => '_widget_fourth_col',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
	),
	array(
		'name' => __( 'Footer Widget Area - Fifth Column', 'jupiter-donut' ),
		'desc' => __( 'Choose which widget area you would like to show in this column for this post/page', 'jupiter-donut' ),
		'id' => '_widget_fifth_col',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
	),

	array(
		'name' => __( 'Footer Widget Area - Sixth Column', 'jupiter-donut' ),
		'desc' => __( 'Choose which widget area you would like to show in this column for this post/page', 'jupiter-donut' ),
		'id' => '_widget_sixth_col',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
	),



);
new mkMetaboxesGenerator( $config, $options );
