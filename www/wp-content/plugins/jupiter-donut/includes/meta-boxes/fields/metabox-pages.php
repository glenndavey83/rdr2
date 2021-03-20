<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */

if ( jupiter_donut_is_jupiterx() ) {
	return;
}

$config = array(
	'title' => sprintf( '%s Page Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-general',
	'pages' => array(
		'page',
		'news',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(
	array(
		'name' => __( 'Layout', 'jupiter-donut' ),
		'desc' => __( "Please choose this page's layout.", 'jupiter-donut' ),
		'id' => '_layout',
		'default' => '',
		'options' => array(
			'left' => __( 'Left Sidebar', 'jupiter-donut' ),
			'right' => __( 'Right Sidebar', 'jupiter-donut' ),
			'full' => __( 'Full Layout', 'jupiter-donut' ),
			'full-width' => __( 'Full Width Layout', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Manage Page Elements', 'jupiter-donut' ),
		'desc' => __( "Depending on your need you can change this page's general layout by making structural changes.", 'jupiter-donut' ),
		'id' => '_template',
		'default' => '',
		'options' => array(
			'no-header' => __( 'Remove Header', 'jupiter-donut' ),
			'no-title' => __( 'Remove Page Title', 'jupiter-donut' ),
			'no-header-title' => __( 'Remove Header & Page Title', 'jupiter-donut' ),
			'no-footer' => __( 'Remove Footer', 'jupiter-donut' ),
			'no-header-footer' => __( 'Remove Header & Footer', 'jupiter-donut' ),
			'no-footer-title' => __( 'Remove Footer & Page Title', 'jupiter-donut' ),
			'no-header-title-footer' => __( 'Remove Header & Page Title & Footer', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Stick Template?', 'jupiter-donut' ),
		'desc' => __( 'Enabling this option will remove padding after header and before footer. If you would like to enable it for pages throughout the site, consider setting the option in Theme Options => Main Content => Layout &amp; Backgrounds.', 'jupiter-donut' ),
		'id' => '_padding',
		'default' => 'false',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'Page Preloader?', 'jupiter-donut' ),
		'desc' => __( 'This option will enable preloader for this post only. if you would like to enable it throughout the site consider option in General => Site Preloader => Preloader.', 'jupiter-donut' ),
		'id' => 'page_preloader',
		'default' => 'false',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'Page Title Align', 'jupiter-donut' ),
		'desc' => __( 'You can change title and subtitle text align.', 'jupiter-donut' ),
		'id' => '_introduce_align',
		'default' => 'left',
		'options' => array(
			'left' => 'Left',
			'right' => 'Right',
			'center' => 'Center',
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Custom Page Title', 'jupiter-donut' ),
		'desc' => __( 'You can add a custom title for this page. (This option have NOTHING to do with title  &lt;title&gt; tag inside HTML.)', 'jupiter-donut' ),
		'id' => '_custom_page_title',
		'rows' => '1',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Page Heading Subtitle', 'jupiter-donut' ),
		'desc' => __( 'You can add a subtitle to header section of this page using this option.', 'jupiter-donut' ),
		'id' => '_page_introduce_subtitle',
		'rows' => '3',
		'default' => '',
		'type' => 'textarea',
	),
	array(
		'name' => __( 'Breadcrumb', 'jupiter-donut' ),
		'desc' => __( 'You can disable Breadcrumb for this page using this option', 'jupiter-donut' ),
		'id' => '_disable_breadcrumb',
		'default' => 'true',
		'type' => 'toggle',
	),

	array(
		'name' => __( 'Main Navigation Location', 'jupiter-donut' ),
		'desc' => sprintf(
			__( 'Choose which menu location to be used in this page. If left blank, Primary Menu will be used. You should first %1$screate menu%2$s and then %3$s assign to menu locations%4$s', 'jupiter-donut' ),
			"<a target='_blank' href='" . admin_url( 'nav-menus.php' ) . "'>",
			'</a>',
			"<a target='_blank' href='" . admin_url( 'nav-menus.php' ) . "?action=locations'>",
			'</a>'
		),
		'id' => '_menu_location',
		'default' => '',
		'placeholder' => 'true',
		'width' => 400,
		'options' => array(
			'primary-menu' => __( 'Primary Navigation', 'jupiter-donut' ),
			'second-menu' => __( 'Second Navigation', 'jupiter-donut' ),
			'third-menu' => __( 'Third Navigation', 'jupiter-donut' ),
			'fourth-menu' => __( 'Fourth Navigation', 'jupiter-donut' ),
			'fifth-menu' => __( 'Fifth Navigation', 'jupiter-donut' ),
			'sixth-menu' => __( 'Sixth Navigation', 'jupiter-donut' ),
			'seventh-menu' => __( 'Seventh Navigation', 'jupiter-donut' ),
			'eighth-menu' => __( 'Eighth Navigation', 'jupiter-donut' ),
			'ninth-menu' => __( 'Ninth Navigation', 'jupiter-donut' ),
			'tenth-menu' => __( 'Tenth Navigation', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Custom Sidebar', 'jupiter-donut' ),
		'desc' => __( 'You can create a custom sidebar, under Sidebar option and then assign the custom sidebar here to this post. later on you can customize which widgets to show up.', 'jupiter-donut' ),
		'id' => '_sidebar',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'dependency' => array(
			'element' => '_layout',
			'value' => array(
				'left',
				'right',
			),
		),
		'type' => 'select',
	),
);
new mkMetaboxesGenerator( $config, $options );
