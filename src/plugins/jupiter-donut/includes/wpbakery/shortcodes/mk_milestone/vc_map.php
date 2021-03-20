<?php
vc_map(array(
    "name" => __("Milestones", 'jupiter-donut') ,
    "base" => "mk_milestone",
	'html_template' => dirname( __FILE__ ) . '/mk_milestone.php',
    'icon' => 'icon-mk-milestone vc_mk_element-icon',
    'description' => __('Milestone numbers to show statistics.', 'jupiter-donut') ,
    "category" => __('General', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "icon_selector",
            "heading" => __("Add Icon", 'jupiter-donut') ,
            "param_name" => "icon",
            "value" => "",
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Icon & Text Size", 'jupiter-donut') ,
            "param_name" => "icon_size",
            "value" => array(
                __("Small", 'jupiter-donut') => "small",
                __("Medium", 'jupiter-donut') => "medium",
                __("Large", 'jupiter-donut') => "large"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Align", 'jupiter-donut') ,
            "param_name" => "align",
            "value" => array(
                __("Left", 'jupiter-donut') => "left",
                __("center", 'jupiter-donut') => "center",
                __("right", 'jupiter-donut') => "right",
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Color", 'jupiter-donut') ,
            "param_name" => "icon_color",
            "value" => $skin_color,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Number Starts at:", 'jupiter-donut') ,
            "param_name" => "start",
            "value" => "0",
            "min" => "0",
            "max" => "100000",
            "step" => "1",
            "unit" => '',
            "description" => __("Choose at which number it should start.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Number Stops at:", 'jupiter-donut') ,
            "param_name" => "stop",
            "value" => "100",
            "min" => "0",
            "max" => "100000",
            "step" => "1",
            "unit" => '',
            "description" => __("Choose at which number it should Stop.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Speed", 'jupiter-donut') ,
            "param_name" => "speed",
            "value" => "2000",
            "min" => "0",
            "max" => "10000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("Speed of the animation from start to stop in milliseconds.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Number Prefix", 'jupiter-donut') ,
            "param_name" => "prefix",
            "value" => "",
            "description" => __("This text goes before the Number.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Number Suffix", 'jupiter-donut') ,
            "param_name" => "suffix",
            "value" => "",
            "description" => __("This text goes after the Number.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Description", 'jupiter-donut') ,
            "param_name" => "text",
            "value" => "",
            "description" => __("Description that goes below the Number.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Text Color", 'jupiter-donut') ,
            "param_name" => "text_color",
            "value" => "",
            "description" => __("This option will affect Prefix, suffix, number and description.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Number Text Size (Number, Prefix and Suffix)", 'jupiter-donut') ,
            "param_name" => "text_size",
            "value" => "0",
            "min" => "12",
            "max" => "100",
            "step" => "1",
            "unit" => '',
            "description" => __("Text Size will change based on \"Icon & Text Size\" option, however you can set a custom size using this option.", 'jupiter-donut')
        ) ,
        array(
            "type" => "theme_fonts",
            "heading" => __("Font Family", 'jupiter-donut') ,
            "param_name" => "font_family",
            "value" => "",
            "description" => __("You can choose a font for this shortcode, however using non-safe fonts can affect page load and performance.", 'jupiter-donut')
        ) ,
        array(
            "type" => "hidden_input",
            "param_name" => "font_type",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Number Font Weight", 'jupiter-donut') ,
            "param_name" => "font_weight",
            "value" => $font_weight,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Description Text Size", 'jupiter-donut') ,
            "param_name" => "desc_size",
            "value" => "14",
            "min" => "10",
            "max" => "100",
            "step" => "1",
            "unit" => '',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Link (optional)", 'jupiter-donut') ,
            "param_name" => "link",
            "value" => "",
            "description" => __("", 'jupiter-donut')
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
