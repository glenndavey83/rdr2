<?php
vc_map(array(
    "name" => __("Toggle", 'jupiter-donut'),
    "base" => "mk_toggle",
	'html_template' => dirname( __FILE__ ) . '/mk_toggle.php',
    "wrapper_class" => "jupiter-donut-clearfix",
    'icon' => 'icon-mk-toggle vc_mk_element-icon',
    "category" => __('Typography', 'jupiter-donut'),
    'description' => __( 'Expandable toggle element', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut'),
            "param_name" => "style",
            "width" => 150,
            "value" => array(
                __('Simple', 'jupiter-donut') => "simple",
                __('Fancy', 'jupiter-donut') => "fancy"
            ),
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Toggle Title", 'jupiter-donut'),
            "param_name" => "title",
            "value" => ""
        ),
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "heading" => __("Toggle Content.", 'jupiter-donut'),
            "param_name" => "content",
            "value" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "icon_selector",
            "heading" => __("Add Icon for Title", 'jupiter-donut'),
            "param_name" => "icon",
            "value" => "",
             "dependency" => array(
                'element' => "style",
                'value' => array(
                    'fancy'
                )
            ),
        ),
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
