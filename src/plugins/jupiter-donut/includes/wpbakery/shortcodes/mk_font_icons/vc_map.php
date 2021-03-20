<?php
vc_map(array(
    "name" => __("Font icons", 'jupiter-donut') ,
    "base" => "mk_font_icons",
	'html_template' => dirname( __FILE__ ) . '/mk_font_icons.php',
    'icon' => 'icon-mk-font-icon vc_mk_element-icon',
    "category" => __('Typography', 'jupiter-donut') ,
    'description' => __('Advanced font icon element', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "icon_selector",
            "heading" => __("Add Icon", 'jupiter-donut') ,
            "param_name" => "icon",
            "value" => "",
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Font Icon Color Type", 'jupiter-donut') ,
            "param_name" => "color_style",
            "default" => "single_color",
            "value" => array(
                __('Single Color', 'jupiter-donut') => "single_color",
                __('Gradient Color', 'jupiter-donut') => "gradient_color"
            ) ,
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Color", 'jupiter-donut') ,
            "param_name" => "color",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "color_style",
                'value' => array(
                    'single_color'
                )
            )
        ),
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
            "type" => "dropdown",
            "heading" => __("Icon Size", 'jupiter-donut') ,
            "param_name" => "size",
            "value" => array(
                "16px" => "small",
                "32px" => "medium",
                "48px" => "large",
                "64px" => "x-large",
                "128px" => "xx-large",
                "256px" => "xxx-large"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Horizontal Margin", 'jupiter-donut') ,
            "param_name" => "margin_horizental",
            "value" => "4",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("You can give padding to the icon. this padding will be applied to left and right side of the icon", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Vertical Margin", 'jupiter-donut') ,
            "param_name" => "margin_vertical",
            "value" => "4",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("You can give padding to the icon. this padding will be applied to top and bottom of them icon", 'jupiter-donut')
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Circle Box?", 'jupiter-donut') ,
            "param_name" => "circle",
            "value" => "false",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Circle Color", 'jupiter-donut') ,
            "param_name" => "circle_color",
            "value" => "",
            "description" => __("If Circle Enabled you can set the rounded box background color using this color picker.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "circle",
                'value' => array(
                    'true'
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Circle Border Width", 'jupiter-donut') ,
            "param_name" => "circle_border_width",
            "value" => "1",
            "min" => "0",
            "max" => "10",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "circle",
                'value' => array(
                    'true'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Circle Border Style", 'jupiter-donut') ,
            "param_name" => "circle_border_style",
            "width" => 150,
            "value" => array(
                __('Solid', 'jupiter-donut') => "solid",
                __('Dashed', 'jupiter-donut') => "dashed",
                __('Dotted', 'jupiter-donut') => "dotted"
            ) ,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "circle",
                'value' => array(
                    'true'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Circle Border Color", 'jupiter-donut') ,
            "param_name" => "circle_border_color",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "circle",
                'value' => array(
                    'true'
                )
            )
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Icon Align", 'jupiter-donut') ,
            "param_name" => "align",
            "width" => 150,
            "value" => array(
                __('No Align', 'jupiter-donut') => "none",
                __('Left', 'jupiter-donut') => "left",
                __('Right', 'jupiter-donut') => "right",
                __('Center', 'jupiter-donut') => "center"
            ) ,
            "description" => __("Please note that align left and right will make the icons to float, therefore in order to keep your page elements from wrapping into each other you should add a padding divider shortcode right after the last icon.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Link", 'jupiter-donut') ,
            "param_name" => "link",
            "value" => "",
            "description" => __("You can optionally link your icon. please provide full URL including http://", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Link Target", 'jupiter-donut') ,
            "param_name" => "target",
            "value" => $target_arr,
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
