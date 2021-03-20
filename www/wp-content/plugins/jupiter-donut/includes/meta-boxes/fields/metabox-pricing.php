<?php


$config  = array(
	'title' => sprintf( '%s Pricing Table Options', 'Jupiter' ),
	'id' => 'mk-metaboxes-notab',
	'pages' => array(
		'pricing',
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core',
);

$features_text = '<ul>
	<li>10 Projects</li>
	<li>32 GB Storage</li>
	<li>Unlimited Users</li>
	<li>15 GB Bandwidth</li>
	<li><i class="mk-icon-ok"></i></li>
	<li>Enhanced Security</li>
	<li>Retina Display Ready</li>
	<li><i class="mk-icon-ok"></i></li>
	<li><i class="mk-icon-star"></i><i class="mk-icon-star"></i><i class="mk-icon-star"></i><i class="mk-icon-star"></i><i class="mk-icon-star"></i></li>
</ul>';

$spec_text = '<ul>
			<li>10 Projects</li>
			<li>32 GB Storage</li>
			<li>Unlimited Users</li>
			<li>15 GB Bandwidth</li>
			<li><i class="mk-icon-ok"></i></li>
			<li>Enhanced Security</li>
			<li>Retina Display Ready</li>
			<li><i class="mk-icon-ok"></i></li>
			<li><i class="mk-icon-star"></i><i class="mk-icon-star"></i><i class="mk-icon-star"></i><i class="mk-icon-star"></i><i class="mk-icon-star"></i></li>
		</ul>';

$options = array(
	array(
		'name' => __( 'Pricing Table Style', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'options' => array(
			'simple' => __( 'Simple', 'jupiter-donut' ),
			'builder' => __( 'Builder', 'jupiter-donut' ),
		),
		'id' => '_pricing_table_style',
		'default' => 'simple',
		'type' => 'select',
	),
	array(
		'name' => __( 'Skin', 'jupiter-donut' ),
		'desc' => __( 'This color will be applied only to this plan.', 'jupiter-donut' ),
		'id' => 'skin',
		'default' => '#25ae8d',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),
	array(
		'name' => __( 'Featured Plan?', 'jupiter-donut' ),
		'desc' => __( 'If you would like to select this item as featured enable this option.', 'jupiter-donut' ),
		'id' => 'featured',
		'default' => 'false',
		'type' => 'toggle',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),
	array(
		'name' => __( 'Featured Plan Ribbon Text', 'jupiter-donut' ),
		'desc' => __( 'This text will be place in a ribbon only in Featured Plan.', 'jupiter-donut' ),
		'id' => '_ribbon_txt',
		'default' => 'FEATURED',
		'type' => 'text',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),
	array(
		'name' => __( 'Plan Name', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_plan',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),
	array(
		'name' => __( 'Price?', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_price',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Currency Symbol', 'jupiter-donut' ),
		'desc' => __( 'eg: $, ¥, ₡, €', 'jupiter-donut' ),
		'id' => '_currency',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),
	array(
		'name' => __( 'Period', 'jupiter-donut' ),
		'desc' => __( 'eg: monthly, yearly, daily', 'jupiter-donut' ),
		'id' => '_period',
		'default' => '',
		'type' => 'text',
	),

	array(
		'name' => __( 'Features', 'jupiter-donut' ),
		'desc' => __( 'You can learn more on documentation on how to make a sample pricing table list. switch to Text mode to see html code.', 'jupiter-donut' ),
		'id' => '_features',
		'default' => $features_text,
		'type' => 'editor',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),




	// Pricing Table Builder Style
	array(
		'name' => __( 'Featured Plan?', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_featured_plan',
		'default' => 'false',
		'type' => 'toggle',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Featured Plan Text', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_featured_plan_text',
		'default' => 'Featured',
		'type' => 'text',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),

	array(
		'name' => __( 'Sale Price/Rate Text', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_save_text',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),

	array(
		'name' => __( 'Plan Name?', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_plan_name',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),

	array(
		'name' => __( 'Currency Symbol?', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_currency_symbol',
		'default' => '',
		'type' => 'text',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),


	array(
		'name' => __( 'Specifications text', 'jupiter-donut' ),
		'desc' => __( 'You can learn more on documentation on how to make a sample pricing table list. switch to Text mode to see html code.', 'jupiter-donut' ),
		'id' => '_specifications_text',
		'default' => $spec_text,
		'type' => 'editor',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),

	array(
		'name' => __( 'Title Background Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_title_background',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Price Background Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_price_background',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Title/Price Text Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_title_price_text',
		'default' => 'light',
		'options' => array(
			'light' => __( 'Light', 'jupiter-donut' ),
			'dark' => __( 'Dark', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Specifications Background Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_specifications_background',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Specifications Text Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_specifications_text_color',
		'default' => 'light',
		'options' => array(
			'light' => __( 'Light', 'jupiter-donut' ),
			'dark' => __( 'Dark', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Featured Badge Background Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_featured_badge_background',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Featured Badge Text Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_featured_badge_text_color',
		'default' => 'light',
		'options' => array(
			'light' => __( 'Light', 'jupiter-donut' ),
			'dark' => __( 'Dark', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Column Border Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_column_border_color',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Button Background Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_button_bg_color',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Button Text Color', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_button_text_color',
		'default' => 'light',
		'options' => array(
			'light' => __( 'Light', 'jupiter-donut' ),
			'dark' => __( 'Dark', 'jupiter-donut' ),
		),
		'type' => 'select',
	),
	array(
		'name' => __( 'Drop Shadow', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_drop_shadow',
		'default' => 'false',
		'type' => 'toggle',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),
	array(
		'name' => __( 'Make This Column Bigger', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_column_bigger',
		'default' => 'false',
		'type' => 'toggle',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'builder',
			),
		),
	),

	array(
		'name' => __( 'Button Text', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_text',
		'default' => 'Purchase',
		'type' => 'text',
	),

	array(
		'name' => __( 'Button URL', 'jupiter-donut' ),
		'desc' => __( '', 'jupiter-donut' ),
		'id' => '_btn_url',
		'default' => '',
		'type' => 'text',
	),
	array(
		'name' => __( 'Button Style', 'jupiter-donut' ),
		'desc' => __( 'Choose your button style', 'jupiter-donut' ),
		'id' => '_button_style',
		'default' => 'flat',
		'options' => array(
			'flat' => __( 'Flat', 'jupiter-donut' ),
			'three' => __( 'Three Dimension', 'jupiter-donut' ),
			'two' => __( 'Two Dimension', 'jupiter-donut' ),
			'outline' => __( 'Outline', 'jupiter-donut' ),
		),
		'type' => 'select',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),
	array(
		'name' => __( 'Button Color', 'jupiter-donut' ),
		'desc' => __( 'If left blank defaults will be used.', 'jupiter-donut' ),
		'id' => '_button_skin',
		'default' => '',
		'type' => 'color',
		'dependency' => array(
			'element' => '_pricing_table_style',
			'value' => array(
				'simple',
			),
		),
	),

);

new mkMetaboxesGenerator( $config, $options );
