<?php
/**
 * Add Title dialog of Widgets Styles.
 * Prefix: w -> widgets, gs -> global-styles, b -> boxes.
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Boxes dialog.
$mk_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_w_gs_boxes',
		array(
			'mk_belong' => 'mk_w_s_dialog',
			'mk_tab' => array(
				'id' => 'wg_glb_sty',
				'text' => __( 'Global Styles', 'mk_framework' ),
			),
			'title' => __( 'Boxes', 'mk_framework' ),
			'mk_reset' => 'wg_glb_sty_box',
			'priority' => 40,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Background color.
$mk_customize->add_setting( 'mk_cz[wg_glb_sty_box_background_color]', array(
	'type' => 'option',
	'default'   => 'rgba(255, 255, 255, 0)',
	'transport' => 'postMessage',
) );

$mk_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_box_background_color]',
		array(
			'section'  => 'mk_w_gs_boxes',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border radius.
$mk_customize->add_setting( 'mk_cz[wg_glb_sty_box_border_radius]', array(
	'type' => 'option',
	'default'   => 0,
	'transport' => 'postMessage',
) );

$mk_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_box_border_radius]',
		array(
			'section'  => 'mk_w_gs_boxes',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-corner-radius',
			'unit' => __( 'px', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);

// Border width.
$mk_customize->add_setting( 'mk_cz[wg_glb_sty_box_border_width]', array(
	'type' => 'option',
	'default'   => 0,
	'transport' => 'postMessage',
) );

$mk_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_box_border_width]',
		array(
			'section'  => 'mk_w_gs_boxes',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-border',
			'unit' => __( 'px', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);

// Border color.
$mk_customize->add_setting( 'mk_cz[wg_glb_sty_box_border_color]', array(
	'type' => 'option',
	'default'   => '#D5D8DE',
	'transport' => 'postMessage',
) );

$mk_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_box_border_color]',
		array(
			'section'  => 'mk_w_gs_boxes',
			'column'   => 'mk-col-2-alt mk-col-last',
			'icon'     => 'mk-border-color',
		)
	)
);

// Divider.
$mk_customize->add_setting( 'mk_cz[wg_glb_sty_box_divider]', array(
	'type' => 'option',
) );

$mk_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_box_divider]',
		array(
			'section' => 'mk_w_gs_boxes',
		)
	)
);

// Box Model.
$mk_customize->add_setting( 'mk_cz[wg_glb_sty_box_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top' => 0,
		'margin_right' => 0,
		'margin_bottom' => 40,
		'margin_left' => 0,
		'padding_top' => 0,
		'padding_right' => 0,
		'padding_bottom' => 0,
		'padding_left' => 0,
	),
	'transport' => 'postMessage',
) );

$mk_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_box_box_model]',
		array(
			'section' => 'mk_w_gs_boxes',
			'column'  => 'mk-col-12',
		)
	)
);
