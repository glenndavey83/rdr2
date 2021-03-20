<?php
    vc_map(array(
        "name" => __("Animated Columns", 'jupiter-donut') ,
        "base" => "mk_animated_columns",
		'html_template' => dirname( __FILE__ ) . '/mk_animated_columns.php',
        'icon' => 'icon-mk-animated-columns vc_mk_element-icon',
        'description' => __('Columns with cool animations.', 'jupiter-donut') ,
        "category" => __('General', 'jupiter-donut') ,
        "params" => array(
            array(
                "type" => "range",
                "heading" => __("Column Height", 'jupiter-donut') ,
                "param_name" => "column_height",
                "value" => "500",
                "min" => "100",
                "max" => "1200",
                "step" => "1",
                "unit" => 'px',
                "description" => __("Set the columns height", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("How many Columns?", 'jupiter-donut') ,
                "param_name" => "column_number",
                "value" => "4",
                "min" => "1",
                "max" => "8",
                "step" => "1",
                "unit" => 'columns',
                "description" => __("How many columns would you like to show in one row?", 'jupiter-donut')
            ) ,

            array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Animated Columns', 'jupiter-donut' ),
            'param_name'  => 'columns',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
        ),

            array(
                "heading" => __("Order", 'jupiter-donut') ,
                "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut') ,
                "param_name" => "order",
                "value" => array(
                    __("DESC (descending order)", 'jupiter-donut') => "DESC",
                    __("ASC (ascending order)", 'jupiter-donut') => "ASC"
                ) ,
                "type" => "dropdown"
            ) ,
            array(
                "heading" => __("Orderby", 'jupiter-donut') ,
                "description" => __("Sort retrieved pricing items by parameter.", 'jupiter-donut') ,
                "param_name" => "orderby",
                "value" => $mk_orderby,
                "type" => "dropdown"
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Column Content & Style", 'jupiter-donut') ,
                "param_name" => "style",
                "value" => array(
                    "Full featured (All content)" => "full",
                    "Simple (Icon + Title)" => "simple",
                ) ,
                "description" => __("Choose what type of content should be placed inside columns. Each style has different content and hover scenarios.", 'jupiter-donut')
            ) ,
            array(
                'type' => 'range',
                "heading" => __("Title Font Size", 'jupiter-donut') ,
                "param_name" => "title_size",
                "value" => "20",
                "min" => "9",
                "max" => "60",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Columns Border Color", 'jupiter-donut') ,
                "param_name" => "border_color",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Columns background Color", 'jupiter-donut') ,
                "param_name" => "bg_color",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Columns background Hover Color", 'jupiter-donut') ,
                "param_name" => "bg_hover_color",
                "value" => "",
                "description" => __("Columns background color will change to this color once the user's mouse rolls over on a particular column.", 'jupiter-donut')
            ) ,

            array(
                "type" => "dropdown",
                "heading" => __("Icon Size", 'jupiter-donut') ,
                "param_name" => "icon_size",
                "value" => array(
                    __('16px', 'jupiter-donut') => "16",
                    __('32px', 'jupiter-donut') => "32",
                    __('48px', 'jupiter-donut') => "48",
                    __('64px', 'jupiter-donut') => "64",
                    __('128px', 'jupiter-donut') => "128"
                ) ,
                "description" => __("Choose the icon size by pixel.", 'jupiter-donut')
            ) ,

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Icon Color", 'jupiter-donut') ,
                "param_name" => "icon_color",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Icon Hover Color", 'jupiter-donut') ,
                "param_name" => "icon_hover_color",
                "value" => "",
                "description" => __("Columns Icon color will change to this color once the user's mouse rolls over on a particular column.", 'jupiter-donut')
            ) ,

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Text Color (Active)", 'jupiter-donut') ,
                "param_name" => "txt_color",
                "value" => "",
                "description" => __("The color of title and description inside the column. Description text though, is 70% translucent.", 'jupiter-donut')
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Text Color (Hover)", 'jupiter-donut') ,
                "param_name" => "txt_hover_color",
                "value" => "",
                "description" => __("Column's title and description color will change to this color once the user's mouse rolls over on a particular column.", 'jupiter-donut')
            ) ,

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Button Color (Active)", 'jupiter-donut') ,
                "param_name" => "btn_color",
                "value" => "",
                "description" => __("The color of button inside the column.", 'jupiter-donut')
            ) ,

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Button Color (Hover)", 'jupiter-donut') ,
                "param_name" => "btn_hover_color",
                "value" => "",
                "description" => __("Column's button color will change to this color once the user's mouse rolls over on a particular column.", 'jupiter-donut')
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Button Text Color (Hover)", 'jupiter-donut') ,
                "param_name" => "btn_hover_txt_color",
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
