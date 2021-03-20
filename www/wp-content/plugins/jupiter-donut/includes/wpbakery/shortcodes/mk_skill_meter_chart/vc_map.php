<?php

vc_map(array(
    "name" => __("Diagram Progress Bar", 'jupiter-donut') ,
    "base" => "mk_skill_meter_chart",
	'html_template' => dirname( __FILE__ ) . '/mk_skill_meter_chart.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-diagram-progress-bar vc_mk_element-icon',
    'description' => __('Show skills & data in diagram charts.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "range",
            "heading" => __("Data 1 : Percent", 'jupiter-donut') ,
            "param_name" => "percent_1",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("Measure your data in percent", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Data 1 : Arch Color", 'jupiter-donut') ,
            "param_name" => "color_1",
            "value" => "#e74c3c",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Data 1 : Name", 'jupiter-donut') ,
            "param_name" => "name_1",
            "value" => "",
            "margin_bottom" => 40,
            "description" => __("The name of data you are demonstrating", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Data 2 : Percent", 'jupiter-donut') ,
            "param_name" => "percent_2",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("Measure your data in percent", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Data 2 : Arch Color", 'jupiter-donut') ,
            "param_name" => "color_2",
            "value" => "#8c6645",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Data 2 : Name", 'jupiter-donut') ,
            "param_name" => "name_2",
            "value" => "",
            "margin_bottom" => 40,
            "description" => __("The name of data you are demonstrating", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Data 3 : Percent", 'jupiter-donut') ,
            "param_name" => "percent_3",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("Measure your data in percent", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Data 3 : Arch Color", 'jupiter-donut') ,
            "param_name" => "color_3",
            "value" => "#265573",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Data 3 : Name", 'jupiter-donut') ,
            "param_name" => "name_3",
            "value" => "",
            "margin_bottom" => 40,
            "description" => __("The name of data you are demonstrating", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Data 4 : Percent", 'jupiter-donut') ,
            "param_name" => "percent_4",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("Measure your data in percent", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Data 4 : Arch Color", 'jupiter-donut') ,
            "param_name" => "color_4",
            "value" => "#008b83",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Data 4 : Name", 'jupiter-donut') ,
            "param_name" => "name_4",
            "value" => "",
            "margin_bottom" => 40,
            "description" => __("The name of data you are demonstrating", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Data 5 : Percent", 'jupiter-donut') ,
            "param_name" => "percent_5",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("Measure your data in percent", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Data 5 : Arch Color", 'jupiter-donut') ,
            "param_name" => "color_5",
            "value" => "#d96b52",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Data 5 : Name", 'jupiter-donut') ,
            "param_name" => "name_5",
            "value" => "",
            "margin_bottom" => 40,
            "description" => __("The name of data you are demonstrating", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Data 6 : Percent", 'jupiter-donut') ,
            "param_name" => "percent_6",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("Measure your data in percent", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Data 6 : Arch Color", 'jupiter-donut') ,
            "param_name" => "color_6",
            "value" => "#82bf56",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Data 6 : Name", 'jupiter-donut') ,
            "param_name" => "name_6",
            "value" => "",
            "margin_bottom" => 40,
            "description" => __("The name of data you are demonstrating", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Data 7 : Percent", 'jupiter-donut') ,
            "param_name" => "percent_7",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%',
            "description" => __("Measure your data in percent", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Data 7 : Arch Color", 'jupiter-donut') ,
            "param_name" => "color_7",
            "value" => "#4ecdc4",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Data 7 : Name", 'jupiter-donut') ,
            "param_name" => "name_7",
            "value" => "",
            "margin_bottom" => 40,
            "description" => __("The name of data you are demonstrating", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Default Text", 'jupiter-donut') ,
            "param_name" => "default_text",
            "value" => "Skill",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Center Circle Background Color", 'jupiter-donut') ,
            "param_name" => "center_color",
            "value" => "#1e3641",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Default Text Color", 'jupiter-donut') ,
            "param_name" => "default_text_color",
            "value" => "#fff",
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
