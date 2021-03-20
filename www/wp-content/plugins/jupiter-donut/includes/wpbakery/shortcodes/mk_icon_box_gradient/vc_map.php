<?php
    vc_map(array(
    "name" => __("Icon Box Gradient", 'jupiter-donut') ,
    "base" => "mk_icon_box_gradient",
	'html_template' => dirname( __FILE__ ) . '/mk_icon_box_gradient.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-icon-box vc_mk_element-icon',
    'description' => __('Powerful & versatile Icon Boxes.', 'jupiter-donut') ,
    "params" => array(

        array(
            "heading" => __("Icon Size", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "icon_size",
            "value" => array(
                __("16", 'jupiter-donut') => "16",
                __("32", 'jupiter-donut') => "32",
                __("48", 'jupiter-donut') => "48",
                __("64", 'jupiter-donut') => "64",
                __("128", 'jupiter-donut') => "128",
            ) ,
            "type" => "dropdown"
        ) ,

        array(
            "type" => "icon_selector",
            "heading" => __("Icon", 'jupiter-donut') ,
            "param_name" => "icon",
            "value" => "mk-li-smile",
        ) ,
        array(
            "heading" => __("Container Shape", 'jupiter-donut') ,
            "description" => __("Works properly only in Webkit browsers. Fallback to circle shape for others", 'jupiter-donut') ,
            "param_name" => "holder_shape",
            "border" => 'true',
            "value" => array(
                'shape/circle.png' => "circle",
                'shape/hexagon.png' => "hexagon",
                'shape/hexagon2.png' => "hexagon2",
                'shape/pentagon.png' => "pentagon",
                'shape/square.png' => "square",
                'shape/square2.png' => "square2",
                'shape/starz.png' => "starz",
            ) ,
            "type" => "visual_selector"
        ) ,

        array(
            "type" => "dropdown",
            "heading" => __("Text Color Type", 'jupiter-donut') ,
            "param_name" => "color_style",
            "default" => "",
            "value" => array(
                __('Single Color', 'jupiter-donut') => "single_color",
                __('Gradient Color', 'jupiter-donut') => "gradient_color"
            ) ,
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Container Color", 'jupiter-donut') ,
            "param_name" => "container_color",
            "edit_field_class" => "vc_col-sm-6 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "color_style",
                'value' => array(
                    'single_color'
                )
            )
        ) ,

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Container Hover Color", 'jupiter-donut') ,
            "param_name" => "container_hover_color",
            "edit_field_class" => "vc_col-sm-6 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "color_style",
                'value' => array(
                    'single_color'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("From", 'jupiter-donut') ,
            "param_name" => "grandient_color_from",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("To", 'jupiter-donut') ,
            "param_name" => "grandient_color_to",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut') ,
            "param_name" => "grandient_color_style",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "value" => array(
                __('Linear', 'jupiter-donut') => "linear",
                __('Radial', 'jupiter-donut') => "radial"
            ) ,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Angle", 'jupiter-donut') ,
            "param_name" => "grandient_color_angle",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "value" => array(
                __('Vertical ↓', 'jupiter-donut') => "vertical",
                __('Horizontal →', 'jupiter-donut') => "horizontal",
                __('Diagonal ↘', 'jupiter-donut') => "diagonal_left_bottom",
                __('Diagonal ↗', 'jupiter-donut') => "diagonal_left_top",
            ) ,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "grandient_color_style",
                'value' => array(
                    'linear'
                )
            ) ,
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Gradient Fallback Color", 'jupiter-donut') ,
            "param_name" => "grandient_color_fallback",
            //"edit_field_class" => "vc_col-sm-3",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ),

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Color", 'jupiter-donut') ,
            "param_name" => "icon_color",
            "edit_field_class" => "vc_col-sm-6 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
        ) ,

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Hover Color", 'jupiter-donut') ,
            "param_name" => "icon_hover_color",
            "edit_field_class" => "vc_col-sm-6 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
        ) ,

        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Title Font Size", 'jupiter-donut') ,
            "param_name" => "title_size",
            "value" => "20",
            "min" => "5",
            "max" => "40",
            "step" => "1",
            "unit" => 'px'
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Title Font Weight", 'jupiter-donut') ,
            "param_name" => "title_weight",
            "width" => 150,
            "value" => array(
                __('Default', 'jupiter-donut') => "inherit",
                __('Bold', 'jupiter-donut') => "bold",
                __('Bolder', 'jupiter-donut') => "bolder",
                __('Normal', 'jupiter-donut') => "normal",
                __('Light', 'jupiter-donut') => "300"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Title Font Color", 'jupiter-donut') ,
            "param_name" => "title_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Title Top Padding", 'jupiter-donut') ,
            "param_name" => "title_top_padding",
            "value" => "10",
            "min" => "5",
            "max" => "60",
            "step" => "1",
            "unit" => 'px'
        ) ,
        array(
            "type" => "range",
            "heading" => __("Title Bottom Padding", 'jupiter-donut') ,
            "param_name" => "title_bottom_padding",
            "value" => "10",
            "min" => "5",
            "max" => "60",
            "step" => "1",
            "unit" => 'px'
        ) ,

        array(
            "type" => "textarea_html",
            "holder" => "div",
            'toolbar' => 'full',
            "heading" => __("Description", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("Enter your content.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Description Color", 'jupiter-donut') ,
            "param_name" => "content_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Box Align", 'jupiter-donut') ,
            "param_name" => "align",
            "description" => __("This option will align the whole box content.", 'jupiter-donut') ,
            "value" => array(
                "Center" => "center",
                "Left" => "left",
                "Right" => "right",
            )
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Read More URL", 'jupiter-donut') ,
            "param_name" => "read_more_url",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        $add_css_animations,
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
