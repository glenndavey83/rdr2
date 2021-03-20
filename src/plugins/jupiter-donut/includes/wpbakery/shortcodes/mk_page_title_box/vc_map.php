<?php

vc_map(array(
	'name' => __( 'Page Title Box', 'jupiter-donut' ),
	'base' => 'mk_page_title_box',
	'html_template' => dirname( __FILE__ ) . '/mk_page_title_box.php',
	'icon' => 'icon-mk-animated-columns vc_mk_element-icon',
	'description' => __( 'Page title area with effects.', 'jupiter-donut' ),
	'category' => __( 'General', 'jupiter-donut' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Enter Page Title', 'jupiter-donut' ),
			'param_name' => 'page_title',
			'value' => '',
			'description' => __( 'Enter the title of your page and adjust font settings below', 'jupiter-donut' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Enter Page Subtitle', 'jupiter-donut' ),
			'param_name' => 'page_subtitle',
			'value' => '',
			'description' => __( 'Enter the subtitle of your page and adjust font settings below', 'jupiter-donut' ),
		),
		array(
			'type' => 'range',
			'heading' => __( 'Section Height', 'jupiter-donut' ),
			'param_name' => 'section_height',
			'value' => '400',
			'min' => '0',
			'max' => '1000',
			'step' => '1',
			'unit' => 'px',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Background Type', 'jupiter-donut' ),
			'param_name' => 'bg_type',
			'value' => array(
				__( 'Image', 'jupiter-donut' ) => 'image',
				__( 'Video', 'jupiter-donut' ) => 'video',
				__( 'Color', 'jupiter-donut' ) => 'color',
			),
		),
		array(
			'type' => 'upload',
			'heading' => __( 'Background Video (.MP4)', 'jupiter-donut' ),
			'param_name' => 'mp4',
			'value' => '',
			'description' => __( 'Upload your video with .MP4 extension. (Compatibility for Safari and IE9)', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'video'
				),
			),
		),
		array(
			'type' => 'upload',
			'heading' => __( 'Background Video (.WebM)', 'jupiter-donut' ),
			'param_name' => 'webm',
			'value' => '',
			'description' => __( 'Upload your video with .WebM extension. (Compatibility for Firefox4, Opera, and Chrome)', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'video'
				),
			),
		),
		array(
			'type' => 'upload',
			'heading' => __( 'Background Video (.OGV)', 'jupiter-donut' ),
			'param_name' => 'ogv',
			'value' => '',
			'description' => __( 'Upload your video with .OGV extension. (Compatibility for Firefox4, Opera, and Chrome)', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'video'
				),
			),
		) ,
		array(
			'type' => 'upload',
			'heading' => __( 'Video Preview Image', 'jupiter-donut' ),
			'param_name' => 'video_preview',
			'value' => '',
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'video'
				),
			),
		),
		array(
			'type' => 'upload',
			'heading' => __( 'Background Image', 'jupiter-donut' ),
			'param_name' => 'bg_image',
			'value' => '',
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'image'
				),
			),
		),
		array(
			'type' => 'upload',
			'heading' => __( 'Background Image (Portrait)', 'jupiter-donut' ),
			'param_name' => 'bg_image_portrait',
			'value' => '',
			'description' => __( 'Alternatively, this image could be shown in mobile devices with portrait orientation. It is recommended to use images with portrait ratio such as 2:3.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'image'
				),
			),
		),
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Background color', 'jupiter-donut' ),
			'param_name' => 'bg_color',
			'value' => '',
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'color'
				),
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Background Position', 'jupiter-donut' ),
			'param_name' => 'bg_position',
			'width' => 300,
			'value' => array(
				__( 'Left Top', 'jupiter-donut' ) => 'left top',
				__( 'Center Top', 'jupiter-donut' ) => 'center top',
				__( 'Right Top', 'jupiter-donut' ) => 'right top',
				__( 'Left Center', 'jupiter-donut' ) => 'left center',
				__( 'Center Center', 'jupiter-donut' ) => 'center center',
				__( 'Right Center', 'jupiter-donut' ) => 'right center',
				__( 'Left Bottom', 'jupiter-donut' ) => 'left bottom',
				__( 'Center Bottom', 'jupiter-donut' ) => 'center bottom',
				__( 'Right Bottom', 'jupiter-donut' ) => 'right bottom',
			),
			'description' => __( 'First value defines horizontal position and second vertical position.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'image'
				),
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Background Repeat', 'jupiter-donut' ),
			'param_name' => 'bg_repeat',
			'width' => 300,
			'value' => array(
				__( 'Repeat X', 'jupiter-donut' ) => 'repeat-x',
				__( 'Repeat Y', 'jupiter-donut' ) => 'repeat-y',
				__( 'Repeat', 'jupiter-donut' ) => 'repeat',
				__( 'Space', 'jupiter-donut' ) => 'space',
				__( 'Round', 'jupiter-donut' ) => 'round',
				__( 'No repeat', 'jupiter-donut' ) => 'no-repeat',
			),
			'std' => 'repeat',
			'description' => __( 'Read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/background-repeat" target="_blank">Background Repeat</a> documentation.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'image'
				),
			),
		),
		array(
			'type' => 'toggle',
			'heading' => __( 'Cover whole background', 'jupiter-donut' ),
			'description' => __( 'Scale the background image to be as large as possible so that the background area is completely covered by the background image. Some parts of the background image may not be in view within the background positioning area.', 'jupiter-donut' ),
			'param_name' => 'bg_stretch',
			'value' => 'false',
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'image'
				),
			),
		),
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Color Overlay', 'jupiter-donut' ),
			'param_name' => 'overlay',
			'value' => '',
			'description' => __( 'The overlay layer Color. You will need to change the alpha using this color picker to give an opacity to the color you choose.', 'jupiter-donut' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Background Effects', 'jupiter-donut' ),
			'param_name' => 'bg_effects',
			'width' => 300,
			'value' => array(
				__( '-- No Effect', 'jupiter-donut' ) => '',
				__( 'Parallax', 'jupiter-donut' ) => 'parallax',
				__( 'Parallax Zoom Out', 'jupiter-donut' ) => 'parallaxZoomOut',
				__( 'Gradient Fade In', 'jupiter-donut' ) => 'gradient',
			),
			'description' => __( 'Choose effects for your page title. Please note that Smooth Scroll option should be enabled for this feature function correctly. Smooth Scroll option is loctated in Theme Options > General Settings > Site Settings.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_type',
				'value' => array(
					'image',
					'video',
				),
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Background Attachment', 'jupiter-donut' ),
			'param_name' => 'attachment',
			'width' => 150,
			'value' => array(
				__( 'Scroll', 'jupiter-donut' ) => 'scroll',
				__( 'Fixed', 'jupiter-donut' ) => 'fixed',
			),
			'description' => __( 'This option sets whether the background image is fixed or scrolls with the rest of the page. <a href="http://www.w3schools.com/CSSref/pr_background-attachment.asp">Read More</a>', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'bg_effects',
				'value' => array(
					'',
				),
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Text Align', 'jupiter-donut' ),
			'param_name' => 'text_align',
			'width' => 150,
			'value' => array(
				__( 'Center', 'jupiter-donut' ) => 'center',
				__( 'Left', 'jupiter-donut' ) => 'left',
				__( 'Right', 'jupiter-donut' ) => 'right',
			),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Title Font Size', 'jupiter-donut' ),
			'param_name' => 'font_size',
			'min' => '10',
			'max' => '100',
			'step' => '1',
			'unit' => 'px',
			'value' => '50',
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Title Force Responsive Font Size', 'jupiter-donut' ),
			'param_name' => 'title_force_font_size',
			'value' => 'false',
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Font Size for Small Desktops', 'jupiter-donut' ),
			'param_name' => 'title_size_smallscreen',
			'value' => '0',
			'min' => '0',
			'max' => '70',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'For screens smaller than 1280px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'title_force_font_size',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Font Size for Tablet', 'jupiter-donut' ),
			'param_name' => 'title_size_tablet',
			'value' => '0',
			'min' => '0',
			'max' => '70',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'For screens between 768 and 1024px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'title_force_font_size',
				'value' => array(
					'true'
				),
			),
		),
		array(
			'type' => 'range',
			'heading' => __( 'Font Size for Mobile', 'jupiter-donut' ),
			'param_name' => 'title_size_phone',
			'value' => '0',
			'min' => '0',
			'max' => '70',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'For screens smaller than 768px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'title_force_font_size',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Title Letter Spacing', 'jupiter-donut' ),
			'param_name' => 'title_letter_spacing',
			'min' => '1',
			'max' => '50',
			'step' => '1',
			'unit' => 'px',
			'value' => '3',
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Title Color', 'jupiter-donut' ),
			'param_name' => 'font_color',
			'value' => '#ddd',
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Title Font Weight', 'jupiter-donut' ),
			'param_name' => 'font_weight',
			'value' => $font_weight,
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Title Underline', 'jupiter-donut' ),
			'param_name' => 'underline',
			'value' => 'true',
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Title Padding', 'jupiter-donut' ),
			'param_name' => 'padding',
			'min' => '10',
			'max' => '50',
			'step' => '1',
			'unit' => 'px',
			'value' => '20',
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Subtitle Font Size', 'jupiter-donut' ),
			'param_name' => 'sub_font_size',
			'min' => '10',
			'max' => '100',
			'step' => '1',
			'unit' => 'px',
			'value' => '30',
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Subtitle Force Responsive Font Size', 'jupiter-donut' ),
			'param_name' => 'subtitle_force_font_size',
			'value' => 'false',
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Font Size for Small Desktops', 'jupiter-donut' ),
			'param_name' => 'subtitle_size_smallscreen',
			'value' => '0',
			'min' => '0',
			'max' => '70',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'For screens smaller than 1280px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'subtitle_force_font_size',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Font Size for Tablet', 'jupiter-donut' ),
			'param_name' => 'subtitle_size_tablet',
			'value' => '0',
			'min' => '0',
			'max' => '70',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'For screens between 768 and 1024px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'subtitle_force_font_size',
				'value' => array(
					'true'
				),
			),
		),
		array(
			'type' => 'range',
			'heading' => __( 'Font Size for Mobile', 'jupiter-donut' ),
			'param_name' => 'subtitle_size_phone',
			'value' => '0',
			'min' => '0',
			'max' => '70',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'For screens smaller than 768px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'subtitle_force_font_size',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Subtitle Color', 'jupiter-donut' ),
			'param_name' => 'sub_font_color',
			'value' => '',
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Subtitle Font Weight', 'jupiter-donut' ),
			'param_name' => 'sub_font_weight',
			'value' => $font_weight,
		) ,
		$add_device_visibility,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'jupiter-donut' ),
			'param_name' => 'el_class',
			'value' => '',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'jupiter-donut' ),
		),
	),
) );
