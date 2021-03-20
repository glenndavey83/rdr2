<?php
vc_map(array(
    "name" => __("Tooltip", 'jupiter-donut') ,
    "base" => "mk_tooltip",
	'html_template' => dirname( __FILE__ ) . '/mk_tooltip.php',
    'icon' => 'icon-mk-tooltip vc_mk_element-icon',
    "category" => __('Typography', 'jupiter-donut') ,
    'description' => __('Adds Tooltips to inline texts.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Text", 'jupiter-donut') ,
            "param_name" => "text",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Tooltip Text", 'jupiter-donut') ,
            "param_name" => "tooltip_text",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Tooltip URL", 'jupiter-donut') ,
            "param_name" => "href",
            "value" => "",
            "description" => __("You can optionally link the tooltip text to a webpage.", 'jupiter-donut')
        ) ,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
