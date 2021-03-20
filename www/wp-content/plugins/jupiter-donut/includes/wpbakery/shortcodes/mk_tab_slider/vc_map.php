<?php
vc_map(array(
    "name" => __("Tab Slider", 'jupiter-donut') ,
    "base" => "mk_tab_slider",
	'html_template' => dirname( __FILE__ ) . '/mk_tab_slider.php',
    'icon' => 'icon-mk-tab-slider vc_mk_element-icon',
    "category" => __('General', 'jupiter-donut') ,
    'description' => __('Tab based slider.', 'jupiter-donut') ,
    "params" => array(
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Tabs', 'jupiter-donut' ),
            'param_name'  => 'tabs',
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
            "description" => __("Sort retrieved slides items by parameter.", 'jupiter-donut') ,
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ) ,
        array(
            "heading" => __("Slideshow Speed", 'jupiter-donut') ,
            "param_name" => "autoplay_time",
            "value" => "5000",
            "min" => "0",
            "max" => "50000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("If set to zero the autoplay will be disabled, any number above zeo will define the delay between each slide transition.", 'jupiter-donut') ,
            'type' => 'range'
        ) ,
        $add_css_animations,
        array(
            "heading" => __("Navigation Button Size", 'jupiter-donut') ,
            "param_name" => "button_size",
            "value" => "20",
            "min" => "15",
            "max" => "30",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            'type' => 'range'
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Navigation Button Color", 'jupiter-donut') ,
            "param_name" => "button_color",
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
