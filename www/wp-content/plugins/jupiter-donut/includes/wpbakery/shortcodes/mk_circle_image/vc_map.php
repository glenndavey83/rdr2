<?php
    vc_map(array(
        "name" => __("Circle Image Frame", 'jupiter-donut') ,
        "base" => "mk_circle_image",
		'html_template' => dirname( __FILE__ ) . '/mk_circle_image.php',
        "category" => __('General', 'jupiter-donut') ,
        'icon' => 'icon-mk-circle-image-frame vc_mk_element-icon',
        'description' => __('Adds a circled image element.', 'jupiter-donut') ,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Heading Title", 'jupiter-donut') ,
                "param_name" => "heading_title",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "upload",
                "heading" => __("Upload Your image", 'jupiter-donut') ,
                "param_name" => "src",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Image Diameter", 'jupiter-donut') ,
                "param_name" => "image_diameter",
                "value" => "500",
                "min" => "10",
                "max" => "1000",
                "step" => "1",
                "unit" => 'px',
                "description" => __("The diameter of circle containing your image", 'jupiter-donut')
            ) ,
            array(
                "type" => "textfield",
                "heading" => __("Image Link", 'jupiter-donut') ,
                "param_name" => "link",
                "value" => "",
                "description" => __("Optionally you can link your image.", 'jupiter-donut')
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
