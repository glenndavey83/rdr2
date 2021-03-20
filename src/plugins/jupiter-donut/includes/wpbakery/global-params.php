<?php

defined( 'ABSPATH' ) || die();

/**
 * Options params that are shared among most of the shortcodes.
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       5.1
 * @since       5.9.7 Add Parallax Scroll params.
 * @package     artbees
 */

$target_arr = array(
	__( 'Same window', 'jupiter-donut' ) => '_self',
	__( 'New window', 'jupiter-donut' ) => '_blank',
);

$font_weight = array(
	__( 'Default', 'jupiter-donut' ) => 'inherit',
	__( 'Lightest', 'jupiter-donut' ) => '100',
	__( 'Lighter', 'jupiter-donut' ) => '200',
	__( 'Light', 'jupiter-donut' ) => '300',
	__( 'Normal', 'jupiter-donut' ) => '400',
	__( 'Medium (500)', 'jupiter-donut' ) => '500',
	__( 'Semi-Bold (600)', 'jupiter-donut' ) => '600',
	__( 'Bold', 'jupiter-donut' ) => 'bold',
	__( 'Bolder', 'jupiter-donut' ) => 'bolder',
	__( 'Extra Bold', 'jupiter-donut' ) => '900',
);

$add_css_animations = array(
	'type' => 'dropdown',
	'heading' => __( 'Viewport Animation', 'jupiter-donut' ),
	'param_name' => 'animation',
	'value' => array(
		'None' => '',
		'Fade In' => 'fade-in',
		'Scale Up' => 'scale-up',
		'Right to Left' => 'right-to-left',
		'Left to Right' => 'left-to-right',
		'Bottom to Top' => 'bottom-to-top',
		'Top to Bottom' => 'top-to-bottom',
		'Flip Horizontally' => 'flip-x',
		'Flip Vertically' => 'flip-y',
	),
	'description' => __( 'Viewport animation will be triggered when this element is being viewed while you scroll page down. Choose the type of animation from this list. please note that this only works in moderns. This feature is disabled in touch devices to increase browsing speed.', 'jupiter-donut' ),
);
$add_device_visibility = array(
	'type' => 'dropdown',
	'heading' => __( 'Visibility For devices', 'jupiter-donut' ),
	'param_name' => 'visibility',
	'value' => array(
		'All' => '',
		'Hidden on Phones (Screens smaller than 765px of width)' => 'hidden-sm',
		'Hidden on Tablets (Screens in the range of 768px and 1024px)' => 'hidden-tl',
		'Hidden on Mega Tablets (Screens in the range of 768px and 1280px)' => 'hidden-tl-v2',
		'Hidden on Netbooks (Screens smaller than 1024px of width)' => 'hidden-nb',
		'Hidden on Desktops (Screens wider than 1224px of width)' => 'hidden-dt',
		'Hidden on Mega Desktops (Screens wider than 1290px of width)' => 'hidden-dt-v2',
		'Visible on Phones (Screens smaller than 765px of width)' => 'visible-sm',
		'Visible on Tablets (Screens in the range of 768px and 1024px)' => 'visible-tl',
		'Visible on Mega Tablets (Screens in the range of 768px and 1280px)' => 'visible-tl-v2',
		'Visible on Netbooks (Screens smaller than 1024px of width)' => 'visible-nb',
		'Visible on Desktops (Screens wider than 1224px of width)' => 'visible-dt',
		'Visible on Mega Desktops (Screens wider than 1290px of width)' => 'visible-dt-v2',
	),
	'description' => __( 'You can make this element invisible for a particular device (screen resolution) or set it to All to be visible for all devices.<br> Important : Device detection is based on <strong>Device Screen Width</strong> and we can not clearly define the sort of device whether its a tablet or small laptop. This option mostly helps to organise your content on smaller devices (e.g. remove large content for mobiles) and it does not specifically help you to determine the type of device.', 'jupiter-donut' ),
);
$mk_orderby = array(
	__( 'Date', 'jupiter-donut' ) => 'date',
	__( 'Menu Order', 'jupiter-donut' ) => 'menu_order',
	__( 'Posts In (manually selected posts)', 'jupiter-donut' ) => 'post__in',
	__( 'Post Id', 'jupiter-donut' ) => 'id',
	__( 'Title', 'jupiter-donut' ) => 'title',
	__( 'Comment Count', 'jupiter-donut' ) => 'comment_count',
	__( 'Random', 'jupiter-donut' ) => 'rand',
	__( 'Author', 'jupiter-donut' ) => 'author',
	__( 'No Order', 'jupiter-donut' ) => 'none',
);
$color_selection_style = array(
	'type' => 'dropdown',
	'heading' => __( 'Text Color Type', 'jupiter-donut' ),
	'param_name' => 'color_style',
	'default' => 'single_color',
	'value' => array(
		__( 'Single Color', 'jupiter-donut' ) => 'single_color',
		__( 'Gradient Color', 'jupiter-donut' ) => 'gradient_color',
	),
	'description' => __( '', 'jupiter-donut' ),
);
$color_selection_single_color = array(
	'type' => 'colorpicker',
	'heading' => __( 'Text Color', 'jupiter-donut' ),
	'param_name' => 'color',
	'value' => '',
	'description' => __( '', 'jupiter-donut' ),
	'dependency' => array(
		'element' => 'color_style',
		'value' => array(
			'single_color',
		),
	),
);
$color_selection_gradient_color_from  = array(
	'type' => 'colorpicker',
	'heading' => __( 'From', 'jupiter-donut' ),
	'param_name' => 'grandient_color_from',

	// "edit_field_class" => "vc_col-sm-3",
	'value' => '',
	'description' => __( '', 'jupiter-donut' ),
	'dependency' => array(
		'element' => 'color_style',
		'value' => array(
			'gradient_color',
		),
	),
);
$color_selection_gradient_color_to    = array(
	'type' => 'colorpicker',
	'heading' => __( 'To', 'jupiter-donut' ),
	'param_name' => 'grandient_color_to',

	// "edit_field_class" => "vc_col-sm-3",
	'value' => '',
	'description' => __( '', 'jupiter-donut' ),
	'dependency' => array(
		'element' => 'color_style',
		'value' => array(
			'gradient_color',
		),
	),
);
$color_selection_gradient_color_style = array(
	'type' => 'dropdown',
	'heading' => __( 'Style', 'jupiter-donut' ),
	'param_name' => 'grandient_color_style',

	// "edit_field_class" => "vc_col-sm-3",
	'value' => array(
		__( 'Linear', 'jupiter-donut' ) => 'linear',
		__( 'Radial', 'jupiter-donut' ) => 'radial',
	),
	'description' => __( '', 'jupiter-donut' ),
	'dependency' => array(
		'element' => 'color_style',
		'value' => array(
			'gradient_color',
		),
	),
);
$color_selection_gradient_color_angle = array(
	'type' => 'dropdown',
	'heading' => __( 'Angle', 'jupiter-donut' ),
	'param_name' => 'grandient_color_angle',

	// "edit_field_class" => "vc_col-sm-3",
	'value' => array(
		__( 'Vertical ↓', 'jupiter-donut' ) => 'vertical',
		__( 'Horizontal →', 'jupiter-donut' ) => 'horizontal',
		__( 'Diagonal ↘', 'jupiter-donut' ) => 'diagonal_left_bottom',
		__( 'Diagonal ↗', 'jupiter-donut' ) => 'diagonal_left_top',
	),
	'description' => __( '', 'jupiter-donut' ),
	'dependency' => array(
		'element' => 'grandient_color_style',
		'value' => array(
			'linear',
		),
	),
);

$color_selection_gradient_color_fallback = array(
	'type' => 'colorpicker',
	'heading' => __( 'Gradient Fallback Color', 'jupiter-donut' ),
	'param_name' => 'grandient_color_fallback',

	// "edit_field_class" => "vc_col-sm-3",
	'value' => '',
	'description' => __( '', 'jupiter-donut' ),
	'dependency' => array(
		'element' => 'color_style',
		'value' => array(
			'gradient_color',
		),
	),
);

$mk_vc_map_parallax_scroll = array(
	'pxs' => array(
		'group'       => __( 'Styles & Colors', 'jupiter-donut' ),
		'heading'     => __( 'Parallax Scroll', 'jupiter-donut' ),
		'description' => __( 'Enable Parallax Scroll for this element.', 'jupiter-donut' ),
		'type'        => 'toggle',
		'param_name'  => 'pxs',
		'value'       => 'false',
	),
	'pxs_x' => array(
		'group'            => __( 'Styles & Colors', 'jupiter-donut' ),
		'heading'          => __( 'X', 'jupiter-donut' ),
		'description'      => __( 'X axis translation (pixels)', 'jupiter-donut' ),
		'type'             => 'textfield',
		'param_name'       => 'pxs_x',
		'value'            => 0,
		'edit_field_class' => 'vc_col-sm-4 vc_column',
		'dependency'       => array(
			'element' => 'pxs',
			'value'   => array(
				'true',
			),
		),
	),
	'pxs_y' => array(
		'group'            => __( 'Styles & Colors', 'jupiter-donut' ),
		'heading'          => __( 'Y', 'jupiter-donut' ),
		'description'      => __( 'Y axis translation (pixels)', 'jupiter-donut' ),
		'type'             => 'textfield',
		'param_name'       => 'pxs_y',
		'value'            => -100,
		'edit_field_class' => 'vc_col-sm-4 vc_column',
		'dependency'       => array(
			'element' => 'pxs',
			'value'   => array(
				'true',
			),
		),
	),
	'pxs_z' => array(
		'group'            => __( 'Styles & Colors', 'jupiter-donut' ),
		'heading'          => __( 'Z', 'jupiter-donut' ),
		'description'      => __( 'Z axis translation (pixels)', 'jupiter-donut' ),
		'type'             => 'textfield',
		'param_name'       => 'pxs_z',
		'value'            => 0,
		'edit_field_class' => 'vc_col-sm-4 vc_column',
		'dependency'       => array(
			'element' => 'pxs',
			'value'   => array(
				'true',
			),
		),
	),
	'pxs_smoothness' => array(
		'group'       => __( 'Styles & Colors', 'jupiter-donut' ),
		'heading'     => __( 'Smoothness', 'jupiter-donut' ),
		'description' => __( 'Factor that slowdown the animation, the more the smoothier (default: 30)', 'jupiter-donut' ),
		'type'        => 'range',
		'param_name'  => 'pxs_smoothness',
		'value'       => '30',
		'min'         => '1',
		'max'         => '100',
		'step'        => '1',
		'unit'        => 'ms',
		'dependency'  => array(
			'element' => 'pxs',
			'value'   => array(
				'true',
			),
		),
	),
);

$skin_color = jupiter_donut_get_option( 'skin_color' );
