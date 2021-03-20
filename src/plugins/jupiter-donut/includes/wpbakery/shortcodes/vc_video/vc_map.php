<?php
vc_map(array(
	'name' => __( 'Video Player', 'jupiter-donut' ),
	'base' => 'vc_video',
	'html_template' => dirname( __FILE__ ) . '/vc_video.php',
	'icon' => 'icon-mk-video-player vc_mk_element-icon',
	'description' => __( 'Youtube, Vimeo,..', 'jupiter-donut' ),
	'category' => __( 'Social', 'jupiter-donut' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Shortcode Title', 'jupiter-donut' ),
			'param_name' => 'title',
			'value' => '',
			'description' => __( '', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Video Max Width', 'jupiter-donut' ),
			'param_name' => 'max_width',
			'value' => '0',
			'min' => '0',
			'max' => '2000',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'If set zero, the video will expand to the parent container width', 'jupiter-donut' ),
		) ,
		array(
			'heading' => __( 'Video Type', 'jupiter-donut' ),
			'description' => __( '', 'jupiter-donut' ),
			'param_name' => 'host',
			'value' => array(
				__( 'Social Hosted (Youtube, Vimeo,..)', 'jupiter-donut' ) => 'social_hosted',
				__( 'Self Hosted', 'jupiter-donut' ) => 'self_hosted',
			),
			'type' => 'dropdown',
		) ,
		array(
			'type' => 'upload',
			'heading' => __( 'MP4 Format', 'jupiter-donut' ),
			'param_name' => 'mp4',
			'value' => '',
			'description' => __( 'Compatibility for Safari, IE10', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'upload',
			'heading' => __( 'WebM Format', 'jupiter-donut' ),
			'param_name' => 'webm',
			'value' => '',
			'description' => __( 'Compatibility for Firefox4, Opera, and Chrome', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'upload',
			'heading' => __( 'Video Preview image (and fallback image)', 'jupiter-donut' ),
			'param_name' => 'poster_image',
			'value' => '',
			'description' => __( 'This Image will shown until video load. in case of video is not supported or did not load the image will remain as fallback.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'textfield',
			'heading' => __( 'Video link', 'jupiter-donut' ),
			'param_name' => 'link',
			'value' => '',
			'description' => __( 'Link to the video. For YouTube HD videos add this snippet at the of a link "&vq=1080" (video quality set to 1080p). More about supported formats at <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'social_hosted'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Autoplay, Loop, Controls Visibility', 'jupiter-donut' ),
			'param_name' => 'autoplay',
			'value' => 'false',
			'description' => __( 'To start playing the video automatically, turn on &quot;Autoplay&quot;.', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Loop', 'jupiter-donut' ),
			'param_name' => 'loop',
			'value' => 'false',
			'description' => __( 'To start playing the video from beginning after video ends, turn on &quot;Loop&quot;.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Muted', 'jupiter-donut' ),
			'param_name' => 'muted',
			'value' => 'false',
			'description' => __( 'To set whether the video should be muted.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array(
					'true'
				),
			),
		),
		array(
			'type' => 'toggle',
			'heading' => __( 'Hide Controls', 'jupiter-donut' ),
			'param_name' => 'hide_controls',
			'value' => 'false',
			'description' => __( 'To hide the video control buttons and progress bar, turn on &quot;Hide Controls&quot;. This option will work only with self hosted videos.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Custom thumbnail and Lightbox', 'jupiter-donut' ),
			'param_name' => 'custom_thumbnail',
			'value' => 'false',
			'description' => __( 'Use your own image and playback icon for the video thumbnail and play videos inside a lightbox.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array(
					'false'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Aspect Ratio', 'jupiter-donut' ),
			'param_name' => 'aspect_ratio',
			'value' => 'false',
			'description' => __( 'To keep the video aspect ratio, turn on &quot;Aspect Ratio&quot;.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'attach_image',
			'heading' => __( 'Thumbnail image', 'jupiter-donut' ),
			'param_name' => 'thumbnail_image',
			'description' => __( 'The background image which covers the thumbnail.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'icon_selector',
			'heading' => __( 'Playback Icon', 'jupiter-donut' ),
			'param_name' => 'play_icon',
			'value' => 'mk-icon-play',
			'description' => __( 'This icon will appear in the center of thumbnail.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Playback Icon Size', 'jupiter-donut' ),
			'param_name' => 'play_icon_size',
			'value' => array(
				__( '32px', 'jupiter-donut' ) => '32',
				__( '64px', 'jupiter-donut' ) => '64',
				__( '128px', 'jupiter-donut' ) => '128',
				__( '256px', 'jupiter-donut' ) => '256',
			),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Playback Icon Color', 'jupiter-donut' ),
			'param_name' => 'play_icon_color',
			'value' => '#ffffff',
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Playback Icon Hover Animation', 'jupiter-donut' ),
			'param_name' => 'play_icon_animation',
			'value' => array(
				__( 'Fade In', 'jupiter-donut' ) => 'fade-in',
				__( 'Scale Up', 'jupiter-donut' ) => 'scale-up',
				__( 'None', 'jupiter-donut' ) => 'none',
			),
			'default' => 'fade-in',
			'description' => __( 'What happens when userhovers the mouse pointer above this video thumbnail.', 'jupiter-donut' ),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Open this video in', 'jupiter-donut' ),
			'param_name' => 'play_target',
			'default' => 'lightbox',
			'value' => array(
				__( 'Lightbox', 'jupiter-donut' ) => 'lightbox',
				__( 'Same place', 'jupiter-donut' ) => 'same',
			),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		$add_css_animations,
		$add_device_visibility,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'jupiter-donut' ),
			'param_name' => 'el_class',
			'value' => '',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.', 'jupiter-donut' ),
		),
	),
));
