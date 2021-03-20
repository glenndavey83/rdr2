<?php
vc_map(array(
    "name" => __("Banner Builder", 'jupiter-donut'),
    "base" => "mk_banner_builder",
	'html_template' => dirname( __FILE__ ) . '/mk_banner_builder.php',
    'icon' => 'icon-mk-custom-box vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut'),
    'description' => __( 'Banner Builder.', 'jupiter-donut' ),
    //'deprecated' => '4.9',
    "params" => array(
         array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Posts', 'jupiter-donut' ),
            'param_name'  => 'slides',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                                // In UI show results except selected. NB! You should manually check values in backend
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
        ),
        array(
            "type" => "range",
            "heading" => __("Min Height", 'jupiter-donut'),
            "param_name" => "height",
            "value" => "200",
            "min" => "50",
            "max" => "1200",
            "step" => "1",
            "unit" => 'px',
            "description" => __("You can adjust a min height to avoid height changes between your slide items which may distract the viewer.", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Top & Bottom Padding", 'jupiter-donut'),
            "param_name" => "padding",
            "value" => "30",
            "min" => "0",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("This option will help you to give your own custom vertical spacing.", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Animation Style", 'jupiter-donut'),
            "param_name" => "animation_style",
            "width" => 300,
            "value" => array(
                __('Fade', 'jupiter-donut') => "fade",
                __('Slide', 'jupiter-donut') => "slide"
            ),
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "heading" => __("Order", 'jupiter-donut'),
            "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut'),
            "param_name" => "order",
            "value" => array(
                __("ASC (ascending order)", 'jupiter-donut') => "ASC",
                __("DESC (descending order)", 'jupiter-donut') => "DESC"
            ),
            "type" => "dropdown"
        ),
        array(
            "heading" => __("Orderby", 'jupiter-donut'),
            "description" => __("Sort retrieved slides items by parameter.", 'jupiter-donut'),
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ),

        array(
            "type" => "toggle",
            "heading" => __("Autoplay", 'jupiter-donut'),
            "param_name" => "autoplay",
            "value" => "true",
            "description" => __("Enable this option if you would like slider to autoplay.", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Slideshow Speed", 'jupiter-donut'),
            "param_name" => "slideshow_speed",
            "value" => "5000",
            "min" => "2000",
            "max" => "20000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("Time elapsed between each autoplay sliding case.", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Animation Duration", 'jupiter-donut'),
            "param_name" => "animation_duration",
            "value" => "600",
            "min" => "200",
            "max" => "10000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("Speed of animation.", 'jupiter-donut')
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
