<?php
$config  = array(
	'title' => sprintf( '%s Timeline Options', 'Jupiter' ),
	'id' => 'mk-timeline-meta',
	'pages' => array(
		'timeline',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);

$options = array(
	array(
		'name' => __( 'Link to URL (Optional)', 'jupiter-donut' ),
		'desc' => __( 'Fill this field with a link includinge http://', 'jupiter-donut' ),
		'id' => '_link',
		'default' => '',
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
	array(
		'name' => __( 'Add Icon Class Name', 'jupiter-donut' ),
		'desc' => sprintf( __( '%sClick here%s to get the icon class name', 'jupiter-donut' ), "<a target='_blank' href='" . jupiter_donut_get_icon_library_url() . "'>", '</a>' ),
		'id' => '_icon',
		'default' => '',
		'type' => 'text',
	),
);
new mkMetaboxesGenerator( $config, $options );
