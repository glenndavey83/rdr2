<?php
vc_map(array(
    "name" => __("Widgetized Sidebar", 'jupiter-donut') ,
    "base" => "mk_custom_sidebar",
	'html_template' => dirname( __FILE__ ) . '/mk_custom_sidebar.php',
    'icon' => 'icon-mk-custom-sidebar vc_mk_element-icon',
    'description' => __('Place Widgetized sidebar', 'jupiter-donut') ,
    "category" => __('Structure', 'jupiter-donut') ,
    "params" => array(
        array(
            'type' => 'widgetised_sidebars',
            'heading' => __('Sidebar', 'jupiter-donut') ,
            'param_name' => 'sidebar',
            'description' => __('Select the widget area to be shown in this sidebar.', 'jupiter-donut')
        ) ,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.", 'jupiter-donut')
        )
    )
));
