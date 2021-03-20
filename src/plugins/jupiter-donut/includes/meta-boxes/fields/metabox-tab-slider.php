<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
$config = array(
	'title' => sprintf( '%s Tab Slider Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-notab',
	'pages' => array(
		'tab_slider',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);

$options = array(

	array(
		'name' => __( 'Tab Icon', 'jupiter-donut' ),
		'desc' => sprintf( __( '%sClick here%s to get the icon class name', 'jupiter-donut' ), "<a target='_blank' href='" . jupiter_donut_get_icon_library_url() . "'>", '</a>' ),
		'id' => '_menu_icon',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Tab Title', 'jupiter-donut' ),
		'desc' => __( 'This text will be used in tab section. If left blank the above icon will be used as tab text.', 'jupiter-donut' ),
		'id' => '_menu_text',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Content Title', 'jupiter-donut' ),
		'desc' => __( 'This text will be used as tab title', 'jupiter-donut' ),
		'id' => '_title',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Theme Skin Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_skin_color',
		'default' => 'dark',
		'options' => array(
			'dark' => __( 'Dark', 'jupiter-donut' ),
			'light' => __( 'Light', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Content Background Color', 'jupiter-donut' ),
		'desc' => __( 'You can use solid color in tab slider background.', 'jupiter-donut' ),
		'id' => '_bg_color',
		'default' => '',
		'type' => 'color',
	),
	array(
		'name' => __( 'Image Align', 'jupiter-donut' ),
		'desc' => __( 'Location of tab image.', 'jupiter-donut' ),
		'id' => '_image_align',
		'default' => 'left',
		'options' => array(
			'left' => __( 'Left', 'jupiter-donut' ),
			'right' => __( 'Right', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Short Description', 'jupiter-donut' ),
		'subtitle' => __( '', 'jupiter-donut' ),
		'id' => '_desc',
		'default' => '',
		'type' => 'textarea',
	),
	array(
		'name' => __( 'Button Text', 'jupiter-donut' ),
		'desc' => __( 'This text will be used as tab button text.', 'jupiter-donut' ),
		'id' => '_button_text',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Button Url', 'jupiter-donut' ),
		'desc' => __( 'Please enter full URL of this url(include http://).', 'jupiter-donut' ),
		'id' => '_button_url',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Enable Share Button?', 'jupiter-donut' ),
		'desc' => __( 'If you enable this option you can add share button.', 'jupiter-donut' ),
		'id' => '_share_button',
		'default' => 'false',
		'type' => 'toggle',
	),
);
new mkMetaboxesGenerator( $config, $options );
