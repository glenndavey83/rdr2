<?php

vc_map( array(
	'name' => __( 'Tab', 'jupiter-donut' ),
	'base' => 'vc_tab',
	'html_template' => dirname( __FILE__ ) . '/vc_tab.php',
	'allowed_container_element' => 'vc_row',
	'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/vc_tab/vc_front.js',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'jupiter-donut' ),
			'param_name' => 'title',
			'description' => __( 'Tab title.', 'jupiter-donut' ),
		) ,
		array(
			'type' => 'icon_selector',
			'heading' => __( 'Add Icon (optional)', 'jupiter-donut' ),
			'param_name' => 'icon',
			'value' => '',
		),
		array(
			'type' => 'tab_id',
			'heading' => __( 'Tab ID', 'js_composer' ),
			'param_name' => 'tab_id',
		),
	),
	'js_view' => 'VcTabView',
) );
