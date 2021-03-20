<?php
vc_map(array(
    "name" => __("Divider", 'jupiter-donut') ,
    "base" => "mk_divider",
	'html_template' => dirname( __FILE__ ) . '/mk_divider.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-divider vc_mk_element-icon',
    'description' => __('Dividers with many styles & options.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut') ,
            "param_name" => "style",
            "value" => array(
                "Double Dotted" => "double_dot",
                "Thick Solid Line" => "thick_solid",
                "Thin Solid Line" => "thin_solid",
                "Thin Dotted Line" => "single_dotted",
                "Shadow Line" => "shadow_line",
                "Go Top with Thin Line" => "go_top",
                "Go Top with Thick Line" => "go_top_thick",
                "Empty Space" => "padding_space"
            ) ,
            "description" => __("Choose the divider style.", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Divider Width", 'jupiter-donut') ,
            "param_name" => "divider_width",
            "value" => array(
                "Full Width" => "full_width",
                "One Half" => "one_half",
                "One Third" => "one_third",
                "One Fourth" => "one_fourth",
                "Custom Width" => "custom_width"
            ) ,
            "description" => __("There are 5 predefined and one user defined values for width. If you want to divide the page into 2 sections, you can simply place this shortcode into a row and enable 'Fullwidth Row'.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Divider Custom Width", 'jupiter-donut') ,
            "param_name" => "custom_width",
            "value" => "10",
            "min" => "1",
            "max" => "900",
            "step" => "1",
            "unit" => 'px',
            "description" => __("Choose any custom width for divider", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "divider_width",
                'value' => array(
                    'custom_width'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Align", 'jupiter-donut') ,
            "param_name" => "align",
            "value" => array(
                "Center" => "center",
                "Left" => "left",
                "Right" => "right",
            ),
            "dependency" => array(
                'element' => "divider_width",
                'value' => array(
                    'one_half',
                    'one_third',
                    'one_fourth',
                    'custom_width',
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Divider Color", 'jupiter-donut') ,
            "param_name" => "border_color",
            "value" => "",
            "group" => __('Styles & Colors', 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'thick_solid',
                    'single_dotted',
                    //'thin_solid'
                )
            )
        ) ,

        array(
                "type" => "dropdown",
                "heading" => __("Background Color Style", 'jupiter-donut') ,
                "param_name" => "thin_color_style",
                "default" => "single_color",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => array(
                    __('Single Color', 'jupiter-donut') => "single_color",
                    __('Gradient Color', 'jupiter-donut') => "gradient_color",
                ) ,
                "description" => __("", 'jupiter-donut'),
                "dependency" => array(
                    'element' => "style",
                    'value' => array(
                        'thin_solid'
                    )
                )
            ) ,

        /**
         * Thin Single Color Style
         * ==================================================================================
         */
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Background Color", 'jupiter-donut') ,
            "param_name" => "thin_single_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "group" => __('Styles & Colors', 'jupiter-donut') ,
            "dependency" => array(
                'element' => "thin_color_style",
                'value' => array(
                    'single_color'
                )
            )
        ) ,

        /**
         * Thin Gradient Color Style
         * ==================================================================================
         */

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("From", 'jupiter-donut') ,
            "param_name" => "thin_grandient_color_from",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "group" => __('Styles & Colors', 'jupiter-donut') ,
            "dependency" => array(
                'element' => "thin_color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("To", 'jupiter-donut') ,
            "param_name" => "thin_grandient_color_to",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "group" => __('Styles & Colors', 'jupiter-donut') ,
            "dependency" => array(
                'element' => "thin_color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut') ,
            "param_name" => "thin_gradient_color_style",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "value" => array(
                __('Linear', 'jupiter-donut') => "linear",
                __('Radial', 'jupiter-donut') => "radial"
            ) ,
            "group" => __('Styles & Colors', 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "thin_color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Angle", 'jupiter-donut') ,
            "param_name" => "thin_gradient_color_angle",
            "edit_field_class" => "vc_col-sm-3 vc_column",
            "group" => __('Styles & Colors', 'jupiter-donut') ,
            "value" => array(
                __('Vertical ↓', 'jupiter-donut') => "vertical",
                __('Horizontal →', 'jupiter-donut') => "horizontal",
                __('Diagonal ↘', 'jupiter-donut') => "diagonal_left_bottom",
                __('Diagonal ↗', 'jupiter-donut') => "diagonal_left_top",
            ) ,
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "thin_color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Gradient Fallback Color", 'jupiter-donut') ,
            "param_name" => "thin_grandient_color_fallback",
            "group" => __('Styles & Colors', 'jupiter-donut') ,
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "thin_color_style",
                'value' => array(
                    'gradient_color'
                )
            ) ,
        ) ,



        array(
            "type" => "range",
            "heading" => __("Divider Thickness", 'jupiter-donut') ,
            "param_name" => "thickness",
            "value" => "1",
            "min" => "1",
            "max" => "20",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'thin_solid'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("Padding Top", 'jupiter-donut') ,
            "param_name" => "margin_top",
            "value" => "20",
            "min" => "0",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("How much space would you like to have before divider? This value will be applied to top.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Padding Bottom", 'jupiter-donut') ,
            "param_name" => "margin_bottom",
            "value" => "20",
            "min" => "0",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("How much space would you like to have after divider? This value will be applied to bottom.", 'jupiter-donut')
        ) ,
        $add_device_visibility,
        $add_css_animations,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
