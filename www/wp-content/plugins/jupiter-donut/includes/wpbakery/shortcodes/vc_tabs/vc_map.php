<?php
$tab_id_1 = time() . '-1-' . rand(0, 100);
$tab_id_2 = time() . '-2-' . rand(0, 100);
vc_map(array(
	'name' => __('Tabs', 'jupiter-donut'),
	'base' => 'vc_tabs',
	'html_template' => dirname( __FILE__ ) . '/vc_tabs.php',
	'show_settings_on_create' => false,
	'is_container' => true,
	'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/vc_tabs/vc_front.js',
	'icon' => 'icon-mk-tabs vc_mk_element-icon',
	'category' => __('Content', 'jupiter-donut'),
	'description' => __('Tabbed content', 'jupiter-donut'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __('Title', 'jupiter-donut'),
			'param_name' => 'heading_title',
			'value' => '',
			'description' => __('', 'jupiter-donut')
		),
		array(
			'type' => 'dropdown',
			'heading' => __('Style', 'jupiter-donut'),
			'param_name' => 'style',
			'value' => array(
				'Default' => 'default',
				'Simple' => 'simple'
			),
			'description' => __('Please choose your tabs style', 'jupiter-donut')
		),
		array(
			'type' => 'dropdown',
			'heading' => __('Orientation', 'jupiter-donut'),
			'param_name' => 'orientation',
			'value' => array(
				'Horizontal' => 'horizental',
				'Vertical' => 'vertical'
			),
			'dependency' => array(
				'element' => 'style',
				'value' => array(
					'default'
				)
			),
			'description' => __( 'Note : This option is only for deafult style', 'jupiter-donut' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __('Tab location', 'jupiter-donut'),
			'param_name' => 'tab_location',
			'value' => array(
				'Left' => 'left',
				'Right' => 'right'
			),
			'description' => __('Which side would you like the tabs list appear?', 'jupiter-donut'),
			'dependency' => array(
				'element' => 'orientation',
				'value' => array(
					'vertical'
				)
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __('Mobile Friendly Tabs?', 'jupiter-donut'),
			'description' => __('If enabled tabs functionality will removed in mobile devices, each tab and its content will be inserted below each other.', 'jupiter-donut'),
			'param_name' => 'responsive',
			'value' => array(
				'Yes please!' => 'true',
				'No!' => 'false'
			),
		),
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __('Container Background Color', 'jupiter-donut'),
			'param_name' => 'container_bg_color',
			'value' => '#fff',
			'description' => __('', 'jupiter-donut')
		),
		$add_device_visibility,
		array(
			'type' => 'textfield',
			'heading' => __('Extra class name', 'jupiter-donut'),
			'param_name' => 'el_class',
			'value' => '',
			'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'jupiter-donut')
		),
	),
    "custom_markup" => '
  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
  <ul class="tabs_controls">
  </ul>
  %content%
  </div>',
    'default_content' => '
  [vc_tab title="' . __('Tab 1', 'jupiter-donut') . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
  [vc_tab title="' . __('Tab 2', 'jupiter-donut') . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
  ',
    "js_view" => 'VcTabsView'
));
