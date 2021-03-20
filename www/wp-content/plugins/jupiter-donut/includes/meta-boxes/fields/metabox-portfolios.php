<?php

if ( jupiter_donut_is_jupiterx() ) {
	return;
}

$config = array(
	'title' => sprintf( '%s Page Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-general',
	'pages' => array(
		'portfolio',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(
	array(
		'name' => __( 'Layout', 'jupiter-donut' ),
		'desc' => __( "Please choose this page's layout. If you choose 'Default' then you may modify it from Theme Settings => Portfolio => Portfolio Single Post => Portfolio Single Layout.", 'jupiter-donut' ),
		'id' => '_layout',
		'default' => 'default',
		'options' => array(
			'default' => __( 'Default Sidebar', 'jupiter-donut' ),
			'left' => __( 'Left Sidebar', 'jupiter-donut' ),
			'right' => __( 'Right Sidebar', 'jupiter-donut' ),
			'full' => __( 'Full Layout', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Manage Page Elements', 'jupiter-donut' ),
		'desc' => __( "Depending on your need you can change this page's general layout by making structral changes.", 'jupiter-donut' ),
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
		'desc' => __( 'Enabling this option will remove padding after header and before footer. If you would like to enable it for pages throughout the site, consider setting the option in Theme Options => Portfolio => Portfolio Single Post.', 'jupiter-donut' ),
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
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Custom Sidebar', 'jupiter-donut' ),
		'desc' => __( 'You can create a custom sidebar, under Sidebar option and then assign the custom sidebar here to this post. later on you can customize which widgets to show up.', 'jupiter-donut' ),
		'id' => '_sidebar',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
	),
);
new mkMetaboxesGenerator( $config, $options );
$config = array(
	'title' => sprintf( '%s Portfolio Post Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-posts-options',
	'pages' => array(
		'portfolio',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(
	array(
		'name' => __( 'Custom URL', 'jupiter-donut' ),
		'desc' => __( 'If you may choose to change the permalink to a page, post or external URL. If left empty the single post permalink will be used instead.', 'jupiter-donut' ),
		'id' => '_portfolio_permalink',
		'default' => '',
		'type' => 'superlink',
	),
	array(
		'name' => __( 'Post Type', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_single_post_type',
		'default' => 'image',
		'preview' => false,
		'options' => array(
			'image' => 'Image',
			'video' => 'Video',
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Video Site', 'jupiter-donut' ),
		'id' => '_single_video_site',
		'default' => 'youtube',
		'options' => array(
			'vimeo' => 'Vimeo',
			'youtube' => 'Youtube',
			'dailymotion' => 'Daily Motion',
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'video',
			),
		),
	),

	array(
		'name' => __( 'Video Id', 'jupiter-donut' ),
		'desc' => __( 'Please fill this option with the required ID. you can find the ID from the link parameters as examplified below:<br /> http://www.youtube.com/watch?v=<strong>ID</strong><br />https://vimeo.com/<strong>ID</strong><br />http://www.dailymotion.com/embed/video/<strong>ID</strong>', 'jupiter-donut' ),
		'id' => '_single_video_id',
		'type' => 'text',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'video',
			),
		),
	),

	array(
		'name' => __( 'Masonry Image size', 'jupiter-donut' ),
		'desc' => __( 'Make your hand picked images larger, wider or taller. Masonry loop style image size. First value represents horizontal wideness, second value how tall the item is. X * X is regular item width and height (will occupy one fifth of a column).', 'jupiter-donut' ),
		'id' => '_masonry_img_size',
		'default' => 'x_x',
		'options' => array(
			'x_x' => __( 'X * X', 'jupiter-donut' ),
			'two_x_x' => __( '2X * X', 'jupiter-donut' ),
			'three_x_x' => __( '3X * X', 'jupiter-donut' ),
			'four_x_x' => __( '4X * X', 'jupiter-donut' ),
			'x_two_x' => __( 'X * 2X', 'jupiter-donut' ),
			'two_x_two_x' => __( '2X * 2X', 'jupiter-donut' ),
			'three_x_two_x' => __( '3X * 2X', 'jupiter-donut' ),
			'four_x_two_x' => __( '4X * 2X', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Item Hover Skin', 'jupiter-donut' ),
		'desc' => __( 'Using this option you can modify this portfolio item hover skin color in Grid & Masonry loop style > Zoom Out Box hover scenario.', 'jupiter-donut' ),
		'id' => '_hover_skin',
		'default' => '',
		'type' => 'color',
	),

	array(
		'name' => __( 'Show Featured Image', 'jupiter-donut' ),
		'desc' => __( 'If you do not want to set a featured image inside single portfolio kindly disable it here. If you post type is video, featured video player will be disabled.', 'jupiter-donut' ),
		'id' => '_portfolio_featured_image',
		'default' => 'true',
		'type' => 'toggle',
	),

	array(
		'name' => __( 'Similiar Posts', 'jupiter-donut' ),
		'desc' => __( 'If you do not want to show similar posts section inside single portfolio kindly disable it here.', 'jupiter-donut' ),
		'id' => '_portfolio_similar',
		'default' => 'true',
		'type' => 'toggle',
	),

	array(
		'name' => __( 'Social Share', 'jupiter-donut' ),
		'desc' => __( 'If you do not want to show Social share feature int this post disable this option.', 'jupiter-donut' ),
		'id' => '_portfolio_social',
		'default' => 'true',
		'type' => 'toggle',
	),

	array(
		'name' => __( 'Portfolio Ajax Content', 'jupiter-donut' ),
		'desc' => __( 'This content will be used in ajax portfolio and if left blank the main content above will be used instead. Please note that ajax content will not accept fullwidth rows or page sections. So if you are using these in single portfolio main content then use this field for ajax content.', 'jupiter-donut' ),
		'id' => '_ajax_content',
		'default' => '',
		'type' => 'editor',
	),
);
new mkMetaboxesGenerator( $config, $options );
