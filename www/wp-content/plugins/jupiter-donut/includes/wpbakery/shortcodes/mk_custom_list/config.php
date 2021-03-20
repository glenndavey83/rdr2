<?php

extract( shortcode_atts( array(
	'el_class' 		=> '',
	'title' 		=> '',
	'style' 		=> 'mk-icon-check',
	'icon_color'	=> jupiter_donut_get_option( 'skin_color' ),
	'animation' 	=> '',
	'align' 		=> 'none',
	'margin_bottom' => 30,
	'visibility'    => ''
), $atts ) );

Mk_Static_Files::addAssets('mk_custom_list');
