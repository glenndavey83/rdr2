<?php
$config = array(
	'title' => __( 'Add New Slider', 'jupiter-donut' ),
	'id' => 'mk-metaboxes-edge',
	'pages' => array(
		'edge',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);
$options = array(

	array(
		'name' => __( 'Content Animation', 'jupiter-donut' ),
		'subtitle' => __( 'The type animation for this slide content', 'jupiter-donut' ),
		'desc' => __( 'Using this option you can define specific animations for the content of this slider. This option will affect custom content that you create from above WP editor or the built-in captions and buttons.', 'jupiter-donut' ),
		'id' => '_animation',
		'default' => '',
		'options' => array(
			'' => __( 'Default', 'jupiter-donut' ),
			'fade-in' => __( 'Fade in', 'jupiter-donut' ),
			'slide-top' => __( 'Slide from Top', 'jupiter-donut' ),
			'slide-left' => __( 'Slide from Left', 'jupiter-donut' ),
			'slide-bottom' => __( 'Slide from Bottom', 'jupiter-donut' ),
			'slide-right' => __( 'Slide from Right', 'jupiter-donut' ),
			'scale-down' => __( 'Scale Down', 'jupiter-donut' ),
			'flip-x' => __( 'Horizontally Flip', 'jupiter-donut' ),
			'flip-y' => __( 'Vertically Flip', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Slider Type', 'jupiter-donut' ),
		'desc' => __( 'Do you want to have video or Image for this slide item?', 'jupiter-donut' ),
		'id' => '_edge_type',
		'default' => 'image',
		'options' => array(
			'image' => __( 'Image', 'jupiter-donut' ),
			'video' => __( 'Video', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Upload Video (MP4 format)', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_video_mp4',
		'default' => '',
		'preview' => false,
		'type' => 'upload',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'video',
			),
		),
	),

	array(
		'name' => __( 'Upload Video (WebM format)', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_video_webm',
		'default' => '',
		'preview' => false,
		'type' => 'upload',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'video',
			),
		),
	),

	array(
		'name' => __( 'Upload Video (OGV format)', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_video_ogv',
		'default' => '',
		'preview' => false,
		'type' => 'upload',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'video',
			),
		),
	),
	array(
		'name' => __( 'Upload Video Preview Image', 'jupiter-donut' ),
		'desc' => __( 'This Image will be shown until the video load.', 'jupiter-donut' ),
		'id' => '_video_preview',
		'default' => '',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'video',
			),
		),
	),

	array(
		'name' => __( 'Upload Image', 'jupiter-donut' ),
		'desc' => __( 'Upload slideshow image. Image will fit to the container size however for better quality in all browsers recommded size is 1920px * 1080px.', 'jupiter-donut' ),
		'id' => '_slide_image',
		'default' => '',
		'preview' => true,
		'type' => 'upload',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'image',
			),
		),
	),

	array(
		'name' => __( 'Upload Portrait Image', 'jupiter-donut' ),
		'desc' => __( 'Alternatively, this image could be shown in mobile devices with portrait orientation. It is recommended to use images with portrait ratio such as 2:3.', 'jupiter-donut' ),
		'id' => '_slide_image_portrait',
		'default' => '',
		'preview' => true,
		'type' => 'upload',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'image',
			),
		),
	),

	array(
		'name' => __( 'Cover whole background', 'jupiter-donut' ),
		'subtitle' => __( 'This option is only when image is uploaded.', 'jupiter-donut' ),
		'desc' => __( 'Scale the background image to be as large as possible so that the background area is completely covered by the background image. Some parts of the background image may not be in view within the background positioning area.', 'jupiter-donut' ),
		'id' => '_cover',
		'default' => 'true',
		'type' => 'toggle',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'image',
			),
		),
	),

	array(
		'name' => __( 'background Color', 'jupiter-donut' ),
		'desc' => __( 'You can use solid color in slideshow instead of image', 'jupiter-donut' ),
		'id' => '_bg_color',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_edge_type',
			'value' => array(
				'image',
			),
		),
	),

	array(
		'name' => __( 'Show Pattern Mask', 'jupiter-donut' ),
		'desc' => __( 'If you enable this option a pattern will overlay the video or image.', 'jupiter-donut' ),
		'id' => '_video_pattern',
		'default' => 'false',
		'type' => 'toggle',
	),

	array(
		'name' => __( 'Color Overlay', 'jupiter-donut' ),
		'id' => '_video_color_overlay',
		'default' => '',
		'type' => 'color',
	),
	array(
		'name' => __( 'Color Overlay Opacity', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_overlay_opacity',
		'default' => '0.3',
		'min' => '0',
		'max' => '1',
		'step' => '0.1',
		'unit' => 'alpha',
		'type' => 'range',
	),

	array(
		'type' => 'select',
		'name' => __( 'Gradient Overlay Orientation', 'jupiter-donut' ),
		'id' => '_gradient_layer',
		'default' => 'false',
		'options' => array(
			'false' => __( '-- No Gradient ↓', 'jupiter-donut' ),
			'vertical' => __( 'Vertical ', 'jupiter-donut' ),
			'horizontal' => __( 'Horizontal →', 'jupiter-donut' ),
			'left_top' => __( 'Diagonal ↘', 'jupiter-donut' ),
			'left_bottom' => __( 'Diagonal ↗', 'jupiter-donut' ),
			'radial' => __( 'Radial ○', 'jupiter-donut' ),
		),
		'desc' => __( 'Choose the orientation of gradient overlay', 'jupiter-donut' ),
	),

	array(
		'type' => 'color',
		'name' => __( 'Gradient Layer Color Start', 'jupiter-donut' ),
		'id' => '_gr_start',
		'default' => '',
		'description' => __( 'The ending color for gradient fill overlay. Use only with gradient option selected.', 'jupiter-donut' ),
		'dependency' => array(
			'element' => '_gradient_layer',
			'value' => array(
				'vertical',
				'horizontal',
				'left_top',
				'left_bottom',
				'radial',
			),
		),
	),
	array(
		'type' => 'color',
		'name' => __( 'Gradient Layer Color End', 'jupiter-donut' ),
		'id' => '_gr_end',
		'default' => '',
		'description' => __( 'The ending color for gradient fill overlay. Use only with gradient option selected.', 'jupiter-donut' ),
		'dependency' => array(
			'element' => '_gradient_layer',
			'value' => array(
				'vertical',
				'horizontal',
				'left_top',
				'left_bottom',
				'radial',
			),
		),
	),

	array(
		'name' => __( 'Content Align', 'jupiter-donut' ),
		'desc' => __( 'Location of caption and buttons. Please note that if you add content via Visual Composer into this post, this option will only control the location of the container inside the main grid. So module-based horizontal alignments should be taken care inside the shortcode options.', 'jupiter-donut' ),
		'id' => '_caption_align',
		'default' => 'left_center',
		'options' => array(
			'left_top' => __( 'Left Top', 'jupiter-donut' ),
			'center_top' => __( 'Center Top', 'jupiter-donut' ),
			'right_top' => __( 'Right Top', 'jupiter-donut' ),
			'left_center' => __( 'Left Center', 'jupiter-donut' ),
			'center_center' => __( 'Center Center', 'jupiter-donut' ),
			'right_center' => __( 'Right Center', 'jupiter-donut' ),
			'left_bottom' => __( 'Left Bottom', 'jupiter-donut' ),
			'center_bottom' => __( 'Center Bottom', 'jupiter-donut' ),
			'right_bottom' => __( 'Right Bottom', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Content Width', 'jupiter-donut' ),
		'desc' => __( 'You can define the content width based on percent. Please note that this width will be defined percent width of main grid. default : 70%', 'jupiter-donut' ),
		'id' => '_content_width',
		'default' => '70',
		'min' => '0',
		'max' => '100',
		'step' => '1',
		'unit' => '%',
		'type' => 'range',
	),
	array(
		'name' => __( 'Caption Title', 'jupiter-donut' ),
		'id' => '_title',
		'default' => '',
		'rows' => '3',
		'type' => 'textarea',
	),

	array(
		'name' => __( 'Caption Description', 'jupiter-donut' ),
		'id' => '_description',
		'default' => '',
		'rows' => '3',
		'type' => 'textarea',
	),
	array(
		'name' => __( 'Caption Title Font Size', 'jupiter-donut' ),
		'subtitle' => __( 'Default : 50', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_title_size',
		'default' => '50',
		'min' => '12',
		'max' => '200',
		'step' => '1',
		'unit' => 'px',
		'type' => 'range',
	),
	array(
		'name' => __( 'Caption Title Letter Spacing', 'jupiter-donut' ),
		'subtitle' => __( 'Default : 0', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_title_letter_spacing',
		'default' => '0',
		'min' => '0',
		'max' => '20',
		'step' => '1',
		'unit' => 'px',
		'type' => 'range',
	),
	array(
		'name' => __( 'Caption Title Font Weight', 'jupiter-donut' ),
		'subtitle' => __( '', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_caption_title_weight',
		'default' => '300',
		'options' => array(
			'inherit' => __( 'Default', 'jupiter-donut' ),
			'600' => __( 'Semi Bold', 'jupiter-donut' ),
			'bold' => __( 'Bold', 'jupiter-donut' ),
			'bolder' => __( 'Bolder', 'jupiter-donut' ),
			'normal' => __( 'Normal', 'jupiter-donut' ),
			'300' => __( 'Light', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Caption Skin', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_caption_skin',
		'default' => 'light',
		'options' => array(
			'light' => __( 'Light', 'jupiter-donut' ),
			'dark' => __( 'Dark', 'jupiter-donut' ),
			'custom' => __( 'Custom Color (Change from below option)', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Custom Caption Text Color', 'jupiter-donut' ),
		'subtitle' => __( 'This option will only work when you choose custom from "Caption Skin" option above.', 'jupiter-donut' ),
		'desc' => __( 'This option will affect both caption title & description.', 'jupiter-donut' ),
		'id' => '_custom_caption_color',
		'default' => '',
		'type' => 'color',
	),

	array(
		'name' => __( 'Button 1 Style', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_1_style',
		'default' => 'outline',
		'options' => array(
			'outline' => __( 'Outline', 'jupiter-donut' ),
			'flat' => __( 'Flat', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Button 1 Corner Style', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_1_corner_style',
		'default' => 'pointed',
		'options' => array(
			'pointed' => __( 'Pointed', 'jupiter-donut' ),
			'rounded' => __( 'Rounded', 'jupiter-donut' ),
			'full_rounded' => __( 'Full Rounded', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Button 1 Skin', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_1_skin',
		'default' => '',
		'options' => array(
			'dark' => __( 'Dark', 'jupiter-donut' ),
			'light' => __( 'Light', 'jupiter-donut' ),
			'skin' => __( 'Theme Skin Color', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Button 1 Text', 'jupiter-donut' ),
		'id' => '_btn_1_txt',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Button 1 URL', 'jupiter-donut' ),
		'id' => '_btn_1_url',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Button 1 Target', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_1_target',
		'default' => '_self',
		'options' => array(
			'_self' => __( 'Same Window', 'jupiter-donut' ),
			'_blank' => __( 'New Window', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Button 2 Style', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_2_style',
		'default' => 'outline',
		'options' => array(
			'outline' => __( 'Outline', 'jupiter-donut' ),
			'flat' => __( 'Flat', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Button 2 Corner Style', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_2_corner_style',
		'default' => 'pointed',
		'options' => array(
			'pointed' => __( 'Pointed', 'jupiter-donut' ),
			'rounded' => __( 'Rounded', 'jupiter-donut' ),
			'full_rounded' => __( 'Full Rounded', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Button 2 Skin', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_2_skin',
		'default' => '',
		'options' => array(
			'dark' => __( 'Dark', 'jupiter-donut' ),
			'light' => __( 'Light', 'jupiter-donut' ),
			'skin' => __( 'Theme Skin Color', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Button 2 Text', 'jupiter-donut' ),
		'id' => '_btn_2_txt',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Button 2 URL', 'jupiter-donut' ),
		'id' => '_btn_2_url',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Button 2 Target', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_2_target',
		'default' => '_self',
		'options' => array(
			'_self' => __( 'Same Window', 'jupiter-donut' ),
			'_blank' => __( 'New Window', 'jupiter-donut' ),
		),
		'type' => 'select',
	),

	array(
		'name' => __( 'Transparent Header Style Skin for this Slide', 'jupiter-donut' ),
		'subtitle' => __( 'If this slide image or video is dark color then you should choose light otherwise dark.', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_edge_header_skin',
		'default' => 'dark',
		'options' => array(
			'dark' => __( 'Dark', 'jupiter-donut' ),
			'light' => __( 'Light', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
);
new mkMetaboxesGenerator( $config, $options );
