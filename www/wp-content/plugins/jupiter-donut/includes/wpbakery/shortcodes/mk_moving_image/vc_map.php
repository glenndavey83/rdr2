<?php
vc_map(array(
    "name" => __("Moving Image", 'jupiter-donut') ,
    "base" => "mk_moving_image",
	'html_template' => dirname( __FILE__ ) . '/mk_moving_image.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-moving-image vc_mk_element-icon',
    'description' => __('Images powered by CSS3 moving animations.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "upload",
            "heading" => __("Upload Your image", 'jupiter-donut') ,
            "param_name" => "src",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Animation Style", 'jupiter-donut') ,
            "param_name" => "axis",
            "value" => array(
                "Vertical" => "vertical",
                "Horizontally" => "horizontal",
                "Pulse" => "pulse",
                "Tossing" => "tossing"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Align", 'jupiter-donut') ,
            "param_name" => "align",
            "width" => 150,
            "value" => array(
                __('Left', 'jupiter-donut') => "left",
                __('Right', 'jupiter-donut') => "right",
                __('Center', 'jupiter-donut') => "center"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Title & Alt", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("For SEO purposes you may need to fill out the title and alt property for this image", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Link", 'jupiter-donut') ,
            "param_name" => "link",
            "value" => "",
            "description" => __("Link this image to a URL. Include http://", 'jupiter-donut')
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
    )
));
