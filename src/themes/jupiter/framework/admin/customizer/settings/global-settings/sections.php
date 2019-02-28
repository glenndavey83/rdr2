<?php
/**
 * Add Global Settings section.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 6.0.3
 */

// Global Settings section.
$wp_customize->add_section( 'mk_gs' , array(
	'title'    => __( 'Global Settings', 'mk-framework' ),
	'priority' => 400,
) );

// Global Settings dialogs.
$wp_customize->add_setting( 'mk_gs_dialogs', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_dialog_Control(
		$wp_customize,
		'mk_gs_dialogs',
		array(
			'label'   => false,
			'section' => 'mk_gs',
			'buttons' => array(
				'mk_gs_ss_dialog' => __( 'Site Settings', 'mk_framework' ),
			),
			'column'  => '',
		)
	)
);

// Dialogs.
$dialogs = glob( dirname( __FILE__ ) . '/*/dlg-*.php' );

// Load all the dialogs.
foreach ( $dialogs as $dialog ) {
	require_once $dialog;
}
