<?php
    vc_map(array(
    "name" => __("Content Box", 'jupiter-donut') ,
    "base" => "mk_content_box",
	'html_template' => dirname( __FILE__ ) . '/mk_content_box.php',
    "as_parent" => array(
        'except' => 'vc_row',
        'mk_page_section'
    ) ,
    "content_element" => true,
    "show_settings_on_create" => false,
	'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_content_box/vc_front.js',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-content-box vc_mk_element-icon',
    'description' => __('Content Box with heading', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title Heading", 'jupiter-donut') ,
            "param_name" => "heading",
            "value" => "",
            "description" => __("Add a title to your container box.", 'jupiter-donut')
        ) ,
        array(
            "type" => "icon_selector",
            "heading" => __("Add Icon", 'jupiter-donut') ,
            "param_name" => "icon",
            "value" => "",
        ) ,
        $add_css_animations,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'jupiter-donut')
        )
    ) ,
    "js_view" => 'VcColumnView'
));
