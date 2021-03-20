<?php
vc_map(array(
    "name" => __("Skill Meter", 'jupiter-donut') ,
    "base" => "mk_skill_meter",
	'html_template' => dirname( __FILE__ ) . '/mk_skill_meter.php',
    'icon' => 'icon-mk-skill-meter vc_mk_element-icon',
    'description' => __('Show skills in bars by percent.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("What skill are you demonstrating?", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Percent", 'jupiter-donut') ,
            "param_name" => "percent",
            "value" => "50",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("How many percent would you like to show for this skill bar?", 'jupiter-donut')
        ) ,

        array(
            "type" => "range",
            "heading" => __("Bar Thickness", 'jupiter-donut') ,
            "param_name" => "line_height",
            "value" => "22",
            "min" => "1",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Title Text Color", 'jupiter-donut') ,
            "param_name" => "txt_color",
            "value" => '',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Percentage Text Color", 'jupiter-donut') ,
            "param_name" => "percent_color",
            "value" => 'rgba(0,0,0,0.5)',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Bar Track Color", 'jupiter-donut') ,
            "param_name" => "bar_color",
            "value" => 'rgba(0,0,0,0.12)',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Bar Progress Color", 'jupiter-donut') ,
            "param_name" => "color",
            "value" => $skin_color,
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
