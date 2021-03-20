<?php
vc_map(array(
    "name" => __("Padding Space", 'jupiter-donut') ,
    "base" => "mk_padding_divider",
	'html_template' => dirname( __FILE__ ) . '/mk_padding_divider.php',
    'icon' => 'icon-mk-padding-space vc_mk_element-icon',
    "category" => __('General', 'jupiter-donut') ,
    'description' => __('Adds space between elements', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "range",
            "heading" => __("Padding Size (Px)", 'jupiter-donut') ,
            "param_name" => "size",
            "value" => "40",
            "min" => "0",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("How much empty space would you like to add?", 'jupiter-donut')
        ),
        $add_device_visibility
    )
));
