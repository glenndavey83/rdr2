<?php
    vc_map(array(
        "name" => __("Image Switch", 'jupiter-donut') ,
        "base" => "mk_image_switch",
		'html_template' => dirname( __FILE__ ) . '/mk_image_switch.php',
        "category" => __('General', 'jupiter-donut') ,
        'description' => __('', 'jupiter-donut') ,
        'icon' => 'icon-mk-image vc_mk_element-icon',
        "params" => array(
            array(
                "type" => "upload",
                "heading" => __("Upload Your First image", 'jupiter-donut') ,
                "param_name" => "src_first",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "upload",
                "heading" => __("Upload Your Second image", 'jupiter-donut') ,
                "param_name" => "src_second",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Image Hover Animation", 'jupiter-donut') ,
                "param_name" => "hover_animation",
                "value" => array(
                    __('Without Fading', 'jupiter-donut') => "without-fading",
                    __('Fading', 'jupiter-donut') => "fading",
                ) ,
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Image Width", 'jupiter-donut') ,
                "param_name" => "image_width",
                "value" => "800",
                "min" => "10",
                "max" => "2600",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Image Height", 'jupiter-donut') ,
                "param_name" => "image_height",
                "value" => "350",
                "min" => "10",
                "max" => "5000",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "toggle",
                "heading" => __("Image Cropping", 'jupiter-donut') ,
                "param_name" => "crop",
                "value" => "true",
                "description" => __("If you dont want to crop your image based on the dimensions you defined above disable this option. Only wdith will be used to give the image container max-width property.", 'jupiter-donut')
            ) ,
            array(
                "type" => "toggle",
                "heading" => __("SVG Enable?", 'jupiter-donut') ,
                "param_name" => "svg",
                "value" => "false",
                "description" => __("If enabled max-width property will be added to image tag and you should enable this option if you are using SVG format in this image shortcode.", 'jupiter-donut')
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
                'type' => 'vc_link',
                'heading' => __( 'URL (Link)', 'jupiter-donut' ),
                'param_name' => 'link',
                'description' => __( 'Add link to image.', 'jupiter-donut' ),
            ),
            array(
                "type" => "range",
                "heading" => __("Margin Bottom", 'jupiter-donut') ,
                "param_name" => "margin_bottom",
                "value" => "10",
                "min" => "-50",
                "max" => "300",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
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
