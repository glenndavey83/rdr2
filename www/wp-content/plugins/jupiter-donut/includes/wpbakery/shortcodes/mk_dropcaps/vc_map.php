<?php
vc_map(array(
    "name" => __("Dropcaps", 'jupiter-donut') ,
    "base" => "mk_dropcaps",
	'html_template' => dirname( __FILE__ ) . '/mk_dropcaps.php',
    'icon' => 'icon-mk-dropcaps vc_mk_element-icon',
    "category" => __('Typography', 'jupiter-donut') ,
    'description' => __('Dropcaps element shortcode.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "heading" => __("Dropcaps Character", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut') ,
            "param_name" => "style",
            "value" => array(
                __('Simple', 'jupiter-donut') => "simple-style",
                __('Fancy', 'jupiter-donut') => "fancy-style"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Font Size", 'jupiter-donut') ,
            "param_name" => "size",
            "value" => "34",
            "min" => "12",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'fancy-style'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("Padding", 'jupiter-donut') ,
            "param_name" => "padding",
            "value" => "10",
            "min" => "5",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("You can set padding for dropcaps.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'fancy-style'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Background Color", 'jupiter-donut') ,
            "param_name" => "background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'fancy-style'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Text Color", 'jupiter-donut') ,
            "param_name" => "text_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'fancy-style'
                )
            )
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
