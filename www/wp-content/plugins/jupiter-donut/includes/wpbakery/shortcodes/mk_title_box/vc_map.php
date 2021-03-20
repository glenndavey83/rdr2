<?php
vc_map(array(
    "name" => __("Title Box", 'jupiter-donut') ,
    "base" => "mk_title_box",
	'html_template' => dirname( __FILE__ ) . '/mk_title_box.php',
    "category" => __('Typography', 'jupiter-donut') ,
    'icon' => 'icon-mk-title-box vc_mk_element-icon',
    'description' => __('Adds title text into a highlight box.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textarea_html",
            "rows" => 2,
            "holder" => "div",
            "heading" => __("Content.", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("Allowed Tags [br] [strong] [i] [u] [b] [a] [small]. Please note that [p] tags will be striped out.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Text Color", 'jupiter-donut') ,
            "param_name" => "color",
            "value" => "#393836",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Highlight Background Color", 'jupiter-donut') ,
            "param_name" => "highlight_color",
            "value" => "",
            "description" => __("The Highlight Background color. you can change color opacity from below option.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Highlight Color Opacity", 'jupiter-donut') ,
            "param_name" => "highlight_opacity",
            "value" => "0.3",
            "min" => "0",
            "max" => "1",
            "step" => "0.01",
            "unit" => '',
            "description" => __("The Opacity of the highlight background", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Font Size", 'jupiter-donut') ,
            "param_name" => "size",
            "value" => "18",
            "min" => "12",
            "max" => "70",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Line Height (Important)", 'jupiter-donut') ,
            "param_name" => "line_height",
            "value" => "34",
            "min" => "12",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("Since every font family with differnt sizes need different line heights to get a nice looking highlighted titles you should set them manually. as a hint generally (font-size * 2) - 2 works in many cases, but you may need to give more space in between, so we opened your hands with this option. :) ", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Font Weight", 'jupiter-donut') ,
            "param_name" => "font_weight",
            "value" => $font_weight,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Letter Spacing", 'jupiter-donut') ,
            "param_name" => "letter_spacing",
            "value" => "0",
            "min" => "0",
            "max" => "10",
            "step" => "1",
            "unit" => 'px',
            "description" => __("Space between each character.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Stroke Width", 'jupiter-donut') ,
            "param_name" => "stroke",
            "value" => "0",
            "min" => "0",
            "max" => "10",
            "step" => "1",
            "unit" => 'px',
            "description" => __("Using this option you can set a frame around the title.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Stroke Color", 'jupiter-donut') ,
            "param_name" => "stroke_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Top", 'jupiter-donut') ,
            "param_name" => "margin_top",
            "value" => "0",
            "min" => "-40",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("In some ocasions you may on need to define a top margin for this title.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", 'jupiter-donut') ,
            "param_name" => "margin_bottom",
            "value" => "20",
            "min" => "0",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
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
