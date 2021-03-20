<?php
    vc_map(array(
        "name" => __("Custom Box", 'jupiter-donut') ,
        "base" => "mk_custom_box",
		'html_template' => dirname( __FILE__ ) . '/mk_custom_box.php',
        "as_parent" => array(
            'except' => 'mk_page_section'
        ) ,
        "admin_enqueue_js" => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_custom_box/vc_admin.js',
		'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_custom_box/vc_front.js',
        "content_element" => true,
        "show_settings_on_create" => false,
        "description" => __("Custom Box For your contents.", 'jupiter-donut') ,
        'icon' => 'icon-mk-custom-box vc_mk_element-icon',
        "category" => __('General', 'jupiter-donut') ,
        "params" => array(
            array(
                "type" => "range",
                "heading" => __("Corner Radius", 'jupiter-donut') ,
                "param_name" => "corner_radius",
                "value" => "0",
                "min" => "0",
                "max" => "50",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Padding Top and Bottom", 'jupiter-donut') ,
                "param_name" => "padding_vertical",
                "value" => "30",
                "min" => "0",
                "max" => "200",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Padding Left and Right", 'jupiter-donut') ,
                "param_name" => "padding_horizental",
                "value" => "20",
                "min" => "0",
                "max" => "200",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Margin Bottom", 'jupiter-donut') ,
                "param_name" => "margin_bottom",
                "value" => "10",
                "min" => "0",
                "max" => "200",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Section Min Height", 'jupiter-donut') ,
                "param_name" => "min_height",
                "value" => "100",
                "min" => "0",
                "max" => "1000",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            $add_device_visibility,
            $add_css_animations,
            array(
                "type" => "textfield",
                "heading" => __("Extra class name", 'jupiter-donut') ,
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'jupiter-donut')
            ) ,

            array(
                "type" => "dropdown",
                "heading" => __("Background Color Style", 'jupiter-donut') ,
                "param_name" => "background_style",
                "default" => "image",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => array(
                    __('Image & Single Color', 'jupiter-donut') => "image",
                    __('Gradient Color', 'jupiter-donut') => "gradient_color",

                ) ,
                "description" => __("", 'jupiter-donut')
            ) ,

            /**
             * Background Single Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Background Color", 'jupiter-donut') ,
                "param_name" => "bg_color",
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image'
                    )
                )
            ) ,

            /**
             * Background Gradient Color
             * ==================================================================================
             */

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("From", 'jupiter-donut') ,
                "param_name" => "bg_grandient_color_from",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("To", 'jupiter-donut') ,
                "param_name" => "bg_grandient_color_to",
                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Style", 'jupiter-donut') ,
                "param_name" => "bg_gradient_color_style",
                "value" => array(
                    __('Linear', 'jupiter-donut') => "linear",
                    __('Radial', 'jupiter-donut') => "radial"
                ) ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Angle", 'jupiter-donut') ,
                "param_name" => "bg_gradient_color_angle",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => array(
                    __('Vertical ↓', 'jupiter-donut') => "vertical",
                    __('Horizontal →', 'jupiter-donut') => "horizontal",
                    __('Diagonal ↘', 'jupiter-donut') => "diagonal_left_bottom",
                    __('Diagonal ↗', 'jupiter-donut') => "diagonal_left_top",
                ) ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Gradient Fallback Color", 'jupiter-donut') ,
                "param_name" => "bg_grandient_color_fallback",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,

            /**
             * Background Image
             * ==================================================================================
             */
            array(
                "type" => "upload",
                "heading" => __("Background Image", 'jupiter-donut') ,
                "param_name" => "bg_image",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,
            array(
                "type" => "toggle",
                "heading" => __("Cover whole background", 'jupiter-donut') ,
                "description" => __("Scale the background image to be as large as possible so that the background area is completely covered by the background image. Some parts of the background image may not be in view within the background positioning area.", 'jupiter-donut') ,
                "param_name" => "bg_stretch",
                "width" => 300,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Background Position", 'jupiter-donut') ,
                "param_name" => "bg_position",
                "width" => 300,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => array(
                    __('Center Center', 'jupiter-donut') => "center center",
                    __('Left Center', 'jupiter-donut') => "left center",
                    __('Right Center', 'jupiter-donut') => "right center",
                    __('Left Top', 'jupiter-donut') => "left top",
                    __('Center Top', 'jupiter-donut') => "center top",
                    __('Right Top', 'jupiter-donut') => "right top",
                    __('Left Bottom', 'jupiter-donut') => "left bottom",
                    __('Center Bottom', 'jupiter-donut') => "center bottom",
                    __('Right Bottom', 'jupiter-donut') => "right bottom"
                ) ,
                "description" => __("First value defines horizontal position and second vertical positioning.", 'jupiter-donut') ,
                 "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Background Repeat", 'jupiter-donut') ,
                "param_name" => "bg_repeat",
                "width" => 300,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => array(
                    __('No Repeat', 'jupiter-donut') => "no-repeat",
                    __('Repeat', 'jupiter-donut') => "repeat",
                    __('Horizontally repeat', 'jupiter-donut') => "repeat-x",
                    __('Vertically Repeat', 'jupiter-donut') => "repeat-y"
                ) ,
                "description" => __("", 'jupiter-donut') ,
                 "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,

            array(
                "type" => "dropdown",
                "heading" => __("Background Color Style", 'jupiter-donut') ,
                "param_name" => "background_hov_color_style",
                "group" => __('Hover Options', 'jupiter-donut') ,
                "value" => array(
                    __('None', 'jupiter-donut') => "none",
                    __('Image & Single Color', 'jupiter-donut') => "image",
                    __('Gradient Color', 'jupiter-donut') => "gradient_color",
                ) ,
                "description" => __("", 'jupiter-donut')
            ) ,

            array(
                "type" => "dropdown",
                "heading" => __("Border Color Style", 'jupiter-donut') ,
                "param_name" => "border_color_style",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => array(
                    __('None', 'jupiter-donut') => "none",
                    __('Single Color', 'jupiter-donut') => "single_color",
                    __('Gradient Color', 'jupiter-donut') => "gradient_color"
                ) ,
                "description" => __("", 'jupiter-donut')
            ) ,

            /**
             * Border Single Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Border Color", 'jupiter-donut') ,
                "param_name" => "border_color",
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'single_color'
                    )
                )
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Border Style", 'jupiter-donut') ,
                "param_name" => "border_style",
                "width" => 300,
                "value" => array(
                    __('Solid', 'jupiter-donut') => "solid",
                    __('Dashed', 'jupiter-donut') => "dashed",
                    __('Dotted', 'jupiter-donut') => "dotted",
                ) ,
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'single_color'
                    )
                )
            ) ,
            array(
                "type" => "range",
                "heading" => __("Border Width", 'jupiter-donut') ,
                "param_name" => "border_width",
                "value" => "1",
                "min" => "1",
                "max" => "50",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'single_color',
                        'gradient_color'
                    )
                )
            ) ,

            /**
             * Border Gradient Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("From", 'jupiter-donut') ,
                "param_name" => "border_grandient_color_from",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("To", 'jupiter-donut') ,
                "param_name" => "border_grandient_color_to",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Style", 'jupiter-donut') ,
                "param_name" => "border_gradient_color_style",
                "value" => array(
                    __('Linear', 'jupiter-donut') => "linear",
                    __('Radial', 'jupiter-donut') => "radial"
                ) ,
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Angle", 'jupiter-donut') ,
                "param_name" => "border_gradient_color_angle",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => array(
                    __('Vertical ↓', 'jupiter-donut') => "vertical",
                    __('Horizontal →', 'jupiter-donut') => "horizontal",
                    __('Diagonal ↘', 'jupiter-donut') => "diagonal_left_bottom",
                    __('Diagonal ↗', 'jupiter-donut') => "diagonal_left_top",
                ) ,
                "description" => __("", 'jupiter-donut') ,
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Gradient Fallback Color", 'jupiter-donut') ,
                "param_name" => "border_grandient_color_fallback",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Overlay Color", 'jupiter-donut') ,
                "param_name" => "overlay_color",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,

            array(
                "type" => "toggle",
                "heading" => __("Drop Shadow", 'jupiter-donut') ,
                "description" => __("Drop shadow option is disabled when the elevation effect is turned on.", 'jupiter-donut') ,
                "param_name" => "drop_shadow",
                "value" => 'false',
                "group" => __('Styles & Colors', 'jupiter-donut') ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Angle", 'jupiter-donut') ,
                "param_name" => "drop_shadow_angle",
                "value" => "45",
                "min" => "0",
                "max" => "360",
                "step" => "1",
                "unit" => '°',
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Distance", 'jupiter-donut') ,
                "param_name" => "drop_shadow_distance",
                "value" => "8",
                "min" => "1",
                "max" => "100",
                "step" => "1",
                "unit" => 'px',
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Blur", 'jupiter-donut') ,
                "param_name" => "drop_shadow_blur",
                "value" => "20",
                "min" => "0",
                "max" => "100",
                "step" => "1",
                "unit" => 'px',
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Color", 'jupiter-donut') ,
                "param_name" => "drop_shadow_color",
                "group" => __('Styles & Colors', 'jupiter-donut') ,
                "value" => "rgba(0, 0, 0, 0.5)",
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,



            /**
             * Background Hover Single Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Background Color", 'jupiter-donut') ,
                "param_name" => "bg_hov_color",
                "value" => "",
                "group" => __('Hover Options', 'jupiter-donut') ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'image'
                    )
                )
            ) ,

            /**
             * Background Gradient Hover Color
             * ==================================================================================
             */

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("From", 'jupiter-donut') ,
                "param_name" => "bg_grandient_hov_color_from",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'jupiter-donut') ,
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("To", 'jupiter-donut') ,
                "param_name" => "bg_grandient_hov_color_to",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'jupiter-donut') ,
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Style", 'jupiter-donut') ,
                "param_name" => "bg_gradient_hov_color_style",
                "value" => array(
                    __('Linear', 'jupiter-donut') => "linear",
                    __('Radial', 'jupiter-donut') => "radial"
                ) ,
                "group" => __('Hover Options', 'jupiter-donut') ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Angle", 'jupiter-donut') ,
                "param_name" => "bg_gradient_hov_color_angle",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'jupiter-donut') ,
                "value" => array(
                    __('Vertical ↓', 'jupiter-donut') => "w",
                    __('Horizontal →', 'jupiter-donut') => "horizontal",
                    __('Diagonal ↘', 'jupiter-donut') => "diagonal_left_bottom",
                    __('Diagonal ↗', 'jupiter-donut') => "diagonal_left_top",
                ) ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Gradient Fallback Color", 'jupiter-donut') ,
                "param_name" => "bg_grandient_hov_color_fallback",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'jupiter-donut') ,
                "value" => "",
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,

            /**
             * Background Image Hover Effect
             * ==================================================================================
             */
            array(
                "type" => "dropdown",
                "heading" => __("Background Image Effect", 'jupiter-donut') ,
                "param_name" => "bg_image_hov_effect",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'jupiter-donut') ,
                "value" => array(
                    __('None', 'jupiter-donut') => "none",
                    __('Zoom In', 'jupiter-donut') => "zoom-in",
                    __('Blur', 'jupiter-donut') => "blur",
                    __('Grayscale to Color', 'jupiter-donut') => "grayscale",
                ) ,
                "description" => __("", 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'image'
                    )
                ) ,
            ) ,

            array(
                "type" => "toggle",
                "heading" => __("Elevation effect", 'jupiter-donut') ,
                "param_name" => "elevation_effect",
                "value" => 'false',
                "group" => __('Hover Options', 'jupiter-donut') ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Distance", 'jupiter-donut') ,
                "param_name" => "elevation_effect_distance",
                "description" => __("The distance of custom box from the background when it is elevated.", 'jupiter-donut') ,
                "value" => "2",
                "min" => "2",
                "max" => "16",
                "step" => "1",
                "unit" => 'px',
                "group" => __('Hover Options', 'jupiter-donut') ,
                "dependency" => array(
                    'element' => "elevation_effect",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,


        ) ,
        "js_view" => 'VcColumnView'
    ));
