<?php

vc_map(array(
    "name" => __("Process Builder", 'jupiter-donut') ,
    "base" => "mk_steps",
	'html_template' => dirname( __FILE__ ) . '/mk_steps.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-process-builder vc_mk_element-icon',
    'description' => __('Adds process steps element.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("How Many Steps?", 'jupiter-donut') ,
            "param_name" => "step",
            "value" => "4",
            "min" => "2",
            "max" => "5",
            "step" => "1",
            "unit" => 'step',
            "description" => __("How many steps for the whole process? Each represented in a circular container.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Container Hover Color", 'jupiter-donut') ,
            "param_name" => "hover_color",
            "value" => $skin_color,
            "description" => __("This color will be showed up once user rolls over the circular container.", 'jupiter-donut')
        ) ,
        array(
            "type" => "icon_selector",
            "heading" => __("Step 1 : Add Icon", 'jupiter-donut') ,
            "param_name" => "icon_1",
            "value" => "",
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 1 : Title", 'jupiter-donut') ,
            "param_name" => "title_1",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 1 : Description", 'jupiter-donut') ,
            "param_name" => "desc_1",
            'margin_bottom' => 40,
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 1 : Link", 'jupiter-donut') ,
            "param_name" => "url_1",
            'margin_bottom' => 30,
            "value" => "",
            "description" => __("If you add a URL the title will be converted to a link. add http://", 'jupiter-donut')
        ) ,
        array(
            "type" => "icon_selector",
            "heading" => __("Step 2 : Add Icon", 'jupiter-donut') ,
            "param_name" => "icon_2",
            "value" => "",
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 2 : Title", 'jupiter-donut') ,
            "param_name" => "title_2",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 2 : Description", 'jupiter-donut') ,
            "param_name" => "desc_2",
            'margin_bottom' => 40,
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 2 : Link", 'jupiter-donut') ,
            "param_name" => "url_2",
            'margin_bottom' => 30,
            "value" => "",
            "description" => __("If you add a URL the title will be converted to a link. add http://", 'jupiter-donut')
        ) ,
        array(
            "type" => "icon_selector",
            "heading" => __("Step 3 : Add Icon", 'jupiter-donut') ,
            "param_name" => "icon_3",
            "value" => "",
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 3 : Title", 'jupiter-donut') ,
            "param_name" => "title_3",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 3 : Description", 'jupiter-donut') ,
            "param_name" => "desc_3",
            'margin_bottom' => 40,
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 3 : Link", 'jupiter-donut') ,
            "param_name" => "url_3",
            'margin_bottom' => 30,
            "value" => "",
            "description" => __("If you add a URL the title will be converted to a link. add http://", 'jupiter-donut')
        ) ,
        array(
            "type" => "icon_selector",
            "heading" => __("Step 4 : Add Icon", 'jupiter-donut') ,
            "param_name" => "icon_4",
            "value" => "",
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 4 : Title", 'jupiter-donut') ,
            "param_name" => "title_4",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 4 : Description", 'jupiter-donut') ,
            "param_name" => "desc_4",
            'margin_bottom' => 40,
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 4 : Link", 'jupiter-donut') ,
            "param_name" => "url_4",
            'margin_bottom' => 30,
            "value" => "",
            "description" => __("If you add a URL the title will be converted to a link. add http://", 'jupiter-donut')
        ) ,
        array(
            "type" => "icon_selector",
            "heading" => __("Step 5 : Add Icon", 'jupiter-donut') ,
            "param_name" => "icon_5",
            "value" => "",
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 5 : Title", 'jupiter-donut') ,
            "param_name" => "title_5",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 5 : Description", 'jupiter-donut') ,
            "param_name" => "desc_5",
            'margin_bottom' => 40,
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Step 5 : Link", 'jupiter-donut') ,
            "param_name" => "url_5",
            'margin_bottom' => 30,
            "value" => "",
            "description" => __("If you add a URL the title will be converted to a link. add http://", 'jupiter-donut')
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
