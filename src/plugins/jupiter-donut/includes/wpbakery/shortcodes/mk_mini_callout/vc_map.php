<?php
vc_map(array(
    "name" => __("Mini Callout Box", 'jupiter-donut') ,
    "base" => "mk_mini_callout",
	'html_template' => dirname( __FILE__ ) . '/mk_mini_callout.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-mini-callout-box vc_mk_element-icon',
    'description' => __('Small callout box for important infos.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "heading" => __("Description", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("Enter your content.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Button Text", 'jupiter-donut') ,
            "param_name" => "button_text",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Button URL", 'jupiter-donut') ,
            "param_name" => "button_url",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'jupiter-donut')
        )
    )
));
