<?php

if ( jupiter_donut_is_jupiterx() ) {
	return;
}

$config = array(
	'title' => sprintf( '%s Page Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-general',
	'pages' => array(
		'post',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(
	array(
		'name' => __( 'Layout', 'jupiter-donut' ),
		'desc' => __( "Please choose the layout of this page. If you choose 'Default' then you may modify it from Theme Settings => Blog/News => Blog Single Post => Single Layout.", 'jupiter-donut' ),
		'id' => '_layout',
		'default' => 'default',
		'options' => array(
			'default' => __( 'Default', 'jupiter-donut' ),
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
		'desc' => __( 'Enabling this option will remove padding after header and before footer.', 'jupiter-donut' ),
		'id' => '_padding',
		'default' => 'false',
		'type' => 'toggle',
	),

	array(
		'name' => __( 'Page Preloader?', 'jupiter-donut' ),
		'desc' => __( 'This option will enable preloader for this post only. If you would like to enable it throughout the site consider setting the option in General => Site Preloader => Preloader.', 'jupiter-donut' ),
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
			__( 'Choose which menu location to be used in this page. If left blank, Primary Menu will be used. You should first %1$screate menu%2$s and then %3$sassign to menu locations%4$s', 'jupiter-donut' ),
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
		'desc' => __( 'You can create a custom sidebar, under Sidebar option and then assign the custom sidebar here to this post. later on you can customize which widgets to show up. <a target="blank" href="https://themes.artbees.net/video/jupiter-widgets-custom-sidebars/">CLICK HERE</a> for more information.', 'jupiter-donut' ),
		'id' => '_sidebar',
		'default' => '',
		'options' => mk_get_sidebar_options(),
		'type' => 'select',
		'dependency' => array(
			'element' => '_layout',
			'value' => array(
				'left',
				'right',
			),
		),
	),
);
new mkMetaboxesGenerator( $config, $options );

$config = array(
	'title' => sprintf( '%s Posts Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-blog',
	'pages' => array(
		'post',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(

	array(
		'name' => __( 'Single Post Style', 'jupiter-donut' ),
		'desc' => __( 'Please choose a single post style.', 'jupiter-donut' ),
		'id' => '_single_blog_style',
		'default' => 'default',
		'options' => array(
			'default' => __( 'Default (set in Theme Options)', 'jupiter-donut' ),
			'compact' => __( 'Traditional & Compact', 'jupiter-donut' ),
			'bold' => __( 'Clear & Bold', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Post Format', 'jupiter-donut' ),
		'desc' => __( 'You can set the post format using this option.', 'jupiter-donut' ),
		'id' => '_single_post_type',
		'default' => 'image',
		'preview' => false,
		'options' => array(
			'image' => __( 'Image', 'jupiter-donut' ),
			'video' => __( 'Video', 'jupiter-donut' ),
			'audio' => __( 'Audio', 'jupiter-donut' ),
			'portfolio' => __( 'Portfolio', 'jupiter-donut' ),
			'twitter' => __( 'Twitter', 'jupiter-donut' ),
			'blockquote' => __( 'Blockquote', 'jupiter-donut' ),
			'instagram' => __( 'Instagram', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Gallery Images', 'jupiter-donut' ),
		'desc' => __( 'You can re-arrange images by drag and drop as well as deleting images.', 'jupiter-donut' ),
		'id' => '_gallery_images',
		'default' => '',
		'type' => 'gallery',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'portfolio',
			),
		),
	),

	array(
		'name' => __( 'Classic Loops Style Orientation', 'jupiter-donut' ),
		'desc' => __( 'This option allows you to choose how the blog loop item be displayed. This option only applies to Classic style.', 'jupiter-donut' ),
		'id' => '_classic_orientation',
		'default' => 'landscape',
		'options' => array(
			'landscape' => 'Landscape',
			'portraite' => 'Portrait',
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'image',
			),
		),
	),

	array(
		'name' => __( 'Upload MP3 File', 'jupiter-donut' ),
		'desc' => __( 'Upload MP3 your file or paste the full URL for external files. This file formated needed for Safari, Internet Explorer, Chrome. ', 'jupiter-donut' ),
		'id' => '_mp3_file',
		'preview' => false,
		'default' => '',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'audio',
			),
		),
	),

	array(
		'name' => __( 'Upload OGG File', 'jupiter-donut' ),
		'desc' => __( 'Upload OGG your file or paste the full URL for external files. This file formated needed for Firefox, Opera, Chrome. ', 'jupiter-donut' ),
		'id' => '_ogg_file',
		'preview' => false,
		'default' => '',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'audio',
			),
		),
	),
	array(
		'name' => __( 'Sound Track Author', 'jupiter-donut' ),
		'desc' => __( 'Fill this Field If you would like to state the author of the audio file you are about to post.', 'jupiter-donut' ),
		'id' => '_single_audio_author',
		'type' => 'text',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'audio',
			),
		),
	),
	array(
		'name' => __( 'Soundcloud & Spotify', 'jupiter-donut' ),
		'desc' => __( 'Paste embed iframe or Wordpress shortcode. Please note that using this option will ignore above options related to hosted audio player.', 'jupiter-donut' ),
		'subtitle' => __( '', 'jupiter-donut' ),
		'id' => '_audio_iframe',
		'default' => '',
		'type' => 'textarea',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'audio',
			),
		),
	),

	array(
		'name' => __( 'The URL of tweet', 'jupiter-donut' ),
		'desc' => __( 'Sample url should look like : https://twitter.com/artbees_design/status/643400273923309568.', 'jupiter-donut' ),
		'subtitle' => __( '', 'jupiter-donut' ),
		'id' => '_tweet_oembed',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'twitter',
			),
		),
	),

	array(
		'name' => __( 'Blockquote', 'jupiter-donut' ),
		'desc' => __( 'Paste the quote content in below textarea.', 'jupiter-donut' ),
		'subtitle' => __( '', 'jupiter-donut' ),
		'id' => '_blockquote_content',
		'default' => '',
		'type' => 'textarea',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'blockquote',
			),
		),
	),
	array(
		'name' => __( 'Blockquote Author', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'subtitle' => __( '', 'jupiter-donut' ),
		'id' => '_blockquote_author',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'blockquote',
			),
		),
	),

	array(
		'name' => __( 'Instagram Media Url', 'jupiter-donut' ),
		'desc' => __( 'Sample url should look like : https://instagram.com/p/7vDM4JvdPj/', 'jupiter-donut' ),
		'subtitle' => __( '', 'jupiter-donut' ),
		'id' => '_instagram_url',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_single_post_type',
			'value' => array(
				'instagram',
			),
		),
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
		'name' => __( 'Featured Image', 'jupiter-donut' ),
		'desc' => __( 'This option will disable post featured image, video, audio and gallery (portfolio Post Format).', 'jupiter-donut' ),
		'id' => '_disable_featured_image',
		'default' => 'true',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'Meta Section', 'jupiter-donut' ),
		'desc' => __( 'Using this option you can show/hide extra information about the blog or post, such as Author, Date Created, etc...', 'jupiter-donut' ),
		'id' => '_disable_meta',
		'default' => 'true',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'Tags', 'jupiter-donut' ),
		'desc' => __( 'Using this option you can Show/Hide tags in blogs.', 'jupiter-donut' ),
		'id' => '_disable_tags',
		'default' => 'true',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'Related Posts', 'jupiter-donut' ),
		'desc' => __( 'If you do not want to show related posts disable the post here', 'jupiter-donut' ),
		'id' => '_disable_related_posts',
		'default' => 'true',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'About Author Box', 'jupiter-donut' ),
		'desc' => __( 'Disable the about author box here', 'jupiter-donut' ),
		'id' => '_disable_about_author',
		'default' => 'true',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'Author Email', 'jupiter-donut' ),
		'desc' => __( 'Using this option you can turn on/off author email in about author box.', 'jupiter-donut' ),
		'id' => '_show_author_email',
		'default' => 'true',
		'type' => 'toggle',
	),
	array(
		'name' => __( 'Comments', 'jupiter-donut' ),
		'desc' => __( 'Disable comments section for this post.', 'jupiter-donut' ),
		'id' => '_disable_comments',
		'default' => 'true',
		'type' => 'toggle',
	),
);
new mkMetaboxesGenerator( $config, $options );
