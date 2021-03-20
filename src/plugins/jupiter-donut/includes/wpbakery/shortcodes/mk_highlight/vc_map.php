<?php
vc_map(array(
    "name" => __("Highlight Text", 'jupiter-donut') ,
    "base" => "mk_highlight",
	'html_template' => dirname( __FILE__ ) . '/mk_highlight.php',
    'icon' => 'icon-mk-highlight vc_mk_element-icon',
    "category" => __('Typography', 'jupiter-donut') ,
    'description' => __('adds highlight to an inline text.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Highlight Text", 'jupiter-donut') ,
            "param_name" => "text",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Text Color", 'jupiter-donut') ,
            "param_name" => "text_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Background Color", 'jupiter-donut') ,
            "param_name" => "bg_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,

        /*        array(
                  "type" => "fonts",
                  "heading" => __("Font Family", 'jupiter-donut'),
                  "param_name" => "font_family",
                  "value" => "",
                  "description" => __("You can choose a font for this shortcode, however using non-safe fonts can affect page load and performance.", 'jupiter-donut')
              ),
              array(
                  "type" => "hidden_input",
                  "param_name" => "font_type",
                  "value" => "",
                  "description" => __("", 'jupiter-donut')
              ),
        */
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
