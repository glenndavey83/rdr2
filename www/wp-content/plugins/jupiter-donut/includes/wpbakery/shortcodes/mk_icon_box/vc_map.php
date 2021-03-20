<?php

vc_map(array(
    "name" => __("Icon Box", 'jupiter-donut') ,
    "base" => "mk_icon_box",
	'html_template' => dirname( __FILE__ ) . '/mk_icon_box.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-icon-box vc_mk_element-icon',
    'description' => __('Powerful & versatile Icon Boxes.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "icon_selector",
            "heading" => '' ,
            "param_name" => "icon",
            "value" => "mk-li-smile",
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
            "param_name" => "text_size",
            "value" => "16",
            "min" => "10",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Title Font Weight", 'jupiter-donut') ,
            "param_name" => "font_weight",
            "value" => $font_weight,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "heading" => __("Description", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("Enter your content.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Read More Text", 'jupiter-donut') ,
            "param_name" => "read_more_txt",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Read More URL", 'jupiter-donut') ,
            "param_name" => "read_more_url",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut') ,
            "param_name" => "style",
            "value" => array(
                __('Simple Minimal', 'jupiter-donut') => "simple_minimal",
                __('Simple Ultimate', 'jupiter-donut') => "simple_ultimate",
                __('Boxed', 'jupiter-donut') => "boxed"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Icon Size", 'jupiter-donut') ,
            "param_name" => "icon_size",
            "value" => array(
                __('Small', 'jupiter-donut') => "small",
                __('Medium', 'jupiter-donut') => "medium",
                __('Large', 'jupiter-donut') => "large",
                __('X-large', 'jupiter-donut') => "x-large"
            ) ,
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'simple_ultimate',
                    'simple_minimal'
                )
            )
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Circle container", 'jupiter-donut') ,
            "param_name" => "rounded_circle",
            "value" => "false",
            "description" => __("Enable this option if you want your icon to be contained by a circle. This option will only work for icon size of Small and Medium.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'simple_ultimate'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Icon Location", 'jupiter-donut') ,
            "param_name" => "icon_location",
            "value" => array(
                __('Left', 'jupiter-donut') => "left",
                __('Top', 'jupiter-donut') => "top"
            ) ,
            "description" => __("The horizontal and vertical location of Icon related to the box content", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'simple_ultimate',
                    'boxed'
                )
            )
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Circle container", 'jupiter-donut') ,
            "param_name" => "circled",
            "value" => "false",
            "description" => __("Enable this option if you want your icon to be contained by a circle.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'simple_minimal'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Color", 'jupiter-donut') ,
            "param_name" => "icon_color",
            "value" => jupiter_donut_get_option( 'skin_color' ),
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Container (circle) Background Color", 'jupiter-donut') ,
            "param_name" => "icon_circle_color",
            "value" => jupiter_donut_get_option( 'skin_color' ),
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'boxed',
                    'simple_minimal'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Container (circle) Border Color", 'jupiter-donut') ,
            "param_name" => "icon_circle_border_color",
            "value" => "",
            "description" => __("Optionally you can set a border for icon circle container. To disable border just leave this field blank.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'boxed',
                    'simple_minimal'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Title Color", 'jupiter-donut') ,
            "param_name" => "title_color",
            "value" => "",
            "description" => __("Optionally you can modify Title color inside this shortcode.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Paragraph Color", 'jupiter-donut') ,
            "param_name" => "txt_color",
            "value" => "",
            "description" => __("Optionally you can modify text color inside this shortcode.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Paragraph Link Color", 'jupiter-donut') ,
            "param_name" => "txt_link_color",
            "value" => "",
            "description" => __("Optionally you can modify links color that are inside description.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", 'jupiter-donut') ,
            "param_name" => "margin",
            "value" => "30",
            "min" => "0",
            "max" => "500",
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
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.", 'jupiter-donut')
        )
    )
));
