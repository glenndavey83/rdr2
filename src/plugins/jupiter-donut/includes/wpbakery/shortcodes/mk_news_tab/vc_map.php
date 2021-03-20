<?php
vc_map(array(
    "name" => __("News Tab", 'jupiter-donut') ,
    "base" => "mk_news_tab",
	'html_template' => dirname( __FILE__ ) . '/mk_news_tab.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-news-tab vc_mk_element-icon',
    'description' => __('News feed in tabs style.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Widget Title", 'jupiter-donut') ,
            "param_name" => "widget_title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Tab Title", 'jupiter-donut') ,
            "param_name" => "tab_title",
            "value" => "News",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Mobile Friendly Tabs?", 'jupiter-donut') ,
            "description" => __("If enabled tabs functionality will removed in mobile devices, each tab and its content will be inserted below each other.", 'jupiter-donut') ,
            "param_name" => "responsive",
            "value" => array(
                "Yes please!" => "true",
                "No!" => "false"
            ) ,
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
