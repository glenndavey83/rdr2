<?php
/**
 * Add Field Label section of Checkout & Cart > Styles.
 * Prefixes: s -> shop, cc -> checkout-cart, s -> styles, big_heading -> big_hdn
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Field Label dialog.
$mk_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_cc_s_big_heading',
		array(
			'mk_belong' => 'mk_s_cc_dialog',
			'mk_tab' => array(
				'id' => 'sh_cc_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Big Heading', 'mk_framework' ),
			'mk_reset' => 'sh_cc_sty_big_hdn',
			'priority' => 20,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$mk_customize->add_setting( 'mk_cz[sh_cc_sty_big_hdn_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size' => 18,
		'weight' => 700,
		'style' => 'normal',
		'color' => '#404040',
	),
	'transport' => 'postMessage',
) );

$mk_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_big_hdn_typography]',
		array(
			'section' => 'mk_s_cc_s_big_heading',
			'column'  => 'mk-col-12',
		)
	)
);

// Divider.
$mk_customize->add_setting( 'mk_cz[sh_cc_sty_big_hdn_divider]', array(
	'type' => 'option',
) );

$mk_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_big_hdn_divider]',
		array(
			'section' => 'mk_s_cc_s_big_heading',
		)
	)
);

// Box Model.
$mk_customize->add_setting( 'mk_cz[sh_cc_sty_big_hdn_box_model]', array(
	'type' => 'option',
	'default' => array(
		'padding_top' => 0,
		'padding_right' => 0,
		'padding_bottom' => 12,
		'padding_left' => 0,
	),
	'transport' => 'postMessage',
) );

$mk_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_big_hdn_box_model]',
		array(
			'section' => 'mk_s_cc_s_big_heading',
			'column'  => 'mk-col-12',
		)
	)
);
