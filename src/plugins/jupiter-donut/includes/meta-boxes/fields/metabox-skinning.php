<?php
/**
 * Metabox: Metabox for Page/Post meta style.
 *
 * @package Jupiter
 * @subpackage Metaboxes
 */

if ( jupiter_donut_is_jupiterx() ) {
	return;
}

$config = array(
	'title' => sprintf( '%s Styling Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-styling',
	'pages' => array(
		'page',
		'portfolio',
		'post',
		'news',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'default',
);

$options = array(

	array(
		'name' => __( 'Override Global Settings', 'jupiter-donut' ),
		'desc' => __( 'You should enable this option if you want to override global background values defined in Theme Options.', 'jupiter-donut' ),
		'id' => '_enable_local_backgrounds',
		'default' => 'false',
		'type' => 'toggle',
	),

	array(
		'name' => __( 'Header', 'jupiter-donut' ),
		// translators: %s for HB admin page.
		'desc' => sprintf( __( 'Choose a Header from the list to replace with Global header in this page. All headers built by header builder are listerd here. %s', 'jupiter-donut' ), '<a id="_hb_header_builder_link" target="_blank" href="' . admin_url( 'admin.php?page=header-builder' ) . '">Launch Header Builder</a>.' ),
		'id' => '_hb_override_template_id',
		'default' => 'default',
		'options' => mkhb_get_header_list(),
		'type' => 'select',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Header Styles', 'jupiter-donut' ),
		'desc' => __( 'Using this option you can choose your header style, elements align and toggle off/on header toolbar.', 'jupiter-donut' ),
		'id' => 'theme_header_style',
		'default' => '1',
		'type' => 'header_styles',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'id' => 'theme_header_align',
		'default' => 'left',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'theme_toolbar_toggle',
		'default' => 'true',
		'type' => 'hidden_input',
	),

	array(
		'name' => __( 'Upload Logo (Dark & default)', 'jupiter-donut' ),
		'desc' => sprintf( __( 'This logo will be used when transparent header is enabled and your header skin is dark. <a href="%s" target="_blank">Learn more</a>', 'jupiter-donut' ), 'https://themes.artbees.net/docs/configuring-logos/' ),
		'id' => 'logo',
		'default' => '',
		'width' => 'logo_width',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'id' => 'logo_width',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'name' => __( 'Upload Logo (Light Skin)', 'jupiter-donut' ),
		'desc' => sprintf( __( 'This logo will be used when transparent header is enabled and your header is light skin. <a href="%s" target="_blank">Learn more</a>', 'jupiter-donut' ), 'https://themes.artbees.net/docs/configuring-logos/' ),
		'id' => 'light_logo',
		'default' => '',
		'width' => 'light_logo_width',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'id' => 'light_logo_width',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'name' => __( 'Upload Logo (Header Sticky State)', 'jupiter-donut' ),
		'desc' => sprintf( __( 'Use this option upload the sticky header logo. <a href="%s" target="_blank">Learn more</a>', 'jupiter-donut' ), 'https://themes.artbees.net/docs/configuring-logos/' ),
		'id' => 'sticky_header_logo',
		'default' => '',
		'width' => 'sticky_header_logo_width',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'id' => 'sticky_header_logo_width',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'name' => __( 'Upload Logo (Mobile Version)', 'jupiter-donut' ),
		'desc' => sprintf( __( 'Use this option to change your logo for mobile devices if your logo width is quite long to fit in mobile device screen. <a href="%s" target="_blank">Learn more</a>', 'jupiter-donut' ), 'https://themes.artbees.net/docs/configuring-logos/' ),
		'id' => 'responsive_logo',
		'default' => '',
		'width' => 'responsive_logo_width',
		'type' => 'upload',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'id' => 'responsive_logo_width',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'name' => __( 'Transparent Header', 'jupiter-donut' ),
		'desc' => __( 'You can Enable/Disable transparent header capability using this option.', 'jupiter-donut' ),
		'id' => '_transparent_header',
		'default' => 'false',
		'type' => 'toggle',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'name' => __( 'Enable Transparent Header Skin', 'jupiter-donut' ),
		'desc' => __( 'If this option is enabled, transparent header background will be removed, main navigation as well as other header elements will be controlled by below option. Edge Slider and Edge One Pager shortcodes slides will also be able to control header skin. If disabled none of these will be applied to header background and its elements.', 'jupiter-donut' ),
		'id' => '_trans_header_remove_bg',
		'default' => 'true',
		'type' => 'toggle',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'name' => __( 'Transparent header Skin', 'jupiter-donut' ),
		'desc' => __( 'Use this option to decide about the skin of transparent header.', 'jupiter-donut' ),
		'id' => '_transparent_header_skin',
		'default' => 'light',
		'options' => array(
			'light' => __( 'Light', 'jupiter-donut' ),
			'dark' => __( 'Dark', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Transparent Header Bottom Border Color', 'jupiter-donut' ),
		'desc' => __( 'This border will appear in the bottom of the transparent header. Please note that this options has nothing to do with "header bottom border" and "Header Border Bottom Color" and this border will only appear in transparent header (will disappear in sticky header).', 'jupiter-donut' ),
		'id' => '_trans_header_border_bottom',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
	array(
		'name' => __( 'Sticky Header Offset', 'jupiter-donut' ),
		'desc' => __( 'Set this option to decide when the sticky state of header will trigger. This option does not apply to header style No2.', 'jupiter-donut' ),
		'id' => '_sticky_header_offset',
		'default' => 'header',
		'options' => array(
			'header' => __( 'Header height', 'jupiter-donut' ),
			'25%' => __( '25% Of Viewport', 'jupiter-donut' ),
			'30%' => __( '30% Of Viewport', 'jupiter-donut' ),
			'40%' => __( '40% Of Viewport', 'jupiter-donut' ),
			'50%' => __( '50% Of Viewport', 'jupiter-donut' ),
			'60%' => __( '60% Of Viewport', 'jupiter-donut' ),
			'70%' => __( '70% Of Viewport', 'jupiter-donut' ),
			'80%' => __( '80% Of Viewport', 'jupiter-donut' ),
			'90%' => __( '90% Of Viewport', 'jupiter-donut' ),
			'100%' => __( '100% Of Viewport', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Choose between boxed and full width layout', 'jupiter-donut' ),
		'desc' => __( "Choose between a full or a boxed layout to set how your website's layout will look like.", 'jupiter-donut' ),
		'id' => 'background_selector_orientation',
		'default' => 'full_width_layout',
		'options' => array(
			'boxed_layout' => 'boxed-layout',
			'full_width_layout' => 'full-width-layout',
		),
		'type' => 'visual_selector',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Boxed Layout Outer Shadow Size', 'jupiter-donut' ),
		'desc' => __( 'You can have a outer shadow around the box. using this option you in can modify its range size', 'jupiter-donut' ),
		'id' => 'boxed_layout_shadow_size',
		'default' => '0',
		'min' => '0',
		'max' => '60',
		'step' => '1',
		'unit' => 'px',
		'type' => 'range',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Boxed Layout Outer Shadow Intensity', 'jupiter-donut' ),
		'desc' => __( 'determines how darker the shadow to be.', 'jupiter-donut' ),
		'id' => 'boxed_layout_shadow_intensity',
		'default' => '0',
		'min' => '0',
		'max' => '1',
		'step' => '0.01',
		'unit' => 'alpha',
		'type' => 'range',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Background color & texture', 'jupiter-donut' ),
		'desc' => __( 'Please click on the different sections to modify their backgrounds.', 'jupiter-donut' ),
		'id' => 'general_backgounds',
		'type' => 'general_background_selector',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'id' => 'body_color',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'body_color_gradient',
		'default' => 'single',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'body_color_2',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'body_color_gradient_style',
		'default' => 'linear',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'body_color_gradient_angle',
		'default' => 'vertical',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'body_image',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'body_size',
		'default' => 'false',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'body_position',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'body_attachment',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'body_repeat',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'body_source',
		'default' => 'no-image',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'body_parallax',
		'default' => 'false',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'page_color',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'page_color_gradient',
		'default' => 'single',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'page_color_2',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'page_color_gradient_style',
		'default' => 'linear',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'page_color_gradient_angle',
		'default' => 'vertical',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'page_image',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'page_size',
		'default' => 'false',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'page_position',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'page_attachment',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'page_repeat',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'page_source',
		'default' => 'no-image',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'page_parallax',
		'default' => 'false',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'header_color',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'header_color_gradient',
		'default' => 'single',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'header_color_2',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'header_color_gradient_style',
		'default' => 'linear',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'header_color_gradient_angle',
		'default' => 'vertical',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'header_size',
		'default' => 'false',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'header_image',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'header_position',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'header_attachment',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'header_repeat',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'header_source',
		'default' => 'no-image',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'header_parallax',
		'default' => 'false',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'banner_color',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'banner_color_gradient',
		'default' => 'single',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'banner_color_2',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'banner_color_gradient_style',
		'default' => 'linear',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'banner_color_gradient_angle',
		'default' => 'vertical',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'banner_image',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'banner_size',
		'default' => 'false',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'banner_position',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'banner_attachment',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'banner_repeat',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'banner_source',
		'default' => 'no-image',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'banner_parallax',
		'default' => 'false',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'footer_color',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'footer_color_gradient',
		'default' => 'single',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'footer_color_2',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'footer_color_gradient_style',
		'default' => 'linear',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'footer_color_gradient_angle',
		'default' => 'vertical',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'footer_image',
		'default' => '',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'footer_size',
		'default' => 'false',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'footer_position',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'footer_attachment',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'footer_repeat',
		'default' => '',
		'type' => 'hidden_input',
	),

	array(
		'id' => 'footer_source',
		'default' => 'no-image',
		'type' => 'hidden_input',
	),
	array(
		'id' => 'footer_parallax',
		'default' => 'false',
		'type' => 'hidden_input',
	),

	array(
		'name' => __( 'Page Title Color', 'jupiter-donut' ),
		'desc' => __( 'You can set the page title text color here.', 'jupiter-donut' ),
		'id' => '_page_title_color',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Page Subtitle Color', 'jupiter-donut' ),
		'desc' => __( 'You can set the page subtitle text color here.', 'jupiter-donut' ),
		'id' => '_page_subtitle_color',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Breadcrumb Skin', 'jupiter-donut' ),
		'desc' => __( 'You can set breadcrumbs skin for dark or light backgrounds.', 'jupiter-donut' ),
		'id' => '_breadcrumb_skin',
		'default' => '',
		'options' => array(
			'light' => __( 'For Light Background', 'jupiter-donut' ),
			'dark' => __( 'For Dark Background', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),

	array(
		'name' => __( 'Header Border Bottom Color', 'jupiter-donut' ),
		'desc' => __( 'You can set the color of bottom border of banner section.', 'jupiter-donut' ),
		'id' => '_banner_border_color',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_enable_local_backgrounds',
			'value' => array(
				'true',
			),
		),
	),
);
new mkMetaboxesGenerator( $config, $options );
